@extends('admin.v1.layout.app', ['title' => 'Dashboard'])

@section('content')
<style type="text/css">
  .small-box > .small-box-footer {
    background: rgb(60, 141, 188);
  }
  .small-box .icon {
    font-size:80px;
  }
  @media only screen and (max-width: 768px) {
    .small-box .inner p {
      font-weight: bolder;
      color: black;
      font-size: 18px;
    }
    .small-box .icon {
      display: block;
      font-size:50px;
    }
  }

  .clearbtndiv {
    display: none;
  }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-dashboard"></i> Dashboard
    <small>Version 1.0</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-lg-3 col-xs-12">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ number_format($ctn_company) }}</h3>

          <p>Company</p>
        </div>
        <div class="icon">
          <i class="fa fa-suitcase"></i>
        </div>
        <a href="{{ route('company') }}" class="small-box-footer">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-12">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ number_format($ctn_enrollment) }}</h3>

          <p>Enrollment User</p>
        </div>
        <div class="icon">
          <i class="fa fa-thumbs-up"></i>
        </div>
        <a href="{{ route('enrollment') }}" class="small-box-footer">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-12">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ number_format($ctn_certificate) }}</h3>

          <p>Total Certificate</p>
        </div>
        <div class="icon">
          <i class="fa fa-recycle"></i>
        </div>
        <a href="{{ route('enrollment') }}" class="small-box-footer">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-12">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ number_format($ctn_vision) }}</h3>

          <p>Vision Certificate</p>
        </div>
        <div class="icon">
          <i class="fa fa-file-text"></i>
        </div>
        <a href="{{ route('vision') }}" class="small-box-footer">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
  </div>


  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-body">
          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label for="notify_status">Current Status</label>
                <select id="notify_status" name="notify_status" class="form-control filter_notify_status">
                  <option value="">Select Status</option>
                  <option value="1">Pending</option>
                  <option value="2">Completed</option>
                  <option value="3">Not Required</option>
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
                <button type="button"  class="btn btn-default pull-right daterange" style="width: 100%;">
                  <span><i class="fa fa-calendar"></i> Date range picker</span>
                  <i class="fa fa-caret-down"></i>
                </button>
              </div>
            </div>

            <div class="col-md-2 clearbtndiv">
              <div class="form-group">
                <button type="button" style="margin-top: 24px;"  class="btn btn-info clearfilter">
                  <i class="fa fa-times"></i> Clear Filter
                </button>
              </div>
            </div>

          </div>
          <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered dtable tbldblclick fixtablemobile">
              <thead>
              <tr>
                <th data-orderable="false">Expire Date</th>
                <th data-orderable="false">Certificate</th>
                <th data-orderable="false">Name</th>
                <th data-orderable="false">Contact</th>
                <th data-orderable="false">Status</th>
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
  var dtable;
  var AjaxData = {};
  var dtable_lang = {
    search: "_INPUT_",
    searchPlaceholder: "Search Enrollment"
  };
  $.extend( $.fn.dataTableExt.oStdClasses, {
    "sFilterInput": "form-control",
    "sLengthSelect": "form-control"
  });

  function changeDateRange() {
    AjaxData.startdate = $("#filterStart").val();
    AjaxData.enddate = $("#filterEnd").val();
    dtable.ajax.reload();
    $(".clearbtndiv").show();
  }


  $(document).on("change",".filter_notify_status",function(e){
    AjaxData.notify_status = $(this).val();
    dtable.ajax.reload();
    $(".clearbtndiv").show();
  });

  $(document).on('click', '.clearfilter', function() {
    $(".filter_notify_status").val('').trigger('change') ;
    $('.daterange span').html('<i class="fa fa-calendar"></i> Date range picker');
    AjaxData.notify_status = "";
    AjaxData.startdate = "";
    AjaxData.enddate = "";
    dtable.ajax.reload();
    $(".clearbtndiv").hide();
  });

  $(document).ready(function() {
    dtable = $('.dtable').DataTable({
      "dataSrc": "Data",
      "dom": '<"pull-left"f><"pull-right"l>tip',
      "language": dtable_lang,
      "processing" : true,
      "serverSide" : true,
      "order" : [],
      "ajax" : {
        "url": "{{ route('expire.list.ajax') }}",
        "data": function (d) {
          return  $.extend(d, AjaxData);
        }
      },
      "bLengthChange": false,
      "bAutoWidth": false,
      "searching": false,
      "pageLength": 20,
      "columns" : [
        {"data":"exp_info","sWidth": "10%"},
        {"data":"certificate_info","sWidth": "25%"},
        {"data":"user_info","sWidth": "25%"},
        {"data":"contact_info","sWidth": "25%"},
        {"data":"status_info","sWidth": "15%"},
      ]
    });
  });

  $(document).on("change",".update_notify_status",function(e){
    var certificate_id = $(this).attr('data-cid');
    var current_status = $(this).val();

    $.ajax({
      url:'{{ route('update.expire.status') }}',
      type:'POST',
      data:'_token={{ csrf_token() }}&cid='+certificate_id+'&status='+current_status,
      success: function(e) {
          if(e.status) {
            toastr.success(e.message);
          } else {
            toastr.error(e.message);
          }
      }
    });
  });
</script>
@endsection
