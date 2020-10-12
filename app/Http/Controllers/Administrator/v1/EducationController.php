<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\Qualification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Support\Facades\Validator;

class EducationController extends Controller
{
    public function __construct() {

    }

    public function educationList(Request $request) {
        return view('admin.v1.education.list');
    }

    public function getEducationList(Request $request) {
        $list = Qualification::query();
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

    public function infoEducation(Request $request) {
        $list =  Qualification::find(request('id'));
        return response()->json($list);
    }

    public function updateEducation(Request $request) {
        $validator = Validator::make($request->all(), [
            'edit_unique_id' => 'required',
            'name' => 'required',
            'full_name' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $response = Qualification::where('id',request('edit_unique_id'))->update(
            $request->except('_token','edit_unique_id')
        );

        if($response) {
            return redirect()->route('education')->with('success', "Education updated successfully");
        } else {
            return redirect()->route('education')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function registerEducation(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'full_name' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $response = Qualification::insert(
            $request->except('_token','edit_unique_id')
        );

        if($response) {
            return redirect()->route('education')->with('success', "Education added successfully");
        } else {
            return redirect()->route('education')->with('error', "Ooops..! Something went wrong");
        }
    }
}
