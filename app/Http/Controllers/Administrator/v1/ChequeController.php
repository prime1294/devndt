<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\Expenses;
use App\Model\Invoice;
use App\Model\Process;
use App\Model\StockProcess;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Admin;
use Sentinel;
use App\User;
use App\Model\Bank;
use App\Model\Banks;
use App\Model\BanksUser;
use App\Model\BankTransection;
use App\Model\CashTransection;
use App\Model\ChequeTransection;
use App\Model\Payment;

class ChequeController extends Controller
{
  public function __construct() {

  }

  public function cheque(Request $request)
  {
    return view('admin.v1.cheque.list');
  }

  public function adjustmentChequeRegister(Request $request,$id) {
    $user = Sentinel::getUser();
    $validate['entry_type'] = 'required';
    $validate['adjustment_date'] = 'required';

    if(request('entry_type') == "cheque4" || request('entry_type') == "cheque11") {
      $validate['bank_account'] = 'required|exists:banks_users,id,user_id,'.$user->id;
    }

    $validator = Validator::make($request->all(),$validate);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $info = ChequeTransection::find($id);
    $entry_type = request('entry_type');
    $amount = $info->amount;
    $remarks = request('remarks');
    $adjustmentDate = date('Y-m-d',strtotime(request('adjustment_date')));
    $frombank = request('bank_account');



      if($entry_type == "cheque4" || $entry_type == "cheque11") {
          //Bank
          $transection_type = $entry_type == "cheque4" ?  "bank9" : "bank16";
          $response = self::chequeDepositetoBank($frombank,$amount,$remarks,$adjustmentDate,$info,$transection_type);
      } elseif($entry_type == "cheque3" || $entry_type == "cheque10") {
          //cash
          $transection_type = $entry_type == "cheque3" ?  "cash7" : "cash14";
          $response = self::chequeDepositetoCash($amount,$remarks,$adjustmentDate,$info,$transection_type);
      } else {
          $response = false;
      }

      if($amount >= 0) {
          $message = "Cheque Deposit Successfully";
      } else {
          $message = "Cheque Withdraw Successfully";
      }

    if($response) {
      return redirect()->route('cheque')->with('success',$message);
    } else {
      return redirect()->route('cheque')->with('error', "Ooops..! Something went wrong");
    }

  }

  private function chequeDepositetoCash($amount,$remarks,$adjustmentDate,$info,$ttype) {
    $user = Sentinel::getUser();

    //cash +
    $cash['user_id'] = $user->id;
    $cash['type'] = $ttype;
    $cash['amount'] = $amount;
    $cash['remarks'] = $remarks;
    $cash['tid'] = $info->tid;
    $cash['ref_tbl_no'] = $info->id;
    $cash['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));;
    $response = CashTransection::insert($cash);

    //update cheque
    $response2 = self::updateChequeDetails("close",$info->id);
    return ($response && $response2) ? true : false;

  }

  private function chequeDepositetoBank($frombank,$amount,$remarks,$adjustmentDate,$info,$ttype) {
    $user = Sentinel::getUser();
    // bank +
    $bank['user_id'] = $user->id;
    $bank['bank_user_id'] = $frombank;
    $bank['type'] = $ttype;
    $bank['amount'] = $amount;
    $bank['remarks'] = $remarks;
    $bank['tid'] = $info->tid;
    $bank['ref_tbl_no'] = $info->id;
    $bank['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));;
    $response = BankTransection::insertGetId($bank);

    //update cheque
    $response2 = self::updateChequeDetails("close",$info->id);
    return ($response && $response2) ? true : false;
  }

  private function updateChequeDetails($action,$id) {
    $chequeStatus = $action == "close" ? 1 : 0;
    $ct = ChequeTransection::find($id);
    $ct->cheque_status = $chequeStatus;
    $response = $ct->save();
    return $response ? true : false;
  }

  public function getTransection(Request $request) {
    $user = Sentinel::getUser();
    $transection = ChequeTransection::query();
    $transection->where('user_id',$user->id)->where('status',1);

    if(request('filter_by') != "") {
      $transection->where('type',request('filter_by'));
    }

    if(request('startdate') != "" && request('enddate') != "") {
      $transection->whereBetween('transection_date', [request('startdate'), request('enddate')]);
    }

    $transection->orderBy('transection_date','DESC');
    return DataTables::of($transection)
    ->addColumn('formated_date', function ($transection) {
        $html = '';
        $html .= Admin::FormateDate($transection->transection_date);
        return $html;
    })
    ->addColumn('formated_type', function ($transection) {
        $html = '';
        $html .= config('transection.'.$transection->type)['type'];
        if($transection->type == "cheque5") {
            $html .= ' ('.$transection->remarks.')';
        }
        return $html;
    })
    ->addColumn('formated_amount', function ($transection) {
        $html = '';
        $html .= Admin::FormateTransection($transection->amount);
        return $html;
    })
    ->addColumn('formated_cheque_status', function ($transection) {
        $html = '';
        if($transection->cheque_status == 1) {
          $html .= '<span class="text-success">Close</span>';
        } else {
          $html .= '<span class="text-info">Open</span>';
        }
        return $html;
    })
    ->addColumn('formated_remarks', function ($transection) {
        $html = '';
        if($transection->type == "cheque1" || $transection->type == "cheque2") {
          $html .= self::getPaymentRemarks($transection->ref_tbl_no);
        } elseif($transection->type == "cheque5") {
            $html .= self::getExpensesRemarks($transection->ref_tbl_no);
        } elseif($transection->type == "cheque8") {
            $html .= self::getProcessRemarks($transection->udf4);
        } elseif($transection->type == "cheque9") {
            $html .= self::getInvoiceRemarks($transection->udf5);
        }else {
          $html .= $transection->remarks;
        }

        return $html;
    })
    ->addColumn('action', function ($transection) {
        $transection_type = config('transection.'.$transection->type);
        $html = '';

        //string check
        if($transection->amount >= 0) {
            $greet_txt = '<i class="fa fa-thumbs-up"></i>  Deposit';
        } else {
            $greet_txt = '<i class="fa fa-thumbs-down"></i> Withdraw';
        }

        if($transection->cheque_status == 0 && $transection_type['edit_at'] != "") {
        $html .= ' <a href="'.route($transection_type['edit_at'],$transection->id).'" class="btn btn-info btn-xs">'.$greet_txt.'</a>';
        if($transection->type == "cheque8") {
            $html .= ' <a href="' . route('edit.stock.process', $transection->udf4) . '" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
            $html .= ' <a href="' . route('delete.process.payment', $transection->ref_tbl_no) . '?redirect=cheque" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        } elseif($transection->type == "cheque9") {
            $html .= ' <a href="' . route('edit.invoice', $transection->udf5) . '" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
            $html .= ' <a href="' . route('delete.invoice.payment', $transection->ref_tbl_no) . '?redirect=cheque" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        } elseif($transection->type == "cheque5") {
            $html .= ' <a href="' . route('edit.expenses', $transection->ref_tbl_no) . '" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
            $html .= ' <a href="' . route('delete.expenses.payment', ["id"=>$transection->ref_tbl_no,"tid"=>$transection->tid]) . '?redirect=cheque" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        } else {
            $html .= ' <a href="' . route('edit.paymentin', $transection->ref_tbl_no) . '" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
            $html .= ' <a href="' . route('cheque.delete', $transection->id) . '" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        }
        } else {
        $html .= ' <a href="'.route($transection_type['deleted_at'],$transection->id).'" class="btn btn-info btn-xs" onClick="return chequeopenconfirm();"><i class="fa fa-refresh"></i> Reopen</a>';
        }
        return $html;
    })
    ->rawColumns(['formated_date','formated_type','formated_amount','formated_remarks','formated_cheque_status','action'])
    ->make(true);
  }

    private function getProcessRemarks($pid) {
        $stock = StockProcess::find($pid);
        if($stock) {
            $process = Process::find($stock->process_id);
            return $process->name.' (Process)';
        } else {
            return "Unknown";
        }
    }

    private function getInvoiceRemarks($pid) {
        $invoice = Invoice::find($pid);
        $ret_name = "";
        if($invoice->business_name != "") {
            $ret_name = $invoice->business_name;
        }
        return $ret_name;
    }

    private function getExpensesRemarks($pid) {
        $exp = Expenses::find($pid);
        $ret_name = "";
        if($exp->name != "") {
            $explode = explode('_',$exp->name);
            if(isset($explode[0]) && isset($explode[1])) {
                $tbl = config('master.' . $explode[0])['list'];
                $result = collect(\DB::select("SELECT * FROM `".$tbl."` where id = ".$explode[1]))->first();
                $ret_name = $result->name.' ('.ucwords($tbl).')';
            }
        }

        return $ret_name;
    }

  private function getPaymentRemarks($pid) {
    $pinfo = Payment::find($pid);
    if($pinfo->master_type == "master1") {
      $table = "party";
    } else if($pinfo->master_type == "master2") {
      $table = "agent";
    }  else if($pinfo->master_type == "master3") {
      $table = "staff";
    }  else if($pinfo->master_type == "master4") {
      $table = "engineer";
    }  else if($pinfo->master_type == "master5") {
      $table = "material";
    }  else if($pinfo->master_type == "master6") {
      $table = "process";
    }  else {
      $table = "transport";
    }

    $uinfo = DB::table($table)->select('name')->where('id',$pinfo->master_id)->first();
    return $uinfo->name.' ('.config('master.'.$pinfo->master_type)['name'].')';
  }

  public function adjustmentCheque(Request $request,$id) {
    $user = Sentinel::getUser();
    if(ChequeTransection::HaveRightBank($user->id,$id)) {
      $data['info'] = ChequeTransection::find($id);
      $data['bank_list'] = self::myBanks();
      return view('admin.v1.cheque.adjustment',$data);
    } else {
      return Admin::unauth();
    }
  }

  public function deleteCheque(Request $request,$id) {
    $user = Sentinel::getUser();
    if(ChequeTransection::HaveRightBank($user->id,$id)) {
      $info = ChequeTransection::find($id);
      $response = self::removeAllTrasection($info->tid,"true","all");

      if($response) {
        return redirect()->route('cheque')->with('success', "Transection Removed Successfully");
      } else {
        return redirect()->route('cheque')->with('error', "Ooops..! Something went wrong");
      }

    } else {
      return Admin::unauth();
    }
  }

  public function reopenCheque(Request $request,$id) {
    $user = Sentinel::getUser();
    if(ChequeTransection::HaveRightBank($user->id,$id)) {

      //remove all transection
      $info = ChequeTransection::find($id);
      self::removeAllTrasection($info->tid,"true");

      //update cheque
      $response = self::updateChequeDetails("open",$info->id);
      if($response) {
        return redirect()->route('cheque')->with('success', "Cheque Reopen Successfully");
      } else {
        return redirect()->route('cheque')->with('error', "Ooops..! Something went wrong");
      }

    } else {
      return Admin::unauth();
    }
  }

  private function removeAllTrasection($tid,$force = "false",$action = "no") {
    $user = Sentinel::getUser();
    $respose = BankTransection::where('tid',$tid);
    $respose2 = CashTransection::where('tid',$tid);
    if($action == "all") {
      $respose3 = ChequeTransection::where('tid',$tid);
      $respose4 = Payment::where('tid',$tid);
    }

    if($force == "true") {
      $respose->forceDelete();
      $respose2->forceDelete();

      if($action == "all") {
        $respose3->forceDelete();
        $respose4->forceDelete();
      }

    } else {
      $respose->delete();
      $respose2->delete();
      if($action == "all") {
        $respose3->delete();
        $respose4->delete();
      }
    }
    return true;
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
