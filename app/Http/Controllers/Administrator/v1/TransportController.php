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
use App\Model\Transport;
use App\Model\Banks;

class TransportController extends Controller
{
  public function __construct() {

  }

  public function transport(Request $request)
  {
      return view('admin.v1.transport.list');
  }

  public function transportNew(Request $request)
  {
      $data['type'] = 'add';
      $data['pinfo'] = [];
      $data['bank_list'] = Banks::orderBy('name','ASC')->get();
      $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
      return view('admin.v1.transport.new',$data);
  }

  private function transportList($column = 'transport.id',$order = 'DESC') {
    $user = Sentinel::getUser();
    return Transport::select('transport.*','states.state as mystate','cities.city as mycity')
    ->leftjoin('states','transport.state','=','states.state_id')
    ->leftjoin('cities','transport.city','=','cities.city_id')
    ->where('transport.user_id',$user->id)
    ->groupBy('transport.id')
    ->orderBy($column,$order);
  }

  public function getTransportJson(Request $request) {
      $transport = self::transportList();
      return response()->json($transport);
  }

  public function getTransport(Request $request)  {
    $user = Sentinel::getUser();

    //order logic
    if(request('columns')[0]['orderable'] == "true" && isset(request('order')[0]['dir'])) {
      $order = request('order')[0]['dir'];
      $column = 'transport.name';
      $transport = self::transportList($column,$order);
    } else {
      $transport = self::transportList();
    }

    return DataTables::of($transport)
    ->addColumn('party', function ($transport) {
        $html = '';
        $html .= '<img src="'.asset($transport->photo).'" alt="'.$transport->name.'" class="img-circle img-responsive" style="display:inline-block;vertical-align:top;" width="30"> <span style="display: inline-block;margin-left:5px;">'.$transport->name.'<br><span class="text-muted">'.$transport->business_name.' - '.$transport->vehical_no.'</span></span>';
        return $html;
    })
    ->addColumn('transection_amount', function ($party) use ($user) {
        $html = '';
        $search['table'] = 'transport';
        $search['user_id'] = $user->id;
        $search['id'] = $party->id;
        $search['master_type'] = 'master7';

        $amount = DB::select(Admin::masterTransectionQuery($search));
        $html .= Admin::FormateTransection(collect($amount)->sum('transection_amount'));
        return $html;
    })
    ->addColumn('gstininfo', function ($transport) {
        $html = '';
        $html .= $transport->mystate.', '.$transport->mycity;
        return $html;
    })
    ->addColumn('contactinfo', function ($transport) {
        $html = '';
        $html .= $transport->mobile.'<br><span class="text-muted">'.$transport->alt_mobile.'</span>';
        return $html;
    })
    ->addColumn('action', function ($transport) {
        $activation_status = $transport->status == 1 ? 'checked' : "";
        $html = '';
        $html .= '<input type="checkbox" class="status_checkbox" data-id=" '.$transport->id.'" '.$activation_status.' data-size="mini" data-toggle="toggle" data-on="Active" data-off="Deactive" data-onstyle="success" data-offstyle="danger">';
        $html .= ' <a href="'.route('transport.edit',$transport->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        $html .= ' <a href="'.route('transport.remove',$transport->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        return $html;
    })
    ->filterColumn('party', function($query, $keyword) {
       $query->whereRaw("CONCAT(transport.name,' ',transport.business_name,' ',transport.vehical_no) like ?", ["%{$keyword}%"]);
    })
    ->filterColumn('gstininfo', function($query, $keyword) {
       $query->whereRaw("CONCAT(states.state,', ',cities.city) like ?", ["%{$keyword}%"]);
    })
    ->filterColumn('contactinfo', function($query, $keyword) {
       $query->whereRaw("CONCAT(transport.mobile,' ',transport.alt_mobile) like ?", ["%{$keyword}%"]);
    })
    ->rawColumns(['party','transection_amount','gstininfo','contactinfo', 'action'])
    ->make(true);
  }

  public function ActivationTransport(Request $request) {
    $staff = Transport::find(request('id'));
    $staff->status = request('status');
    $staff->save();
    return $staff;
  }

  public function removeTransport(Request $request,$id)
  {
    $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:transport,id']);
    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $user =  Transport::find($id)->delete();
    if($user) {
      return redirect()->route('transport')->with('success', "Transport Removed Successfully");
    } else {
      return redirect()->route('transport')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function editTransport(Request $request,$id) {
    $data['type'] = 'edit';
    $data['pinfo'] = Transport::find($id);
    $data['bank_list'] = Banks::orderBy('name','ASC')->get();
    $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
    return view('admin.v1.transport.new',$data);
  }

  public function updateTransport(Request $request,$id) {
    $validator = Validator::make($request->all(), [
        'party_name' => 'required',
        'person_name' => 'required',
        'vehical_no' => 'required',
        'mobile' => 'required',
        'id_proff' => 'required',
        'state' => 'required|exists:states,state_id',
        'city' => 'required|exists:cities,city_id'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $update = [
      'business_name' => request('party_name'),
      'name' => request('person_name'),
      'vehical_no' => request('vehical_no'),
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
      'opening_asof' => request('asof') != null ? date('Y-m-d',strtotime(request('asof'))) : null,
      'remarks' => request('remarks'),
    ];

    if ($request->hasFile('fbinput')) {
      $dir = 'image/transport/';
      $image = $request->file('fbinput');
      $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
      $destinationPath = public_path($dir);
      $imagepath = $image->move($destinationPath, $name);
      $update['photo'] = $dir.$name;
    }

    if ($request->hasFile('id_select')) {
      $dir = 'image/transport/';
      $image = $request->file('id_select');
      $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
      $destinationPath = public_path($dir);
      $imagepath = $image->move($destinationPath, $name);
      $update['staff_image'] = $dir.$name;
    }

    $response = Transport::where('id',$id)->update($update);
    if($response) {
      return redirect()->route('transport')->with('success', "Transport updated Successfully");
    } else {
      return redirect()->route('transport')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function registerTransport(Request $request) {
    $validator = Validator::make($request->all(), [
        'party_name' => 'required',
        'person_name' => 'required',
        'vehical_no' => 'required',
        'mobile' => 'required',
        'id_proff' => 'required',
        'state' => 'required|exists:states,state_id',
        'city' => 'required|exists:cities,city_id'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $fbinput = 'user_404.jpg';
    if ($request->hasFile('fbinput')) {
      $dir = 'image/transport/';
      $image = $request->file('fbinput');
      $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
      $destinationPath = public_path($dir);
      $imagepath = $image->move($destinationPath, $name);
      $fbinput = $dir.$name;
    }

    $staffid = '';
    if ($request->hasFile('id_select')) {
      $dir = 'image/transport/';
      $image = $request->file('id_select');
      $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
      $destinationPath = public_path($dir);
      $imagepath = $image->move($destinationPath, $name);
      $staffid = $dir.$name;
    }

    $user = Sentinel::getUser();
    $response = Transport::insert([
      'user_id' => $user->id,
      'business_name' => request('party_name'),
      'name' => request('person_name'),
      'vehical_no' => request('vehical_no'),
      'photo' => $fbinput,
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
      'opening_asof' => request('asof') != null ? date('Y-m-d',strtotime(request('asof'))) : null,
      'remarks' => request('remarks'),
    ]);

    if($response) {
      return Admin::checkRedirect($request,'transport',"Transport registred Successfully");
    } else {
      return redirect()->route('transport')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function transportView(Request $request,$id) {
    $user = Sentinel::getUser();
    if(Transport::HaveRightBank($user->id,$id)) {
      $data['info'] = Transport::find($id);

      //total
      $search['table'] = 'transport';
      $search['user_id'] = $user->id;
      $search['id'] = $id;
      $search['master_type'] = 'master7';
      $amount = DB::select(Admin::masterTransectionQuery($search));
      $data['total_amount'] = collect($amount)->sum('transection_amount');

      return view('admin.v1.transport.view',$data);
    } else {
      return Admin::unauth();
    }
  }

  public function transportTransection(Request $request,$id) {
    $user = Sentinel::getUser();
    $search['table'] = 'transport';
    $search['user_id'] = $user->id;
    $search['id'] = $id;
    $search['master_type'] = 'master7';

    $master = DB::select(Admin::masterTransectionQuery($search));
    return DataTables::of($master)
    ->addColumn('formated_date', function ($master) {
        $html = '';
        $html .= Admin::FormateDate($master->transection_date);
        return $html;
    })
    ->addColumn('formated_type', function ($master) {
        $html = '';
        $html .= config('transection.'.$master->transection_type)['type'];
        return $html;
    })
    ->addColumn('formated_amount', function ($master) {
        $html = '';
        $html .= Admin::FormateTransection($master->transection_amount);
        return $html;
    })
    ->addColumn('action', function ($master) use ($id) {
        $transection_type = config('transection.'.$master->transection_type);
        $html = '';
        if($transection_type['edit_at'] != "") {
        $html .= ' <a href="'.route("redirecting").'?redirectback=transport.view&id='.$id.'&redirect='.$transection_type['edit_at'].'&toid='.$master->transection_id.'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        }
        if($transection_type['deleted_at'] != "") {
        $html .= ' <a href="'.route("redirecting").'?redirectback=transport.view&id='.$id.'&redirect='.$transection_type['deleted_at'].'&toid='.$master->transection_id.'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        }
        return $html;
    })
    ->rawColumns(['formated_date','formated_type','formated_amount','action'])
    ->make(true);
  }

}
