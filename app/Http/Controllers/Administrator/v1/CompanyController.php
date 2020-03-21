<?php

namespace App\Http\Controllers\Administrator\v1;

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

    public function company(Request $request) {
        $user = Sentinel::getUser();
        $data = [];
        return view('admin.v1.company.list',$data);
    }

    public function companyListAjax(Request $request) {
        $user = Sentinel::getUser();
        $enrollment = array();
        for($x=1;$x<=30;$x++) {
            $child = [
                "id" => 1200-$x,
                "name" => "Relience Group of India",
                "company_type" => "Foundry Forging",
                "company_contact" => 8733883364+$x,
                "company_email" => "prime".$x."@gmail.com",
                "company_address" => "A-365, Subham estate, Vatva G.I.D.C",
                "company_city" => "Ahmedabad",
                "company_state" => "Gujarat",
                "company_pincode" => "382345",
                "person_name" => "Pankaj Narola",
                "person_contact" => 9825642316+$x,
                "person_email" => "pankaj".$x."@yahoo.com",
            ];
            array_push($enrollment,$child);
        }
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
                $html .= $enrollment['id'].'<br><span class="text-muted">'.$enrollment['name'].'</span>'.'<br><span class="text-muted">'.$enrollment['company_type'].'</span>';
                return $html;
            })
            ->addColumn('company_contact', function ($enrollment) {
                $html = $enrollment['company_contact'].'<br><span class="text-muted">'.$enrollment['company_email'].'</span>';
                return $html;
            })
            ->addColumn('company_address', function ($enrollment) {
                $html = $enrollment['company_address'].',<br>'.$enrollment['company_city'].','.$enrollment['company_state'].','.$enrollment['company_pincode'].'</span>';
                return $html;
            })
            ->addColumn('contact_person', function ($enrollment) {
                $html = $enrollment['person_name'].'<br><span class="text-muted">'.$enrollment['person_contact'].'<br><span class="text-muted">'.$enrollment['person_email'].'</span>';
                return $html;
            })
            ->addColumn('action', function ($enrollment) {
                $html = '';
                $html .= ' <a href="#" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
                $html .= ' <a href="'.route('enrollment.pdf').'" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-file-pdf-o"></i> Download</a>';
                return $html;
            })
            ->rawColumns(['check','company_info','company_contact','company_address','contact_person', 'action'])
            ->make(true);
    }
}
