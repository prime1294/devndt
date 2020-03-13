<?php
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class Instamojo {
  public static function status($reqid = '')
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.instamojo.com/api/1.1/payment-requests/'.$reqid.'/');
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER,
    array("X-Api-Key:423f6276290faa26876c7fc7535dcb2e",
          "X-Auth-Token:379f034c1824eebc969c3ae6e3580b3d"));
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
  }
}
