@extends('admin.v1.layout.app', ['title' => 'Cash'])

@section('content')

<style type="text/css">
.filterdiv {
  display: none;
}
.info-box-number {
  font-size: 37px;
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
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-user"></i> Cash
    <small>Manage Cash</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Cash</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-lg-3 col-xs-12">
          <!-- small box -->
          <div class="small-box {!! Admin::is_positive_integer($cashinhand->total_amount) ? "bg-green" : "bg-red" !!}">
            <div class="inner">
              <h3>{!! Admin::NumberFormate($cashinhand->total_amount) !!}</h3>
              <p>Cash In Hand</p>
            </div>
            <div class="icon">
              <i class="fa fa-inr"></i>
            </div>
          </div>
        </div>
  </div>
  <!-- Filter -->
  <div class="row filterdiv">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Filter &nbsp;
            <button type="button" class="btn btn-danger btn-xs clearfilter"><i class="fa fa-times"></i> Clear Filter</button>
          </h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="filter_by">Filter By</label>
                <select style="width:100%;" id="filter_by" name="filter_by" class="form-control select2">
                  <option value="">All Transaction</option>
                  <option value="cash1">{{ config('transection.cash1')['type'] }}</option>
                  <option value="cash2">{{ config('transection.cash2')['type'] }}</option>
                  <option value="cash3">{{ config('transection.cash3')['type'] }}</option>
                  <option value="cash4">{{ config('transection.cash4')['type'] }}</option>
                  <option value="cash5">{{ config('transection.cash5')['type'] }}</option>
                  <option value="cash6">{{ config('transection.cash6')['type'] }}</option>
                  <option value="cash7">{{ config('transection.cash7')['type'] }}</option>
                  <option value="cash14">{{ config('transection.cash14')['type'] }}</option>
                  <option value="cash8">{{ config('transection.cash8')['type'] }}</option>
                  <option value="cash9">{{ config('transection.cash9')['type'] }}</option>
                  <option value="cash10">{{ config('transection.cash10')['type'] }}</option>
                  <option value="cash11">{{ config('transection.cash11')['type'] }}</option>
                  <option value="cash12">{{ config('transection.cash12')['type'] }}</option>
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
                <div class="input-group">
                  <button type="button" class="btn btn-default pull-right daterange">
                    <span><i class="fa fa-calendar"></i> Date range picker</span>
                    <i class="fa fa-caret-down"></i>
                  </button>
                </div>
              </div>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Filter -->

  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Transaction
          <button type="button" class="btn btn-info btn-xs filterbtn"><i class="fa fa-filter"></i> Filter Data</button>
        </h3>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered dtable fixtablemobile">
            <thead>
                <tr>
                  <th data-orderable="false">Date</th>
                  <th data-orderable="false">Number</th>
                  <th data-orderable="false">Type</th>
                  <th data-orderable="false">Name</th>
                  <th data-orderable="false">Amount</th>
                  <th data-orderable="false">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    </div>
  </div>
</section>

<div class="buy-now">
<a href="{{ route('cash.adjustment') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-sliders" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>



<script type="text/javascript">
var datatable;
var AjaxData = {"_token": "{{ csrf_token() }}"};

$(document).on('click', '.filterbtn', function() {
  $('.filterdiv').slideToggle();
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
      "url":"{{ route('cash.transection.ajax') }}",
      "type":"POST",
      "data": function (d) {
               return  $.extend(d, AjaxData);
            }
    },
    "columns" : [
      {"data":"formated_date","sWidth": "15%"},
      {"data":"formated_number","sWidth": "10%"},
      {"data":"formated_type", "sWidth": "20%"},
      {"data":"formated_remarks", "sWidth": "25%"},
      {"data":"formated_amount", "sWidth": "10%"},
      {"data":"action", "sWidth": "20%"},
      // {"data":"contactinfo"},
      // {"data":"action", "searchable": false , "orderable": false},
    ],
    "fnDrawCallback": function() {
            $('.status_checkbox').bootstrapToggle();
            // $('.make-switch').bootstrapSwitch();
        },
  });
});


$(document).on('change', '#filter_by', function() {
  AjaxData.filter_by = $(this).val();
  datatable.ajax.reload();
});


$(document).on('click', '.clearfilter', function() {
  $("#filter_by").val('').trigger('change') ;
  $('.daterange span').html('<i class="fa fa-calendar"></i> Date range picker');
  AjaxData.filter_by = "";
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

</script>

@endsection
