<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\Company;
use App\Model\Designation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Admin;
use Sentinel;
use App\User;

class CompanyController extends Controller
{
    public function __construct() {

    }

    private function getCompanyUniqueNumber() {
        $user = Sentinel::getUser();
        $max_id = Company::withTrashed()->max('id');
        return $max_id + 1;
    }

    public function company(Request $request) {
        $user = Sentinel::getUser();
        $data['add_company']['max_id'] = self::getCompanyUniqueNumber();
        $data['add_company']['designation'] = self::getDesignation();
        return view('admin.v1.company.list',$data);
    }

    public function companyInfo(Request $request) {
        $user = Sentinel::getUser();
        $info = Company::find(request('id'));
        return response()->json($info);
    }

    public function getDesignation() {
        $list = Designation::select('name')->where('status',1)->get();
        return $list;
    }

    public function companySelect(Request $request) {
        $user = Sentinel::getUser();
        if(request('searchTerm')){
            //fetch with keyword
            $search = request('searchTerm');
            $result = Company::select('id','company_name')->where('company_name','LIKE','%'.$search.'%')->orderBy('id','DESC')->get()->toArray();
        }else{
            //fetch without keyword
            $result = Company::select('id','company_name')->orderBy('id','DESC')->get()->toArray();
        }

        $data = array();
        $data[] = ['id'=>0,"text"=>'Add New'];
        foreach($result as $row) {
            $data[] = array("id"=>$row['id'], "text"=>$row['company_name']);
        }

        return response()->json($data);
    }

    public function companyListAjax(Request $request) {
        $user = Sentinel::getUser();

        $enrollment = Company::orderBy('id','DESC')->get()->toArray();
        return DataTables::of($enrollment)
            ->addColumn('check', function ($enrollment) {
                $html = '';
                $html .= '<input type="checkbox" id="chk_'.$enrollment['id'].'" class="chkbox" name="selected_users[]" value="'.$enrollment['id'].'">';
                return $html;
            })
            ->addColumn('check_id', function ($enrollment) {
                $html = '';
                $html .= 'chk_'.$enrollment['id'];
                return $html;
            })
            ->addColumn('company_info', function ($enrollment) {
                $html = '';
                $html .= $enrollment['id'].'<br><span class="text-muted">'.$enrollment['company_name'].'</span>'.'<br><span class="text-muted">'.$enrollment['company_type'].'</span>';
                return $html;
            })
            ->addColumn('company_contact', function ($enrollment) {
                $html = $enrollment['mobile'].'<br><span class="text-muted">'.$enrollment['email'].'</span>';
                return $html;
            })
            ->addColumn('company_address', function ($enrollment) {
                $html = $enrollment['address'].',<br>'.$enrollment['city'].','.$enrollment['district'].','.$enrollment['state'].','.$enrollment['pincode'].'</span>';
                return $html;
            })
            ->addColumn('contact_person', function ($enrollment) {
                $html = $enrollment['person_greet'].' '.$enrollment['person_fname'].' '.$enrollment['person_mname'].' '.$enrollment['person_lname'].'<br><span class="text-muted">'.$enrollment['person_contact'].'<br><span class="text-muted">'.$enrollment['person_email'].'</span>';
                return $html;
            })
            ->addColumn('action', function ($enrollment) {
                $html = '';
                $html .= ' <a class="btn btn-primary btn-xs" onclick="editcompany('.$enrollment['id'].')"><i class="fa fa-edit"></i> Edit</a>';
                $html .= ' <a href="'.route('sticker.company',$enrollment['id']).'" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-file-pdf-o"></i> Sticker</a>';
                return $html;
            })
            ->rawColumns(['check','company_info','company_contact','company_address','contact_person', 'action'])
            ->make(true);
    }

    public function editCompany(Request $request) {
        $user = Sentinel::getUser();
        $validator = Validator::make($request->all(), [
            'ac_no' => 'required',
            'ac_comp_name' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $data['status'] = "false";
            $data['message'] = $errors->first();
            return response()->json($data);
        }

        $ins['company_name'] = request('ac_comp_name');
        $ins['company_type'] = request('ac_comp_type');
        $ins['mobile'] = request('ac_comp_contact');
        $ins['email'] = request('ac_comp_email');
        $ins['website'] = request('ac_comp_website');
        $ins['address'] = request('ac_comp_address');
        $ins['city'] = request('ac_comp_city');
        $ins['district'] = request('ac_comp_district');
        $ins['state'] = request('ac_comp_state');
        $ins['pincode'] = request('ac_comp_pincode');
        $ins['person_greet'] = request('ac_person_greet');
        $ins['person_fname'] = request('ac_person_fname');
        $ins['person_mname'] = request('ac_person_mname');
        $ins['person_lname'] = request('ac_person_lname');
        $ins['person_designation'] = request('ac_person_designation');
        $ins['person_contact'] = request('ac_person_contact');
        $ins['person_email'] = request('ac_person_email');
        $ins['week_off'] = request('ac_comp_weekoff');

        $response = Company::where('id',request('ac_no'))->update($ins);

        if($response) {
            $data['status'] = "true";
            $data['message'] = "Company Updated successfully";
        } else {
            $data['status'] = "false";
            $data['message'] = "Ooops..! Something went wrong";
        }

        return response()->json($data);

    }

    public function registerCompany(Request $request) {
        $user = Sentinel::getUser();
        $validator = Validator::make($request->all(), [
            'ac_comp_name' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $data['status'] = "false";
            $data['message'] = $errors->first();
            return response()->json($data);
        }

        $ins['company_name'] = request('ac_comp_name');
        $ins['company_type'] = request('ac_comp_type');
        $ins['mobile'] = request('ac_comp_contact');
        $ins['email'] = request('ac_comp_email');
        $ins['website'] = request('ac_comp_website');
        $ins['address'] = request('ac_comp_address');
        $ins['city'] = request('ac_comp_city');
        $ins['district'] = request('ac_comp_district');
        $ins['state'] = request('ac_comp_state');
        $ins['pincode'] = request('ac_comp_pincode');
        $ins['person_greet'] = request('ac_person_greet');
        $ins['person_fname'] = request('ac_person_fname');
        $ins['person_mname'] = request('ac_person_mname');
        $ins['person_lname'] = request('ac_person_lname');
        $ins['person_designation'] = request('ac_person_designation');
        $ins['person_contact'] = request('ac_person_contact');
        $ins['person_email'] = request('ac_person_email');
        $ins['week_off'] = request('ac_comp_weekoff');

        $response = Company::insert($ins);

        if($response) {
            $data['status'] = "true";
            $data['message'] = "Company Registered successfully";
        } else {
            $data['status'] = "false";
            $data['message'] = "Ooops..! Something went wrong";
        }

        return response()->json($data);
    }

    public function companySticker(Request $request,$id) {
        $user = Sentinel::getUser();
        $data['info'] = Company::find($id);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin.v1.company.sticker', $data);
        return $pdf->stream('company-sticker-'.$id.'.pdf');
    }
}
