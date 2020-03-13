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

class BankController extends Controller
{
  public function __construct() {

  }

  public function bank(Request $request)
  {
      $data['bank_list'] = self::myBanks();
      if(count($data['bank_list'])) {
        return view('admin.v1.bank.list',$data);
      } else {
        return redirect()->route('bank.new');
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

  public function newBank(Request $request) {
      $data['bank_list'] = Banks::orderBy('name','ASC')->get();
      return view('admin.v1.bank.new',$data);
  }

  public function registerBank(Request $request) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        'bank_name' => 'required|exists:banks,id|unique:banks_users,bank_id,NULL,id,user_id,'.$user->id,
        'bank_person_name' => 'required',
        'bank_account_no' => 'required|unique:banks_users,account_no,NULL,id,user_id,'.$user->id,
        'bank_ifsc' => 'required',
        'account_type' => 'required',
        'bank_branch' => 'required',
        'opening_balance' => 'required',
        'asof' => 'required'
    ],
    [
        'bank_name.unique'      => 'Bank account already registered',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $bank1_transection_id = Admin::uniqueTransectionId("bank1",$user->id);

    $request = BanksUser::insertGetId([
      'user_id' => $user->id,
      'bank_id' => request('bank_name'),
      'name' => request('bank_person_name'),
      'account_no' => request('bank_account_no'),
      'ifsc' => request('bank_ifsc'),
      'type' => request('account_type'),
      'branch' => request('bank_branch'),
      'opening_balance' => request('opening_balance'),
      'asof' => request('asof') != null ? date('Y-m-d',strtotime(request('asof'))) : null,
      'remarks' => request('remarks'),
      'tid' => $bank1_transection_id,
    ]);

    if($request) {

      //register Opening Balance
      $ins['user_id'] = $user->id;
      $ins['bank_user_id'] = $request;
      $ins['type'] = "bank1";
      $ins['amount'] = request('opening_balance');
      $ins['transection_date'] = request('asof') != null ? date('Y-m-d',strtotime(request('asof'))) : null;
      $ins['remarks'] = request('remarks');
      $ins['tid'] = $bank1_transection_id;
      BankTransection::insert($ins);

      return redirect()->route('bankaccount')->with('success', "Bank added Successfully");
    } else {
      return redirect()->route('bankaccount')->with('error', "Ooops..! Something went wrong");
    }
  }


  public function updateBank(Request $request,$id) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        'bank_name' => 'required|exists:banks,id|unique:banks_users,bank_id,'.$id.',id,user_id,'.$user->id,
        'bank_person_name' => 'required',
        'bank_account_no' => 'required|unique:banks_users,account_no,'.$id.',id,user_id,'.$user->id,
        'bank_ifsc' => 'required',
        'account_type' => 'required',
        'bank_branch' => 'required',
        'opening_balance' => 'required',
        'asof' => 'required'
    ],
    [
        'bank_name.unique'      => 'Bank account already registered',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $bankuser = BanksUser::find($id);
    $bankuser->bank_id = request('bank_name');
    $bankuser->name = request('bank_person_name');
    $bankuser->account_no = request('bank_account_no');
    $bankuser->ifsc = request('bank_ifsc');
    $bankuser->type = request('account_type');
    $bankuser->branch = request('bank_branch');
    $bankuser->opening_balance = request('opening_balance');
    $bankuser->asof = request('asof') != null ? date('Y-m-d',strtotime(request('asof'))) : null;
    $bankuser->remarks = request('remarks');
    $request = $bankuser->save();

    if($request) {

      //update transection
      $tid = $bankuser->tid;
      self::updateBankTransection($id,$tid,"",request('opening_balance'));

      return redirect()->route('bankaccount')->with('success', "Bank updated Successfully");
    } else {
      return redirect()->route('bankaccount')->with('error', "Ooops..! Something went wrong");
    }
  }

  private function updateBankTransection($id,$transection_id,$math,$amount) {
    $user = Sentinel::getUser();
    $update['amount'] = $math.$amount;
    $query = BankTransection::where('bank_user_id',$id)->where('tid',$transection_id)->update($update);
    return $query ? true : false;
  }

  public function getTransection(Request $request) {
    $user = Sentinel::getUser();
    $transection = BankTransection::query();
    $transection->where('user_id',$user->id)
    ->where('bank_user_id',request('bank_user_id'))
    ->where('status',1);

    if(request('filter_by') != "") {
      $transection->where('type',request('filter_by'));
    }

    if(request('startdate') != "" && request('enddate') != "") {
      $transection->whereBetween('transection_date', [request('startdate'), request('enddate')]);
    }

    $transection->orderBy('transection_date','DESC')->orderBy('id', 'DESC');;
    return DataTables::of($transection)
    ->addColumn('formated_date', function ($transection) {
        $html = '';
        $html .= Admin::FormateDate($transection->transection_date);
        return $html;
    })
    ->addColumn('formated_number', function ($transection) {
        $html = '';
        if($transection->type == "bank10") {
            $html .= self::getExpensesNumber($transection->ref_tbl_no);
        } elseif($transection->type == "bank9" || $transection->type == "bank16") {
            $html .= self::getChequeNumber($transection->ref_tbl_no);
        } elseif ($transection->type == "bank13") {
            $html .= self::getProcessNumber($transection->udf4);
        } elseif ($transection->type == "bank2" || $transection->type == "bank3") {
            $html .= self::getpaymentNumber($transection->ref_tbl_no);
        } elseif ($transection->type == "bank15") {
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
        if($transection->type == "bank2" || $transection->type == "bank3") {
          $html .= self::getPaymentRemarks($transection->ref_tbl_no);
        } elseif($transection->type == "bank9" || $transection->type == "bank16") {
            $html .= self::getChequeRemarks($transection->ref_tbl_no);
        } elseif ($transection->type == "bank1") {
          $html .= "opening balance";
        } elseif($transection->type == "bank10") {
            $html .= self::getExpensesRemarks($transection->ref_tbl_no);
        } elseif($transection->type == "bank13") {
            $html .= self::getProcessRemarks($transection->udf4);
        } elseif($transection->type == "bank15") {
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
            if($transection->type == "bank2" || $transection->type == "bank3" || $transection->type == "bank10"  || $transection->type == "bank11"  || $transection->type == "bank12") {
              $html .= ' <a href="'.route($transection_type['edit_at'],$transection->ref_tbl_no).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
            } elseif($transection->type == "bank13") {
              $html .= ' <a href="'.route($transection_type['edit_at'],$transection->udf4).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
            } elseif($transection->type == "bank15") {
              $html .= ' <a href="'.route($transection_type['edit_at'],$transection->udf5).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
            } else {
              $html .= ' <a href="'.route($transection_type['edit_at'],$transection->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
            }
        } else {
            //for cheque transection only
            if($transection->type == "bank9" || $transection->type == "bank16") {
                if($transection->type == "bank9") {
                    $opration = "Deposit";
                } else {
                    $opration = "Withdraw";
                }
                $html .= ' <a onclick="getchequeHandler(\'edit\',\''.$opration.'\')" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
            }
        }
        if($transection_type['deleted_at'] != "") {
            if($transection->type == "bank10") {
                $html .= ' <a href="'.route($transection_type['deleted_at'],["id"=>$transection->ref_tbl_no,"tid"=>$transection->tid]).'?redirect=bankaccount" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
            } elseif($transection->type == "bank15" || $transection->type == "bank13" || $transection->type == "bank11" || $transection->type == "bank12") {
                $html .= ' <a href="'.route($transection_type['deleted_at'],$transection->ref_tbl_no).'?redirect=bankaccount" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
            } else {
                $html .= ' <a href="'.route($transection_type['deleted_at'],$transection->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
            }
        } else {
            //for cheque transection only
            if($transection->type == "bank9" || $transection->type == "bank16") {
                if($transection->type == "bank9") {
                    $opration = "Deposit";
                } else {
                    $opration = "Withdraw";
                }
                $html .= ' <a onclick="getchequeHandler(\'delete\',\''.$opration.'\')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a>';
            }
        }
        return $html;
    })
    // ->filterColumn('filter_by', function($query, $keyword) {
    //    $query->where('type','=',$keyword);
    // })
    ->rawColumns(['formated_date','formated_type','formated_amount','formated_remarks','action'])
    ->make(true);
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
        return $exp->ref_no; // changes on 20-02-2020
    }

    private function getInvoiceRemarks($id) {
        $info = Invoice::find($id);
        return $info->business_name;
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
    }  else {
      $table = "transport";
    }

    $uinfo = DB::table($table)->select('name')->where('id',$pinfo->master_id)->first();
    return $uinfo->name.' ('.config('master.'.$pinfo->master_type)['name'].')';
  }

  public function adjustmentBank(Request $request) {
    $data['bank_list'] = self::myBanks();
    return view('admin.v1.bank.adjustment',$data);
  }

  public function editAdjustmentBank(Request $request,$id) {
    $user = Sentinel::getUser();
    if(BankTransection::HaveRightBank($user->id,$id)) {
      $info = $data['info'] = BankTransection::find($id);
      $data['bank_list'] = self::myBanks();

      //from and to account logic
      if($info->type == "bank8" && $info->bank_user_id == $info->to_bank)  {
        $banktransection = BankTransection::where('tid',$info->tid)->where('id','!=',$info->id)->first();
        $data['frombank'] = $banktransection->bank_user_id;
        $data['tobank'] = $banktransection->to_bank;
      } else {
        $data['frombank'] = $info->bank_user_id;
        $data['tobank'] = $info->to_bank;
      }

      return view('admin.v1.bank.edit_adjustment',$data);
    } else {
      return Admin::unauth();
    }
  }


  public function deleteAdjustmentBank(Request $request,$id) {
    $user = Sentinel::getUser();
    if(BankTransection::HaveRightBank($user->id,$id)) {
      $info = BankTransection::find($id);
      $response = self::removeAllTrasection($info->tid,"true");

      if($response) {
        return redirect()->route('bankaccount')->with('success', "Transection Removed Successfully");
      } else {
        return redirect()->route('bankaccount')->with('error', "Ooops..! Something went wrong");
      }

    } else {
      return Admin::unauth();
    }
  }

  public function adjustmentBankUpdate(Request $request,$id) {
    $user = Sentinel::getUser();
    $validate['bank_account'] = 'required|exists:banks_users,id,user_id,'.$user->id;
    $validate['entry_type'] = 'required';
    $validate['bank_amount'] = 'required';
    $validate['adjustment_date'] = 'required';

    if(request('entry_type') == "bank8") {
      $validate['transfer_to'] = 'required|exists:banks_users,id,user_id,'.$user->id;
    }

    $validator = Validator::make($request->all(),$validate);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $frombank = request('bank_account');
    $tobank = request('transfer_to');
    $amount = request('bank_amount');
    $remarks = request('remarks');
    $adjustmentDate = date('Y-m-d',strtotime(request('adjustment_date')));

    //get info of transection
    $info = BankTransection::find($id);
    $response2 = self::removeAllTrasection($info['tid'],"true");

    //create new entry
    if(request('entry_type') == "bank4") {
      //Withdraw Cash
      $response = self::WithdrawCash($frombank,$amount,$remarks,$adjustmentDate);
      $message = "Cash Withdraw Successfully";
    } else if(request('entry_type') == "bank5") {
      //Deposit Cash
      $response = self::DepositCash($frombank,$amount,$remarks,$adjustmentDate);
      $message = "Cash Deposit Successfully";
    } else if(request('entry_type') == "bank6") {
      //Increase Balance
      $response = self::IncreaseBalance($frombank,$amount,$remarks,$adjustmentDate);
      $message = "Balance Increased Successfully";
    } else if(request('entry_type') == "bank7") {
      //Reduce Balance
      $response = self::ReduceBalance($frombank,$amount,$remarks,$adjustmentDate);
      $message = "Balance Reduce Successfully";
    } else if(request('entry_type') == "bank8") {
      //Bank to Bank Transfer
      $response = self::BanktoBank($frombank,$tobank,$amount,$remarks,$adjustmentDate);
      $message = "Bank to Bank Transfer Successfully";
    } else {
      //nothing
      $response = true;
      $message = "No Action Performed";
    }

    if($response) {
      return redirect()->route('bankaccount')->with('success', $message);
    } else {
      return redirect()->route('bankaccount')->with('error', "Ooops..! Something went wrong");
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

  public function adjustmentBankRegister(Request $request) {
    $user = Sentinel::getUser();
    $validate['bank_account'] = 'required|exists:banks_users,id,user_id,'.$user->id;
    $validate['entry_type'] = 'required';
    $validate['bank_amount'] = 'required';
    $validate['adjustment_date'] = 'required';

    if(request('entry_type') == "bank8") {
      $validate['transfer_to'] = 'required|exists:banks_users,id,user_id,'.$user->id;
    }

    $validator = Validator::make($request->all(),$validate);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $frombank = request('bank_account');
    $tobank = request('transfer_to');
    $amount = request('bank_amount');
    $remarks = request('remarks');
    $adjustmentDate = date('Y-m-d',strtotime(request('adjustment_date')));

    if(request('entry_type') == "bank4") {
      //Withdraw Cash
      $response = self::WithdrawCash($frombank,$amount,$remarks,$adjustmentDate);
      $message = "Cash Withdraw Successfully";
    } else if(request('entry_type') == "bank5") {
      //Deposit Cash
      $response = self::DepositCash($frombank,$amount,$remarks,$adjustmentDate);
      $message = "Cash Deposit Successfully";
    } else if(request('entry_type') == "bank6") {
      //Increase Balance
      $response = self::IncreaseBalance($frombank,$amount,$remarks,$adjustmentDate);
      $message = "Balance Increased Successfully";
    } else if(request('entry_type') == "bank7") {
      //Reduce Balance
      $response = self::ReduceBalance($frombank,$amount,$remarks,$adjustmentDate);
      $message = "Balance Reduce Successfully";
    } else if(request('entry_type') == "bank8") {
      //Bank to Bank Transfer
      $response = self::BanktoBank($frombank,$tobank,$amount,$remarks,$adjustmentDate);
      $message = "Bank to Bank Transfer Successfully";
    } else if(request('entry_type') == "bank14") {
        //Other Income
        $response = self::OtherIncome($frombank,$amount,$remarks,$adjustmentDate);
        $message = "Other Income added Successfully";
    } else {
      //nothing
      $message = "No Action Performed";
    }

    if($response) {
      return redirect()->route('bankaccount')->with('success', $message);
    } else {
      return redirect()->route('bankaccount')->with('error', "Ooops..! Something went wrong");
    }

  }

  private function BanktoBank($frombank,$tobank,$amount,$remarks,$adjustmentDate) {
      $user = Sentinel::getUser();
      $tid = Admin::uniqueTransectionId("bank8",$user->id);
      //from bank -
      $bank['user_id'] = $user->id;
      $bank['bank_user_id'] = $frombank;
      $bank['type'] = "bank8";
      $bank['amount'] = '-'.$amount;
      $bank['remarks'] = $remarks;
      $bank['tid'] = $tid;
      $bank['to_bank'] = $tobank;
      $bank['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));
      $response = BankTransection::insert($bank);

      //to bank -
      $bank['user_id'] = $user->id;
      $bank['bank_user_id'] = $tobank;
      $bank['type'] = "bank8";
      $bank['amount'] = $amount;
      $bank['remarks'] = $remarks;
      $bank['tid'] = $tid;
      $bank['to_bank'] = $tobank;
      $bank['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));
      $response2 = BankTransection::insert($bank);

      return ($response && $response2) ? true : false;
  }

  private function ReduceBalance($frombank,$amount,$remarks,$adjustmentDate) {
      $user = Sentinel::getUser();
      $tid = Admin::uniqueTransectionId("bank7",$user->id);
      //bank -
      $bank['user_id'] = $user->id;
      $bank['bank_user_id'] = $frombank;
      $bank['type'] = "bank7";
      $bank['amount'] = '-'.$amount;
      $bank['remarks'] = $remarks;
      $bank['tid'] = $tid;
      $bank['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));;
      $response = BankTransection::insert($bank);
      return $response ? true : false;
  }

  private function OtherIncome($frombank,$amount,$remarks,$adjustmentDate) {
      $user = Sentinel::getUser();
      $tid = Admin::uniqueTransectionId("bank14",$user->id);
      //bank +
      $bank['user_id'] = $user->id;
      $bank['bank_user_id'] = $frombank;
      $bank['type'] = "bank14";
      $bank['amount'] = $amount;
      $bank['remarks'] = $remarks;
      $bank['tid'] = $tid;
      $bank['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));;
      $response = BankTransection::insert($bank);
      return $response ? true : false;
  }

  private function IncreaseBalance($frombank,$amount,$remarks,$adjustmentDate) {
      $user = Sentinel::getUser();
      $tid = Admin::uniqueTransectionId("bank6",$user->id);
      //bank +
      $bank['user_id'] = $user->id;
      $bank['bank_user_id'] = $frombank;
      $bank['type'] = "bank6";
      $bank['amount'] = $amount;
      $bank['remarks'] = $remarks;
      $bank['tid'] = $tid;
      $bank['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));;
      $response = BankTransection::insert($bank);
      return $response ? true : false;
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
      $cash['tid'] = $tid;
      $cash['ref_tbl_no'] = $response;
      $cash['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));;
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
      $cash['tid'] = $tid;
      $cash['ref_tbl_no'] = $response;
      $cash['transection_date'] = date('Y-m-d',strtotime($adjustmentDate));;
      $response2 = CashTransection::insert($cash);
      return ($response && $response2) ? true : false;
  }

  public function excludingBankList(Request $request) {
    $bank_list = self::myBanks();
    $parent = [];
    foreach($bank_list as $row) {
      if($row->id != request('id')) {
        array_push($parent,$row);
      }
    }
    return response()->json($parent);
  }

  public function editBank(Request $request,$id) {
    $user = Sentinel::getUser();
    if(BanksUser::HaveRightBank($user->id,$id)) {
      $data['bank_list'] = Banks::orderBy('name','ASC')->get();
      $data['info'] = BanksUser::find($id);
      return view('admin.v1.bank.edit',$data);
    } else {
      return Admin::unauth();
    }
  }

  public function deleteBank(Request $request,$id) {
    $user = Sentinel::getUser();
    if(BanksUser::HaveRightBank($user->id,$id)) {
      $bankuser = BanksUser::find($id);
      $response = $bankuser->delete();

      if($response) {
        return redirect()->route('bankaccount')->with('success', "Bank Removed Successfully");
      } else {
        return redirect()->route('bankaccount')->with('error', "Ooops..! Something went wrong");
      }

    } else {
      return Admin::unauth();
    }
  }

  public function mtypes(Request $request)
  {
      return view('admin.v1.bank.mtypes');
  }

    public function registerTypes(Request $request) {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|unique:banks,name',
            'fbinputtxt' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $request = Banks::insert(
            [
                'name' => request('type_name'),
                'icon' => request('fbinputtxt'),
            ]
        );

        if($request) {
            return redirect()->route('bank.unit')->with('success', "Bank registred");
        } else {
            return redirect()->route('bank.unit')->with('error', "Bank Name already exist");
        }
    }

    public function getMtype(Request $request)  {
        $mtype = Banks::select('*')->orderBy('id','DESC');
        return DataTables::of($mtype)
            ->addColumn('formated_image', function ($mtype) {
                $html = '';
                $html .= '<img src="'.asset($mtype->icon).'" alt="'.$mtype->name.'" class="img-responsive" style="width:40px;">';
                return $html;
            })
            ->addColumn('action', function ($mtype) {
                $activation_status = $mtype->status == 1 ? 'checked' : "";
                $html = '';
                $html .= ' <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#EditModal" onClick="getformelement('.$mtype->id.')" style="margin-bottom: 0px !important;"><i class="fa fa-pencil-square-o"></i> Edit</button>';
                $html .= ' <a href="'.route('bank.types.remove',$mtype->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
                return $html;
            })
            ->rawColumns(['formated_image','action'])
            ->make(true);
    }

    public function removeMtype(Request $request,$id)
    {
        $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:banks,id']);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $user =  Banks::find($id)->delete();
        if($user) {
            return redirect()->route('bank.unit')->with('success', "Bank deleted");
        } else {
            return redirect()->route('bank.unit')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function infoMtype(Request $request) {
        $manufacturer = Banks::find(request('id'));
        return response()->json($manufacturer);
    }

    public function updateMtype(Request $request) {
        $validator = Validator::make($request->all(), [
            'edit_type_name' => 'required|unique:banks,name,'.request('edit_unique_id'),
            'edit_fbinputtxt' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $request = Banks::where('id',request('edit_unique_id'))->update(
            [
                'name' => request('edit_type_name'),
                'icon' => request('edit_fbinputtxt')
            ]
        );

        if($request) {
            return redirect()->route('bank.unit')->with('success', "Bank updated");
        } else {
            return redirect()->route('bank.unit')->with('error', "Ooops..! Something went wrong");
        }
    }

}
