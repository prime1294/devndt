<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\KarigarReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Admin;
use Sentinel;
use App\User;
use App\Model\DailyProduction;
use App\Model\Machine;
use App\Model\ProgrammeCard;
use App\Model\StockUnit;
use App\Model\FrameReport;

class FrameReportController extends Controller
{
  public function __construct() {

  }

  public function manageFrameReport(Request $request,$id) {
    $user = Sentinel::getUser();
    $data['pc_list'] = ProgrammeCard::where('status',1)->where('user_id',$user->id)->orderBy('id','DESC')->get();
    $data['stock_unit'] = StockUnit::where('status',1)->orderBy('name','ASC')->get();
    $data['daily_report_id'] = $id;
    return view('admin.v1.framereport.add',$data);
  }

  public function registerFrameReport(Request $request,$id) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        "fr_frame"    => "required|array|min:1",
        "fr_frame.*"  => "required|min:1",
        "fr_programmecard"    => "required|array|min:1",
        "fr_programmecard.*"  => "required|min:1",
        "fr_stitch"    => "required|array|min:1",
        "fr_stitch.*"  => "required|min:1",
        "fr_quantity"    => "required|array|min:1",
        "fr_quantity.*"  => "required|min:1",
        "fr_unit"    => "required|array|min:1",
        "fr_unit.*"  => "required|min:1",
        "fr_rate"    => "required|array|min:1",
        "fr_rate.*"  => "required|min:1",
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      $data['message'] = $errors->first();
      return response()->json($data);
    }

    $fr_frame = request('fr_frame');
    $fr_programmecard = request('fr_programmecard');
    $fr_stitch = request('fr_stitch');
    $fr_quantity = request('fr_quantity');
    $fr_unit = request('fr_unit');
    $fr_rate = request('fr_rate');
    $fr_design = request('fr_design');
    $fr_monitor = request('fr_monitor');
    $fr_remarks = request('fr_remarks');
    $flag = 0;
    $total_frame = 0;
    $total_jobwork = 0;
    //remove all old entry
    FrameReport::where('user_id',$user->id)->where('production_id',$id)->forceDelete();
    foreach($fr_frame as $key => $row) {
      if($fr_frame[$key] != "" && $fr_stitch[$key] != "" && $fr_quantity[$key] != "" && $fr_unit[$key] != "" && $fr_rate[$key] != "") {
          $ins['production_id'] = $id;
          $ins['user_id'] = $user->id;
          $ins['frame'] = $fr_frame[$key];
          $ins['pcard'] = $fr_programmecard[$key];
          $ins['design'] = $fr_design[$key];
          $ins['monitor_no'] = $fr_monitor[$key];
          $ins['stitch'] = $fr_stitch[$key];
          $ins['quantity'] = $fr_quantity[$key];
          $ins['unit'] = $fr_unit[$key];
          $ins['rate'] = $fr_rate[$key];
          $ins['remarks'] = $fr_remarks[$key];
          FrameReport::insert($ins);
          $total_frame  = $total_frame + $fr_frame[$key];
          $temp = 0;
          $temp = $fr_quantity[$key] * $fr_rate[$key];
          $total_jobwork = $total_jobwork + $temp;
          $flag = 1;
      }
    }

    //update totals
    $update['frame'] = $total_frame;
    $update['jobwork'] = $total_jobwork;
    KarigarReport::where('id',$id)->update($update);

    if($flag) {
      $data['status'] = "true";
      $data['message'] = "success";
    } else {
      $data['status'] = "false";
      $data['message'] = "Ooops...! Something went wrong";
    }
    return response()->json($data);
  }

  public function fetchFrameReport(Request $request) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        'report_id' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      $data['message'] = $errors->first();
      return response()->json($data);
    }

    $response = FrameReport::where('user_id',$user->id)->where('production_id',request('report_id'))->get();
    if($response) {
      $data['status'] = "true";
      $data['message'] = "success";
      $data['result'] = $response;
    } else {
      $data['status'] = "false";
      $data['message'] = "Ooops..! Something went wrong";
    }
    return response()->json($data);
  }
}
