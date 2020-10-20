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
            ->addColumn('cource_info', function ($result) {
                $html = $result->short_name;
                $html .= '<br><span class="text-muted">'.$result->name.'</span>';
                $service_category = $result->is_other ? 'Special' : 'General';
                $html .= '<br><span class="text-muted">'.$service_category.'</span>';
                return $html;
            })
            ->addColumn('cource_specification', function ($result) {
                $avalible_services = explode(',',$result->avalible_services);
                $html = '';
                $html .= '<div class="row">';
                $html .= '<div class="col-md-4">';
                $html .= '</div>';
                $html .= '<div class="col-md-4 text-center">';
                if(in_array('I',$avalible_services)) {
                    $html .= 'Level I';
                }
                $html .= '</div>';
                $html .= '<div class="col-md-4 text-center">';
                if(in_array('II',$avalible_services)) {
                    $html .= 'Level II';
                }
                $html .= '</div>';
                $html .= '</div>';

                $html .= '<div class="row">';
                $html .= '<div class="col-md-4">';
                $html .= 'Training Hours';
                $html .= '</div>';
                $html .= '<div class="col-md-4 text-center">';
                if(in_array('I',$avalible_services)) {
                    $html .= $result->level1_hours;
                }
                $html .= '</div>';
                $html .= '<div class="col-md-4 text-center">';
                if(in_array('II',$avalible_services)) {
                    $html .= $result->level2_hours;
                }
                $html .= '</div>';
                $html .= '</div>';


                $html .= '<div class="row">';
                $html .= '<div class="col-md-4">';
                $html .= 'Min Experience';
                $html .= '</div>';
                $html .= '<div class="col-md-4 text-center">';
                if(in_array('I',$avalible_services)) {
                    $html .= $result->min_exp_hours_1;
                }
                $html .= '</div>';
                $html .= '<div class="col-md-4 text-center">';
                if(in_array('II',$avalible_services)) {
                    $html .= $result->min_exp_hours_2;
                }
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->addColumn('action', function ($result) {
                $html = '';
                $html .= ' <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#EditModal" onClick="getformelement('.$result->id.')" style="margin-bottom: 0px !important;"><i class="fa fa-pencil-square-o"></i> Edit</button>';
                return $html;
            })
            ->rawColumns(['cource_info','cource_specification','action'])
            ->make(true);
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
            'is_other' => 'required',
            'avalible_services' => 'required',
            'level1_hours' => 'required',
            'level2_hours' => 'required',
            'min_exp_hours_1' => 'required',
            'min_exp_hours_2' => 'required',
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
