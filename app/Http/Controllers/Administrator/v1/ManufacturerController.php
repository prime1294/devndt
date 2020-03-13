<?php

namespace App\Http\Controllers\Administrator\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Admin;
use Sentinel;
use App\User;
use App\Model\Manufacturer;

class ManufacturerController extends Controller
{
    public function __construct() {

    }


    public function mtypes(Request $request)
    {
        return view('admin.v1.manufacturer.mtypes');
    }

    public function getMtype(Request $request)  {
      $mtype = Manufacturer::select('*');
      return DataTables::of($mtype)
      ->addColumn('action', function ($mtype) {
          $activation_status = $mtype->status == 1 ? 'checked' : "";
          $html = '';
          // $html .= '<input id="toggle-demo" type="checkbox" checked data-toggle="toggle" data-on="Ready" data-off="Not Ready" data-onstyle="success" data-offstyle="danger">';
          $html .= ' <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#EditModal" onClick="getformelement('.$mtype->id.')" style="margin-bottom: 0px !important;"><i class="fa fa-pencil-square-o"></i> Edit</button>';
          $html .= ' <a href="'.route('manufacturer.types.remove',$mtype->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
          return $html;
      })->make(true);
    }

    public function ActivationMtype(Request $request) {
      $request = Manufacturer::where('id',request('id'))->update(
        ['status' => request('status')]
      );

      return $request;
    }

    public function updateMtype(Request $request) {
      $validator = Validator::make($request->all(), [
          'edit_type_name' => 'required|unique:manufacturer_type,name,'.request('edit_unique_id'),
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('error', $errors->first());
      }

      $request = Manufacturer::where('id',request('edit_unique_id'))->update(
        ['name' => request('edit_type_name')]
      );

      if($request) {
        return redirect()->route('manufacturer.types')->with('success', "Manufacturer type updated");
      } else {
        return redirect()->route('manufacturer.types')->with('error', "Ooops..! Something went wrong");
      }
    }

    public function infoMtype(Request $request) {
      $manufacturer = Manufacturer::find(request('id'));
      return response()->json($manufacturer);
    }

    public function removeMtype(Request $request,$id)
    {
      $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:manufacturer_type,id']);
      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('error', $errors->first());
      }

      $user =  Manufacturer::find($id)->delete();
      if($user) {
        return redirect()->route('manufacturer.types')->with('success', "Manufacturer type deleted");
      } else {
        return redirect()->route('manufacturer.types')->with('error', "Ooops..! Something went wrong");
      }
    }

    public function registerTypes(Request $request) {
      $validator = Validator::make($request->all(), [
          'type_name' => 'required|unique:manufacturer_type,name',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('error', $errors->first());
      }

      $request = Manufacturer::insert(
        ['name' => request('type_name')]
      );

      if($request) {
        return redirect()->route('manufacturer.types')->with('success', "Manufacturer type registred");
      } else {
        return redirect()->route('manufacturer.types')->with('error', "Manufacturer type already exist");
      }
    }
}
