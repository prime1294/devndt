<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Api;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Model\Banner;
use App\Model\Setting;

class ApiController extends Controller
{
  public function __construct()
  {
  }

  public function banner()
  {
      $banner = Banner::select("id","image")->where('status',1)->get()->toArray();
      // print_r($banner);
      if(!empty($banner)) {
        return Api::apiresponse("true","success",$banner);
      } else {
        return Api::apiresponse("false","No Banner");
      }
  }

  public function faqList()
  {
      $faq = DB::table('faq')->get()->toArray();
      if(!empty($faq)) {
        return Api::apiresponse("true","success",$faq);
      } else {
        return Api::apiresponse("false","No Faq");
      }
  }

  public function settings()
  {
    $requireSettings = ['support_email','support_contact','copyrights','tandc','pandp','share_text'];
    $settings = Setting::select('setting_key','setting_value')->whereIn('setting_key',$requireSettings)->get()->toArray();
    if(!empty($settings)) {
      return Api::apiresponse("true","success",$settings);
    } else {
      return Api::apiresponse("false","No Settings");
    }
  }

  public function registerFeedback(Request $request)
  {
    $validation['title'] = "required";
    $validation['description'] = "required";
    $validation['user_id'] = 'required|exists:users,id';
    $validator = Validator::make($request->all(),$validation);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return Api::apiresponse("false",$errors->first());
    }

    $ins['user_id'] = request('user_id');
    $ins['title'] = request('title');
    $ins['description'] = request('description');
    $ins['status'] = 1;
    $response = DB::table('feedback')->insertGetId($ins);
    if($response) {
      $ins['feedback_id'] = $response;
      return Api::apiresponse("true","success",$ins);
    } else {
      return Api::apiresponse("false","Ooops..! Something went wrong");
    }
  }

}
