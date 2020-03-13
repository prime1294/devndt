<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Api;
use Sentinel;
use Activation;
use Hash;
use App\User;
use App\Model\Business;

class UserController extends Controller
{
  public function __construct()
  {

  }

  public function sendTextlocalmessage($otp,$mobile)
  {
      $message = rawurlencode("Dear Customer,\nYour Take And Make OTP is ".$otp);
      $apiKey = urlencode('qtHX7Y7/vYQ-ymB53IkyYAdJriySFZKnWB4zzPTB6l');
      // Message details
     	$numbers = array($mobile);
     	$sender = urlencode('PSKKUM');

     	$numbers = implode(',', $numbers);

     	// Prepare data for POST request
     	$data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message, "unicode" => true);

     	// Send the POST request with cURL
     	$ch = curl_init('https://api.textlocal.in/send/');
     	curl_setopt($ch, CURLOPT_POST, true);
     	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
     	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     	$response = curl_exec($ch);
     	curl_close($ch);
     	// Process your response here
     	// return $response;
      $decode = json_decode($response,true);
      if($decode['status'] == "success") {
        return true;
      } else {
        return false;
      }
  }

  public function sendOTP(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'mobilecode' => 'required',
        'mobile' => 'required|max:255'
    ]);

    $otp = rand(111111,999999);
    $response = self::sendTextlocalmessage($otp,request('mobilecode').request('mobile'));
    if($response) {
      return Api::apiresponse("true","success",$otp);
    } else {
      return Api::apiresponse("false","OTP Failure. Please try again.");
    }
  }


  public function registerLogin(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
        'email' => 'required|email|max:255',
        'country_id' => 'required|exists:countries,id',
        'mobilecode' => 'required',
        'mobile' => 'required|max:255',
        'password' => 'required',
        'device_id' => 'required',
        // 'fcm_token' => 'required',
        'device_type' => 'required'
    ]);

    $flag = "login";
    $is_new_user = 0;
    $api_response = [];

    if ($validator->fails()) {
      $errors = $validator->errors();
      return Api::apiresponse("false",$errors->first());
    }

    //check users
    $Auth = User::where('mobilecode',request('mobilecode'))->where('mobile',request('mobile'))->first();
    if(!empty($Auth)) {
      $user = Sentinel::authenticate($request->all());

      if(!empty($user)) {
        if (Activation::completed($user)) {
          // User account is activated
          User::where('id',$user->id)->update([
            'device_id' => request('device_id'),
            'fcm_token' => request('fcm_token'),
            'device_type' => request('device_type'),
            'last_login' => Api::getUTC()
          ]);

          $api_response = $user;

        } else {
          // Not activated
          return Api::apiresponse("false","Your Account has not been activated yet");
        }
      } else {
        return Api::apiresponse("false","Invalid Login Details");
      }
    } else {
      $flag = "register";
    }


    //if flag is register then
    if($flag == "register") {
      $user = [
        "email" => request('email'),
        "password" => request('password'),
        "first_name" => request('name'),
        "country_id" => request('country_id'),
        "last_name" => "",
        "mobilecode" => request('mobilecode'),
        "mobile" => request('mobile'),
        "device_id" => request('device_id'),
        "fcm_token" => request('fcm_token'),
        "device_type" => request('device_type'),
        "status" => 1
      ];

      $user = Sentinel::registerAndActivate($user);
      $role = Sentinel::findRoleBySlug('user');
      $role->users()->attach($user);
      if($user)
      {
        $is_new_user = 1;
        $api_response = $user;
      } else {
        return Api::apiresponse("false","User Already Exist");
      }
    } //end of check register flag

    if(!empty($api_response)) {

      //check register business
      $api_response['is_new_user'] = $is_new_user;
      $api_response['roles'] = Sentinel::findById($api_response->id)->roles()->first()->slug;
      $api_response['is_business'] = $api_response['roles'] == "business" ? 1 : 0;
      if($api_response['is_business'] == 1) {
      $api_response['business_id'] = Business::from('business_profile as b')->where('b.user_id',$user->id)->first()->id;
      }

      return Api::apiresponse("true","success",$api_response);
    } else {
      return Api::apiresponse("false","Oops..! Something went wrong");
    }

  }
  public function register(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'name' => 'required|max:255',
          'email' => 'required|email|max:255|unique:users,email',
          'country_id' => 'required|exists:countries,id',
          'mobilecode' => 'required',
          'mobile' => 'required|max:255|unique:users,mobile',
          'password' => 'required',
          'device_id' => 'required',
          // 'fcm_token' => 'required',
          'device_type' => 'required'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      $user = [
        "email" => request('email'),
        "password" => request('password'),
        "first_name" => request('name'),
        "country_id" => request('country_id'),
        "last_name" => "",
        "mobilecode" => request('mobilecode'),
        "mobile" => request('mobile'),
        "device_id" => request('device_id'),
        "fcm_token" => request('fcm_token'),
        "device_type" => request('device_type'),
        "status" => 1
      ];

      $user = Sentinel::registerAndActivate($user);
      //$activation = Activation::create($user);
      $role = Sentinel::findRoleBySlug('user');
      $role->users()->attach($user);
      if($user)
      {
        //$resonse['user'] = $user;
        //$resonse['activation'] = $activation;
        return Api::apiresponse("true","success",$user);
      } else {
        return Api::apiresponse("false","User Already Exist");
      }
  }

  public function login(Request $request) {
    $validator = Validator::make($request->all(), [
        'mobilecode' => 'required',
        'mobile' => 'required|max:255',
        'password' => 'required',
        'device_id' => 'required',
        // 'fcm_token' => 'required',
        'device_type' => 'required'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return Api::apiresponse("false",$errors->first());
    }


    $user = Sentinel::authenticate($request->all());
      if(!empty($user)) {
        if (Activation::completed($user)) {
          // User account is activated
          User::where('id',$user->id)->update([
            'device_id' => request('device_id'),
            'fcm_token' => request('fcm_token'),
            'device_type' => request('device_type'),
            'last_login' => Api::getUTC()
          ]);

          //check register business
          $user['roles'] = Sentinel::findById($user->id)->roles()->first()->slug;
          $user['is_business'] = $user['roles'] == "business" ? 1 : 0;
          if($user['is_business'] == 1) {
          $user['business_id'] = Business::from('business_profile as b')->where('b.user_id',$user->id)->first()->id;
          }

          // $user['is_plan_expire'] = 0;

          return Api::apiresponse("true","success",$user);
        } else {
          // Not activated
          return Api::apiresponse("false","Your Account has not been activated yet");
        }
      } else {
        return Api::apiresponse("false","Invalid Login Details");
      }
  }

  public function activateAccount(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'activation_code' => 'required',
        'otp' => 'required'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return Api::apiresponse("false",$errors->first());
    }

    $user = Activation::createModel()->where('code', request('activation_code'))->first();
    if(!empty($user)) {
      $user = Sentinel::findById($user->user_id);
      if (Activation::complete($user, request('activation_code')))
      {
          // Activation was successfull
          User::where('id',$user->id)->update(['otp' => request('otp')]);
          return Api::apiresponse("true","success",$user);
      }
      else
      {
          // Activation not found or not completed.
          return Api::apiresponse("true","Activation Already Completed",$user);
      }
    } else {
      return Api::apiresponse("false","Invalid Activation Code");
    }

  }

  public function forgotPassword(Request $request) {
    $validator = Validator::make($request->all(), [
        'mobilecode' => 'required',
        'mobile' => 'required|max:255'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return Api::apiresponse("false",$errors->first());
    }

    $credentials = [
    'mobilecode' => request('mobilecode'),
    'mobile' => request('mobile'),
    ];

    $user = Sentinel::findByCredentials($credentials);
    if(!empty($user)) {
      return Api::apiresponse("true","success",$user);
    } else {
      return Api::apiresponse("false","No account associate with this mobile number");
    }
  }

  public function resetPassword(Request $request) {
    $validator = Validator::make($request->all(), [
        'password' => 'required',
        'user_id' => 'required'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return Api::apiresponse("false",$errors->first());
    }

    $user = Sentinel::findById(request('user_id'));
    if(!empty($user)) {
      if (Activation::completed($user)) {
          $credentials = [
            'password' => request('password'),
        ];

        $user = Sentinel::update($user, $credentials);
        return Api::apiresponse("true","success",$user);
      } else {
        return Api::apiresponse("false","Your Account has not been activated yet");
      }
    } else {
      return Api::apiresponse("false","No account associate with this user");
    }
  }

  public function updateProfile(Request $request) {
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'name' => 'required',
        'email' => 'required|email|max:255|unique:users,email,'.request('user_id'),
        'country_id' => 'required|exists:countries,id',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return Api::apiresponse("false",$errors->first());
    }

    $update = [
        'first_name' => request('name'),
        'email' => request('email'),
        'country_id' => request('country_id'),
    ];

    if ($request->hasFile('image')) {
      $dir = 'image/profile/';
      $image = $request->file('image');
      $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
      $destinationPath = public_path($dir);
      $imagepath = $image->move($destinationPath, $name);
      $update['image'] = $dir.$name;
    }


    $user = Sentinel::findById(request('user_id'));
    $user = Sentinel::update($user, $update);
    if($user) {
      return Api::apiresponse("true","success",$user);
    } else {
      return Api::apiresponse("false","Oops..! Something went wrong");
    }

  }

  public function verifyUserActivation(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return Api::apiresponse("false",$errors->first());
    }

    $user = Sentinel::findById(request('user_id'));
    $activation = Activation::completed($user);

    //check plan is expire
    $bizinfo = Business::from('business_profile as b')->whereRaw('CURRENT_DATE() BETWEEN date(b.start_date) AND date(b.end_date)')->where('b.user_id',request('user_id'))->where('b.status',1)->first();
    $plan_expire = 1;

    if(!empty($bizinfo)) {
      $plan_expire = 0;
    }
    $user->is_plan_expire = $plan_expire;
    // dd($activation);
    if($activation) {
      return Api::apiresponse("true","success",$user);
    } else {
      return Api::apiresponse("false","Your Account Suspended");
    }
  }

}
