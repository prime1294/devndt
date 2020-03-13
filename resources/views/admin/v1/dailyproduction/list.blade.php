@extends('admin.v1.layout.app', ['title' => 'Daily Production'])

@section('content')
<style type="text/css">
  .filterrow {
    display: none;
  }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-calendar"></i> Daily Production
    <small>Manage Daily Production</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Daily Production</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">List of Daily Production <button type="button" class="btn btn-primary btn-xs showfilter"><i class="glyphicon glyphicon-filter"></i> Filter</button> </h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                  title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="row filterrow">
          <div class="col-md-2">
            <div class="form-group">
              <label for="filter_by">Machine Name</label>
              <select style="width:100%;" id="machine" name="machine" class="form-control select2-bank" data-placeholder="Select Machine">
                <option value="">Select Machine</option>
                @foreach($machine_list as $row)
                  <option data-img="{{ $row->photo }}" value="{{ $row->id }}">{{ $row->machine_no }} - {{ ucwords($row->company_name) }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-2">
            <div class="form-group">
              <label for="filter_by">Karigar Name</label>
              <select style="width:100%;" id="karigar" name="karigar" class="form-control select2-bank" data-placeholder="Select Karigar">
                <option value="">Select Karigar</option>
                @foreach($karigar_list as $row)
                  <option data-img="{{ $row->photo }}" value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-2">
            <div class="form-group">
              <label for="filter_by">Programme Name</label>
              <select style="width: 100%;" id="programmecard" name="programmecard" class="form-control select2" data-placeholder="Select Pr.cd">
                <option value="">Select Pr.cd</option>
                @foreach($pc_list as $row)
                  <option value="{{ $row->id }}">{{ $row->pc_name }}</option>
                @endforeach
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
              <button style="width: 100%;" type="button" class="btn btn-default pull-right daterange no-margin mb24-mobile">
                <span><i class="fa fa-calendar"></i> Date range picker</span>
                <i class="fa fa-caret-down"></i>
              </button>
            </div>
          </div>



          <div class="col-md-2">
            <div class="form-group">
              <button type="button" style="margin-top: 24px;" class="btn btn-danger btn-block clearfilter no-margin mb24-mobile">
                <i class="fa fa-times"></i> Clear Filter
              </button>
            </div>
          </div>


        </div>
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered dtable fixtablemobile">
            <thead>
                <tr>
                  <th data-orderable="false">Date</th>
                  <th data-orderable="false">Machine</th>
                  <th data-orderable="false">Job Work</th>
                  <th data-orderable="false">Karigar</th>
                  <th data-orderable="false">Ship</th>
                  <th data-orderable="false">Stitch</th>
                  <th data-orderable="false">Frame</th>
                  <th data-orderable="false">T.B.No</th>
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
<a href="{{ route('add.daily.production') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-plus" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<div id="editReportModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Report</h4>
      </div>
      <form method="post" action="{{ route('update.karigar.report') }}">
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
                <label for="machine_id">Machine</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <select style="width:100%;" id="machine_id" name="machine" class="form-control select2-bank" data-placeholder="Select Machine">
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

var datatable;
var AjaxData = {
  "_token": "{{ csrf_token() }}"
};

function changeDateRange() {
  AjaxData.startdate = $("#filterStart").val();
  AjaxData.enddate = $("#filterEnd").val();
  datatable.ajax.reload();
}

$(document).on("change","#machine",function(e){
if($(this).val() != "") {
  AjaxData.machine = $(this).val();
  datatable.ajax.reload();
}
});

$(document).on("change","#karigar",function(e){
  if($(this).val() != "") {
    AjaxData.karigar = $(this).val();
    datatable.ajax.reload();
  }
});

$(document).on("change","#programmecard",function(e){
  if($(this).val() != "") {
    AjaxData.programmecard = $(this).val();
    datatable.ajax.reload();
  }
});

$(document).on('click', '.clearfilter', function() {
  $("#machine").val('').trigger('change') ;
  $("#karigar").val('').trigger('change') ;
  $("#programmecard").val('').trigger('change') ;
  $('.daterange span').html('<i class="fa fa-calendar"></i> Date range picker');
  AjaxData.machine = "";
  AjaxData.karigar = "";
  AjaxData.programmecard = "";
  AjaxData.startdate = "";
  AjaxData.enddate = "";
  datatable.ajax.reload();
  $(".filterrow").slideUp();
});

$(document).ready(function() {
  datatable =  $('.dtable').DataTable({
    "dataSrc": "Data",
    "searching": false,
    "processing" : true,
    "serverSide" : true,
    "order" : [],
    "ajax" : {
      "url":"{{ route('daily.production.ajax') }}",
      "type":"POST",
      "data": function (d) {
        return  $.extend(d, AjaxData);
      }
    },
    "bLengthChange": false,
    "bAutoWidth": false,
    "columns" : [
      {"data":"formated_date","sWidth": "8%"},
      {"data":"machine_info","sWidth": "20%"},
      {"data":"jobwork","sWidth": "8%"},
      {"data":"karigar_info","sWidth": "13%"},
      {"data":"karigar_ship","sWidth": "8%"},
      {"data":"stitch","sWidth": "8%"},
      {"data":"frame","sWidth": "5%"},
      {"data":"tbno","sWidth": "5%"},
      {"data":"action", "searchable": false , "orderable": false,"sWidth": "25%"},
    ]
  });
});


$(document).on("click",".showfilter",function(e){
$(".filterrow").stop().slideToggle();
});

function getDetailofReport(id,karigar_id) {
  var route = '{{ route('info.karigar.report',":ID") }}';
  route = route.replace(':ID', karigar_id);
  $.ajax({
    url:route,
    data:'id='+id,
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        $("#report_id").val(e.result.id);
        $("#adjustment_date").datepicker( "setDate", new Date(e.result.date_new));
        $("#machine_id").val(e.result.machine).trigger('change');
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
</script>

@endsection
