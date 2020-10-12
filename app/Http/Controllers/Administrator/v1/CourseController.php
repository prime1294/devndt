<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\Cource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function __construct() {

    }

    public function courceList(Request $request)
    {
        return view('admin.v1.cource.list');
    }

    public function getCourseList(Request $request) {
        $list = Cource::query();
        $list->where('status','1');
        $list->orderBy('id','ASC');
        $result = $list->get();
        return DataTables::of($result)
            ->addColumn('action', function ($result) {
                $html = '';
                $html .= ' <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#EditModal" onClick="getformelement('.$result->id.')" style="margin-bottom: 0px !important;"><i class="fa fa-pencil-square-o"></i> Edit</button>';
                return $html;
            })->make(true);
    }

    public function infoCourse(Request $request) {
        $list =  Cource::find(request('id'));
        return response()->json($list);
    }

    public function updateCourse(Request $request) {
        $validator = Validator::make($request->all(), [
            'edit_unique_id' => 'required',
            'name' => 'required',
            'short_name' => 'required',
            'trainning_hours' => 'required',
            'fees' => 'required',
            'renew_fees' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $response = Cource::where('id',request('edit_unique_id'))->update(
            $request->except('_token','edit_unique_id')
        );

        if($response) {
            return redirect()->route('course')->with('success', "Course updated successfully");
        } else {
            return redirect()->route('course')->with('error', "Ooops..! Something went wrong");
        }
    }
}
