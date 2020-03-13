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
use App\Model\Agent;
use App\Model\Banks;

class AgentController extends Controller
{
  public function __construct() {

  }

  public function agent(Request $request)
  {
      return view('admin.v1.agent.list');
  }

  public function agentNew(Request $request)
  {
      $data['type'] = 'add';
      $data['pinfo'] = [];
      $data['bank_list'] = Banks::orderBy('name','ASC')->get();
      $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
      return view('admin.v1.agent.new',$data);
  }

  private function AgentList($column = 'agent.id',$order = 'DESC') {
    $user = Sentinel::getUser();
    return Agent::select('agent.*','states.state as mystate','cities.city as mycity')
    ->leftjoin('states','agent.state','=','states.state_id')
    ->leftjoin('cities','agent.city','=','cities.city_id')
    ->where('agent.user_id',$user->id)
    ->where('agent.status',1)
    ->groupBy('agent.id')
    ->orderBy($column,$order);
  }

  public function getAgentJson(Request $request)  {
    $agent = self::AgentList()->where('agent.status',1)->get();
    return response()->json($agent);
  }

  public function getAgent(Request $request)  {
    $user = Sentinel::getUser();

    //order logic
    if(request('columns')[0]['orderable'] == "true" && isset(request('order')[0]['dir'])) {
      $order = request('order')[0]['dir'];
      $column = 'agent.name';
      $agent = self::AgentList($column,$order);
    } else {
      $agent = self::AgentList();
    }

    return DataTables::of($agent)
    ->addColumn('party', function ($agent) {
        $html = '';
        $html .= '<img src="'.asset($agent->photo).'" alt="'.$agent->name.'" class="img-circle img-responsive" style="display:inline-block;vertical-align:middle;" width="40"> <span style="display: inline-block;margin-left:5px;">'.$agent->name.'</span>';
        return $html;
    })
    ->addColumn('transection_amount', function ($party) use ($user) {
        $html = '';
        $search['table'] = 'agent';
        $search['user_id'] = $user->id;
        $search['id'] = $party->id;
        $search['master_type'] = 'master2';

        $amount = DB::select(Admin::masterTransectionQuery($search));
        $html .= Admin::FormateTransection(collect($amount)->sum('transection_amount'));
        return $html;
    })
    ->addColumn('gstininfo', function ($agent) {
        $html = '';
        if($agent->mycity || $agent->mystate) {
            $html .= $agent->mycity.', '.$agent->mystate;
        }
        return $html;
    })
    ->addColumn('contactinfo', function ($agent) {
        $html = '';
        $html .= $agent->mobile.'<br><span class="text-muted">'.$agent->alt_mobile.'</span>';
        return $html;
    })
    ->addColumn('action', function ($agent) {
        //$activation_status = $agent->status == 1 ? 'checked' : "";
        $html = '';
        //$html .= '<input type="checkbox" class="status_checkbox" data-id=" '.$agent->id.'" '.$activation_status.' data-size="mini" data-toggle="toggle" data-on="Active" data-off="Deactive" data-onstyle="success" data-offstyle="danger">';
        $html .= ' <a href="'.route('agent.edit',$agent->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        $html .= ' <a href="'.route('agent.remove',$agent->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        return $html;
    })
    ->filterColumn('party', function($query, $keyword) {
       $query->whereRaw("agent.name like ?", ["%{$keyword}%"]);
    })
    ->filterColumn('gstininfo', function($query, $keyword) {
       $query->whereRaw("CONCAT(states.state,', ',cities.city) like ?", ["%{$keyword}%"]);
    })
    ->filterColumn('contactinfo', function($query, $keyword) {
       $query->whereRaw("CONCAT(agent.mobile,' ',agent.alt_mobile) like ?", ["%{$keyword}%"]);
    })
    ->rawColumns(['party','transection_amount','gstininfo','contactinfo', 'action'])
    ->make(true);
  }

  public function ActivationAgent(Request $request) {
    $agent = Agent::find(request('id'));
    $agent->status = request('status');
    $agent->save();
    return $agent;
  }

  public function removeAgent(Request $request,$id)
  {
    $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:agent,id']);
    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

//    $user =  Agent::find($id)->delete();
      $user =  Agent::find($id);
      $user->status = 0;
      $user->save();
    if($user) {
      return redirect()->route('agent')->with('success', "Agent Removed Successfully");
    } else {
      return redirect()->route('agent')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function editAgent(Request $request,$id) {
    $data['type'] = 'edit';
    $data['pinfo'] = Agent::find($id);
    $data['bank_list'] = Banks::orderBy('name','ASC')->get();
    $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
    return view('admin.v1.agent.new',$data);
  }

  public function updateAgent(Request $request,$id) {
    $validator = Validator::make($request->all(), [
        'person_name' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $update = [
      'business_name' => request('party_name'),
      'name' => request('person_name'),
      'email' => request('email'),
      'mobile' => request('mobile'),
      'alt_mobile' => request('alt_no'),
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

    $request = Agent::where('id',$id)->update($update);
    if($request) {
      return redirect()->route('agent')->with('success', "Agent updated Successfully");
    } else {
      return redirect()->route('agent')->with('error', "Ooops..! Something went wrong");
    }
  }


  public function registerAgent(Request $request) {
    $validator = Validator::make($request->all(), [
        'person_name' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $user = Sentinel::getUser();
    $response = Agent::insert([
      'user_id' => $user->id,
      'business_name' => request('party_name'),
      'name' => request('person_name'),
      'photo' => request('fbinputtxt') ? request('fbinputtxt') : 'user_404.jpg',
      'email' => request('email'),
      'mobile' => request('mobile'),
      'alt_mobile' => request('alt_no'),
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
      return Admin::checkRedirect($request,'agent',"Agent registred Successfully");
    } else {
      return redirect()->route('agent')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function agentView(Request $request,$id) {
    $user = Sentinel::getUser();
    if(Agent::HaveRightBank($user->id,$id)) {
      $data['info'] = Agent::find($id);

      //total
      $search['table'] = 'agent';
      $search['user_id'] = $user->id;
      $search['id'] = $id;
      $search['master_type'] = 'master2';
      $amount = DB::select(Admin::masterTransectionQuery($search));
      $data['total_amount'] = collect($amount)->sum('transection_amount');

      return view('admin.v1.agent.view',$data);
    } else {
      return Admin::unauth();
    }
  }

  public function agentTransection(Request $request,$id) {
    $user = Sentinel::getUser();
    $search['table'] = 'agent';
    $search['user_id'] = $user->id;
    $search['id'] = $id;
    $search['master_type'] = 'master2';

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
        $html .= ' <a href="'.route("redirecting").'?redirectback=agent.view&id='.$id.'&redirect='.$transection_type['edit_at'].'&toid='.$master->transection_id.'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        }
        if($transection_type['deleted_at'] != "") {
        $html .= ' <a href="'.route("redirecting").'?redirectback=agent.view&id='.$id.'&redirect='.$transection_type['deleted_at'].'&toid='.$master->transection_id.'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        }
        return $html;
    })
        ->rawColumns(['formated_date','formated_number','formated_type','formated_amount','transection_recive','transection_paid','action'])
    ->make(true);
  }

}
