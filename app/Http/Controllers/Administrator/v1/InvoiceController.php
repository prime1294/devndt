<?php

namespace App\Http\Controllers\Administrator\v1;

use Admin;
use App\Model\Company;
use App\Model\Cource;
use App\Model\Invoice;
use App\Model\Enrollment;
use App\Model\InvoiceServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Sentinel;
use DataTables;

class InvoiceController extends Controller
{
    public function __construct() {

    }

    public function invoice(Request $request) {
        return view('admin.v1.invoice.list');
    }

    public function invoiceServices(Request $request,$id) {
        $data['info'] = Invoice::find($id);
        $data['invoice_print_id'] = self::generateInvoiceNumber($id);
        return view('admin.v1.invoice.service_list',$data);
    }

    private function getInvoiceUniqueNumber() {
        $user = Sentinel::getUser();
        $max_id = Invoice::withTrashed()->max('id');
        return $max_id + 1;
    }

    public function newInvoice(Request $request) {
        $data['enrollment_list'] = Enrollment::select('id','front_fname','front_lname')->orderBy('id','DESC')->get();
        $data['company_list'] = Company::select('id','company_name')->where('status',1)->orderBy('id','DESC')->get();
        $data['max_id'] = self::getInvoiceUniqueNumber();
        return view('admin.v1.invoice.new',$data);
    }

    public function editInvoice(Request $request,$id) {
        $data['info'] = Invoice::find($id);
        $data['enrollment_list'] = Enrollment::select('id','front_fname','front_lname')->orderBy('id','DESC')->get();
        $data['company_list'] = Company::select('id','company_name')->where('status',1)->orderBy('id','DESC')->get();
        return view('admin.v1.invoice.edit',$data);
    }

    public function newService(Request $request,$id) {
        $data['info'] = Invoice::find($id);
        $data['invoice_print_id'] = self::generateInvoiceNumber($id);
        $data['cource'] = Cource::where('status',1)->orderBy('id','ASC')->get();
        $data['enrollment_list'] = Enrollment::select('id','front_fname','front_lname')->orderBy('id','DESC')->get();
        return view('admin.v1.invoice.service_new',$data);
    }

    public function updateInvoice(Request $request,$id) {
        $validator = Validator::make($request->all(), [
            'invoice_date' => 'required',
            'invoice_name' => 'required',
            'invoice_address' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $ins['invoice_date'] = request('invoice_date') ? date('Y-m-d',strtotime(request('invoice_date'))) : date('Y-m-d',strtotime("now"));
        $ins['enrollment_id'] = request('enrollment_id') ? request('enrollment_id') : 0;
        $ins['company_id'] = request('company_id') ? request('company_id') : 0;
        $ins['invoice_name'] = request('invoice_name');
        $ins['invoice_address'] = request('invoice_address');

        $response = Invoice::where('id',$id)->update($ins);

        if($response) {
            return redirect()->route('invoice')->with('success','Invoice updated successfully');
        } else {
            return back()->with('error','Oops..! Something went wrong');
        }

    }

    public function registerInvoice(Request $request) {

        $validator = Validator::make($request->all(), [
            'invoice_date' => 'required',
            'invoice_name' => 'required',
            'invoice_address' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $ins['invoice_date'] = request('invoice_date') ? date('Y-m-d',strtotime(request('invoice_date'))) : date('Y-m-d',strtotime("now"));
        $ins['enrollment_id'] = request('enrollment_id') ? request('enrollment_id') : 0;
        $ins['company_id'] = request('company_id') ? request('company_id') : 0;
        $ins['invoice_name'] = request('invoice_name');
        $ins['invoice_address'] = request('invoice_address');

        $response = Invoice::insert($ins);

        if($response) {
            return redirect()->route('invoice')->with('success','Invoice registered successfully');
        } else {
            return back()->with('error','Oops..! Something went wrong');
        }
    }

    public function registerService(Request $request,$id) {
        $validator = Validator::make($request->all(), [
            'charge_for' => 'required',
            'grand_total' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $cource_list = Cource::where('status',1)->orderBy('id','ASC')->get();
        $final_array = [];
        foreach($cource_list as $row) {
            $ser = [];
            if(request('chk_'.$row->short_name)) {
                $ser['cource_id'] = $row->id;
                $ser['qty'] = request('qty_'.$row->short_name);
                $ser['fees'] = request('fees_'.$row->short_name);
                $ser['subtotal'] = request('subtotal_'.$row->short_name);

                $final_array[] = $ser;
            }
        }

        $ins['invoice_id'] = $id;
        $ins['charge_for'] = request('charge_for');
        $ins['certificate_detail'] = serialize($final_array);
        $ins['service_total'] = request('grand_total');
        $ins['quotation_no'] = request('quotation_no');

        //if vision
        $ins['vision_certificate_fees'] = request('vision_certificate_fees');
        $ins['certificate_to'] = request('certificate_to') ? implode(',',request('certificate_to')) : "";

        //if consultancy
        $ins['const_from'] = request('const_from') ? date('Y-m-d',strtotime(request('const_from'))) : date('Y-m-d',strtotime("now"));
        $ins['const_to'] = request('const_to') ? date('Y-m-d',strtotime(request('const_to'))) : date('Y-m-d',strtotime("now"));
        $ins['const_days'] = request('const_days') ? request('const_days') : 0;
        $ins['const_charge'] = request('const_charge') ? request('const_charge') : 0;

        $response = InvoiceServices::insert($ins);

        if($response) {
            return redirect()->route('invoice')->with('success','Service registered successfully');
        } else {
            return back()->with('error','Oops..! Something went wrong');
        }

    }

    public static function generateInvoiceNumber($id) {
        return "DNIE/".$id;
    }

    public function getInvoiceServices($id) {
        $list = InvoiceServices::where('invoice_id',$id)->where('status',1)->get();
        return $list;
    }

    public function getGrandTotalofServices($id) {
        return InvoiceServices::where('invoice_id',$id)->where('status',1)->sum('service_total');
    }

    public static function infoCourse($id) {
        return Cource::find($id);
    }

    public static function enrollmentIdsInfo($ids) {
       return Enrollment::select('id','front_fname','front_lname')
            ->whereIn('id',$ids)
            ->orderBy('id','ASC')
            ->get();
    }

    public function getServiceList(Request $request,$id) {
        $user = Sentinel::getUser();
        $list = InvoiceServices::query();
        $list->where('invoice_id',$id);
        $list->where('status',1);
        $list->orderby('id','DESC');
        $result = $list->get();
        return DataTables::of($result)
        ->addColumn('charge_info', function ($result) {
            $html = Admin::getInvoiceType($result->charge_for);
            return $html;
        })
        ->addColumn('quote_info', function ($result) {
            $html = $result->quotation_no;
            return $html;
        })
        ->addColumn('total_info', function ($result) {
            $html = Admin::FormateTransection($result->service_total,true);
            return $html;
        })
        ->addColumn('action', function ($result) {
            $html = '<a href="'.route('delete.service',["id"=>$result->id,"invid"=>$result->invoice_id]).'" class="btn btn-danger btn-xs" onclick="return confirm(\'Are You sure want to delete this service?\')"><i class="fa fa-trash"></i> Delete</a>';
            return $html;
        })
        ->rawColumns(['charge_info','quote_info','total_info', 'action'])
        ->make(true);
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
            $grand_total = self::getGrandTotalofServices($result->id);
            $html = Admin::FormateTransection($grand_total,true);
            return $html;
        })
        ->addColumn('action', function ($result) {
            $html = '';
            $html .= '<div class="dropdown">';
            $html .= '<button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-file-pdf-o"></i> Action <span class="caret"></span></button>';
            $html .= '<ul class="dropdown-menu">';
            $html .= '<li><a href="'.route('edit.invoice',$result->id).'">Edit Invoice</a></li>';
            $html .= '<li><a href="'.route('new.invoice.service',$result->id).'">Add New Service</a></li>';
            $html .= '<li><a href="'.route('invoice.services',$result->id).'">View Services</a></li>';
            $html .= '<li><a target="_blank" href="'.route("invoice.pdf",$result->id).'">Download Invoice</a></li>';
            $html .= '</ul>';
            $html .= '</div>';
            return $html;
        })
        ->rawColumns(['invoice_info','user_info','charge_info','grand_total_info', 'action'])
        ->make(true);
    }

    public function deleteService(Request $request,$id,$invid) {
        $response = InvoiceServices::where('id',$id)->delete();
        if($response) {
            return redirect()->route('invoice.services',$invid)->with('success','Service deleted successfully');
        } else {
            return redirect()->route('invoice.services',$invid)->with('error','Oops..! Something went wrong');
        }
    }

    public function invoicePdf(Request $request,$id) {
        $user = Sentinel::getUser();
        $data['info'] = Invoice::find($id);
        $data['invoice_number'] = self::generateInvoiceNumber($id);
        $data['invoice_services'] = self::getInvoiceServices($id);
        $data['invoice_grand_total'] = self::getGrandTotalofServices($id);
        $data['user'] = Sentinel::check();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin.v1.invoice.pdf', $data);
        return $pdf->stream('invoice-'.$id.'.pdf');
    }
}
