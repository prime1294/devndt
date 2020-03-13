@extends('admin.v1.layout.app', ['title' => 'Cheque'])

@section('content')

<style type="text/css">
.filterdiv {
  display: none;
}
.info-box-number {
  font-size: 37px;
}
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-user"></i> Cheque
    <small>Manage Cheque</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Cheque</li>
  </ol>
</section>

<section class="content">
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
                  <option value="cheque1">{{ config('transection.cheque1')['type'] }}</option>
                  <option value="cheque2">{{ config('transection.cheque2')['type'] }}</option>
                  <option value="cheque5">{{ config('transection.cheque5')['type'] }}</option>
                  <option value="cheque6">{{ config('transection.cheque6')['type'] }}</option>
                  <option value="cheque7">{{ config('transection.cheque7')['type'] }}</option>
                  <option value="cheque8">{{ config('transection.cheque8')['type'] }}</option>
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
                  <th data-orderable="false">Status</th>
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
      "url":"{{ route('cheque.transection.ajax') }}",
      "type":"POST",
      "data": function (d) {
               return  $.extend(d, AjaxData);
            }
    },
    "columns" : [
      {"data":"formated_date","sWidth": "15%"},
      {"data":"ref_no", "sWidth": "8%"},
      {"data":"formated_type", "sWidth": "15%"},
      {"data":"formated_remarks", "sWidth": "25%"},
      {"data":"formated_amount", "sWidth": "10%"},
      {"data":"formated_cheque_status", "sWidth": "7%"},
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


function chequeopenconfirm() {
  var conf = confirm('Are you sure want to reopen cheque?');
  if(conf) {
    return true;
  }
  return false;
}

function changeDateRange() {
  AjaxData.startdate = $("#filterStart").val();
  AjaxData.enddate = $("#filterEnd").val();
  datatable.ajax.reload();
}

</script>

@endsection
