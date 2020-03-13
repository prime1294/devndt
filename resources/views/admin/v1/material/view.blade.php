@extends('admin.v1.layout.app', ['title' => 'Material'])

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
      <!-- Trasection Table -->
      <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Transaction</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                  title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
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
        </div>
        </div>
      <!-- End of Transection Table -->
    </div>
  </div>
</section>

<div class="buy-now">
<a href="{{ route('material') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<script type="text/javascript">
var datatable;
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

$(document).on("click",".showfilter",function(e){
    $(".filterdiv").stop().slideToggle();
});

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
      "url":"{{ route('material.transection',$info->id) }}",
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

</script>

@endsection
