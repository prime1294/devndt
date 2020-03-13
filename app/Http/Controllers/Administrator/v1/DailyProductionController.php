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
use App\Model\DailyProduction;
use App\Model\Machine;
use App\Model\Karigar;
use App\Model\ProgrammeCard;
use App\Model\StockUnit;
use App\Model\KarigarReport;

class DailyProductionController extends Controller
{
  public function __construct() {

  }


  public function dailyProdution(Request $request) {
    $user = Sentinel::getUser();
    $data['machine_list'] = Machine::where('status',1)->where('user_id',$user->id)->orderBy('id','DESC')->get();
    $data['karigar_list'] = Karigar::where('status',1)->where('user_id',$user->id)->orderBy('id','DESC')->get();
    $data['pc_list'] = ProgrammeCard::where('status',1)->where('user_id',$user->id)->orderBy('id','DESC')->get();
    return view('admin.v1.dailyproduction.list',$data);
  }

  public function adddailyProdution(Request $request,$date = null) {
    $user = Sentinel::getUser();
    $data['machine_list'] = Machine::where('status',1)->where('user_id',$user->id)->orderBy('id','DESC')->get();
    $data['karigar_list'] = Karigar::where('status',1)->where('user_id',$user->id)->orderBy('id','DESC')->get();
    $data['pc_list'] = ProgrammeCard::where('status',1)->where('user_id',$user->id)->orderBy('id','DESC')->get();
    $data['stock_unit'] = StockUnit::where('status',1)->orderBy('name','ASC')->get();
    $data['today_date'] = isset($date) ? $date : date('d-m-Y',strtotime("now"));
    return view('admin.v1.dailyproduction.add',$data);
  }

  public function fetchDailyProdution(Request $request) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        'adjustment_date' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      $data['message'] = $errors->first();
      return response()->json($data);
    }

    $adjustment_date = date('Y-m-d',strtotime(request('adjustment_date')));
    $response = DailyProduction::where('user_id',$user->id)->where('date',$adjustment_date)->get();
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

  public function deleteDailyProdution(Request $request,$id) {
      $user = Sentinel::getUser();
      $response = KarigarReport::where('id',$id)->where('user_id',$user->id)->delete();
      if($response) {
          return redirect()->route('daily.production')->with('success', "Production Removed Successfully");
      } else {
          return redirect()->route('daily.production')->with('error', "Ooops..! Something went wrong");
      }
  }

  public function registerDailyProdution(Request $request) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        'adjustment_date' => 'required',
        "machine"    => "required|array|min:1",
        "machine.*"  => "required|min:1",
        "karigar"    => "required|array|min:1",
        "karigar.*"  => "required|min:1",
        "ship"    => "required|array|min:1",
        "ship.*"  => "required|min:1",
        "stitch"    => "required|array|min:1",
        "stitch.*"  => "required|min:1",
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      $data['message'] = $errors->first();
      return response()->json($data);
    }

    $adjustment_date = date('Y-m-d',strtotime(request('adjustment_date')));
    $machine = request('machine');
    $karigar = request('karigar');
    $ship = request('ship');
    $tbno = request('tbno');
    $stitch = request('stitch');

    $flag = 0;
    //remove all old entry
    //DailyProduction::where('user_id',$user->id)->where('date',$adjustment_date)->forceDelete();
    foreach($machine as $key => $row) {
      if($machine[$key] != "" && $karigar[$key] != "") {
          $ins['user_id'] = $user->id;
          $ins['date'] = $adjustment_date;
          $ins['machine'] = $machine[$key];
          $ins['karigar_id'] = $karigar[$key];
          $ins['ship'] = $ship[$key];
          $ins['tbno'] = $tbno[$key];
          $ins['stitch'] = $stitch[$key];
          $ins['frame'] = 0;
          $ins['jobwork'] = 0;
          KarigarReport::insert($ins);
          $flag = 1;
      }
    }

    if($flag) {
      $data['status'] = "true";
      $data['message'] = "success";
    } else {
      $data['status'] = "false";
      $data['message'] = "Ooops...! Something went wrong";
    }
    return response()->json($data);
  }

  function getdailyreorts($search = []) {
    $user = Sentinel::getUser();
    $result = KarigarReport::query();
    $result->select('karigar_report.*',DB::raw('machine.company_name as machine_name'),'machine.photo as machine_photo','machine.machine_no as machine_no','karigar.name as staff_name','karigar.photo as staff_photo');
    $result->leftjoin('machine','karigar_report.machine','=','machine.id');
    $result->leftjoin('karigar','karigar_report.karigar_id','=','karigar.id');
    $result->leftjoin('frame_report','karigar_report.id','=','frame_report.production_id');
    $result->where('karigar_report.user_id',$user->id);
    $result->where('karigar_report.status',1);


    //date filter
    if(isset($search['startdate']) && isset($search['enddate'])) {
      if($search['startdate'] != "" && $search['enddate'] != "") {
        $result->whereBetween('karigar_report.date',[request('startdate'), request('enddate')]);
      }
    }

    //machine
    if(isset($search['machine'])) {
      if($search['machine'] != "") {
      $result->where('karigar_report.machine',$search['machine']);
      }
    }

  //karigar
  if(isset($search['karigar'])) {
      if($search['karigar'] != "") {
      $result->where('karigar_report.karigar_id',$search['karigar']);
      }
  }

  //programme card
  if(isset($search['programmecard'])) {
      if($search['programmecard'] != "") {
      $result->where('frame_report.pcard',$search['programmecard']);
      }
  }

    $result->groupBy('karigar_report.id');
    $result->orderBy('karigar_report.date','DESC');
    $result->orderBy('karigar_report.id','DESC');
    return $result;
  }



  public function dailyProdutionAjax(Request $request) {
    $user = Sentinel::getUser();
      $search = request()->all();
      $item = self::getdailyreorts($search);
    return DataTables::of($item)
    ->addColumn('formated_date', function ($item) {
        $html = '';
        $html .= Admin::FormateDate($item->date);
        return $html;
    })
    ->addColumn('machine_info', function ($item) {
        $html = '';
        $html .= '<img src="'.asset($item->machine_photo).'" alt="'.$item->machine_name.'" class="img-responsive img-rounded" style="display:inline-block;vertical-align:top;width:40px;"> <span style="display: inline-block;margin-left:5px;">'.$item->machine_no.' - '.$item->machine_name.'</span>';
        return $html;
    })
    ->addColumn('karigar_info', function ($item) {
        $html = '';
        $html .= '<img src="'.asset($item->staff_photo).'" alt="'.$item->staff_name.'" class="img-responsive img-circlemachine" style="display:inline-block;vertical-align:top;width:25px;"> <span style="display: inline-block;margin-left:5px;"><a href="'.route('karigar.view',$item->karigar_id).'">'.$item->staff_name.'</a></span>';
        return $html;
    })
    ->addColumn('karigar_ship', function ($item) {
        $html = '';
        $html .= $item->ship == 1 ? "Day" : "Night";
        return $html;
    })
    ->addColumn('action', function ($item) {
        $html = '';
        $html .= ' <a href="'.route('manage.frame.report',$item->id).'" class="btn btn-info btn-xs"><i class="fa fa-map"></i> Frame Report</a>';
        $html .= ' <a  data-toggle="modal" data-target="#editReportModal" onclick="getDetailofReport('.$item->id.','.$item->karigar_id.')" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        $html .= ' <a href="'.route('delete.daily.production',$item->id).'" onClick="return confirm(\'Are you sure want to remove this record?\')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a>';
        return $html;
    })
    ->rawColumns(['formated_date','machine_info','karigar_info','action'])
    ->make(true);
  }

}
