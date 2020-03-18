<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\Company;
use App\Model\Qualification;
use App\Model\Ref;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Admin;
use Sentinel;
use App\User;

class EnrollmentController extends Controller
{
    public function __construct() {

    }

    public function enrollment(Request $request) {
        $user = Sentinel::getUser();
        $data = [];
        return view('admin.v1.enrollment.list',$data);
    }

    public function enrollmentPdf(Request $request) {
        $user = Sentinel::getUser();
        $data = [];
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin.v1.enrollment.pdf', $data);
        return $pdf->stream('enrollment'.rand(3000,4000).'.pdf');
    }

    public function newEnrollment(Request $request) {
        $user = Sentinel::getUser();
        $data['education'] = Qualification::where('status',1)->orderBy('name','ASC')->get();
        $data['company_list'] = Company::where('status',1)->orderBy('company_name','ASC')->get();
        $data['ref_list'] = Ref::where('status',1)->orderBy('id','DESC')->get();
        return view('admin.v1.enrollment.new',$data);
    }

    public function enrollmentListAjax(Request $request) {
        $user = Sentinel::getUser();
        $enrollment = array();
        for($x=1;$x<=30;$x++) {
            $child = [
                "id" => 3260-$x,
                "name" => "Parag Kadiya",
                "photo" => "user_404.jpg",
                "certificate" => "RT, UT, MT, PT, VT",
                "contact" => 8733883364+$x,
                "email" => "prime".$x."@gmail.com",
                "company" => "Relience Group of India",
                "ref_name" => "Pankaj Narola"
            ];
            array_push($enrollment,$child);
        }
        return DataTables::of($enrollment)
        ->addColumn('user_info', function ($enrollment) {
            $html = '';
            $html .= '<img src="'.asset($enrollment['photo']).'" alt="'.$enrollment['name'].'" class="img-circle img-responsive" style="display:inline-block;vertical-align:top;" width="40"> <span style="display: inline-block;margin-left:5px;">'.$enrollment['id'].'<br><span class="text-muted">'.$enrollment['name'].'</span></span>';
            return $html;
        })
        ->addColumn('certificate_info', function ($enrollment) {
            $html = $enrollment['certificate'];
            return $html;
        })
        ->addColumn('contact_info', function ($enrollment) {
            $html = $enrollment['contact'].'<br>'.$enrollment['email'];
            return $html;
        })
        ->addColumn('other_info', function ($enrollment) {
            $html = $enrollment['company'].'<br>'.$enrollment['ref_name'];
            return $html;
        })
        ->addColumn('action', function ($enrollment) {
            $html = '';
            $html .= ' <a href="#" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
            $html .= ' <a href="'.route('enrollment.pdf').'" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-file-pdf-o"></i> Download</a>';
            return $html;
        })
        ->rawColumns(['user_info','certificate_info','contact_info','other_info', 'action'])
        ->make(true);
    }
}
