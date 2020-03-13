<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\ReadyStock;
use App\Model\StockProcess;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Admin;
use Sentinel;
use App\User;
use App\Model\Stock;
use App\Model\StockCategory;
use App\Model\StockItem;
use App\Model\StockItemQunatity;
use App\Model\StockUnit;
use App\Model\Party;
use App\Model\Agent;
use App\Model\Transport;
use App\Model\Settlement;

class StockController extends Controller
{
  public function __construct() {

  }

  public function addStock(Request $request,$id) {
    $user = Sentinel::getUser();
    self::removeallDummyRecords();
    $data['stock_category'] = StockCategory::where('status',1)->orderBy('name','ASC')->get();
    $data['stock_unit'] = StockUnit::where('status',1)->orderBy('name','ASC')->get();
    $data['party_info'] = Party::find($id);
    $data['agent_list'] = Agent::where('status',1)->orderBy('id','DESC')->get();
    $data['transport_list'] = Transport::where('status',1)->orderBy('id','DESC')->get();
    $data['unique_stock_id'] =  Admin::uniqueStockId($user->id,$id); //'2_5_1573900492_28';
    return view('admin.v1.stock.new',$data);
  }

  public function settlementStock(Request $request,$id,$uid) {
    $user = Sentinel::getUser();
    $data['info'] = $info = StockItem::find($id);
    $data['party_info'] = Party::find($uid);
    $data['settlement_list'] = $settlement = Settlement::where('user_id',$user->id)->where('stock_id',$id)->where('party_id',$uid)->get();
    $data['validation_amount'] = $info->pending + $settlement->sum("unit");
    return view('admin.v1.stock.return',$data);
  }

  public function editStock(Request $request,$id,$uid) {
    $user = Sentinel::getUser();
    if(Stock::scopeHaveRightBank(Stock::query(),$user->id,$uid)) {
      self::removeallDummyRecords();
      $data['stock_category'] = StockCategory::where('status',1)->orderBy('name','ASC')->get();
      $data['stock_unit'] = StockUnit::where('status',1)->orderBy('name','ASC')->get();
      $data['party_info'] = Party::find($id);
      $data['agent_list'] = Agent::where('status',1)->orderBy('id','DESC')->get();
      $data['transport_list'] = Transport::where('status',1)->orderBy('id','DESC')->get();
      $data['stock_info'] = Stock::find($uid);
      $data['unique_stock_id'] =  $data['stock_info']->stock_unique_id; ///Admin::uniqueStockId($user->id,$id);
      return view('admin.v1.stock.edit',$data);
    } else {
      return Admin::unauth();
    }
  }

  public function removeStock(Request $request,$id,$uid) {
    $user = Sentinel::getUser();
    if(Stock::scopeHaveRightBank(Stock::query(),$user->id,$uid)) {
      $stock = Stock::find($uid)->delete();
      if($stock) {
        return redirect()->route('party.view',$id)->with('success', "Stock Removed Successfully");
      } else {
        return redirect()->route('party.view',$id)->with('error', "Ooops..! Something went wrong");
      }
    } else {
      return Admin::unauth();
    }
  }

  public function getStockItemInfo(Request $request,$id) {
    $user = Sentinel::getUser();
    $info = StockItem::find($id);
    $info['unit_name'] = StockUnit::find($info->unit)->name;
    $info['quantity'] = StockItemQunatity::where('stock_item_id',$info->id)->get();
    $data['result'] = $info;
    $data['message'] = "success";
    $data['status'] = "true";
    return response()->json($data);
  }

  function getstockcolumninformation($stock_id,$column) {
    $stock = Stock::where('stock_unique_id',$stock_id)->first();
    if(!empty($stock)) {
      return $stock->$column;
    } else {
      return 0;
    }
  }

  function getstockitemcolumninformation($stockitemid,$column) {
    $stock = StockItem::find($stockitemid);
    return $stock->$column;
  }

  function getTotalofStock($stockitemid,$user_id) {
    $result = StockItemQunatity::where('stock_item_id',$stockitemid)->get();
    return collect($result)->sum('total_quantity');
  }

  //unused
  public function updatestockinformation($stock_id,$total_prefix = "",$total = 0,$pending_prefix = "",$pending = 0, $return_prefix = "",$return = 0) {
    $stock = Stock::where('stock_unique_id',$stock_id)->first();
    if($total != 0) {
      if($total_prefix == "-") {
        $stock->total -= $total;
      } elseif($total_prefix == "+") {
        $stock->total += $total;
      } else {
        $stock->total = $total;
      }
    }
    if($pending != 0) {
      if($pending_prefix == "-") {
        $stock->pending -= $pending;
      } elseif($pending_prefix == "+") {
        $stock->pending += $pending;
      } else {
        $stock->pending = $pending;
      }
    }
    if($return != 0) {
      if($return_prefix == "-") {
        $stock->stock_return -= $return;
      } elseif($return_prefix == "+") {
        $stock->stock_return += $return;
      } else {
        $stock->stock_return = $return;
      }
    }
    $response = $stock->save();
    return $response ? true : false;
  }


  public function updatestockiteminformation($stockitemid,$total_prefix = "",$total = 0,$pending_prefix = "",$pending = 0, $return_prefix = "",$return = 0) {
    $stock = StockItem::find($stockitemid);
    if($total != 0) {
      if($total_prefix == "-") {
        $stock->total -= $total;
      } elseif($total_prefix == "+") {
        $stock->total += $total;
      } else {
        $stock->total = $total;
      }
    }
    if($pending != 0) {
      if($pending_prefix == "-") {
        $stock->pending -= $pending;
      } elseif($pending_prefix == "+") {
        $stock->pending += $pending;
      } else {
        $stock->pending = $pending;
      }
    }
    if($return != 0) {
      if($return_prefix == "-") {
        $stock->stock_return -= $return;
      } elseif($return_prefix == "+") {
        $stock->stock_return += $return;
      } else {
        $stock->stock_return = $return;
      }
    }
    $response = $stock->save();
    return $response ? true : false;
  }

  //unused
  function updatestocktotalmain($stock_id) {
    $user = Sentinel::getUser();
    $old_total = self::getstockcolumninformation($stock_id,'total');
    $new_total = self::getTotalofStock($stock_id,$user->id);
    $new_total = $new_total - $old_total; //return diffrent only
    $new_prefix = $old_total != 0 ? "+" : "";
    self::updatestockinformation($stock_id,$new_prefix,$new_total,$new_prefix,$new_total);
  }

  function updatestockitemtotalmain($stockitemid) {
    $user = Sentinel::getUser();
    $old_total = self::getstockitemcolumninformation($stockitemid,'total');
    $new_total = self::getTotalofStock($stockitemid,$user->id);
    $new_total = $new_total - $old_total; //return diffrent only
    $new_prefix = $old_total != 0 ? "+" : "";
    self::updatestockiteminformation($stockitemid,$new_prefix,$new_total,$new_prefix,$new_total);
  }

  public function updateStockQuantity(Request $request) {
    $user = Sentinel::getUser();
    $data['status'] = "false";
    $data['message'] = "Ooops..! Something went wrong";
    $validator = Validator::make($request->all(), [
        'mainid' => 'required',
        'stock_unique_id' => 'required',
        'product_name' => 'required',
        "qty"    => "required|array|min:1",
        "qty.*"  => "required|min:1",
        "measurement"    => "required|array|min:1",
        "measurement.*"  => "required|min:1",
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      $data['message'] = $errors->first();
      return response()->json($data);
    }

    $update['product_name'] = request('product_name');
    $update['category'] = request('category');
    $update['unit'] = request('unit');
    $update['product_length'] = request('product_length');
    if(request('fbinputtxt') != "") {
      $update['photo'] = request('fbinputtxt');
    }

    $response = StockItem::where('id',request('mainid'))->where('stock_unique_id',request('stock_unique_id'))
    ->update($update);

    if($response) {
      //remove all quantity
      StockItemQunatity::where('stock_item_id',request('mainid'))->forceDelete();

      //reinsert
      $quantity_arr = request('qty');
      $measurement_arr = request('measurement');
      $color_arr = request('colors');
      foreach($quantity_arr as $key=>$row) {
        if($row != "" && $measurement_arr[$key] != "") {
          $qua['stock_item_id'] = request('mainid');
          $qua['quantity'] = $row;
          $qua['user_id'] = $user->id;
          $qua['stock_unique_id'] = request('stock_unique_id');
          $qua['mesurement'] = $measurement_arr[$key];
          $qua['total_quantity'] = $row*$measurement_arr[$key];
          $qua['color'] = $color_arr[$key];
          $response = StockItemQunatity::insert($qua);
        }
      }

      //update in table
      self::updatestockitemtotalmain(request('mainid'));
      $data['status'] = "true";
      $data['message'] = "success";
    } else {
      $data['message'] = "Ooops..! Something went wrong";
    }

    return response()->json($data);
  }

  public function updateStock(Request $request,$id,$uid) {
    $user = Sentinel::getUser();
    if(Stock::scopeHaveRightBank(Stock::query(),$user->id,$uid)) {
      $validator = Validator::make($request->all(), [
        'adjustment_date' => 'required',
        'challan_no' => 'required',
        'upload_challanphotobtn' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('error', $errors->first());
      }

      $user = Sentinel::getUser();
      $update['as_of'] = request('adjustment_date') != null ? date('Y-m-d',strtotime(request('adjustment_date'))) : null;
      $update['party_id'] = $id;
      $update['agent_id'] = request('agent') ? request('agent') : 0;
      $update['transport_id'] = request('transport') ? request('transport') : 0;
      $update['challan_no'] = request('challan_no');
      $update['challan_photo'] = request('upload_challanphotobtn');
      $update['lrno'] = request('lrno');
      $update['sample_photo'] = request('upload_image_text');
      $update['location'] = request('location');
      $update['remarks'] = request('remarks');

      $response = Stock::where('id',$uid)->update($update);

        //active all same unique id
        $info = Stock::find($uid);
        StockItem::where('user_id',$user->id)->where('stock_unique_id',$info->stock_unique_id)->update([
            'status'=>1
        ]);

      if($response) {
        return redirect()->route('party.view',$id)->with('success', "Stock updated Successfully");
      } else {
        return redirect()->route('party.view',$id)->with('error', "Ooops..! Something went wrong");
      }
    } else {
      return Admin::unauth();
    }
  }

    public function readyStock(Request $request) {
        $user = Sentinel::getUser();
        $data['stock_list'] = StockItem::where('pending','!=',0)->where('user_id',$user->id)->where('status',1)->orderBy('id','DESC')->get();
        $data['category_list'] = StockUnit::where('status',1)->orderBy('name','ASC')->get();
        $ready_stock = ReadyStock::where('user_id',$user->id);
        $data['settlement_list'] = $ready_stock->get();
        $data['total_stock'] = $ready_stock->sum('amount');
        return view('admin.v1.stock.ready',$data);
    }

    public function registerReadyStock(Request $request) {
        $user = Sentinel::getUser();
        //register new process
        $validator = Validator::make($request->all(), [
            'adjustment_date' => 'array|min:1',
            "stock_no"    => "array|min:1",
            "design_name"    => "array|min:1",
            "measurement"    => "array|min:1",
            "mesurement"    => "array|min:1",
            "rate"    => "array|min:1",
            "amount"    => "array|min:1",
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        //delete all
        ReadyStock::where('user_id',$user->id)->forceDelete();

        $response = true;
        $stock_no = request('stock_no') ? request('stock_no') : [];
        $adjustment_date = request('adjustment_date');
        $design_name = request('design_name');
        $qty = request('measurement');
        $unit = request('mesurement');
        $rate = request('rate');
        $amount = request('amount');
        foreach($stock_no as $key=>$row) {
            if($adjustment_date[$key] != "" && $stock_no[$key] != "" && $qty[$key] != "" && $unit[$key] != "" && $rate[$key] != "" && $amount[$key] != "") {
                $child['user_id'] = $user->id;
                $child['date'] = date('Y-m-d',strtotime($adjustment_date[$key]));
                $child['stock_no'] = $stock_no[$key];
                $child['qty'] = $qty[$key];
                $child['design_name'] = $design_name[$key];
                $child['unit'] = $unit[$key];
                $child['rate'] = $rate[$key];
                $child['amount'] = $amount[$key];
                $response = ReadyStock::insert($child);
            }
        }

        if($response) {
            return redirect()->route('ready.stock')->with('success', "Ready Stock updated");
        } else {
            return redirect()->route('ready.stock')->with('error', "Ooops..! Something went wrong");
        }
    }

  private  function getUserinfo($tbl,$id) {
      $user = Sentinel::getUser();
      $info = DB::table($tbl)->where('id',$id)->where('user_id',$user->id)->first();
      return $info;
  }

  private function getProcessReportType($id) {
      $user = Sentinel::getUser();
      $list = StockProcess::select(DB::raw('GROUP_CONCAT(process_type.name) as mname'))
      ->leftjoin('process_type', DB::raw('FIND_IN_SET(`process_type`.`id`,`stock_process`.`manufacturer_type`)'),'<>',DB::raw('"0"'))
      ->where('stock_process.user_id',$user->id)
      ->where('stock_process.id',$id)
      ->first();
      return $list->mname;
  }

  public function getStockInfo(Request $request) {
      $user = Sentinel::getUser();
      $search = request('term');
      $stock = StockItem::select('stock_item.stock_unique_id','stock_item.stock_name','stock_item.photo','stock.party_id')
          ->leftjoin('stock', 'stock_item.stock_unique_id','=','stock.stock_unique_id')
          ->where('stock_item.pending','!=',0)
          ->where('stock_item.stock_name','like','%'.$search.'%')
          ->where('stock_item.user_id',$user->id)
          ->where('stock_item.status',1)
          ->groupBy('stock_item.id')
          ->orderBy('stock_item.id','DESC')
          ->get();
      $parent = [];
      foreach($stock as $row) {
          $child = [];
          $child['name'] = $row->stock_name;
          $child['value'] = route('party.view',$row->party_id);
          $child['label'] = '<img src="'.asset($row->photo).'" style="display: inline-block" width="40" /><span style="display: inline-block">'.$row->stock_name.'</span>';
          array_push($parent,$child);
      }
      return $parent;
  }

  public function getStockReport(Request $request) {
      $user = Sentinel::getUser();
      $id = request('id');
      $search['user_id'] = $user->id;
      $search['stock_id'] = $id;
//      echo Admin::getStockReportQuery($search); die();
      $list = DB::select(Admin::getStockReportQuery($search));
      return DataTables::of($list)
      ->addColumn('report_date', function ($list) {
          $html = '';
          $html .= Admin::FormateDate($list->report_date);
          return $html;
      })
      ->addColumn('report_number', function ($list) {
          $html = '';
          $html .= $list->report_number;
          return $html;
      })
      ->addColumn('report_type', function ($list) {
          $html = '';
          $html .= config('stock_report.'.$list->report_type)['name'];
          if($list->report_type == "PROCESS") {
              $reportname = self::getProcessReportType($list->report_number);
              if($reportname != "") {
                  $html .= ' ('.$reportname.')';
              }
          }
          return $html;
      })
      ->addColumn('report_user', function ($list) {
          $html = '';
          if($list->report_user_tbl != '') {
              if ($list->report_user != 0) {
                  $info = self::getUserinfo($list->report_user_tbl, $list->report_user);
                  //$html .= '<img src="' . asset($info->photo) . '" alt="' . $info->name . '" class="img-circle img-responsive" style="display:inline-block;vertical-align:top; width: 40px;"> <span style="display: inline-block;margin-left:5px;">' . $info->name . '</span>';
                  if($list->report_type == "STOCK") {
                      $html .= $info->business_name;
                  } else {
                      $html .= $info->name;
                  }
              } else {
                  $html .= $list->report_user_tbl;
              }
          }
          return $html;
      })
      ->addColumn('report_quantity', function ($list) {
          $html = '';
          $html .= round($list->report_quantity,2).' '.StockUnit::find($list->report_unit)->name;
          return $html;
      })
      ->addColumn('report_design', function ($list) {
          $html = '';
          $html .= $list->report_design;
          return $html;
      })
      ->addColumn('report_recive', function ($list) {
          $html = '';
          if($list->report_recive != "") {
              $html .= round($list->report_recive,2) . ' ' . StockUnit::find($list->report_unit)->name;
          }
          return $html;
      })
      ->rawColumns(['report_date','report_number','report_type','report_user','report_quantity','report_design','report_recive'])
      ->make(true);

  }

  public function getStockList(Request $request,$party_id) {
    $user = Sentinel::getUser();
    $item = Stock::query();
    $item->select('stock.*','agent.name as agentname');
    $item->leftjoin('agent','stock.agent_id','=','agent.id');
    $item->where('stock.status',1);
    $item->where('stock.user_id',$user->id);
    $item->where('stock.party_id',$party_id);

      if(request('bill_no')) {
          $item->where('stock.challan_no','like','%'.request('bill_no').'%');
      }

      if(request('filter_by')) {
          $item->where('stock.agent_id',request('filter_by'));
      }

      if(request('stock_no')) {
          $item->where('stock.stock_unique_id',request('stock_no'));
      }

      if(request('startdate') && request('enddate')) {
          if(request('startdate') != "" && request('enddate') != "") {
              $item->whereBetween('stock.as_of',[request('startdate'), request('enddate')]);
          }
      }

    $item->groupBy('stock.id');
    $item->orderBY('stock.id','DESC');


    return DataTables::of($item)
    ->addColumn('stock_info', function ($item) {
        $html = '';
        $agentname = $item->agent_id != 0 ? "<br>".$item->agentname : "";
        $html .= '<img src="'.asset($item->challan_photo).'" alt="'.$item->challan_no.'" class="img-responsive" style="display:inline-block;vertical-align:top;" width="40"> <span style="display: inline-block;margin-left:5px;">'.Admin::FormateDate($item->as_of).$agentname.'</span>';
        return $html;
    })
    ->addColumn('challan_number', function ($item) {
        $html = '';
        $html .= $item->challan_no;
        return $html;
    })
    ->addColumn('stock_quantity_html', function ($item) {
        $html = '';
        $stockitems = self::getstockitemList($item->stock_unique_id)->get();
        $toEnd = count($stockitems);
        foreach($stockitems as $key=>$row) {
          $html .= '<div class="row">';
          $html .= '<div class="col-md-2 col-xs-2">';
          $html .= $row->product_name;
          $html .= '</div>';
          $html .= '<div class="col-md-2 col-xs-2">';
          $html .= $row->category_name;
          $html .= '</div>';
          $html .= '<div class="col-md-2 col-xs-2">';
          $html .= $row->stock_name;
          $html .= '</div>';
          $html .= '<div class="col-md-4 col-xs-4">';
          $html .= $row->pending." / ".$row->total." ".$row->mesurement_name;
          $html .= '</div>';
          $html .= '<div class="col-md-2 col-xs-2">';
            $html .= '<a href="'.route('stock.settlement',["id"=>$row->id,"uid"=>$row->party_id]).'" class="btn btn-danger btn-xs"  data-toggle="tooltip" data-placement="top" title="Settlement" ><i class="fa fa-map-signs"></i></a>';
            $html .= ' <button type="button" onclick="getReportInfo('.$row->id.')"  data-toggle="tooltip" data-placement="top" title="Report" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></button>';
          $html .= '</div>';
            $html .= '</div>';
          if (0 !== --$toEnd) {
          $html .= '<div class="row"><div class="col-md-12"><div class="row-spliter"></div></div></div>';
          }
        }
        return $html;
    })
    ->addColumn('action', function ($item) {
        $html = '';
        $html .= '<a href="'.route('stock.edit',["id"=>$item->party_id,"uid"=>$item->id]).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        $html .= ' <a href="'.route('stock.remove',["id"=>$item->party_id,"uid"=>$item->id]).'" class="btn btn-danger btn-xs" onclick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        return $html;
    })
    ->rawColumns(['challan_number','stock_info','stock_quantity_html','action'])
    ->make(true);
  }

  public function getstockitemList($stock_unique_id) {
    $result = StockItem::select('stock_item.*',DB::raw('stock.party_id as party_id'),DB::raw('stock_category.name as category_name'),DB::raw('stock_unit.name as mesurement_name'),DB::raw('SUM(stock_item_quantity.total_quantity) as total_quantity'))
    ->leftjoin('stock_item_quantity','stock_item.id','=','stock_item_quantity.stock_item_id')
    ->leftjoin('stock','stock_item.stock_unique_id','=','stock.stock_unique_id')
    ->leftjoin('stock_unit','stock_item.unit','=','stock_unit.id')
    ->leftjoin('stock_category','stock_item.category','=','stock_category.id')
    ->where('stock_item.stock_unique_id',$stock_unique_id)
    ->whereIn('stock_item.status',[1,3])
    ->groupBy('stock_item.id')
    ->orderBy('stock_item.id','ASC');
    return $result;
  }

  public function getStockProducts(Request $request) {
    $user = Sentinel::getUser();
    $item = self::getstockitemList(request('stock_unique_id'));

    return DataTables::of($item)
    ->addColumn('stock_info', function ($item) {
        $html = '';
        $html .= '<img src="'.asset($item->photo).'" alt="'.$item->product_name.'" class="img-responsive" style="display:inline-block;vertical-align:top;" width="40"> <span style="display: inline-block;margin-left:5px;">'.$item->product_name.'</span>';
        return $html;
    })
    ->addColumn('stock_number', function ($item) {
        $html = '';
        $html .= $item->stock_name;
        return $html;
    })
    ->addColumn('stock_quantity_html', function ($item) {
        $html = '';
        $html .= round($item->total_quantity,2)." ".$item->mesurement_name;
        return $html;
    })
    ->addColumn('action', function ($item) {
        $html = '';
        $html .= ' <a class="btn btn-primary btn-xs editstockproductbtn" data-id="'.$item->id.'" data-toggle="modal" data-target="#editproduct"><i class="fa fa-edit"></i> Edit</a>';
        $html .= ' <a onClick="return removeproduct('.$item->id.')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a>';
        return $html;
    })
    ->rawColumns(['stock_number','stock_info','stock_quantity_html','action'])
    ->make(true);
  }


  public function getStockReturn(Request $request) {
    $user = Sentinel::getUser();
    $item = self::getstockitemList(request('stock_unique_id'));

    return DataTables::of($item)
    ->addColumn('stock_info', function ($item) {
        $html = '';
        $html .= '<img src="'.asset($item->photo).'" alt="'.$item->product_name.'" class="img-responsive" style="display:inline-block;vertical-align:top;" width="40"> <span style="display: inline-block;margin-left:5px;">#'.Admin::FormateStockItemID($item->id).'<br><span class="text-muted">'.$item->product_name.'</span></span>';
        return $html;
    })
    ->addColumn('stock_quantity_html', function ($item) {
        $html = '';
        $html .= $item->pending." ".$item->mesurement_name;
        return $html;
    })
    ->addColumn('action', function ($item) {
        $html = '';
        $html .= ' <a class="btn btn-primary btn-xs editstockproductbtn" data-id="'.$item->id.'" data-panding="'.$item->pending.'" data-toggle="modal" data-target="#addmyproduct"><i class="fa fa-map-signs"></i> Settlement Stock</a>';
        //$html .= ' <a onClick="return removeproduct('.$item->id.')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a>';
        return $html;
    })
    ->rawColumns(['stock_info','stock_quantity_html','action'])
    ->make(true);
  }

  public function registerSettlement(Request $request,$id,$uid) {
    $user = Sentinel::getUser();
    $validator = Validator::make($request->all(), [
        'adjustment_date' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    //collect all old total
    $settlement = Settlement::where('user_id',$user->id)->where('stock_id',$id)->where('party_id',$uid);
    $old_settlement = $settlement->get()->sum("unit");

    //add all old to pending
    self::updatestockiteminformation($id,'+',0,'+',$old_settlement);

    //remove all old data
    $settlement->forceDelete();

    //reinsert data
    $response = true;
    $adjustment = request('adjustment_date');
    $measurement = request('measurement');
    $colors = request('colors');
    $design = request('design');
    $remarks = request('remarks');
    foreach($adjustment as $key=>$row) {
        if($row != "" && $measurement[$key] != "") {
            $ins['user_id'] = $user->id;
            $ins['date'] = date('Y-m-d',strtotime($row));
            $ins['party_id'] = $uid;
            $ins['stock_id'] = $id;
            $ins['unit'] = $measurement[$key];
            $ins['color'] = $colors[$key];
            $ins['design'] = $design[$key];
            $ins['remarks'] = $remarks[$key];

            $response = Settlement::insertGetId($ins);
            self::updatestockiteminformation($id,'-',0,'-',$measurement[$key]);
        }
    }

//    $stockless = self::updatestockiteminformation(request('stock_number'),'-',request('quantity'),'-',request('quantity'));
//    if($stockless === false) {
//      return redirect()->route('party.view',$id)->with('error', "Ooops..! Something went wrong");
//    }


    if($response) {
      return redirect()->route('party.view',$uid)->with('success', "Stock settlement registred Successfully");
    } else {
      return redirect()->route('party.view',$uid)->with('error', "Ooops..! Something went wrong");
    }

  }

  public function registerStock(Request $request,$id) {
    $validator = Validator::make($request->all(), [
        'adjustment_date' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $user = Sentinel::getUser();

    $ins['user_id'] = $user->id;
    $ins['stock_unique_id'] = request('sui');
    $ins['as_of'] = request('adjustment_date') != null ? date('Y-m-d',strtotime(request('adjustment_date'))) : null;
    $ins['party_id'] = $id;
    $ins['agent_id'] = request('agent') ? request('agent') : 0;
    $ins['transport_id'] = request('transport') ? request('transport') : 0;
    $ins['complete_date'] = date('Y-m-d',strtotime(request('complete_date')));
    $ins['challan_no'] = request('challan_no');
    $ins['lrno'] = request('lrno');
    $ins['challan_photo'] = request('upload_challanphotobtn');
    $ins['sample_photo'] = request('upload_image_text');
    $ins['location'] = request('location');
    $ins['stock_status'] = 1;
    $ins['remarks'] = request('remarks');
    $ins['status'] = 1;

    $response = Stock::insert($ins);

    //active all same unique id
    StockItem::where('user_id',$user->id)->where('stock_unique_id',request('sui'))->update([
        'status'=>1
    ]);

    if($response) {
      return redirect()->route('party.view',$id)->with('success', "Stock registred Successfully");
    } else {
      return redirect()->route('party.view',$id)->with('error', "Ooops..! Something went wrong");
    }

  }

  private function getStockUniqueNumber() {
      $user = Sentinel::getUser();
      $max_id = StockItem::where('user_id',$user->id)->count();
      $final =  $max_id + 1;
      return config('setting.stock_prefix').$final;
  }

  private function removeallDummyRecords() {
      $user = Sentinel::getUser();
      StockItem::where('user_id',$user->id)->where('status',3)->forceDelete();
  }

  public function registerStockQuantity(Request $request,$id) {
    $user = Sentinel::getUser();
    $data['status'] = "false";
    $data['message'] = "Ooops..! Something went wrong";

    $validator = Validator::make($request->all(), [
        'stock_unique_id' => 'required',
        'product_name' => 'required',
        "qty"    => "required|array|min:1",
        "qty.*"  => "required|min:1",
        "measurement"    => "required|array|min:1",
        "measurement.*"  => "required|min:1",
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      $data['message'] = $errors->first();
      return response()->json($data);
    }


    $ins['stock_unique_id'] = request('stock_unique_id');
    $ins['user_id'] = $user->id;
    $ins['stock_name'] = self::getStockUniqueNumber();
    $ins['product_name'] = request('product_name');
    $ins['category'] = request('category');
    $ins['unit'] = request('unit');
    $ins['product_length'] = request('product_length');
    $ins['status'] = 3;
    if(request('fbinputtxt') != "") {
      $ins['photo'] = request('fbinputtxt');
    }

    $item_unique_id = StockItem::insertGetId($ins);
    $quantity_arr = request('qty');
    $measurement_arr = request('measurement');
    $color_arr = request('colors');
    $total = [];
    foreach($quantity_arr as $key=>$row) {
      if($row != "" && $measurement_arr[$key] != "") {
        $qua['stock_item_id'] = $item_unique_id;
        $qua['quantity'] = $row;
        $qua['user_id'] = $user->id;
        $qua['stock_unique_id'] = request('stock_unique_id');
        $qua['mesurement'] = $measurement_arr[$key];
        $qua['total_quantity'] = $row*$measurement_arr[$key];
        $qua['color'] = $color_arr[$key];
        $response = StockItemQunatity::insert($qua);
        $total[] = $row*$measurement_arr[$key];
      }
    }

    if($item_unique_id) {
      //update stock quantity
      $final_total = array_sum($total);
      $istock = StockItem::find($item_unique_id);
      $istock->total = $final_total;
      $istock->pending = $final_total;
      $istock->save();

      $data['status'] = "true";
      $data['message'] = "success";
    }

    return response()->json($data);
  }

  public function removeStockProduct(Request $request,$id) {
    $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:stock_item,id']);
    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $data['status'] = "false";
    $data['message'] = "Ooops..! Something went wrong";

    $product =  StockItem::find($id)->forceDelete();
    $items =  StockItemQunatity::where('stock_item_id',$id)->forceDelete();
    if($product) {
      $data['status'] = "true";
      $data['message'] = "success";
    }

    return response()->json($data);
  }

    public function mtypes(Request $request)
    {
        return view('admin.v1.stock.mtypes');
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
                'status' => 1,
                'cond1' => 0,
            ]
        );

        if($request) {
            return redirect()->route('stock.unit')->with('success', "Stock unit registred");
        } else {
            return redirect()->route('stock.unit')->with('error', "Stock unit already exist");
        }
    }

    public function getMtype(Request $request)  {
        $mtype = StockUnit::select('*')->where('status',1)->where('cond1',0)->orderBy('id','DESC');
        return DataTables::of($mtype)
            ->addColumn('action', function ($mtype) {
                $activation_status = $mtype->status == 1 ? 'checked' : "";
                $html = '';
                $html .= ' <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#EditModal" onClick="getformelement('.$mtype->id.')" style="margin-bottom: 0px !important;"><i class="fa fa-pencil-square-o"></i> Edit</button>';
                $html .= ' <a href="'.route('stock.types.remove',$mtype->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
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
            return redirect()->route('stock.unit')->with('success', "Stock unit deleted");
        } else {
            return redirect()->route('stock.unit')->with('error', "Ooops..! Something went wrong");
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
            return redirect()->route('stock.unit')->with('success', "Stock unit updated");
        } else {
            return redirect()->route('stock.unit')->with('error', "Ooops..! Something went wrong");
        }
    }

}
