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

class CertificateController extends Controller
{
    public function __construct() {

    }

    public function newVision(Request $request) {
        $user = Sentinel::getUser();
        $data = [];
        return view('admin.v1.certificate.new',$data);
    }
}
