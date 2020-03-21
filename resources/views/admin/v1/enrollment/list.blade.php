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
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered dtable tbldblclick fixtablemobile">
            <thead>
            <tr>
              <th data-orderable="false"></th>
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
  table = $('.dtable').DataTable({
    "dataSrc": "Data",
    "dom": '<"pull-left"f><"pull-right"l>tip',
    "language": dtable_lang,
    "processing" : true,
    "serverSide" : true,
    "order" : [],
    "ajax" : "{{ route('enrollment.list.ajax') }}",
    "bLengthChange": false,
    "bAutoWidth": false,
    "pageLength": 100,
    "columns" : [
      {"data":"check","sWidth": "3%"},
      {"data":"user_info","sWidth": "25%"},
      {"data":"certificate_info","sWidth": "21%"},
      {"data":"contact_info","sWidth": "16%"},
      {"data":"other_info","sWidth": "20%"},
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
