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
use App\Model\Staff;
use App\Model\Banks;

class StaffController extends Controller
{
  public function __construct() {

  }

  public function staff(Request $request)
  {
      return view('admin.v1.staff.list');
  }

  public function staffNew(Request $request)
  {
      $data['type'] = 'add';
      $data['pinfo'] = [];
      $data['bank_list'] = Banks::orderBy('name','ASC')->get();
      $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
      return view('admin.v1.staff.new',$data);
  }

  private function staffList($column = 'staff.id',$order = 'DESC') {
    $user = Sentinel::getUser();
    return Staff::select('staff.*','states.state as mystate','cities.city as mycity')
    ->leftjoin('states','staff.state','=','states.state_id')
    ->leftjoin('cities','staff.city','=','cities.city_id')
    ->where('staff.user_id',$user->id)
    ->where('staff.status',1)
    ->groupBy('staff.id')
    ->orderBy($column,$order);
  }

  public function getStaffJson(Request $request)  {
    $staff = self::staffList()->where('staff.status',1)->get();
    return response()->json($staff);
  }

  public function getStaff(Request $request)  {
    $user = Sentinel::getUser();

    //order logic
    if(request('columns')[0]['orderable'] == "true" && isset(request('order')[0]['dir'])) {
      $order = request('order')[0]['dir'];
      $column = 'staff.name';
      $staff = self::staffList($column,$order);
    } else {
      $staff = self::staffList();
    }

    return DataTables::of($staff)
    ->addColumn('party', function ($staff) {
        $html = '';
        $html .= '<img src="'.asset($staff->photo).'" alt="'.$staff->name.'" class="img-circle img-responsive" style="display:inline-block;vertical-align:top;" width="40"> <span style="display: inline-block;margin-left:5px;">'.$staff->name.'<br><span class="text-muted">'.$staff->business_name.'</span></span>';
        return $html;
    })
    ->addColumn('transection_amount', function ($party) use ($user) {
        $html = '';
        $search['table'] = 'staff';
        $search['user_id'] = $user->id;
        $search['id'] = $party->id;
        $search['master_type'] = 'master3';

        $amount = DB::select(Admin::masterTransectionQuery($search));
        $html .= Admin::FormateTransection(collect($amount)->sum('transection_amount'));
        return $html;
    })
    ->addColumn('gstininfo', function ($staff) {
        $html = '';
        if($staff->mystate && $staff->mycity) {
            $html .= $staff->mystate.', '.$staff->mycity;
        }
        return $html;
    })
    ->addColumn('contactinfo', function ($staff) {
        $html = '';
        $html .= $staff->mobile.'<br><span class="text-muted">'.$staff->alt_mobile.'</span>';
        return $html;
    })
    ->addColumn('action', function ($staff) {
        //$activation_status = $staff->status == 1 ? 'checked' : "";
        $html = '';
        //$html .= '<input type="checkbox" class="status_checkbox" data-id=" '.$staff->id.'" '.$activation_status.' data-size="mini" data-toggle="toggle" data-on="Active" data-off="Deactive" data-onstyle="success" data-offstyle="danger">';
        $html .= ' <a href="'.route('staff.edit',$staff->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        $html .= ' <a href="'.route('staff.remove',$staff->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        return $html;
    })
    ->filterColumn('party', function($query, $keyword) {
       $query->whereRaw("CONCAT(staff.name,' ',staff.business_name) like ?", ["%{$keyword}%"]);
    })
    ->filterColumn('gstininfo', function($query, $keyword) {
       $query->whereRaw("CONCAT(states.state,', ',cities.city) like ?", ["%{$keyword}%"]);
    })
    ->filterColumn('contactinfo', function($query, $keyword) {
       $query->whereRaw("CONCAT(staff.mobile,' ',staff.alt_mobile) like ?", ["%{$keyword}%"]);
    })
    ->rawColumns(['party','transection_amount','gstininfo','contactinfo', 'action'])
    ->make(true);
  }

  public function ActivationStaff(Request $request) {
    $staff = Staff::find(request('id'));
    $staff->status = request('status');
    $staff->save();
    return $staff;
  }

  public function removeStaff(Request $request,$id)
  {
    $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:staff,id']);
    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

//    $user =  Staff::find($id)->delete();
      $user =  Staff::find($id);
      $user->status = 0;
      $user->save();
    if($user) {
      return redirect()->route('staff')->with('success', "Staff Removed Successfully");
    } else {
      return redirect()->route('staff')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function editStaff(Request $request,$id) {
    $data['type'] = 'edit';
    $data['pinfo'] = Staff::find($id);
    $data['bank_list'] = Banks::orderBy('name','ASC')->get();
    $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
    return view('admin.v1.staff.new',$data);
  }

  public function updateStaff(Request $request,$id) {
    $validator = Validator::make($request->all(), [
        'person_name' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }
//      print_r(date('Y-m-d',strtotime(request('asof')))); die();
    $update = [
      'business_name' => request('party_name'),
      'name' => request('person_name'),
      'email' => request('email'),
      'mobile' => request('mobile'),
      'alt_mobile' => request('alt_no'),
      'staff_id_proff' => request('id_proff'),
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
      $dir = 'image/staff/';
      $image = $request->file('id_select');
      $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
      $destinationPath = public_path($dir);
      $imagepath = $image->move($destinationPath, $name);
      $update['staff_image'] = $dir.$name;
    }

    $request = Staff::where('id',$id)->update($update);
    if($request) {
      return redirect()->route('staff')->with('success', "Staff updated Successfully");
    } else {
      return redirect()->route('staff')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function registerStaff(Request $request) {
    $validator = Validator::make($request->all(), [
        'person_name' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }


    $staffid = '';
    if ($request->hasFile('id_select')) {
      $dir = 'image/staff/';
      $image = $request->file('id_select');
      $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
      $destinationPath = public_path($dir);
      $imagepath = $image->move($destinationPath, $name);
      $staffid = $dir.$name;
    }

    $user = Sentinel::getUser();
    $response = Staff::insert([
      'user_id' => $user->id,
      'business_name' => request('party_name'),
      'name' => request('person_name'),
      'photo' => request('fbinputtxt') ? request('fbinputtxt') : 'user_404.jpg',
      'email' => request('email'),
      'mobile' => request('mobile'),
      'alt_mobile' => request('alt_no'),
      'staff_id_proff' => request('id_proff'),
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
      return Admin::checkRedirect($request,'staff',"Staff registred Successfully");
    } else {
      return redirect()->route('staff')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function staffView(Request $request,$id) {
    $user = Sentinel::getUser();
    if(Staff::HaveRightBank($user->id,$id)) {
      $data['info'] = Staff::find($id);

      //total
      $search['table'] = 'staff';
      $search['user_id'] = $user->id;
      $search['id'] = $id;
      $search['master_type'] = 'master3';
      $amount = DB::select(Admin::masterTransectionQuery($search));
      $data['total_amount'] = collect($amount)->sum('transection_amount');

      return view('admin.v1.staff.view',$data);
    } else {
      return Admin::unauth();
    }
  }

  public function staffTransection(Request $request,$id) {
    $user = Sentinel::getUser();
    $search['table'] = 'staff';
    $search['user_id'] = $user->id;
    $search['id'] = $id;
    $search['master_type'] = 'master3';

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
        $html .= ' <a href="'.route("redirecting").'?redirectback=staff.view&id='.$id.'&redirect='.$transection_type['edit_at'].'&toid='.$master->transection_id.'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        }
        if($transection_type['deleted_at'] != "") {
        $html .= ' <a href="'.route("redirecting").'?redirectback=staff.view&id='.$id.'&redirect='.$transection_type['deleted_at'].'&toid='.$master->transection_id.'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        }
        return $html;
    })
    ->rawColumns(['formated_date','formated_number','formated_type','formated_amount','transection_recive','transection_paid','action'])
    ->make(true);
  }

}
