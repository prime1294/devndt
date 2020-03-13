<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\Agent;
use App\Model\BanksUser;
use App\Model\BankTransection;
use App\Model\CashTransection;
use App\Model\ChequeTransection;
use App\Model\DailyProduction;
use App\Model\DeliveryChallan;
use App\Model\DeliveryChallanItem;
use App\Model\Expenses;
use App\Model\ExpensesCategory;
use App\Model\FrameReport;
use App\Model\Income;
use App\Model\IncomeCategory;
use App\Model\Invoice;
use App\Model\InvoiceItem;
use App\Model\InvoicePayment;
use App\Model\Karigar;
use App\Model\KarigarPayment;
use App\Model\KarigarReport;
use App\Model\Machine;
use App\Model\Material;
use App\Model\Party;
use App\Model\Payment;
use App\Model\PCDesgin;
use App\Model\PCDesignItem;
use App\Model\PCReceive;
use App\Model\Process;
use App\Model\ProcessPayment;
use App\Model\ProcessReceive;
use App\Model\ProgrammeCard;
use App\Model\ReadyStock;
use App\Model\Settlement;
use App\Model\Staff;
use App\Model\Stock;
use App\Model\StockItem;
use App\Model\StockItemQunatity;
use App\Model\StockProcess;
use App\Model\StockProcessItem;
use App\Model\WidLessAmount;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Illuminate\Support\Facades\Validator;
use Sentinel;
use Activation;
use Admin;
use DataTables;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class UserController extends Controller
{
    public function forgotPassword()
    {
      return view('admin.v1.auth.forgot');
    }

    public function profile() {
        $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
        return view('admin.v1.profile.profile',$data);
    }

    public function verifyOTP(Request $request,$id)
    {
      $user = Sentinel::findUserById($id);
      if($user) {
          if($user->otp != "") {
              $data['info'] = $user;
              return view('admin.v1.auth.verifyotp',$data);
          } else {
              return redirect()->route('user.login');
          }
      } else {
          return redirect()->route('user.login');
      }
    }

    public function newPassword(Request $request,$id) {
        $user = Sentinel::findUserById($id);
        if($user) {
             $data['info'] = $user;
             return view('admin.v1.auth.newpassword',$data);
        } else {
            return redirect()->route('user.login');
        }
    }

    public function login()
    {
//       $user = [
//         "email" => "8733883364@allinone.com",
//         "password" => "parag@123",
//         "first_name" => "Parag Kadiya",
//         "country_id" => "101",
//         "last_name" => "",
//         "mobilecode" => "91",
//         "mobile" => "8733883364",
//         "device_id" => "1234",
//         "fcm_token" => "1234",
//         "device_type" => "A",
//         "status" => 1
//       ];
//
//       $user = Sentinel::registerAndActivate($user);
//       $role = Sentinel::findRoleBySlug('admin');
//       $role->users()->attach($user);
      return view('admin.v1.auth.login');
    }

    public function verifyOTPNumber(Request $request) {
        $validator = Validator::make($request->all(), [
            'mobilecode' => 'required',
            'mobile' => 'required|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('alert-error', $errors->first());
        }

        $user = Sentinel::findUserById(request('user_id'));
        if($user) {
            if($user->otp != "") {
                if($user->otp == request('mobile')) {
                    User::where('id',$user->id)->update([
                        'otp' => NULL
                    ]);
                    return redirect()->route('user.new.password',$user->id)->with('alert-success','OTP verified. Please enter new password');
                } else {
                    return redirect()->back()->with('alert-error','You have enter wrong OTP');
                }
            } else {
                return redirect()->route('user.login');
            }
        } else {
            return redirect()->route('user.login');
        }

    }

    public function updateProfile(Request $request) {
        $user = Sentinel::getUser();
        $user = User::find($user->id);
        $response = $user->update($request->all());

        if(request('redirect')) {
            if($response) {
                return redirect()->route(request('redirect'))->with('success', "Profile updated Successfully");
            } else {
                return redirect()->route(request('redirect'))->with('error', "Ooops..! Something went wrong");
            }
        } else {
            if($response) {
                $data['status'] = "true";
                $data['message'] = "success";
            } else {
                $data['status'] = "false";
                $data['message'] = "Ooops...! Something went wrong";
            }
            return response()->json($data);
        }
    }

    public function registerNewPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'password' => 'required|required_with:password_confirm|same:password_confirm',
            'password_confirm' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('alert-error', $errors->first());
        }

        $user = Sentinel::findUserById(request('user_id'));
        if($user) {
            $user = Sentinel::update($user,  array('password' => request('password')));

            //patch profile password change
            if(request('redirect')) {
                if($user) {
                    return redirect()->route(request('redirect'))->with('success','Your password updated successfully');
                } else {
                    return redirect()->route(request('redirect'))->with('error', "Oops..! Something went wrong.");
                }
            }

            if($user) {
                return redirect()->route('user.login')->with('alert-success','Your password updated successfully');
            } else {
                return back()->with('alert-error', "Oops..! Something went wrong.");
            }
        } else {
            return redirect()->route('user.login');
        }
    }

    public function userForgot(Request $request) {
        $validator = Validator::make($request->all(), [
            'mobilecode' => 'required',
            'mobile' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('alert-error', $errors->first());
        }

        $user = Sentinel::findByCredentials($request->all());
        if($user) {
            $otp = rand(111111,999999);

            //store otp in DB
            User::where('id',$user->id)->update([
                'otp' => $otp
            ]);

            //send OTP in mobile
            $message = "Hello ".$user->first_name.",\nYour All IN ONE account OTP is : ".$otp;
            self::sendTextlocalmessage($message,$user->mobile);
            return redirect()->route('user.verify.otp',$user->id)->with('alert-success', "Password sent to your mobile number");

        } else {
            return back()->with('alert-error', "Invalid Account");
        }

    }

    private function sendTextlocalmessage($message,$mobile)
    {
        $message = rawurlencode($message);

        // Account details
        $apiKey = urlencode('qtHX7Y7/vYQ-ymB53IkyYAdJriySFZKnWB4zzPTB6l');

        // Message details
        $mobile = "91".$mobile;
        $numbers = array($mobile);
        $sender = urlencode('PSKKUM');

        $numbers = implode(',', $numbers);

        // Prepare data for POST request
        $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message, "unicode" => true);

        // Send the POST request with cURL
        $ch = curl_init('https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        // Process your response here
        return $response;
    }

    public function authorized(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'mobilecode' => 'required',
          'mobile' => 'required|max:255',
          'password' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('alert-error', $errors->first());
      }

      try {

          if (request('remember')) {
              $user = Sentinel::authenticateAndRemember($request->all());
          } else {
              $user = Sentinel::authenticate($request->all());
          }


          if (!empty($user)) {
              if (Activation::completed($user)) {
                  // User account is activated
                  User::where('id', $user->id)->update([
                      'last_login' => Admin::getUTC()
                  ]);

                  //do redirect
                  if (Sentinel::inRole('admin')) {
                      return redirect()->route('user.dashboard');
                  } else if (Sentinel::inRole('subadmin')) {
                      return "subadmin";
                  } else if (Sentinel::inRole('user')) {
                      return redirect()->route('user.dashboard');
                  } else {
                      return back()->with('alert-error', "Role not assign");
                  }

              } else {
                  // Not activated
                  return back()->with('alert-error', "Your Account Inactive");
              }
          } else {
              return back()->with('alert-error', "Invalid login detail");
          }

      } catch(ThrottlingException $e) {
          return back()->with('alert-error', $e->getMessage());
      }
    }

    public function logout() {
      Sentinel::logout();
      return redirect()->route('user.login');
    }

    public function redirecting(Request $request) {
      if(request('redirect') && request('redirectback')) {
        $redirecting = request('redirect');
        $id = request('id');
        $toid = request('toid');
        $redirectingback = request('redirectback');
        $request->session()->put('redirecting', $redirecting);
        $request->session()->put('redirectingback', $redirectingback);
        $request->session()->put('redirectingbackid', $id);
        $request->session()->put('redirectingid', $toid);
        if($toid != "") {
          return redirect()->route($redirecting,$toid);
        } else {
          return redirect()->route($redirecting);
        }
      } else {
        return redirect()->route('user.dashboard');
      }
    }


    public function uploadImage(Request $request) {
      // $iWidth = $iHeight = 200; // desired image result dimensions
      $iWidth = request('w');
      $iHeight = request('h');
      $iJpgQuality = 90;
      $newfilenameupload = rand(111,999999).time();
      if ($request->hasFile('image_file')) {
        $dir = request('upload_dir');
        $image = $request->file('image_file');
        $name = $newfilenameupload.'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path($dir);
        $imagepath = $image->move($destinationPath, $name);
        $sTempFileName = public_path($dir.$name);
        @chmod($sTempFileName, 0644);



        //process
        if (file_exists($sTempFileName) && filesize($sTempFileName) > 0) {
          // echo "true";
              $aSize = getimagesize($sTempFileName); // try to obtain image info
              if (!$aSize) {
                  @unlink($sTempFileName);
                  return "false";
              }
              // check for image type
              // print_r($aSize);
              switch($aSize[2]) {
                  case IMAGETYPE_JPEG:
                      $sExt = '.jpg';
                      // create a new image from file
                      $vImg = @imagecreatefromjpeg($sTempFileName);
                      break;
                  case IMAGETYPE_WEBP:
                      $sExt = '.jpg';
                      // create a new image from file
                      $vImg = @imagecreatefromwebp($sTempFileName);
                      break;
                  case IMAGETYPE_GIF:
                      $sExt = '.gif';
                      // create a new image from file
                      $vImg = @imagecreatefromgif($sTempFileName);
                      break;
                  case IMAGETYPE_PNG:
                      $sExt = '.png';
                      // create a new image from file
                      $vImg = @imagecreatefrompng($sTempFileName);
                      break;
                  default:
                      @unlink($sTempFileName);
                      return "false";
              }
              // create a new true color image
              $vDstImg = @imagecreatetruecolor( $iWidth, $iHeight );
              // copy and resize part of an image with resampling
              imagecopyresampled($vDstImg, $vImg, 0, 0, (int)request('x1'), (int)request('y1'), $iWidth, $iHeight, (int)request('w'), (int)request('h'));
              // define a result image filename
              $sResultFileName = $sTempFileName . $sExt;
              // output image to file
              imagejpeg($vDstImg, $sResultFileName, $iJpgQuality);
              @unlink($sTempFileName);
              $renamefilename = public_path($dir.$newfilenameupload.$sExt);
              rename($sResultFileName,$renamefilename);
              return $dir.$newfilenameupload.$sExt;
      } else {
        return "false";
      }
        //end process
      } else {
        return "false";
      }
    }


    public function userList(Request $request) {
        $user = Sentinel::getUser();
        return view('admin.v1.admin.user');
    }

    public function userListAjax(Request $request) {
        $role = Sentinel::findRoleById(3);
        $list = $role->users()->with('roles')->get();
        return DataTables::of($list)
        ->addColumn('personal_info', function ($list) {
            $html = '';
            $html .= '<img src="'.asset($list->image).'" alt="'.$list->first_name.'" class="img-responsive img-circle" style="display:inline-block;vertical-align:top; width: 40px;"> <span style="display: inline-block;margin-left:5px;">'.$list->first_name.'<br><span class="text-muted">'.$list->business_name.'</span></span>';
            return $html;
        })
        ->addColumn('contact_info', function ($list) {
            $html = '';
            $html .= $list->mo_number;
            if($list->alt_number != "") {
                $html .=  ', '.$list->alt_number;
            }
            if($list->email_id != "") {
                $html .=  '<br><span class="text-muted">'.$list->email_id.'</span>';
            }
            return $html;
        })
        ->addColumn('account_info', function ($list) {
            $html = '';
            $html .= $list->mobile;
            if($list->trial_start != "" && $list->trial_end != "") {
                $html .=  '<br><span class="text-muted">'.Admin::FormateDate($list->trial_start).' To '.Admin::FormateDate($list->trial_end).'</span>';
            }
            if($list->last_login != "") {
                $html .=  '<br><span class="text-muted">Last Login: '.date('d/m/Y h:i:s',strtotime($list->last_login)).'</span>';
            }
            return $html;
        })
        ->addColumn('action', function ($list) {
            $html = '';
            $activation_status = $list->status == 1 ? 'checked' : "";
            $html .= '<input type="checkbox" class="status_checkbox" data-id=" '.$list->id.'" '.$activation_status.' data-size="mini" data-toggle="toggle" data-on="Active" data-off="Deactive" data-onstyle="success" data-offstyle="danger">';
            $html .= ' <a href="'.route('user.edit',$list->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
            return $html;
        })
        ->rawColumns(['personal_info','contact_info','account_info','action'])
        ->make(true);
    }

    public function ActivationUser(Request $request) {
        $party = User::find(request('id'));
        $party->status = request('status');
        $party->save();
        return $party;
    }

    public function userNew(Request $request)
    {
        $data['type'] = 'add';
        $data['pinfo'] = [];
        $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
        return view('admin.v1.admin.new',$data);
    }

    public function editUser(Request $request,$id)
    {
        $data['type'] = 'edit';
        $data['pinfo'] = User::find($id);
        $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
        return view('admin.v1.admin.new',$data);
    }

    public function updateUser(Request $request,$id) {
        $validator = Validator::make($request->all(), [
            'trial_start' => 'required|date',
            'trial_end' => 'required|date|after_or_equal:trial_start',
            'first_name' => 'required',
            'business_name' => 'required',
            'mobile' => 'required|unique:users,mobile,'.$id,
            'state' => 'required|exists:states,state_id',
            'city' => 'required|exists:cities,city_id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $data = $request->all();
        $data['image'] = request('fbinputtxt');
        $data['trial_start'] = date('Y-m-d',strtotime(request('trial_start'))); //remove
        $data['trial_end'] = date('Y-m-d',strtotime(request('trial_end'))); //remove
        $response = User::find($id);
        $response->update($data);

        if($response) {
            return redirect()->route('user.list')->with('success', "User updated successfully");
        } else {
            return redirect()->route('user.list')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function registerUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'trial_start' => 'required|date',
            'trial_end' => 'required|date|after_or_equal:trial_start',
            'first_name' => 'required',
            'business_name' => 'required',
            'mobile' => 'required|unique:users,mobile',
            'state' => 'required|exists:states,state_id',
            'city' => 'required|exists:cities,city_id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $input = $request->toArray();
        $input['email'] = request('mobile').'@allinone.com'; //remove
        $input['password'] = request('mobile'); //remove
        if(request('fbinputtxt')) {
            $input['image'] = request('fbinputtxt'); //remove
        }
        $input['last_login'] = NULL; //remove
        $input['last_name'] = ""; //remove
        $input['country_id'] = 101; //remove
        $input['device_id'] = 1234; //remove
        $input['fcm_token'] = 1234; //remove
        $input['device_type'] = 0; //remove
        $input['signature'] = NULL; //remove
        $input['trial_start'] = date('Y-m-d',strtotime(request('trial_start'))); //remove
        $input['trial_end'] = date('Y-m-d',strtotime(request('trial_end'))); //remove
        $input['status'] = 1; //remove
//        print_r($input); die();

        $user = Sentinel::registerAndActivate($input);
       $role = Sentinel::findRoleBySlug('user');
       $attach = $role->users()->attach($user);
       $user_info = User::find($user->id);
       unset($input['password']);
       $response = $user_info->update($input);
        if($user) {
            return redirect()->route('user.list')->with('success', "User added successfully");
        } else {
            return redirect()->route('user.list')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function remove3635(Request $request) {
        $user = Sentinel::getUser();
        BanksUser::where('user_id',$user->id)->forceDelete();
        BankTransection::where('user_id',$user->id)->forceDelete();
        CashTransection::where('user_id',$user->id)->forceDelete();
        ChequeTransection::where('user_id',$user->id)->forceDelete();
        DailyProduction::where('user_id',$user->id)->forceDelete();
        DeliveryChallan::where('user_id',$user->id)->forceDelete();
        DeliveryChallanItem::where('user_id',$user->id)->forceDelete();
        Expenses::where('user_id',$user->id)->forceDelete();
        ExpensesCategory::where('user_id',$user->id)->forceDelete();
        FrameReport::where('user_id',$user->id)->forceDelete();
        Income::where('user_id',$user->id)->forceDelete();
        IncomeCategory::where('user_id',$user->id)->forceDelete();
        Invoice::where('user_id',$user->id)->forceDelete();
        InvoiceItem::where('user_id',$user->id)->forceDelete();
        InvoicePayment::where('user_id',$user->id)->forceDelete();
        KarigarPayment::where('user_id',$user->id)->forceDelete();
        KarigarReport::where('user_id',$user->id)->forceDelete();
        Payment::where('user_id',$user->id)->forceDelete();
        ProcessPayment::where('user_id',$user->id)->forceDelete();
        ProcessReceive::where('user_id',$user->id)->forceDelete();
        ProgrammeCard::where('user_id',$user->id)->forceDelete();
        PCDesgin::where('user_id',$user->id)->forceDelete();
        PCDesignItem::where('user_id',$user->id)->forceDelete();
        PCReceive::where('user_id',$user->id)->forceDelete();
        ReadyStock::where('user_id',$user->id)->forceDelete();
        Settlement::where('user_id',$user->id)->forceDelete();
        Stock::where('user_id',$user->id)->forceDelete();
        StockItem::where('user_id',$user->id)->forceDelete();
        StockItemQunatity::where('user_id',$user->id)->forceDelete();
        StockProcess::where('user_id',$user->id)->forceDelete();
        StockProcessItem::where('user_id',$user->id)->forceDelete();
        WidLessAmount::where('user_id',$user->id)->forceDelete();
        Party::onlyTrashed()->forceDelete();
        Agent::onlyTrashed()->forceDelete();
        Staff::onlyTrashed()->forceDelete();
        Karigar::onlyTrashed()->forceDelete();
        Material::onlyTrashed()->forceDelete();
        Process::onlyTrashed()->forceDelete();
        Machine::onlyTrashed()->forceDelete();
        echo "data removed successfully";
    }

}
