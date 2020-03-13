<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use Api;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function getAllCountry()
    {
      $country = DB::table('countries')->select('id','sortname','name','phonecode')->get();
      if(!empty($country)) {
        return Api::apiresponse("true","success",$country);
      } else {
        return Api::apiresponse("false","No country avalible");
      }
    }

    public function getAllState(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'country_id' => 'required'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      $state = DB::table('states')->select('id','name')->where('country_id',request('country_id'))->get();
      //echo count($state);
      if(!empty($state)) {
        return Api::apiresponse("true","success",$state);
      } else {
        return Api::apiresponse("false","No state avalible");
      }
    }


    public function getAllCity(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'state_id' => 'required'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      $city = DB::table('cities')->select('id','name')->where('state_id',request('state_id'))->get();
      if(!empty($city)) {
        return Api::apiresponse("true","success",$city);
      } else {
        return Api::apiresponse("false","No city avalible");
      }
    }
}
