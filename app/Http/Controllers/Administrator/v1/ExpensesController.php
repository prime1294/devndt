<?php

namespace App\Http\Controllers\Administrator\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Admin;
use Sentinel;
use App\User;
use App\Model\Expenses;
use App\Model\ExpensesCategory;
use App\Model\StockUnit;
use App\Model\BanksUser;
use App\Model\BankTransection;
use App\Model\CashTransection;
use App\Model\ChequeTransection;

class ExpensesController extends Controller
{
  public function __construct() {

  }

  public function expenses(Request $request) {
    $user = Sentinel::getUser();
      $search['user_id'] = $user->id;
    $category_info = ExpensesCategory::where('user_id',$user->id)->where('status',1)->orderBy('id','desc')->first();
    $data['default_category'] = (!empty($category_info)) ? $category_info->id : 0;
      $data['userlist'] = DB::select(Admin::getUserJoinQuery($search));
    return view('admin.v1.expenses.list',$data);
  }

  private function getTotalofCategory($category_id) {
    $user = Sentinel::getUser();
    $query = Expenses::where('category',$category_id)->where('user_id',$user->id)->where('status',1)->get();
    return $query->sum('grand_total');
  }

  public function getCategoryInfo(Request $request) {
    $user = Sentinel::getUser();
    $category_info = ExpensesCategory::find(request('id'));
    if($category_info) {
      $data['status'] = "true";
      $data['message'] = "success";
      $data['result'] = $category_info;
    } else {
      $data['status'] = "false";
      $data['message'] = "Ooops..! Something went wrong";
    }

    return response()->json($data);
  }

  public function getExpensesAjax(Request $request) {
    $user = Sentinel::getUser();
    $query = Expenses::query();
    $query->where('user_id',$user->id);
    $query->where('status',1);
    if(request('id')) {
      $query->where('category',request('id'));
    }
      if(request('bill_no')) {
          $query->where('bill_no','like','%'.request('bill_no').'%');
      }
      if(request('filter_by')) {
          $query->where('name',request('filter_by'));
      }

      if(request('startdate') && request('enddate')) {
          if(request('startdate') != "" && request('enddate') != "") {
              $query->whereBetween('date',[request('startdate'), request('enddate')]);
          }
      }
    $query->orderBy('date','DESC');
    return DataTables::of($query)
    ->addColumn('formate_date', function ($query) {
        $html = '';
        $html .= Admin::FormateDate($query->date);
        return $html;
    })
    ->addColumn('formate_amount', function ($query) {
        $html = '';
        $total = $query->grand_total;
        $html .= Admin::FormateTransection($query->grand_total);
        return $html;
    })
    ->addColumn('formate_info', function ($query) {
        $html = '';
        $html .= $query->name;
        $html .= '<br><span class="text-muted">'.$query->bill_no.'</span>';
        return $html;
    })
    ->addColumn('formate_name', function ($query) {
        $html = '';
        if($query->name != "") {
            $explode = explode('_',$query->name);
            if(isset($explode[0]) && isset($explode[1])) {
                $tbl = config('master.' . $explode[0])['list'];
                $result = collect(\DB::select("SELECT * FROM `".$tbl."` where id = ".$explode[1]))->first();
                $html .= $result->name.' ('.ucwords($tbl).')';
            }
        }
        return $html;
    })
    ->addColumn('formate_type', function ($query) {
        $html = '';
        if($query->exp_type == "2") {
            $html .= 'Credit';
            $html .= '<br><span class="text-muted">'.Admin::FormateDate($query->due_date).'</span>';
        } else {
            $html .= 'Cash';
        }

        return $html;
    })
    ->addColumn('formate_paid', function ($query) {
        $html = '';
        $payment_data = unserialize($query->payment_history);
        $total_payment = 0;
        foreach($payment_data as $row) {
            $total_payment += $row['sp_amount'];
        }
        $html .= Admin::FormateTransection($total_payment);
        return $html;
    })
    ->addColumn('formate_balace', function ($query) {
        $html = '';
        $payment_data = unserialize($query->payment_history);
        $total_payment = 0;
        foreach($payment_data as $row) {
            $total_payment += $row['sp_amount'];
        }
        $paid_amount = $query->grand_total - $total_payment;
        $html .= Admin::FormateTransection($paid_amount);
        return $html;
    })
    ->addColumn('action', function ($query) {
        $html = '';
        $html .= '<a href="'.route('edit.expenses',$query->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        $html .= '  <a href="'.route('delete.expenses',$query->id).'" onclick="return confirm(\'Are you sure want to delete this recrod?\')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a>';
        return $html;
    })
    ->rawColumns(['formate_name','formate_date','formate_info','formate_type','formate_paid','formate_balace','formate_amount','action'])
    ->make(true);
  }

  public function getExpensesCategory(Request $request) {
    $user = Sentinel::getUser();
    $category = ExpensesCategory::where('user_id',$user->id)->where('status',1)->orderBy('id','DESC');
    return DataTables::of($category)
    ->addColumn('expence_amount', function ($category) {
        $html = '';
        $html .= Admin::FormateTransection(self::getTotalofCategory($category->id));
        return $html;
    })
    ->setRowClass('rendering_data')
    ->setRowAttr([
        'data-id' => function($category){
                return $category->id;
        },
        'data-amount' => function($category){
            return self::getTotalofCategory($category->id);
        }
    ])
    ->rawColumns(['expence_amount'])
    ->make(true);
  }

  public function expensesRegister(Request $request) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        'adjustment_date' => 'required',
        'exp_category' => 'required',
//        'payment_type' => 'required',
        'grand_total' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $payment_type = request('payment_type');
    $bank_amount = request('grand_total');
    $adjustmentDate = date('Y-m-d',strtotime(request('adjustment_date')));
    $dueDate = date('Y-m-d',strtotime(request('due_date')));
    $tid = Admin::uniqueTransectionId("EXPENSES",$user->id);
    $category_info = ExpensesCategory::find(request('exp_category'));

    $description = request('description');
    $quantity = request('quantity');
    $mesurement = request('mesurement');
    $unit = request('unit');
    $discount = request('discount');
    $discount_amount = request('discount_amount');
    $gst = request('gst');
    $gst_amount = request('gst_amount');
    $total = request('total');
    $history = [];
    $grand_total = 0;
    foreach($quantity as $key=>$row) {
      if($quantity[$key] != "" && $description[$key] != "" && $unit[$key] != "" && $total[$key] != "") {
        $items = array(
          "description" => $description[$key],
          "quantity" => $quantity[$key],
          "mesurement" => $mesurement[$key],
          "unit" => $unit[$key],
          "discount" => $discount[$key],
          "discount_amount" => $discount_amount[$key],
          "gst" => $gst[$key],
          "gst_amount" => $gst_amount[$key],
          "total" => $total[$key]
        );
        $grand_total += $total[$key];
        $history[] = $items;
      }
    }

    $ins['user_id'] = $user->id;
    $ins['category'] = request('exp_category');
    $ins['date'] = $adjustmentDate;
    $ins['name'] = request('exp_name');
    $ins['exp_type'] = request('confirmAns') != "" ? 1 : 2;
    $ins['due_date'] = $dueDate;
    $ins['history'] = serialize($history);
    $ins['bill_no'] = request('bill_no');
    $ins['bill_photo'] = request('upload_image_text_bill');
    $ins['remarks'] = request('remarks');
    $ins['grand_total'] = $grand_total;
    $response = Expenses::insertGetId($ins);

    $remarks = $category_info->name;

    $sp_date = request('sp_date');
    $sp_by = request('sp_by');
    $sp_ref_no = request('sp_ref_no');
    $sp_amount = request('sp_amount');
    $sp_remarks = request('sp_remarks');
    $response2 = true;
    $payment_history = [];
    $total_payment = 0;
    foreach($sp_amount as $key=>$row) {
        if($sp_amount[$key] != "" && $sp_date[$key] != "" && $sp_by[$key] != "") {
            $tid = Admin::uniqueTransectionId("EXPENSES",$user->id);
            $payment_type = $sp_by[$key];
            $total_payment += $sp_amount[$key];
            $new_date = date('Y-m-d h:i:s',strtotime($sp_date[$key]));
            if (strpos($payment_type, 'bank_ref_') !== false) {
                //bank transection
                   $bank_user_id = explode('_',$payment_type);
                   $bank_user_id = end($bank_user_id);
                   $response2 = self::bankTransection($bank_user_id,$sp_amount[$key],$remarks,$new_date,$response,$tid);
            } else {
                if ($payment_type == "cheque") {
                    //cheque
                    $response2 = self::chequeTransection($sp_amount[$key],$remarks,$new_date,$response,$sp_ref_no[$key],$tid);
                } else {
                    //cash
                    $response2 = self::cashTransection($sp_amount[$key],$remarks,$new_date,$response,$tid);
                }
            }

            //update in payment table
            $ptbl = [];
            $ptbl['sp_date'] = $new_date;
            $ptbl['sp_by'] = $payment_type;
            $ptbl['sp_ref_no'] = $sp_ref_no[$key];
            $ptbl['sp_amount'] = $sp_amount[$key];
            $ptbl['sp_remarks'] = $sp_remarks[$key];
            $ptbl['tid'] = $tid;
            $payment_history[] = $ptbl;
        }
    }

    //update payment history
    $exp = Expenses::find($response);
    $exp->payment_history = serialize($payment_history);
    $exp->grand_payment = $total_payment;
    $response3 = $exp->save();

     if($response && $response2 && $response3) {
      return redirect()->route('expenses')->with('success', "Expenses register Successfully");
    } else {
      return redirect()->route('expenses')->with('error', "Ooops..! Something went wrong");
    }

  }


    public function expensesUpdate(Request $request,$id) {
        $user = Sentinel::getUser();
        $validator = Validator::make($request->all(), [
            'adjustment_date' => 'required',
            'exp_category' => 'required',
            'grand_total' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $adjustmentDate = date('Y-m-d',strtotime(request('adjustment_date')));
        $dueDate = date('Y-m-d',strtotime(request('due_date')));
        $category_info = ExpensesCategory::find(request('exp_category'));

        $description = request('description');
        $quantity = request('quantity');
        $mesurement = request('mesurement');
        $unit = request('unit');
        $discount = request('discount');
        $discount_amount = request('discount_amount');
        $gst = request('gst');
        $gst_amount = request('gst_amount');
        $total = request('total');
        $history = [];
        $grand_total = 0;
        foreach($quantity as $key=>$row) {
            if($quantity[$key] != "" && $description[$key] != "" && $unit[$key] != "" && $total[$key] != "") {
                $items = array(
                    "description" => $description[$key],
                    "quantity" => $quantity[$key],
                    "mesurement" => $mesurement[$key],
                    "unit" => $unit[$key],
                    "discount" => $discount[$key],
                    "discount_amount" => $discount_amount[$key],
                    "gst" => $gst[$key],
                    "gst_amount" => $gst_amount[$key],
                    "total" => $total[$key]
                );
                $grand_total += $total[$key];
                $history[] = $items;
            }
        }

        $ins['user_id'] = $user->id;
        $ins['category'] = request('exp_category');
        $ins['date'] = $adjustmentDate;
        $ins['name'] = request('exp_name');
        $ins['exp_type'] = request('confirmAns') != "" ? 1 : 2;
        $ins['due_date'] = $dueDate;
        $ins['history'] = serialize($history);
        $ins['bill_no'] = request('bill_no');
        $ins['bill_photo'] = request('upload_image_text_bill');
        $ins['remarks'] = request('remarks');
        $ins['grand_total'] = $grand_total;
        $response = Expenses::where('id',$id)->update($ins);
        $response = $id; //patch

        $remarks = $category_info->name;


        //remove all old transection
        $einfo = Expenses::find($id);
        $etransection = unserialize($einfo->payment_history);
        foreach($etransection as $row) {
            self::removeAllTrasection($row['tid'],true);
        }


        $sp_date = request('sp_date');
        $sp_by = request('sp_by');
        $sp_ref_no = request('sp_ref_no');
        $sp_amount = request('sp_amount');
        $sp_remarks = request('sp_remarks');
        $response2 = true;
        $payment_history = [];
        $total_payment = 0;
        foreach($sp_amount as $key=>$row) {
            if($sp_amount[$key] != "" && $sp_date[$key] != "" && $sp_by[$key] != "") {
                $total_payment += $sp_amount[$key];
                $tid = Admin::uniqueTransectionId("EXPENSES",$user->id);
                $payment_type = $sp_by[$key];
                $new_date = date('Y-m-d h:i:s',strtotime($sp_date[$key]));
                if (strpos($payment_type, 'bank_ref_') !== false) {
                    //bank transection
                    $bank_user_id = explode('_',$payment_type);
                    $bank_user_id = end($bank_user_id);
                    $response2 = self::bankTransection($bank_user_id,$sp_amount[$key],$remarks,$new_date,$response,$tid);
                } else {
                    if ($payment_type == "cheque") {
                        //cheque
                        $response2 = self::chequeTransection($sp_amount[$key],$remarks,$new_date,$response,$sp_ref_no[$key],$tid);
                    } else {
                        //cash
                        $response2 = self::cashTransection($sp_amount[$key],$remarks,$new_date,$response,$tid);
                    }
                }

                //update in payment table
                $ptbl = [];
                $ptbl['sp_date'] = $new_date;
                $ptbl['sp_by'] = $payment_type;
                $ptbl['sp_ref_no'] = $sp_ref_no[$key];
                $ptbl['sp_amount'] = $sp_amount[$key];
                $ptbl['sp_remarks'] = $sp_remarks[$key];
                $ptbl['tid'] = $tid;
                $payment_history[] = $ptbl;
            }
        }

        //update payment history
        $einfo->payment_history = serialize($payment_history);
        $einfo->grand_payment = $total_payment;
        $response3 = $einfo->save();

        if($response && $response2 && $response3) {
//            return redirect()->route('expenses')->with('success', "Expenses updated Successfully");
            return Admin::checkRedirect($request,'expenses',"Expenses updated Successfully");
        } else {
            return redirect()->route('expenses')->with('error', "Ooops..! Something went wrong");
        }

    }

    private function removeAllTrasection($tid,$force = "false") {
        $user = Sentinel::getUser();
        $respose = BankTransection::where('tid',$tid);
        $respose2 = CashTransection::where('tid',$tid);
        $respose3 = ChequeTransection::where('tid',$tid);
        if($force == "true") {
            $respose->forceDelete();
            $respose2->forceDelete();
            $respose3->forceDelete();
        } else {
            $respose->delete();
            $respose2->delete();
            $respose3->delete();
        }
        return true;
    }

    public function deleteExpensesPayment(Request $request,$id,$tid) {
        $user = Sentinel::getUser();

        //get check table record
        $check_status = ChequeTransection::where('user_id',$user->id)->where('tid',$tid);
        if($check_status->count()) {
            //authenticate is not submitted.
            $check_info = $check_status->first();
            if($check_info->cheque_status == 1) {
                if(request('redirect')) {
                    return redirect()->route(request('redirect'))->with('error', "You can not delete this transaction. you have to reopen cheque first.");
                } else {
                    $data['status'] = "false";
                    $data['message'] = "You can not delete this transaction. you have to reopen cheque first.";
                    return response()->json($data);
                }
                die();
            }
        }


        $response = self::removeAllTrasection($tid,true);
        $einfo = Expenses::find($id);
        $etransection = unserialize($einfo->payment_history);
        $total_amount = 0;
        foreach($etransection as $key=>$row) {
            if($row['tid'] == $tid) {
                unset($etransection[$key]);
            } else {
                $total_amount += $row['sp_amount'];
            }
        }
        $einfo->payment_history = serialize($etransection);
        $einfo->grand_payment = $total_amount;
        $einfo->save();
        if(request('redirect')) {
            if($response) {
                return redirect()->route(request('redirect'))->with('success', "Transection deleted Successfully");
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

    public function deleteExpenses(Request $request, $id) {
        $einfo = Expenses::find($id);
        $etransection = unserialize($einfo->payment_history);
        foreach($etransection as $row) {
            self::removeAllTrasection($row['tid'],false);
        }

        $response = Expenses::where('id',$id)->delete();

        if($response) {
            //return redirect()->route('expenses')->with('success', "Expenses deleted Successfully");
            return Admin::checkRedirect($request,'expenses',"Expenses deleted Successfully");
        } else {
            return redirect()->route('expenses')->with('error', "Ooops..! Something went wrong");
        }
    }


  private function chequeTransection($bank_amount,$remarks,$adjustmentDate,$paymentid,$refno,$tid) {
    $transection_type = "cheque5";
    $math_type = "-";
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
    $response = ChequeTransection::insert($cash);
    return $response ? true : false;
  }

  private function cashTransection($bank_amount,$remarks,$adjustmentDate,$paymentid,$tid) {
    $transection_type = "cash8";
    $math_type = "-";
    //cash +
    $user = Sentinel::getUser();
    $cash['user_id'] = $user->id;
    $cash['type'] = $transection_type;
    $cash['amount'] = $math_type.$bank_amount;
    $cash['remarks'] = $remarks;
    $cash['transection_date'] = $adjustmentDate;
    $cash['ref_tbl_no'] = $paymentid;
    $cash['tid'] = $tid;
    $response = CashTransection::insert($cash);
    return $response ? true : false;
  }

  private function bankTransection($bank_user_id,$bank_amount,$remarks,$adjustmentDate,$paymentid,$tid) {
    $transection_type = "bank10";
    $math_type = "-";

    $user = Sentinel::getUser();
    $bank['user_id'] = $user->id;
    $bank['bank_user_id'] = $bank_user_id;
    $bank['type'] = $transection_type;
    $bank['amount'] = $math_type.$bank_amount;
    $bank['remarks'] = $remarks;
    $bank['transection_date'] = $adjustmentDate;
    $bank['ref_tbl_no'] = $paymentid;
    $bank['tid'] = $tid;
    $response = BankTransection::insert($bank);
    return $response ? true : false;
  }

  public function addExpensesCategory(Request $request) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return response()->json(["status"=>"false","message"=>$errors->first()]);
    }

    $ins['user_id'] = $user->id;
    $ins['name'] = request('name');
    $response = ExpensesCategory::insert($ins);

    if($response) {
      $data['status'] = "true";
      $data['message'] = "success";
    } else {
      $data['status'] = "false";
      $data['message'] = "Ooops..! Something went wrong";
    }
    return response()->json($data);
  }


  public function updateExpensesCategory(Request $request) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        'id' => 'required',
        'name' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return response()->json(["status"=>"false","message"=>$errors->first()]);
    }

    $ins['name'] = request('name');
    $response = ExpensesCategory::where('id',request('id'))->where('user_id',$user->id)->update($ins);

    if($response) {
      $data['status'] = "true";
      $data['message'] = "success";
    } else {
      $data['status'] = "false";
      $data['message'] = "Ooops..! Something went wrong";
    }
    return response()->json($data);
  }

  public function deleteExpensesCategory(Request $request) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        'id' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return response()->json(["status"=>"false","message"=>$errors->first()]);
    }

    //get all expences of same category
    $exp = Expenses::where('user_id',$user->id)->where('category',request('id'))->get();
    foreach($exp as $row) {
        //get all payment of same category
        $etransection = unserialize($row->payment_history);
        foreach($etransection as $row) {
            self::removeAllTrasection($row['tid'],true);
        }

        //delete exp
        Expenses::where('user_id',$user->id)->where('category',request('id'))->forceDelete();
    }

    $response = ExpensesCategory::where('id',request('id'))->where('user_id',$user->id)->delete();

    if($response) {
      $data['status'] = "true";
      $data['message'] = "success";
    } else {
      $data['status'] = "false";
      $data['message'] = "Ooops..! Something went wrong";
    }
    return response()->json($data);
  }

  public function addExpenses(Request $request) {
    $user = Sentinel::getUser();
      $search['user_id'] = $user->id;
    $data['category_list'] = StockUnit::where('status',0)->where('cond1',1)->orderBy('id','ASC')->get();
    $data['exp_category_list'] = ExpensesCategory::where('status',1)->orderBy('name','ASC')->get();
    $data['bank_list'] = self::myBanks();
      $data['userlist'] = DB::select(Admin::getUserJoinQuery($search));
    return view('admin.v1.expenses.new',$data);
  }


    public function editExpenses(Request $request,$id) {
        $user = Sentinel::getUser();
        $search['user_id'] = $user->id;
        $data['category_list'] = StockUnit::where('status',0)->where('cond1',1)->orderBy('id','ASC')->get();
        $data['exp_category_list'] = ExpensesCategory::where('status',1)->orderBy('name','ASC')->get();
        $data['bank_list'] = self::myBanks();
        $data['userlist'] = DB::select(Admin::getUserJoinQuery($search));
        $data['info'] = Expenses::find($id);
        return view('admin.v1.expenses.edit',$data);
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

    public function mtypes(Request $request)
    {
        return view('admin.v1.expenses.mtypes');
    }

    public function registerTypes(Request $request) {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|unique:stock_unit,name',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $request = StockUnit::insert(
            [
                'name' => request('type_name'),
                'status' => 0,
                'cond1' => 1,
            ]
        );

        if($request) {
            return redirect()->route('expenses.unit')->with('success', "Expenses unit registred");
        } else {
            return redirect()->route('expenses.unit')->with('error', "Expenses unit already exist");
        }
    }

    public function getMtype(Request $request)  {
        $mtype = StockUnit::select('*')->where('status',0)->where('cond1',1)->orderBy('id','DESC');
        return DataTables::of($mtype)
            ->addColumn('action', function ($mtype) {
                $activation_status = $mtype->status == 1 ? 'checked' : "";
                $html = '';
                $html .= ' <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#EditModal" onClick="getformelement('.$mtype->id.')" style="margin-bottom: 0px !important;"><i class="fa fa-pencil-square-o"></i> Edit</button>';
                $html .= ' <a href="'.route('expenses.types.remove',$mtype->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
                return $html;
            })->make(true);
    }

    public function removeMtype(Request $request,$id)
    {
        $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:stock_unit,id']);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $user =  StockUnit::find($id)->delete();
        if($user) {
            return redirect()->route('expenses.unit')->with('success', "Expenses unit deleted");
        } else {
            return redirect()->route('expenses.unit')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function infoMtype(Request $request) {
        $manufacturer = StockUnit::find(request('id'));
        return response()->json($manufacturer);
    }

    public function updateMtype(Request $request) {
        $validator = Validator::make($request->all(), [
            'edit_type_name' => 'required|unique:stock_unit,name,'.request('edit_unique_id'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $request = StockUnit::where('id',request('edit_unique_id'))->update(
            ['name' => request('edit_type_name')]
        );

        if($request) {
            return redirect()->route('expenses.unit')->with('success', "Expenses unit updated");
        } else {
            return redirect()->route('expenses.unit')->with('error', "Ooops..! Something went wrong");
        }
    }

}
