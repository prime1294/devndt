<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Api;
use Sentinel;
use App\User;
use App\Model\Business;
use App\Model\Product;
use App\Model\Plan;
use Illuminate\Support\Facades\DB;
use App\Model\Activity;

class ProductController extends Controller
{
    public function registerProduct(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'business_id' => 'required|exists:business_profile,id',
          'name' => 'required',
          'description' => 'required',
          'logo' => 'required | mimes:jpeg,png,jpg | max:50000',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      //check limit is not exeed
      $count = Product::where('business_id',request('business_id'))->where('status',1)->count();
      $business = Business::from('business_profile as b')->where('b.id',request('business_id'))->first();
      $current_plan = $business->plan_id;
      $plan = Plan::find($current_plan);
      if($plan->product_capping <= $count)
      {
        return Api::apiresponse("false","Maximum product upload limit reach. Please upgrade your plan");
      }

      $insert['business_id'] = request('business_id');
      $insert['name'] = request('name');
      $insert['description'] = request('description');
      $insert['status'] = 1;

      if ($request->hasFile('logo')) {
        $dir = 'image/product/';
        $image = $request->file('logo');
        $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path($dir);
        $imagepath = $image->move($destinationPath, $name);
        $insert['logo'] = $dir.$name;
      }

      $product = Product::insertGetId($insert);
      if($product) {
        $insert['id'] = $product;

        if($request->hasfile('images'))
        {
          $dir = 'image/product_images/';
          foreach($request->file('images') as $image) {
              $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
              $destinationPath = public_path($dir);
              $imagepath = $image->move($destinationPath, $name);
              DB::table('product_images')->insert([
                "product_id" => $product,
                "image" => $dir.$name,
                "status" => 1
              ]);
          }
        }

        if($request->has('veriation_key') && $request->has('veriation_value'))
        {
          $veriation_key_ctn = count(request('veriation_key'));
          $veriation_value_ctn = count(request('veriation_value'));

          if($veriation_key_ctn != $veriation_value_ctn) {
            return Api::apiresponse("false","Product variations mismatch");
          }

          $value = request('veriation_value');
          foreach(request('veriation_key') as $key=>$keys) {
            // echo $keys." = ".$value[$key]."<br>";
            DB::table('product_attrib')->insert([
              "product_id" => $product,
              "title" => $keys,
              "description" => $value[$key]
            ]);
          }
        }

        return Api::apiresponse("true","success",$insert);

      } else {
        return Api::apiresponse("false","Oops..! Something went wrong");
      }
    }

    public function updateProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:product,id',
            'business_id' => 'required|exists:business_profile,id',
            'name' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
          $errors = $validator->errors();
          return Api::apiresponse("false",$errors->first());
        }

        $update["name"] = request('name');
        $update["description"] = request('description');

        if ($request->hasFile('logo')) {
          $dir = 'image/product/';
          $image = $request->file('logo');
          $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
          $destinationPath = public_path($dir);
          $imagepath = $image->move($destinationPath, $name);
          $update['logo'] = $dir.$name;
        }

        //get product info
        $product = Product::where('business_id',request('business_id'))->where('id',request('product_id'))->update($update);
        if($product) {

          //remove images comma seprated
          if(request('remove_images')) {
          $remove_images = explode(',',request('remove_images'));
          foreach($remove_images as $row) {
            $rimage = DB::table('product_images')->where('id',$row)->where('product_id',request('product_id'));
            unlink(public_path($rimage->first()->image));
            $rimage->delete();
          }
          }

          //insert new images
          //print_r($_FILES);
          if($request->hasfile('images'))
          {
            $dir = 'image/product_images/';
            foreach($request->file('images') as $image) {
                $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path($dir);
                $imagepath = $image->move($destinationPath, $name);
                DB::table('product_images')->insert([
                  "product_id" => request('product_id'),
                  "image" => $dir.$name,
                  "status" => 1
                ]);
            }
          }


          //remove all product veriations
          DB::table('product_attrib')->where('product_id',request('product_id'))->delete();

          //reinsert all veriation
          if($request->has('veriation_key') && $request->has('veriation_value'))
          {
            $veriation_key_ctn = count(request('veriation_key'));
            $veriation_value_ctn = count(request('veriation_value'));

            if($veriation_key_ctn != $veriation_value_ctn) {
              return Api::apiresponse("false","Product variations mismatch");
            }

            $value = request('veriation_value');
            foreach(request('veriation_key') as $key=>$keys) {
              DB::table('product_attrib')->insert([
                "product_id" => request('product_id'),
                "title" => $keys,
                "description" => $value[$key]
              ]);
            }
          }

          $info = Product::where('business_id',request('business_id'))->where('id',request('product_id'))->first();
          return Api::apiresponse("true","success",$info);

        } else {
          return Api::apiresponse("false","Unauthorized access. Please try again.");
        }
    }


    //get all product list based on Category level and Business ID
    public function getProductList(Request $request)
    {
      //assign search type
      $search_by = "business_id";
      if(request('category_id1')) {
        $search_by = "category";
      }

      //validation of search by based on search type
      if($search_by == "category")
      {
        $validation = [
            'category_id1' => 'required|exists:business_category,id',
            'category_id2' => 'required|exists:business_category,id',
            'user_id' => 'required|exists:users,id',
        ];

      } else {
        $validation = [
            'business_id' => 'required|exists:business_profile,id',
            'user_id' => 'required|exists:users,id',
        ];
      }

      if(request('sort_by')) {
        $validation['sort_by'] = 'required|in:name,view,favourite';
      }

      $validator = Validator::make($request->all(), $validation);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }


      if(request('sort_by') == "name"){
        $sort_by = "product.name";
        $short_type = "ASC";
      } elseif(request('sort_by') == "view") {
        $sort_by = "product.views";
        $short_type = "DESC";
      } elseif(request('sort_by') == "favourite") {
        $sort_by = "product.favourite";
        $short_type = "DESC";
      } else {
        $sort_by = "product.id";
        $short_type = "DESC";
      }

      //fetch data based on search type
      $fields = array('product.id','product.business_id','product.name','product.description','product.logo','product.views','c1.name as category_name1','c2.name as category_name2');
      if($search_by == "category")
      {
        $list = Product::select($fields)
        ->leftJoin('business_profile as b', 'product.business_id', '=', 'b.id')
        ->leftJoin('business_category as c1', 'b.category_id1', '=', 'c1.id')
        ->leftJoin('business_category as c2', 'b.category_id2', '=', 'c2.id')
        ->where('b.category_id1',request('category_id1'))->where('b.category_id2',request('category_id2'))->where('product.status',1)->where('b.status',1)
        // ->groupBy('product.id')
        ->orderBy($sort_by, $short_type)->paginate(20)->toArray();
      } else {
        $list = Product::select($fields)
        ->leftJoin('business_profile as b', 'product.business_id', '=', 'b.id')
        ->leftJoin('business_category as c1', 'b.category_id1', '=', 'c1.id')
        ->leftJoin('business_category as c2', 'b.category_id2', '=', 'c2.id')
        ->where('product.business_id',request('business_id'))->where('product.status',1)->where('b.status',1)
        // ->groupBy('product.id')
        ->orderBy($sort_by,$short_type)->paginate(20)->toArray();
      }

      //send status based on your result
      $temp = [];
      if(!empty($list['data'])) {

        //loop video
        foreach($list['data'] as $row) {
          $row['is_user_fav'] = self::checkUserAddedInFav(request('user_id'),$row['id']);
          array_push($temp,$row);
        }

        //reassign value
        $list['data'] = $temp;
        return Api::apiresponse("true","success",$list);
      } else {
        return Api::apiresponse("false","no data found");
      }
    }

    //product details
    public function getProductDetail(Request $request) {
      $validator = Validator::make($request->all(), [
          'product_id' => 'required|exists:product,id',
          'user_id' => 'required|exists:users,id',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      //get product info 
      $product = Product::select('product.id','product.business_id','product.name','product.description','product.logo','product.views','users.first_name','users.mobilecode','users.mobile')
      ->leftJoin('business_profile as b', 'product.business_id', '=', 'b.id')
      ->leftJoin('users', 'b.user_id', '=', 'users.id')
      ->where('product.id',request('product_id'))->where('product.status',1)->first();

      $activity = "VIEW";
      if (Activity::where('activity', $activity)->where('user_id', request('user_id'))->where('product_id', request('product_id'))->exists() === FALSE) {
      $ins['activity'] = $activity;
      $ins['user_id'] = request('user_id');
      $ins['product_id'] = request('product_id');
      $ins['business_id'] = 0;
      $activity = Activity::insert($ins);
      $product_increment = Product::find(request('product_id'))->increment('views');
      }

      if(empty($product)) {
        return Api::apiresponse("false","Product not avalible");
      }

      $product->is_user_fav = self::checkUserAddedInFav(request('user_id'),request('product_id'));
      // print_r($product);
      // exit;
      //get product other image
      $product->images = DB::table('product_images')->select('id','image')->where('product_id',$product->id)->where('status',1)->get()->toArray();

      //get product specification
      $product->specification = DB::table('product_attrib')->select('id','title','description')->where('product_id',$product->id)->get()->toArray();

      return Api::apiresponse("true","success",$product);
    }


    public function removeProduct(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'product_id' => 'required|exists:product,id',
          'business_id' => 'required|exists:business_profile,id',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      $product = Product::where('id',request('product_id'))->where('business_id',request('business_id'));
      $response = $product->delete();

      if($response) {
        return Api::apiresponse("true","success",$response);
      } else {
        return Api::apiresponse("false","Unauthorized access. Please try again");
      }

    }


    private function checkUserAddedInFav($user_id,$product_id)
    {
        $activity = Activity::where('activity','BOOKMARK')->where('user_id', $user_id)->where('product_id', $product_id)->exists();
        if($activity) {
          return true;
        } else {
          return false;
        }
    }


}
