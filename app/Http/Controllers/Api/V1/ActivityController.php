<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Api;
use Illuminate\Support\Facades\DB;
use App\Model\Product;
use App\Model\Business;
use App\Model\Activity;
use App\Model\Reportspam;
use App\Model\Rating;


class ActivityController extends Controller
{
    public function reigsterActivity(Request $request)
    {
      $validation['user_id'] = 'required|exists:users,id';
      $validation['activity'] = 'required|in:VIEW,BOOKMARK,RBOOKMARK';

      $activity = request('activity');
      if($activity == "VIEW" || $activity == "BOOKMARK" || $activity == "RBOOKMARK")
      {
        $validation['product_id'] = 'required|exists:product,id';
      }

      $validator = Validator::make($request->all(), $validation);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      //increment view of product and update product_view in product table
      if($activity == "VIEW" || $activity == "BOOKMARK") {
          if (Activity::where('activity', $activity)->where('user_id', request('user_id'))->where('product_id', request('product_id'))->exists() === FALSE) {
          $ins['activity'] = $activity;
          $ins['user_id'] = request('user_id');
          $ins['product_id'] = request('product_id');
          $ins['business_id'] = 0;
          $dbactivity = Activity::insert($ins);

          if($activity == "VIEW") {
            $product = Product::find(request('product_id'))->increment('views');
          } else {
            $product = Product::find(request('product_id'))->increment('favourite');
          }

          }
      } elseif($activity == "RBOOKMARK") {
          $dbactivity = Activity::where('activity', "BOOKMARK")->where('user_id', request('user_id'))->where('product_id', request('product_id'))->delete();
          // dd($activity);
          if(!$dbactivity) {
            return Api::apiresponse("false","Unauthorized access. Please try again.");
          } else {
            $product = Product::find(request('product_id'))->decrement('favourite');
          }
      }

      return Api::apiresponse("true","success");
    }


    public function getUserBookmarkList(Request $request)
    {
      $validation['user_id'] = 'required|exists:users,id';
      $validator = Validator::make($request->all(), $validation);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      $activity = Activity::where('activity', "BOOKMARK")->where('user_id', request('user_id'))->get()->toArray();
      // dd($activity);
      // exit();
      if(!empty($activity)) {

        //fetch product_id
        $parent = [];
        $fields = array('product.id','product.business_id','product.name','product.description','product.logo','product.views','c1.name as category_name1','c2.name as category_name2');
        foreach($activity as $row) {
          $list = Product::select($fields)
          ->leftJoin('business_profile as b', 'product.business_id', '=', 'b.id')
          ->leftJoin('business_category as c1', 'b.category_id1', '=', 'c1.id')
          ->leftJoin('business_category as c2', 'b.category_id2', '=', 'c2.id')
          ->where('product.id',$row['product_id'])->where('product.status',1)->where('b.status',1)->first()
          ->toArray();
          // print_r($list);
          $list['is_user_fav'] = true;
          array_push($parent,$list);
        }

        return Api::apiresponse("true","success",$parent);
      } else {
        return Api::apiresponse("false","No Bookmark");
      }
    }

    public function searchHint(Request $request)
    {
      $validation['keyword'] = 'required';
      $validator = Validator::make($request->all(), $validation);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      $keyword = request('keyword');
      $fields = ['b.id as id','b.name as name', DB::Raw("'business' as type")];

      $business = Business::from('business_profile as b')
      ->select($fields)
      ->leftJoin('business_category as c1', 'b.category_id1', '=', 'c1.id')
      ->leftJoin('business_category as c2', 'b.category_id2', '=', 'c2.id')
      ->where('b.status',1)
      ->where(function ($query) use ($keyword) {
            $query->where('b.name','LIKE','%'.$keyword.'%')
            ->orWhere('c1.name','LIKE','%'.$keyword.'%')
            ->orWhere('c2.name','LIKE','%'.$keyword.'%');
      })->limit(10)->get();

      $fields = ['id as id','name as name', DB::Raw("'product' as type")];
      $product = Product::select($fields)
      ->where('status',1)
      ->where(function ($query) use ($keyword) {
            $query->where('name','LIKE','%'.$keyword.'%')
            ->orWhere('description','LIKE','%'.$keyword.'%');
      })->limit(10)->get();


      //merge column
      $final = $business->union($product);

      if(!empty($final)) {
        return Api::apiresponse("true","success",$final);
      } else {
        return Api::apiresponse("false","No Bookmark");
      }

    }


    public function reportSpam(Request $request)
    {
      $validation['business_id'] = "required|exists:business_profile,id";
      $validation['user_id'] = "required|exists:users,id";
      $validation['message'] = "required";
      $validator = Validator::make($request->all(), $validation);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      $spam =  Reportspam::firstOrNew([
        'spam_business' => request('business_id'),
        'spam_uid' => request('user_id')
      ]);
      $spam->spam_message = request('message');
      $spam->save();

      if($spam) {
        return Api::apiresponse("true","success",$spam);
      } else {
        return Api::apiresponse("false","Oops..! Something went wrong");
      }
    }

    public function addRating(Request $request)
    {
      $validation['business_id'] = "required|exists:business_profile,id";
      $validation['user_id'] = "required|exists:users,id";
      $validation['rating'] = "required|numeric|min:0|max:5";
      $validator = Validator::make($request->all(), $validation);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      $rating['profile_id'] = request('business_id');
      $rating['user_id'] = request('user_id');
      $rating['rating'] = request('rating');
      if(Rating::where('profile_id',request('business_id'))->where('user_id',request('user_id'))->exists() === false)
      {
        //insert in tabel
        Rating::create($rating);
        //get all rating of business
        $ratings = Rating::where('profile_id',request('business_id'))->avg('rating');
        //update in business table
        Business::from('business_profile as b')->where('id',request('business_id'))->update(['rating' => round($ratings)]);
      }

      return Api::apiresponse("true","success");

    }
}
