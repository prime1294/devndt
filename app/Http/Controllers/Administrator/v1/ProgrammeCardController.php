<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\PCReceive;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Admin;
use Sentinel;
use App\User;
use App\Model\ProgrammeCard;
use App\Model\PCDesgin;
use App\Model\PCDesignItem;
use App\Model\Stock;
use App\Model\StockCategory;
use App\Model\StockUnit;
use App\Model\Party;
use App\Model\StockItem;
use App\Model\Design;
use Barryvdh\DomPDF\PDF;

class ProgrammeCardController extends Controller
{
  public function __construct() {

  }

  public function programmeCard() {
    $user = Sentinel::getUser();
      $data['stock_list'] = StockItem::where('pending','!=',0)->where('user_id',$user->id)->where('status',1)->orderBy('id','DESC')->get();
    return view('admin.v1.pcard.list',$data);
  }

  public function verifyStockNumber(Request $request) {
    $user = Sentinel::getUser();
    $request->session()->put('unique_pc_id', Admin::uniquePCId($user->id));
    $data['stock_item'] = StockItem::where('user_id',$user->id)->orderBy('id','DESC')->get();
    $data['pc_item'] = ProgrammeCard::where('user_id',$user->id)->orderBy('id','DESC')->get();
    return view('admin.v1.pcard.verify',$data);
  }

  public function editPC(Request $request,$id) {
    $user = Sentinel::getUser();
    $pc_info = ProgrammeCard::where('id',$id)->where('user_id',$user->id)->first();
    if($pc_info) {
      $request->session()->put('unique_pc_id', $pc_info->pc_unique_number);
      if($pc_info->stock_id != 0) {
        return redirect()->route('add.programme.card',$pc_info->stock_id);
      } else {
        return redirect()->route('add.programme.card');
      }
    } else {
      return Admin::unauth();
    }
  }

  private function validatePCId($request) {
    if(!$request->session()->has('unique_pc_id')) {
        return Admin::unauth();
    }
  }

  function getStockItemInfo($stock_id) {
    return StockItem::find($stock_id);
  }

  function getRecivedCountColor($id) {
      $user = Sentinel::getUser();
    $received =  PCReceive::where('pc_unique_number',$id)->where('user_id',$user->id)->sum('qty');
    $total = PCDesignItem::where('pc_unique_number',$id)->where('user_id',$user->id)->sum('quantity');
    if($total == $received) {
        return true;
    } else {
        return false;
    }
  }

  public function getPCAjax(Request $request) {
    $user = Sentinel::getUser();
    $item = ProgrammeCard::query();
    $item->select('programme_card.*','stock_unit.name as mesurement_name');
    $item->leftjoin('stock_unit','programme_card.unit_id','=','stock_unit.id');
    $item->where('programme_card.user_id',$user->id);
    $item->where('programme_card.status',1);
      if(request('bill_no')) {
          $item->where('programme_card.pc_name','like','%'.request('bill_no').'%');
      }
      if(request('design_name')) {
          $item->where('programme_card.dname','like','%'.request('design_name').'%');
      }
      if(request('stock_no')) {
          $item->where('programme_card.stock_id',request('stock_no'));
      }
      if(request('startdate') && request('enddate')) {
          if(request('startdate') != "" && request('enddate') != "") {
              $item->whereBetween('programme_card.date',[request('startdate'), request('enddate')]);
          }
      }
    $item->groupBy('programme_card.id','pc_unique_number');
    $item->orderBy('programme_card.id','DESC');

    return DataTables::of($item)
    ->addColumn('pc_unique_number_info', function ($item) {
        $html = '';
        $color = self::getRecivedCountColor($item->pc_unique_number);
        $color_name = $color ? "bg-green" : "bg-red";
        $html .= Admin::FormateDate($item->date).'<br><span class="text-muted label '.$color_name.'">'.$item->pc_name.'</span>' ;
        return $html;
    })
    ->addColumn('formated_stock', function ($item) {
        $html = '';
        if($item->stock_id) {
            $html .= Admin::FormateStockItemID($item->stock_id);
        } else {
            $html .= '-';
        }
        return $html;
    })
    ->addColumn('designlineinfo', function ($item) {
        $html = '<div class="row">';
        $designinfo = self::designLineInfo($item->pc_unique_number)->get();
        $toEnd = count($designinfo);
        foreach($designinfo as $row) {
          $html .= '<div class="col-md-2 col-xs-2">';
          $html .= $row->total_quantity.' '.$item->mesurement_name;
          $html .= '</div>';
          $html .= '<div class="col-md-2 col-xs-2">';
          $html .= '<img src="'.asset($row->image).'" alt="'.$row->name.'" class="img-responsive img-rounded" style="width:30px;">';
          $html .= '</div>';
          $html .= '<div class="col-md-2 col-xs-2">';
          $html .= $row->category;
          $html .= '</div>';
          $html .= '<div class="col-md-4 col-xs-4">';
          $html .= $row->name;
          $html .= '</div>';
            $html .= '<div class="col-md-2 col-xs-2">';
            $html .= '<a href="'.route('receive.pc.stock',["id"=>$row->id]).'" class="btn btn-success btn-xs">Receive</a>';
            $html .= '</div>';
          if (0 !== --$toEnd) {
          $html .= '<div class="col-md-12 col-xs-12"><div class="row-spliter"></div></div>';
          }
        }
        $html .= '</div>';
        return $html;
    })
    ->addColumn('action', function ($item) {
        $html = '';
        $html .= ' <a href="'.route('pc.edit',["id"=>$item->id]).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        $html .= ' <a href="'.route('pc.pdf',["id"=>$item->id]).'" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-file-pdf-o"></i> Preview</a>';
        $html .= ' <a href="'.route('pc.remove',["id"=>$item->id]).'" class="btn btn-danger btn-xs" onclick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        return $html;
    })
    ->rawColumns(['pc_unique_number_info','pc_quantity','formated_stock','designlineinfo','action'])
    ->make(true);;
  }

  public function receiveStock(Request $request,$id) {
      $user = Sentinel::getUser();
      $data['info'] = $info = PCDesgin::find($id);
      $data['settlement_list'] = PCReceive::where('user_id',$user->id)->where('design_tbl_id',$id)->get();
      $data['validation_amount'] = PCDesignItem::where('user_id',$user->id)->where('design_tbl_id',$id)->sum('quantity');
      return view('admin.v1.pcard.receive',$data);
  }

  public function registerReceiveStock(Request $request,$id) {
      $user = Sentinel::getUser();
      $validator = Validator::make($request->all(), [
          "measurement"    => "required|array|min:1",
      ]);

      if ($validator->fails()) {
          $errors = $validator->errors();
          return back()->with('error', $errors->first());
      }

      //collect all old total
      $settlement = PCReceive::where('user_id',$user->id)->where('design_tbl_id',$id);
      //remove all old data
      $settlement->forceDelete();

      //get programme card id
      $info = PCDesgin::find($id);
      $pc_number = $info->pc_unique_number;

      //reinsert data
      $response = true;
      $adjustment = request('adjustment_date');
      $type = request('type');
      $measurement = request('measurement');
      $remarks = request('remarks');
      foreach($adjustment as $key=>$row) {
          if($row != "" && $measurement[$key] != "") {
              $ins['user_id'] = $user->id;
              $ins['pc_unique_number'] = $pc_number;
              $ins['design_tbl_id'] = $id;
              $ins['date'] = date('Y-m-d',strtotime($row));
              $ins['type'] = $type[$key];
              $ins['qty'] = $measurement[$key];
              $ins['remarks'] = $remarks[$key];
              $response = PCReceive::insertGetId($ins);
          }
      }

      if($response) {
          return redirect()->route('programme.card')->with('success', "Programme Card Received Successfully");
      } else {
          return redirect()->route('programme.card')->with('error', "Ooops..! Something went wrong");
      }

  }


  function designLineInfo($pc_unique_id) {
    $user = Sentinel::getUser();
    $result = PCDesgin::select('programme_card_design.*','design.image','design.name','design.stitch','design.area',DB::raw('SUM(programme_card_item.quantity) as total_quantity'))
    ->leftjoin('design','programme_card_design.design_id','=','design.id')
    ->leftjoin('programme_card_item','programme_card_design.id','=','programme_card_item.design_tbl_id')
    ->where('programme_card_design.pc_unique_number',$pc_unique_id)
    ->where('programme_card_design.user_id',$user->id)
    ->where('programme_card_design.status',1)
    ->groupBy('programme_card_design.id')
    ->orderBy('programme_card_design.id','ASC');

    return $result;
  }

  public function getPCDesign(Request $request) {
    $user = Sentinel::getUser();
    $item = self::designLineInfo(request('pc_unique_id'));

    return DataTables::of($item)
    ->addColumn('desing_info', function ($item) {
        $html = '';
        $html .= '<img src="'.asset($item->image).'" alt="'.$item->name.'" class="img-responsive" style="display:inline-block;vertical-align:top;" width="40"> <span style="display: inline-block;margin-left:5px;">'.$item->name.'</span>';
        return $html;
    })
    ->addColumn('desing_total', function ($item) {
        $html = '';
        $html .= $item->total_quantity;
        return $html;
    })
    ->addColumn('action', function ($item) {
        $html = '';
        $html .= ' <a class="btn btn-primary btn-xs editstockproductbtn" data-id="'.$item->id.'"><i class="fa fa-edit"></i> Edit</a>';
        $html .= ' <a onClick="return removeproduct('.$item->id.')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a>';
        return $html;
    })
    ->rawColumns(['desing_info','action'])
    ->make(true);
  }

  public function makeDuplicatePc(Request $request,$id) {
    $user = Sentinel::getUser();
    self::validatePCId($request);
    $data['status'] = "false";
    $data['message'] = "Ooops..! Something went wrong";
    $unique_pc_id = $request->session()->get('unique_pc_id');
    $maincard = $info = ProgrammeCard::find($id)->toArray();

    if(!empty($maincard)) {
        if(request('stock_id')) {
          $info['stock_id'] = request('stock_id');
        } else {
          $info['stock_id'] = 0;
        }
        $info['pc_unique_number'] = $unique_pc_id;
        $info['pc_name'] = self::getProgrammeCardUniqueNumber();
        unset($info['id']);
        unset($info['status']);
        unset($info['created_at']);
        unset($info['updated_at']);
        unset($info['deleted_at']);

        //get all programme card design
        $response = PCDesgin::where('user_id',$user->id)->where('pc_unique_number',$maincard['pc_unique_number'])->get()->toArray();
        $pcmainid = ProgrammeCard::insertGetId($info);
        foreach($response as $row) {
          //get all child items
          $designitem = PCDesignItem::where('design_tbl_id',$row['id'])->where('pc_unique_number',$maincard['pc_unique_number'])->where('user_id',$user->id)->get()->toArray();
          unset($row['id']);
          unset($row['status']);
          unset($row['created_at']);
          unset($row['updated_at']);
          unset($row['deleted_at']);
          $row['pending'] = $row['total'];
          $row['pc_unique_number'] = $unique_pc_id;
          $designid = PCDesgin::insertGetId($row);

          foreach($designitem as $irow) {
            unset($irow['id']);
            unset($irow['status']);
            unset($irow['created_at']);
            unset($irow['updated_at']);
            unset($irow['deleted_at']);
            $irow['pc_unique_number'] = $unique_pc_id;
            $irow['design_tbl_id'] = $designid;
            PCDesignItem::insert($irow);
          }
      }
      $data['status'] = "true";
      $data['message'] = "success";
    } else {
      $data['message'] = "Programme Card Not Found";
    }
    return response()->json($data);
  }



  public function addProgrammeCard(Request $request,$id = null) {
    $user = Sentinel::getUser();
    // if($id != null) {
    //   $request->session()->put('unique_pc_id', Admin::uniquePCId($user->id,$id));
    // }
    self::validatePCId($request);
    $data['stock_category'] = StockCategory::where('status',1)->orderBy('name','ASC')->get();
    $data['stock_unit'] = StockUnit::where('status',1)->orderBy('name','ASC')->get();
    $data['desgin_list'] = Design::where('user_id',$user->id)->where('type',1)->where('status',1)->orderBy('id','DESC')->get();
    $data['unique_pc_id'] =  $request->session()->get('unique_pc_id');
    $data['main_stock_id'] = $id != null ? $id : 0;
    $data['pc_info'] = ProgrammeCard::where('pc_unique_number',$data['unique_pc_id'])->where('user_id',$user->id)->first();
    $pcdesignitem = PCDesignItem::where('user_id',$user->id)->where('pc_unique_number',$request->session()->get('unique_pc_id'))->get();
    $data['pre_added_design'] = collect($pcdesignitem)->count();
    $data['filled_total_quantity'] = collect($pcdesignitem)->sum('quantity');
    if($id != null)
    {
      if(!StockItem::scopeHaveRightBank(StockItem::query(),$user->id,$id)) {
        return Admin::unauth();
      } else {
        $data['stock_item_info'] = StockItem::find($id);
      }
    }

    return view('admin.v1.pcard.new',$data);
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

    private function getProgrammeCardUniqueNumber() {
        $user = Sentinel::getUser();
        $max_id = ProgrammeCard::where('user_id',$user->id)->count();
        $final =  $max_id + 1;
        return config('setting.programmecard_prefix').$final;
    }

  public function registerProgrammeCard(Request $request) {
    $user = Sentinel::getUser();
    self::validatePCId($request);
    $data['status'] = "false";
    $validator = Validator::make($request->all(), [
        'date' => 'required',
        'unit' => 'required',
        "main_stock_id"    => "required",
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      $data['message'] = $errors->first();
      return response()->json($data);
    }

    $pc = ProgrammeCard::firstOrNew(array('pc_unique_number' => $request->session()->get('unique_pc_id'),'user_id'=>$user->id));
    $pc->stock_id = request('main_stock_id');
    if($pc->pc_name == "") {
        $pc->pc_name = self::getProgrammeCardUniqueNumber();
    }
    $pc->date = date('Y-m-d h:i:s',strtotime(request('date')));
    $pc->unit_id = request('unit');
    $pc->dname = request('txt_design_name');
    $response = $pc->save();

    $data['status'] = "true";
    $data['message'] = "success";
    return response()->json($data);
  }

  public function updatePCQuantity(Request $request,$id) {
    $user = Sentinel::getUser();
    self::validatePCId($request);
    $data['status'] = "false";
    $validator = Validator::make($request->all(), [
        'design_id' => 'required|exists:design,id',
        'design_code' => 'required',
        'stitch' => 'required',
        "design_area" => "required",
        "category" => "required",
//        "color" => "required|array|min:1",
//        "color.*"  => "required|min:1",
        "qty" => "required|array|min:1",
        "qty.*"  => "required|min:1",
        "color1" => "required|array|min:1",
        "color1.*"  => "required|min:1",
//        "color2" => "required|array|min:1",
//        "color2.*"  => "required|min:1",
//        "color3" => "required|array|min:1",
//        "color3.*"  => "required|min:1",
//        "color4" => "required|array|min:1",
//        "color4.*"  => "required|min:1",
//        "color5" => "required|array|min:1",
//        "color5.*"  => "required|min:1",
//        "color6" => "required|array|min:1",
//        "color6.*"  => "required|min:1",
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      $data['message'] = $errors->first();
      return response()->json($data);
    }

    $update['design_id'] = request('design_id');
    $update['category'] = request('category');
    $update['working_type'] = request('working_type');
    $update['pendrive_design_number'] = request('pendrive_design_number');

    $mainstock = PCDesgin::where('id',$id)->where('user_id',$user->id)->where('pc_unique_number',$request->session()->get('unique_pc_id'))->update($update);
    $mainstock = ProgrammeCard::where('user_id',$user->id)->where('pc_unique_number',$request->session()->get('unique_pc_id'))->first();
    if($mainstock) {
          $rmstatus = PCDesignItem::where('user_id',$user->id)->where('pc_unique_number',$request->session()->get('unique_pc_id'))->where('design_tbl_id',$id)->forceDelete();

          $color_arr = request('color');
          $qty_arr = request('qty');
          $color1_arr = request('color1');
          $color2_arr = request('color2');
          $color3_arr = request('color3');
          $color4_arr = request('color4');
          $color5_arr = request('color5');
          $color6_arr = request('color6');

          foreach($qty_arr as $key=>$row) {
            if($row != "") {
              $ins2['design_tbl_id'] = $id;
              $ins2['user_id'] = $user->id;
              $ins2['pc_unique_number'] = $request->session()->get('unique_pc_id');
              $ins2['color'] = $color_arr[$key];
              $ins2['quantity'] = $row;
              $ins2['n1'] = $color1_arr[$key];
              $ins2['n2'] = $color2_arr[$key];
              $ins2['n3'] = $color3_arr[$key];
              $ins2['n4'] = $color4_arr[$key];
              $ins2['n5'] = $color5_arr[$key];
              $ins2['n6'] = $color6_arr[$key];

              PCDesignItem::insert($ins2);
            }
          }
          $data['status'] = "true";
          $data['message'] = "success";
    } else {
      $data['message'] = "Ooops..! Something went wrong";
    }

    return response()->json($data);

  }

  public function registerPcDesign(Request $request) {
    // print_r($_POST);
    $user = Sentinel::getUser();
    self::validatePCId($request);
    $data['status'] = "false";
    $validator = Validator::make($request->all(), [
        'design_id' => 'required|exists:design,id',
        'design_code' => 'required',
        'stitch' => 'required',
        "design_area" => "required",
//        "category" => "required",
//        "color" => "required|array|min:1",
//        "color.*"  => "required|min:1",
        "qty" => "required|array|min:1",
        "qty.*"  => "required|min:1",
        "color1" => "required|array|min:1",
        "color1.*"  => "required|min:1",
//        "color2" => "required|array|min:1",
//        "color2.*"  => "required|min:1",
//        "color3" => "required|array|min:1",
//        "color3.*"  => "required|min:1",
//        "color4" => "required|array|min:1",
//        "color4.*"  => "required|min:1",
//        "color5" => "required|array|min:1",
//        "color5.*"  => "required|min:1",
//        "color6" => "required|array|min:1",
//        "color6.*"  => "required|min:1",
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      $data['message'] = $errors->first();
      return response()->json($data);
    }

    $ins['user_id'] = $user->id;
    $ins['pc_unique_number'] = $request->session()->get('unique_pc_id');
    $ins['design_id'] = request('design_id');
    $ins['category'] = request('category');
    $ins['working_type'] = request('working_type');
    $ins['pendrive_design_number'] = request('pendrive_design_number');

    $mainstock = ProgrammeCard::where('user_id',$user->id)->where('pc_unique_number',$request->session()->get('unique_pc_id'))->first();
    if($mainstock) {
        $response = PCDesgin::insertGetId($ins);
        if($ins) {
          $color_arr = request('color');
          $qty_arr = request('qty');
          $color1_arr = request('color1');
          $color2_arr = request('color2');
          $color3_arr = request('color3');
          $color4_arr = request('color4');
          $color5_arr = request('color5');
          $color6_arr = request('color6');

          foreach($qty_arr as $key=>$row) {
            if($row != "") {
              $ins2['design_tbl_id'] = $response;
              $ins2['user_id'] = $user->id;
              $ins2['pc_unique_number'] = $request->session()->get('unique_pc_id');
              $ins2['color'] = $color_arr[$key];
              $ins2['quantity'] = $row;
              $ins2['n1'] = $color1_arr[$key];
              $ins2['n2'] = $color2_arr[$key];
              $ins2['n3'] = $color3_arr[$key];
              $ins2['n4'] = $color4_arr[$key];
              $ins2['n5'] = $color5_arr[$key];
              $ins2['n6'] = $color6_arr[$key];

              PCDesignItem::insert($ins2);
            }
          }

          $data['status'] = "true";
          $data['message'] = "success";
        } else {
          $data['message'] = "Ooops..! Something went wrong";
        }

    } else {
      $data['message'] = "Ooops..! Something went wrong";
    }

    return response()->json($data);
  }


  public function removePC(Request $request,$id) {
    $user = Sentinel::getUser();
    $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:programme_card,id']);
    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }
    $data['status'] = "false";
    $data['message'] = "Ooops..! Something went wrong";
    if(ProgrammeCard::scopeHaveRightBank(ProgrammeCard::query(),$user->id,$id)) {
      $pcinfo = ProgrammeCard::find($id);
      $product =  PCDesgin::where('pc_unique_number',$pcinfo->pc_unique_number)->delete();
      $items =  PCDesignItem::where('pc_unique_number',$pcinfo->pc_unique_number)->delete();
      $response = $pcinfo->delete();

      if($response) {
        return redirect()->route('programme.card')->with('success', "Programme Card Removed Successfully");;
      } else {
        return redirect()->route('programme.card')->with('error', "Ooops..! Something went wrong");
      }

    } else {
      return Admin::unauth();
    }
  }

  public function removePCDesign(Request $request,$id) {
    $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:programme_card_design,id']);
    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $data['status'] = "false";
    $data['message'] = "Ooops..! Something went wrong";

    $product =  PCDesgin::find($id)->forceDelete();
    $items =  PCDesignItem::where('design_tbl_id',$id)->forceDelete();
    if($product) {
      $data['status'] = "true";
      $data['message'] = "success";
    }

    return response()->json($data);
  }

  public function getTotalofInserted(Request $request) {
    $user = Sentinel::getUser();
    self::validatePCId($request);
    $data['status'] = "true";
    $data['message'] = "success";
    $filled_qunatity = PCDesignItem::where('user_id',$user->id)->where('pc_unique_number',$request->session()->get('unique_pc_id'))->get();
    $data['count'] = collect($filled_qunatity)->sum('quantity');
    return response()->json($data);
  }

  public function getPCItemInfo(Request $request,$id) {
    $user = Sentinel::getUser();
    $info = PCDesgin::find($id);
    $info['quantity'] = PCDesignItem::where('design_tbl_id',$info->id)->get();
    $info['design'] = Design::find($info->design_id);
    $data['result'] = $info;
    $data['message'] = "success";
    $data['status'] = "true";
    return response()->json($data);
  }

  public function downloadpdf(Request $request,$id) {
      $user = Sentinel::getUser();
      $info = $data['info'] = ProgrammeCard::select('programme_card.*','stock_unit.name as mesurement_name')
      ->leftjoin('stock_unit','programme_card.unit_id','=','stock_unit.id')
      ->where('programme_card.user_id',$user->id)
      ->where('programme_card.id',$id)
      ->groupBy('programme_card.id')
      ->first();
      if($info) {
          echo "Invalid PDF request";
      }
      $parent = [];
      $design_list = PCDesgin::select('programme_card_design.*','design.image','design.name','design.color','design.stitch','design.area',DB::raw('SUM(programme_card_item.quantity) as total_quantity'))
          ->leftjoin('design','programme_card_design.design_id','=','design.id')
          ->leftjoin('programme_card_item','programme_card_design.id','=','programme_card_item.design_tbl_id')
          ->where('programme_card_design.pc_unique_number',$info->pc_unique_number)
          ->where('programme_card_design.user_id',$user->id)
          ->groupBy('programme_card_design.id')
          ->orderBy('programme_card_design.id','ASC')
          ->get()->toArray();
      foreach($design_list as $row) {
        $quantity_list = PCDesignItem::where('design_tbl_id',$row['id'])->where('user_id',$user->id)->get()->toArray();
        $row['item_list'] = $quantity_list;
        array_push($parent,$row);
      }
      $data['parent_list'] = $parent;
//      print_r($parent); die();
//      return view('admin.v1.pcard.pdf',$data); die();
      $pdf = App::make('dompdf.wrapper');
      $pdf->loadView('admin.v1.pcard.pdf', $data);
      return $pdf->stream('programme-card-no-'.$info->id.'.pdf');
  }

}
