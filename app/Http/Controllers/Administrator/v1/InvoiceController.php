<?php

namespace App\Http\Controllers\Administrator\v1;

use Admin;
use App\Model\Company;
use App\Model\Cource;
use App\Model\Invoice;
use App\Model\Enrollment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sentinel;
use DataTables;

class InvoiceController extends Controller
{
    public function __construct() {

    }

    public function invoice(Request $request) {
        return view('admin.v1.invoice.list');
    }

    private function getInvoiceUniqueNumber() {
        $user = Sentinel::getUser();
        $max_id = Invoice::withTrashed()->max('id');
        return $max_id + 1;
    }

    public function newInvoice(Request $request) {
        $data['enrollment_list'] = Enrollment::select('id','front_fname','front_lname')->orderBy('id','DESC')->get();
        $data['company_list'] = Company::select('id','company_name')->where('status',1)->orderBy('id','DESC')->get();
        $data['cource'] = Cource::where('status',1)->orderBy('id','ASC')->get();
        $data['max_id'] = self::getInvoiceUniqueNumber();
        return view('admin.v1.invoice.new',$data);
    }

    public function registerInvoice(Request $request) {
        //dd($request);
        $cource_list = Cource::where('status',1)->orderBy('id','ASC')->get();
        $final_array = [];
        foreach($cource_list as $row) {
            $ser = [];
            if(request('chk_'.$row->short_name)) {
                $ser['qty'] = request('qty_'.$row->short_name);
                $ser['fees'] = request('fees_'.$row->short_name);
                $ser['subtotal'] = request('subtotal_'.$row->short_name);

                $final_array[] = $ser;
            }
        }

        $ins['invoice_date'] = request('invoice_date') ? date('Y-m-d',strtotime(request('invoice_date'))) : date('Y-m-d',strtotime("now"));
        $ins['enrollment_id'] = request('enrollment_id') ? request('enrollment_id') : 0;
        $ins['company_id'] = request('company_id') ? request('company_id') : 0;
        $ins['invoice_name'] = request('invoice_name');
        $ins['invoice_address'] = request('invoice_address');
        $ins['charge_for'] = request('charge_for');
        $ins['certificate_detail'] = serialize($final_array);
        $ins['grand_total'] = request('grand_total');

        $response = Invoice::insert($ins);

        if($response) {
            return redirect()->route('invoice')->with('success','Invoice registered successfully');
        } else {
            return back()->with('error','Oops..! Something went wrong');
        }
    }

    public static function generateInvoiceNumber($id) {
        return "DNIE/".$id;
    }

    public function getInvoiceList(Request $request) {
        $user = Sentinel::getUser();
        $list = Invoice::query();
        $list->where('status',1);
        $list->orderby('id','DESC');
        $result = $list->get();
        return DataTables::of($result)
        ->addColumn('invoice_info', function ($result) {
            $html = self::generateInvoiceNumber($result->id);
            $html .= '<br><span class="text-muted">'.Admin::FormateDate($result->invoice_date).'</span>';
            return $html;
        })
        ->addColumn('user_info', function ($result) {
            $html = $result->invoice_name;
            $html .= '<br><span class="text-muted">'.$result->invoice_address.'</span>';
            return $html;
        })
        ->addColumn('charge_info', function ($result) {
            $html = Admin::getInvoiceType($result->charge_for);
            return $html;
        })
        ->addColumn('grand_total_info', function ($result) {
            $html = Admin::FormateTransection($result->grand_total,true);
            return $html;
        })
        ->addColumn('action', function ($result) {
            $html = '';
            return $html;
        })
        ->rawColumns(['invoice_info','user_info','charge_info','grand_total_info', 'action'])
        ->make(true);
    }
}
