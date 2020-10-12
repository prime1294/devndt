@extends('admin.v1.layout.app', ['title' => 'Vision'])

@section('content')
<style type="text/css">
.select2-container .select2-selection--single .select2-selection__rendered {
  padding-left: 0px !important;
}
  .clearbtndiv {
    display: none;
  }
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-eye"></i> Vision
    <small>Vision List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Vision</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="company_id">Company</label>
              <select id="company_id" name="company_id" class="form-control select2">
                <option value="">Select Company</option>
                @foreach($company_list as $row)
                  <option value="{{ $row->id }}">{{ $row->company_name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-2">
            <div class="form-group">
              <label for="issue_year">Year</label>
              <input type="text" id="issue_year" name="issue_year" class="form-control onlyint" placeholder="Certificate Issue Year">
            </div>
          </div>

          <!-- hidden date range -->
          <input type="hidden" id="filterStart" onchange="changeDateRange()">
          <input type="hidden" id="filterEnd">
          <!-- hidden date range end -->

          <div class="col-md-4">
            <div class="form-group">
              <label>Expire Date</label>
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
              <th data-orderable="false">Information</th>
              <th data-orderable="false">Company</th>
              <th data-orderable="false">Issue Date</th>
              <th data-orderable="false">Expire Date</th>
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
<a href="{{ route('new.vision') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-plus" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>

<script type="text/javascript">
  var selected = [];
  var dtable;
  var AjaxData = {};
$(document).ready(function(e){
  // initdatepicker(true);
});

var dtable_lang = {
  search: "_INPUT_",
  searchPlaceholder: "Search Enrollment"
};
$.extend( $.fn.dataTableExt.oStdClasses, {
  "sFilterInput": "form-control",
  "sLengthSelect": "form-control"
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
      "url": "{{ route('vision.list.ajax') }}",
      "data": function (d) {
        return  $.extend(d, AjaxData);
      }
    },
    "bLengthChange": false,
    "bAutoWidth": false,
    "searching": false,
    "pageLength": 20,
    "columns" : [
      {"data":"user_info","sWidth": "33%"},
      {"data":"company_info","sWidth": "30%"},
      {"data":"issue_date","sWidth": "10%"},
      {"data":"expire_date","sWidth": "10%"},
      {"data":"action","sWidth": "17%", "searchable": false , "orderable": false},
    ],
    "fnDrawCallback": function() {

    },
    "rowCallback": function( row, data ) {
      if ( $.inArray(data.check_id, selected) !== -1 ) {
        $(row).find("#"+data.check_id).attr('checked','checked');
        // console.log($(row).find("#"+data.check_id));
        // console.log(data.check_id);
      }
    }
  });
});

  function changeDateRange() {
    AjaxData.startdate = $("#filterStart").val();
    AjaxData.enddate = $("#filterEnd").val();
    dtable.ajax.reload();
    $(".clearbtndiv").show();
  }

$(document).on("change",".chkbox",function(e){
  var id = this.id;
  var index = $.inArray(id, selected);

  if ( index === -1 ) {
    selected.push( id );
  } else {
    selected.splice( index, 1 );
  }
});


//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('#issue_year');

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
  AjaxData.issue_year = $input.val();
  dtable.ajax.reload();
  if($input.val() == "") {
    $(".clearbtndiv").hide();
  } else {
    $(".clearbtndiv").show();
  }
}


  $(document).on('change', '#company_id', function() {
    AjaxData.company_id = $(this).val();
    dtable.ajax.reload();
    $(".clearbtndiv").show();
  });


  $(document).on('click', '.clearfilter', function() {
    $("#company_id").val('').trigger('change') ;
    $("#issue_year").val('').trigger('change') ;
    $('.daterange span').html('<i class="fa fa-calendar"></i> Date range picker');
    AjaxData.company_id = "";
    AjaxData.issue_year = "";
    AjaxData.startdate = "";
    AjaxData.enddate = "";
    dtable.ajax.reload();
    $(".clearbtndiv").hide();
  });

</script>

@endsection
