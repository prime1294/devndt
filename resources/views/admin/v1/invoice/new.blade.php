@extends('admin.v1.layout.app', ['title' => 'Invoice'])

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
  width: 20%;
}
.mytbl .ele3, .mytbl .ele4, .mytbl .ele5, .mytbl .ele6 {
  width: 8%;
}
.mytbl .ele7 {
  width: 6%;
}
.mytbl .ele8 {
  width: 12%;
}
.mytbl .ele9 {
  width: 3%;
}
.mytbl .ele10 {
  width: 5%;
}
.mytbl .ele11, .mytbl .ele12 {
  width: 5%;
}
.sp_by {
  width: 400px;
}
.ele10 {
  display: none;
}
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-cubes"></i> Invoice
    <small>Add Invoice</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Add Invoice</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <form method="post" action="{{ route('invoice.register') }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Payment Type</label>
                <input type="checkbox" value="1" id="confirmAns" name="confirmAns" class="confirmAns" data-size="mini" data-toggle="toggle" data-on="Cash" data-off="Credit" data-onstyle="success" data-offstyle="info">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="adjustment_date">Date</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="adjustment_date" name="adjustment_date" value="" class="form-control datepicker">
                </div>
              </div>
              </div>

            <div class="col-md-4 due_date_div">
              <div class="form-group">
                <label for="due_date">Due Date</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="text" id="due_date" name="due_date" value="" class="form-control datepicker">
                </div>
              </div>
            </div>

                <div class="col-md-4">
                  <div class="form-group">
                        <label for="process_name">Party Name</label>
                        <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <select id="process_name" name="process_name" class="form-control select2-bank" data-placeholder="Select Party">
                          <option value="">Select Party</option>
                          <option data-img="{{ 'plus.png' }}" value="0">Add New</option>
                          @foreach($party_list as $row)
                          <option data-state="{{ $row->state  }}" data-business="{{ $row->business_name }}" data-img="{{ $row->photo }}" value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                          @endforeach
                        </select>
                        </div>
                    </div>
                </div>

          </div>


          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="business_name">Business Name</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="text" id="business_name" name="business_name" value="" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="agent_name">Agent Name</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                  <select id="agent_name" name="agent_name" class="form-control select2-bank" data-placeholder="Select Agent">
                    <option value="">Select Party</option>
                    <option data-img="{{ 'plus.png' }}" value="0">Add New</option>
                    @foreach($agent_list as $row)
                      <option data-state="{{ $row->state  }}" data-business="{{ $row->business_name }}" data-img="{{ $row->photo }}" value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
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
                    <option value="{{ $row->state_id }}" data-gst="{{ $row->gst_code }}" {{ @$pinfo->state == $row->state_id ? "selected" : "" }}>{{ $row->state }}</option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="process_transport">Transport Detail</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="text" id="process_transport" name="process_transport" value="" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="challan_no">Challan No</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="text" id="challan_no" name="challan_no" value="" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="dc_no">Delivery Challan No</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="text" id="dc_no" name="dc_no" value="" class="form-control">
                </div>
              </div>
            </div>
          </div>


          <!-- Panel Code goes here -->
          <div class="row">
            <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-hover table-striped mytbl fixtablemobile2">
                <thead>
                  <tr class="bg-primary">
                    <td class="text-center">Stock</td>
                    <td class="text-center">Description</td>
                    <td class="text-center">Design</td>
                    <td class="text-center">HSN code</td>
                    <td class="text-center">Quantity</td>
                    <td class="text-center">Unit</td>
                    <td class="text-center" style="display: none;">Length</td>
                    <td class="text-center">Rate</td>
                    <td class="text-center">Disc(%)</td>
                    <td class="text-center">GST(%)</td>
                    <td class="text-center">Total</td>
                    <td class="text-center"></td>
                  </tr>
                </thead>
                <tbody class="append_to_me">
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
                    <td class="ele10">
                      <div class="form-group">
                        <input type="text" id="length" name="length[]" class="form-control length onlyint text-center checkcalculation" value="0" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele7">
                      <div class="form-group">
                      <input type="text" id="unit" name="unit[]" class="form-control unit onlyint text-center checkcalculation" value="0" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele11">
                      <div class="form-group">
                        <input type="text" id="discount" name="discount[]" class="form-control discount onlyint text-center checkcalculation" maxlength='2' value="0" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele12">
                      <div class="form-group">
                        <input type="text" id="gst" name="gst[]" class="form-control gst onlyint text-center checkcalculation" maxlength='2' value="0" autocomplete="false">
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
              <div class="row">
                <div class="col-md-12">
              <div class="form-group pull-right">
                <label for="grand_total">Subtotal: </label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                <input type="text" class="form-control onlyint text-center" readonly value="0" id="grand_total" name="grand_total">
                </div>
              </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group mt-sm pull-right">
                    <label for="loss_amount">Loss Amount: </label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                      <input type="text" class="form-control onlyint text-center" value="0" id="loss_amount" name="loss_amount">
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
              <div class="form-group mt-sm pull-right">
                <label for="grand_tax">T.D.S: </label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                  <input type="text" class="form-control onlyint text-center" value="0" id="grand_tax" name="grand_tax">
                </div>
              </div>
                </div>
                <div class="col-md-12">
              <div class="form-group mt-sm pull-right">
                <label for="main_total">Grand Total: </label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                  <input type="text" readonly class="form-control onlyint text-center" value="0" id="main_total" name="main_total">
                </div>
              </div>
                </div>
              </div>
            </div>
          </div>


          <div class="row mt-20">
            <div class="col-md-12">
              <h3 class="page-header">Payment History</h3>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered fixtablemobile">
              <thead>
              <tr class="bg-primary">
                <th class="text-center">Date</th>
                <th class="text-center">Pay By</th>
                <th class="text-center">Ref No</th>
                <th class="text-center">Amount</th>
                <th class="text-center">Remarks</th>
                <th class="text-center"></th>
              </tr>
              </thead>
              <tbody id="append_payment_row">
              <tr>
                <td><input type="text" id="sp_date" name="sp_date[]" class="form-control datepicker"></td>
                <td>
                  <select id="sp_by" name="sp_by[]" class="form-control sp_by select2">
                    <option value="cash">Cash</option>
                    <option value="cheque">Cheque</option>
                    @if(count($bank_list) != 0)
                      <optgroup label="Banks">
                        @foreach ($bank_list as $row)
                          <option value="bank_ref_{{ $row->id }}">{{ $row->name }} - {{ $row->bankname }} - {{ $row->account_no }} - {{ $row->type == 2 ? "Current" : "Saving" }}</option>
                        @endforeach
                      </optgroup>
                    @endif
                  </select>
                </td>
                <td><input type="text" id="sp_ref_no" name="sp_ref_no[]" class="form-control sp_ref_no"></td>
                <td><input type="text" id="sp_amount" name="sp_amount[]" class="form-control sp_amount onlyint"></td>
                <td><input type="text" id="sp_remarks" name="sp_remarks[]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-xs removerowpayment"><i class="fa fa-trash"></i></button></td>
              </tr>
              </tbody>
            </table>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <button type="button" class="btn btn-primary" onclick="addmorepayment()"><i class="fa fa-plus"></i> Add Row</button>
              </div>
            </div>
          </div>

          <div class="row mt-sm">
            <div class="col-md-12">
              <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea id="remarks" name="remarks" class="form-control" rows="2"></textarea>
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
<a href="{{ route('invoice') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>



<script type="text/javascript">
var placeholder = "Unit";
var producttbl;
var editableid = 0;
var currentrow = 1;
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
  html += '<td class="ele10">';
  html += '<div class="form-group">';
  html += '<input type="text" id="length" name="length[]" class="form-control length onlyint text-center checkcalculation" value="0" autocomplete="false">';
  html += '</div>';
  html += '</td>';
  html += '<td class="ele7">';
  html += '<div class="form-group">';
  html += '<input type="text" id="unit" name="unit[]" class="form-control unit onlyint text-center checkcalculation" value="0" autocomplete="false">';
  html += '</div>';
  html += '</td>';
  html += '<td class="ele11">';
  html += '<div class="form-group">';
  html += '<input type="text" id="discount" name="discount[]" class="form-control discount onlyint text-center checkcalculation" maxlength="2" value="0" autocomplete="false">';
  html += '</div>';
  html += '</td>';
  html += '<td class="ele12">';
  html += '<div class="form-group">';
  html += '<input type="text" id="gst" name="gst[]" class="form-control gst onlyint text-center checkcalculation" maxlength="2" value="0" autocomplete="false">';
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

function addmorepayment() {
  var today = new Date();
  var dd = String(today.getDate()).padStart(2, '0');
  var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = today.getFullYear();
  var html = '';
  html += '<tr>';
  html += '<td><input type="text" id="sp_date" name="sp_date[]" value="'+dd+'-'+mm+'-'+yyyy+'" class="form-control datepicker"></td>';
  html += '<td>';
  html += '<select id="sp_by" name="sp_by[]" class="form-control sp_by select2">';
  html += '<option value="cash">Cash</option>';
  html += '<option value="cheque">Cheque</option>';
  html += '@if(count($bank_list) != 0)';
  html += '<optgroup label="Banks">';
  html += '@foreach ($bank_list as $row)';
  html += '<option value="bank_ref_{{ $row->id }}">{{ $row->name }} - {{ $row->bankname }} - {{ $row->account_no }} - {{ $row->type == 2 ? "Current" : "Saving" }}</option>';
  html += '@endforeach';
  html += '</optgroup>';
  html += '@endif';
  html += '</select>';
  html += '</td>';
  html += '<td><input type="text" id="sp_ref_no" name="sp_ref_no[]" class="form-control sp_ref_no"></td>';
  html += '<td><input type="text" id="sp_amount" name="sp_amount[]" class="form-control sp_amount onlyint"></td>';
  html += '<td><input type="text" id="sp_remarks" name="sp_remarks[]" class="form-control"></td>';
  html += '<td><button type="button" class="btn btn-danger btn-xs removerowpayment"><i class="fa fa-trash"></i></button></td>';
  html += '</tr>';
  $("#append_payment_row").append(html);
  initdatepicker(false);
  $('.select2').select2();
}

$(document).on("click",".removerowpayment",function(e) {
  $(this).closest('tr').remove();
  checkpaymentdivs();
});

function checkpaymentdivs() {
  if($("#append_payment_row > tr").length == "0") {
    addmorepayment();
  }
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
  var discount = tabid.children('.ele11').children('.form-group').children('.discount').val();
  var gst = tabid.children('.ele12').children('.form-group').children('.gst').val();
  total = 0;
  if(quantity != "" && unit != "") {
    total = quantity * unit;
  }
  if(discount != "" && discount != 0) {
    var dec = (discount / 100).toFixed(2);
    var mult = total * dec;
    total = total - mult;
    // tabid.children('.ele7').children('.form-group').children('.discount_amount').val(mult);
  }
  else {
    // tabid.children('.ele7').children('.form-group').children('.discount_amount').val(0);
  }
  if(gst != "" && gst != 0) {
    var dec = (gst / 100).toFixed(2);
    var mult = total * dec;
    total = total + mult;
    // tabid.children('.ele9').children('.form-group').children('.gst_amount').val(mult);
  } else {
    // tabid.children('.ele9').children('.form-group').children('.gst_amount').val(0);
  }
  // console.log(total);
  tabid.children('.ele8').children('.form-group').children('.total').val(total.toFixed(2));
  updategrandtotal();
}

$(document).on("keyup","#grand_tax",function(e){
  updategrandtotal();
});
$(document).on("keyup","#loss_amount",function(e){
  updategrandtotal();
});

function updategrandtotal() {
  var grandtotal = 0;
  var main_total  = 0;
  $(".total").each(function(e){
    grandtotal += parseFloat($(this).val());
  });

  $("#grand_total").val(grandtotal.toFixed(2));

  var loss = 0;
  if($("#loss_amount").val()) {
    var loss_amount = parseFloat($("#loss_amount").val());
    grandtotal -= loss_amount;
  }

  if($("#grand_tax").val()) {
    var tax_amount = parseFloat($("#grand_tax").val());
    grandtotal -= tax_amount;
  }

  $("#main_total").val(grandtotal.toFixed(2));
}


$(document).on("keyup",".checkcalculation",function(e){
  var tblid = $(this).closest('tr').attr('data-id');
updatecalculation(tblid);
});






$(document).on("change","#process_name",function(e) {

  if($(this).val() == 0) {
    var redirect_route = "{{ config('master.master1')['new'] }}";
    window.location.href = '{{ route("redirecting") }}?redirectback=add.invoice&redirect='+redirect_route;
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

$(document).on("change","#agent_name",function(e) {

  if($(this).val() == 0) {
    var redirect_route = "{{ config('master.master2')['new'] }}";
    window.location.href = '{{ route("redirecting") }}?redirectback=add.invoice&redirect='+redirect_route;
  }
});


$(function() {
  $('#confirmAns').change(function() {
    if($(this).prop('checked')) {
      //yes readonly
      $(".due_date_div").hide();
    } else {
      //no readonly
      $(".due_date_div").show();
    }
  })
})


$(document).ready(function(e) {
  initdatepicker(true);
  jcropratio = 0;
  jcropresize = true;
});
</script>

@endsection
