<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\Expenses;
use App\Model\Invoice;
use App\Model\InvoicePayment;
use App\Model\Party;
use App\Model\Process;
use App\Model\ProcessPayment;
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

class CashController extends Controller
{
    public function __construct() {

    }

    public function cashInHand(Request $request)
    {
      $user = Sentinel::getUser();
      $data['cashinhand'] = CashTransection::select(DB::raw('SUM(amount) as total_amount'))
      ->where('user_id',$user->id)
      ->where('status',1)
      ->first();
      return view('admin.v1.cash.list',$data);
    }

    public function getTransection(Request $request) {
      $user = Sentinel::getUser();
      $transection = CashTransection::query();
      $transection->where('user_id',$user->id)->where('status',1);

      if(request('filter_by') != "") {
        $transection->where('type',request('filter_by'));
      }

      if(request('startdate') != "" && request('enddate') != "") {
        $transection->whereBetween('transection_date', [request('startdate'), request('enddate')]);
      }

      if(request('formated_number')) {

      }

      $transection->orderBy('transection_date','DESC')->orderBy('id', 'DESC');
      return DataTables::of($transection)
      ->addColumn('formated_date', function ($transection) {
          $html = '';
          $html .= Admin::FormateDate($transection->transection_date);
          return $html;
      })
      ->addColumn('formated_number', function ($transection) {
          $html = '';
          if($transection->type == "cash8") {
              $html .= self::getExpensesNumber($transection->ref_tbl_no);
          } elseif($transection->type == "cash7" || $transection->type == "cash14") {
              $html .= self::getChequeNumber($transection->ref_tbl_no);
          } elseif ($transection->type == "cash11") {
              $html .= self::getProcessNumber($transection->udf4);
          } elseif ($transection->type == "cash5" || $transection->type == "cash6") {
              $html .= self::getpaymentNumber($transection->ref_tbl_no);
          } elseif ($transection->type == "cash13") {
              $html .= self::getInvoiceNumber($transection->udf5);
          }
          return $html;
      })
      ->addColumn('formated_type', function ($transection) {
          $html = '';
          $type = config('transection.'.$transection->type)['type'];
          $html .= $type;
          if($type == "Expenses") {
              $html .= ' ('.$transection->remarks.')';
          }
          return $html;
      })
      ->addColumn('formated_amount', function ($transection) {
          $html = '';
          $html .= Admin::FormateTransection($transection->amount);
          return $html;
      })
      ->addColumn('formated_remarks', function ($transection) {
          $html = '';
          if($transection->type == "cash5" || $transection->type == "cash6") {
            $html .= self::getPaymentRemarks($transection->ref_tbl_no);
          } elseif($transection->type == "cash7" || $transection->type == "cash14") {
             $html .= self::getChequeRemarks($transection->ref_tbl_no);
          } elseif($transection->type == "cash8") {
             $html .= self::getExpensesRemarks($transection->ref_tbl_no);
          } elseif($transection->type == "cash11") {
              $html .= self::getProcessRemarks($transection->udf4);
          } elseif($transection->type == "cash13") {
              $html .= self::getInvoiceRemarks($transection->udf5);
          } else {
            $html .= $transection->remarks;
          }

          return $html;
      })
      ->addColumn('action', function ($transection) {
          $transection_type = config('transection.'.$transection->type);
          $html = '';
          if($transection_type['edit_at'] != "") {
              if($transection->type == "cash10" || $transection->type == "cash9" || $transection->type == "cash8" || $transection->type == "cash5" || $transection->type == "cash6" || $transection->type == "cash1" || $transection->type == "cash2") {
                $html .= ' <a href="'.route($transection_type['edit_at'],$transection->ref_tbl_no).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
              } elseif ($transection->type == "cash11") {
                 $html .= ' <a href="'.route($transection_type['edit_at'],$transection->udf4).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
              } elseif ($transection->type == "cash13") {
                  $html .= ' <a href="'.route($transection_type['edit_at'],$transection->udf5).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
              } else {
                $html .= ' <a href="'.route($transection_type['edit_at'],$transection->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
              }
          } else {
              //for cheque transection only
              if($transection->type == "cash7" || $transection->type == "cash14") {
                  if($transection->type == "cash7") {
                    $opration = "Deposit";
                  } else {
                    $opration = "Withdraw";
                  }
                  $html .= ' <a onclick="getchequeHandler(\'edit\',\''.$opration.'\')" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
              }
          }
          if($transection_type['deleted_at'] != "") {
              if($transection->type == "cash8") {
                  $html .= ' <a href="'.route($transection_type['deleted_at'],["id"=>$transection->ref_tbl_no,"tid"=>$transection->tid]).'?redirect=cashinhand" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
              } elseif($transection->type == "cash13" || $transection->type == "cash11" || $transection->type == "cash10" || $transection->type == "cash9") {
                  $html .= ' <a href="'.route($transection_type['deleted_at'],$transection->ref_tbl_no).'?redirect=cashinhand" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
              } else {
                  $html .= ' <a href="'.route($transection_type['deleted_at'],$transection->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
              }
          } else {
              //for cheque transection only
              if($transection->type == "cash7" || $transection->type == "cash14") {
                  if($transection->type == "cash7") {
                      $opration = "Deposit";
                  } else {
                      $opration = "Withdraw";
                  }
                  $html .= ' <a onclick="getchequeHandler(\'delete\',\''.$opration.'\')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a>';
              }
          }
          return $html;
      })
      ->rawColumns(['formated_number','formated_date','formated_type','formated_amount','formated_remarks','action'])
      ->make(true);
    }

    private function getProcessRemarks($pid) {
        $stock = StockProcess::find($pid);
        if($stock) {
            $process = Process::find($stock->process_id);
            return $process->name;
        } else {
            return "Unknown";
        }
    }

    private function getInvoiceRemarks($id) {
        $info = Invoice::find($id);
        return $info->business_name;
    }

    private function getExpensesNumber($pid) {
        $exp = Expenses::find($pid);
        return $exp->bill_no;
    }

    private function getInvoiceNumber($id) {
        $info = Invoice::find($id);
        return $info->invoice_name;
    }

    private function getProcessNumber($id) {
        $info = StockProcess::find($id);
        return $info->pname;
    }

    private function getChequeNumber($id) {
        $info = ChequeTransection::find($id);
        if($info) {
            if($info->type == "cheque1" || $info->type == "cheque2") {
                return self::getpaymentNumber($info->ref_tbl_no);
            } elseif($info->type == "cheque8") {
                $name = "";
                $process = ProcessPayment::find($info->ref_tbl_no);
                if($process) {
                    $prinfo = StockProcess::find($process->process_id);
                    if($prinfo) {
                        $name = $prinfo->pname;
                    }
                }
                return $name;
            } elseif($info->type == "cheque5") {
                return self::getExpensesNumber($info->ref_tbl_no);
            } elseif($info->type == "cheque9") {
                $name = "";
                $invoice = InvoicePayment::find($info->ref_tbl_no);
                if($invoice) {
                    $iinfo = Invoice::find($invoice->invoice_id);
                    if($iinfo) {
                        $name = $iinfo->invoice_name;
                    }
                }
                return $name;
            } else {
                return "";
            }
        } else {
            return "";
        }
    }

    private function getpaymentNumber($pid) {
        $exp = Payment::find($pid);
        return $exp->ref_no; //changed at 20-02-2020
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

    private function getChequeRemarks($id) {
        $info = ChequeTransection::find($id);
        if($info) {
            if($info->type == "cheque1" || $info->type == "cheque2") {
                return self::getPaymentRemarks($info->ref_tbl_no);
            } elseif($info->type == "cheque8") {
                $process_payment = ProcessPayment::find($info->ref_tbl_no);
                if($process_payment) {
                    $stock_process = StockProcess::find($process_payment->process_id);
                    if($stock_process) {
                        $processor_info = Process::find($stock_process->process_id);
                        if($processor_info) {
                            return $processor_info->name.' (Process)';
                        } else {
                            return "";
                        }
                    } else {
                        return "";
                    }
                } else {
                    return "";
                }
            } elseif($info->type == "cheque5") {
                return self::getExpensesRemarks($info->ref_tbl_no);
            } elseif($info->type == "cheque9") {
                $invoice_payment = InvoicePayment::find($info->ref_tbl_no);
                if($invoice_payment) {
                    $invoice = Invoice::find($invoice_payment->invoice_id);
                    if($invoice) {
                        return $invoice->business_name;
                    } else {
                        return "";
                    }
                } else {
                    return "";
                }
            } else {
                return "";
            }
        } else {
            return "";
        }
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
      }  else if($pinfo->master_type == "master8") {
          $table = "karigar";
      }  else {
        $table = "transport";
      }

      $uinfo = DB::table($table)->select('name')->where('id',$pinfo->master_id)->first();
      return $uinfo->name.' ('.config('master.'.$pinfo->master_type)['name'].')';
    }

    public function deleteAdjustmentCash(Request $request,$id) {
      $user = Sentinel::getUser();
      if(CashTransection::HaveRightBank($user->id,$id)) {
        $info = CashTransection::find($id);
        $response = self::removeAllTrasection($info->tid,"true");

        if($response) {
          return redirect()->route('cashinhand')->with('success', "Transection Removed Successfully");
        } else {
          return redirect()->route('cashinhand')->with('error', "Ooops..! Something went wrong");
        }

      } else {
        return Admin::unauth();
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

    public function adjustmentCash(Request $request) {
      $data['bank_list'] = self::myBanks();
      return view('admin.v1.cash.adjustment',$data);
    }

    public function adjustmentCashEdit(Request $request,$id) {
      $user = Sentinel::getUser();
      if(CashTransection::HaveRightBank($user->id,$id)) {
        $data['bank_list'] = self::myBanks();
        $data['info'] = CashTransection::find($id);
        return view('admin.v1.cash.edit',$data);
      } else {
        return Admin::unauth();
      }
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

    public function adjustmentCashUpdate(Request $request,$id) {
      $user = Sentinel::getUser();
      $validate['entry_type'] = 'required';
      $validate['bank_amount'] = 'required';
      $validate['adjustment_date'] = 'required';

      $validator = Validator::make($request->all(),$validate);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('error', $errors->first());
      }

      $amount = request('bank_amount');
      $remarks = request('remarks');
      $adjustmentDate = date('Y-m-d',strtotime(request('adjustment_date')));

      //remove all data
      $info = CashTransection::find($id);
      self::removeAllTrasection($info->tid,"true");

      if(request('entry_type') == "cash3") {
        //Increase Cash
        $response = self::IncreaseCash($amount,$remarks,$adjustmentDate);
      } else if(request('entry_type') == "cash4") {
        //Reduce Cash
        $response = self::ReduceCash($amount,$remarks,$adjustmentDate);
      }  else if(request('entry_type') == "cash12") {
        //Other Income
        $response = self::OtherIncome($amount,$remarks,$adjustmentDate);
      }  else {
        //nothing
        $response = true;
      }

      if($response) {
        return redirect()->route('cashinhand')->with('success', "Transection Updated Successfully");
      } else {
        return redirect()->route('cashinhand')->with('error', "Ooops..! Something went wrong");
      }

    }

    public function adjustmentCashRegister(Request $request) {
      $user = Sentinel::getUser();
      $validate['entry_type'] = 'required';
      $validate['bank_amount'] = 'required';
      $validate['adjustment_date'] = 'required';

      if(request("entry_type") == "cash1" || request("entry_type") == "cash2") {
        $validate['bank_account'] = 'required|exists:banks_users,id,user_id,'.$user->id;
      }

      $validator = Validator::make($request->all(),$validate);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('error', $errors->first());
      }

      $amount = request('bank_amount');
      $remarks = request('remarks');
      $adjustmentDate = date('Y-m-d',strtotime(request('adjustment_date')));

      if(request('entry_type') == "cash1") {
        //Withdraw Cash
        $bank_account = request('bank_account');
        $response = self::WithdrawCash($bank_account,$amount,$remarks,$adjustmentDate);
        $message = "Cash Withdraw Successfully";
      } else if(request('entry_type') == "cash2") {
        //Deposit Cash
        $bank_account = request('bank_account');
        $response = self::DepositCash($bank_account,$amount,$remarks,$adjustmentDate);
        $message = "Cash Deposit Successfully";
      } else if(request('entry_type') == "cash3") {
        //Increase Cash
        $response = self::IncreaseCash($amount,$remarks,$adjustmentDate);
        $message = "Cash Increase Successfully";
      } else if(request('entry_type') == "cash4") {
        //Reduce Cash
        $response = self::ReduceCash($amount,$remarks,$adjustmentDate);
        $message = "Cash Reduce Successfully";
      }  else if(request('entry_type') == "cash12") {
        //Other Income
        $response = self::OtherIncome($amount,$remarks,$adjustmentDate);
        $message = "Other Income Register Successfully";
      }  else {
        //nothing
        $response = true;
        $message = "No Action Performed";
      }

      if($response) {
        return redirect()->route('cashinhand')->with('success', $message);
      } else {
        return redirect()->route('cashinhand')->with('error', "Ooops..! Something went wrong");
      }
    }

    private function DepositCash($frombank,$amount,$remarks,$adjustmentDate) {
        $user = Sentinel::getUser();
        $tid = Admin::uniqueTransectionId("bank5",$user->id);
        //bank +
        $bank['user_id'] = $user->id;
        $bank['bank_user_id'] = $frombank;
        $bank['type'] = "bank5";
        $bank['amount'] = $amount;
        $bank['remarks'] = $remarks;
        $bank['tid'] = $tid;
        $bank['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));;
        $response = BankTransection::insertGetId($bank);

        //cash -
        $cash['user_id'] = $user->id;
        $cash['type'] = "cash2";
        $cash['amount'] = '-'.$amount;
        $cash['remarks'] = $remarks;
        $cash['ref_tbl_no'] = $response;
        $cash['tid'] = $tid;
        $cash['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));
        $response2 = CashTransection::insert($cash);
        return ($response && $response2) ? true : false;
    }

    private function WithdrawCash($frombank,$amount,$remarks,$adjustmentDate) {
        $user = Sentinel::getUser();
        $tid = Admin::uniqueTransectionId("bank4",$user->id);
        //bank -
        $bank['user_id'] = $user->id;
        $bank['bank_user_id'] = $frombank;
        $bank['type'] = "bank4";
        $bank['amount'] = '-'.$amount;
        $bank['remarks'] = $remarks;
        $bank['tid'] = $tid;
        $bank['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));;
        $response = BankTransection::insertGetId($bank);

        //cash +
        $cash['user_id'] = $user->id;
        $cash['type'] = "cash1";
        $cash['amount'] = $amount;
        $cash['remarks'] = $remarks;
        $cash['ref_tbl_no'] = $response;
        $cash['tid'] = $tid;
        $cash['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));;
        $response2 = CashTransection::insert($cash);
        return ($response && $response2) ? true : false;
    }

    private function OtherIncome($amount,$remarks,$adjustmentDate) {
      //cash +
      $user = Sentinel::getUser();
      $tid = Admin::uniqueTransectionId("cash12",$user->id);

      $cash['user_id'] = $user->id;
      $cash['type'] = "cash12";
      $cash['amount'] = $amount;
      $cash['remarks'] = $remarks;
      $cash['tid'] = $tid;
      $cash['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));;
      $response = CashTransection::insert($cash);
      return $response ? true : false;
    }

    private function IncreaseCash($amount,$remarks,$adjustmentDate) {
      //cash +
      $user = Sentinel::getUser();
      $tid = Admin::uniqueTransectionId("cash3",$user->id);

      $cash['user_id'] = $user->id;
      $cash['type'] = "cash3";
      $cash['amount'] = $amount;
      $cash['remarks'] = $remarks;
      $cash['tid'] = $tid;
      $cash['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));;
      $response = CashTransection::insert($cash);
      return $response ? true : false;
    }

    private function ReduceCash($amount,$remarks,$adjustmentDate) {
      //cash -
      $user = Sentinel::getUser();
      $tid = Admin::uniqueTransectionId("cash4",$user->id);

      $cash['user_id'] = $user->id;
      $cash['type'] = "cash4";
      $cash['amount'] = '-'.$amount;
      $cash['remarks'] = $remarks;
      $cash['tid'] = $tid;
      $cash['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));;
      $response = CashTransection::insert($cash);
      return $response ? true : false;
    }
}
