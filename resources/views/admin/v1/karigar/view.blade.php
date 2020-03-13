@extends('admin.v1.layout.app', ['title' => 'Karigar'])

@section('content')

<style type="text/css">
.widget-user .widget-user-image {
    position: unset;
    top: 0px;
    left: 0px;
    margin-left: 0px;
}
.widget-user .widget-user-header {
  padding: 20px 20px;
  height: 160px;
}
.widget-user .widget-user-username {
  margin-top: 7px;
}
.amountno {
  background-color: white;
  padding: 8px;
  border-radius: 10px;
  font-size: 20px;
  font-weight: 900;
  width: auto;
  /*float: right;*/
  min-width: 70px;
  text-align: center;
  padding: 5px 36px;
}
.top-image {
  width: 50px;
}
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <img class="img-circle top-image" src="{{ asset($info->photo) }}" alt="User Avatar"> {{ ucwords($info->name) }}
    <span class="amountno">{!! Admin::FormateTransection($total_amount) !!}</span>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">{{ ucwords($info->name) }}</li>
  </ol>
</section>

<section class="content">
  <!-- Profile top -->
  <div class="row" style="display: none">
    <div class="col-md-12">
    <div class="box box-widget widget-user">
      <!-- Add the bg color to the header using any of the bg-* classes -->
      <div class="widget-user-header bg-black" style="background: url('{{ asset('bg.png') }}') center center;">
        <div class="amountno">{!! Admin::FormateTransection($total_amount) !!}</div>
        <div class="widget-user-image">
        <img class="img-circle" src="{{ asset($info->photo) }}" alt="User Avatar">
        </div>
        <h3 class="widget-user-username">{{ ucwords($info->name) }} </h3>
      </div>
    </div>
    </div>
  </div>
  <!-- Profile Top end -->
  <div class="row">
    <div class="col-md-12">
      <!-- Tabs -->
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_2" data-toggle="tab">Salary Report</a></li>
          <li><a href="#tab_1" data-toggle="tab">Transaction</a></li>
{{--          <li><a href="#tab_3" data-toggle="tab">Withdrawal and Less Amount</a></li>--}}
        </ul>
        <div class="tab-content">
          <div class="tab-pane" id="tab_1">
            <div class="row">
              <div class="col-md-12">
                <button type="button" style="margin-bottom: 10px;" class="btn btn-info btn-xs showfilter">
                  <i class="glyphicon glyphicon-filter"></i> Filter
                </button>
              </div>
            </div>
            <div class="row filterdiv">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="filter_by">Filter By</label>
                  <select style="width:100%;" id="filter_by" name="filter_by" class="form-control select2">
                    <option value="">All Transaction</option>
                    <option value="RECIVABLE">{{ config('transection.RECIVABLE')['type'] }}</option>
                    <option value="PAYABLE">{{ config('transection.PAYABLE')['type'] }}</option>
                    <option value="PAYIN">{{ config('transection.PAYIN')['type'] }}</option>
                    <option value="PAYOUT">{{ config('transection.PAYOUT')['type'] }}</option>
                    <option value="EXPENSES">{{ config('transection.EXPENSES')['type'] }}</option>
                  </select>
                </div>
              </div>

              <!-- hidden date range -->
              <input type="hidden" id="filterStart" onchange="changeDateRange()">
              <input type="hidden" id="filterEnd">
              <!-- hidden date range end -->

              <div class="col-md-4">
                <div class="form-group">
                  <label>Date range button:</label>
                  <button style="width: 100%;" type="button" class="btn btn-default pull-right no-margin mb20-mobile daterange">
                    <span><i class="fa fa-calendar"></i> Date range picker</span>
                    <i class="fa fa-caret-down"></i>
                  </button>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="bill_no">Number</label>
                  <input type="text" id="bill_no" name="bill_no" class="form-control">
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <button type="button" style="margin-top: 24px;" class="btn btn-danger clearfilter">
                    <i class="fa fa-times"></i> Clear Filter
                  </button>
                </div>
              </div>


            </div>
            <!-- transection -->
            <div class="table-responsive">
              <table class="table table-hover table-striped table-bordered dtable tbldblclick fixtablemobile">
                <thead>
                    <tr>
                      <th data-orderable="false">Date</th>
                      <th data-orderable="false">Number</th>
                      <th data-orderable="false">Type</th>
                      <th data-orderable="false">Amount</th>
                      <th data-orderable="false">Rec/Paid</th>
                      <th data-orderable="false">Balance</th>
                      <th data-orderable="false">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- end transection -->
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane active" id="tab_2">
            <div class="row">
              <div class="col-md-12">
                <a href="{{ route('add.daily.production')  }}" class="btn btn-primary btn-sm mb-10"><i class="fa fa-plus"></i> Add New Report</a>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-hover table-striped table-bordered fixtablemobile">
                    <thead>
                      <tr>
                        <th>Month</th>
                        <th>Stitch</th>
                        <th>Day</th>
                        <th>Salary</th>
                        <th>Bonus</th>
                        <th>Total Salary</th>
                        <th>Payment</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $npp = 0; ?>
                      @foreach($monthly_report as $row)
                          <?php if($row['days_count'] == 0) { continue; } ?>
                          <?php ++$npp; ?>
                          <?php
                          if($row['payment_status'] == "Completed") {
                            $btn_class = "btn-success";
                          } elseif($row['payment_status'] == "Over Paid")  {
                            $btn_class = "btn-danger";
                          } else {
                            $btn_class = "btn-info";
                          }
                          ?>
                      <tr>
                        <td>{{ $row['month_name'] }}</td>
                        <td>{{ $row['show_stitch'] }}</td>
                        <td>{{ $row['days_count'] }}</td>
                        <td><i class="fa fa-inr"></i> {{ $row['total_salary'] }}</td>
                        <td><i class="fa fa-inr"></i> {{ $row['total_bonus'] }}</td>
                        <td><i class="fa fa-inr"></i> {{ $row['calculated_salary'] }}</td>
                        <td><i class="fa fa-inr"></i> {{ $row['total_payment'] }}</td>
                        <td>
                          <button type="button" class="btn <?= $btn_class ?> btn-xs">
                            {{ $row['payment_status'] }} <span class="badge badge-light">{{ $row['payment_count'] }}</span>
                          </button>
                        </td>
                        <td><a href="{{ route('manage.karigar.report',["id"=>$info->id,"month"=>$row['month'],"year"=>$row['year']]) }}" class="btn btn-primary btn-xs"><i class="fa fa-check-circle"></i> Manage Salary</a></td>
                      </tr>
                      @endforeach
                    @if($npp  == 0)
                      <tr>
                        <td class="text-center" colspan="9">No Salary report found</td>
                      </tr>
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab_3">
            <div class="row">
              <div class="col-md-12">
                <button type="button" class="btn btn-primary btn-sm mb-10" onclick="addeventtrigger()" data-toggle="modal" data-target="#addWidLessAmount"><i class="fa fa-plus"></i> Add Transection</button>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-hover table-striped table-bordered dtable2 tbldblclick">
                    <thead>
                        <tr>
                          <th data-orderable="false">Date</th>
                          <th data-orderable="false">Type</th>
                          <th data-orderable="false">Amount</th>
                          <th data-orderable="false">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- End Tabs -->
    </div>
  </div>
</section>


<!-- Modal -->
<div id="addWidLessAmount" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Transaction</h4>
      </div>
      <div class="modal-body">
        <form method="post" id="addwidlessamount" action-type="add">
          {!! csrf_field() !!}
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                    <label for="wl_date">Date</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="text" id="wl_date" name="wl_date" class="form-control datepicker">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                    <label for="wl_type">Type</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <select style="width:100%;" id="wl_type" name="wl_type" class="form-control select2">
                        <option value="1">Withdrawal Amount</option>
                        <option value="2">Less Amount</option>
                    </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                    <label for="wl_amount">Amount</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="text" id="wl_amount" name="wl_amount" class="form-control onlyint">
                    </div>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                    <label for="wl_remarks">Remarks</label>
                    <textarea id="wl_remarks" name="wl_remarks" class="form-control" rows="2"></textarea>
                </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary submitwidlessform"><i class="fa fa-upload"></i> Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>



<div class="buy-now">
<a href="{{ route('karigar') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<script type="text/javascript">
var datatable;
var datatable2;
var editid = 0;
var AjaxData = {"_token": "{{ csrf_token() }}"};


//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('#bill_no');

//on keyup, start the countdown
$input.on('keyup', function () {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval);
});

//on keydown, clear the countdown
$input.on('keydown', function () {
  clearTimeout(typingTimer);
});

//user is "finished typing," do something
function doneTyping () {
  //do something
  AjaxData.bill_no = $input.val();
  datatable.ajax.reload();
}

$(document).on('change', '#filter_by', function() {
  AjaxData.filter_by = $(this).val();
  datatable.ajax.reload();
});

$(document).on("click",".showfilter",function(e){
  $(".filterdiv").stop().slideToggle();
});

$(document).on('click', '.clearfilter', function() {
  $("#filter_by").val('').trigger('change') ;
  $("#bill_no").val('');
  $('.daterange span').html('<i class="fa fa-calendar"></i> Date range picker');
  AjaxData.filter_by = "";
  AjaxData.bill_no = "";
  AjaxData.startdate = "";
  AjaxData.enddate = "";
  datatable.ajax.reload();
  $(".filterdiv").slideUp();
});

function changeDateRange() {
  AjaxData.startdate = $("#filterStart").val();
  AjaxData.enddate = $("#filterEnd").val();
  datatable.ajax.reload();
}


$(document).ready(function() {
   datatable = $('.dtable').DataTable({
    "dataSrc": "Data",
    "searching": false,
    "processing" : true,
    "serverSide" : true,
    "order" : [],
    "bAutoWidth": false,
     "bLengthChange": false,
    "ajax" : {
      "url":"{{ route('karigar.transection',$info->id) }}",
      "type":"POST",
      "data": function (d) {
               return  $.extend(d, AjaxData);
            }
    },
    "columns" : [
      {"data":"formated_date","sWidth": "10%"}, //d
      {"data":"formated_number","sWidth": "10%"}, //d
      {"data":"formated_type", "sWidth": "23%"},
      {"data":"formated_amount", "sWidth": "10%"}, //d
      {"data":"transection_recive", "sWidth": "10%"}, //d
      {"data":"transection_paid", "sWidth": "10%"}, //d
      {"data":"action", "sWidth": "17%", "searchable": false , "orderable": false}, //d
    ],
    "fnDrawCallback": function() {
            $('.status_checkbox').bootstrapToggle();
            // $('.make-switch').bootstrapSwitch();
        },
  });


  datatable2 = $('.dtable2').DataTable({
   "dataSrc": "Data",
   "searching": false,
   "processing" : true,
   "serverSide" : true,
   "order" : [],
   "bAutoWidth": false,
   "ajax" : "{{ route('widless.amount.ajax',$info->id) }}",
   "columns" : [
     {"data":"formated_date","sWidth": "25%"},
     {"data":"formated_type", "sWidth": "25%"},
     {"data":"formated_amount", "sWidth": "25%"},
     {"data":"action", "sWidth": "25%"},
     // {"data":"contactinfo"},
     // {"data":"action", "searchable": false , "orderable": false},
   ]
 });
});

$('.dtable tbody').on('dblclick', 'tr', function () {
var data = datatable.row( this ).data();
var action = data.action;
var filter = $(action).filter('a').html();
if(filter){
  if(filter.indexOf('<i class="fa fa-edit"></i>') != -1) {
    window.location.href = $(action).filter('a').attr('href');
  }
}
});

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

function assignval(e) {
  $("#wl_date" ).datepicker( "setDate", new Date(e.date));
  // $("#wl_type").val(e.type);
  $('#wl_type').val(e.type).trigger('change');
  $("#wl_amount").val(e.amount);
  $("#wl_remarks").val(e.remarks);
}
function addeventtrigger() {
$("#addwidlessamount").attr('action-type','add');
$("#wl_date" ).datepicker( "setDate", new Date());
$('#wl_type').val(1).trigger('change');
$("#wl_amount").val('');
$("#wl_remarks").val('');
}
function editthisrecord(id) {
  editid = id;
  $("#addwidlessamount").attr('action-type','edit');
  var route = '{{ route('widless.amount.info',":ID") }}';
  route = route.replace(':ID', id);
  $.ajax({
      url:route,
      success:function(e){
        if(e.status == "true" && e.message == "success") {
          assignval(e.result);
        } else {
          toastr.error(e.message);
        }
      }
  });
}

function removethisrecord(id) {
  var conf = confirm("Are you sure want to delete this record?");
  if(conf) {
    var url = '{{ route('widless.amount.remove',":ID") }}';
    url = url.replace(':ID', id);
    $.ajax({
      url:url,
      success:function(e) {
        if(e.status == "true" && e.message == "success") {
          datatable2.draw();
        } else {
          toastr.error(e.message);
        }
      }
    });
  }
}


$(document).on("click",".submitwidlessform",function(e) {
  if($("#wl_date").val() == "") {
    toastr.error("Please select date");
    $("#wl_date").focus();
    return false;
  }
  if($("#wl_type").val() == "") {
    toastr.error("Please select Type");
    $("#wl_type").focus();
    return false;
  }
  if($("#wl_amount").val() == "") {
    toastr.error("Please Enter Amount");
    $("#wl_amount").focus();
    return false;
  }
  $("#addWidLessAmount").modal('hide');
  if($("#addwidlessamount").attr('action-type') == "edit") {
    var url = '{{ route('update.widless.amount',":ID") }}';
    url = url.replace(':ID', editid);
  } else {
    var url = '{{ route('register.widless.amount',$info->id) }}';
  }
  $.ajax({
    url:url,
    type:'POST',
    data:$("#addwidlessamount").serialize(),
    success:function(e) {
        if(e.status == "true" && e.message == "success") {
          datatable2.draw();
        } else {
          toastr.error(e.message);
        }
    }
  });
});

</script>

@endsection
