<?php

namespace App\Http\Controllers\Administrator\v1;

use App\Model\Agent;
use App\Model\Payment;
use App\Model\StockItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Admin;
use Sentinel;
use App\User;
use App\Model\Banks;
use App\Model\Party;
use App\Model\Stock;
use App\Model\Manufacturer;

class PartyController extends Controller
{
  public function __construct() {

  }

  public function party(Request $request)
  {
    return view('admin.v1.party.list');
  }


  public function partyNew(Request $request)
  {
      $data['type'] = 'add';
      $data['pinfo'] = [];
      $data['bank_list'] = Banks::orderBy('name','ASC')->get();
      $data['manufacturer'] = Manufacturer::select('id','name')->where('status',1)->get();
      $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
      return view('admin.v1.party.new',$data);
  }

  private function PartyList($column = 'party.id',$order = 'DESC') {
    $user = Sentinel::getUser();
    return Party::select('party.*',DB::raw('GROUP_CONCAT(manufacturer_type.name) as mname'),'states.state as mystate','cities.city as mycity')
    ->leftjoin('manufacturer_type', DB::raw('FIND_IN_SET(`manufacturer_type`.`id`,`party`.`types_of_menufecture`)'),'<>',DB::raw('"0"'))
    ->leftjoin('states','party.state','=','states.state_id')
    ->leftjoin('cities','party.city','=','cities.city_id')
    ->where('party.user_id',$user->id)
    ->where('party.status',1)
    ->groupBy('party.id')
    ->orderBy($column,$order);
  }

  public function getPartyJson(Request $request)  {
    $party = self::PartyList()->where('party.status',1)->get();
    return response()->json($party);
  }

  public function getTypesOfMenufecturer($types) {
      if($types != "") {
          $types = explode(',',$types);
          $mtype = Manufacturer::whereIn('id',$types)->get();
          $result = [];
          foreach($mtype as $row) {
              $result[] = $row->name;
          }
          return implode(',',$result);
      } else {
          return "";
      }
  }

  public function getParty(Request $request)  {
    $user = Sentinel::getUser();

    //order logic
    if(request('columns')[0]['orderable'] == "true" && isset(request('order')[0]['dir'])) {
      $order = request('order')[0]['dir'];
      $column = 'party.name';
      $party = self::PartyList($column,$order);
    } else {
      $party = self::PartyList();
    }
    return DataTables::of($party)
    ->addColumn('party', function ($party) {
        $html = '';
        $html .= '<img src="'.asset($party->photo).'" alt="'.$party->name.'" class="img-circle img-responsive" style="display:inline-block;vertical-align:top;" width="40"> <span style="display: inline-block;margin-left:5px;">'.$party->name.'<br><span class="text-muted">'.$party->business_name.'</span></span>';
        return $html;
    })
    ->addColumn('transection_amount', function ($party) use ($user) {
        $html = '';
        $search['table'] = 'party';
        $search['user_id'] = $user->id;
        $search['id'] = $party->id;
        $search['master_type'] = 'master1';

        $amount = DB::select(Admin::masterTransectionQuery($search));
        $html .= Admin::FormateTransection(collect($amount)->sum('transection_amount'));
        return $html;
    })
    ->addColumn('gstininfo', function ($party) {
        $html = '';
        if($party->gstin_no || $party->mycity || $party->mystate) {
            $html .= $party->gstin_no . '<br><span class="text-muted">' . $party->mycity . ', ' . $party->mystate . '</span>';
        }
        return $html;
    })
    ->addColumn('types_of_menu', function ($party) {
        $html = '';
        $html .= self::getTypesOfMenufecturer($party->types_of_menufecture);
        return $html;
    })
    ->addColumn('contactinfo', function ($party) {
        $html = '';
        $html .= $party->mobile.'<br><span class="text-muted">'.$party->alt_mobile.'</span>';
        return $html;
    })
    ->addColumn('action', function ($party) {
        //$activation_status = $party->status == 1 ? 'checked' : "";
        $html = '';
        //$html .= '<input type="checkbox" class="status_checkbox" data-id=" '.$party->id.'" '.$activation_status.' data-size="mini" data-toggle="toggle" data-on="Active" data-off="Deactive" data-onstyle="success" data-offstyle="danger">';
        $html .= ' <a href="'.route('party.edit',$party->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        $html .= ' <a href="'.route('party.remove',$party->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        return $html;
    })
    ->filterColumn('party', function($query, $keyword) {
       $query->whereRaw("CONCAT(party.name,' ',party.business_name) like ?", ["%{$keyword}%"]);
    })
    // ->filterColumn('transection_amount', function($query, $keyword) {
    //    $query->whereRaw("manufacturer_type.name like ?", ["%{$keyword}%"]);
    // })
    ->filterColumn('gstininfo', function($query, $keyword) {
       $query->whereRaw("CONCAT(states.state,', ',cities.city,' ',party.gstin_no) like ?", ["%{$keyword}%"]);
    })
    ->filterColumn('contactinfo', function($query, $keyword) {
       $query->whereRaw("CONCAT(party.mobile,' ',party.alt_mobile) like ?", ["%{$keyword}%"]);
    })
    // ->orderColumn('party', function ($query, $order) {
    //     $query->orderBy('party.name', 'DESC');
    // })
    ->rawColumns(['party','transection_amount','gstininfo','contactinfo', 'action'])
    ->make(true);
  }

  public function ActivationParty(Request $request) {
    // $request = Party::where('id',request('id'))->get();
    // ->update(['status' => request('status')]);

    $party = Party::find(request('id'));
    $party->status = request('status');
    $party->save();

    return $party;
  }

  public function cityListAjax(Request $request) {
    $data['cities'] = DB::table('cities')->select('city_id as id','city as text')->where('status',1)->where('state_id',request('id'))->orderBy('city','ASC')->get();
    return response()->json($data);
  }

  public function removeParty(Request $request,$id)
  {
    $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:party,id']);
    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }

    //$user =  Party::find($id)->delete();
      $user =  Party::find($id);
      $user->status = 0;
      $user->save();

    if($user) {
      return redirect()->route('party')->with('success', "Party Removed Successfully");
    } else {
      return redirect()->route('party')->with('error', "Ooops..! Something went wrong");
    }
  }

  public function editParty(Request $request,$id) {
    $data['type'] = 'edit';
    $data['pinfo'] = Party::find($id);
    $data['bank_list'] = Banks::orderBy('name','ASC')->get();
    $data['manufacturer'] = Manufacturer::select('id','name')->where('status',1)->get();
    $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
    return view('admin.v1.party.new',$data);
  }

  public function updateParty(Request $request,$id) {
    $validator = Validator::make($request->all(), [
        'party_name' => 'required',
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
      // 'office_no' => "",
      'opening_balance' => request('opening_balance') != null ? request('opening_balance') : 0,
      'opening_type' => request('opening_type'),
      'opening_asof' => request('asof') != null ? date('Y-m-d',strtotime(request('asof'))) : date('Y-m-d',strtotime("now")),
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
      'remarks' => request('remarks')
    ];


    if (request('fbinputtxt')) {
      $update['photo'] = request('fbinputtxt');
    }

    $request = Party::where('id',$id)->update($update);

    if($request) {
      return redirect()->route('party')->with('success', "Party updated Successfully");
    } else {
      return redirect()->route('party')->with('error', "Ooops..! Something went wrong");
    }


  }

  public function registerParty(Request $request) {
    $validator = Validator::make($request->all(), [
        'party_name' => 'required',
        'person_name' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return back()->with('error', $errors->first());
    }



    $user = Sentinel::getUser();
    $response = Party::insert([
      'user_id' => $user->id,
      'business_name' => request('party_name'),
      'name' => request('person_name'),
      'types_of_menufecture' => request('type_of_manufacturer') ? implode(',',request('type_of_manufacturer')) : NULL,
      'gstin_no' => request('gstno'),
      'photo' => request('fbinputtxt') ? request('fbinputtxt') : 'user_404.jpg',
      'email' => request('email'),
      'mobile' => request('mobile'),
      'alt_mobile' => request('alt_no'),
      // 'office_no' => "",
      'opening_balance' => request('opening_balance') != null ? request('opening_balance') : 0,
      'opening_type' => request('opening_type'),
      'opening_asof' => request('asof') != null ? date('Y-m-d',strtotime(request('asof'))) : date('Y-m-d',strtotime("now")),
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
      'remarks' => request('remarks'),
    ]);

    if($response) {
      return Admin::checkRedirect($request,'party',"Party registred Successfully");
    } else {
      return redirect()->route('party')->with('error', "Ooops..! Something went wrong");
    }

  }


  public function partyView(Request $request,$id) {
    $user = Sentinel::getUser();
    if(Party::HaveRightBank($user->id,$id)) {
      $data['info'] = Party::find($id);

      $data['agent_list'] = Agent::where('user_id',$user->id)->where('status',1)->orderBy('name','ASC')->get();
      $data['stock_list_item'] = StockItem::where('user_id',$user->id)->where('status',1)->orderBy('id','DESC')->get();

      //total
      $search['table'] = 'party';
      $search['user_id'] = $user->id;
      $search['id'] = $id;
      $search['master_type'] = 'master1';
//      echo Admin::masterTransectionQuery($search); die();
      $amount = DB::select(Admin::masterTransectionQuery($search));
      $data['total_amount'] = collect($amount)->sum('transection_amount');



      //stock
      $query = Stock::query();
      $query->select('stock.*');
      $query->where('stock.status',1);
      $query->where('stock.user_id',$user->id);
      $query->where('stock.party_id',$id);
      $query->groupBy('stock.id');
      $query->orderBY('stock.id','DESC');
      $data['stock_list'] = $query->paginate(12);

      return view('admin.v1.party.view',$data);
    } else {
      return Admin::unauth();
    }
  }

  public function partyTransection(Request $request,$id) {
    $user = Sentinel::getUser();
    $search['table'] = 'party';
    $search['user_id'] = $user->id;
    $search['id'] = $id;
    $search['master_type'] = 'master1';

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
        if($master->transection_type == "EXPENSES") {
            $html = Admin::FormateTransection($master->transection_amount+$master->transection_recive,false);
        }

        if($master->transection_type == "INVOICE") {
            $html = Admin::FormateTransection($master->transection_paid+$master->transection_recive,false);
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
        $html .= ' <a href="'.route("redirecting").'?redirectback=party.view&id='.$id.'&redirect='.$transection_type['edit_at'].'&toid='.$master->transection_id.'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
        }
        if($transection_type['deleted_at'] != "") {
        $html .= ' <a href="'.route("redirecting").'?redirectback=party.view&id='.$id.'&redirect='.$transection_type['deleted_at'].'&toid='.$master->transection_id.'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
        }
        return $html;
    })
    ->rawColumns(['formated_date','formated_number','formated_type','formated_amount','transection_recive','transection_paid','action'])
    ->make(true);
  }


}
