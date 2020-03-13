<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Api;
use Instamojo;
use Sentinel;
use App\User;
use App\Model\Business;
use App\Model\Product;
use App\Model\Plan;
use App\Model\Donation;
use App\Model\Workinghours;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BusinessController extends Controller
{
    public function getPaymentStatus($request_id)
    {
        // $request_id = 'c0039dd3e4fd4ffdbec17595e5c00d50';
        $fullres = $status =  Instamojo::status($request_id);
        $status = json_decode($status,true);
        if($status['success'] == "true") {
          $status = $status['payment_request'];
          if(isset($status['status']) && isset($status['payments'][0]))
		      {
            if($status['status'] == "Completed")
            {
              // $data['donation_device_id'] = $device_id;
              $data['donation_instamojo_id'] = $status['payments'][0]['payment_id'];
              $data['donation_name'] = $status['payments'][0]['buyer_name'];
              $data['donation_mobile'] = $status['payments'][0]['buyer_phone'];
              $data['donation_email'] = $status['payments'][0]['buyer_email'];
              $data['donation_amount'] = $status['payments'][0]['amount'];
              $data['donation_total'] = intval($status['payments'][0]['amount']) - intval($status['payments'][0]['fees']);
              $data['donation_payment_status'] = $status['status'];
              $data['donation_internet_fees'] = $status['payments'][0]['fees'];
              $data['donation_instrument_type'] = $status['payments'][0]['instrument_type'];
              $data['donation_payment_request_id'] = $request_id;
              $data['donation_webhook'] = 0;
              $data['donation_response'] = $fullres;
              $data['donation_status'] = 1;

              $response = Donation::insertIgnore($data);

              return true;

            } else {
              return Api::apiresponse("false","Payment Failed");
            }
        } else {
          return Api::apiresponse("false","Payment Failed");
        }

      }
    }

    public function upgradeCurrentPlan(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'plan_id' => 'required|exists:business_plan,id',
          'user_id' => 'required',
          'business_id' => 'required',
          'payment_id' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      // if(request('payment_id') != "") {
      $payment_status_check = self::getPaymentStatus(request('payment_id'));
      // } else {
      //   $payment_status_check = true;
      // }

      if($payment_status_check === false) {
        return Api::apiresponse("false","Ooops..! Something went wrong");
      }
      //check have business reigster
      $business = Business::from('business_profile as b')->where('user_id',request('user_id'))->where('id',request('business_id'))->first();
      if(empty($business)) {
        return Api::apiresponse("false","No business account associated your account");
      }

      $plan_validity = Api::generatePlanValidity(request('plan_id'));
      $business->start_date = $plan_validity['start_date'];
      $business->end_date = $plan_validity['end_date'];
      $business->plan_id = request('plan_id');

      if($business->save()) {
        return Api::apiresponse("true","success",$business);
      } else {
        return Api::apiresponse("false","Oops..! Something went wrong please try again");
      }

    }


    public function registerBusiness(Request $request) {
      $messages = array( 'unique' => 'Business already register with this account' );
      $validator = Validator::make($request->all(), [
          'name' => 'required',
          'address' => 'required',
          'country_id' => 'required',
          'state_id' => 'required',
          'city_id' => 'required',
          'pincode' => 'required',
          'latitude' => 'required',
          'longitude' => 'required',
          'logo' => 'required | mimes:jpeg,png,jpg | max:50000',
          'user_id' => 'required|unique:business_profile,user_id',
          'category_id1' => 'required|exists:business_category,id',
          'category_id2' => 'required|exists:business_category,id',
          'plan_id' => 'required|exists:business_plan,id',
          // 'payment_id' => 'required',
          // 'gst_no' => 'required| max: 50',
      ],$messages);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      $user = Sentinel::findById(request('user_id'));
      if(empty($user)) {
        return Api::apiresponse("false","User not found");
      }

      //get plan info
      $plan_validity = Api::generatePlanValidity(request('plan_id'));

      $insert['user_id'] = request('user_id');
      $insert['name'] = request('name');
      $insert['country'] = request('country_id');
      $insert['state'] = request('state_id');
      $insert['city'] = request('city_id');
      $insert['address'] = request('address');
      $insert['pincode'] = request('pincode');
      $insert['latitude'] = request('latitude');
      $insert['longitude'] = request('longitude');
      $insert['category_id1'] = request('category_id1');
      $insert['category_id2'] = request('category_id2');
      $insert['gst_no'] = request('gst_no');
      $insert['plan_id'] = request('plan_id');
      $insert['start_date'] = $plan_validity['start_date'];
      $insert['end_date'] = $plan_validity['end_date'];
      $insert['status'] = 1;

      if ($request->hasFile('logo')) {
        $dir = 'image/logo/';
        $image = $request->file('logo');
        $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path($dir);
        $imagepath = $image->move($destinationPath, $name);
        $insert['logo'] = $dir.$name;
      }

      //check payment
      if(request('payment_id') != "") {
      $payment_status_check = self::getPaymentStatus(request('payment_id'));
      } else {
        $payment_status_check = true;
      }

      if($payment_status_check) {
        $business = Business::insertGetId($insert);
        if($business) {
          $insert['id'] = $business;

          //change user type as business owner
          $role = Sentinel::findRoleBySlug('business');
          $user->roles()->sync($role->id);
          return Api::apiresponse("true","success",$insert);
        } else {
          return Api::apiresponse("false","Business already register with this account");
        }
      } else {
        return Api::apiresponse("false","Ooops..! Something went wrong");
      }
    }

    public function updateBusiness(Request $request) {
      $validator = Validator::make($request->all(), [
          'name' => 'required',
          'address' => 'required',
          'country_id' => 'required',
          'state_id' => 'required',
          'city_id' => 'required',
          'pincode' => 'required',
          'latitude' => 'required',
          'longitude' => 'required',
          // 'logo' => 'required | mimes:jpeg,png,jpg | max:50000',
          'user_id' => 'required',
          'business_id' => 'required',
          'category_id1' => 'required|exists:business_category,id',
          'category_id2' => 'required|exists:business_category,id',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      //check have business reigster
      $business = Business::from('business_profile as b')->where('user_id',request('user_id'))->where('id',request('business_id'))->first();
      if(empty($business)) {
        return Api::apiresponse("false","No business account associated your account");
      }


      if ($request->hasFile('logo')) {
        $dir = 'image/logo/';
        $image = $request->file('logo');
        $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path($dir);
        $imagepath = $image->move($destinationPath, $name);
        $business->logo = $dir.$name;
      }


      //update info
      $business->name = request('name');
      $business->country = request('country_id');
      $business->state = request('state_id');
      $business->city = request('city_id');
      $business->address = request('address');
      $business->pincode = request('pincode');
      $business->latitude = request('latitude');
      $business->longitude = request('longitude');
      $business->gst_no = request('gst_no');
      $business->category_id1 = request('category_id1');
      $business->category_id2 = request('category_id2');

      if($business->save()) {
        return Api::apiresponse("true","success",$business);
      } else {
        return Api::apiresponse("false","Oops..! Something went wrong please try again");
      }
    }

    public function manageworkingHours(Request $request)
    {
      $validator = Validator::make($request->all(), [
          "business_id"    => "required|exists:business_profile,id",
          "start_time"    => "required|array|min:7|max:7",
          "end_time"    => "required|array|min:7|max:7",
          "is_off"    => "required|array|min:7|max:7",
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }


      $start_time = request('start_time');
      $end_time = request('end_time');
      $is_off = request('is_off');

      //update or insert
      foreach($is_off as $key=>$row) {
        Workinghours::updateOrCreate(
            ['day_id' => $key, 'profile_id' => request('business_id')],
            ['start_time' => $start_time[$key],'end_time' => $end_time[$key],'is_off' => $row]
        );
      }

      //send success
      return Api::apiresponse("true","Working hours updated successfully");

    }


    public function getBusinessProfiles(Request $request) {
      $validator = Validator::make($request->all(), [
          "country_id"    => "required|exists:countries,id",
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }
      if(request('keyword')) {
        $list = Business::from('business_profile as b')->select('b.id','b.name as business_name','b.logo','b.rating','c.name as city_name')
        ->leftJoin('cities as c', 'b.city', '=', 'c.id')
        ->where('b.status',1)->whereRaw('CURRENT_DATE() BETWEEN date(b.start_date) AND date(b.end_date)')->where('b.country',request('country_id'))
        ->where('b.name','like','%'.request('keyword').'%')
        ->orderBy('b.id', 'DESC')->paginate(20)->toArray();
      } else {
      $list = Business::from('business_profile as b')->select('b.id','b.name as business_name','b.logo','b.rating','c.name as city_name')
      ->leftJoin('cities as c', 'b.city', '=', 'c.id')
      ->where('b.status',1)->whereRaw('CURRENT_DATE() BETWEEN date(b.start_date) AND date(b.end_date)')->where('b.country',request('country_id'))->orderBy('b.id', 'DESC')->paginate(20)->toArray();
    }
    if(!empty($list['data'])) {
        return Api::apiresponse("true","success",$list);
      } else {
        return Api::apiresponse("false","no data found");
      }
    }

    public function getBusinessDetails(Request $request)
    {
      // exho "hello";
      $validator = Validator::make($request->all(), [
          "search_by" => [
                    'required',
                    Rule::in(['business_id', 'user_id']),
                ],
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      if(request('search_by') == "user_id")
      {
        //put search by product logic
        $validator = Validator::make($request->all(), [
          'id' => 'required|exists:users,id',
        ]);
      } else {
        //put search by business logic
        $validator = Validator::make($request->all(), [
          'id' => 'required|exists:business_profile,id',
        ]);
      }

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      //not fetch details of business
      if(request('search_by') == "user_id")
      {
        $col = 'b.user_id';
      } else {
        $col = 'b.id';
      }

      //select fields
      $fields = [
        'b.id','b.name as business_name','b.logo','b.user_id','b.plan_id','b.start_date','b.end_date','b.gst_no','b.address','b.pincode','b.latitude','b.longitude',
         'b.rating',
        'co.id as country_id', 'co.name as country_name',
        's.id as state_id','s.name as state_name',
        'c.id as city_id','c.name as city_name',
        'b.category_id1','c1.name as category_name1',
        'b.category_id2','c2.name as category_name2'
      ];
      //fetch business info
      $business = Business::from('business_profile as b')
      ->select($fields)
      ->leftJoin('countries as co', 'b.country', '=', 'co.id')
      ->leftJoin('states as s', 'b.state', '=', 's.id')
      ->leftJoin('cities as c', 'b.city', '=', 'c.id')
      ->leftJoin('business_category as c1', 'b.category_id1', '=', 'c1.id')
      ->leftJoin('business_category as c2', 'b.category_id2', '=', 'c2.id')
      ->where('b.status',1)->where($col,request('id'))->first();
      if(empty($business)) {
        return Api::apiresponse("false","No business account associate with this account");
      }
      $business->toArray();
      $parent['business'] = $business;
      $business_id = $business['id'];
      // dd($business);

      //fetch opening hours info
      $parent['opening_hours'] = Workinghours::where('profile_id',$business_id)->get()->toArray();

      //fetch user info
      $user = Sentinel::findById($business['user_id']);
      if(empty($user)) {
        return Api::apiresponse("false","User not found");
      }
      $parent['user'] = $user;

      //fetch business current plan info
      $parent['plan'] = Plan::find($business['plan_id']);
      return Api::apiresponse("true","success",$parent);
    }


    public function businessFilter(Request $request)
    {
      if(request('category')) {
        $validation['category'] = 'required|exists:business_category,id';
      }

      if(request('rating')) {
        $validation['rating'] = 'required';
      }

      if(request('country')) {
        $validation['country'] = 'required|exists:countries,id';
      }
      if(request('state')) {
        $validation['state'] = 'required|exists:states,id';
      }
      if(request('city')) {
        $validation['city'] = 'required|exists:cities,id';
      }

      if(request('radius') || request('latitude') || request('longitude')) {
        $validation['radius'] = 'required';
        $validation['latitude'] = 'required';
        $validation['longitude'] = 'required';
      }

      $validator = Validator::make($request->all(),$validation);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      //select fields
      $fields = [
        'b.id','b.name as business_name','b.logo','b.user_id','b.plan_id','b.start_date','b.end_date','b.gst_no','b.address','b.pincode','b.latitude','b.longitude',
         'b.rating',
        'co.id as country_id', 'co.name as country_name',
        's.id as state_id','s.name as state_name',
        'c.id as city_id','c.name as city_name',
        'b.category_id1','c1.name as category_name1',
        'b.category_id2','c2.name as category_name2'
        ];


        if(request('radius') && request('latitude') && request('longitude')) {
          array_push($fields,DB::Raw("(3959 * acos(cos(radians(".request('latitude').")) * cos(radians(b.latitude)) * cos(radians(b.longitude) - radians(".request('longitude').")) + sin(radians(".request('latitude').")) * sin(radians(b.latitude)))) AS distance"));
        }

      //fetch business info
      $query = Business::query();
      $query->from('business_profile as b')->select($fields)
      ->leftJoin('countries as co', 'b.country', '=', 'co.id')
      ->leftJoin('states as s', 'b.state', '=', 's.id')
      ->leftJoin('cities as c', 'b.city', '=', 'c.id')
      ->leftJoin('business_category as c1', 'b.category_id1', '=', 'c1.id')
      ->leftJoin('business_category as c2', 'b.category_id2', '=', 'c2.id');
      $query->where('b.status',1);
      $query->whereRaw('CURRENT_DATE() BETWEEN date(b.start_date) AND date(b.end_date)');

      //category filter
      if(request('category')) {
        $query->whereIn('b.category_id2', explode(',',request('category')));
      }


      //rating filter
      if(request('rating')) {
        $query->where('b.rating', request('rating'));
      }

      //country city and state
      if(request('country')) {
        $query->where('b.country', request('country'));
      }
      if(request('state')) {
        $query->where('b.state', request('state'));
      }
      if(request('city')) {
        $query->where('b.city', request('city'));
      }

      //radius + lat + long
      if(request('radius') && request('latitude') && request('longitude')) {
        $query->havingRaw('distance < ?', [request('radius')]);
        $query->orderBy('distance','ASC');
      }
      else
      {
        $query->orderBy('b.id','DESC');
      }

      //rating
      // $query->paginate(20);
      $collection = $query->limit(100)->get();

      if(!empty($collection)) {
          return Api::apiresponse("true","success",$collection);
        } else {
          return Api::apiresponse("false","no data found");
        }

    }

}
