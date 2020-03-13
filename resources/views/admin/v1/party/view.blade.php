@extends('admin.v1.layout.app', ['title' => 'Party'])

@section('content')

{!!Html::style('/public/admin/theme1/plugins/iCheck/square/blue.css')!!}

<style type="text/css">
.icheckbox_square-blue {
  /* margin-left: -19px; */
  margin-right: 3px;
}
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

.info-box {
  cursor: pointer;
  border: 1px solid #ECF0F5;
  min-height: 92px;
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
    font-size: 19px;
}
.info-box-text2 {
    font-weight: 700;
    letter-spacing: 1px;
    color: #777;
    font-size: 13px;
}
.info-box-text3 {
    font-weight: 900;
    letter-spacing: 1px;
    color: #00796b;
    font-size: 20px;
}
.info-box-icon img {
  vertical-align: middle;
  position: absolute;
  transform: translate(12%, 12%);
}
.info-box.active {
  border-bottom: 2px solid #3c8dbc;
}
/* .info-box.active .info-box-icon {
  border-bottom: 2px solid #3c8dbc;
} */
.info-box .dropdown-menu {
  top: 25%;
  min-width:100px;
  right:25px;
  left:auto;
}
.info-box .dropdown-menu > li > a {
  text-transform:none;
}
.info-box .dropdown-menu > li > a > .glyphicon, .dropdown-menu > li > a > .fa, .dropdown-menu > li > a > .ion {
  margin-right:1px;
}
.mt7 {
  margin-top: 7px;
}
/* Note: Try to remove the following lines to see the effect of CSS positioning */
.affix {
  top: 0;
  width: 77.75%;
  z-index: 9999 !important;
  margin-top: 49px;
}

.affix + .affix_cont {
  padding-top: 200px;
}
.affix_cont {
  /* background-color: red;
  min-height: 100px; */
  background-color: #ECF0F5;
  padding: 10px 10px;
}

.color1 {
  color: #00a65a;
}
.color2 {
  color: #f56954;
}
.color3 {
  color: #00c0ef;
}
.color4 {
  color: #f39c12;
}
.color-code {
  margin-right: 16px;
  margin-top: 7px;
  float: right;
}
.color-code i {

}
#custom-search-input {
  margin:0;
  /* margin-top: 10px; */
  padding: 0;
  width: 40%;
  display: inline-block;
  margin-bottom: -7px;
}

#custom-search-input .form-control {
  padding: 0px 0px;
  border: none;
}

#custom-search-input .search-query {
  padding-right: 3px;
  padding-right: 4px \9;
  padding-left: 3px;
  padding-left: 4px \9;
  /* IE7-8 doesn't have border-radius, so don't indent the padding */

  margin-bottom: 0;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
}

#custom-search-input button {
  border: 0;
  background: none;
  /** belows styles are working good */
  padding: 2px 5px;
  margin-top: 2px;
  position: relative;
  left: -28px;
  /* IE7-8 doesn't have border-radius, so don't indent the padding */
  margin-bottom: 0;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  color:#D9230F;
}

.search-query:focus + button {
  z-index: 99 !important;
}
.input-group-btn:last-child > .btn, .input-group-btn:last-child > .btn-group {
  z-index: 5;
}
.row-spliter {
    height: 1px;
    background:
    #e1e1e1;
    width: 100%;
    margin-top: 5px;
    margin-bottom: 5px;
}
@media only screen and (max-width: 768px) {
.affix {
  margin-top: 100px;
  right: 0;
  width: 100%;
}
.color-code {
  float: left;
  width: 50% !important;
  display: table;
  margin-right: 0px;
}
#custom-search-input {
  margin-bottom: 10px;
  width: 100%;
  margin-top: 8px;
  /* display: block;
  margin-left: 5px; */
}
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
  <div class="row" style="display: none;">
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
      <!-- tabs -->
      <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_2" data-toggle="tab">Stock</a></li>
              <li><a href="#tab_1" data-toggle="tab">Transaction</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane" id="tab_1">
                  <div class="row">
                      <div class="col-md-12">
                          <button type="button" class="btn btn-info mb-10 btn-xs showfilter">
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


                <!-- transection -->
                <div class="table-responsive">
                  <table class="table table-hover table-striped table-bordered dtable fixtablemobile tbldblclick">
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
                  <a href="{{ route('stock.new',$info->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Stock</a>
                      <button type="button" class="btn btn-info btn-sm no-margin showfilter">
                          <i class="glyphicon glyphicon-filter"></i> Filter
                      </button>
                  </div>
                </div>

                  <div class="row mt-sm filterdiv">
                      <div class="col-md-4">
                          <div class="form-group">
                              <label for="challan_no">Challan No</label>
                              <input type="text" id="challan_no" name="challan_no" class="form-control" placeholder="Challan No">
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-group">
                              <label for="agent_filter">Name</label>
                              <select style="width:100%;" id="agent_filter" name="agent_filter" class="form-control select2-bank" data-placeholder="Select Name">
                                  <option value="">Select Name</option>
                                  @foreach($agent_list as $row)
                                      <option data-img="{{ $row->photo }}" value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                                  @endforeach
                              </select>
                          </div>
                      </div>

                      <div class="col-md-4">
                          <div class="form-group">
                              <label for="stock_no">Stock No</label>
                              <select style="width: 100%;" id="stock_no" name="stock_no" class="form-control select2">
                                  <option value="">Select Stock</option>
                                  @foreach($stock_list_item as $row)
                                      <option value="{{ $row->stock_unique_id }}" data-pending="{{ $row->pending }}" data-unit="{{ $row->unit }}">{{ $row->stock_name }} </option>
                                  @endforeach
                              </select>
                          </div>
                      </div>

                      <div class="col-md-4">
                          <div class="form-group">
                              <label for="design_name">Design</label>
                              <input type="text" id="design_name" name="design_name" class="form-control" placeholder="Design Name">
                          </div>
                      </div>

                      <!-- hidden date range -->
                      <input type="hidden" id="filterStartNew" onchange="changeDateRangeNew()">
                      <input type="hidden" id="filterEndNew">
                      <!-- hidden date range end -->

                      <div class="col-md-4">
                          <div class="form-group">
                              <label>Date range button:</label>
                              <button type="button"  class="btn btn-default pull-right daterange" style="width: 100%;">
                                  <span><i class="fa fa-calendar"></i> Date range picker</span>
                                  <i class="fa fa-caret-down"></i>
                              </button>
                          </div>
                      </div>

                      <div class="col-md-2">
                          <div class="form-group">
                              <button type="button" style="margin-top: 24px;"  class="btn btn-danger clearfilternew">
                                  <i class="fa fa-times"></i> Clear Filter
                              </button>
                          </div>
                      </div>
                  </div>

                <div class="row mt-sm">
                  <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered dtable2 fixtablemobile2 tbldblclick">
                      <thead>
                          <tr>
                            <th data-orderable="false">Challan</th>
                            <th data-orderable="false">Ch. No</th>
                            <th data-orderable="false">
                                <div class="row">
                                    <div class="col-md-2 col-xs-2">
                                    Febric
                                    </div>
                                    <div class="col-md-2 col-xs-2">
                                    Category
                                    </div>
                                    <div class="col-md-2 col-xs-2">
                                    Stock No
                                    </div>
                                    <div class="col-md-4 col-xs-4">
                                    Qty
                                    </div>
                                    <div class="col-md-2 col-xs-2">

                                    </div>
                                </div>
                            </th>
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
          <!-- nav-tabs-custom -->
      <!-- end tabs -->
    </div>
  </div>
</section>


<!-- Modal -->
<div id="stockReport" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Stock Report</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered dtable3 fixtablemobile">
                        <thead>
                        <tr>
                            <td data-orderable="false">Date</td>
                            <td data-orderable="false">Number</td>
                            <td data-orderable="false">Type</td>
                            <td data-orderable="false">Name</td>
                            <td data-orderable="false">Qty</td>
                            <td data-orderable="false">Design</td>
                            <td data-orderable="false">Receive</td>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<div class="buy-now">
<a href="{{ route('party') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


{!!Html::script('/public/admin/theme1/plugins/iCheck/icheck.min.js')!!}

<script type="text/javascript">
var datatable;
var datatable2;
var datatable3;

var AjaxData = {
    "_token": "{{ csrf_token() }}"
};

var AjaxDataNew = {
    "_token": "{{ csrf_token() }}"
};


var AjaxDataStockReport = {
    "_token": "{{ csrf_token() }}",
    "id" : 0
};


//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('#bill_no');
var $inputnew = $('#challan_no');

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

//on keyup, start the countdown
$inputnew.on('keyup', function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTypingnew, doneTypingInterval);
});

//on keydown, clear the countdown
$inputnew.on('keydown', function () {
    clearTimeout(typingTimer);
});

//user is "finished typing," do something
function doneTypingnew () {
    //do something
    AjaxDataNew.bill_no = $inputnew.val();
    datatable2.ajax.reload();
}

$(document).on("click",".showfilter",function(e){
    $(".filterdiv").stop().slideToggle();
});

$(document).on('change', '#filter_by', function() {
    AjaxData.filter_by = $(this).val();
    datatable.ajax.reload();
});

$(document).on('change', '#agent_filter', function() {
    AjaxDataNew.filter_by = $(this).val();
    datatable2.ajax.reload();
});

$(document).on('change', '#stock_no', function() {
    AjaxDataNew.stock_no = $(this).val();
    datatable2.ajax.reload();
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

function changeDateRangeNew() {
    AjaxDataNew.startdate = $("#filterStartNew").val();
    AjaxDataNew.enddate = $("#filterEndNew").val();
    datatable2.ajax.reload();
}

$(document).on('click', '.clearfilternew', function() {
    $("#agent_filter").val('').trigger('change') ;
    $("#stock_no").val('').trigger('change') ;
    $("#challan_no").val('');
    $('.daterange span').html('<i class="fa fa-calendar"></i> Date range picker');
    AjaxDataNew.filter_by = "";
    AjaxDataNew.stock_no = "";
    AjaxDataNew.bill_no = "";
    AjaxDataNew.startdate = "";
    AjaxDataNew.enddate = "";
    datatable2.ajax.reload();
    $(".filterdiv").slideUp();
});

$(document).ready(function() {
    datatable = $('.dtable').DataTable({
    "dataSrc": "Data",
    "searching": false,
    "processing" : true,
    "serverSide" : true,
    "bLengthChange": false,
    "order" : [],
    "bAutoWidth": false,
    "ajax" : {
      "url":"{{ route('party.transection',$info->id) }}",
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
      // {"data":"contactinfo"},
      // {"data":"action", "searchable": false , "orderable": false},
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
      "bLengthChange": false,
   "ajax" : {
     "url":"{{ route('stock.list.ajax',$info->id) }}",
     "type":"POST",
     "data": function (d) {
              return  $.extend(d, AjaxDataNew);
           }
   },
   "columns" : [
     {"data":"stock_info","sWidth": "20%"},
     {"data":"challan_number","sWidth": "10%"},
     {"data":"stock_quantity_html", "sWidth": "50%"},
     {"data":"action", "sWidth": "20%"},
   ],
      "fnDrawCallback": function() {
          $('[data-toggle="tooltip"]').tooltip();
      },
 });

    datatable3 = $('.dtable3').DataTable({
        "dataSrc": "Data",
        "searching": false,
        "processing" : true,
        "serverSide" : true,
        "order" : [],
        "bAutoWidth": false,
        "bLengthChange": false,
        "ajax" : {
            "url":"{{ route('stock.report.ajax') }}",
            "type":"POST",
            "data": function (d) {
                return  $.extend(d, AjaxDataStockReport);
            }
        },
        "columns" : [
            {"data":"report_date","sWidth": "10%"},
            {"data":"report_number","sWidth": "11%"},
            {"data":"report_type", "sWidth": "15%"},
            {"data":"report_user", "sWidth": "25%"},
            {"data":"report_quantity", "sWidth": "12%"},
            {"data":"report_design", "sWidth": "15%"},
            {"data":"report_recive", "sWidth": "12%"},
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

$(document).on("click",".dbclickopen",function(e) {
  window.location.href = $(this).attr('data-url');
});

function getReportInfo(id) {
    AjaxDataStockReport.id = id;
    datatable3.ajax.reload();
    $("#stockReport").modal('show');
}

$(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      // radioClass: 'iradio_square-blue',
      // increaseArea: '20%' /* optional */
    });
  });

</script>

@endsection
