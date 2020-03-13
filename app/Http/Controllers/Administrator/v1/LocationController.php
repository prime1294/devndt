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
use App\Model\State;
use App\Model\City;

class LocationController extends Controller
{
    public function __construct() {

    }

    public function stateList(Request $request)
    {
        return view('admin.v1.location.state.list');
    }

    public function cityList(Request $request)
    {
        $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
        return view('admin.v1.location.city.list',$data);
    }

    public function registerState(Request $request) {

        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            'state' => 'required',
            'gst_code' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $response = State::create(
            $request->all()
        );

        if($response) {
            return redirect()->route('states')->with('success', "State added successfully");
        } else {
            return redirect()->route('states')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function registerCities(Request $request) {

        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            'state_id' => 'required',
            'city' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $response = City::create(
            $request->all()
        );

        if($response) {
            return redirect()->route('cities')->with('success', "City added successfully");
        } else {
            return redirect()->route('cities')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function getStateList() {
        $states = State::select('*')->where('country_id','101')->orderBy('state_id','DESC');
        return DataTables::of($states)
            ->addColumn('action', function ($states) {
                $html = '';
                $html .= ' <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#EditModal" onClick="getformelement('.$states->state_id.')" style="margin-bottom: 0px !important;"><i class="fa fa-pencil-square-o"></i> Edit</button>';
                $html .= ' <a href="'.route('state.remove',$states->state_id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
                return $html;
            })->make(true);
    }

    public function getCityList() {
        $states = City::select('cities.*','states.state as state_name')
            ->leftjoin('states','cities.state_id','=','states.state_id')
            ->where('cities.country_id','101')->orderBy('cities.city_id','DESC');
        return DataTables::of($states)
            ->addColumn('action', function ($states) {
                $html = '';
                $html .= ' <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#EditModal" onClick="getformelement('.$states->city_id.')" style="margin-bottom: 0px !important;"><i class="fa fa-pencil-square-o"></i> Edit</button>';
                $html .= ' <a href="'.route('city.remove',$states->city_id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
                return $html;
            })->make(true);
    }

    public function infoState(Request $request) {
        $manufacturer = State::where('state_id',request('id'))->first();
        return response()->json($manufacturer);
    }

    public function infoCities(Request $request) {
        $manufacturer = City::where('city_id',request('id'))->first();
        return response()->json($manufacturer);
    }

    public function updateState(Request $request) {
        $validator = Validator::make($request->all(), [
            'edit_unique_id' => 'required',
            'state' => 'required',
            'gst_code' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $response = State::where('state_id',request('edit_unique_id'))->update(
            $request->except('_token','edit_unique_id')
        );

        if($response) {
            return redirect()->route('states')->with('success', "State updated successfully");
        } else {
            return redirect()->route('states')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function updateCity(Request $request) {
        $validator = Validator::make($request->all(), [
            'edit_unique_id' => 'required',
            'state_id' => 'required',
            'city' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $response = City::where('city_id',request('edit_unique_id'))->update(
            $request->except('_token','edit_unique_id')
        );

        if($response) {
            return redirect()->route('cities')->with('success', "City updated successfully");
        } else {
            return redirect()->route('cities')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function removeState(Request $request,$id)
    {
        $user =  State::where('state_id',$id)->delete();
        if($user) {
            return redirect()->route('states')->with('success', "State deleted successfully");
        } else {
            return redirect()->route('states')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function removeCity(Request $request,$id)
    {
        $user =  City::where('city_id',$id)->delete();
        if($user) {
            return redirect()->route('cities')->with('success', "City deleted successfully");
        } else {
            return redirect()->route('cities')->with('error', "Ooops..! Something went wrong");
        }
    }
}
