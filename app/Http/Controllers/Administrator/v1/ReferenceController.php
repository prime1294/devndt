<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\Company;
use App\Model\Designation;
use App\Model\Enrollment;
use App\Model\Ref;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use DataTables;
use Admin;
use Illuminate\Support\Facades\Validator;
use Sentinel;
use App\User;

class ReferenceController extends Controller
{
    public function __construct() {

    }

    public function getDesignation() {
        $list = Designation::select('name')->where('status',1)->get();
        return $list;
    }

    public function referenceList(Request $request) {
        $data['company_list'] = Company::select('id','company_name')->where('status',1)->orderBy('id','DESC')->get();
        $data['enrollment_list'] = Enrollment::select('id','front_fname','front_lname')->orderBy('id','DESC')->get();
        $data['comp']['designation'] = self::getDesignation();
        return view('admin.v1.reference.list',$data);
    }

    public function getReferenceList(Request $request) {
        $list = Ref::query();
        $list->where('status','1');
        $list->orderBy('id','DESC');
        $result = $list->get();
        return DataTables::of($result)
        ->addColumn('user_info', function ($result) {
            $html = '';
            $html .= $result->id;
            $html .= '<br><span class="text-muted">'.$result->fname.' '.$result->mname.' '.$result->lname.'</span>';
            return $html;
        })
        ->addColumn('contact_info', function ($result) {
            $html = '';
            $html .= $result->contact;
            $html .= '<br><span class="text-muted">'.$result->email.'</span>';
            return $html;
        })
        ->addColumn('company_info', function ($result) {
            $html = '';
            $html .= $result->company_name;
            $html .= '<br><span class="text-muted">'.$result->designation.'</span>';
            return $html;
        })
        ->addColumn('address_info', function ($result) {
            $html = '';
            $html .= $result->address;
            return $html;
        })
        ->addColumn('action', function ($result) {
            $html = '';
            $html .= ' <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#EditModal" onClick="getformelement('.$result->id.')" style="margin-bottom: 0px !important;"><i class="fa fa-pencil-square-o"></i> Edit</button>';
            return $html;
        })
        ->rawColumns(['user_info','contact_info','company_info','address_info','action'])
        ->make(true);
    }

    public function registerRef(Request $request) {
        $user = Sentinel::getUser();
        $validator = Validator::make($request->all(), [
            'add_ref_fname' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $data['status'] = "false";
            $data['message'] = $errors->first();
            return response()->json($data);
        }

        $ins['ndt_id'] = request('add_ref_dev_ndt_id');
        $ins['fname'] = request('add_ref_fname');
        $ins['mname'] = request('add_ref_mname');
        $ins['lname'] = request('add_ref_lname');
        $ins['contact'] = request('add_ref_company_contact');
        $ins['email'] = request('add_ref_company_email');
        $ins['designation'] = request('add_ref_designation');
        $ins['company_id'] = request('add_ref_company_no');
        $ins['company_name'] = request('add_ref_company_name');
        $ins['address'] = request('add_ref_company_address');
        $ins['remarks'] = request('add_ref_remarks');

        $response = Ref::insert($ins);

        if($response) {
            $data['status'] = "true";
            $data['message'] = "Reference Registered successfully";
        } else {
            $data['status'] = "false";
            $data['message'] = "Ooops..! Something went wrong";
        }

        return response()->json($data);
    }

    public function updateRef(Request $request) {
        $user = Sentinel::getUser();
        $validator = Validator::make($request->all(), [
            'edit_ref_id' => 'required',
            'edit_ref_fname' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $data['status'] = "false";
            $data['message'] = $errors->first();
            return response()->json($data);
        }

        $ins['ndt_id'] = request('edit_ref_dev_ndt_id');
        $ins['fname'] = request('edit_ref_fname');
        $ins['mname'] = request('edit_ref_mname');
        $ins['lname'] = request('edit_ref_lname');
        $ins['contact'] = request('edit_ref_company_contact');
        $ins['email'] = request('edit_ref_company_email');
        $ins['designation'] = request('edit_ref_designation');
        $ins['company_id'] = request('edit_ref_company_no');
        $ins['company_name'] = request('edit_ref_company_name');
        $ins['address'] = request('edit_ref_company_address');
        $ins['remarks'] = request('edit_ref_remarks');

        $response = Ref::where('id',request('edit_ref_id'))->update($ins);

        if($response) {
            $data['status'] = "true";
            $data['message'] = "Reference update successfully";
        } else {
            $data['status'] = "false";
            $data['message'] = "Ooops..! Something went wrong";
        }

        return response()->json($data);
    }

    public function refSelect(Request $request) {
        $user = Sentinel::getUser();
        if(request('searchTerm')){
            //fetch with keyword
            $search = request('searchTerm');
            $result = Ref::select('id','fname','mname','lname','company_name')
                ->where(function($query) use ($search){
                    $query->where('fname', 'LIKE', '%'.$search.'%');
                    $query->orWhere('mname', 'LIKE', '%'.$search.'%');
                    $query->orWhere('lname', 'LIKE', '%'.$search.'%');
                    $query->orWhere('company_name', 'LIKE', '%'.$search.'%');
                })
                ->orderBy('id','DESC')->get()->toArray();
        }else{
            //fetch without keyword
            $result = Ref::select('id','fname','mname','lname','company_name')->orderBy('id','DESC')->get()->toArray();
        }

        $data = array();
        $data[] = ['id'=>0,"text"=>'Add New'];
        foreach($result as $row) {
            $data[] = array("id"=>$row['id'], "text"=>$row['fname'].' '.$row['mname'].' '.$row['lname'].' - '.$row['company_name']);
        }

        return response()->json($data);
    }

    public function refInfo(Request $request) {
        $user = Sentinel::getUser();
        $info = Ref::find(request('id'));
        return response()->json($info);
    }


}
