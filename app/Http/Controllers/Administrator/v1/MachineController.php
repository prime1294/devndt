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
use App\Model\Machine;

class MachineController extends Controller
{
  public function __construct() {

  }

  public function machine(Request $request)
  {
      return view('admin.v1.machine.list');
  }

  public function machineNew(Request $request)
  {
      $data['type'] = 'add';
      $data['pinfo'] = [];
      $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
      return view('admin.v1.machine.new',$data);
  }

  public function getMachine(Request $request)  {
    $user = Sentinel::getUser();
    $machine = Machine::select('machine.*')
    ->where('machine.user_id',$user->id)
    ->where('machine.status',1)
    ->groupBy('machine.id')
    ->orderBy('machine.id','DESC');
    return DataTables::of($machine)
    ->addColumn('party', function ($machine) {
        $html = '';
        $html .= '<img src="'.asset($machine->photo).'" alt="'.$machine->machine_type.'" class="img-rounded img-responsive" style="display:inline-block;vertical-align:top;" width="50"> <span style="display: inline-block;margin-left:5px;">'.$machine->company_name.'</span>';
        return $html;
    })
    // ->addColumn('gstininfo', function ($machine) {
    //     $html = '';
    //     $html .= $machine->mystate.', '.$machine->mycity;
    //     return $html;
    // })
    ->addColumn('action', function ($machine) {
        //$activation_status = $machine->status == 1 ? 'checked' : "";
        $html = '';
        //$html .= '<input type="checkbox" class="status_checkbox" data-id=" '.$machine->id.'" '.$activation_status.' data-size="mini" data-toggle="toggle" data-on="Active" data-off="Deactive" data-onstyle="success" data-offstyle="danger">';
        $html .= ' <a href="'.route('machine.edit',$machine->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        $html .= ' <a href="'.route('machine.remove',$machine->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        return $html;
    })
    ->filterColumn('party', function($query, $keyword) {
       $query->whereRaw("CONCAT(machine.machine_no,' ',machine.machine_type,' ',machine.company_name) like ?", ["%{$keyword}%"]);
    })
    ->rawColumns(['party','action'])
    ->make(true);
  }

  public function ActivationMachine(Request $request) {
    $agent = Machine::find(request('id'));
    $agent->status = request('status');
    $agent->save();
    return $agent;
  }

  public function removeMachine(Request $request,$id)
  {
    $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:machine,id']);
    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

//    $user =  Machine::find($id)->delete();
      $user =  Machine::find($id);
      $user->status = 0;
      $user->save();
    if($user) {
      return redirect()->route('machine')->with('success', "Machine Removed Successfully");
    } else {
      return redirect()->route('machine')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function editMachine(Request $request,$id) {
    $data['type'] = 'edit';
    $data['pinfo'] = Machine::find($id);
    $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
    return view('admin.v1.machine.new',$data);
  }

  public function updateMachine(Request $request,$id) {
    $validator = Validator::make($request->all(), [
      'machine_no' => 'required',
      'machine_type' => 'required',
      'company_name' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $update = [
      'machine_no' => request('machine_no'),
      'machine_type' => implode(', ',request('machine_type')),
      'company_name' => request('company_name'),
      'machine_area' => request('machine_area'),
      'machine_head' => request('machine_head'),
      'machine_frame' => request('machine_frame'),
      'remarks' => request('remarks'),
    ];

      if (request('fbinputtxt')) {
          $update['photo'] = request('fbinputtxt');
      }

    $request = Machine::where('id',$id)->update($update);
    if($request) {
      return redirect()->route('machine')->with('success', "Machine updated Successfully");
    } else {
      return redirect()->route('machine')->with('error', "Ooops..! Something went wrong");
    }
  }


  public function registerMachine(Request $request) {
    $validator = Validator::make($request->all(), [
        'machine_no' => 'required',
        'machine_type' => 'required',
        'company_name' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }



    $user = Sentinel::getUser();
    $reponse = Machine::insert([
      'user_id' => $user->id,
      'photo' => request('fbinputtxt') ? request('fbinputtxt') : 'user_404.jpg',
      'machine_no' => request('machine_no'),
      'machine_type' => implode(', ',request('machine_type')),
      'company_name' => request('company_name'),
      'machine_area' => request('machine_area'),
      'machine_head' => request('machine_head'),
      'machine_frame' => request('machine_frame'),
      'remarks' => request('remarks'),
    ]);

    if($reponse) {
        return Admin::checkRedirect($request,'machine',"Machine registred Successfully");
    } else {
      return redirect()->route('machine')->with('error', "Ooops..! Something went wrong");
    }
  }

}
