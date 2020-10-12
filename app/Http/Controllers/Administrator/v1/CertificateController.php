<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\Company;
use App\Model\Designation;
use App\Model\Enrollment;
use App\Model\Vision;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Admin;
use Sentinel;
use App\User;

class CertificateController extends Controller
{
    public function __construct() {

    }

    public function vision(Request $request) {
        $user = Sentinel::getUser();
        $data['company_list'] = Company::orderBy('company_name','ASC')->get();
        return view('admin.v1.certificate.list',$data);
    }

    private function getCompanyUniqueNumber() {
        $user = Sentinel::getUser();
        $max_id = Company::withTrashed()->max('id');
        return $max_id + 1;
    }

    public function getDesignation() {
        $list = Designation::select('name')->where('status',1)->get();
        return $list;
    }

    public function newVision(Request $request) {
        $user = Sentinel::getUser();
        $data['enrollment_list'] = Enrollment::select('id','front_fname','front_lname')->orderBy('id','DESC')->get();
        $data['company_list'] = Company::select('id')->where('status',1)->orderBy('id','DESC')->get();
        $data['comp']['max_id'] = self::getCompanyUniqueNumber();
        $data['comp']['designation'] = self::getDesignation();
        return view('admin.v1.certificate.new',$data);
    }

    public function editVision(Request $request,$id) {
        $user = Sentinel::getUser();
        $data['info'] = Vision::find($id);
        $data['enrollment_list'] = Enrollment::select('id','front_fname','front_lname')->orderBy('id','DESC')->get();
        $data['company_list'] = Company::select('id')->where('status',1)->orderBy('id','DESC')->get();
        $data['comp']['max_id'] = self::getCompanyUniqueNumber();
        $data['comp']['designation'] = self::getDesignation();
        return view('admin.v1.certificate.edit',$data);
    }

    public function visionPdf(Request $request,$id) {
        $user = Sentinel::getUser();
        $data['info'] = Vision::find($id);
        $data['company_name'] = self::getCompanyName($data['info']->company_id);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin.v1.certificate.pdf', $data);
        return $pdf->stream('vision'.rand(3000,4000).'.pdf');
    }

    public function visionUpdate(Request $request, $id) {
        $user = Sentinel::getUser();
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'certificate_date' => 'required',
            'expire_date' => 'required',
            'f_greet' => 'required',
            'f_fname' => 'required',
            'f_mname' => 'required',
            'f_lname' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $ins['ndt_id'] = request('dev_ndt_id');
        $ins['company_id'] = request('company_id');
        $ins['issue_date'] = request('certificate_date') ? date('Y-m-d',strtotime(request('certificate_date'))) : date('Y-m-d',strtotime('now'));
        $ins['expire_date'] = request('expire_date') ? date('Y-m-d',strtotime(request('expire_date'))) : date('Y-m-d',strtotime('+ 1 year'));
        $ins['greet'] = request('f_greet');
        $ins['fname'] = request('f_fname');
        $ins['mname'] = request('f_mname');
        $ins['lname'] = request('f_lname');
        $ins['spectacles'] = request('spectacles') ? 1 : 0;
        $ins['nv_type'] = request('j');
        $ins['nv_condition'] = request('nv_condition');
        $ins['cv_condition'] = request('cv');
        $ins['cv_color'] = request('method') ? implode(',',request('method')) : '';
        $ins['gray_shade'] = request('gray_shade');
        $ins['issue_year'] = date('Y',strtotime(request('certificate_date')));

        $response = Vision::where('id',$id)->update($ins);

        if($response) {
            return redirect()->route('vision')->with('success','Certificate updated successfully');
        } else {
            return back()->with('error','Oops..! Something went wrong');
        }

    }

    public function visionRegister(Request $request) {
        $user = Sentinel::getUser();
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'certificate_date' => 'required',
            'expire_date' => 'required',
            'f_greet' => 'required',
            'f_fname' => 'required',
            'f_mname' => 'required',
            'f_lname' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $ins['ndt_id'] = request('dev_ndt_id');
        $ins['company_id'] = request('company_id');
        $ins['issue_date'] = request('certificate_date') ? date('Y-m-d',strtotime(request('certificate_date'))) : date('Y-m-d',strtotime('now'));
        $ins['expire_date'] = request('expire_date') ? date('Y-m-d',strtotime(request('expire_date'))) : date('Y-m-d',strtotime('+ 1 year'));
        $ins['greet'] = request('f_greet');
        $ins['fname'] = request('f_fname');
        $ins['mname'] = request('f_mname');
        $ins['lname'] = request('f_lname');
        $ins['spectacles'] = request('spectacles') ? 1 : 0;
        $ins['nv_type'] = request('j');
        $ins['nv_condition'] = request('nv_condition');
        $ins['cv_condition'] = request('cv');
        $ins['cv_color'] = request('method') ? implode(',',request('method')) : '';
        $ins['gray_shade'] = request('gray_shade');
        $ins['issue_year'] = date('Y',strtotime(request('certificate_date')));


        $response = Vision::insert($ins);

        if($response) {
            return redirect()->route('vision')->with('success','Certificate created successfully');
        } else {
            return back()->with('error','Oops..! Something went wrong');
        }
    }

    public function renewVision(Request $request,$id) {
        $user = Sentinel::getUser();
        $info = Vision::where('id',$id)->first()->toArray();
        unset($info['id']);
        unset($info['created_at']);
        unset($info['updated_at']);
        unset($info['deleted_at']);

        $info['issue_date'] = date('Y-m-d',strtotime('+1 year',strtotime($info['issue_date'])));
        $info['expire_date'] = date('Y-m-d',strtotime('+1 year',strtotime($info['issue_date'])));
        $info['issue_year'] = date('Y',strtotime($info['issue_date']));

        $response = Vision::insert($info);

        if($response) {
            return redirect()->route('vision')->with('success','Certificate Renewed successfully');
        } else {
            return back()->with('error','Oops..! Something went wrong');
        }

    }

    public function getCompanyName($id) {
        $user = Sentinel::getUser();
        $info = Company::find($id);
        return $info->company_name;
    }

    public function visionListAjax(Request $request) {
        $user = Sentinel::getUser();
        $list = Vision::query();

        if(request('company_id')) {
            $list->where('company_id',request('company_id'));
        }

        if(request('issue_year')) {
            $list->where('issue_year',request('issue_year'));
        }

        if(request('startdate') && request('enddate')) {
            if(request('startdate') != "" && request('enddate') != "") {
                $list->whereBetween('expire_date',[request('startdate'), request('enddate')]);
            }
        }

        $list->where('status',1);
        $list->orderby('id','DESC');
        $list->get();
        //print_r($list); die();
        return DataTables::of($list)
            ->addColumn('check', function ($list) {
                $html = '';
                $html .= '<input type="checkbox" id="chk_'.$list->id.'" class="chkbox" name="selected_users[]" value="'.$list->id.'">';
                return $html;
            })
            ->addColumn('check_id', function ($list) {
                $html = '';
                $html .= 'chk_'.$list->id;
                return $html;
            })
            ->addColumn('user_info', function ($list) {
                $html = $list->id.'-'.$list->issue_year;
                $html .= '<br><span class="text-muted">'.ucwords($list->greet).' '.$list->fname.' '.$list->mname.' '.$list->lname.'</span>';
                return $html;
            })
            ->addColumn('company_info', function ($list) {
                $html = $list->company_id;
                $html .= '<br><span class="text-muted">'.self::getCompanyName($list->company_id).'</span>';
                return $html;
            })
            ->addColumn('issue_date', function ($list) {
                $html = date('d-m-Y',strtotime($list->issue_date));
                return $html;
            })
            ->addColumn('expire_date', function ($list) {
                $html = date('d-m-Y',strtotime($list->expire_date));
                return $html;
            })
            ->addColumn('action', function ($list) {
                $html = '';
                $html .= '<div class="dropdown">';
                $html .= '<button class="btn btn-primary btn-xs no-margin dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-file-pdf-o"></i> Action <span class="caret"></span></button>';
                $html .= '<ul class="dropdown-menu">';
                $html .= '<li><a href="'.route('vision.edit',$list->id).'">Edit</a></li>';
                $html .= '<li><a onclick="return confirm(\'Are you sure want to renew certificate?\')" href="'.route('vision.renew',$list->id).'">Renew</a></li>';
                $html .= '<li><a target="_blank" href="'.route('vision.pdf',$list->id).'">Certificate</a></li>';
                $html .= '</ul>';
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['check','user_info','company_info','issue_date','expire_date', 'action'])
            ->make(true);
    }

}
