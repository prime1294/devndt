@extends('admin.v1.layout.app', ['title' => 'Material'])

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-user"></i> Material
    <small>Manage Material</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Material</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">List of Material</h3>

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
          <table class="table table-hover table-striped table-bordered dtable tbldblclick fixtablemobile">
            <thead>
                <tr>
                  <th>Name</th>
                  <th data-orderable="false">Amount</th>
                  <th data-orderable="false">Type of Material</th>
                  <th data-orderable="false">GSTIN</th>
                  <th data-orderable="false">Contact</th>
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
<a href="{{ route('material.new') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-plus" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<div id="EditModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="overlay"><div class="text"><i class="fa fa-refresh fa-spin"></i></div></div>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Manufacturer Types</h4>
      </div>
      <form method="post" action="{{ route('manufacturer.types.update') }}" enctype="multipart/form-data">
      <div class="modal-body">
        {!! csrf_field() !!}
        <input type="hidden" id="edit_unique_id" name="edit_unique_id" value="">
        <div class="row">
        <div class="col-md-12">
          <div class="form-group">
                <label for="edit_type_name">Type Name</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="text" id="edit_type_name" name="edit_type_name" class="form-control" value="">
                </div>
            </div>
        </div>
        </div>
      </div>
      <div class="modal-footer">
      	<button type="submit" class="btn btn-primary" onclick="return val_edit_type();"><i class="fa fa-upload"></i> Update</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>

<script type="text/javascript">
var table;
function val_edit_type() {
  if($("#edit_unique_id").val() == "")
	{
		toastr.error("Unique id required");
		$("#edit_unique_id").focus();
		return false;
	}

  if($("#edit_type_name").val() == "")
	{
		toastr.error("Please, Enter Type Name");
		$("#edit_type_name").focus();
		return false;
	}
}
function val_add_type()
{
	if($("#type_name").val() == "")
	{
		toastr.error("Please, Enter Type Name");
		$("#type_name").focus();
		return false;
	}
}

function getformelement(id)
{
modaloverlay("#EditModal","show");
var jqxhr = $.ajax({
  url:"{{ route('manufacturer.types.info') }}",
  data:"id="+id
  })
  .done(function(e) {
	if(e)
	{
		//console.log(e);
		$("#edit_unique_id").val(e.id);
		$("#edit_type_name").val(e.name);
		modaloverlay("#EditModal","hide");
	}
	else
	{
		toastr.error('Ooops..! Something went wrong');
		modaloverlay("#EditModal","hide");
		$("#EditModal").modal('hide');
	}
  })
  .fail(function() {
	toastr.error('Ooops..! Something went wrong');
	modaloverlay("#EditModal","hide");
	$("#EditModal").modal('hide');
  });
}

$(document).on("change",".status_checkbox",function(e){
  // alert('hello');
	var status_txt = $(this).prop('checked') ? "Active" : "Deactive";
	var default_status = $(this).prop('checked') ? "off" : "on";
	var status = $(this).prop('checked') ? 1 : 0;
	var id = $(this).attr('data-id');

	var jqxhr = $.ajax({
    url:'{{ route("material.activation") }}',
    data:'id='+id+"&status="+status+"&_token={{ csrf_token() }}",
    type:'POST'
  })
	  .done(function(e) {
		if(e)
		{
			toastr.success("Material "+status_txt+" Successfully.");
		}
		else
		{
			toastr.error('Ooops..! Something went wrong');
		}
	  })
	  .fail(function() {
		toastr.error('Ooops..! Something went wrong');
	  });
});
var dtable_lang = {
  search: "_INPUT_",
  searchPlaceholder: "Search Material Name"
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
    "ajax" : "{{ route('material.list.ajax') }}",
    "bLengthChange": false,
    "bAutoWidth": false,
    "columns" : [
      {"data":"party","sWidth": "25%"},
      {"data":"transection_amount","sWidth": "9%"},
      {"data":"types_of_menu","sWidth": "18%"},
      {"data":"gstininfo","sWidth": "15%"},
      {"data":"contactinfo","sWidth": "13%"},
      {"data":"action", "searchable": false , "orderable": false,"sWidth": "20%"},
    ],
    "fnDrawCallback": function() {
            $('.status_checkbox').bootstrapToggle();
            // $('.make-switch').bootstrapSwitch();
        },
  });
});

$('.dtable tbody').on('dblclick', 'tr', function () {
var data = table.row( this ).data();
var user_id = data.id;
if(user_id) {
  var route = '{{ route('material.view',":ID") }}';
  route = route.replace(':ID', user_id);
  window.location.href = route;
}
});
</script>

@endsection
