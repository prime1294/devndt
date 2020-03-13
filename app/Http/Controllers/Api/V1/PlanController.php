<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Api;
use App\Model\Plan;

class PlanController extends Controller
{
    public function getAllPlan(Request $request)
    {
      $plan = Plan::all();
      if(!empty($plan)) {
        return Api::apiresponse("true","success",$plan);
      } else {
        return Api::apiresponse("false","No Plan Found");
      }
    }

    public function upgradePlan(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'plan_id' => 'required|exists:business_plan,id',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      $plan = Plan::where('id','>=',request('plan_id'))->where('amount','!=',0)->get();
      if(!empty($plan)) {
        return Api::apiresponse("true","success",$plan);
      } else {
        return Api::apiresponse("false","No Plan Found");
      }

    }

    public function getPlanDetail(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'plan_id' => 'required|exists:business_plan,id',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return Api::apiresponse("false",$errors->first());
      }

      $plan = Plan::find(request('plan_id'));
      if(!empty($plan)) {
        return Api::apiresponse("true","success",$plan);
      } else {
        return Api::apiresponse("false","Invalid Plan Id");
      }
    }
}
