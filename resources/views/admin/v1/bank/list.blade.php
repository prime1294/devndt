@extends('admin.v1.layout.app', ['title' => 'Banks'])

@section('content')

<style type="text/css">
.info-box {
  cursor: pointer;
}
.info-box-content {
  padding: 5px 0px;
  min-height: 77px;
}
.info-box-text , .info-box-text1, .info-box-text2 {
  text-overflow: ellipsis;
  overflow: hidden;
  white-space: nowrap;
}
 .info-box-text {
    text-transform: uppercase;
    font-weight: 900;
    letter-spacing: 1px;
    color: #777;
}
.info-box-text2 {
    font-weight: 900;
    letter-spacing: 1px;
    color: #777;
}
.info-box-text3 {
    font-weight: 900;
    letter-spacing: 1px;
    color: #00796b;
    font-size: 20px;
}
.info-box-icon img {
  vertical-align: middle;
  /*position: absolute;*/
  transform: translate(12%, 12%);
}
.info-box.active {
  border-bottom: 2px solid #3c8dbc;
}
.info-box.active .info-box-icon {
  border-bottom: 2px solid #3c8dbc;
}
.filterdiv {
  display: none;
}
/*.info-box .dropdown-menu {*/
/*  top: 25%;*/
/*  min-width:100px;*/
/*  right: auto;*/
/*  left: 300px;*/
/*  position: absolute;*/
/*}*/
.info-box .dropdown-menu {
    top: 25%;
    min-width: 100px;
    left: auto;
    right: unset;
    position: absolute;
    margin-left: 200px;
}
.info-box .dropdown-menu > li > a {
  text-transform:none;
}
.info-box .dropdown-menu > li > a > .glyphicon, .dropdown-menu > li > a > .fa, .dropdown-menu > li > a > .ion {
  margin-right:1px;
}
    .slidercontainer {
        display: flex;
        overflow-x: auto;
    }
    .bankslider {
        width: 400px;
        margin-right: 10px;
        flex: 0 0 auto;
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-user"></i> Banks
    <small><a href="{{ route('bank.new') }}" style="margin-top: -7px;" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Bank</a></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Banks</li>
  </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
  <div class="slidercontainer">
    <?php
    $no = 1;
    foreach($bank_list as $row) {
      ?>
      <div class="bankslider">
        <div class="info-box {{ $no == 1 ? "active" : "" }}" data-id="{{ $row->id }}">
          <span class="info-box-icon bg-white"><img src="{{ asset($row->bankicon) }}" class="img-responsive" alt="{{ $row->bankname }}" width="70" height="70"></span>
          <div class="info-box-content">
            <div class="info-box-text">{{ $row->name }}
               <!--  action -->
               <button type="button" class="btn btn-info btn-xs mr-sm pull-right dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-chevron-down"></i></button>
                    <ul class="dropdown-menu" role="menu">
                    <li><a href="{{ route('bank.edit',$row->id) }}"><i class="fa fa-edit"></i> Edit</a></li>
                    <li><a href="{{ route('bank.delete',$row->id) }}" onclick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a></li>
                  </ul>
               <!-- end action -->
            </div>
            <div class="info-box-text2">{{ $row->account_no }} - {{ $row->type == 2 ? "Current" : "Saving" }}</div>
            <div class="info-box-text3"><i class="fa fa-inr"></i> {{ $row->bankbalance }}</div>
          </div>
        </div>
      </div>
      <?php
      $no++;
    }
    ?>
  </div>
        </div>
    </div>

  <!-- Filter -->
  <div class="row filterdiv mt-sm">
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
                  <option value="bank1">{{ config('transection.bank1')['type'] }}</option>
                  <option value="bank2">{{ config('transection.bank2')['type'] }}</option>
                  <option value="bank3">{{ config('transection.bank3')['type'] }}</option>
                  <option value="bank4">{{ config('transection.bank4')['type'] }}</option>
                  <option value="bank5">{{ config('transection.bank5')['type'] }}</option>
                  <option value="bank6">{{ config('transection.bank6')['type'] }}</option>
                  <option value="bank7">{{ config('transection.bank7')['type'] }}</option>
                  <option value="bank8">{{ config('transection.bank8')['type'] }}</option>
                  <option value="bank9">{{ config('transection.bank9')['type'] }}</option>
                  <option value="bank16">{{ config('transection.bank16')['type'] }}</option>
                  <option value="bank10">{{ config('transection.bank10')['type'] }}</option>
                  <option value="bank11">{{ config('transection.bank11')['type'] }}</option>
                  <option value="bank12">{{ config('transection.bank12')['type'] }}</option>
                  <option value="bank13">{{ config('transection.bank13')['type'] }}</option>
                  <option value="bank14">{{ config('transection.bank14')['type'] }}</option>
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

  <div class="row mt-sm">
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
<a href="{{ route('bank.adjustment') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-sliders" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>



<script type="text/javascript">
var datatable;
var AjaxData = {"_token": "{{ csrf_token() }}", "bank_user_id" : "{{ $bank_list[0]->id }}"};


$(document).on('click', '.info-box', function() {
  $('.info-box.active').removeClass('active');
  $(this).addClass('active');
  AjaxData.bank_user_id = $(this).attr('data-id');
  datatable.ajax.reload();
});


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
      "url":"{{ route('bank.transection.ajax') }}",
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
