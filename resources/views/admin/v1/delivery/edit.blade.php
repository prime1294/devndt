@extends('admin.v1.layout.app', ['title' => 'Delivery Challan'])

@section('content')
<style type="text/css">
.select2-container .select2-selection--single .select2-selection__rendered {
  padding-left: 0px !important;
}
.banktransferinput {
  display: none;
}
.bggray {
  background-color: #ECF0F5;
}
.qty .count {
    color: #000;
    display: inline-block;
    vertical-align: top;
    font-size: 25px;
    font-weight: 700;
    line-height: 30px;
    padding: 0 2px
    ;min-width: 70px;
    text-align: center;
}
.qty .plus {
    cursor: pointer;
    display: inline-block;
    vertical-align: top;
    color: #717FE0;
    width: 21px;
    border: 1px solid #717FE0;
    height: 22px;
    font: 21px/1 Arial,sans-serif;
    text-align: center;
    border-radius: 50%;
    margin-top: 3px;
    }
.qty .minus {
    cursor: pointer;
    display: inline-block;
    vertical-align: top;
    color: #717FE0;
    border: 1px solid #717FE0;
    width: 21px;
    height: 22px;
    font: 19px/1 Arial,sans-serif;
    text-align: center;
    border-radius: 50%;
    background-clip: padding-box;
    margin-top: 3px;
}
.minus:hover{
    background-color: #717fe0 !important;
    color:#FFFFFF;
}
.plus:hover{
    background-color: #717fe0 !important;
    color:#FFFFFF;
}
/*Prevent text selection*/
span{
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}
.count{
    border: 0;
    width: 2%;
}
.count:disabled{
    background-color:white;
}
.seprator {
  margin-bottom: 10px;
  border-bottom: 1px solid #fbf4f4;
}
.totalquantityctndiv {
    padding: 10px 8px;
}
.totalquantityctn {
  font-size: 20px;
  font-weight: 900;
  text-transform: uppercase;
}
.totalctndiv {
  display: none;
}
.editsubmit , .editreset {
  display: none;
}
.toggle-group .btn-xs {
  line-height: 1.3;
}
.mytbl tr td {
  padding: 5px !important;
}
.mytbl .ele1 {
    width: 12%;
}
.mytbl .ele2 {
  width: 21%;
}
.mytbl .ele3, .mytbl .ele4, .mytbl .ele5, .mytbl .ele6, .mytbl .ele7 {
  width: 10%;
}
.mytbl .ele8 {
  width: 12%;
}
.mytbl .ele9 {
  width: 5%;
}
.sp_by {
  width: 400px;
}
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-cubes"></i> Delivery Challan
    <small>Add Delivery Challan</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Add Delivery Challan</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <form method="post" action="{{ route('update.delivery.challan',$info->id) }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="adjustment_date">Date</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="adjustment_date" name="adjustment_date" value="{{ date('d-m-Y',strtotime($info->date))  }}" class="form-control datepicker">
                </div>
              </div>
              </div>

                <div class="col-md-4">
                  <div class="form-group">
                        <label for="process_name">Party Name</label>
                        <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <select style="width: 100%;" id="process_name" name="process_name" class="form-control select2-bank" data-placeholder="Select Party">
                          <option value="">Select Party</option>
                          <option data-img="{{ 'plus.png' }}" value="0">Add New</option>
                          @foreach($party_list as $row)
                          <option data-state="{{ $row->state  }}" data-business="{{ $row->business_name }}" data-img="{{ $row->photo }}" value="{{ $row->id }}" {{ $info->party_id == $row->id ? "selected" : ""  }}>{{ ucwords($row->name) }}</option>
                          @endforeach
                        </select>
                        </div>
                    </div>
                </div>

            <div class="col-md-4">
              <div class="form-group">
                <label for="business_name">Business Name</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="text" id="business_name" name="business_name" value="{{ $info->business_name }}" class="form-control">
                </div>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="process_state">State</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <select  style="width: 100%;" id="process_state" name="process_state" class="form-control select2">
                    <option value="">Select State</option>
                    <?php
                    foreach($states as $row) {
                    ?>
                    <option value="{{ $row->state_id }}" data-gst="{{ $row->gst_code }}" {{ $info->state == $row->state_id ? "selected" : "" }}>{{ $row->state }}</option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="process_transport">Transport Detail</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="text" id="process_transport" name="process_transport" value="{{ $info->transport }}" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="challan_no">Challan No</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="text" id="challan_no" name="challan_no" value="{{ $info->challan_no }}" class="form-control">
                </div>
              </div>
            </div>
          </div>


          <!-- Panel Code goes here -->
          <div class="row">
            <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-hover table-striped mytbl fixtablemobile">
                <thead>
                  <tr class="bg-primary">
                    <td class="text-center">Stock</td>
                    <td class="text-center">Description</td>
                    <td class="text-center">Design</td>
                    <td class="text-center">HSN code</td>
                    <td class="text-center">Quantity</td>
                    <td class="text-center">Unit</td>
                    <td class="text-center">Rate</td>
                    <td class="text-center">Total</td>
                    <td class="text-center"></td>
                  </tr>
                </thead>
                <tbody class="append_to_me">
                  <?php $no = 1; ?>
                  @if($challan_items->isNotEmpty())
                  @foreach($challan_items as $rr)
                  <tr class="tblrow{{ $no }}" data-id="{{ $no++ }}">
                    <td class="ele1">
                      <div class="form-group">
                      <select style="width: 100%;" id="stock_no" name="stock_no[]" class="form-control stock_no select2">
                        <option value="">Select Stock</option>
                        @foreach($stock_list as $row)
                        <option value="{{ $row->id }}" data-pending="{{ $row->pending }}" data-unit="{{ $row->unit }}" {{ $rr->stock_id == $row->id ? "selected" : "" }}>{{ Admin::FormateStockItemID($row->id) }} </option>
                        @endforeach
                      </select>
                      </div>
                    </td>
                    <td class="ele2">
                      <div class="form-group">
                      <input type="text" id="description" name="description[]" class="form-control description text-center" value="{{ $rr->description }}" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele3">
                      <div class="form-group">
                      <input type="text" id="design_name" name="design_name[]" class="form-control design_name text-center" value="{{ $rr->design_name }}" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele4">
                      <div class="form-group">
                        <input type="text" id="hsn_no" name="hsn_no[]" class="form-control hsn_no text-center" value="{{ $rr->hsn_code }}" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele5">
                      <div class="form-group">
                      <input type="text" id="quantity" name="quantity[]" class="form-control quantity onlyint text-center checkcalculation" value="{{ $rr->quantity }}" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele6">
                      <div class="form-group">
                      <select id="mesurement" style="width:100%;" name="mesurement[]" class="form-control mesurement select2">
                        @foreach($category_list as $row)
                        <option value="{{ $row->id }}" {{ $row->id == $rr->mesurement ? "selected" : "" }}>{{ $row->name }}</option>
                        @endforeach
                      </select>
                      </div>
                    </td>
                    <td class="ele7">
                      <div class="form-group">
                      <input type="text" id="unit" name="unit[]" class="form-control unit onlyint text-center checkcalculation" value="{{ $rr->rate }}" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele8">
                      <div class="form-group">
                      <input type="text" id="total" name="total[]" readonly class="form-control total onlyint text-center checkcalculation" value="{{ $rr->total }}" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele9">
                      <button type="button" class="btn btn-danger btn-xs removerowbtn"><i class="fa fa-trash"></i></button>
                    </td>
                  </tr>
                    @endforeach
                    @else
                    <tr class="tblrow1" data-id="1">
                      <td class="ele1">
                        <div class="form-group">
                          <select style="width: 100%;" id="stock_no" name="stock_no[]" class="form-control stock_no select2">
                            <option value="">Select Stock</option>
                            @foreach($stock_list as $row)
                              <option value="{{ $row->id }}" data-pending="{{ $row->pending }}" data-unit="{{ $row->unit }}">{{ Admin::FormateStockItemID($row->id) }} </option>
                            @endforeach
                          </select>
                        </div>
                      </td>
                      <td class="ele2">
                        <div class="form-group">
                          <input type="text" id="description" name="description[]" class="form-control description text-center" autocomplete="false">
                        </div>
                      </td>
                      <td class="ele3">
                        <div class="form-group">
                          <input type="text" id="design_name" name="design_name[]" class="form-control design_name text-center" autocomplete="false">
                        </div>
                      </td>
                      <td class="ele4">
                        <div class="form-group">
                          <input type="text" id="hsn_no" name="hsn_no[]" class="form-control hsn_no text-center" autocomplete="false">
                        </div>
                      </td>
                      <td class="ele5">
                        <div class="form-group">
                          <input type="text" id="quantity" name="quantity[]" class="form-control quantity onlyint text-center checkcalculation" autocomplete="false">
                        </div>
                      </td>
                      <td class="ele6">
                        <div class="form-group">
                          <select id="mesurement" style="width:100%;" name="mesurement[]" class="form-control mesurement select2">
                            @foreach($category_list as $row)
                              <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </td>
                      <td class="ele7">
                        <div class="form-group">
                          <input type="text" id="unit" name="unit[]" class="form-control unit onlyint text-center checkcalculation" value="0" autocomplete="false">
                        </div>
                      </td>
                      <td class="ele8">
                        <div class="form-group">
                          <input type="text" id="total" name="total[]" readonly class="form-control total onlyint text-center checkcalculation" value="0" autocomplete="false">
                        </div>
                      </td>
                      <td class="ele9">
                        <button type="button" class="btn btn-danger btn-xs removerowbtn"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                    @endif
                </tbody>

              </table>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
              <button type="button" class="btn btn-primary" onclick="addmorerow()"><i class="fa fa-plus"></i> Add Row</button>
              </div>
            </div>
            <div class="col-md-6 form-inline">
              <div class="form-group pull-right">
                <label for="grand_total">Total: </label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                <input type="text" class="form-control onlyint text-center" readonly value="{{ $info->grand_total }}" id="grand_total" name="grand_total">
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-sm">
            <div class="col-md-12">
              <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea id="remarks" name="remarks" class="form-control" rows="2">{{ $info->remarks }}</textarea>
                </div>
            </div>
          </div>

          <div class="form-devider"></div>
          <div class="form-group">
          <button type="submit" class="btn btn-primary" onclick="return val_add_party();"><i class="fa fa-plus"></i> Save</button>
          </div>
          </form>
      </div>
      <!-- /.box-body -->
    </div>
    </div>
  </div>
</section>

<div class="buy-now">
<a href="{{ route('delivery.challan') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>



<script type="text/javascript">
var placeholder = "Unit";
var producttbl;
var editableid = 0;
var currentrow = {{ $no }};
function addmorerow() {
  currentrow++;
  var html = '';
  html += '<tr class="tblrow'+currentrow+'" data-id="'+currentrow+'">';
  html += '<td class="ele1">';
  html += '<div class="form-group">';
  html += '<select style="width: 100%;" id="stock_no" name="stock_no[]" class="form-control stock_no select2">';
  html += '<option value="">Select Stock</option>';
  html += '@foreach($stock_list as $row)';
  html += '<option value="{{ $row->id }}" data-pending="{{ $row->pending }}" data-unit="{{ $row->unit }}">{{ Admin::FormateStockItemID($row->id) }} </option>';
  html += '@endforeach';
  html += '</select>';
  html += '</div>';
  html += '</td>';
  html += '<td class="ele2">';
  html += '<div class="form-group">';
  html += '<input type="text" id="description" name="description[]" class="form-control description text-center" autocomplete="false">';
  html += '</div>';
  html += '</td>';
  html += '<td class="ele3">';
  html += '<div class="form-group">';
  html += '<input type="text" id="design_name" name="design_name[]" class="form-control design_name text-center" autocomplete="false">';
  html += '</div>';
  html += '</td>';
  html += '<td class="ele4">';
  html += '<div class="form-group">';
  html += '<input type="text" id="hsn_no" name="hsn_no[]" class="form-control hsn_no text-center" autocomplete="false">';
  html += '</div>';
  html += '</td>';
  html += '<td class="ele5">';
  html += '<div class="form-group">';
  html += '<input type="text" id="quantity" name="quantity[]" class="form-control quantity onlyint text-center checkcalculation" autocomplete="false">';
  html += '</div>';
  html += '</td>';
  html += '<td class="ele6">';
  html += '<div class="form-group">';
  html += '<select id="mesurement" style="width:100%;" name="mesurement[]" class="form-control mesurement select2">';
  html += '@foreach($category_list as $row)';
  html += '<option value="{{ $row->id }}">{{ $row->name }}</option>';
  html += '@endforeach';
  html += '</select>';
  html += '</div>';
  html += '</td>';
  html += '<td class="ele7">';
  html += '<div class="form-group">';
  html += '<input type="text" id="unit" name="unit[]" class="form-control unit onlyint text-center checkcalculation" value="0" autocomplete="false">';
  html += '</div>';
  html += '</td>';
  html += '<td class="ele8">';
  html += '<div class="form-group">';
  html += '<input type="text" id="total" name="total[]" readonly class="form-control total onlyint text-center checkcalculation" value="0" autocomplete="false">';
  html += '</div>';
  html += '</td>';
  html += '<td class="ele9">';
  html += '<button type="button" class="btn btn-danger btn-xs removerowbtn"><i class="fa fa-trash"></i></button>';
  html += '</td>';
  html += '</tr>';
  $(".append_to_me").append(html);
  $(".stock_no").select2();
  $(".mesurement").select2();
}


$(document).on("click",".removerowbtn",function(e){
  $(this).closest('tr').remove();
  checkstockdivs();
  updategrandtotal();
});

function checkstockdivs() {
  if($(".append_to_me > tr").length == "0") {
    addmorerow();
  }
}

$(document).on("change",".stock_no",function(e) {
  var element = $(this).find(":selected");
  $(this).closest('tr').children('.ele5').children('.form-group').children('.quantity').val(element.data("pending"));
  $(this).closest('tr').children('.ele6').children('.form-group').children('.mesurement').val(element.data("unit")).trigger('change');
  var tblid = $(this).closest('tr').attr('data-id');
  updatecalculation(tblid);
});

function updatecalculation(rowid) {
  var tabid = $(".tblrow"+rowid);
  var quantity = tabid.children('.ele5').children('.form-group').children('.quantity').val();
  var unit = tabid.children('.ele7').children('.form-group').children('.unit').val();
  total = 0;
  if(quantity != "" && unit != "") {
    total = quantity * unit;
  }
  // console.log(total);
  tabid.children('.ele8').children('.form-group').children('.total').val(total);
  updategrandtotal();
}

function updategrandtotal() {
  var grandtotal = 0;
  $(".total").each(function(e){
    grandtotal += parseFloat($(this).val());
  });

  $("#grand_total").val(grandtotal.toFixed(2));
}


$(document).on("keyup",".checkcalculation",function(e){
  var tblid = $(this).closest('tr').attr('data-id');
updatecalculation(tblid);
});





$(document).on("change","#process_name",function(e) {

  if($(this).val() == 0) {
    var redirect_route = "{{ config('master.master1')['new'] }}";
    window.location.href = '{{ route("redirecting") }}?redirectback=add.delivery.challan&redirect='+redirect_route;
  }
  var selector = $('#process_name option:selected');
  var state_name = selector.attr('data-state')
  var business_name = selector.attr('data-business')
  if(state_name != "") {
    $("#process_state").val(state_name).trigger('change');
  } else {
    $("#process_state").val('').trigger('change');
  }

  if(business_name != "") {
    $("#business_name").val(business_name);
  } else {
    $("#business_name").val('');
  }
});





$(document).ready(function(e) {
  jcropratio = 0;
  jcropresize = true;
});
</script>

@endsection
