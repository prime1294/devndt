@extends('admin.v1.layout.app', ['title' => 'Engineer'])

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
  float: right;
  min-width: 70px;
  text-align: center;
}
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-user"></i> {{ ucwords($info->name) }}
    <small>Detail of Engineer</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">{{ ucwords($info->name) }}</li>
  </ol>
</section>

<section class="content">
  <!-- Profile top -->
  <div class="row">
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
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered dtable tbldblclick">
            <thead>
                <tr>
                  <th data-orderable="false">Date</th>
                  <th data-orderable="false">Type</th>
                  <th data-orderable="false">Remarks</th>
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
      <!-- End of Transection Table -->
    </div>
  </div>
</section>

<div class="buy-now">
<a href="{{ route('engineer') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<script type="text/javascript">
var datatable;
var AjaxData = {"_token": "{{ csrf_token() }}"};
$(document).ready(function() {
   datatable = $('.dtable').DataTable({
    "dataSrc": "Data",
    "searching": false,
    "processing" : true,
    "serverSide" : true,
    "order" : [],
    "bAutoWidth": false,
    "ajax" : {
      "url":"{{ route('engineer.transection',$info->id) }}",
      "type":"POST",
      "data": function (d) {
               return  $.extend(d, AjaxData);
            }
    },
    "columns" : [
      {"data":"formated_date","sWidth": "15%"},
      {"data":"formated_type", "sWidth": "20%"},
      {"data":"transection_remarks", "sWidth": "30%"},
      {"data":"formated_amount", "sWidth": "15%"},
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
