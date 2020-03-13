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
use App\Model\Material;
use App\Model\MaterialType;
use App\Model\Banks;

class MaterialController extends Controller
{
    public function __construct() {

    }

    public function material(Request $request)
    {
        return view('admin.v1.material.list');
    }

    public function mtypes(Request $request)
    {
        return view('admin.v1.material.mtypes');
    }

    public function getMtype(Request $request)  {
        $mtype = MaterialType::select('*')->orderBy('id','DESC');
        return DataTables::of($mtype)
            ->addColumn('action', function ($mtype) {
                $activation_status = $mtype->status == 1 ? 'checked' : "";
                $html = '';
                $html .= ' <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#EditModal" onClick="getformelement('.$mtype->id.')" style="margin-bottom: 0px !important;"><i class="fa fa-pencil-square-o"></i> Edit</button>';
                $html .= ' <a href="'.route('material.types.remove',$mtype->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
                return $html;
            })->make(true);
    }

    public function infoMtype(Request $request) {
        $manufacturer = MaterialType::find(request('id'));
        return response()->json($manufacturer);
    }

    public function removeMtype(Request $request,$id)
    {
        $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:material_type,id']);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $user =  MaterialType::find($id)->delete();
        if($user) {
            return redirect()->route('material.types')->with('success', "Material type deleted");
        } else {
            return redirect()->route('material.types')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function registerTypes(Request $request) {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|unique:material_type,name',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $request = MaterialType::insert(
            ['name' => request('type_name')]
        );

        if($request) {
            return redirect()->route('material.types')->with('success', "Material type registred");
        } else {
            return redirect()->route('material.types')->with('error', "Material type already exist");
        }
    }

    public function updateMtype(Request $request) {
        $validator = Validator::make($request->all(), [
            'edit_type_name' => 'required|unique:material_type,name,'.request('edit_unique_id'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('error', $errors->first());
        }

        $request = MaterialType::where('id',request('edit_unique_id'))->update(
            ['name' => request('edit_type_name')]
        );

        if($request) {
            return redirect()->route('material.types')->with('success', "Material type updated");
        } else {
            return redirect()->route('material.types')->with('error', "Ooops..! Something went wrong");
        }
    }

    public function materialNew(Request $request)
    {
        $data['type'] = 'add';
        $data['pinfo'] = [];
        $data['bank_list'] = Banks::orderBy('name','ASC')->get();
        $data['manufacturer'] = MaterialType::select('id','name')->where('status',1)->get();
        $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
        return view('admin.v1.material.new',$data);
    }

    private function materialList($column = 'material.id',$order = 'DESC') {
      $user = Sentinel::getUser();
      return Material::select('material.*',DB::raw('GROUP_CONCAT(material_type.name) as mname'),'states.state as mystate','cities.city as mycity')
      ->leftjoin('material_type', DB::raw('FIND_IN_SET(`material_type`.`id`,`material`.`types_of_menufecture`)'),'<>',DB::raw('"0"'))
      ->leftjoin('states','material.state','=','states.state_id')
      ->leftjoin('cities','material.city','=','cities.city_id')
      ->where('material.user_id',$user->id)
      ->where('material.status',1)
      ->groupBy('material.id')
      ->orderBy($column,$order);
    }

    public function getMaterialJson(Request $request) {
      $material = self::materialList()->where('material.status',1)->get();
      return response()->json($material);
    }

    public function getTypesOfMenufecturer($types) {
        if($types != "") {
            $types = explode(',',$types);
            $mtype = MaterialType::whereIn('id',$types)->get();
            $result = [];
            foreach($mtype as $row) {
                $result[] = $row->name;
            }
            return implode(',',$result);
        } else {
            return "";
        }
    }

    public function getMaterial(Request $request)  {
      $user = Sentinel::getUser();

      //order logic
      if(request('columns')[0]['orderable'] == "true" && isset(request('order')[0]['dir'])) {
        $order = request('order')[0]['dir'];
        $column = 'material.name';
        $material = self::materialList($column,$order);
      } else {
        $material = self::materialList();
      }

      return DataTables::of($material)
      ->addColumn('party', function ($material) {
          $html = '';
          $html .= '<img src="'.asset($material->photo).'" alt="'.$material->name.'" class="img-circle img-responsive" style="display:inline-block;vertical-align:top;" width="40"> <span style="display: inline-block;margin-left:5px;">'.$material->name.'<br><span class="text-muted">'.$material->business_name.'</span></span>';
          return $html;
      })
      ->addColumn('types_of_menu', function ($party) {
          $html = '';
          $html .= self::getTypesOfMenufecturer($party->types_of_menufecture);
          return $html;
      })
      ->addColumn('transection_amount', function ($party) use ($user) {
          $html = '';
          $search['table'] = 'material';
          $search['user_id'] = $user->id;
          $search['id'] = $party->id;
          $search['master_type'] = 'master5';

          $amount = DB::select(Admin::masterTransectionQuery($search));
          $html .= Admin::FormateTransection(collect($amount)->sum('transection_amount'));
          return $html;
      })
      // ->addColumn('manufacturer', function ($material) {
      //     $html = '';
      //     $html .= $material->mname;
      //     return $html;
      // })
      ->addColumn('gstininfo', function ($material) {
          $html = '';
          if(($material->mycity || $material->mystate) || $material->gstin_no)
          $html .= $material->gstin_no.'<br><span class="text-muted">'.$material->mycity.', '.$material->mystate.'</span>';
          return $html;
      })
      ->addColumn('contactinfo', function ($material) {
          $html = '';
          $html .= $material->mobile.'<br><span class="text-muted">'.$material->alt_mobile.'</span>';
          return $html;
      })
      ->addColumn('action', function ($material) {
          //$activation_status = $material->status == 1 ? 'checked' : "";
          $html = '';
          //$html .= '<input type="checkbox" class="status_checkbox" data-id=" '.$material->id.'" '.$activation_status.' data-size="mini" data-toggle="toggle" data-on="Active" data-off="Deactive" data-onstyle="success" data-offstyle="danger">';
          $html .= ' <a href="'.route('material.edit',$material->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
          $html .= ' <a href="'.route('material.remove',$material->id).'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
          return $html;
      })
      ->filterColumn('party', function($query, $keyword) {
         $query->whereRaw("CONCAT(material.name,' ',material.business_name) like ?", ["%{$keyword}%"]);
      })
      // ->filterColumn('manufacturer', function($query, $keyword) {
      //    $query->whereRaw("material_type.name like ?", ["%{$keyword}%"]);
      // })
      ->filterColumn('gstininfo', function($query, $keyword) {
         $query->whereRaw("CONCAT(states.state,', ',cities.city,' ',material.gstin_no) like ?", ["%{$keyword}%"]);
      })
      ->filterColumn('contactinfo', function($query, $keyword) {
         $query->whereRaw("CONCAT(material.mobile,' ',material.alt_mobile) like ?", ["%{$keyword}%"]);
      })
      ->rawColumns(['party','transection_amount','gstininfo','contactinfo', 'action'])
      ->make(true);
    }

    public function ActivationMaterial(Request $request) {
      $party = Material::find(request('id'));
      $party->status = request('status');
      $party->save();
      return $party;
    }

    public function removeMaterial(Request $request,$id)
    {
      $validator = Validator::make(['id'=>$id], ['id' => 'required|exists:material,id']);
      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('error', $errors->first());
      }

//      $user =  Material::find($id)->delete();
        $user =  Material::find($id);
        $user->status = 0;
        $user->save();
      if($user) {
        return redirect()->route('material')->with('success', "Material Removed Successfully");
      } else {
        return redirect()->route('material')->with('error', "Ooops..! Something went wrong");
      }
    }

    public function editMaterial(Request $request,$id) {
      $data['type'] = 'edit';
      $data['pinfo'] = Material::find($id);
      $data['bank_list'] = Banks::orderBy('name','ASC')->get();
      $data['manufacturer'] = MaterialType::select('id','name')->where('status',1)->get();
      $data['states'] = DB::table('states')->where('status_id',1)->where('country_id','101')->orderBy('state','ASC')->get();
      return view('admin.v1.material.new',$data);
    }

    public function updateMaterial(Request $request,$id) {
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

      $request = Material::where('id',$id)->update($update);
      if($request) {
        return redirect()->route('material')->with('success', "Material updated Successfully");
      } else {
        return redirect()->route('material')->with('error', "Ooops..! Something went wrong");
      }
    }

    public function registerMaterial(Request $request) {
      $validator = Validator::make($request->all(), [
          'person_name' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()->with('error', $errors->first());
      }



      $user = Sentinel::getUser();
      $response = Material::insert([
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
        return Admin::checkRedirect($request,'material',"Material registred Successfully");
      } else {
        return redirect()->route('material')->with('error', "Ooops..! Something went wrong");
      }

    }

    public function materialView(Request $request,$id) {
      $user = Sentinel::getUser();
      if(Material::HaveRightBank($user->id,$id)) {
        $data['info'] = Material::find($id);

        //total
        $search['table'] = 'material';
        $search['user_id'] = $user->id;
        $search['id'] = $id;
        $search['master_type'] = 'master5';
        $amount = DB::select(Admin::masterTransectionQuery($search));
        $data['total_amount'] = collect($amount)->sum('transection_amount');

        return view('admin.v1.material.view',$data);
      } else {
        return Admin::unauth();
      }
    }

    public function materialTransection(Request $request,$id) {
      $user = Sentinel::getUser();
      $search['table'] = 'material';
      $search['user_id'] = $user->id;
      $search['id'] = $id;
      $search['master_type'] = 'master5';

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
          $html .= ' <a href="'.route("redirecting").'?redirectback=material.view&id='.$id.'&redirect='.$transection_type['edit_at'].'&toid='.$master->transection_id.'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
          }
          if($transection_type['deleted_at'] != "") {
          $html .= ' <a href="'.route("redirecting").'?redirectback=material.view&id='.$id.'&redirect='.$transection_type['deleted_at'].'&toid='.$master->transection_id.'" class="btn btn-danger btn-xs" onClick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a>';
          }
          return $html;
      })
      ->rawColumns(['formated_date','formated_number','formated_type','formated_amount','transection_recive','transection_paid','action'])
      ->make(true);
    }
}
