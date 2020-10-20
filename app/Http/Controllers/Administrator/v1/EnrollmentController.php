<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\Company;
use App\Model\Cource;
use App\Model\Designation;
use App\Model\Ecertificate;
use App\Model\Enrollment;
use App\Model\Qualification;
use App\Model\Ref;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Admin;
use Sentinel;
use App\User;
use Carbon\Carbon;
use DB;

class EnrollmentController extends Controller
{
    public function __construct() {

    }

    public function enrollment(Request $request) {
        $user = Sentinel::getUser();
        $data['cerificate'] = Cource::where('status',1)->orderBy('id','ASC')->get();
        $data['company_list'] = Company::select('id','company_name')->where('status',1)->orderBy('id','DESC')->get();
        $data['ref_list'] = Ref::select('id','fname','lname')->where('status',1)->orderBy('id','DESC')->get();
        return view('admin.v1.enrollment.list',$data);
    }

    public function enrollmentPdf(Request $request,$id) {
        $user = Sentinel::getUser();
        $data['info'] = Enrollment::find($id);
        $data['certificate_short_name'] = self::getCretificateShortName($data['info']->certificates);
        $data['education_group_name'] = self::getEducationGroupName($data['info']->education);

        //company info
        $data['cinfo'] = $data['info']->company_id ? self::getCompanyInfo($data['info']->company_id) : [];
        $data['rinfo'] = $data['info']->ref_id ? self::getRefInfo($data['info']->ref_id) : [];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin.v1.enrollment.formpdf', $data);
        return $pdf->stream('enrollment'.$id.'.pdf');
    }

    public function enrollmentCertificate(Request $request,$id,$cid = null) {
        $user = Sentinel::getUser();
        $data['info'] = Enrollment::find($id);
        //$data['cinfo'] = self::getCertificateInfo($cid);
        $data['cid'] = $cid;
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin.v1.enrollment.pdf', $data);
        $filename = $cid ? self::generateCertificateNumber($id,$cid) : $id.' '.$data['info']->front_fname.' '.$data['info']->front_mname.' '.$data['info']->front_lname;
        return $pdf->stream(Admin::senatizeFileName($filename).'.pdf');
    }

    private function getCompanyUniqueNumber() {
        $user = Sentinel::getUser();
        $max_id = Company::withTrashed()->max('id');
        return $max_id + 1;
    }

    private function getEnrollmentUniqueNumber() {
        $user = Sentinel::getUser();
        $max_id = Enrollment::withTrashed()->max('id');
        return $max_id + 1;
    }

    public function getDesignation() {
        $list = Designation::select('name')->where('status',1)->get();
        return $list;
    }

    public function newEnrollment(Request $request) {
        $user = Sentinel::getUser();
        $data['cource'] = Cource::where('status',1)->orderBy('id','ASC')->get();
        $data['education'] = Qualification::where('status',1)->orderBy('name','ASC')->get();
        $data['company_list'] = Company::select('id','company_name')->where('status',1)->orderBy('id','DESC')->get();
        $data['ref_list'] = Ref::select('id','fname','lname')->where('status',1)->orderBy('id','DESC')->get();
        $data['comp']['max_id'] = self::getCompanyUniqueNumber();
        $data['comp']['designation'] = self::getDesignation();
        $data['enrollment_increment'] = self::getEnrollmentUniqueNumber();
        $data['enrollment_list'] = Enrollment::select('id','front_fname','front_lname')->orderBy('id','DESC')->get();
        return view('admin.v1.enrollment.new',$data);
    }

    public function  enrollmentInfo(Request $request) {
        $user = Sentinel::getUser();
        $info = Enrollment::find(request('id'));
        return response()->json($info);
    }

    public function editEnrollment(Request $request,$id) {
        $user = Sentinel::getUser();
        $data['cource'] = Cource::where('status',1)->orderBy('id','ASC')->get();
        $data['education'] = Qualification::where('status',1)->orderBy('name','ASC')->get();
        $data['company_list'] = Company::select('id','company_name')->where('status',1)->orderBy('id','DESC')->get();
        $data['enrollment_increment'] = $id;
        $data['comp']['max_id'] = self::getCompanyUniqueNumber();
        $data['comp']['designation'] = self::getDesignation();
        $data['company_name'] = Company::select('id','company_name')->where('status',1)->orderBy('id','DESC')->get();
        $data['info'] = Enrollment::find($id);
        $data['enrollment_list'] = Enrollment::select('id','front_fname','front_lname')->orderBy('id','DESC')->get();
        return view('admin.v1.enrollment.edit',$data);
    }

    public function getCretificateShortName($ids) {
        $user = Sentinel::getUser();
        $list = Cource::select(DB::Raw('GROUP_CONCAT(short_name) as short_name'))->whereIn('id',explode(',',$ids))->where('status',1)->first();
        return $list->short_name;
    }

    public static function getEducationGroupName($ids,$addtional = true) {
        $user = Sentinel::getUser();
        if($addtional) {
            $list = Qualification::select(DB::Raw("GROUP_CONCAT(CONCAT(' ',full_name)) as short_name"))->whereIn('id',explode(',',$ids))->where('status',1)->first();
        } else {
            $list = Qualification::select(DB::Raw("GROUP_CONCAT(CONCAT(' ',full_name)) as short_name"))->whereIn('id',explode(',',$ids))->where('status',1)->first();
        }
        return $list->short_name;
    }

    public function getCompanyName($id) {
        $user = Sentinel::getUser();
        $info = Company::find($id);
        return $info->company_name;
    }

    public function getCompanyInfo($id) {
        $user = Sentinel::getUser();
        $info = Company::find($id);
        return $info;
    }

    public function getRefInfo($id) {
        $user = Sentinel::getUser();
        $info = Ref::find($id);
        return $info;
    }

    public function getRefName($id) {
        $user = Sentinel::getUser();
        $info = Ref::find($id);
        return $info->fname.' '.$info->mname.' '.$info->lname;
    }

    public function expiredCertificate(Request $request) {
        $default_interval = 30;
        $user = Sentinel::getUser();
        $list = Ecertificate::query();
        $list->select('enrollment_certificate.id',
            'enrollment_certificate.enrollment_id',
            'enrollment_certificate.cource_id',
            'enrollment_certificate.from_date',
            'enrollment_certificate.to_date',
            'enrollment_certificate.notify_status',
            'enrollment.front_fname',
            'enrollment.front_lname',
            'enrollment.contact',
            'enrollment.email',
            'enrollment.photo',
            'cource.name',
            'cource.short_name');
        $list->leftJoin('enrollment', 'enrollment_certificate.enrollment_id', '=', 'enrollment.id');
        $list->leftJoin('cource', 'enrollment_certificate.cource_id', '=', 'cource.id');
        $list->whereRaw('enrollment_certificate.to_date between NOW() and adddate(NOW(), INTERVAL '.$default_interval.' DAY)');

        if(request('notify_status')) {
            $list->where('notify_status',request('notify_status'));
        }

        if(request('startdate') && request('enddate')) {
            if(request('startdate') != "" && request('enddate') != "") {
                $list->whereBetween('enrollment_certificate.to_date',[request('startdate'), request('enddate')]);
            }
        }

        $list->orderBy('enrollment_certificate.to_date','ASC')->get();


        return DataTables::of($list)
        ->addColumn('exp_info', function ($list) {
            $html = Admin::FormateDate($list->to_date);
            return $html;
        })
        ->addColumn('certificate_info', function ($list) {
            $certificate_no = self::generateCertificateNumber($list->enrollment_id,$list->id);
            $html = $list->name;
            $url = route('certificate.pdf',["id"=>$list->enrollment_id,"cid"=>$list->id]);
            $html .= '<br><span class="text-muted"><a href="'.$url.'" target="_blank">'.$certificate_no.'</a></span>';
            return $html;
        })
        ->addColumn('user_info', function ($list) {
            $html = '';
            $name = $list->front_fname.' '.$list->front_lname;
            $html .= '<img src="'.asset($list->photo).'" alt="'.$name.'" class="img-circle img-responsive" style="display:inline-block;vertical-align:top;" width="40"> <span style="display: inline-block;margin-left:5px;">'.$list->enrollment_id.'<br><span class="text-muted">'.$name.'</span></span>';
            return $html;
        })
        ->addColumn('contact_info', function ($list) {
            $html = $list->contact;
            $html .= '<br><span class="text-muted">'.$list->email.'</span>';
            return $html;
        })
        ->addColumn('status_info', function ($list) {
            $html = '';
            $select1 = $list->notify_status == 1 ? "selected" : "";
            $select2 = $list->notify_status == 2 ? "selected" : "";
            $select3 = $list->notify_status == 3 ? "selected" : "";
            $html .= '<select id="notify_status" name="notify_status" data-cid="'.$list->id.'" class="form-control update_notify_status">';
              $html .= '<option value="1" '.$select1.'>Pending</option>';
              $html .= '<option value="2" '.$select2.'>Completed</option>';
              $html .= '<option value="3" '.$select3.'>Not Required</option>';
            $html .= '</select>';
            return $html;
        })
        ->rawColumns(['exp_info','certificate_info','user_info','contact_info','status_info'])
        ->make(true);
    }

    public function enrollmentListAjax(Request $request) {
        $user = Sentinel::getUser();
        $enrollment = array();
        $list = Enrollment::query();
        $list->where('status',1);

        if(request('enrollment_no')) {
            $list->where('id',request('enrollment_no'));
        }
        if(request('search_by_name')) {
            $list->whereRaw("CONCAT(front_fname,' ',front_mname,' ',front_lname) LIKE '%".request('search_by_name')."%'");
        }
        if(request('ref_id')) {
            $list->where('ref_id',request('ref_id'));
        }
        if(request('creation')) {
            $list->where('creation',request('creation'));
        }
        if(request('company_id')) {
            $list->where('company_id',request('company_id'));
        }
        if(request('certificate_no')) {
            $list->whereRaw("FIND_IN_SET(".request('certificate_no').", certificates) > 0");
        }

        $list->orderby('id','DESC');
        $result = $list->get();
        foreach($result as $row) {
            //certificate info
            if($row->certificates != "") {
                $certificateShortName = self::getCretificateShortName($row->certificates);
            } else {
                $certificateShortName = '';
            }

            //comapny info
            if($row->company_id) {
                $company_name = self::getCompanyName($row->company_id);
            } else {
                $company_name = '';
            }

            //ref info
            if($row->ref_id) {
                $ref_name = self::getRefName($row->ref_id);
            } else {
                $ref_name = '';
            }

            //check type
            $ctype = "Unknown";
            if($row->creation == 1) {
                $ctype = "New";
            } else if($row->creation == 2) {
                $ctype = "Other";
            } else if($row->creation == 3) {
                $ctype = "Renew";
            } else {
                $ctype = "Unknown";
            }

            $alt_contact = $row->alt_contact ? ', '.$row->alt_contact : '';
            $child = [
                "id" => $row->id,
                "name" => $row->front_fname.' '.$row->front_mname.' '.$row->front_lname,
                "photo" => $row->photo,
                "certificate" => $certificateShortName,
                "ctype" => $ctype,
                "contact" => $row->contact.$alt_contact,
                "email" => $row->email,
                "company" => $company_name,
                "ref_name" => $ref_name
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
        ->addColumn('user_info', function ($enrollment) {
            $html = '';
            $html .= '<img src="'.asset($enrollment['photo']).'" alt="'.$enrollment['name'].'" class="img-circle img-responsive" style="display:inline-block;vertical-align:top;" width="40"> <span style="display: inline-block;margin-left:5px;">'.$enrollment['id'].'<br><span class="text-muted">'.$enrollment['name'].'</span></span>';
            return $html;
        })
        ->addColumn('certificate_info', function ($enrollment) {
            $html = $enrollment['certificate'];
            $html .= '<br><span class="text-muted">'.$enrollment['ctype'].'</span>';
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
            //$html .= ' <a href="'.route('enrollment.edit',$enrollment['id']).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
            //$html .= '<a href="'.route('enrollment.pdf').'" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-file-pdf-o"></i> Download</a>';
            $html .= '<div class="dropdown">';
            $html .= '<button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-file-pdf-o"></i> Action <span class="caret"></span></button>';
            $html .= '<ul class="dropdown-menu">';
            $html .= '<li><a href="'.route('enrollment.edit',$enrollment['id']).'">Edit</a></li>';
            $html .= '<li><a target="_blank" href="'.route('enrollment.pdf',$enrollment['id']).'">Enrollment Form</a></li>';

            //get all certificates
            $mycerti = self::getallCretificates($enrollment['id']);
            if(count($mycerti) > 0) {
                $html .= '<li><a target="_blank" href="'.route('certificate.pdf',["id"=>$enrollment['id']]).'">Certificates</a></li>';
            }

            $html .= '<li><a target="_blank" href="'.route('sticker.enrollment',["id"=>$enrollment['id']]).'">Download Sticker</a></li>';
//            foreach($mycerti as $row) {
//                $html .= '<li><a target="_blank" href="'.route('certificate.pdf',["id"=>$enrollment['id'],"cid"=>$row->id]).'">'.$row->short_name.' Certificate</a></li>';
//            }
            $html .= '<li><a href="'.route('enrollment.renew',$enrollment['id']).'" onclick="return confirm(\'Are You sure want to renew this certificate?\')">Renew Certificate</a></li>';
            $html .= '</ul>';
            $html .= '</div>';
            return $html;
        })
        ->rawColumns(['check','user_info','certificate_info','contact_info','other_info', 'action'])
        ->make(true);
    }

    public static function generateCertificateNumber($id,$cid) {
        $info = Enrollment::find($id);
        $cinfo = self::getCertificateInfo($cid);
        $renewal_prefix = $info->creation == 3 ? "R-" : "";
        return $cinfo->short_name.'-'.$info->ndt_level.'/'.date('m-y',strtotime($cinfo->from_date)).'/'.$renewal_prefix.$info->id;
    }

    public function ageCalculator(Request $request) {
        $dob = request('dob');
        if(request('type')) {
            echo date('d-m-Y', strtotime($dob . " +1 year") );
        } else {
            echo Carbon::parse($dob)->age;
        }
    }

    public function updateExpireStatus(Request $request) {
        $user = Sentinel::getUser();
        $validator = Validator::make($request->all(), [
            'cid' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $data['status'] = false;
            $data['message'] = $errors->first();
            return response()->json($data);
        }

        $update['notify_status'] = request('status');
        $response = Ecertificate::where('id',request('cid'))->update($update);

        if($response) {
            $data['status'] = true;
            $data['message'] = "Status updated successfully";
        } else {
            $data['status'] = false;
            $data['message'] = "Ooops..! Something went wrong";
        }
        return response()->json($data);
    }

    public function enrollmentRegister(Request $request) {
        $user = Sentinel::getUser();
        $validator = Validator::make($request->all(), [
            'front_fname' => 'required',
            'front_mname' => 'required',
            'front_lname' => 'required',
            'back_fname' => 'required',
            'back_mname' => 'required',
            'back_lname' => 'required',
            'father_fname' => 'required',
            'father_lname' => 'required',
            'education' => 'required',
            'exp_hour' => 'required',
            'exp_type' => 'required',
            'place' => 'required',
            'reg_date' => 'required',
            'creation' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $ins['certificates'] = request('certificates') ? implode(',',request('certificates')) : '';
        $ins['photo'] = request('fbinputtxt');
        $ins['front_greet'] = request('front_greet');
        $ins['front_fname'] = request('front_fname');
        $ins['front_mname'] = request('front_mname');
        $ins['front_lname'] = request('front_lname');
        $ins['back_greet'] = request('back_greet');
        $ins['back_fname'] = request('back_fname');
        $ins['back_mname'] = request('back_mname');
        $ins['back_lname'] = request('back_lname');
        $ins['father_greet'] = 'MR';
        $ins['father_fname'] = request('father_fname');
        $ins['father_mname'] = request('father_mname');
        $ins['father_lname'] = request('father_lname');
        $ins['dob'] = request('dob') ? date('Y-m-d',strtotime(request('dob'))) : NULL;
        $ins['age'] = request('age');
        $ins['is_whatsapp'] = request('is_whatsapp') ? 1 : 0;
        $ins['contact'] = request('contact');
        $ins['alt_contact'] = request('alt_contact');
        $ins['email'] = request('email');
        $ins['address'] = request('address');
        $ins['city'] = request('city');
        $ins['district'] = request('district');
        $ins['state'] = request('state');
        $ins['pincode'] = request('pincode');
        $ins['education'] = request('education') ? implode(',',request('education')) : '';
        $ins['year_of_complete'] = request('year_of_complete');
        $ins['exp_hour'] = request('exp_hour');
        $ins['exp_type'] = request('exp_type');
        $ins['company_id'] = request('company_id') != "" ? request('company_id') : 0;
        $ins['designation'] = request('designation');
        $ins['ref_id'] = request('ref_id') != "" ? request('ref_id') : 0;
        $ins['total_fees'] = request('total_fees');
        $ins['paid_fees'] = request('paid_fees');
        $ins['pending_fees'] = request('pending_fees');
        $ins['due_date'] = request('due_date') ? date('Y-m-d',strtotime(request('due_date'))) : NULL;
        $ins['place'] = request('place');
        $ins['reg_date'] = date('Y-m-d',strtotime(request('reg_date')));
        $ins['status'] = 1;
        $ins['creation'] = request('creation');
        $ins['ndt_level'] = request('ndt_level');
        $ins['snt_edition'] = request('snt_edition');
        $ins['vision'] = request('vision');
        $ins['sponsor'] = request('sponsor');
        $ins['company_id_certificate'] = request('company_id_certificate') ? request('company_id_certificate') : 0;

        $response = Enrollment::insertGetId($ins);

        $previous_certificate = request('creation') == 2 ? 0 : 1;

        if(request('certificates')) {
            self::deleteallenrollmentcertificate($response);
            $certificate_ids = request('certificates');
            $req_certificates = Cource::whereIn('id', $certificate_ids)->orderBy('id', 'ASC')->get();

            foreach($req_certificates as $cour) {
                $short_name = $cour->short_name;

                $cer['enrollment_id'] = $response;
                $cer['cource_id'] = $cour->id;
                $cer['cno'] = request($short_name.'_cno');
                $cer['previous_certificate'] = $previous_certificate;
                $cer['marks_general'] = request($short_name.'_general');
                $cer['marks_specific'] = request($short_name.'_specific');
                $cer['marks_practical'] = request($short_name.'_practical');
                $cer['marks_average'] = request($short_name.'_average');

                $history = [];
                if(request('creation') == 1) {
                    $certification_main_no = 0;
                    $final_last_fdate = $from_date = date('Y-m-d',strtotime(request('reg_date')));
                    $history[0]['no'] = $certification_main_no;
                    $history[0]['from_date'] = $from_date;
                    $history[0]['to_date'] = $final_last_tdate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($from_date)) . " + 5 years"));
                } else {
                    $cr_from_date = request($short_name . '_fromdate');
                    $cr_to_date = request($short_name . '_todate');
                    $seq = 0;
                    foreach ($cr_from_date as $key => $inf) {
                        if ($inf != "") {
                            $certification_main_no = $history[$seq]['no'] = $seq;
                            $final_last_fdate = $history[$seq]['from_date'] = date('Y-m-d', strtotime($cr_from_date[$seq]));
                            $final_last_tdate = $history[$seq]['to_date'] = date('Y-m-d', strtotime($cr_to_date[$seq]));
                            $seq++;
                        }
                    }
                }

                $cer['chistory'] = serialize($history);
                $cer['tno'] = $certification_main_no;
                $cer['from_date'] = $final_last_fdate;
                $cer['to_date'] = $final_last_tdate;
                Ecertificate::insert($cer);
            }
        }

        if($response) {
            //echo "done";
            return redirect()->route('enrollment')->with('success','Enrollment registered successfully');
        } else {
            //echo "not done";
            return back()->with('error','Oops..! Something went wrong');
        }
    }

    public function renewEnrollment(Request $request,$id) {
        $enrollment = Enrollment::where('id',$id)->first()->toArray();
        $addtional_year = 5;
        $no_certificate = explode(',',$enrollment['certificates']);
        $cource_fees = Cource::select(DB::Raw('sum(renew_fees) as renew_fees'))->whereIn('id',$no_certificate)->first();
        $total_fees = $cource_fees->renew_fees;

        //unset
        unset($enrollment['id']);
        unset($enrollment['created_at']);
        unset($enrollment['updated_at']);
        unset($enrollment['deleted_at']);

        //lasr tegistration date
        $due_date = date('Y-m-d',strtotime("now"));
        //$last_reg_date = date('Y-m-d',strtotime($enrollment['reg_date']));
        //$new_from_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($last_reg_date)) . " + 5 years"));
        //$new_to_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($new_from_date)) . " + 5 years"));

        //add in exp
        if($enrollment['exp_type'] == "Years") {
            $enrollment['exp_hour'] = intval($enrollment['exp_hour']) + $addtional_year;
        }


        $enrollment['due_date'] = $due_date;
        $enrollment['paid_fees'] = 0;
        $enrollment['pending_fees'] = 0;
        $enrollment['total_fees'] = $total_fees;
        $enrollment['reg_date'] = $due_date;
        $enrollment['creation'] = 3;
        $enrollment['snt_edition'] = Admin::getSNTEdition($enrollment['snt_edition']);

        $renew_enroll_id = Enrollment::insertGetId($enrollment);


        //get old certificate
        $certificates = Ecertificate::where('enrollment_id',$id)->get()->toArray();
        foreach($certificates as $row) {
            $row['cno'] = self::generateCertificateNumber($id,$row['id']);
            $history = unserialize($row['chistory']);
            $no = 0;
            foreach($history as $his) {
                $last_from_certificate_date = $his['from_date'];
                $last_to_certificate_date = $his['to_date'];
                $no++;
            }

            $history[$no]['no'] = $no;
            $row['from_date'] = $history[$no]['from_date'] = $last_to_certificate_date;
            $row['to_date'] = $history[$no]['to_date'] = date("Y-m-d", strtotime(date("Y-m-d", strtotime($last_to_certificate_date)) . " + ".$addtional_year." years"));
            $history = serialize($history);
            $row['chistory'] = $history;
            $row['tno'] = $no;
            $row['previous_certificate'] = 1;

            //unset
            unset($row['id']);
            unset($row['created_at']);
            unset($row['updated_at']);
            unset($row['deleted_at']);

            $row['enrollment_id'] = $renew_enroll_id;

            Ecertificate::insert($row);
        }

        return redirect()->route('enrollment')->with('success','Certificates Renewed Successfully');
    }

    private function deleteallenrollmentcertificate($id) {
        Ecertificate::where('enrollment_id',$id)->forceDelete();
    }

    public static function getallCretificates($eid) {
        $result = Ecertificate::select('enrollment_certificate.*','cource.name as counce_name','cource.short_name')
        ->leftJoin('cource', 'enrollment_certificate.cource_id', '=', 'cource.id')
        ->where('enrollment_certificate.enrollment_id',$eid)
        ->get();

        return $result;
    }
    
    public static function getCertificateInfo($cid) {
        $result = Ecertificate::select('enrollment_certificate.*','cource.name as counce_name','cource.short_name as short_name','cource.level1_hours','cource.level2_hours','cource.min_exp_hours_1','cource.min_exp_hours_2')
            ->leftJoin('cource', 'enrollment_certificate.cource_id', '=', 'cource.id')
            ->where('enrollment_certificate.id',$cid)
            ->first();

        return $result;
    }

    public static function getCertificateDetails($enrollment_id,$cource_id) {
        $result = Ecertificate::select('enrollment_certificate.*','cource.name as counce_name','cource.short_name','cource.level1_hours','cource.level2_hours','cource.min_exp_hours_1','cource.min_exp_hours_2')
            ->leftJoin('cource', 'enrollment_certificate.cource_id', '=', 'cource.id')
            ->where('enrollment_certificate.enrollment_id',$enrollment_id)
            ->where('enrollment_certificate.cource_id',$cource_id)
            ->first();

        return $result;
    }

    public function enrollmentUpdate(Request $request,$id) {
        $user = Sentinel::getUser();
        $validator = Validator::make($request->all(), [
            'front_fname' => 'required',
            'front_mname' => 'required',
            'front_lname' => 'required',
            'back_fname' => 'required',
            'back_mname' => 'required',
            'back_lname' => 'required',
            'father_fname' => 'required',
            'father_lname' => 'required',
            'education' => 'required',
            'exp_hour' => 'required',
            'exp_type' => 'required',
            'place' => 'required',
            'reg_date' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $ins['certificates'] = request('certificates') ? implode(',',request('certificates')) : '';
        $ins['photo'] = request('fbinputtxt');
        $ins['front_greet'] = request('front_greet');
        $ins['front_fname'] = request('front_fname');
        $ins['front_mname'] = request('front_mname');
        $ins['front_lname'] = request('front_lname');
        $ins['back_greet'] = request('back_greet');
        $ins['back_fname'] = request('back_fname');
        $ins['back_mname'] = request('back_mname');
        $ins['back_lname'] = request('back_lname');
        $ins['father_greet'] = 'MR';
        $ins['father_fname'] = request('father_fname');
        $ins['father_mname'] = request('father_mname');
        $ins['father_lname'] = request('father_lname');
        $ins['dob'] = request('dob') ? date('Y-m-d',strtotime(request('dob'))) : NULL;
        $ins['age'] = request('age');
        $ins['is_whatsapp'] = request('is_whatsapp') ? 1 : 0;
        $ins['contact'] = request('contact');
        $ins['alt_contact'] = request('alt_contact');
        $ins['email'] = request('email');
        $ins['address'] = request('address');
        $ins['city'] = request('city');
        $ins['district'] = request('district');
        $ins['state'] = request('state');
        $ins['pincode'] = request('pincode');
        $ins['education'] = request('education') ? implode(',',request('education')) : '';
        $ins['year_of_complete'] = request('year_of_complete');
        $ins['exp_hour'] = request('exp_hour');
        $ins['exp_type'] = request('exp_type');
        $ins['company_id'] = request('company_id') != "" ? request('company_id') : 0;
        $ins['designation'] = request('designation');
        $ins['ref_id'] = request('ref_id') != "" ? request('ref_id') : 0;
        $ins['total_fees'] = request('total_fees');
        $ins['paid_fees'] = request('paid_fees');
        $ins['pending_fees'] = request('pending_fees');
        $ins['due_date'] = request('due_date') ? date('Y-m-d',strtotime(request('due_date'))) : NULL;
        $ins['place'] = request('place');
        $ins['reg_date'] = date('Y-m-d',strtotime(request('reg_date')));
        //$ins['status'] = 1;
        //$ins['creation'] = request('creation');
        $ins['ndt_level'] = request('ndt_level');
        $ins['snt_edition'] = request('snt_edition');
        $ins['vision'] = request('vision');
        $ins['sponsor'] = request('sponsor');
        $ins['company_id_certificate'] = request('company_id_certificate') ? request('company_id_certificate') : 0;

        $response = Enrollment::where('id',$id)->update($ins);


        if(request('certificates')) {
            self::deleteallenrollmentcertificate($id);
            $certificate_ids = request('certificates');
            $req_certificates = Cource::whereIn('id', $certificate_ids)->orderBy('id', 'ASC')->get();

            foreach($req_certificates as $cour) {
                $short_name = $cour->short_name;

                $cer['enrollment_id'] = $id;
                $cer['cource_id'] = $cour->id;
                $cer['cno'] = request($short_name.'_cno');
                $cer['marks_general'] = request($short_name.'_general');
                $cer['marks_specific'] = request($short_name.'_specific');
                $cer['marks_practical'] = request($short_name.'_practical');
                $cer['marks_average'] = request($short_name.'_average');

                $history = [];
                if(request('creation') == 1) {
                    $certification_main_no = 0;
                    $final_last_fdate = $from_date = date('Y-m-d',strtotime(request('reg_date')));
                    $history[0]['no'] = $certification_main_no;
                    $history[0]['from_date'] = $from_date;
                    $history[0]['to_date'] = $final_last_tdate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($from_date)) . " + 5 years"));
                } else {
                    $cr_from_date = request($short_name . '_fromdate');
                    $cr_to_date = request($short_name . '_todate');
                    $seq = 0;
                    foreach ($cr_from_date as $key => $inf) {
                        if ($inf != "") {
                            $certification_main_no = $history[$seq]['no'] = $seq;
                            $final_last_fdate = $history[$seq]['from_date'] = date('Y-m-d', strtotime($cr_from_date[$seq]));
                            $final_last_tdate = $history[$seq]['to_date'] = date('Y-m-d', strtotime($cr_to_date[$seq]));
                            $seq++;
                        }
                    }
                }

                $cer['chistory'] = serialize($history);
                $cer['tno'] = $certification_main_no;
                $cer['from_date'] = $final_last_fdate;
                $cer['to_date'] = $final_last_tdate;
                Ecertificate::insert($cer);
            }
        }

        if($response) {
            return redirect()->route('enrollment')->with('success','Enrollment updated successfully');
        } else {
            return back()->with('error','Oops..! Something went wrong');
        }
    }

    public function enrollmentSticker(Request $request,$id) {
        $user = Sentinel::getUser();
        $data['info'] = Enrollment::find($id);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin.v1.enrollment.sticker', $data);
        return $pdf->stream('enrollment-sticker-'.$id.'.pdf');
    }
}
