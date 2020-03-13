<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\BanksUser;
use App\Model\BankTransection;
use App\Model\CashTransection;
use App\Model\ChequeTransection;
use App\Model\City;
use App\Model\KarigarPayment;
use App\Model\ProcessPayment;
use App\Model\ProcessReceive;
use App\Model\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Admin;
use Sentinel;
use App\User;
use App\Model\Process;
use App\Model\ProcessType;
use App\Model\Banks;
use App\Model\StockItem;
use App\Model\StockUnit;
use App\Model\StockProcess;
use App\Model\StockProcessItem;

class ProcessController extends Controller
{
  public function __construct() {

  }

  public function process(Request $request)
  {
      return view('admin.v1.process.list');
  }

  public function processNew(Request $request)
  {
      $data['type'] = 'add';
      $data['pinfo'] = [];
      $data['bank_list'] = Banks::orderBy('name','ASC')->get();
      $data['manufacturer'] = ProcessType::select('id','name')->where('status',1)->get();
      $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
      return view('admin.v1.process.new',$data);
  }

  private function processList($column = 'process.id',$order = 'DESC') {
    $user = Sentinel::getUser();
    return Process::select('process.*',DB::raw('GROUP_CONCAT(process_type.name) as mname'),'states.state as mystate','cities.city as mycity')
    ->leftjoin('process_type', DB::raw('FIND_IN_SET(`process_type`.`id`,`process`.`types_of_menufecture`)'),'<>',DB::raw('"0"'))
    ->leftjoin('states','process.state','=','states.state_id')
    ->leftjoin('cities','process.city','=','cities.city_id')
    ->where('process.user_id',$user->id)
    ->where('process.status',1)
    ->groupBy('process.id')
    ->orderBy($column,$order);
  }

  public function getProcessJson(Request $request) {
    $process = self::processList()->where('process.status',1)->get();
    return response()->json($process);
  }

  public function getProcess(Request $request)  {
    $user = Sentinel::getUser();

    //order logic
    if(request('columns')[0]['orderable'] == "true" && isset(request('order')[0]['dir'])) {
      $order = request('order')[0]['dir'];
      $column = 'process.name';
      $process = self::processList($column,$order);
    } else {
      $process = self::processList();
    }

    return DataTables::of($process)
    ->addColumn('party', function ($process) {
        $html = '';
        $html .= '<img src="'.asset($process->photo).'" alt="'.$process->name.'" class="img-circle img-responsive" style="display:inline-block;vertical-align:top;" width="40"> <span style="display: inline-block;margin-left:5px;">'.$process->name.'<br><span class="text-muted">'.$process->business_name.'</span></span>';
        return $html;
    })
    ->addColumn('transection_amount', function ($party) use ($user) {
        $html = '';
        $search['table'] = 'process';
        $search['user_id'] = $user->id;
        $search['id'] = $party->id;
        $search['master_type'] = 'master6';

        $amount = DB::select(Admin::masterTransectionQuery($search));
        $html .= Admin::FormateTransection(collect($amount)->sum('transection_amount'));
        return $html;
    })
    ->addColumn('types_of_menu', function ($party) {
        $html = '';
        $html .= self::getTypesOfMenufecturer($party->types_of_menufecture);
        return $html;
    })
    // ->addColumn('manufacturer', function ($process) {
    //     $html = '';
    //     $html .= $process->mname;
    //     return $html;
    // })
    ->addColumn('gstininfo', function ($process) {
        $html = '';
        if (($process->mycity || $process->mystate) || $process->gstin_no) {
            $html .= $process->gstin_no . '<br><span class="text-muted">' . $process->mycity . ', ' . $process->mystate . '</span>';
         }
        return $html;
    })
    ->addColumn('contactinfo', function ($process) {
        $html = '';
        $html .= $process->mobile.'<br><span class="text-muted">'.$process->alt_mobile.'</span>';
        return $html;
    })
    ->addColumn('action', function ($process) {
        //$activation_status = $process->status == 1 ? 'checked' : "";
        $html = '';
        //$html .= '<input type="checkbox" class="status_checkbox" data-id=" '.$process->id.'" '.$activation_status.' data-size="mini" data-toggle="toggle" data-on="Active" data-off="Deactive" data-onstyle="success" data-offstyle="danger">';
        $html .= ' <a href="'.route('process.edit',$process->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        $html .= ' <a href="'.route('process.remove',$process->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        return $html;
    })
    ->filterColumn('party', function($query, $keyword) {
       $query->whereRaw("CONCAT(process.name,' ',process.business_name) like ?", ["%{$keyword}%"]);
    })
    // ->filterColumn('manufacturer', function($query, $keyword) {
    //    $query->whereRaw("process_type.name like ?", ["%{$keyword}%"]);
    // })
    ->filterColumn('gstininfo', function($query, $keyword) {
       $query->whereRaw("CONCAT(states.state,', ',cities.city,' ',process.gstin_no) like ?", ["%{$keyword}%"]);
    })
    ->filterColumn('contactinfo', function($query, $keyword) {
       $query->whereRaw("CONCAT(process.mobile,' ',process.alt_mobile) like ?", ["%{$keyword}%"]);
    })
    ->rawColumns(['party','transection_amount','gstininfo','contactinfo', 'action'])
    ->make(true);
  }

  public function ActivationProcess(Request $request) {
    $party = Process::find(request('id'));
    $party->status = request('status');
    $party->save();
    return $party;
  }

  public function removeProcess(Request $request,$id)
  {
    $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:process,id']);
    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

//    $user =  Process::find($id)->delete();
      $user =  Process::find($id);
      $user->status = 0;
      $user->save();
    if($user) {
      return redirect()->route('process')->with('success', "Process Removed Successfully");
    } else {
      return redirect()->route('process')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function editProcess(Request $request,$id) {
    $data['type'] = 'edit';
    $data['pinfo'] = Process::find($id);
    $data['bank_list'] = Banks::orderBy('name','ASC')->get();
    $data['manufacturer'] = ProcessType::select('id','name')->where('status',1)->get();
    $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
    return view('admin.v1.process.new',$data);
  }

  public function updateProcess(Request $request,$id) {
    $validator = Validator::make($request->all(), [
        'person_name' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    $update = [
      'business_name' => request('party_name'),
      'name' => request('person_name'),
      'types_of_menufecture' => request('type_of_manufacturer') ? implode(',',request('type_of_manufacturer')) : NULL,
      'gstin_no' => request('gstno'),
      'email' => request('email'),
      'mobile' => request('mobile'),
      'alt_mobile' => request('alt_no'),
      'address' => request('address'),
      'country' => '101',
      'state' => request('state'),
      'city' => request('city'),
      'pincode' => request('pincode'),
      'is_bank_detail' => request('confirmAns') != null ? request('confirmAns') : 0,
      'bank_person_name' => request('bank_person_name'),
      'account_number' => request('bank_account_no'),
      'bank_name' => request('bank_name'),
      'account_type' => request('account_type'),
      'ifsc_code' => request('bank_ifsc'),
      'branch' => request('bank_branch'),
      'opening_balance' => request('opening_balance') != null ? request('opening_balance') : 0,
      'opening_type' => request('opening_type'),
      'opening_asof' => request('asof') != null ? date('Y-m-d',strtotime(request('asof'))) : date('Y-m-d',strtotime("now")),
      'remarks' => request('remarks'),
    ];

      if (request('fbinputtxt')) {
          $update['photo'] = request('fbinputtxt');
      }

    $request = Process::where('id',$id)->update($update);
    if($request) {
      return redirect()->route('process')->with('success', "Process updated Successfully");
    } else {
      return redirect()->route('process')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function registerProcess(Request $request) {
    $validator = Validator::make($request->all(), [
        'person_name' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }


    $user = Sentinel::getUser();
    $response = Process::insert([
      'user_id' => $user->id,
      'business_name' => request('party_name'),
      'name' => request('person_name'),
      'types_of_menufecture' => request('type_of_manufacturer') ? implode(',',request('type_of_manufacturer')) : NULL,
      'gstin_no' => request('gstno'),
      'photo' => request('fbinputtxt') ? request('fbinputtxt') : 'user_404.jpg',
      'email' => request('email'),
      'mobile' => request('mobile'),
      'alt_mobile' => request('alt_no'),
      'address' => request('address'),
      'country' => '101',
      'state' => request('state'),
      'city' => request('city'),
      'pincode' => request('pincode'),
      'is_bank_detail' => request('confirmAns') != null ? request('confirmAns') : 0,
      'bank_person_name' => request('bank_person_name'),
      'account_number' => request('bank_account_no'),
      'bank_name' => request('bank_name'),
      'account_type' => request('account_type'),
      'ifsc_code' => request('bank_ifsc'),
      'branch' => request('bank_branch'),
      'opening_balance' => request('opening_balance') != null ? request('opening_balance') : 0,
      'opening_type' => request('opening_type'),
      'opening_asof' => request('asof') != null ? date('Y-m-d',strtotime(request('asof'))) : date('Y-m-d',strtotime("now")),
      'remarks' => request('remarks'),
    ]);

    if($response) {
      return Admin::checkRedirect($request,'process',"Process registred Successfully");
    } else {
      return redirect()->route('process')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function processView(Request $request,$id) {
    $user = Sentinel::getUser();
    if(Process::HaveRightBank($user->id,$id)) {
      $data['info'] = Process::find($id);

      //total
      $search['table'] = 'process';
      $search['user_id'] = $user->id;
      $search['id'] = $id;
      $search['master_type'] = 'master6';
      $amount = DB::select(Admin::masterTransectionQuery($search));
      $data['total_amount'] = collect($amount)->sum('transection_amount');

      return view('admin.v1.process.view',$data);
    } else {
      return Admin::unauth();
    }
  }

  public function addNewProcess(Request $request) {
    $user = Sentinel::getUser();
    $data['bank_list'] = self::myBanks();
    $data['process_list'] = Process::where('user_id',$user->id)->where('status',1)->get();
    $data['manufacturer'] = ProcessType::select('id','name')->where('status',1)->get();
    $data['stock_list'] = StockItem::where('pending','!=',0)->where('user_id',$user->id)->where('status',1)->orderBy('id','DESC')->get();
    $data['category_list'] = StockUnit::where('status',1)->orderBy('name','ASC')->get();
    $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
    return view('admin.v1.process.new_process',$data);
  }

  public function editStockProcess(Request $request,$id) {
    $user = Sentinel::getUser();
    $data['bank_list'] = self::myBanks();
    $data['info'] = StockProcess::where('id',$id)->where('user_id',$user->id)->first();
    $data['process_item'] = StockProcessItem::where('stock_process_id',$id)->where('user_id',$user->id)->get();
    $data['process_payment'] = ProcessPayment::where('user_id',$user->id)->where('process_id',$id)->get();
    $data['process_list'] = Process::where('user_id',$user->id)->where('status',1)->get();
    $data['manufacturer'] = ProcessType::select('id','name')->where('status',1)->get();
    $data['stock_list'] = StockItem::where('pending','!=',0)->where('user_id',$user->id)->where('status',1)->orderBy('id','DESC')->get();
    $data['category_list'] = StockUnit::where('status',1)->orderBy('name','ASC')->get();
    $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
    return view('admin.v1.process.edit_process',$data);
  }

  public function viewAllProcess(Request $request) {
    $user = Sentinel::getUser();
    $data['process_list'] = Process::where('user_id',$user->id)->where('status',1)->get();
    $data['stock_list'] = StockItem::where('pending','!=',0)->where('user_id',$user->id)->where('status',1)->orderBy('id','DESC')->get();
    return view('admin.v1.process.view_process',$data);
  }

    public function getTypesOfMenufecturer($types) {
        if($types != "") {
            $types = explode(',',$types);
            $mtype = ProcessType::whereIn('id',$types)->get();
            $result = [];
            foreach($mtype as $row) {
                $result[] = $row->name;
            }
            return implode(',',$result);
        } else {
            return "";
        }
    }

    private function getstockitemList($id) {
        $user = Sentinel::getUser();
        $result = StockProcessItem::select('stock_process_iem.*','stock_unit.name as mesurement_name')
            ->leftjoin('stock_unit','stock_process_iem.mesurement','=','stock_unit.id')
            ->where('stock_process_iem.stock_process_id',$id)
            ->where('stock_process_iem.user_id',$user->id)
            ->where('stock_process_iem.status',1)
            ->groupBy('stock_process_iem.id')
            ->orderBy('stock_process_iem.id','ASC');
        return $result;
    }

    function getRecivedCountColor($id) {
        $user = Sentinel::getUser();
        $received =  ProcessReceive::where('process_id',$id)->where('user_id',$user->id)->sum('qty');
        $total = StockProcessItem::where('stock_process_id',$id)->where('user_id',$user->id)->sum('quantity');
        if(round($total,2) == round($received,2)) {
            return true;
        } else {
            return false;
        }
    }

    public function receiveStock(Request $request,$id) {
        $user = Sentinel::getUser();
        $data['info'] = $info = StockProcessItem::find($id);
        $data['settlement_list'] = ProcessReceive::where('user_id',$user->id)->where('process_item_id',$id)->get();
        $data['validation_amount'] = $info->quantity;
        return view('admin.v1.process.receive',$data);
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
        $settlement = ProcessReceive::where('user_id',$user->id)->where('process_item_id',$id);
        //remove all old data
        $settlement->forceDelete();

        //get programme card id
        $info = StockProcessItem::find($id);
        $pc_number = $info->stock_process_id;


        //reinsert data
        $response = true;
        $flag = 0;
        $adjustment = request('adjustment_date');
        $type = request('type');
        $measurement = request('measurement');
        $remarks = request('remarks');
        foreach($adjustment as $key=>$row) {
            if($row != "" && $measurement[$key] != "") {
                $flag = 1;
                $ins['user_id'] = $user->id;
                $ins['process_id'] = $pc_number;
                $ins['process_item_id'] = $id;
                $ins['date'] = date('Y-m-d',strtotime($row));
                $ins['type'] = $type[$key];
                $ins['qty'] = $measurement[$key];
                $ins['remarks'] = $remarks[$key];
                $response = ProcessReceive::insertGetId($ins);
            }
        }

        if($flag) {
            $info->col_lock = 1;
        } else {
            $info->col_lock = 0;
        }
        $info->save();

        if($response) {
            return redirect()->route('view.all.process')->with('success', "Process Stock Received Successfully");
        } else {
            return redirect()->route('view.all.process')->with('error', "Ooops..! Something went wrong");
        }

    }

  public function viewAjaxProcess(Request $request) {
    $user = Sentinel::getUser();
    $process = StockProcess::query();
    $process->select('stock_process.*',DB::Raw('GROUP_CONCAT(DISTINCT process_type.name  ORDER BY process_type.id) as manufacturer'),DB::Raw('SUM(stock_process_iem.total) as sum_of_process'),'process.name as process_name','process.photo as process_photo');
    $process->leftjoin('process','stock_process.process_id','=','process.id');
    $process->leftjoin('stock_process_iem','stock_process.id','=','stock_process_iem.stock_process_id');
    $process->join('process_type',DB::raw('FIND_IN_SET(process_type.id, stock_process.manufacturer_type)'),'>',DB::raw('"0"'));
    $process->where('stock_process.status',1);
    $process->where('stock_process_iem.status',1);

      if(request('bill_no')) {
          $process->where('stock_process.pname','like','%'.request('bill_no').'%');
      }
      if(request('filter_by')) {
          $process->where('stock_process.process_id',request('filter_by'));
      }
      if(request('stock_no')) {
          $process->where('stock_process_iem.stock_id',request('stock_no'));
      }
      if(request('design_name')) {
          $process->where('stock_process_iem.design_name','like','%'.request('design_name').'%');
      }

      if(request('startdate') && request('enddate')) {
          if(request('startdate') != "" && request('enddate') != "") {
              $process->whereBetween('stock_process.date',[request('startdate'), request('enddate')]);
          }
      }

    $process->groupBy('stock_process.id');
    $process->orderBy('stock_process.date','DESC');

    return DataTables::of($process)
    ->addColumn('formated_date', function ($process) {
      $html = '';
      $color = self::getRecivedCountColor($process->id);
      $color_name = $color ? "bg-green" : "bg-red";
      $html .= Admin::FormateDate($process->date);
      $html .= '<br><span class="text-muted label '.$color_name.'">'.Admin::FormatePRC($process->id).'</span>';
      return $html;
    })
    ->addColumn('formate_stock', function ($process) {
      $html = '';
        $stockitems = self::getstockitemList($process->id)->get();
        $toEnd = count($stockitems);
        foreach($stockitems as $key=>$row) {
            $received =  ProcessReceive::where('process_item_id',$row->id)->where('user_id',$process->user_id)->where('type',1)->sum('qty');
            $html .= '<div class="row">';
            $html .= '<div class="col-md-2 col-xs-2">';
            if($row->stock_id) {
                $html .= Admin::FormateStockItemID($row->stock_id);
            } else {
                $html .= '-';
            }
            $html .= '</div>';
            $html .= '<div class="col-md-3 col-xs-3">';
            $html .= $row->design_name != "" ? $row->design_name : "-";
            $html .= '</div>';
            $html .= '<div class="col-md-5 col-xs-5">';
            $html .= round($received,2).' / '.round($row->quantity,2)." ".$row->mesurement_name;
            $html .= '</div>';
            $html .= '<div class="col-md-2 col-xs-2">';
            $html .= '<a href="'.route('receive.process.stock',$row->id).'" class="btn btn-success btn-xs no-margin">Receive</a>';
            $html .= '</div>';
            $html .= "</div>";
            if (0 !== --$toEnd) {
                $html .= '<div class="row"><div class="col-md-12 col-xs-12"><div class="row-spliter"></div></div></div>';
            }
        }

      return $html;
    })
    ->addColumn('formated_process', function ($process) {
        $html = '';
        $html .= '<img src="'.asset($process->process_photo).'" alt="'.$process->process_name.'" class="img-circle img-responsive" style="display:inline-block;vertical-align:top;width:30px;"> <span style="display: inline-block;margin-left:5px;">'.$process->process_name.'<br><span class="text-muted">'.$process->manufacturer.'</span> </span>';
        return $html;
    })
    ->addColumn('formated_grand_total', function ($process) {
      $html = '';
      $html .= Admin::FormateTransection('-'.$process->final_total);
      return $html;
    })
    ->addColumn('formated_payment_total', function ($process) {
      $html = '';
      $html .= Admin::FormateTransection('-'.$process->grand_payment);
      return $html;
    })
    ->addColumn('formated_balance_total', function ($process) {
      $html = '';
      $total = floatval($process->grand_payment) - floatval($process->final_total);
      $html .= Admin::FormateTransection($total);
      return $html;
    })
    ->addColumn('action', function ($process) {
      $html = '';
      $html .= '<a href="'.route('edit.stock.process',$process->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
      $html .= ' <a href="'.route('process.pdf',$process->id).'" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-file-pdf-o"></i> Preview</a>';
      $html .= '  <a href="'.route('delete.stock.process',$process->id).'" onclick="return confirm(\'Are you sure want to delete this recrod?\')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a>';
      return $html;
    })
    ->rawColumns(['formated_date','formate_stock','formated_process','formated_grand_total','formated_payment_total','formated_balance_total','action'])
    ->make(true);
  }

  public function deleteProcessPaymentAll(Request $request,$id) {
      $user = Sentinel::getUser();
      $process_payment = ProcessPayment::where('user_id',$user->id)->where('process_id',$id);
      $process_payment_list = $process_payment->get();
      foreach($process_payment_list as $row) {
          $response = self::removeAllTrasection($row->tid,false);
      }
      $process_payment->delete();

      //delete stock item
      StockProcessItem::where('stock_process_id',$id)->where('user_id',$user->id)->delete();

      //delete self
      $response = StockProcess::where('id',$id)->where('user_id',$user->id)->delete();

      if($response) {
          return redirect()->route('view.all.process')->with('success', "Process deleted Successfully");
      } else {
          return redirect()->route('view.all.process')->with('error', "Ooops..! Something went wrong");
      }
  }

    public function deleteProcessPayment(Request $request,$id) {
        $user = Sentinel::getUser();
        $process_payment = ProcessPayment::where('user_id',$user->id)->where('id',$id)->first();

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


    public function newProcessUpdate(Request $request,$id) {
        $user = Sentinel::getUser();

        $validator = Validator::make($request->all(), [
            'adjustment_date' => 'required',
            "process_name"    => "required",
            //"stock_no"    => "required|array|min:1",
            //"stock_no.*"  => "required|min:1",
            //"quantity"    => "required|array|min:1",
            //"quantity.*"  => "required|min:1",
            //"mesurement"    => "required|array|min:1",
            //"mesurement.*"  => "required|min:1",
            //"unit"    => "required|array|min:1",
           // "unit.*"  => "required|min:1",
            //"total"    => "required|array|min:1",
            //"total.*"  => "required|min:1",
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }



        $ins['date'] = date('Y-m-d',strtotime(request('adjustment_date')));
        $ins['type'] = request('confirmAns') != "" ? 1 : 2;
        $ins['due_date'] = date('Y-m-d',strtotime(request('due_date')));
        $ins['process_id'] = request('process_name');
        $ins['manufacturer_type'] = request('type_of_manufacturer') ? implode(',',request('type_of_manufacturer')) : NULL;
        $ins['sample'] = request('upload_image_text');
        $ins['bill_photo'] = request('upload_image_text_bill');
        $ins['expected_date'] = date('Y-m-d',strtotime(request('expected_date')));
        $ins['remarks'] = request('remarks');
        $ins['process_state'] = request('process_state');
        $ins['process_transport'] = request('process_transport');
        $ins['less_amount'] = request('less_total');
        $ins['grand_total'] = request('grand_total');
        $ins['final_total'] = request('final_total');
        $update = StockProcess::where('id',$id)->where('user_id',$user->id)->update($ins);
        $stock_process_ins_id = $id;

        //remove all old stock id
        StockProcessItem::where('stock_process_id',$id)->where('user_id',$user->id)->where('col_lock',0)->forceDelete();

        //insert new one
        $stock_no = request('stock_no');
        $description = request('description');
        $design_name = request('design_name');
        $quantity = request('quantity') ? request('quantity') : [];
        $mesurement = request('mesurement');
        $unit = request('unit');
        $discount = request('discount');
        $discount_amount = request('discount_amount');
        $gst = request('gst');
        $gst_amount = request('gst_amount');
        $sub_total = request('total');
        foreach($quantity as $key=>$row) {
            if($quantity[$key] != "" && $unit[$key] != "" && $quantity[$key] != 0 && $unit[$key] != 0) {
                $child['user_id'] = $user->id;
                $child['stock_process_id'] = $stock_process_ins_id;
                $child['stock_id'] = $stock_no[$key] != "" ? $stock_no[$key] : 0;
                $child['description'] = $description[$key];
                $child['design_name'] = $design_name[$key];
                $child['quantity'] = $quantity[$key];
                $child['mesurement'] = $mesurement[$key];
                $child['rate'] = $unit[$key];
                $child['discount'] = $discount[$key];
                $child['discount_amount'] = $discount_amount[$key];
                $child['gst'] = $gst[$key];
                $child['gst_amount'] = $gst_amount[$key];
                $child['total'] = $sub_total[$key];
                StockProcessItem::insert($child);
            }
        }

        //remove all old payment
        $process_payment = ProcessPayment::where('user_id',$user->id)->where('process_id',$id);
        $old_payment = $process_payment->get();
        foreach($old_payment as $row) {
            self::removeAllTrasection($row->tid,true);
        }
        $process_payment->forceDelete();


        //insert new payment
        $info = Process::find(request('process_name'));
        $remarks = NULL;

        $sp_date = request('sp_date');
        $sp_by = request('sp_by');
        $sp_ref_no = request('sp_ref_no');
        $sp_amount = request('sp_amount');
        $sp_remarks = request('sp_remarks');
        $total_payment = 0;
        $response = $stock_process_ins_id;
        foreach($sp_amount as $key=>$row) {
            if ($sp_amount[$key] != "" && $sp_date[$key] != "" && $sp_by[$key] != "") {

                $tid = Admin::uniqueTransectionId("PROCESS",$user->id);
                $payment_type = $sp_by[$key];
                $total_payment += $sp_amount[$key];
                $new_date = date('Y-m-d h:i:s',strtotime($sp_date[$key]));

                //add in payment table
                $ptbl['user_id'] = $user->id;
                $ptbl['process_id'] = $response;
                $ptbl['date'] = $new_date;
                $ptbl['pay_by'] = $sp_by[$key];
                $ptbl['ref_no'] = $sp_ref_no[$key];
                $ptbl['amount'] = $sp_amount[$key];
                $ptbl['remarks'] = $sp_remarks[$key];
                $ptbl['tid'] = $tid;

                $response3 = ProcessPayment::insertGetId($ptbl);

                if (strpos($payment_type, 'bank_ref_') !== false) {
                    //bank
                    $bank_user_id = explode('_',$payment_type);
                    $bank_user_id = end($bank_user_id);
                    $response2 = self::bankTransection($bank_user_id,$sp_amount[$key],$remarks,$new_date,$response3,$tid,$response);
                } else {
                    if ($payment_type == "cheque") {
                        //cheque
                        $response2 = self::chequeTransection($sp_amount[$key],$remarks,$new_date,$response3,$sp_ref_no[$key],$tid,$response);
                    } else {
                        //cash
                        $response2 = self::cashTransection($sp_amount[$key],$remarks,$new_date,$response3,$tid,$response);
                    }
                }
            }
        }

        //update total payment
        $stock_process = StockProcess::find($stock_process_ins_id);
        $stock_process->grand_payment = $total_payment;
        $stock_process->save();


        if($update) {
            return redirect()->route('view.all.process')->with('success', "Process updated successfully");
        } else {
            return redirect()->route('view.all.process')->with('error', "Ooops..! Something went wrong");
        }

    }

    private function getProcessUniqueNumber() {
        $user = Sentinel::getUser();
        $max_id = StockProcess::where('user_id',$user->id)->count();
        return  $max_id + 1;
    }


  public function newProcessRegister(Request $request) {
    $user = Sentinel::getUser();
    //register new process
    $validator = Validator::make($request->all(), [
        'adjustment_date' => 'required',
        "process_name"    => "required",
//        "stock_no"    => "required|array|min:1",
//        "stock_no.*"  => "required|min:1",
//        "quantity"    => "required|array|min:1",
//        "quantity.*"  => "required|min:1",
//        "mesurement"    => "required|array|min:1",
//        "mesurement.*"  => "required|min:1",
//        "unit"    => "required|array|min:1",
//        "unit.*"  => "required|min:1",
//        "total"    => "required|array|min:1",
//        "total.*"  => "required|min:1",
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }


    $ins['user_id'] = $user->id;
    $ins['pname'] = self::getProcessUniqueNumber();
    $ins['date'] = date('Y-m-d',strtotime(request('adjustment_date')));
    $ins['type'] = request('confirmAns') != "" ? 1 : 2;
    $ins['due_date'] = date('Y-m-d',strtotime(request('due_date')));
    $ins['process_id'] = request('process_name');
    $ins['manufacturer_type'] = request('type_of_manufacturer') ? implode(',',request('type_of_manufacturer')) : NULL;
    $ins['sample'] = request('upload_image_text');
    $ins['bill_photo'] = request('upload_image_text_bill');
    $ins['expected_date'] = date('Y-m-d',strtotime(request('expected_date')));
    $ins['remarks'] = request('remarks');
    $ins['process_state'] = request('process_state');
    $ins['process_transport'] = request('process_transport');
    $ins['less_amount'] = request('less_total');
    $ins['grand_total'] = request('grand_total');
    $ins['final_total'] = request('final_total');
    $stock_process_ins_id = StockProcess::insertGetId($ins);

    //insert item
    $stock_no = request('stock_no');
    $description = request('description');
    $design_name = request('design_name');
    $quantity = request('quantity') ? request('quantity') : [];
    $mesurement = request('mesurement');
    $unit = request('unit');
    $discount = request('discount');
    $discount_amount = request('discount_amount');
    $gst = request('gst');
    $gst_amount = request('gst_amount');
    $sub_total = request('total');
    foreach($quantity as $key=>$row) {
      if($quantity[$key] != "" && $unit[$key] != "" && $quantity[$key] != 0 && $unit[$key] != 0) {
        $child['user_id'] = $user->id;
        $child['stock_process_id'] = $stock_process_ins_id;
        $child['stock_id'] = $stock_no[$key] != "" ? $stock_no[$key] : 0;
        $child['description'] = $description[$key];
        $child['design_name'] = $design_name[$key];
        $child['quantity'] = $quantity[$key];
        $child['mesurement'] = $mesurement[$key];
        $child['rate'] = $unit[$key];
        $child['discount'] = $discount[$key];
        $child['discount_amount'] = $discount_amount[$key];
        $child['gst'] = $gst[$key];
        $child['gst_amount'] = $gst_amount[$key];
        $child['total'] = $sub_total[$key];
        StockProcessItem::insert($child);
      }
    }

    //insert payment
      $info = Process::find(request('process_name'));
      $remarks = NULL;

      $sp_date = request('sp_date');
      $sp_by = request('sp_by');
      $sp_ref_no = request('sp_ref_no');
      $sp_amount = request('sp_amount');
      $sp_remarks = request('sp_remarks');
      $total_payment = 0;
      $response = $stock_process_ins_id;
      foreach($sp_amount as $key=>$row) {
          if ($sp_amount[$key] != "" && $sp_date[$key] != "" && $sp_by[$key] != "") {

              $tid = Admin::uniqueTransectionId("PROCESS",$user->id);
              $payment_type = $sp_by[$key];
              $total_payment += $sp_amount[$key];
              $new_date = date('Y-m-d h:i:s',strtotime($sp_date[$key]));

              //add in payment table
              $ptbl['user_id'] = $user->id;
              $ptbl['process_id'] = $response;
              $ptbl['date'] = $new_date;
              $ptbl['pay_by'] = $sp_by[$key];
              $ptbl['ref_no'] = $sp_ref_no[$key];
              $ptbl['amount'] = $sp_amount[$key];
              $ptbl['remarks'] = $sp_remarks[$key];
              $ptbl['tid'] = $tid;

              $response3 = ProcessPayment::insertGetId($ptbl);

              if (strpos($payment_type, 'bank_ref_') !== false) {
                  //bank
                  $bank_user_id = explode('_',$payment_type);
                  $bank_user_id = end($bank_user_id);
                  $response2 = self::bankTransection($bank_user_id,$sp_amount[$key],$remarks,$new_date,$response3,$tid,$response);
              } else {
                  if ($payment_type == "cheque") {
                      //cheque
                      $response2 = self::chequeTransection($sp_amount[$key],$remarks,$new_date,$response3,$sp_ref_no[$key],$tid,$response);
                  } else {
                      //cash
                      $response2 = self::cashTransection($sp_amount[$key],$remarks,$new_date,$response3,$tid,$response);
                  }
              }
          }
      }

      //update total payment
      $stock_process = StockProcess::find($stock_process_ins_id);
      $stock_process->grand_payment = $total_payment;
      $stock_process->save();

    if($stock_process_ins_id) {
      return redirect()->route('view.all.process')->with('success', "New process register successfully");
    } else {
      return redirect()->route('view.all.process')->with('error', "Ooops..! Something went wrong");
    }

  }

    private function bankTransection($bank_user_id,$bank_amount,$remarks,$adjustmentDate,$paymentid,$tid,$udf4) {
        $transection_type = "bank13";
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
        $bank['udf4'] = $udf4;
        $response = BankTransection::insert($bank);
        return $response ? true : false;
    }

    private function chequeTransection($bank_amount,$remarks,$adjustmentDate,$paymentid,$refno,$tid,$udf4) {
        $transection_type = "cheque8";
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
        $cash['udf4'] = $udf4;
        $response = ChequeTransection::insert($cash);
        return $response ? true : false;
    }

    private function cashTransection($bank_amount,$remarks,$adjustmentDate,$paymentid,$tid,$udf4) {
        $transection_type = "cash11";
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
        $cash['udf4'] = $udf4;
        $response = CashTransection::insert($cash);
        return $response ? true : false;
    }

  public function processTransection(Request $request,$id) {
    $user = Sentinel::getUser();
    $search['table'] = 'process';
    $search['user_id'] = $user->id;
    $search['id'] = $id;
    $search['master_type'] = 'master6';

      if(request('filter_by') != "") {
          $search['type'] = request('filter_by');
      }

      if(request('bill_no') != "") {
          $search['bill_no'] = request('bill_no');
      }

      if(request('startdate') != "" && request('enddate') != "") {
          $search['startdate'] = request('startdate');
          $search['enddate'] = request('enddate');
      }

    $master = DB::select(Admin::masterTransectionQuery($search));
    return DataTables::of($master)
    ->addColumn('formated_date', function ($master) {
        $html = '';
        $html .= Admin::FormateDate($master->transection_date);
        return $html;
    })
    ->addColumn('formated_number', function ($master) {
        $html = '';
        $html .= $master->transection_recipt_no;
        return $html;
    })
    ->addColumn('formated_type', function ($master) {
        $html = '';
        $type = config('transection.'.$master->transection_type)['type'];
        $html .= $type;
        if($type == "Expenses") {
            $html = $master->transection_remarks;
            $html .= ' ('.$type.')';
        }
        return $html;
    })
    ->addColumn('formated_amount', function ($master) {
        $html = '';
        $html .= Admin::FormateTransection($master->transection_amount,false);
        if($master->transection_type == "EXPENSES" || $master->transection_type == "PROCESS") {
            $html = Admin::FormateTransection($master->transection_amount+$master->transection_recive,false);
        }
        return $html;
    })
    ->addColumn('transection_recive', function ($master) {
        $html = '';
        if($master->transection_recive != "") {
            $html .= Admin::FormateTransection($master->transection_recive,false);
        }
        return $html;
    })
    ->addColumn('transection_paid', function ($master) {
        $html = '';
        if($master->transection_paid != "") {
            $html .= Admin::FormateTransection($master->transection_paid,false);
        }
        return $html;
    })
    ->addColumn('action', function ($master) use ($id) {
        $transection_type = config('transection.'.$master->transection_type);
        $html = '';
        if($transection_type['edit_at'] != "") {
        $html .= ' <a href="'.route("redirecting").'?redirectback=process.view&id='.$id.'&redirect='.$transection_type['edit_at'].'&toid='.$master->transection_id.'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        }
        if($transection_type['deleted_at'] != "") {
        $html .= ' <a href="'.route("redirecting").'?redirectback=process.view&id='.$id.'&redirect='.$transection_type['deleted_at'].'&toid='.$master->transection_id.'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        }
        return $html;
    })
    ->rawColumns(['formated_date','formated_number','formated_type','formated_amount','transection_recive','transection_paid','action'])
    ->make(true);
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
        return view('admin.v1.process.mtypes');
    }

    public function registerTypes(Request $request) {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|unique:process_type,name',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $request = ProcessType::insert(
            ['name' => request('type_name')]
        );

        if($request) {
            return redirect()->route('process.types')->with('success', "Process type registred");
        } else {
            return redirect()->route('process.types')->with('error', "Process type already exist");
        }
    }

    public function getMtype(Request $request)  {
        $mtype = ProcessType::select('*')->orderBy('id','DESC');
        return DataTables::of($mtype)
            ->addColumn('action', function ($mtype) {
                $activation_status = $mtype->status == 1 ? 'checked' : "";
                $html = '';
                $html .= ' <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#EditModal" onClick="getformelement('.$mtype->id.')" style="margin-bottom: 0px !important;"><i class="fa fa-pencil-square-o"></i> Edit</button>';
                $html .= ' <a href="'.route('process.types.remove',$mtype->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
                return $html;
            })->make(true);
    }

    public function removeMtype(Request $request,$id)
    {
        $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:process_type,id']);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $user =  ProcessType::find($id)->delete();
        if($user) {
            return redirect()->route('process.types')->with('success', "Process type deleted");
        } else {
            return redirect()->route('process.types')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function infoMtype(Request $request) {
        $manufacturer = ProcessType::find(request('id'));
        return response()->json($manufacturer);
    }

    public function updateMtype(Request $request) {
        $validator = Validator::make($request->all(), [
            'edit_type_name' => 'required|unique:process_type,name,'.request('edit_unique_id'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $request = ProcessType::where('id',request('edit_unique_id'))->update(
            ['name' => request('edit_type_name')]
        );

        if($request) {
            return redirect()->route('process.types')->with('success', "Process type updated");
        } else {
            return redirect()->route('process.types')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function downloadpdf(Request $request,$id) {
        $user = Sentinel::getUser();
        $data['user_state'] = $user->state != "" ? State::where('state_id',$user->state)->first() : [];
        $data['user_city'] = $user->city != "" ? DB::table('cities')->where('city_id',$user->city)->first() : [];
        $process = StockProcess::query();
        $process->select('stock_process.*',DB::Raw('GROUP_CONCAT(DISTINCT process_type.name  ORDER BY process_type.id) as manufacturer'),'process.name as process_name','process.gstin_no as process_gst','process.state as process_state','process.city as process_city','process.address as process_address','process.photo as process_photo','process.business_name as process_business');
        $process->leftjoin('process','stock_process.process_id','=','process.id');
        $process->join('process_type',DB::raw('FIND_IN_SET(process_type.id, stock_process.manufacturer_type)'),'>',DB::raw('"0"'));
        $process->where('stock_process.status',1);
        $process->where('stock_process.id',$id);
        $data['info'] = $info = $process->first();
        $data['process_state'] = $info->process_state != "" ? State::where('state_id',$info->process_state)->first() : [];
        $data['process_city'] = $info->process_city != "" ? DB::table('cities')->where('city_id',$info->process_city)->first() : [];
        $data['item_list'] = self::getstockitemList($id)->get();
//        print_r($data['item_list']); die();
//        return View('admin.v1.process.pdf', $data);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin.v1.process.pdf', $data);
        return $pdf->stream('process-no-'.$id.'.pdf');
    }

}
