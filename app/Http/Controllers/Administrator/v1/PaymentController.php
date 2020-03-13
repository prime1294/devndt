<?php

namespace App\Http\Controllers\Administrator\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Admin;
use Sentinel;
use App\User;
use App\Model\Payment;
use App\Model\BanksUser;
use App\Model\BankTransection;
use App\Model\CashTransection;
use App\Model\ChequeTransection;

class PaymentController extends Controller
{
    public function __construct() {

    }

    public function paymentIn(Request $request) {
      $data['bank_list'] = self::myBanks();
      return view('admin.v1.payment.in',$data);
    }

    private  function checkChequeStatus($tid) {
        $user = Sentinel::getUser();
        //get payment info
        $pinfo = Payment::find($tid);
        if($pinfo->payment_type == "cheque") {
            //check payment_type
            //if cheque then get tid
            //check cheque status
            $cinfo = ChequeTransection::where('tid',$pinfo->tid)->where('user_id',$user->id)->first();
            if($cinfo->cheque_status == 1) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function editPayment(Request $request,$id) {
      $data['bank_list'] = self::myBanks();
      $data['info'] = Payment::find($id);
      $data['check_status'] = self::checkChequeStatus($id);
      return view('admin.v1.payment.edit',$data);
    }

    public function paymentOut(Request $request) {
      $data['bank_list'] = self::myBanks();
      return view('admin.v1.payment.out',$data);
    }

    public function paymentinRegister(Request $request) {
      $user = Sentinel::getUser();
      $validate['master_type'] = 'required';
      $validate['master_user'] = 'required';
      $validate['adjustment_date'] = 'required';
      $validate['payment_type'] = 'required';
      $validate['bank_amount'] = 'required';

      $validator = Validator::make($request->all(),$validate);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('error', $errors->first());
      }

      $master_type = request('master_type');
      $master_user = request('master_user');
      $payment_type = request('payment_type');
      $bank_amount = request('bank_amount');
      $remarks = request('remarks');
      $adjustmentDate = date('Y-m-d',strtotime(request('adjustment_date')));

      //upload image ref_img
      if ($request->hasFile('ref_img')) {
        $dir = 'image/payment/';
        $image = $request->file('ref_img');
        $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path($dir);
        $imagepath = $image->move($destinationPath, $name);
        $paymentin['ref_img'] = $dir.$name;
      }

      $tid = Admin::uniqueTransectionId("PAYIN",$user->id);

      //payment table
      $paymentin['user_id'] = $user->id;
      $paymentin['type'] = "in";
      $paymentin['master_type'] = $master_type;
      $paymentin['master_id'] = $master_user;
      $paymentin['transection_date'] = $adjustmentDate;
      $paymentin['ref_no'] = request('ref_no');
      $paymentin['recipt_no'] = request('recipt_no');
      $paymentin['payment_type'] = $payment_type;
      $paymentin['amount'] = $bank_amount;
      $paymentin['remarks'] = $remarks;
      $paymentin['tid'] = $tid;
      $response = Payment::insertGetId($paymentin);

      //other table entry Bank, Cheque, Cash
      if(strpos($payment_type, 'bank_ref_') !== false) {
        //bank transection
        $bank_user_id = explode('_',$payment_type);
        $bank_user_id = end($bank_user_id);
        $response2 = self::bankTransection("in",$bank_user_id,$bank_amount,$remarks,$adjustmentDate,$response,$tid,request('recipt_no'));
        $redirect = "bankaccount";
      } else {
        if($payment_type == "cheque") {
          //cheque
          $response2 = self::chequeTransection("in",$bank_amount,$remarks,$adjustmentDate,$response,request('ref_no'),$tid,request('recipt_no'));
          $redirect = "cheque";
        } else {
          //cash
          $response2 = self::cashTransection("in",$bank_amount,$remarks,$adjustmentDate,$response,$tid,request('recipt_no'));
          $redirect = "cashinhand";
        }
      }

      if($response && $response2) {
        return redirect()->route($redirect)->with('success', "Payment in Successfully");
      } else {
        return redirect()->route('paymentin')->with('error', "Ooops..! Something went wrong");
      }
    }

    public function deletePayment(Request $request,$id) {
      $user = Sentinel::getUser();
      if(Payment::HaveRightBank($user->id,$id)) {

        //check first cheque is reopen or not
          if(self::checkChequeStatus($id) == false) {
              return Redirect::back()->with('error', "You can not delete this transaction. you have to reopen cheque first.");
          }

        $info = Payment::find($id);
        $response = self::removeAllTrasection($info->tid,"true");

        if($response) {
          return Admin::checkRedirect($request,'paymentin',"Transection deleted Successfully");
        } else {
          return redirect()->route('paymentin')->with('error', "Ooops..! Something went wrong");
        }

      } else {
        return Admin::unauth();
      }
    }

    public function paymentUpdate(Request $request,$id,$type) {
      $user = Sentinel::getUser();
      $validate['master_type'] = 'required';
      $validate['master_user'] = 'required';
      $validate['adjustment_date'] = 'required';
      $validate['payment_type'] = 'required';
      $validate['bank_amount'] = 'required';

      $validator = Validator::make($request->all(),$validate);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('error', $errors->first());
      }

      $info = Payment::where('id',$id)->where('type',$type)->first();
      $master_type = request('master_type');
      $master_user = request('master_user');
      $payment_type = request('payment_type');
      $bank_amount = request('bank_amount');
      $remarks = request('remarks');
      $adjustmentDate = date('Y-m-d',strtotime(request('adjustment_date')));

      //upload image ref_img
      if ($request->hasFile('ref_img')) {
        $dir = 'image/payment/';
        $image = $request->file('ref_img');
        $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path($dir);
        $imagepath = $image->move($destinationPath, $name);
        $paymentin['ref_img'] = $dir.$name;
      } else {
        $paymentin['ref_img'] = $info->ref_img;
      }

      //remove all same transection
      self::removeAllTrasection($info->tid,"true");

      //start new register
      $tid = Admin::uniqueTransectionId("PAYIN",$user->id);
      $math = $type == "out" ? "-" : "";

      //payment table
      $paymentin['user_id'] = $user->id;
      $paymentin['type'] = $type;
      $paymentin['master_type'] = $master_type;
      $paymentin['master_id'] = $master_user;
      $paymentin['transection_date'] = $adjustmentDate;
      $paymentin['ref_no'] = request('ref_no');
      $paymentin['recipt_no'] = request('recipt_no');
      $paymentin['payment_type'] = $payment_type;
      $paymentin['amount'] =  $math.$bank_amount;
      $paymentin['remarks'] = $remarks;
      $paymentin['tid'] = $tid;
      $response = Payment::insertGetId($paymentin);

      //other table entry Bank, Cheque, Cash
      if(strpos($payment_type, 'bank_ref_') !== false) {
        //bank transection
        $bank_user_id = explode('_',$payment_type);
        $bank_user_id = end($bank_user_id);
        $response2 = self::bankTransection($type,$bank_user_id,$bank_amount,$remarks,$adjustmentDate,$response,$tid,request('recipt_no'));
        $redirect = "bankaccount";
      } else {
        if($payment_type == "cheque") {
          //cheque
          $response2 = self::chequeTransection($type,$bank_amount,$remarks,$adjustmentDate,$response,request('ref_no'),$tid,request('recipt_no'));
          $redirect = "cheque";
        } else {
          //cash
          $response2 = self::cashTransection($type,$bank_amount,$remarks,$adjustmentDate,$response,$tid,request('recipt_no'));
          $redirect = "cashinhand";
        }
      }

      if($response && $response2) {
        return Admin::checkRedirect($request,$redirect,"Transection updated Successfully");
      } else {
        return redirect()->route($redirect)->with('error', "Ooops..! Something went wrong");
      }

    }

    private function removeAllTrasection($tid,$force = "false") {
      $user = Sentinel::getUser();
      $respose = BankTransection::where('tid',$tid);
      $respose2 = CashTransection::where('tid',$tid);
      $respose3 = ChequeTransection::where('tid',$tid);
      $respose4 = Payment::where('tid',$tid);
      if($force == "true") {
        $respose->forceDelete();
        $respose2->forceDelete();
        $respose3->forceDelete();
        $respose4->forceDelete();
      } else {
        $respose->delete();
        $respose2->delete();
        $respose3->delete();
        $respose4->delete();
      }
      return true;
    }

    public function paymentoutRegister(Request $request) {
      $user = Sentinel::getUser();
      $validate['master_type'] = 'required';
      $validate['master_user'] = 'required';
      $validate['adjustment_date'] = 'required';
      $validate['payment_type'] = 'required';
      $validate['bank_amount'] = 'required';

      $validator = Validator::make($request->all(),$validate);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('error', $errors->first());
      }

      $master_type = request('master_type');
      $master_user = request('master_user');
      $payment_type = request('payment_type');
      $bank_amount = request('bank_amount');
      $remarks = request('remarks');
      $adjustmentDate = date('Y-m-d',strtotime(request('adjustment_date')));

      //upload image ref_img
      if ($request->hasFile('ref_img')) {
        $dir = 'image/payment/';
        $image = $request->file('ref_img');
        $name = rand(111,999999).time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path($dir);
        $imagepath = $image->move($destinationPath, $name);
        $paymentin['ref_img'] = $dir.$name;
      }

      $tid = Admin::uniqueTransectionId("PAYOUT",$user->id);

      //payment table
      $paymentin['user_id'] = $user->id;
      $paymentin['type'] = "out";
      $paymentin['master_type'] = $master_type;
      $paymentin['master_id'] = $master_user;
      $paymentin['transection_date'] = $adjustmentDate;
      $paymentin['ref_no'] = request('ref_no');
      $paymentin['recipt_no'] = request('recipt_no');
      $paymentin['payment_type'] = $payment_type;
      $paymentin['amount'] = '-'.$bank_amount;
      $paymentin['remarks'] = $remarks;
      $paymentin['tid'] = $tid;
      $response = Payment::insertGetId($paymentin);

      //other table entry Bank, Cheque, Cash
      if(strpos($payment_type, 'bank_ref_') !== false) {
        //bank transection
        $bank_user_id = explode('_',$payment_type);
        $bank_user_id = end($bank_user_id);
        $response2 = self::bankTransection("out",$bank_user_id,$bank_amount,$remarks,$adjustmentDate,$response,$tid,request('recipt_no'));
        $redirect = "bankaccount";
      } else {
        if($payment_type == "cheque") {
          //cheque
          $response2 = self::chequeTransection("out",$bank_amount,$remarks,$adjustmentDate,$response,request('ref_no'),$tid,request('recipt_no'));
          $redirect = "cheque";
        } else {
          //cash
          $response2 = self::cashTransection("out",$bank_amount,$remarks,$adjustmentDate,$response,$tid,request('recipt_no'));
          $redirect = "cashinhand";
        }
      }

      if($response && $response2) {
        return redirect()->route($redirect)->with('success', "Payment Out Successfully");
      } else {
        return redirect()->route($redirect)->with('error', "Ooops..! Something went wrong");
      }
    }

    private function chequeTransection($type,$bank_amount,$remarks,$adjustmentDate,$paymentid,$refno,$tid,$ref_no = "") {
      if($type == "out") {
        $transection_type = "cheque2";
        $math_type = "-";
      } else {
        $transection_type = "cheque1";
        $math_type = "";
      }

      //cheque +
      $user = Sentinel::getUser();
      $cash['user_id'] = $user->id;
      $cash['type'] = $transection_type;
      $cash['amount'] = $math_type.$bank_amount;
      $cash['remarks'] = $remarks;
      $cash['transection_date'] = $adjustmentDate;
      $cash['ref_no'] = $refno;
      $cash['ref_tbl_no'] = $paymentid;
      $cash['tid'] = $tid;
      $cash['udf1'] = $ref_no;
      $response = ChequeTransection::insert($cash);
      return $response ? true : false;
    }

    private function cashTransection($type,$bank_amount,$remarks,$adjustmentDate,$paymentid,$tid,$refno = "") {
      if($type == "out") {
        $transection_type = "cash6";
        $math_type = "-";
      } else {
        $transection_type = "cash5";
        $math_type = "";
      }

      //cash +
      $user = Sentinel::getUser();
      $cash['user_id'] = $user->id;
      $cash['type'] = $transection_type;
      $cash['amount'] = $math_type.$bank_amount;
      $cash['remarks'] = $remarks;
      $cash['transection_date'] = $adjustmentDate;
      $cash['ref_tbl_no'] = $paymentid;
      $cash['tid'] = $tid;
      $cash['udf1'] = $refno;
      $response = CashTransection::insert($cash);
      return $response ? true : false;
    }

    private function bankTransection($type,$bank_user_id,$bank_amount,$remarks,$adjustmentDate,$paymentid,$tid,$refno = "") {
      if($type == "out") {
        $transection_type = "bank3";
        $math_type = "-";
      } else {
        $transection_type = "bank2";
        $math_type = "";
      }

      $user = Sentinel::getUser();
      $bank['user_id'] = $user->id;
      $bank['bank_user_id'] = $bank_user_id;
      $bank['type'] = $transection_type;
      $bank['amount'] = $math_type.$bank_amount;
      $bank['remarks'] = $remarks;
      $bank['transection_date'] = $adjustmentDate;
      $bank['ref_tbl_no'] = $paymentid;
      $bank['tid'] = $tid;
      $bank['udf1'] = $refno;
      $response = BankTransection::insert($bank);
      return $response ? true : false;
    }

    private function myBanks() {
      $user = Sentinel::getUser();
      return BanksUser::select(
      'banks_users.id','banks_users.bank_id','banks_users.name','banks_users.account_no','banks_users.ifsc','banks_users.type','banks_users.branch','banks_users.status',
      'banks.name as bankname','banks.icon as bankicon',
      DB::raw('COALESCE(SUM(bank_transection.amount),0) as bankbalance')
      )
      ->leftJoin('banks', function($join) {
        $join->on('banks_users.bank_id', '=', 'banks.id');
        $join->where('banks_users.status', '=', '1');
        $join->whereNull('banks_users.deleted_at');
      })
      ->leftJoin('bank_transection', function($join)  use($user) {
        $join->on('banks_users.id', '=', 'bank_transection.bank_user_id');
        $join->on('bank_transection.user_id', '=', DB::raw('"'.$user->id.'"'));
        $join->where('bank_transection.status', '=', '1');
        $join->whereNull('bank_transection.deleted_at');
      })
      ->where('banks.status',1)
      ->groupBy('banks_users.id')
      ->get();
    }
}
