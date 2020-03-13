<?php
use Illuminate\Support\Facades\Validator;
use App\Model\Plan;
use Carbon\Carbon;

class Api {
  public static function apiresponse($status,$message, $array = array() ,$code = 200 )
  {
    $reponse['status'] = $status;
    $reponse['message'] = $message;
    if(!empty($array)) {
    $reponse['response'] = $array;
    }
    return response($reponse, $code)->header('Content-Type', 'application/json');
  }

  public static function generatePlanValidity($plan_id) {
    $plan = Plan::find($plan_id);
    $start_date = Carbon::now()->format('Y-m-d');
    $end_date = Carbon::now()->format('Y-m-d');
    if(!empty($plan)) {
      $end_date = Carbon::now()->addDays($plan->trial_days); //add trial
      $end_date = $end_date->addMonths($plan->plan_month)->format('Y-m-d');
    }
    return array("start_date" => $start_date, "end_date" => $end_date);
  }

  public static function getUTC()
  {
    return date('Y-m-d h:i:s',strtotime("now"));
  }

  //array push in associative array
  public static function array_push_assoc($array, $key, $value){
  	$array[$key] = $value;
  	return $array;
  }

  //encode string
  public static function encode($key,$string) {
        //$key = passwordencrypt; //key to encrypt and decrypts.
        $result = '';
        $test = "";
        for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)+ord($keychar));
            @$test[$char]= ord($char)+ord($keychar);
            $result .= $char;
        }
  	return base64_encode(urlencode($result));
    }

  //decode string
  public static function decode($key,$string) {
      //$key = passwordencrypt; //key to encrypt and decrypts.
      $result = '';
      $string = urldecode(base64_decode($string));
      for($i=0; $i<strlen($string); $i++) {
          $char = substr($string, $i, 1);
          $keychar = substr($key, ($i % strlen($key))-1, 1);
          @$char = chr(ord($char)-ord($keychar));
          $result .= $char;
      }
      return $result;
  }


}
