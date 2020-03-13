@extends('admin.v1.layout.app', ['title' => 'Add New Process'])

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
    width: 13%;
}
.mytbl .ele2 {
  width: 15%;
}
.mytbl .ele3, .mytbl .ele4, .mytbl .ele5, .mytbl .ele7, .mytbl .ele9, .mytbl .ele10 {
    width: 9%;
}
.mytbl .ele6, .mytbl .ele8 {
  width: 5%;
}
.mytbl .ele11 {
  width: 8%;
}
.sp_by {
  width: 400px;
}
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-cubes"></i> Add Process
    <small>Add New Process</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Add Process</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <form method="post" action="{{ route('new.process.register') }}" enctype="multipart/form-data">
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

                <div class="col-md-4 process_name_div">
                  <div class="form-group">
                        <label for="process_name">Processor Name</label>
                        <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <select style="width: 100%;" id="process_name" name="process_name" class="form-control select2-bank" data-placeholder="Select Processor">
                          <option value="">Select Processor</option>
                          <option data-img="{{ 'plus.png' }}" value="0">Add New</option>
                          @foreach($process_list as $row)
                          <option data-state="{{ $row->state  }}" data-img="{{ $row->photo }}" value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                          @endforeach
                        </select>
                        </div>
                    </div>
                </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                    <label for="type_of_manufacturer">Type of Process</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <select style="width: 100%;" id="type_of_manufacturer" multiple="multiple" name="type_of_manufacturer[]" class="form-control select2">
                      <?php
                      foreach($manufacturer as $row) {
                        ?>
                        <option value="{{ $row->id }}" {{ Admin::find_in_set(@$pinfo->types_of_menufecture,$row->id) ? "selected" : "" }}>{{ $row->name }}</option>
                        <?php
                      }
                      ?>
                    </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                    <label for="ref_img">Sample Photo</label>
                    <button type="button" class="btn btn-default btn-block" id="imagebtn" onclick="triggerfile('imagebtn','upload_image_text','image/stock/','.jpg,.png,.jpeg')" ><i class="glyphicon glyphicon-folder-open"></i> &nbsp; Browse File</button>
                    <input type="hidden" id="upload_image_text" name="upload_image_text">
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="expected_date">Expected Date</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="expected_date" name="expected_date" value="" class="form-control datepicker">
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
                    <option value="{{ $row->state_id }}" data-gst="{{ $row->gst_code }}" {{ @$pinfo->state == $row->state_id ? "selected" : "" }}>{{ $row->state }}</option>
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
                  <input type="text" id="process_transport" name="process_transport" value="" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="imagebtnbill">Bill Photo</label>
                <button type="button" class="btn btn-default btn-block" id="imagebtnbill" onclick="triggerfile('imagebtnbill','upload_image_text_bill','image/stock/','.jpg,.png,.jpeg')" ><i class="glyphicon glyphicon-folder-open"></i> &nbsp; Browse File</button>
                <input type="hidden" id="upload_image_text_bill" value="" name="upload_image_text_bill">
              </div>
            </div>
          </div>


          <!-- Panel Code goes here -->
          <div class="row">
            <div class="col-md-12">
              <h3 class="page-header">Process Items</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-hover table-striped mytbl fixtablemobile2">
                <thead>
                  <tr>
                    <td class="text-center">Stock</td>
                    <td class="text-center">Description</td>
                    <td class="text-center">Design</td>
                    <td class="text-center">Quantity</td>
                    <td class="text-center">Unit</td>
                    <td class="text-center">Rate</td>
                    <td class="text-center">Disc(%)</td>
                    <td class="text-center">Disc. Amount</td>
                    <td class="text-center">GST(%)</td>
                    <td class="text-center">GST Amount</td>
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
                      <input type="text" id="quantity" name="quantity[]" class="form-control quantity checkcalculation onlyint text-center" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele11">
                      <div class="form-group">
                      <select id="mesurement" style="width:100%;" name="mesurement[]" class="form-control mesurement select2">
                        @foreach($category_list as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                      </select>
                      </div>
                    </td>
                    <td class="ele5">
                      <div class="form-group">
                      <input type="text" id="unit" name="unit[]" class="form-control unit onlyint text-center checkcalculation" value="0" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele6">
                      <div class="form-group">
                      <input type="text" id="discount" name="discount[]" class="form-control discount onlyint text-center checkcalculation" maxlength='2' value="0" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele7">
                      <div class="form-group">
                      <input type="text" id="discount_amount" name="discount_amount[]" readonly class="form-control discount_amount onlyint text-center" value="0" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele8">
                      <div class="form-group">
                      <input type="text" id="gst" name="gst[]" class="form-control gst onlyint text-center checkcalculation" maxlength='2' value="0" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele9">
                      <div class="form-group">
                      <input type="text" id="gst_amount" name="gst_amount[]" readonly class="form-control gst_amount onlyint text-center" value="0" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele10">
                      <div class="form-group">
                      <input type="text" id="total" name="total[]" readonly class="form-control total onlyint text-center checkcalculation" value="0" autocomplete="false">
                      </div>
                    </td>
                    <td class="ele13">
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
                <label for="grand_total">Total: </label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                <input type="text" class="form-control onlyint text-center" readonly value="0" id="grand_total" name="grand_total">
                </div>
              </div>
                </div>
                  <div class="col-md-12">
              <div class="form-group pull-right" style="margin-top: 15px;">
                <label for="less_total">Less Amount : </label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                  <input type="text" class="form-control onlyint text-center" value="0" id="less_total" name="less_total">
                </div>
              </div>
                  </div>
                    <div class="col-md-12">
              <div class="form-group pull-right" style="margin-top: 15px;">
                <label for="final_total">Grand Total : </label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                  <input type="text" class="form-control onlyint text-center" readonly id="final_total" name="final_total">
                </div>
              </div>
                    </div>
              </div>
            </div>
          </div>



          <!-- End of panel -->

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
<a href="{{ route('view.all.process') }}" class="btn btn-primary buy-now-btn">
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
      html += '<div class="form-group">';
      html += '<td class="ele3">';
      html += '<input type="text" id="design_name" name="design_name[]" class="form-control design_name text-center" autocomplete="false">';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele4">';
      html += '<div class="form-group">';
      html += '<input type="text" id="quantity" name="quantity[]" class="form-control quantity onlyint checkcalculation text-center" autocomplete="false">';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele11">';
      html += '<div class="form-group">';
      html += '<select id="mesurement" style="width:100%;"  name="mesurement[]" class="form-control mesurement select2">';
        html += '@foreach($category_list as $row)';
        html += '<option value="{{ $row->id }}">{{ $row->name }}</option>';
        html += '@endforeach';
      html += '</select>';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele5">';
      html += '<div class="form-group">';
      html += '<input type="text" id="unit" name="unit[]" class="form-control unit onlyint text-center checkcalculation" value="0" autocomplete="false">';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele6">';
      html += '<div class="form-group">';
      html += '<input type="text" id="discount" name="discount[]" class="form-control discount onlyint text-center checkcalculation" maxlength="2" value="0" autocomplete="false">';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele7">';
      html += '<div class="form-group">';
      html += '<input type="text" id="discount_amount" name="discount_amount[]" readonly class="form-control discount_amount onlyint text-center" value="0" autocomplete="false">';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele8">';
      html += '<div class="form-group">';
      html += '<input type="text" id="gst" name="gst[]" class="form-control gst onlyint text-center checkcalculation" maxlength="2" value="0" autocomplete="false">';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele9">';
      html += '<div class="form-group">';
      html += '<input type="text" id="gst_amount" name="gst_amount[]" readonly class="form-control gst_amount onlyint text-center" value="0" autocomplete="false">';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele10">';
      html += '<div class="form-group">';
      html += '<input type="text" id="total" name="total[]" readonly class="form-control total onlyint text-center checkcalculation" value="0" autocomplete="false">';
      html += '</div>';
    html += '</td>';
  html += '<td class="ele13">';
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

function checkpaymentdivs() {
  if($("#append_payment_row > tr").length == "0") {
    addmorepayment();
  }
}

function assignvaluetoedit(e) {
  resetquantityform();
  // console.log(e);
  editableid = e.id;
  $('#fbholdernew').attr('src','<?php echo url('public'); ?>/'+e.photo);
  $('.fbinputtxt').val(e.photo);
  $("#product_name").val(e.product_name);
  $('#category').val(e.category).trigger('change');
  $('#unit').val(e.unit).trigger('change');
  $( ".iquantity" ).first().val(e.quantity[0].quantity);
  $(".imeasurement").first().val(e.quantity[0].mesurement);
  $(".icolorfirst").val(e.quantity[0].color);

  $.each(e.quantity, function(i, item) {
    if(i != 0) {
      add_quantity(item.quantity,item.mesurement,item.color);
    }
  });

  $(".modalsubmitbtn").attr("onclick","return checkquantityval('update')");
  updatetotal();
  $("#addmyproduct").modal('show');
}

function resetquantityform() {
  var default_holder = $('#fbholdernew').attr('data-default');
  $('#fbholdernew').attr('src',default_holder);
  $('.fbinputtxt').val('');

  $("#product_name").val('');
  // $('#category').val('').trigger('change');
  // $('#unit').val('').trigger('change');
  $('#category').val(null).trigger('change');
  $('#unit').val(null).trigger('change');

  $( ".iquantity" ).first().val('1');
  $(".imeasurement").first().val('');
  $(".icolorfirst").val('');

  $('.remove_quantity').each(function(){
    $(this).parent('.form-group').parent('.rmbtn').parent('.row').remove();
  });

  updatetotal();
}


function updatecalculation(rowid) {
  var tabid = $(".tblrow"+rowid);
  var quantity = tabid.children('.ele4').children('.form-group').children('.quantity').val();
  var unit = tabid.children('.ele5').children('.form-group').children('.unit').val();
  var discount = tabid.children('.ele6').children('.form-group').children('.discount').val();
  var gst = tabid.children('.ele8').children('.form-group').children('.gst').val();
  total = 0;
  if(quantity != "" && unit != "") {
    total = quantity * unit;
  }
  if(discount != "" && discount != 0) {
    var dec = (discount / 100).toFixed(2);
    var mult = total * dec;
    total = total - mult;
    tabid.children('.ele7').children('.form-group').children('.discount_amount').val(mult.toFixed(2));
  }
  else {
    tabid.children('.ele7').children('.form-group').children('.discount_amount').val(0);
  }
  if(gst != "" && gst != 0) {
    var dec = (gst / 100).toFixed(2);
    var mult = total * dec;
    total = total + mult;
    tabid.children('.ele9').children('.form-group').children('.gst_amount').val(mult.toFixed(2));
  } else {
    tabid.children('.ele9').children('.form-group').children('.gst_amount').val(0);
  }
  // console.log(total);
  tabid.children('.ele10').children('.form-group').children('.total').val(total.toFixed(2));
  updategrandtotal();
}

$(document).on("keyup","#less_total",function(e){
  updategrandtotal();
});

function updategrandtotal() {
  var grandtotal = 0;
  $(".total").each(function(e){
    grandtotal += parseFloat($(this).val());
  });

  $("#grand_total").val(grandtotal.toFixed(2));

  if($("#less_total").val()) {
    var loss_amount = parseFloat($("#less_total").val());
    grandtotal -= loss_amount;
  }
  $("#final_total").val(grandtotal.toFixed(2));
}

$(document).on("click",".addproductbtn",function(e){
resetquantityform();
$(".modalsubmitbtn").attr("onclick","return checkquantityval('add')");
});


$(document).on("change",".stock_no",function(e) {
  var element = $(this).find(":selected");
  $(this).closest('tr').children('.ele4').children('.form-group').children('.quantity').val(element.data("pending"));
  $(this).closest('tr').children('.ele5').children('.form-group').children('.myunit').val(element.data("unit"));
  var tblid = $(this).closest('tr').attr('data-id');
  updatecalculation(tblid);
});

$(document).on("keyup",".checkcalculation",function(e){
  var tblid = $(this).closest('tr').attr('data-id');
updatecalculation(tblid);
});


$(document).on("click",".editstockproductbtn",function(e) {
  var url = '{{ route('stock.item.info',":ID") }}';
  url = url.replace(':ID', $(this).attr('data-id'));
  $.ajax({
    url:url,
    type:'POST',
    data:"_token={{ csrf_token() }}",
    success:function(e) {
        if(e.status == "true" && e.message == "success") {
          assignvaluetoedit(e.result);
        } else {
          toastr.error("Oops..! Something went wrong");
          $("#addmyproduct").modal('hide');
        }
    }
  });
});

function editproductsync() {
  var data = $("#quantity_form").serialize();
  $.ajax({
    url:'{{ route('stock.quantity.update') }}',
    type:'POST',
    data: data+"&mainid="+editableid,
    success: function(e) {
      // console.log(e);
      if(e.status == "true" && e.message == "success") {
        toastr.success("Product Updated Successfully");
        producttbl.draw(true);
        $("#addmyproduct").modal('hide');
        resetquantityform();
      } else {
        toastr.error(e.message);
      }
    }
  })
}

$(document).ready(function() {
  initdatepicker(true);
  producttbl = $('.dtable').DataTable({
    "dataSrc": "Data",
    "processing" : true,
    "searching": false,
    "ordering": false,
    "paging": false,
    "serverSide" : true,
    "ajax" : "{{ route('stock.product.ajax') }}",
    "bAutoWidth": false,
    "columns" : [
      {"data":"stock_info","sWidth": "30%"},
      {"data":"category_name","sWidth": "10%"},
      {"data":"stock_quantity_html","sWidth": "25%"},
      // {"data":"mesurement_name","sWidth": "15%"},
      {"data":"action","sWidth": "35%"},
    ],
    "fnDrawCallback": function() {
            // $('.status_checkbox').bootstrapToggle();
            // $('.make-switch').bootstrapSwitch();
        },
  });
});


function removeproduct(id) {

  var conf = confirm('Are you sure want to delete this record?');
  if(conf === false) {
    return false;
  }

  var route = '{{ route('stock.product.remove',":ID") }}';
  route = route.replace(':ID', id);
  $.ajax({
    url:route,
    type:'POST',
    data: "_token={{ csrf_token() }}",
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        toastr.success("Product Removed Successfully");
      } else {
        toastr.error(e.message);
      }
      producttbl.draw(true);
    }
  });
}





function checkquantityval(type) {
  if($("#product_name").val() == "")
	{
		toastr.error("Please, Enter Product Name");
		$("#product_name").focus();
		return false;
	}

  if($("#category").val() == "")
	{
		toastr.error("Please, Select Category");
		$("#category").focus();
		return false;
	}

  if($("#unit").val() == "")
	{
		toastr.error("Please, Select Unit");
		$("#unit").focus();
		return false;
	}

  if(type == "update") {
    //update product
    editproductsync();
  } else {
    syncproduct();
  }

}
function updatetotal() {
  var total = [];
  var iquantity = $( ".iquantity" ).first().val();
  var imeasurement = $(".imeasurement").first().val();
  if(iquantity && imeasurement) {

    var iquantity_arr = [];
    $('.iquantity').each(function(){
        iquantity_arr.push(parseInt($(this).val()));
    });

    var imeasurement_arr = [];
    $('.imeasurement').each(function(){
        imeasurement_arr.push(parseInt($(this).val()));
    });

    var total_imeasurement = 0;
    var total_iquantity = 0;
    for (var i = 0; i < imeasurement_arr.length; i++) {
        if(Number(imeasurement_arr[i]) > 0 && Number(iquantity_arr[i]) > 0) {
        total_imeasurement = imeasurement_arr[i] << 0;
        total_iquantity = iquantity_arr[i] << 0;
        total.push(parseInt(total_imeasurement) * parseInt(total_iquantity));
        }
    }
    $(".totalquantityctndiv").text("Total: "+ $.sum(total)+" "+placeholder);
    $(".totalctndiv").show();

  } else {
    $(".totalctndiv").hide();
  }
}

function add_quantity(quantity = 1, mesurement = "", colors = "") {
  var html = '';
  html += '<div class="row">';
    html += '<div class="col-md-2">';
      html += '<div class="qty mt-5">';
          html += '<span class="minus bg-dark">-</span>';
          html += '<input type="text" id="avaliblity" class="count iquantity onlyint" onkeyup="updatetotal()" name="qty[]" min="1" max="1000000" value="'+quantity+'">';
          html += '<span class="plus bg-dark">+</span>';
      html += '</div>';
    html += '</div>';
    html += '<div class="col-md-4">';
      html += '<div class="form-group">';
        html += '<div class="input-group">';
        html += '<span class="input-group-addon"><i class="fa fa-user"></i></span>';
        html += '<input type="text" id="measurement" name="measurement[]" placeholder="'+placeholder+'" value="'+mesurement+'" class="imeasurement form-control onlyint">';
        html += '</div>';
      html += '</div>';
    html += '</div>';
    html += '<div class="col-md-4">';
      html += '<div class="form-group">';
        html += '<div class="input-group">';
        html += '<span class="input-group-addon"><i class="fa fa-user"></i></span>';
        html += '<input type="text" id="colors" name="colors[]" placeholder="Colors" value="'+colors+'" class="form-control">';
        html += '</div>';
      html += '</div>';
    html += '</div>';
    html += '<div class="col-md-2 rmbtn">';
      html += '<div class="form-group">';
        html += '<button type="button" class="btn btn-danger remove_quantity btn-xs"><i class="fa fa-trash"></i> Delete</button>';
      html += '</div>';
  html += '</div>';
  html += '</div>';

  $(".qunatity_holder").append(html);
  $(".onlyint").ForceNumericOnly();

}

function val_add_party()
{
  if($("#entry_type").val() == "")
	{
		toastr.error("Please, Select Entry Type");
		$("#entry_type").focus();
		return false;
	}

  if($("#adjustment_date").val() == "")
	{
		toastr.error("Please, Select Date");
		$("#adjustment_date").focus();
		return false;
	}
  if($('#entry_type').val() == "cash1" || $('#entry_type').val() == "cash2") {
    if($("#bank_account").val() == "") {
      toastr.error("Please, Select Bank Account");
  		$("#bank_account").focus();
  		return false;
    }
  }
}


$(document).on("change","#process_name",function(e) {

  if($(this).val() == 0) {
    var redirect_route = "{{ config('master.master6')['new'] }}";
    window.location.href = '{{ route("redirecting") }}?redirectback=add.new.process&redirect='+redirect_route;
  }

  var state_name = $('#process_name option:selected').attr('data-state')
  if(state_name != "") {
    $("#process_state").val(state_name).trigger('change');
  } else {
    $("#process_state").val('').trigger('change');
  }
});


$(document).on("change","#unit",function(e) {

  if($(this).val() != "") {
    placeholder = $("#unit option:selected").text();
  } else {
    placeholder = "Unit";
  }

  $(".imeasurement").attr('placeholder',placeholder);
  updatetotal();
});

$(document).on('change','#entry_type',function(e) {
  if($(this).val() == "cheque4") {
    $(".banklist").show();
  } else {
    $(".banklist").hide();
  }
});

$(document).on('keyup','.imeasurement',function(){
  updatetotal();
});

$(document).on('click','.remove_quantity',function(){
  $(this).parent('.form-group').parent('.rmbtn').parent('.row').remove();
  updatetotal();
});

$(document).on('click','.plus',function(){
 var maxval = $(this).parent(".qty").children('.count').attr("max");
 var x =  $(this).parent(".qty").children('.count').val();
 if(parseInt(x) < parseInt(maxval))
 {
 $(this).parent(".qty").children('.count').val(parseInt($(this).parent(".qty").children('.count').val()) + 1 );
 // dosync('control',this);
 updatetotal();
}
});

$(document).on('click','.minus',function(){
 var minval = $(this).parent(".qty").children('.count').attr("min");
 var x =  $(this).parent(".qty").children('.count').val();
 if(parseInt(x) > parseInt(minval))
 {
    $(this).parent(".qty").children('.count').val(parseInt($(this).parent(".qty").children('.count').val()) - 1 );
    // dosync('control',this);
    updatetotal();
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
  jcropratio = 0;
  jcropresize = true;
});
</script>

@endsection
