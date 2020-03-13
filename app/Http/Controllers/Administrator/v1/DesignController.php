<?php

namespace App\Http\Controllers\Administrator\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Admin;
use Sentinel;
use App\User;
use App\Model\Staff;
use App\Model\Design;
use App\Model\DesignType;

class DesignController extends Controller
{

    public function __construct() {

    }

    public function embroideryDesign(Request $request) {
      $user = Sentinel::getUser();
      //get min stitch
      $data['min_stitch'] =  Design::where('user_id',$user->id)->where('type',1)->min('stitch');
      $data['max_stitch'] =  Design::where('user_id',$user->id)->where('type',1)->max('stitch');
      //get max stitch

      //get design list
      $query = Design::query();
      $query->select('design.*' , DB::raw('GROUP_CONCAT(design_type.name SEPARATOR ", ") as design_type_name'));
      $query->leftjoin('design_type', DB::raw('FIND_IN_SET(design_type.id,design.design_type)'),'<>',DB::raw('"0"'));
      $query->where('design.status',1);
      $query->where('design.type',1);

      if(request('search') != "") {
        $q = request('search');
        $query->where(function($m) use ($q) {
            $m->where('design.name', 'LIKE', '%'.$q.'%')
              ->orWhere('design_type.name', 'LIKE', '%'.$q.'%')
              ->orWhere('design.designer_id', 'LIKE', '%'.$q.'%');
          });
      }

      if(request('area') != "") {
        $query->where('design.area',request('area'));
      }

      if(request('color') != "") {
        $query->where('design.color','LIKE', '%'.request('color').'%');
      }

      if(request('stitch_range') != "") {
        $query->whereBetween('design.stitch',explode(',',request('stitch_range')));
      }

      if(request('design') != "") {
        $query->where('design.is_fav',request('design'));
      }
      $query->where('design.user_id',$user->id);
      $query->groupBy('design.id');
      $query->orderBY('design.id','DESC');
//       dd($query->toSql(), $query->getBindings());

      $data['design_list'] = $query->paginate(12);
      return view('admin.v1.embroidery.list',$data);
    }

    // public function getEmbroideryDesign(Request $request) {
    //
    // }
    //
    //
    // public function getFashionDesign(Request $request) {
    //
    // }


    public function addEmbroiderydesign(Request $request) {
      $user = Sentinel::getUser();
      $data['design_type'] = DesignType::select('id','name')->where('type',1)->where('status',1)->get();
      $data['designer'] = Staff::select('id','name','photo')->where('business_name','Designer')->where('user_id',$user->id)->where('status',1)->get();
      return view('admin.v1.embroidery.new',$data);
    }


    public function editEmbroiderydesign(Request $request,$id) {
      $user = Sentinel::getUser();
      if(Design::HaveRightBank($user->id,$id)) {
      $data['info'] = Design::find($id);
      $data['design_type'] = DesignType::select('id','name')->where('type',1)->where('status',1)->get();
      $data['designer'] = Staff::select('id','name','photo')->where('business_name','Designer')->where('user_id',$user->id)->where('status',1)->get();
      return view('admin.v1.embroidery.edit',$data);
      } else {
        return Admin::unauth();
      }
    }


    public function registerEmbroiderydesign(Request $request) {
      $validator = Validator::make($request->all(), [
          'design_name' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('error', $errors->first());
      }

      $design_file = "";
      if ($request->hasFile('design_file')) {
        $dir = 'image/design/';
        $image = $request->file('design_file');
        $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path($dir);
        $imagepath = $image->move($destinationPath, $name);
        $design_file = $dir.$name;
      }

      $user = Sentinel::getUser();
      $response = Design::insert([
        'user_id' => $user->id,
        'type' => 1,
        'image' => request('fbinputtxt'),
        'name' => request('design_name'),
        'design_type' => !empty(request('master_type')) ? implode(',',request('master_type')) : "",
        'stitch' => request('stitch'),
        'color' => request('color'),
        'area' => request('area'),
        'designer_id' => request('master_user'),
        'design_file_type' => request('file_type'),
        'design_file' => $design_file,
        'sale_price' => "0.00",
        'remarks' => request('remarks'),
        'status' => '1',
      ]);

      if($response) {
        return redirect()->route('embroidery.design')->with('success', "Design Register Successfully");
      } else {
        return redirect()->route('embroidery.design')->with('error', "Ooops..! Something went wrong");
      }
    }


    public function updateEmbroiderydesign(Request $request,$id) {
      $user = Sentinel::getUser();
      if(Design::HaveRightBank($user->id,$id)) {
      $validator = Validator::make($request->all(), [
          'design_name' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('error', $errors->first());
      }

      $update = [
        'name' => request('design_name'),
        'image' => request('fbinputtxt'),
        'design_type' => !empty(request('master_type')) ? implode(',',request('master_type')) : "",
        'stitch' => request('stitch'),
        'color' => request('color'),
        'area' => request('area'),
        'designer_id' => request('master_user'),
        'design_file_type' => request('file_type'),
        'sale_price' => "0.00",
        'remarks' => request('remarks'),
      ];


      if ($request->hasFile('design_file')) {
        $dir = 'image/design2/';
        $image = $request->file('design_file');
        $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path($dir);
        $imagepath = $image->move($destinationPath, $name);
        $update['design_file'] = $dir.$name;
      }

      $user = Sentinel::getUser();
      $response = Design::where('id',$id)->where('user_id',$user->id)->where('type','1')->update($update);

      if($response) {
        return redirect()->route('embroidery.design')->with('success', "Design Updated Successfully");
      } else {
        return redirect()->route('embroidery.design')->with('error', "Ooops..! Something went wrong");
      }
      } else {
        return Admin::unauth();
      }
    }


    public function bookmarkDesign(Request $request,$id) {
      $user = Sentinel::getUser();
      if(Design::HaveRightBank($user->id,$id)) {
      $update['is_fav'] = request('status');
      $response = Design::where('id',$id)->update($update);
      return $response ? "true" : "false";
      } else {
        return false;
      }
    }


    public function deleteDesign(Request $request,$id,$type) {
      $user = Sentinel::getUser();
      if(Design::HaveRightBank($user->id,$id)) {
        $response = Design::where('id',$id)->where('user_id',$user->id)->where('type',$type)->delete($id);
        $route = $type == 1 ? "embroidery.design" : "fashion.design";

        if($response) {
          return redirect()->route($route)->with('success', "Design Deleted Successfully");
        } else {
          return redirect()->route($route)->with('error', "Ooops..! Something went wrong");
        }

      } else {
        return Admin::unauth();
      }
    }


    public function fashionDesign(Request $request) {
      $user = Sentinel::getUser();
      //get min price
      $data['min_price'] =  intval(Design::where('user_id',$user->id)->min('sale_price'));
      $data['max_price'] =  intval(Design::where('user_id',$user->id)->max('sale_price'));
      //get max price


      //get design list
      $query = Design::query();
      $query->select('design.*' , DB::raw('GROUP_CONCAT(design_type.name SEPARATOR ", ") as design_type_name'));
      $query->leftjoin('design_type', DB::raw('FIND_IN_SET(design_type.id,design.design_type)'),'<>',DB::raw('"0"'));
      $query->where('design.status',1);
      $query->where('design.type',2);


        if(request('search') != "") {
            $q = request('search');
            $query->where(function($m) use ($q) {
                $m->where('design.name', 'LIKE', '%'.$q.'%');
            });
        }

      if(request('price_range') != "") {
        $query->whereBetween('design.sale_price',explode(',',request('price_range')));
      }

      if(request('design') != "") {
        $query->where('design.is_fav',request('design'));
      }

      $query->where('design.user_id',$user->id);
      $query->groupBy('design.id');
      $query->orderBY('design.id','DESC');

      $data['design_list'] = $query->paginate(12);
      return view('admin.v1.fashion.list',$data);
    }


    public function addFashiondesign(Request $request) {
      $user = Sentinel::getUser();
      $data['design_type'] = DesignType::select('id','name')->where('type',2)->where('status',1)->get();
      $data['designer'] = Staff::select('id','name','photo')->where('business_name','Designer')->where('user_id',$user->id)->where('status',1)->get();
      return view('admin.v1.fashion.new',$data);
    }


    public function editFashiondesign(Request $request,$id) {
      $user = Sentinel::getUser();
      if(Design::HaveRightBank($user->id,$id)) {
      $data['info'] = Design::find($id);
      $data['design_type'] = DesignType::select('id','name')->where('type',2)->where('status',1)->get();
      $data['designer'] = Staff::select('id','name','photo')->where('business_name','Designer')->where('user_id',$user->id)->where('status',1)->get();
      return view('admin.v1.fashion.edit',$data);
      } else {
        return Admin::unauth();
      }
    }


    public function registerFashiondesign(Request $request) {
      $validator = Validator::make($request->all(), [
          'design_name' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('error', $errors->first());
      }


      $user = Sentinel::getUser();
      $response = Design::insert([
        'user_id' => $user->id,
        'type' => 2,
        'image' => request('fbinputtxt'),
        'name' => request('design_name'),
        'design_type' => !empty(request('master_type')) ? implode(',',request('master_type')) : "",
        'sale_price' => request('sale_price'),
        'remarks' => request('remarks'),
        'status' => '1',
      ]);

      if($response) {
        return redirect()->route('fashion.design')->with('success', "Design Register Successfully");
      } else {
        return redirect()->route('fashion.design')->with('error', "Ooops..! Something went wrong");
      }
    }


    public function updateFashiondesign(Request $request,$id) {
      $user = Sentinel::getUser();
      if(Design::HaveRightBank($user->id,$id)) {
      $validator = Validator::make($request->all(), [
          'design_name' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('error', $errors->first());
      }

      $update = [
        'name' => request('design_name'),
        'image' => request('fbinputtxt'),
        'design_type' => !empty(request('master_type')) ? implode(',',request('master_type')) : "",
        'sale_price' => request('sale_price'),
        'remarks' => request('remarks'),
      ];


      $user = Sentinel::getUser();
      $response = Design::where('id',$id)->where('user_id',$user->id)->where('type','2')->update($update);
      // dd($response);
      if($response) {
        return redirect()->route('fashion.design')->with('success', "Design Updated Successfully");
      } else {
        return redirect()->route('fashion.design')->with('error', "Ooops..! Something went wrong");
      }
      } else {
        return Admin::unauth();
      }
    }

}
