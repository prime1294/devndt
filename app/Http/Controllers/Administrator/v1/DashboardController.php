<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\BanksUser;
use App\Model\BankTransection;
use App\Model\CashTransection;
use App\Model\ChequeTransection;
use App\Model\Company;
use App\Model\Ecertificate;
use App\Model\Enrollment;
use App\Model\Expenses;
use App\Model\Invoice;
use App\Model\InvoicePayment;
use App\Model\KarigarPayment;
use App\Model\KarigarReport;
use App\Model\Process;
use App\Model\ReadyStock;
use App\Model\StockProcess;
use App\Model\Vision;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Admin;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Sentinel;
use App\User;
use DataTables;

class DashboardController extends Controller
{

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Sentinel::getUser();
        $data['ctn_company'] = Company::where('status',1)->count();
        $data['ctn_enrollment'] = Enrollment::count();
        $data['ctn_certificate'] = Ecertificate::count();
        $data['ctn_vision'] = Vision::where('status',1)->count();
        return view('admin.v1.dashboard.dashboard',$data);
    }

    public function outstanding(Request $request) {
        $user = Sentinel::getUser();
        $data = [];
        return view('admin.v1.dashboard.outstanding',$data);
    }

    public function profitloss(Request $request) {
        $user = Sentinel::getUser();
        $data = [];
        return view('admin.v1.dashboard.profitloss',$data);
    }

    private function getUsersTransection($type = "receivable") {
        $user = Sentinel::getUser();
        $parent = [];
        $tbl = ['master1'=>'party','master2'=>'agent','master3'=>'staff','master8'=>'karigar','master5'=>'material','master6'=>'process'];
        foreach($tbl as $key=>$t) {
            $result = DB::select("SELECT * FROM `".$t."` where status = 1 AND deleted_at IS NULL");
            foreach($result as $row) {
                $ret_name = $row->name.' ('.ucwords($t).')';

                $search['table'] = $t;
                $search['user_id'] = $user->id;
                $search['id'] = $row->id;
                $search['master_type'] = $key;

                $amount = DB::select(Admin::masterTransectionQuery($search));
                $amount_sum = collect($amount)->sum('transection_amount');
                if ($amount_sum < 0 && $type == "payable")
                {
                    $child['id'] = $key.'_'.$row->id;
                    $child['name'] = $ret_name;
                    $child['image'] = $row->photo;
                    $child['amount'] = $amount_sum;
                    array_push($parent,$child);
                }

                if ($amount_sum > 0 && $type == "receivable")
                {
                    $child['id'] = $key.'_'.$row->id;
                    $child['name'] = $ret_name;
                    $child['image'] = $row->photo;
                    $child['amount'] = $amount_sum;
                    array_push($parent,$child);
                }
            }
        }
        return $parent;
    }

    public function outstandingAjax(Request $request) {
        $user = Sentinel::getUser();
        $list = self::getUsersTransection(request('getdata'));
        return DataTables::of($list)
        ->addColumn('name', function ($list) {
            $html = '';
            $html .= '<img src="'.asset($list['image']).'" alt="'.$list['name'].'" class="img-responsive img-circle" style="display:inline-block;vertical-align:top; width: 40px;"> <span style="display: inline-block;margin-left:5px;">'.$list['name'].'</span>';
            return $html;
        })
        ->addColumn('amount', function ($list) {
            $html = '';
            $html .= Admin::FormateTransection(round($list['amount']));
            return $html;
        })
        ->with('sum_balance', Admin::FormateTransection(array_sum(array_column($list,'amount'))))
        ->rawColumns(['name','amount'])
        ->make(true);
    }

    public function dayBook(Request $request) {
        $user = Sentinel::getUser();
        $data = [];
        return view('admin.v1.dashboard.daybook',$data);
    }

    private function getFormatedName($type,$pid) {
        if($type == "master1") {
            $table = "party";
        } else if($type == "master2") {
            $table = "agent";
        }  else if($type == "master3") {
            $table = "staff";
        }  else if($type == "master4") {
            $table = "engineer";
        }  else if($type == "master5") {
            $table = "material";
        }  else if($type == "master6") {
            $table = "process";
        }  else if($type == "master8") {
            $table = "karigar";
        }  else {
            $table = "transport";
        }

        $uinfo = DB::table($table)->select('name')->where('id',$pid)->first();
        return $uinfo->name.' ('.config('master.'.$type)['name'].')';
    }

    public function dayBookAjax(Request $request) {
        $user = Sentinel::getUser();
        $search['user_id'] = $user->id;
        $date = request('filter_date') ? date('Y-m-d',strtotime(request('filter_date'))) : date('Y-m-d',strtotime("now"));
        $list = DB::select(Admin::dayBookQuery($search,$date));
        $collect = collect($list);
        $money_in = $collect->sum('money_in');
        $money_out = $collect->sum('money_out');
        return DataTables::of($list)
        ->addColumn('formated_name', function ($list) {
            $html = '';
            if($list->master_type != "" && $list->master_id != 0) {
                $html .= self::getFormatedName($list->master_type,$list->master_id);
            } elseif($list->master_type != "" && $list->master_id == 0) {
                $html .= $list->master_type;
            }

            return $html;
        })
        ->addColumn('formated_number', function ($list) {
            $html = '';
            if($list->transection_id != "") {
                $html .= $list->transection_id;
            }
            return $html;
        })
        ->addColumn('formated_type', function ($list) {
            $html = '';
            $html .= config('daybook.'.$list->transection_type)['name'];
            return $html;
        })
        ->addColumn('formated_total', function ($list) {
            $html = '';
            if($list->transection_amount != 0) {
                $html .= Admin::FormateTransection($list->transection_amount,false);
            }
            return $html;
        })
        ->addColumn('formated_in', function ($list) {
            $html = '';
            if($list->money_in != 0) {
                $html .= Admin::FormateTransection($list->money_in,false);
            }
            return $html;
        })
        ->addColumn('formated_out', function ($list) {
            $html = '';
            if($list->money_out != 0) {
                $html .= Admin::FormateTransection($list->money_out,false);
            }
            return $html;
        })
        ->with('money_in', abs($money_in) )
        ->with('money_out', abs($money_out))
        ->with('money_balance', Admin::FormateTransection(abs($money_in) - abs($money_out)))
        ->rawColumns(['formated_name','formated_number','formated_type','formated_total','formated_in','formated_out'])
        ->make(true);
    }

    public function getProfitLossReport(Request $request) {
        $user = Sentinel::getUser();
        $invoice = Invoice::where('user_id',$user->id)->where('status',1);
        $process = StockProcess::where('user_id',$user->id)->where('status',1);
        $ready_stock = ReadyStock::where('user_id',$user->id)->where('status',1);
        $cash_income = CashTransection::where('user_id',$user->id)->where('status',1)->where('type','cash12');
        $bank_income = BankTransection::where('user_id',$user->id)->where('status',1)->where('type','bank14');
        $karigar_salary = KarigarReport::where('user_id',$user->id)->where('status',1);
        $karigar_loss = KarigarPayment::where('user_id',$user->id)->where('status',1)->where('pay_type',3);
        $expenses = Expenses::where('user_id',$user->id)->where('status',1);

        if(request('startdate') && request('enddate')) {
            if(request('startdate') != "" && request('enddate') != "") {
                $invoice->whereBetween('date',[request('startdate'), request('enddate')]);
                $process->whereBetween('date',[request('startdate'), request('enddate')]);
                $ready_stock->whereBetween('date',[request('startdate'), request('enddate')]);
                $cash_income->whereBetween('transection_date',[request('startdate'), request('enddate')]);
                $bank_income->whereBetween('transection_date',[request('startdate'), request('enddate')]);
                $karigar_salary->whereBetween('date',[request('startdate'), request('enddate')]);
                $karigar_loss->whereBetween('date',[request('startdate'), request('enddate')]);
                $expenses->whereBetween('date',[request('startdate'), request('enddate')]);
            }
        }

        $bank_income = $bank_income->sum('amount');
        $cash_income = $cash_income->sum('amount');
        $other_income = $cash_income + $bank_income;

        $list = [
           [
               "title" => "Total Job Work",
               "amount" => $invoice->sum('sub_total'),
               "class" => "text-success",
               "sign" => "+"
           ],
            [
               "title" => "Job Loss",
               "amount" => $invoice->sum('discount_amount'),
                "class" => "text-danger",
                "sign" => "-"
           ],
            [
               "title" => "Total Process",
               "amount" => $process->sum('grand_total'),
                "class" => "text-danger",
                "sign" => "-"
           ],
            [
               "title" => "Process Loss",
               "amount" => $process->sum('less_amount'),
                "class" => "text-success",
                "sign" => "+"
           ],
            [
               "title" => "Ready Stock",
               "amount" => $ready_stock->sum('amount'),
                "class" => "text-success",
                "sign" => "+"
           ],
            [
               "title" => "Other Income",
               "amount" => $other_income,
                "class" => "text-success",
                "sign" => "+"
           ],
            [
               "title" => "Total Salary",
               "amount" => $karigar_salary->sum(DB::raw('salary + bonus')),
                "class" => "text-danger",
                "sign" => "-"
           ],
            [
               "title" => "Loss by Karigar",
               "amount" => $karigar_loss->sum('amount'),
                "class" => "text-success",
                "sign" => "+"
           ],
            [
               "title" => "Total Expenses",
               "amount" => $expenses->sum('grand_total'),
                "class" => "text-danger",
                "sign" => "-"
           ],
        ];

        $final_amount = 0;
        foreach($list as $row) {
            if($row['sign'] == "-") {
                $final_amount -= $row['amount'];
            } else {
                $final_amount += $row['amount'];
            }
        }
        return DataTables::of($list)
        ->addColumn('perticulers', function ($list) {
            $html = '<span class="'.$list['class'].' text-bold">'.$list['title'].' ('.$list['sign'].')</span>';
            return $html;
        })
        ->addColumn('formated_amount', function ($list) {
            $html = '<span class="'.$list['class'].' text-bold"><i class="fa fa-inr"></i> '.number_format($list['amount'],2).'</span>';
            return $html;
        })
        ->rawColumns(['perticulers','formated_amount'])
        ->with('total_amount', Admin::is_positive_integer($final_amount) ? "<span class='text-bold text-success'><i class='fa fa-inr'></i> ".number_format(abs($final_amount),2)."</span>" : "<span class='text-bold text-danger'><i class='fa fa-inr'></i> ".number_format(abs($final_amount),2)."</span>")
        ->with('total_title',  Admin::is_positive_integer($final_amount) ? "<span class='text-bold text-success'>Net Profit</span>" : "<span class='text-bold text-danger'>Net Loss</span>")
        ->with('total_tds',  Admin::FormateTransection($invoice->sum('tax_total')))
        ->make(true);
    }
}
