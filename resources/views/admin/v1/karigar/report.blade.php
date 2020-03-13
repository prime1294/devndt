@extends('admin.v1.layout.app', ['title' => 'Karigar Report of '.$report_month])

@section('content')
<!-- Content Header (Page header) -->
<style type="text/css">
.no_1 {
  width: 15%;
}
.no_2 {
  width: 15%;
}
.no_3 {
  width: 8%;
}
.no_4 {
  width: 10%;
}
.no_5 {
  width: 15%;
}
.no_6 {
  width: 12%;
}
.no_7 {
  width: 12%;
}
.no_8 {
  width: 13%;
}
.page-header {
  border-bottom: 1px solid #242424;
}
.text-status {
    font-size: 16px;
    margin-left: 16px;
    color:green;
}
.top-image {
  width: 50px;
}
@media only screen and (max-width: 768px) {
  .small-box .inner p {
    font-weight: bolder;
    font-size: 18px;
  }
  .small-box .icon {
    display: block;
    font-size:60px;
  }
}
</style>
<section class="content-header">
  <h1>
    <img class="img-circle top-image" src="{{ asset($karigar_info->photo) }}" alt="User Avatar"> {{ ucwords($karigar_info->name) }}
    <small> of {{ $report_month }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Karigar Report</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-lg-3 col-xs-12">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3 id="total-box">0</h3>
          <p>{{ $report_month }}</p>
        </div>
        <div class="icon">
          <i class="fa fa-inr"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered fixtablemobile">
            <thead>
              <tr class="bg-primary">
                <th>Date</th>
                <th>Machine</th>
                <th>Ship</th>
                <th>Stitch</th>
                <th>Salary Type</th>
                <th>Salary</th>
                <th>Bonus</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @if(!$karigar_report->isEmpty())
              @foreach($karigar_report as $row)
              <tr id="rep_row_{{ $row->id }}">
                <td class="no_1">{{ Admin::FormateDate($row->date) }}</td>
                <td class="no_2">{{ $row->machine_no.' - '.$row->company_name }}</td>
                <td class="no_3">{{ $row->ship == 2 ? "Night" : "Day" }}</td>
                <td class="no_4 stitch_calculation">{{ $row->stitch }}</td>
                <td class="no_5">
                  <select id="stype" name="stype" onchange="updaterow({{ $row->id }})" class="form-control stype">
                    <option value="0" {{ $row->salary_type == 0 ? "selected" : "" }}>Select Type</option>
                    <option value="1" {{ $row->salary_type == 1 ? "selected" : "" }}>Salary</option>
                    <option value="2" {{ $row->salary_type == 2 ? "selected" : "" }}>On Production</option>
                  </select>
                </td>
                <td class="salary_calculation no_6">
                  <input type="text" id="salary" name="salary" readonly value="{{ number_format($row->salary,2) }}" class="form-control text-center onlyint">
                </td>
                <td class="bonus_calculation no_7">
                  <input type="text" id="bonus" name="bonus" value="{{ number_format($row->bonus,2) }}" onchange="updaterow({{ $row->id }})" class="form-control text-center onlyint">
                </td>
                <td class="no_8">
                  <a class="btn btn-primary btn-xs" onclick="getDetailofReport({{ $row->id }})" data-toggle="modal" data-target="#editReportModal"><i class="fa fa-edit"></i> Edit</a>
                  <a class="btn btn-danger btn-xs" onclick="deleteentry(this,{{ $row->id }})"><i class="fa fa-trash"></i> Delete</a>
                </td>
              </tr>
              @endforeach
              @else
              <td colspan="8" class="text-center">No Report Found on {{ $report_month }}</td>
              @endif
            </tbody>
            <tfoot>
              <tr class="bg-success">
                <th>Total</th>
                <th></th>
                <th></th>
                <th id="total_stitch"></th>
                <th></th>
                <th id="total_salary" class="text-center"></th>
                <th id="total_bonus" class="text-center"></th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <h3 class="page-header">Payment History   <span class="text-status text-muted"><i class="fa fa-check-circle"></i> <span class="payment_status">Pending</span></span></h3>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <form method="post" id="salary_report">
        {!! csrf_field() !!}
      <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered fixtablemobile">
        <thead>
          <tr class="bg-primary">
            <th style="width: 10%;">Date</th>
            <th style="width: 15%;">Type</th>
            <th style="width: 45%;">Pay By</th>
            <th style="width: 10%;">Ref.No</th>
            <th style="width: 10%;">Amount</th>
            <th style="width: 10%;">Action</th>
          </tr>
        </thead>
        <tbody id="append_payment_row">
          @if(!$karigar_payment->isEmpty())
          @foreach($karigar_payment as $row)
          <tr data-id="{{ $row->id  }}">
            <td><input type="text" id="sp_date" name="sp_date[]" value="{{ date('d-m-Y',strtotime($row->date)) }}" class="form-control datepicker"></td>
            <td>
              <select id="sp_type" name="sp_type[]" class="form-control sp_type">
                <option value="1" {{ $row->pay_type == 1 ? "selected" : "" }}>Salary</option>
                <option value="2" {{ $row->pay_type == 2 ? "selected" : "" }}>Withdrawal</option>
                <option value="3" {{ $row->pay_type == 3 ? "selected" : "" }}>Loss</option>
              </select>
            </td>
            <td>
              <select style="width: 100%;" id="sp_by" name="sp_by[]" class="form-control sp_by select2">
                <option value="cash" {{ $row->pay_by == "cash" ? "selected" : "" }}>Cash</option>
                <option value="cheque" {{ $row->pay_by == "cheque" ? "selected" : "" }}>Cheque</option>
                @if(count($bank_list) != 0)
                  <optgroup label="Banks">
                    @foreach ($bank_list as $rr)
                      <option value="bank_ref_{{ $rr->id }}" {{ $row->pay_by == "bank_ref_".$rr->id ? "selected" : "" }}>{{ $rr->name }} - {{ $rr->bankname }} - {{ $rr->account_no }} - {{ $rr->type == 2 ? "Current" : "Saving" }}</option>
                    @endforeach
                  </optgroup>
                @endif
              </select>
            </td>
            <td><input type="text" id="sp_remarks" name="sp_remarks[]" value="{{ $row->remarks }}" class="form-control"></td>
            <td><input type="text" id="sp_amount" name="sp_amount[]" value="{{ intval($row->amount) }}" class="form-control sp_amount onlyint"></td>
            <td><button type="button" class="btn btn-danger btn-xs removerowpayment"><i class="fa fa-trash"></i> Delete</button></td>
          </tr>
          @endforeach
          @else
          <tr>
            <td><input type="text" id="sp_date" name="sp_date[]" class="form-control datepicker"></td>
            <td>
              <select id="sp_type" name="sp_type[]" class="form-control sp_type">
                <option value="1">Salary</option>
                <option value="2">Withdrawal</option>
                <option value="3">Loss</option>
              </select>
            </td>
            <td>
              <select style="width: 100%;" id="sp_by" name="sp_by[]" class="form-control sp_by select2">
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
            <td><input type="text" id="sp_remarks" name="sp_remarks[]" class="form-control"></td>
            <td><input type="text" id="sp_amount" name="sp_amount[]" class="form-control sp_amount onlyint"></td>
            <td><button type="button" class="btn btn-danger btn-xs removerowpayment"><i class="fa fa-trash"></i> Delete</button></td>
          </tr>
          @endif
        </tbody>
        </table>
      </div>
      </form>
    </div>
    <div class="col-md-12">
      <button class="btn btn-info" onclick="appendrow()"><i class="fa fa-plus"></i> Add New</button>
      <button class="btn btn-primary" onclick="syncpayment()"><i class="fa fa-upload"></i> Submit Payment</button>
    </div>
  </div>
</section>

<div class="buy-now">
<a href="{{ route('karigar.view',$karigar_info->id) }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>

<!-- Modal -->
<div id="editReportModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Report</h4>
      </div>
      <form method="post" action="{{ route('update.karigar.report',$payment_route) }}">
        {!! csrf_field() !!}
        <input type="hidden" id="report_id" name="report_id" value="0">
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6" style="display: none;">
            <div class="form-group">
              <label for="adjustment_date">Date</label>
              <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <input type="text" id="adjustment_date" name="adjustment_date" class="form-control datepicker">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
            <label for="machine">Machine</label>
            <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
            <select style="width:100%;" id="machine" name="machine" class="form-control select2-bank" data-placeholder="Select Machine">
              <option value="">Select Machine</option>
              @foreach($machine_list as $row)
              <option data-img="{{ $row->photo }}" value="{{ $row->id }}">{{ $row->machine_no }} - {{ ucwords($row->company_name) }}</option>
              @endforeach
            </select>
            </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="ship">Ship</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <select style="width:100%;" id="ship" name="ship" class="form-control select2">
                  <option value="1">Day</option>
                  <option value="2">Night</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="row">

          <div class="col-md-6">
            <div class="form-group">
              <label for="stitch">Stitch</label>
              <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <input type="text" id="stitch" name="stitch" class="form-control onlyint">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="tb_no">T.B No.</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" id="tb_no" name="tb_no" class="form-control onlyint">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" onclick="return checkval()"><i class="fa fa-upload"></i> Update</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>

<script type="text/javascript">
var final_salary = 0;
function getDetailofReport(id) {
  $.ajax({
    url:'{{ route('info.karigar.report',$karigar_info->id) }}',
    data:'id='+id,
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        $("#report_id").val(e.result.id);
        $("#adjustment_date").datepicker( "setDate", new Date(e.result.date_new));
        $("#machine").val(e.result.machine).trigger('change');
        $("#ship").val(e.result.ship).trigger('change');
        $("#stitch").val(e.result.stitch);
        $("#tb_no").val(e.result.tbno);
      } else {
        toastr.error(e.message);
        $("#editReportModal").modal('hide');
      }
    }
  });
}
function checkval() {
  if($("#adjustment_date").val() == "") {
    toastr.error("Please, Select date");
    $("#adjustment_date").focus();
    return false;
  }
  if($("#machine").val() == "") {
    toastr.error("Please, Select Machine");
    $("#machine").focus();
    return false;
  }
  if($("#ship").val() == "") {
    toastr.error("Please, Select Ship");
    $("#ship").focus();
    return false;
  }
  if($("#stitch").val() == "") {
    toastr.error("Please, Enter Stitch");
    $("#stitch").focus();
    return false;
  }
}
function deleteentry(element,id) {
  var conf = confirm('Are you sure want to delete this record?');
  if(conf) {
  $.ajax({
    url:'{{ route('delete.karigar.report',$karigar_info->id) }}',
    data:'id='+id,
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        $(element).closest('tr').remove();
        toastr.success("Report removed successfully");
      } else {
        toastr.error("Ooops..! Something went wrong");
      }
    }
  });

  //update calculation
  update_calculation();
  }
}

function syncpayment() {
  $.ajax({
    url:'{{ route('manage.karigar.payment',$payment_route) }}',
    type:'POST',
    data:$("#salary_report").serialize(),
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        toastr.success("Payment saved successfully");
        location.reload();
      } else {
        toastr.error("Ooops..! Something went wrong");
      }
      tracksalarystatus();
    }
  });
}

function updaterow(id) {
  //get salary type
  var salary_type = $("#rep_row_"+id+" .stype").val();

  //update calculation
  var current_salary = 0;
  if(salary_type == 1) {
    current_salary = parseFloat('{{ $fixed_salary }}').toFixed(2);
  } else if(salary_type == 2) {
    var total_stitch = parseInt($("#rep_row_"+id+" .stitch_calculation").text());
    current_salary = parseFloat(total_stitch / 1000).toFixed(2);
  } else {
    current_salary = 0.00;
  }
  $("#rep_row_"+id+" .salary_calculation input").val(current_salary);

  //get salary
  //var salary = $("#rep_row_"+id+" .salary_calculation input").val();

  //get bonus
  var bonus = $("#rep_row_"+id+" .bonus_calculation input").val();

  //ajax call
  $.ajax({
    url:'{{ route('update.karigar.salary',$karigar_info->id) }}',
    type:'POST',
    data:'_token={{ csrf_token() }}&type='+salary_type+'&salary='+current_salary+'&bonus='+bonus+'&id='+id,
    success:function(e) {
        //console.log(e);
        if(e.status == "true" && e.message == "success") {
          toastr.success("Data sync Successfully");
        } else {
          toastr.error(e.message);
        }
    }
  });

  //update calculation
  update_calculation();
}
function update_calculation() {
  //stitch calculation
  var total_stitch = 0;
  $('.stitch_calculation').each(function(){
    total_stitch += parseInt($(this).text());
  });
  $("#total_stitch").html(total_stitch);

  //salary calculation
  var salary_total= 0;
  $('.salary_calculation input').each(function(){
    salary_total += parseFloat($(this).val());
  });
  $("#total_salary").html(salary_total.toFixed(2));

  //bonus calculation
  var bonus_total= 0;
  $('.bonus_calculation input').each(function(){
    bonus_total += parseFloat($(this).val());
  });
  $("#total_bonus").html(bonus_total.toFixed(2));

  //total salary
  final_salary = salary_total+bonus_total;
  $("#total-box").html(final_salary.toFixed(2));
  tracksalarystatus();
}

function tracksalarystatus() {
  var entrysalary = 0;
  $('.sp_amount').each(function(){
    if($(this).val() != "") {
      entrysalary += parseInt($(this).val());
    }
  });

  var status = "Unknown";
  if(final_salary == entrysalary) {
    status = "Completed";
  } else if(final_salary < entrysalary) {
    status = "Over Paid";
  } else {
    status = "Pending";
  }
  $(".payment_status").html(status);
}

function appendrow() {
  var today = new Date();
  var dd = String(today.getDate()).padStart(2, '0');
  var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = today.getFullYear();
  var html = '';
  html += '<tr>';
    html += '<td><input type="text" id="sp_date" name="sp_date[]" value="'+dd+'-'+mm+'-'+yyyy+'" class="form-control datepicker"></td>';
  html += '<td>';
  html += '<select id="sp_type" name="sp_type[]" class="form-control sp_type">';
  html += '<option value="1">Salary</option>';
  html += '<option value="2">Withdrawal</option>';
  html += '<option value="3">Loss</option>';
  html += '</select>';
  html += '</td>';
    html += '<td>';
  html += '<select style="width: 100%;" id="sp_by" name="sp_by[]" class="form-control sp_by select2">';
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
    html += '<td><input type="text" id="sp_remarks" name="sp_remarks[]" class="form-control"></td>';
    html += '<td><input type="text" id="sp_amount" name="sp_amount[]" class="form-control sp_amount onlyint"></td>';
    html += '<td><button type="button" class="btn btn-danger btn-xs removerowpayment"><i class="fa fa-trash"></i> Delete</button></td>';
  html += '</tr>';
  $("#append_payment_row").append(html);
  initdatepicker(false);
  $('.select2').select2();
}

$(document).on("change",".sp_type",function(e){
  // if($(this).val() == 3) {
  //
  // } else {
  //
  // }
});


$(document).on("click",".removerowpayment",function(e) {
  var ele = $(this).closest('tr');
  if(ele.attr('data-id')) {
    var conf = confirm("Are you sure want to delete this record?");
    if(conf) {
      var route = '{{ route('delete.karigar.payment',":ID") }}';
      route = route.replace(':ID', ele.attr('data-id'));
      $.ajax({
        url:route,
        type:'GET',
        success:function(e) {
          if(e.status == "true" && e.message == "success") {
            toastr.success("Payment Removed Successfully");
            ele.remove();
          } else {
            toastr.error(e.message);
          }
        }
      });
    }
  } else {
    ele.remove();
  }
});

initdatepicker(true);

$(document).ready(function(e) {
update_calculation();
tracksalarystatus();
});
</script>

@endsection
