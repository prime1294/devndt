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
use App\Model\Karigar;
use App\Model\Banks;
use App\Model\WidLessAmount;
use App\Model\DailyProduction;
use App\Model\FrameReport;
use App\Model\KarigarReport;
use App\Model\KarigarPayment;
use App\Model\Machine;
use App\Model\BanksUser;
use App\Model\BankTransection;
use App\Model\CashTransection;
use App\Model\ChequeTransection;

class KarigarController extends Controller
{
  public function __construct() {

  }

  public function karigar(Request $request)
  {
      return view('admin.v1.karigar.list');
  }

  public function karigarNew(Request $request)
  {
      $data['type'] = 'add';
      $data['pinfo'] = [];
      $data['bank_list'] = Banks::orderBy('name','ASC')->get();
      $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
      return view('admin.v1.karigar.new',$data);
  }

  private function karigarList($column = 'karigar.id',$order = 'DESC') {
    $user = Sentinel::getUser();
    return Karigar::select('karigar.*','states.state as mystate','cities.city as mycity')
    ->leftjoin('states','karigar.state','=','states.state_id')
    ->leftjoin('cities','karigar.city','=','cities.city_id')
    ->where('karigar.user_id',$user->id)
    ->where('karigar.status',1)
    ->groupBy('karigar.id')
    ->orderBy($column,$order);
  }

  public function getKarigarJson(Request $request)  {
    $karigar = self::karigarList()->where('karigar.status',1)->get();
    return response()->json($karigar);
  }

  public function getKarigar(Request $request)  {
    $user = Sentinel::getUser();

    //order logic
    if(request('columns')[0]['orderable'] == "true" && isset(request('order')[0]['dir'])) {
      $order = request('order')[0]['dir'];
      $column = 'karigar.name';
      $karigar = self::karigarList($column,$order);
    } else {
      $karigar = self::karigarList();
    }

    return DataTables::of($karigar)
    ->addColumn('party', function ($karigar) {
        $html = '';
        $html .= '<img src="'.asset($karigar->photo).'" alt="'.$karigar->name.'" class="img-circle img-responsive" style="display:inline-block;vertical-align:top;" width="40"> <span style="display: inline-block;margin-left:5px;">'.$karigar->name.'<br><span class="text-muted"><i class="fa fa-inr"></i> '.$karigar->business_name.'</span></span>';
        return $html;
    })
    ->addColumn('transection_amount', function ($party) use ($user) {
        $html = '';
        $search['table'] = 'karigar';
        $search['user_id'] = $user->id;
        $search['id'] = $party->id;
        $search['master_type'] = 'master8';

        $amount = DB::select(Admin::masterTransectionQuery($search));
        $html .= Admin::FormateTransection(collect($amount)->sum('transection_amount'));
        return $html;
    })
    ->addColumn('gstininfo', function ($karigar) {
        $html = '';
        if($karigar->mycity && $karigar->mystate) {
            $html .= $karigar->mycity.', '.$karigar->mystate;
        }
        return $html;
    })
    ->addColumn('contactinfo', function ($karigar) {
        $html = '';
        $html .= $karigar->mobile.'<br><span class="text-muted">'.$karigar->alt_mobile.'</span>';
        return $html;
    })
    ->addColumn('action', function ($karigar) {
        //$activation_status = $karigar->status == 1 ? 'checked' : "";
        $html = '';
        //$html .= '<input type="checkbox" class="status_checkbox" data-id=" '.$karigar->id.'" '.$activation_status.' data-size="mini" data-toggle="toggle" data-on="Active" data-off="Deactive" data-onstyle="success" data-offstyle="danger">';
        $html .= ' <a href="'.route('karigar.edit',$karigar->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        $html .= ' <a href="'.route('karigar.remove',$karigar->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        return $html;
    })
    ->filterColumn('party', function($query, $keyword) {
       $query->whereRaw("CONCAT(karigar.name,' ',karigar.business_name) like ?", ["%{$keyword}%"]);
    })
    ->filterColumn('gstininfo', function($query, $keyword) {
       $query->whereRaw("CONCAT(states.state,', ',cities.city) like ?", ["%{$keyword}%"]);
    })
    ->filterColumn('contactinfo', function($query, $keyword) {
       $query->whereRaw("CONCAT(karigar.mobile,' ',karigar.alt_mobile) like ?", ["%{$keyword}%"]);
    })
    ->rawColumns(['party','transection_amount','gstininfo','contactinfo', 'action'])
    ->make(true);
  }

  public function ActivationKarigar(Request $request) {
    $karigar = Karigar::find(request('id'));
    $karigar->status = request('status');
    $karigar->save();
    return $karigar;
  }

  public function removeKarigar(Request $request,$id)
  {
    $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:karigar,id']);
    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

//    $user =  Karigar::find($id)->delete();
      $user =  Karigar::find($id);
      $user->status = 0;
      $user->save();
    if($user) {
      return redirect()->route('karigar')->with('success', "Karigar Removed Successfully");
    } else {
      return redirect()->route('karigar')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function editKarigar(Request $request,$id) {
    $data['type'] = 'edit';
    $data['pinfo'] = Karigar::find($id);
    $data['bank_list'] = Banks::orderBy('name','ASC')->get();
    $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
    return view('admin.v1.karigar.new',$data);
  }

  public function updateKarigar(Request $request,$id) {
    $validator = Validator::make($request->all(), [
        'person_salary' => 'required',
        'person_name' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $update = [
      'business_name' => request('person_salary'),
      'name' => request('person_name'),
      'email' => request('email'),
      'mobile' => request('mobile'),
      'alt_mobile' => request('alt_no'),
//      'staff_id_proff' => request('id_proff'),
      'address' => request('address'),
      'country' => '101',
      'state' => request('state'),
      'city' => request('city'),
      'pincode' => request('pincode'),
      'is_bank_detail' => request('confirmAns') != null ? request('confirmAns') : 0,
      'bank_person_name' => request('bank_person_name'),
      'account_number' => request('bank_account_no'),
      'bank_name' => request('bank_name'),
      'account_type' => request('account_type'),
      'ifsc_code' => request('bank_ifsc'),
      'branch' => request('bank_branch'),
      'opening_balance' => request('opening_balance') != null ? request('opening_balance') : 0,
      'opening_type' => request('opening_type'),
      'opening_asof' => request('asof') != null ? date('Y-m-d',strtotime(request('asof'))) : date('Y-m-d',strtotime("now")),
      'remarks' => request('remarks'),
    ];

      if (request('fbinputtxt')) {
          $update['photo'] = request('fbinputtxt');
      }

    if ($request->hasFile('id_select')) {
      $dir = 'image/karigar/';
      $image = $request->file('id_select');
      $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
      $destinationPath = public_path($dir);
      $imagepath = $image->move($destinationPath, $name);
      $update['staff_image'] = $dir.$name;
    }

    $request = Karigar::where('id',$id)->update($update);
    if($request) {
      return redirect()->route('karigar')->with('success', "Karigar updated Successfully");
    } else {
      return redirect()->route('karigar')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function removeWidLessAmount(Request $request,$id) {
    $user = Sentinel::getUser();
    $response = WidLessAmount::where('user_id',$user->id)->where('id',$id)->forceDelete();
    if($response) {
      $data['status'] = "true";
      $data['message'] = "success";
    } else {
      $data['status'] = "false";
      $data['message'] = "Ooops..! Something went wrong";
    }
    return response()->json($data);
  }

  public function infoWidLessAmount(Request $request,$id) {
    $response = WidLessAmount::find($id);
    if($response) {
      $response['date'] = date('D M d Y H:i:s O',strtotime($response['date']));
      $response['amount'] = intval(abs($response['amount']));
      $data['status'] = "true";
      $data['message'] = "success";
      $data['result'] = $response;
    } else {
      $data['status'] = "false";
      $data['message'] = "Ooops..! Something went wrong";
    }
    return response()->json($data);
  }

  public function registerWidLessAmount(Request $request,$id) {
    $validator = Validator::make($request->all(), [
        'wl_date' => 'required',
        'wl_type' => 'required',
        'wl_amount' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $user = Sentinel::getUser();
    $ins['user_id'] = $user->id;
    $ins['master_id'] = $user->id;
    $ins['date'] = request('wl_date') != null ? date('Y-m-d',strtotime(request('wl_date'))) : null;
    $ins['type'] = request('wl_type');
    $ins['amount'] = '-'.request('wl_amount');
    $ins['remarks'] = request('wl_remarks');
    $response = WidLessAmount::insert($ins);

    if($response) {
      $data['status'] = "true";
      $data['message'] = "success";
    } else {
      $data['status'] = "false";
      $data['message'] = "Ooops..! Something went wrong";
    }
    return response()->json($data);
  }

  public function updateWidLessAmount(Request $request,$id) {
    $validator = Validator::make($request->all(), [
        'wl_date' => 'required',
        'wl_type' => 'required',
        'wl_amount' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $user = Sentinel::getUser();
    $wlamount = WidLessAmount::find($id);
    $wlamount->date = request('wl_date') != null ? date('Y-m-d',strtotime(request('wl_date'))) : null;
    $wlamount->type = request('wl_type');
    $wlamount->amount = '-'.request('wl_amount');
    $wlamount->remarks = request('wl_remarks');
    $response = $wlamount->save();

    if($response) {
      $data['status'] = "true";
      $data['message'] = "success";
    } else {
      $data['status'] = "false";
      $data['message'] = "Ooops..! Something went wrong";
    }
    return response()->json($data);
  }

  public function registerKarigar(Request $request) {
    $validator = Validator::make($request->all(), [
        'person_salary' => 'required',
        'person_name' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }


    $staffid = '';
    if ($request->hasFile('id_select')) {
      $dir = 'image/karigar/';
      $image = $request->file('id_select');
      $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
      $destinationPath = public_path($dir);
      $imagepath = $image->move($destinationPath, $name);
      $staffid = $dir.$name;
    }

    $user = Sentinel::getUser();
    $response = Karigar::insert([
      'user_id' => $user->id,
      'business_name' => request('person_salary'),
      'name' => request('person_name'),
      'photo' => request('fbinputtxt') ? request('fbinputtxt') : 'user_404.jpg',
      'email' => request('email'),
      'mobile' => request('mobile'),
      'alt_mobile' => request('alt_no'),
//      'staff_id_proff' => request('id_proff'),
      'staff_image' => $staffid,
      'address' => request('address'),
      'country' => '101',
      'state' => request('state'),
      'city' => request('city'),
      'pincode' => request('pincode'),
      'is_bank_detail' => request('confirmAns') != null ? request('confirmAns') : 0,
      'bank_person_name' => request('bank_person_name'),
      'account_number' => request('bank_account_no'),
      'bank_name' => request('bank_name'),
      'account_type' => request('account_type'),
      'ifsc_code' => request('bank_ifsc'),
      'branch' => request('bank_branch'),
      'opening_balance' => request('opening_balance') != null ? request('opening_balance') : 0,
      'opening_type' => request('opening_type'),
      'opening_asof' => request('asof') != null ? date('Y-m-d',strtotime(request('asof'))) : date('Y-m-d',strtotime("now")),
      'remarks' => request('remarks'),
    ]);

    if($response) {
      return Admin::checkRedirect($request,'karigar',"Karigar registred Successfully");
    } else {
      return redirect()->route('karigar')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function karigarView(Request $request,$id) {
    $user = Sentinel::getUser();
    if(Karigar::HaveRightBank($user->id,$id)) {
      $data['info'] = Karigar::find($id);
      $data['machine_list'] = Machine::where('status',1)->where('user_id',$user->id)->orderBy('id','DESC')->get();

      //total
      $search['table'] = 'karigar';
      $search['user_id'] = $user->id;
      $search['id'] = $id;
      $search['master_type'] = 'master8';
//      echo   Admin::masterTransectionQuery($search); die();
      $amount = DB::select(Admin::masterTransectionQuery($search));
      $data['total_amount'] = collect($amount)->sum('transection_amount');

      //karigar report
      $data['monthly_report'] = [];
      $month_list = Admin::getMonthTimestamp();
      foreach($month_list as $row) {
        $child = [];
        $date = '01 '.$row;
        $child['month_name'] = $row;
        $child['month'] = date('m',strtotime($date));
        $child['year'] = substr($row, -4);
        $month_report = self::getMonthWiseKarigarReport($id,$child['month'],$child['year']);
        $child = $child+$month_report;
        array_push($data['monthly_report'],$child);
      }
//       print_r($data['monthly_report']); die();
      return view('admin.v1.karigar.view',$data);
    } else {
      return Admin::unauth();
    }
  }

  function getMonthWiseKarigarReport($id,$month,$year) {
    $user = Sentinel::getUser();
    $total_stitch = 0;
    $total_salary = 0;
    $total_bonus = 0;
    $total_payment = 0;
    $payment_count = 0;
    $calculated_salary = 0;

    $report = KarigarReport::where('user_id',$user->id)->where('status',1)->where('karigar_id',$id)->where(DB::Raw('month(date)'),$month)->where(DB::Raw('year(date)'),$year)->get();
    $report2 = KarigarReport::where('user_id',$user->id)->where('status',1)->where('karigar_id',$id)->where(DB::Raw('month(date)'),$month)->where(DB::Raw('year(date)'),$year)->groupBy('date')->get();
    $show_total_stitch = $report->sum('stitch');
    $total_stitch = $report->sum('stitch');
    $total_salary = $report->sum('salary');
    $total_bonus = $report->sum('bonus');
    $calculated_salary = number_format($total_salary + $total_bonus,2);
    $count_days = $report2->count();

    $payment = KarigarPayment::where('user_id',$user->id)->where('status',1)->where('karigar_id',$id)->where('month',$month)->where('year',$year)->get();
    $total_payment = $payment->sum('amount');
    $payment_count = $payment->count('id');
    $pstatus = "Unknown";
    if($calculated_salary == "0.00") {
      $pstatus = "Pending";
    } else if($calculated_salary == $total_payment) {
        $pstatus = "Completed";
    } else if($calculated_salary < $total_payment) {
      $pstatus = "Over Paid";
    }  else {
      $pstatus = "Pending";
    }

    return ["show_stitch"=>$show_total_stitch,"days_count"=>$count_days,"payment_count"=>$payment_count,"calculated_salary"=>$calculated_salary,"payment_status"=>$pstatus,"total_stitch"=>$total_stitch,"total_salary"=>$total_salary,"total_bonus"=>$total_bonus,"total_payment"=>$total_payment];
  }

  public function getWidLessAmount(Request $request,$id) {
    $user = Sentinel::getUser();
    $master = WidLessAmount::where('user_id',$user->id)->where('status',1)->orderBy('date','DESC');
    return DataTables::of($master)
    ->addColumn('formated_date', function ($master) {
        $html = '';
        $html .= Admin::FormateDate($master->date);
        return $html;
    })
    ->addColumn('formated_type', function ($master) {
        $html = '';
        $html .= $master->type == 1 ? "Withdrawal Amount" : "Less Amount";
        return $html;
    })
    ->addColumn('formated_amount', function ($master) {
        $html = '';
        $html .= Admin::FormateTransection($master->amount);
        return $html;
    })
    ->addColumn('action', function ($master) {
        $html = '';
        $html .= '<button type="button" class="btn btn-primary btn-xs" onClick="return editthisrecord('.$master->id.')" data-toggle="modal" data-target="#addWidLessAmount"><i class="fa fa-edit"></i> Edit</button>';
        $html .= ' <a class="btn btn-danger btn-xs" onClick="return removethisrecord('.$master->id.')" ><i class="fa fa-trash"></i> Delete</button>';
        return $html;
    })
    ->rawColumns(['formated_date','formated_type','formated_amount','action'])
    ->make(true);
  }

  public function karigarTransection(Request $request,$id) {
    $user = Sentinel::getUser();
    $search['table'] = 'karigar';
    $search['user_id'] = $user->id;
    $search['id'] = $id;
    $search['master_type'] = 'master8';

      if(request('filter_by') != "") {
          $search['type'] = request('filter_by');
      }

      if(request('bill_no') != "") {
          $search['bill_no'] = request('bill_no');
      }

      if(request('startdate') != "" && request('enddate') != "") {
          $search['startdate'] = request('startdate');
          $search['enddate'] = request('enddate');
      }

    $master = DB::select(Admin::masterTransectionQuery($search));
    return DataTables::of($master)
    ->addColumn('formated_date', function ($master) {
        $html = '';
        $html .= Admin::FormateDate($master->transection_date);
        return $html;
    })
    ->addColumn('formated_number', function ($master) {
        $html = '';
        $html .= $master->transection_recipt_no;
        return $html;
    })
    ->addColumn('formated_type', function ($master) {
        $html = '';
        $type = config('transection.'.$master->transection_type)['type'];
        $html .= $type;
        if($type == "Expenses") {
            $html = $master->transection_remarks;
            $html .= ' ('.$type.')';
        }
        return $html;
    })
    ->addColumn('formated_amount', function ($master) {
        $html = '';
        $html .= Admin::FormateTransection($master->transection_amount,false);
        if($master->transection_type == "EXPENSES") {
            $html = Admin::FormateTransection($master->transection_amount+$master->transection_recive,false);
        }
        return $html;
    })
    ->addColumn('transection_recive', function ($master) {
        $html = '';
        if($master->transection_recive != "") {
            $html .= Admin::FormateTransection($master->transection_recive,false);
        }
        return $html;
    })
    ->addColumn('transection_paid', function ($master) {
        $html = '';
        if($master->transection_paid != "") {
            $html .= Admin::FormateTransection($master->transection_paid,false);
        }
        return $html;
    })
    ->addColumn('action', function ($master) use ($id) {
        $transection_type = config('transection.'.$master->transection_type);
        $html = '';
        if($transection_type['edit_at'] != "") {
        $html .= ' <a href="'.route("redirecting").'?redirectback=karigar.view&id='.$id.'&redirect='.$transection_type['edit_at'].'&toid='.$master->transection_id.'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        }
        if($transection_type['deleted_at'] != "") {
        $html .= ' <a href="'.route("redirecting").'?redirectback=karigar.view&id='.$id.'&redirect='.$transection_type['deleted_at'].'&toid='.$master->transection_id.'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        }
        return $html;
    })
    ->rawColumns(['formated_date','formated_number','formated_type','formated_amount','transection_recive','transection_paid','action'])
    ->make(true);
  }


  function syncreport($id,$month,$year) {
    $user = Sentinel::getUser();
    $lastday = Admin::getlastdateofmonth($month,$year);
    for($i=1;$i<=$lastday;$i++) {
      $check = KarigarReport::
      where('date',DB::Raw("date('".$year."-".$month."-".$i."')"))
      ->where('user_id',$user->id)
      ->where('karigar_id',$id)->count();
      if($check == 0) {
        $result = DailyProduction::
        where('date',DB::Raw("date('".$year."-".$month."-".$i."')"))
        ->where('user_id',$user->id)
        ->where('karigar',$id)
        ->where('status',1)->get();

        foreach($result as $row) {
          if($row['date'] != "" && $row['karigar'] != "") {
            $ins['user_id'] = $user->id;
            $ins['karigar_id'] = $id;
            $ins['date'] = $row['date'];
            $ins['machine'] = $row['machine'];
            $ins['ship'] = $row['ship'];
            $ins['stitch'] = $row['stitch'];
            $ins['salary_type'] = 0;
            $ins['salary'] = 0;
            $ins['bonus'] = 0;
            // $frame_report = FrameReport::where('user_id',$user->id)->where('status',1)->where('production_id',$row['id'])->get()->toArray();
            // if(!empty($frame_report)) {
            //   $ins['work_history'] = serialize($frame_report);
            // }
            KarigarReport::insert($ins);
          }
        }
      }
    }
  }

  public function manageKarigarReport(Request $request,$id,$month,$year) {
    $user = Sentinel::getUser();
    //self::syncreport($id,$month,$year);
    $data['machine_list'] = Machine::where('status',1)->where('user_id',$user->id)->orderBy('id','DESC')->get();
    $lastday = Admin::getlastdateofmonth($month,$year);
    $data['payment_route'] = ["id"=>$id,"month"=>$month,"year"=>$year];
    $data['report_month'] = date('F, Y',strtotime($year.'-'.$month.'-01'));
    $data['karigar_info'] = Karigar::find($id);
    $data['fixed_salary'] = number_format(($data['karigar_info']->business_name / $lastday),2);
    $data['karigar_payment'] = KarigarPayment::where('user_id',$user->id)->where('karigar_id',$id)->where('month',$month)->where('year',$year)->get();
    $data['karigar_report'] = KarigarReport::select('karigar_report.*','machine.machine_no as machine_no','machine.company_name as company_name')
    ->leftjoin('machine','karigar_report.machine','=','machine.id')
    ->where(DB::Raw("month(karigar_report.date)"),$month)
    ->where(DB::Raw("year(karigar_report.date)"),$year)
    ->where('karigar_report.user_id',$user->id)
    ->where('karigar_report.karigar_id',$id)
    ->where('karigar_report.status',1)
    ->orderBy('karigar_report.date','ASC')
    ->get();
      $data['bank_list'] = self::myBanks();
    return view('admin.v1.karigar.report',$data);
  }

  public function infoKarigarReport(Request $request,$id) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        "id"    => "required",
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      $data['message'] = $errors->first();
      return response()->json($data);
    }

    $response = KarigarReport::where('user_id',$user->id)->where('karigar_id',$id)->where('id',request('id'))->first()->toArray();
    if(!empty($response)) {
      $data['status'] = "true";
      $data['message'] = "success";
      $response['date_new'] = date('D M d Y H:i:s O',strtotime($response['date']));
      $data['result'] = $response;
    } else {
      $data['status'] = "false";
      $data['message'] = "Ooops...! Something went wrong";
    }
    return response()->json($data);

  }

  public function deleteKarigarReport(Request $request,$id) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        "id"    => "required",
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      $data['message'] = $errors->first();
      return response()->json($data);
    }

    $response = KarigarReport::where('user_id',$user->id)->where('karigar_id',$id)->where('id',request('id'))->update(["status"=>0]);
    if($response) {
      $data['status'] = "true";
      $data['message'] = "success";
    } else {
      $data['status'] = "false";
      $data['message'] = "Ooops...! Something went wrong";
    }
    return response()->json($data);
  }

  public function deleteKarigarPayment(Request $request,$id) {
      $user = Sentinel::getUser();
      $karigar_payment = KarigarPayment::where('user_id',$user->id)->where('id',$id)->first();
      $response = self::removeAllTrasection($karigar_payment->tid,true);
      $karigar_payment->forceDelete();
      if(request('redirect')) {
          if($response) {
              return redirect()->route(request('redirect'))->with('success', "Transection deleted Successfully");
          } else {
              return redirect()->route(request('redirect'))->with('error', "Ooops..! Something went wrong");
          }
      } else {
          if($response) {
              $data['status'] = "true";
              $data['message'] = "success";
          } else {
              $data['status'] = "false";
              $data['message'] = "Ooops...! Something went wrong";
          }
          return response()->json($data);
      }
  }


  public function manageKarigarPayment(Request $request,$id,$month,$year) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        "sp_date"    => "required",
        'sp_by' => 'required',
        'sp_type' => 'required',
        "sp_amount"    => "required",
    ]);


    if ($validator->fails()) {
      $errors = $validator->errors();
      $data['message'] = $errors->first();
      return response()->json($data);
    }


    $ins['user_id'] = $user->id;
    $ins['karigar_id'] = $id;
    $ins['month'] = $month;
    $ins['year'] = $year;

    $amount = request('sp_amount');
    $remarks = request('sp_remarks');
    $by = request('sp_by');
    $type = request('sp_type');
    $sp_date = request('sp_date');

    $info = Karigar::find($id);
    $tremarks = ucwords($info->name).' (Karigar)';


    //trash all old data
    $old_payment = KarigarPayment::where('user_id',$user->id)->where('karigar_id',$id)->where('month',$month)->where('year',$year)->get();
    foreach($old_payment as $row) {
        self::removeAllTrasection($row->tid,true);
    }
    KarigarPayment::where('user_id',$user->id)->where('karigar_id',$id)->where('month',$month)->where('year',$year)->forceDelete();

    foreach($amount as $key=>$row) {
      if($amount[$key] != "" && $by[$key] != "" && $sp_date[$key] != "") {
      $tfiletype = $type[$key] == "2" ? "WITHDRAWAL" : "SALARY";
      $tid = Admin::uniqueTransectionId($tfiletype, $user->id);
      $payment_type = $by[$key];
      $new_date = date('Y-m-d', strtotime($sp_date[$key]));


      $ins['date'] = $new_date;
      $ins['pay_by'] = $by[$key];
      $ins['pay_type'] = $type[$key];
      $ins['amount'] = $amount[$key];
      $ins['remarks'] = $remarks[$key];
      $ins['tid'] = $tid;
      $lastins = KarigarPayment::insertGetId($ins);

      //do entry in relevent table
      if($type[$key] == "1" || $type[$key] == "2") {
          if (strpos($payment_type, 'bank_ref_') !== false) {
              //bank transection
              $tfiletype2 = $type[$key] == "2" ? "bank12" : "bank11";
              $bank_user_id = explode('_',$payment_type);
              $bank_user_id = end($bank_user_id);
              $response2 = self::bankTransection($bank_user_id,$amount[$key],$tremarks,$new_date,$lastins,$tid,$tfiletype2);
          } else {
              if ($payment_type == "cheque") {
                  //cheque
                  $tfiletype2 = $type[$key] == "2" ? "cheque7" : "cheque6";
                  $response2 = self::chequeTransection($amount[$key],$tremarks,$new_date,$lastins,$remarks[$key],$tid,$tfiletype2);
              } else {
                  //cash
                  $tfiletype2 = $type[$key] == "2" ? "cash10" : "cash9";
                  $response2 = self::cashTransection($amount[$key],$tremarks,$new_date,$lastins,$tid,$tfiletype2);
              }
          }
      }

      }
    }

    $data['status'] = "true";
    $data['message'] = "success";
    return response()->json($data);
  }

    private function chequeTransection($bank_amount,$remarks,$adjustmentDate,$paymentid,$refno,$tid,$transection_type) {
        $math_type = "-";
        //cheque +
        $user = Sentinel::getUser();
        $cash['user_id'] = $user->id;
        $cash['type'] = $transection_type;
        $cash['amount'] = $math_type.$bank_amount;
        $cash['remarks'] = $remarks;
        $cash['transection_date'] = $adjustmentDate;
        $cash['ref_no'] = $refno;
        $cash['ref_tbl_no'] = $paymentid;
        $cash['tid'] = $tid;
        $response = ChequeTransection::insert($cash);
        return $response ? true : false;
    }

    private function bankTransection($bank_user_id,$bank_amount,$remarks,$adjustmentDate,$paymentid,$tid,$transection_type) {
        $math_type = "-";

        $user = Sentinel::getUser();
        $bank['user_id'] = $user->id;
        $bank['bank_user_id'] = $bank_user_id;
        $bank['type'] = $transection_type;
        $bank['amount'] = $math_type.$bank_amount;
        $bank['remarks'] = $remarks;
        $bank['transection_date'] = $adjustmentDate;
        $bank['ref_tbl_no'] = $paymentid;
        $bank['tid'] = $tid;
        $response = BankTransection::insert($bank);
        return $response ? true : false;
    }

    private function cashTransection($bank_amount,$remarks,$adjustmentDate,$paymentid,$tid,$transection_type) {
        $math_type = "-";
        //cash +
        $user = Sentinel::getUser();
        $cash['user_id'] = $user->id;
        $cash['type'] = $transection_type;
        $cash['amount'] = $math_type.$bank_amount;
        $cash['remarks'] = $remarks;
        $cash['transection_date'] = $adjustmentDate;
        $cash['ref_tbl_no'] = $paymentid;
        $cash['tid'] = $tid;
        $response = CashTransection::insert($cash);
        return $response ? true : false;
    }

    private function removeAllTrasection($tid,$force = "false") {
        $user = Sentinel::getUser();
        $respose = BankTransection::where('tid',$tid);
        $respose2 = CashTransection::where('tid',$tid);
        $respose3 = ChequeTransection::where('tid',$tid);
        if($force == "true") {
            $respose->forceDelete();
            $respose2->forceDelete();
            $respose3->forceDelete();
        } else {
            $respose->delete();
            $respose2->delete();
            $respose3->delete();
        }
        return true;
    }

    public function salaryRedirect(Request $request,$id) {
      $user = Sentinel::getUser();
      $info = KarigarPayment::where('user_id',$user->id)->where('id',$id)->first();
      return redirect()->route('manage.karigar.report',["id"=>$info->karigar_id,"month"=>str_pad($info->month, 2, "0", STR_PAD_LEFT),"year"=>$info->year]);
    }

  public function registerKarigarReport(Request $request,$id) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        'adjustment_date' => 'required',
        "machine"    => "required",
        "ship"    => "required",
        "stitch"    => "required",
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $ins['user_id'] = $user->id;
    $ins['karigar_id'] = $id;
    $ins['date'] = date('Y-m-d',strtotime(request('adjustment_date')));
    $ins['machine'] = request('machine');
    $ins['ship'] = request('ship');
    $ins['stitch'] = request('stitch');
    $ins['salary'] = 0;
    $ins['bonus'] = 0;
    $response = KarigarReport::insert($ins);

    if($response) {
      return redirect()->route('karigar.view',$id)->with('success', "Report Added Successfully");
    } else {
      return redirect()->route('karigar.view',$id)->with('error', "Ooops..! Something went wrong");
    }

  }
  public function updateKarigarReport(Request $request,$id = null,$month = null ,$year = null) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        'report_id' => 'required',
        'adjustment_date' => 'required',
        "machine"    => "required",
        "ship"    => "required",
        "stitch"    => "required",
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $karigar_report = KarigarReport::find(request('report_id'));
    $karigar_report->date = date('Y-m-d',strtotime(request('adjustment_date')));
    $karigar_report->machine = request('machine');
    $karigar_report->ship = request('ship');
    $karigar_report->stitch = request('stitch');
    $karigar_report->tbno = request('tb_no');
    if($karigar_report->salary_type == 2) {
      $karigar_report->salary = round($karigar_report->stitch / 1000);
    }
    $response = $karigar_report->save();
    if(isset($id) && isset($month) && isset($year)) {
        $redirect_params = ["id" => $id, "month" => $month, "year" => $year];
        if ($response) {
            return redirect()->route('manage.karigar.report', $redirect_params)->with('success', "Report Updated Successfully");
        } else {
            return redirect()->route('manage.karigar.report', $redirect_params)->with('error', "Ooops..! Something went wrong");
        }
    } else {
        if ($response) {
            return redirect()->route('daily.production')->with('success', "Report Updated Successfully");
        } else {
            return redirect()->route('daily.production')->with('error', "Ooops..! Something went wrong");
        }
    }
  }

  public function updateKarigarSalary(Request $request,$id) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        'type' => 'required|in:0,1,2',
        "id"    => "required",
        "salary"    => "required",
        "bonus"    => "required",
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      $data['message'] = $errors->first();
      return response()->json($data);
    }

    $unique = request('id');
    $karigar_report = KarigarReport::find($unique);
    $karigar_report->salary_type = request('type');
    $karigar_report->salary = request('salary');
    $karigar_report->bonus = request('bonus');
    $response = $karigar_report->save();

    if($response) {
      $data['status'] = "true";
      $data['message'] = "success";
    } else {
      $data['status'] = "false";
      $data['message'] = "Ooops...! Something went wrong";
    }
    return response()->json($data);
  }


    private function myBanks() {
        $user = Sentinel::getUser();
        return BanksUser::select(
            'banks_users.id','banks_users.bank_id','banks_users.name','banks_users.account_no','banks_users.ifsc','banks_users.type','banks_users.branch','banks_users.status',
            'banks.name as bankname','banks.icon as bankicon',
            DB::raw('COALESCE(SUM(bank_transection.amount),0) as bankbalance')
        )
            ->leftJoin('banks', function($join) {
                $join->on('banks_users.bank_id', '=', 'banks.id');
                $join->where('banks_users.status', '=', '1');
                $join->whereNull('banks_users.deleted_at');
            })
            ->leftJoin('bank_transection', function($join)  use($user) {
                $join->on('banks_users.id', '=', 'bank_transection.bank_user_id');
                $join->on('bank_transection.user_id', '=', DB::raw('"'.$user->id.'"'));
                $join->where('bank_transection.status', '=', '1');
                $join->whereNull('bank_transection.deleted_at');
            })
            ->where('banks.status',1)
            ->groupBy('banks_users.id')
            ->get();
    }

}
