<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\DeliveryChallan;
use App\Model\DeliveryChallanItem;
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

class DeliveryController extends Controller
{
    public function __construct() {

    }

    public function deliverychallan(Request $request) {
        $user = Sentinel::getUser();
        $data['party_list'] = Party::where('user_id',$user->id)->where('status',1)->get();
        $data['stock_list'] = StockItem::where('pending','!=',0)->where('user_id',$user->id)->where('status',1)->orderBy('id','DESC')->get();
        return view('admin.v1.delivery.list',$data);
    }

    public function addNewChallan(Request $request) {
        $user = Sentinel::getUser();
        $data['party_list'] = Party::where('user_id',$user->id)->where('status',1)->get();
        $data['stock_list'] = StockItem::where('pending','!=',0)->where('user_id',$user->id)->where('status',1)->orderBy('id','DESC')->get();
        $data['category_list'] = StockUnit::where('status',1)->orderBy('name','ASC')->get();
        $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
        return view('admin.v1.delivery.new',$data);
    }

    public function editChallan(Request $request,$id) {
        $user = Sentinel::getUser();
        $data['info'] = DeliveryChallan::find($id);
        $data['challan_items'] = DeliveryChallanItem::where('user_id',$user->id)->where('delivery_id',$id)->where('status',1)->get();
        $data['party_list'] = Party::where('user_id',$user->id)->where('status',1)->get();
        $data['stock_list'] = StockItem::where('pending','!=',0)->where('user_id',$user->id)->where('status',1)->orderBy('id','DESC')->get();
        $data['category_list'] = StockUnit::where('status',1)->orderBy('name','ASC')->get();
        $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
        return view('admin.v1.delivery.edit',$data);
    }

    private function getChallanUniqueNumber() {
        $user = Sentinel::getUser();
        $max_id = DeliveryChallan::where('user_id',$user->id)->count();
        return  $max_id + 1;
    }

    public function newChallanRegister(Request $request) {
        $user = Sentinel::getUser();
        //register new process
        $validator = Validator::make($request->all(), [
            'adjustment_date' => 'required',
            "process_name"    => "required",
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
        $ins['dc_name'] = self::getChallanUniqueNumber();
        $ins['date'] = date('Y-m-d',strtotime(request('adjustment_date')));
        $ins['party_id'] = request('process_name');
        $ins['business_name'] = request('business_name');
        $ins['challan_no'] = request('challan_no');
        $ins['remarks'] = request('remarks');
        $ins['state'] = request('process_state');
        $ins['transport'] = request('process_transport');
        $ins['grand_total'] = request('grand_total');
        $stock_process_ins_id = DeliveryChallan::insertGetId($ins);

        //insert item
        $stock_no = request('stock_no');
        $description = request('description');
        $design_name = request('design_name');
        $quantity = request('quantity') ? request('quantity') : [];
        $mesurement = request('mesurement');
        $unit = request('unit');
        $hsn_number = request('hsn_no');
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
                $child['rate'] = $unit[$key];
                $child['total'] = $sub_total[$key];
                DeliveryChallanItem::insert($child);
            }
        }

        if($stock_process_ins_id) {
            return redirect()->route('delivery.challan')->with('success', "Delivery challan register successfully");
        } else {
            return redirect()->route('delivery.challan')->with('error', "Ooops..! Something went wrong");
        }

    }

    public function updateDeliveryChallan(Request $request,$id) {
        $user = Sentinel::getUser();
        //register new process
        $validator = Validator::make($request->all(), [
            'adjustment_date' => 'required',
            "process_name"    => "required",
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
        $ins['party_id'] = request('process_name');
        $ins['business_name'] = request('business_name');
        $ins['challan_no'] = request('challan_no');
        $ins['remarks'] = request('remarks');
        $ins['state'] = request('process_state');
        $ins['transport'] = request('process_transport');
        $ins['grand_total'] = request('grand_total');
        $response = DeliveryChallan::where('id',$id)->update($ins);
        $stock_process_ins_id = $id;


        //remove all old items
        DeliveryChallanItem::where('delivery_id',$id)->where('user_id',$user->id)->forceDelete();

        //insert item
        $stock_no = request('stock_no');
        $description = request('description');
        $design_name = request('design_name');
        $quantity = request('quantity') ? request('quantity') : [];
        $mesurement = request('mesurement');
        $unit = request('unit');
        $hsn_number = request('hsn_no');
        $sub_total = request('total');
        foreach($stock_no as $key=>$row) {
            if($quantity[$key] != ""  && $quantity[$key] != 0 ) {
                $child['user_id'] = $user->id;
                $child['delivery_id'] = $stock_process_ins_id;
                $child['stock_id'] = $stock_no[$key] != "" ? $stock_no[$key] : 0;
                $child['description'] = $description[$key];
                $child['design_name'] = $design_name[$key];
                $child['hsn_code'] = $hsn_number[$key];
                $child['quantity'] = $quantity[$key];
                $child['mesurement'] = $mesurement[$key];
                $child['rate'] = $unit[$key];
                $child['total'] = $sub_total[$key];
                DeliveryChallanItem::insert($child);
            }
        }

        if($stock_process_ins_id) {
            return redirect()->route('delivery.challan')->with('success', "Delivery challan updated successfully");
        } else {
            return redirect()->route('delivery.challan')->with('error', "Ooops..! Something went wrong");
        }
    }

    private function getchallanitemList($id) {
        $user = Sentinel::getUser();
        $result = DeliveryChallanItem::select('*','stock_unit.name as mesurement_name')
            ->leftjoin('stock_unit','delivery_challan_item.mesurement','=','stock_unit.id')
            ->where('delivery_challan_item.delivery_id',$id)
            ->where('delivery_challan_item.user_id',$user->id)
            ->where('delivery_challan_item.status',1)
            ->groupBy('delivery_challan_item.id')
            ->orderBy('delivery_challan_item.id','ASC');
        return $result;
    }

    public function viewAjaxChallan(Request $request) {
        $user = Sentinel::getUser();
        $process = DeliveryChallan::query();
        $process->select('delivery_challan.*','party.name as party_name','party.photo as party_photo');
        $process->leftjoin('party','delivery_challan.party_id','=','party.id');
        $process->leftjoin('delivery_challan_item','delivery_challan.id','=','delivery_challan_item.delivery_id');
        $process->where('delivery_challan.status',1);

        if(request('bill_no')) {
            $process->where('delivery_challan.dc_name','like','%'.request('bill_no').'%');
        }
        if(request('filter_by')) {
            $process->where('delivery_challan.party_id',request('filter_by'));
        }
        if(request('stock_no')) {
            $process->where('delivery_challan_item.stock_id',request('stock_no'));
        }
        if(request('design_name')) {
            $process->where('delivery_challan_item.design_name','like','%'.request('design_name').'%');
        }

        if(request('startdate') && request('enddate')) {
            if(request('startdate') != "" && request('enddate') != "") {
                $process->whereBetween('delivery_challan.date',[request('startdate'), request('enddate')]);
            }
        }

        $process->groupBy('delivery_challan.id');
        $process->orderBy('delivery_challan.date','DESC');

        return DataTables::of($process)
            ->addColumn('formated_date', function ($process) {
                $html = '';
                $html .= Admin::FormateDate($process->date);
                $html .= '<br><span class="label bg-green">'.$process->dc_name.'</span>';
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
                $html .= '<img src="'.asset($process->party_photo).'" alt="'.$process->party_name.'" class="img-circle img-responsive" style="display:inline-block;vertical-align:top;width:30px;"> <span style="display: inline-block;margin-left:5px;">'.$process->party_name.'<br><span class="text-muted">'.$process->business_name.'</span> </span>';
                return $html;
            })
            ->addColumn('formated_grand_total', function ($process) {
                $html = '';
                $html .= Admin::FormateTransection($process->grand_total);
                return $html;
            })
            ->addColumn('action', function ($process) {
                $html = '';
                $html .= '<a href="'.route('edit.delivery.challan',$process->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
                $html .= ' <a href="'.route('delivery.challan.pdf',$process->id).'" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-file-pdf-o"></i> Preview</a>';
                $html .= '  <a href="'.route('delete.delivery.challan',$process->id).'" onclick="return confirm(\'Are you sure want to delete this recrod?\')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a>';
                return $html;
            })
            ->rawColumns(['formated_date','formate_stock','formated_process','formated_grand_total','action'])
            ->make(true);
    }

    public function deleteChallan(Request $request,$id) {
        $user = Sentinel::getUser();
        //delete stock item
        DeliveryChallanItem::where('delivery_id',$id)->where('user_id',$user->id)->delete();

        //delete self
        $response = DeliveryChallan::where('id',$id)->where('user_id',$user->id)->delete();

        if($response) {
            return redirect()->route('delivery.challan')->with('success', "Challan deleted Successfully");
        } else {
            return redirect()->route('delivery.challan')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function downloadpdf(Request $request,$id) {
        $user = Sentinel::getUser();
        $data['user_state'] = $user->state != "" ? State::where('state_id',$user->state)->first() : [];
        $data['user_city'] = $user->city != "" ? DB::table('cities')->where('city_id',$user->city)->first() : [];
        $process = DeliveryChallan::query();
        $process->select('delivery_challan.*','party.name as process_name','party.gstin_no as process_gst','party.state as process_state','party.city as process_city','party.address as process_address','party.photo as process_photo','party.business_name as process_business');
        $process->leftjoin('party','delivery_challan.party_id','=','party.id');
        $process->where('delivery_challan.status',1);
        $process->where('delivery_challan.id',$id);
        $data['info'] = $info = $process->first();
        $data['process_state'] = $info->process_state != "" ? State::where('state_id',$info->process_state)->first() : [];
        $data['process_city'] = $info->process_city != "" ? DB::table('cities')->where('city_id',$info->process_city)->first() : [];
        $data['item_list'] = self::getchallanitemList($id)->get();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin.v1.delivery.pdf', $data);
        return $pdf->stream('delivery-challan-no-'.$id.'.pdf');
    }
}
