<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\Agent;
use App\Model\BanksUser;
use App\Model\BankTransection;
use App\Model\CashTransection;
use App\Model\ChequeTransection;
use App\Model\Invoice;
use App\Model\InvoiceItem;
use App\Model\InvoicePayment;
use App\Model\Party;
use App\Model\State;
use App\Model\StockItem;
use App\Model\StockUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Admin;
use Sentinel;
use App\User;

class InvoiceController extends Controller
{
    public function __construct() {

    }

    public function deliverychallan(Request $request) {
        $user = Sentinel::getUser();
        $data['party_list'] = Party::where('user_id',$user->id)->where('status',1)->get();
        $data['stock_list'] = StockItem::where('pending','!=',0)->where('user_id',$user->id)->where('status',1)->orderBy('id','DESC')->get();
        return view('admin.v1.invoice.list',$data);
    }

    public function addNewChallan(Request $request) {
        $user = Sentinel::getUser();
        $data['bank_list'] = self::myBanks();
        $data['party_list'] = Party::where('user_id',$user->id)->where('status',1)->get();
        $data['agent_list'] = Agent::where('user_id',$user->id)->where('status',1)->get();
        $data['stock_list'] = StockItem::where('pending','!=',0)->where('user_id',$user->id)->where('status',1)->orderBy('id','DESC')->get();
        $data['category_list'] = StockUnit::where('status',1)->orderBy('name','ASC')->get();
        $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
        return view('admin.v1.invoice.new',$data);
    }

    public function editChallan(Request $request,$id) {
        $user = Sentinel::getUser();
        $data['bank_list'] = self::myBanks();
        $data['info'] = Invoice::find($id);
        $data['challan_items'] = InvoiceItem::where('user_id',$user->id)->where('delivery_id',$id)->where('status',1)->get();
        $data['invoice_payments'] = InvoicePayment::where('invoice_id',$id)->where('user_id',$user->id)->get();
        $data['party_list'] = Party::where('user_id',$user->id)->where('status',1)->get();
        $data['agent_list'] = Agent::where('user_id',$user->id)->where('status',1)->get();
        $data['stock_list'] = StockItem::where('pending','!=',0)->where('user_id',$user->id)->where('status',1)->orderBy('id','DESC')->get();
        $data['category_list'] = StockUnit::where('status',1)->orderBy('name','ASC')->get();
        $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
        return view('admin.v1.invoice.edit',$data);
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

    private function getInvoiceUniqueNumber() {
        $user = Sentinel::getUser();
        $max_id = Invoice::where('user_id',$user->id)->count();
        return $max_id + 1;
    }

    public function newChallanRegister(Request $request) {
        $user = Sentinel::getUser();
        //register new process
        $validator = Validator::make($request->all(), [
            'adjustment_date' => 'required',
            "process_name"    => "required",
            "business_name"    => "required",
            "process_state"    => "required",
//            "stock_no"    => "required|array|min:1",
//            "stock_no.*"  => "required|min:1",
            "quantity"    => "required|array|min:1",
            "quantity.*"  => "required|min:1",
            "mesurement"    => "required|array|min:1",
            "mesurement.*"  => "required|min:1",
            "unit"    => "required|array|min:1",
            "unit.*"  => "required|min:1",
            "total"    => "required|array|min:1",
            "total.*"  => "required|min:1",
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $ins['user_id'] = $user->id;
        $ins['invoice_name'] = self::getInvoiceUniqueNumber();
        $ins['date'] = date('Y-m-d',strtotime(request('adjustment_date')));
        $ins['invoice_type'] = request('confirmAns') != "" ? 1 : 2;
        $ins['due_date'] = request('due_date') ? date('Y-m-d',strtotime(request('due_date'))) : '0000-00-00';
        $ins['party_id'] = request('process_name');
        $ins['business_name'] = request('business_name');
        $ins['agent_id'] = request('agent_name');
        $ins['challan_no'] = request('challan_no');
        $ins['delivery_no'] = request('dc_no');
        $ins['remarks'] = request('remarks');
        $ins['state'] = request('process_state');
        $ins['transport'] = request('process_transport');
        $ins['sub_total'] = request('grand_total');
        $ins['discount_amount'] = request('loss_amount');
        $ins['tax_name'] = 'T.D.S';
        $ins['tax_percent'] = 0;
        $ins['tax_total'] = request('grand_tax');
        $ins['grand_total'] = request('main_total');
        $stock_process_ins_id = Invoice::insertGetId($ins);

        //insert item
        $stock_no = request('stock_no');
        $description = request('description');
        $design_name = request('design_name');
        $quantity = request('quantity') ? request('quantity') : [];
        $mesurement = request('mesurement');
        $length = request('length');
        $unit = request('unit');
        $hsn_number = request('hsn_no');
        $discount = request('discount');
        $gst = request('gst');
        $sub_total = request('total');
        foreach($quantity as $key=>$row) {
            if($quantity[$key] != ""  && $quantity[$key] != 0 ) {
                $child['user_id'] = $user->id;
                $child['delivery_id'] = $stock_process_ins_id;
                $child['stock_id'] = $stock_no[$key] != "" ? $stock_no[$key] : 0;
                $child['description'] = $description[$key];
                $child['design_name'] = $design_name[$key];
                $child['hsn_code'] = $hsn_number[$key];
                $child['quantity'] = $quantity[$key];
                $child['mesurement'] = $mesurement[$key];
                $child['item_length'] = $length[$key];
                $child['rate'] = $unit[$key];
                $child['discount'] = $discount[$key];
                $child['gst'] = $gst[$key];
                $child['total'] = $sub_total[$key];
                InvoiceItem::insert($child);
            }
        }


        //payment register
        $sp_date = request('sp_date');
        $sp_by = request('sp_by');
        $sp_ref_no = request('sp_ref_no');
        $sp_amount = request('sp_amount');
        $sp_remarks = request('sp_remarks');
        $total_payment = 0;
        foreach($sp_amount as $key=>$row) {
            if($sp_amount[$key] != "" && $sp_date[$key] != "" && $sp_by[$key] != "") {
                $tid = Admin::uniqueTransectionId("INVOICE",$user->id);
                $payment_type = $sp_by[$key];
                $total_payment += $sp_amount[$key];
                $new_date = date('Y-m-d h:i:s',strtotime($sp_date[$key]));
                $remarks = $sp_remarks[$key];

                //add in payment table
                $ptbl['user_id'] = $user->id;
                $ptbl['invoice_id'] = $stock_process_ins_id;
                $ptbl['date'] = $new_date;
                $ptbl['pay_by'] = $sp_by[$key];
                $ptbl['ref_no'] = $sp_ref_no[$key];
                $ptbl['amount'] = $sp_amount[$key];
                $ptbl['remarks'] = $sp_remarks[$key];
                $ptbl['tid'] = $tid;

                $response3 = InvoicePayment::insertGetId($ptbl);

                if (strpos($payment_type, 'bank_ref_') !== false) {
                    //bank transection
                    $bank_user_id = explode('_',$payment_type);
                    $bank_user_id = end($bank_user_id);
                    $response2 = self::bankTransection($bank_user_id,$sp_amount[$key],$remarks,$new_date,$response3,$tid,$stock_process_ins_id);
                } else {
                    if ($payment_type == "cheque") {
                        //cheque
                        $response2 = self::chequeTransection($sp_amount[$key],$remarks,$new_date,$response3,$sp_ref_no[$key],$tid,$stock_process_ins_id);
                    } else {
                        //cash
                        $response2 = self::cashTransection($sp_amount[$key],$remarks,$new_date,$response3,$tid,$stock_process_ins_id);
                    }
                }
            }
        }

        //update total payment
        $stock_process = Invoice::find($stock_process_ins_id);
        $stock_process->grand_payment = $total_payment;
        $stock_process->save();

        if($stock_process_ins_id) {
            return redirect()->route('invoice')->with('success', "Invoice register successfully");
        } else {
            return redirect()->route('invoice')->with('error', "Ooops..! Something went wrong");
        }

    }



    private function cashTransection($bank_amount,$remarks,$adjustmentDate,$paymentid,$tid,$udf5) {
        $transection_type = "cash13";
        $math_type = "+";
        //cash +
        $user = Sentinel::getUser();
        $cash['user_id'] = $user->id;
        $cash['type'] = $transection_type;
        $cash['amount'] = $math_type.$bank_amount;
        $cash['remarks'] = $remarks;
        $cash['transection_date'] = $adjustmentDate;
        $cash['ref_tbl_no'] = $paymentid;
        $cash['tid'] = $tid;
        $cash['udf5'] = $udf5;
        $response = CashTransection::insert($cash);
        return $response ? true : false;
    }

    private function bankTransection($bank_user_id,$bank_amount,$remarks,$adjustmentDate,$paymentid,$tid,$udf5) {
        $transection_type = "bank15";
        $math_type = "+";

        $user = Sentinel::getUser();
        $bank['user_id'] = $user->id;
        $bank['bank_user_id'] = $bank_user_id;
        $bank['type'] = $transection_type;
        $bank['amount'] = $math_type.$bank_amount;
        $bank['remarks'] = $remarks;
        $bank['transection_date'] = $adjustmentDate;
        $bank['ref_tbl_no'] = $paymentid;
        $bank['tid'] = $tid;
        $bank['udf5'] = $udf5;
        $response = BankTransection::insert($bank);
        return $response ? true : false;
    }

    private function chequeTransection($bank_amount,$remarks,$adjustmentDate,$paymentid,$refno,$tid,$udf5) {
        $transection_type = "cheque9";
        $math_type = "+";
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
        $cash['udf5'] = $udf5;
        $response = ChequeTransection::insert($cash);
        return $response ? true : false;
    }

    private function getchallanitemList($id) {
        $user = Sentinel::getUser();
        $result = InvoiceItem::select('*','stock_unit.name as mesurement_name')
            ->leftjoin('stock_unit','invoice_item.mesurement','=','stock_unit.id')
            ->where('invoice_item.delivery_id',$id)
            ->where('invoice_item.user_id',$user->id)
            ->where('invoice_item.status',1)
            ->groupBy('invoice_item.id')
            ->orderBy('invoice_item.id','ASC');
        return $result;
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

    public function viewAjaxChallan(Request $request) {
        $user = Sentinel::getUser();
        $process = Invoice::query();
        $process->select('invoice.*','party.name as party_name','party.photo as party_photo');
        $process->leftjoin('party','invoice.party_id','=','party.id');
        $process->leftjoin('agent','invoice.agent_id','=','agent.id');
        $process->leftjoin('invoice_item','invoice.id','=','invoice_item.delivery_id');
        $process->where('invoice.status',1);
        $process->where('invoice.user_id',$user->id);

        if(request('bill_no')) {
            $process->where('invoice.invoice_name','like','%'.request('bill_no').'%');
        }
        if(request('filter_by')) {
            $process->where('invoice.party_id',request('filter_by'));
        }
        if(request('stock_no')) {
            $process->where('invoice_item.stock_id',request('stock_no'));
        }
        if(request('design_name')) {
            $process->where('invoice_item.design_name','like','%'.request('design_name').'%');
        }

        if(request('startdate') && request('enddate')) {
            if(request('startdate') != "" && request('enddate') != "") {
                $process->whereBetween('invoice.date',[request('startdate'), request('enddate')]);
            }
        }

        $process->groupBy('invoice.id');
        $process->orderBy('invoice.date','DESC');
//        dd($process->toSql());
//        die();

        return DataTables::of($process)
            ->addColumn('formated_date', function ($process) {
                $html = '';
                $html .= Admin::FormateDate($process->date);
                $html .= '<br><span class="label bg-green">'.$process->invoice_name.'</span>';
                return $html;
            })
            ->addColumn('formate_stock', function ($process) {
                $html = '<div class="row">';
                $stockitems = self::getchallanitemList($process->id)->get();
                $toEnd = count($stockitems);
                foreach($stockitems as $key=>$row) {
                    $html .= '<div class="col-md-4 col-xs-4">';
                    if($row->stock_id) {
                        $html .= Admin::FormateStockItemID($row->stock_id);
                    } else {
                        $html .= '-';
                    }
                    $html .= '</div>';
                    $html .= '<div class="col-md-4 col-xs-4">';
                    $html .= $row->design_name != "" ? $row->design_name : "-";
                    $html .= '</div>';
                    $html .= '<div class="col-md-4 col-xs-4">';
                    $html .= $row->quantity." ".$row->mesurement_name;
                    $html .= '</div>';
                    if (0 !== --$toEnd) {
                        $html .= '<div class="col-md-12 col-xs-12"><div class="row-spliter"></div></div>';
                    }
                }
                $html .= "</div>";
                return $html;
            })
            ->addColumn('formated_process', function ($process) {
                $html = '';
                $html .= '<img src="'.asset($process->party_photo).'" alt="'.$process->party_name.'" class="img-circle img-responsive" style="display:inline-block;vertical-align:top;width:30px;"> <span style="display: inline-block;margin-left:5px;">'.$process->party_name.'<br><span class="text-muted">'.$process->business_name.'</span><br>Agent: Parag Kadiya</span>';
                return $html;
            })
            ->addColumn('formated_grand_total', function ($process) {
                $html = '';
                $html .= Admin::FormateTransection($process->grand_total);
                return $html;
            })
            ->addColumn('formated_payment_total', function ($process) {
                $html = '';
                $html .= Admin::FormateTransection('-'.$process->grand_payment);
                return $html;
            })
            ->addColumn('formated_balance_total', function ($process) {
                $html = '';
                $total = floatval($process->grand_total) - floatval($process->grand_payment);
                $html .= Admin::FormateTransection($total);
                return $html;
            })
            ->addColumn('action', function ($process) {
                $html = '';
                $html .= '<a href="'.route('edit.invoice',$process->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
                $html .= ' <a href="'.route('invoice.pdf',$process->id).'" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-file-pdf-o"></i> Preview</a>';
                $html .= '  <a href="'.route('delete.invoice',$process->id).'" onclick="return confirm(\'Are you sure want to delete this recrod?\')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a>';
                return $html;
            })
            ->rawColumns(['formated_date','formate_stock','formated_process','formated_grand_total','formated_payment_total','formated_balance_total','action'])
            ->make(true);
    }

    public function deleteInvoicePayment(Request $request,$id) {
        $user = Sentinel::getUser();
        $process_payment = InvoicePayment::where('user_id',$user->id)->where('id',$id)->first();

        //check cheque is deposite or not.
        if($process_payment->pay_by == "cheque") {
            //get check table record
            $check_status = ChequeTransection::where('user_id',$user->id)->where('tid',$process_payment->tid)->first();
            if($check_status) {
                //authenticate is not submitted.
                if($check_status->cheque_status == 1) {
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
        }

        $response = self::removeAllTrasection($process_payment->tid,true);

        //manage calculation
        $stock_process = Invoice::find($process_payment->invoice_id);
        $stock_process->grand_payment = $stock_process->grand_payment - $process_payment->amount;
        $stock_process->save();

        $process_payment->forceDelete();
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

    public function updateDeliveryChallan(Request $request,$id) {
        $user = Sentinel::getUser();
        //register new process
        $validator = Validator::make($request->all(), [
            'adjustment_date' => 'required',
            "process_name"    => "required",
            "business_name"    => "required",
            "process_state"    => "required",
//            "stock_no"    => "required|array|min:1",
//            "stock_no.*"  => "required|min:1",
            "quantity"    => "required|array|min:1",
            "quantity.*"  => "required|min:1",
            "mesurement"    => "required|array|min:1",
            "mesurement.*"  => "required|min:1",
            "unit"    => "required|array|min:1",
            "unit.*"  => "required|min:1",
            "total"    => "required|array|min:1",
            "total.*"  => "required|min:1",
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $ins['user_id'] = $user->id;
        $ins['date'] = date('Y-m-d',strtotime(request('adjustment_date')));
        $ins['invoice_type'] = request('confirmAns') != "" ? 1 : 2;
        $ins['due_date'] = request('due_date') ? date('Y-m-d',strtotime(request('due_date'))) : '0000-00-00';
        $ins['party_id'] = request('process_name');
        $ins['business_name'] = request('business_name');
        $ins['agent_id'] = request('agent_name');
        $ins['challan_no'] = request('challan_no');
        $ins['delivery_no'] = request('dc_no');
        $ins['remarks'] = request('remarks');
        $ins['state'] = request('process_state');
        $ins['transport'] = request('process_transport');
        $ins['sub_total'] = request('grand_total');
        $ins['discount_amount'] = request('loss_amount');
        $ins['tax_name'] = 'T.D.S';
        $ins['tax_percent'] = 0;
        $ins['tax_total'] = request('grand_tax');
        $ins['grand_total'] = request('main_total');
        $response = Invoice::where('user_id',$user->id)->where('id',$id)->update($ins);
        $stock_process_ins_id = $id;

        //remove all old items
        InvoiceItem::where('delivery_id',$id)->where('user_id',$user->id)->forceDelete();

        //insert item
        $stock_no = request('stock_no');
        $description = request('description');
        $design_name = request('design_name');
        $quantity = request('quantity') ? request('quantity') : [];
        $mesurement = request('mesurement');
        $length = request('length');
        $unit = request('unit');
        $hsn_number = request('hsn_no');
        $discount = request('discount');
        $gst = request('gst');
        $sub_total = request('total');
        foreach($quantity as $key=>$row) {
            if($quantity[$key] != ""  && $quantity[$key] != 0 ) {
                $child['user_id'] = $user->id;
                $child['delivery_id'] = $stock_process_ins_id;
                $child['stock_id'] = $stock_no[$key] != "" ? $stock_no[$key] : 0;
                $child['description'] = $description[$key];
                $child['design_name'] = $design_name[$key];
                $child['hsn_code'] = $hsn_number[$key];
                $child['quantity'] = $quantity[$key];
                $child['mesurement'] = $mesurement[$key];
                $child['item_length'] = $length[$key];
                $child['rate'] = $unit[$key];
                $child['discount'] = $discount[$key];
                $child['gst'] = $gst[$key];
                $child['total'] = $sub_total[$key];
                InvoiceItem::insert($child);
            }
        }

        //remove all old payment
        $process_payment = InvoicePayment::where('user_id',$user->id)->where('invoice_id',$id);
        $old_payment = $process_payment->get();
        foreach($old_payment as $row) {
            self::removeAllTrasection($row->tid,true);
        }
        $process_payment->forceDelete();


        //payment register
        $sp_date = request('sp_date');
        $sp_by = request('sp_by');
        $sp_ref_no = request('sp_ref_no');
        $sp_amount = request('sp_amount');
        $sp_remarks = request('sp_remarks');
        $total_payment = 0;
        foreach($sp_amount as $key=>$row) {
            if($sp_amount[$key] != "" && $sp_date[$key] != "" && $sp_by[$key] != "") {
                $tid = Admin::uniqueTransectionId("INVOICE",$user->id);
                $payment_type = $sp_by[$key];
                $total_payment += $sp_amount[$key];
                $new_date = date('Y-m-d h:i:s',strtotime($sp_date[$key]));
                $remarks = $sp_remarks[$key];

                //add in payment table
                $ptbl['user_id'] = $user->id;
                $ptbl['invoice_id'] = $stock_process_ins_id;
                $ptbl['date'] = $new_date;
                $ptbl['pay_by'] = $sp_by[$key];
                $ptbl['ref_no'] = $sp_ref_no[$key];
                $ptbl['amount'] = $sp_amount[$key];
                $ptbl['remarks'] = $sp_remarks[$key];
                $ptbl['tid'] = $tid;

                $response3 = InvoicePayment::insertGetId($ptbl);

                if (strpos($payment_type, 'bank_ref_') !== false) {
                    //bank transection
                    $bank_user_id = explode('_',$payment_type);
                    $bank_user_id = end($bank_user_id);
                    $response2 = self::bankTransection($bank_user_id,$sp_amount[$key],$remarks,$new_date,$response3,$tid,$stock_process_ins_id);
                } else {
                    if ($payment_type == "cheque") {
                        //cheque
                        $response2 = self::chequeTransection($sp_amount[$key],$remarks,$new_date,$response3,$sp_ref_no[$key],$tid,$stock_process_ins_id);
                    } else {
                        //cash
                        $response2 = self::cashTransection($sp_amount[$key],$remarks,$new_date,$response3,$tid,$stock_process_ins_id);
                    }
                }
            }
        }

        //update total payment
        $stock_process = Invoice::find($stock_process_ins_id);
        $stock_process->grand_payment = $total_payment;
        $stock_process->save();

        if($stock_process_ins_id) {
            return redirect()->route('invoice')->with('success', "Invoice updated successfully");
        } else {
            return redirect()->route('invoice')->with('error', "Ooops..! Something went wrong");
        }

    }

    public function deleteChallan(Request $request,$id) {
        $user = Sentinel::getUser();
        $process_payment = InvoicePayment::where('user_id',$user->id)->where('invoice_id',$id);
        $process_payment_list = $process_payment->get();
        foreach($process_payment_list as $row) {
            $response = self::removeAllTrasection($row->tid,false);
        }
        $process_payment->delete();

        //delete stock item
        InvoiceItem::where('delivery_id',$id)->where('user_id',$user->id)->delete();

        //delete self
        $response = Invoice::where('id',$id)->where('user_id',$user->id)->delete();

        if($response) {
            return redirect()->route('invoice')->with('success', "Invoice deleted Successfully");
        } else {
            return redirect()->route('invoice')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function downloadpdf(Request $request,$id) {
        $user = Sentinel::getUser();
        $data['user_state'] = $user->state != "" ? State::where('state_id',$user->state)->first() : [];
        $data['user_city'] = $user->city != "" ? DB::table('cities')->where('city_id',$user->city)->first() : [];
        $process = Invoice::query();
        $process->select('invoice.*','states.state as state_name','states.gst_code as state_gst_code','agent.name as agent_name','party.name as process_name','party.gstin_no as process_gst','party.state as process_state','party.city as process_city','party.address as process_address','party.photo as process_photo','party.business_name as process_business');
        $process->leftjoin('party','invoice.party_id','=','party.id');
        $process->leftjoin('agent','invoice.agent_id','=','agent.id');
        $process->leftjoin('states','invoice.state','=','states.state_id');
        $process->where('invoice.status',1);
        $process->where('invoice.id',$id);
        $data['info'] = $info = $process->first();
        $data['process_state'] = $info->process_state != "" ? State::where('state_id',$info->process_state)->first() : [];
        $data['process_city'] = $info->process_city != "" ? DB::table('cities')->where('city_id',$info->process_city)->first() : [];
        $data['item_list'] = self::getchallanitemList($id)->get();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin.v1.invoice.pdf', $data);
        return $pdf->stream('invoice-no-'.$id.'.pdf');
    }
}
