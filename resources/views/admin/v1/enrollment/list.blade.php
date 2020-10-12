@extends('admin.v1.layout.app', ['title' => 'Enrollment'])

@section('content')
<style type="text/css">
.select2-container .select2-selection--single .select2-selection__rendered {
  padding-left: 0px !important;
}
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-file-text"></i> Enrollment
    <small>Enrollment List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Enrollment</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <div class="row">
          <div class="col-md-1">
            <div class="form-group">
              <label for="enrollment_no">No.</label>
              <input type="text" id="enrollment_no" name="enrollment_no" class="form-control onlyint filter_me" placeholder="1">
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label for="search_by_name">Name</label>
              <input type="search" id="search_by_name" name="search_by_name" class="form-control filter_me" placeholder="Baldevbhai Patel">
            </div>
          </div>

          <div class="col-md-2">
            <div class="form-group">
              <label for="search_by_contact">Contact</label>
              <input type="text" id="search_by_contact" name="search_by_contact" class="form-control filter_me" placeholder="873388XXXX" data-inputmask='"mask": "9999999999"' data-mask>
            </div>
          </div>

          <div class="col-md-2">
            <div class="form-group">
              <label for="search_by_email">Email</label>
              <input type="email" id="search_by_email" name="search_by_email" class="form-control filter_me" placeholder="find@email.com">
            </div>
          </div>

          <div class="col-md-2">
            <div class="form-group">
              <label for="certificate">Certificate</label>
              <select style="width:100%;" id="certificate" name="certificate" class="form-control select2">
                  <option value="">Select Certificate</option>
                  @foreach($cerificate as $row)
                    <option value="{{ $row->id }}">{{ $row->short_name }}</option>
                  @endforeach
              </select>
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
              <th data-orderable="false">Method</th>
              <th data-orderable="false">Contact</th>
              <th data-orderable="false">Company / Ref</th>
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
<a href="{{ route('new.enrollment') }}" class="btn btn-primary buy-now-btn">
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
      "url": "{{ route('enrollment.list.ajax') }}",
      "data": function (d) {
        return  $.extend(d, AjaxData);
      }
    },
    "searching": false,
    "bLengthChange": false,
    "bAutoWidth": false,
    "pageLength": 20,
    "columns" : [
      {"data":"user_info","sWidth": "26%"},
      {"data":"certificate_info","sWidth": "22%"},
      {"data":"contact_info","sWidth": "16%"},
      {"data":"other_info","sWidth": "21%"},
      {"data":"action","sWidth": "15%", "searchable": false , "orderable": false},
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

  //setup before functions
  var typingTimer;                //timer identifier
  var doneTypingInterval = 1000;  //time in ms, 5 second for example
  var $input = $('.filter_me');

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
    var enrollment_no = $("#enrollment_no").val();
    var search_by_name = $("#search_by_name").val();
    var search_by_contact = $("#search_by_contact").val();
    var search_by_email = $("#search_by_email").val();
    //do something
    AjaxData.enrollment_no = enrollment_no;
    AjaxData.search_by_name = search_by_name;
    AjaxData.search_by_contact = search_by_contact;
    AjaxData.search_by_email = search_by_email;

    dtable.ajax.reload();
}

$(document).on("change","#certificate",function(e){
  AjaxData.certificate_no = $(this).val();
  dtable.ajax.reload();
});

$(document).on('click', '.clearfilter', function() {
  $("#enrollment_no").val('').trigger('change') ;
  $("#search_by_name").val('').trigger('change') ;
  $("#search_by_contact").val('').trigger('change') ;
  $("#search_by_email").val('').trigger('change') ;
  $("#certificate").val('').trigger('change') ;
  AjaxData.enrollment_no = "";
  AjaxData.search_by_name = "";
  AjaxData.search_by_contact = "";
  AjaxData.search_by_email = "";
  AjaxData.certificate_no = "";
  dtable.ajax.reload();
});

$(document).on("change",".chkbox",function(e){
  var id = this.id;
  var index = $.inArray(id, selected);

  if ( index === -1 ) {
    selected.push( id );
  } else {
    selected.splice( index, 1 );
  }
});
</script>

@endsection
