<?php

namespace App\Http\Controllers\Administrator\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Support\Facades\Validator;
use App\Model\Designation;

class DesignationController extends Controller
{
    public function __construct() {

    }

    public function designationList(Request $request) {
        return view('admin.v1.designation.list');
    }

    public function getDesignationList(Request $request) {
        $list = Designation::query();
        $list->where('status','1');
        $list->orderBy('id','DESC');
        $result = $list->get();
        return DataTables::of($result)
        ->addColumn('action', function ($result) {
            $html = '';
            $html .= ' <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#EditModal" onClick="getformelement('.$result->id.')" style="margin-bottom: 0px !important;"><i class="fa fa-pencil-square-o"></i> Edit</button>';
            return $html;
        })->make(true);
    }

    public function infoDesignation(Request $request) {
        $list =  Designation::find(request('id'));
        return response()->json($list);
    }

    public function updateDesignation(Request $request) {
        $validator = Validator::make($request->all(), [
            'edit_unique_id' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $response = Designation::where('id',request('edit_unique_id'))->update(
            $request->except('_token','edit_unique_id')
        );

        if($response) {
            return redirect()->route('designation')->with('success', "Designation updated successfully");
        } else {
            return redirect()->route('designation')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function registerDesignation(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:designation,name',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $response = Designation::insert(
            $request->except('_token','edit_unique_id')
        );

        if($response) {
            return redirect()->route('designation')->with('success', "Designation added successfully");
        } else {
            return redirect()->route('designation')->with('error', "Ooops..! Something went wrong");
        }
    }
}
