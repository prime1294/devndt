@extends('admin.v1.layout.app', ['title' => 'Machine'])

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-user"></i> Machine
    <small>Manage Machine</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Machine</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">List of Machine</h3>

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
          <table class="table table-hover table-striped table-bordered dtable fixtablemobile">
            <thead>
                <tr>
                  <th data-orderable="false">Machine</th>
                  <th data-orderable="false">Number</th>
                  <th data-orderable="false">Type</th>
                  <th data-orderable="false">Area</th>
                  <th data-orderable="false">Head</th>
                  <th data-orderable="false">Frame</th>
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
<a href="{{ route('machine.new') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-plus" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<script type="text/javascript">
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
    url:'{{ route("machine.activation") }}',
    data:'id='+id+"&status="+status+"&_token={{ csrf_token() }}",
    type:'POST'
  })
	  .done(function(e) {
		if(e)
		{
			toastr.success("Machine "+status_txt+" Successfully.");
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
  searchPlaceholder: "Search Machine Name"
};
$.extend( $.fn.dataTableExt.oStdClasses, {
  "sFilterInput": "form-control",
  "sLengthSelect": "form-control"
});
$(document).ready(function() {
  $('.dtable').DataTable({
    "dataSrc": "Data",
    "dom": '<"pull-left"f><"pull-right"l>tip',
    "language": dtable_lang,
    "processing" : true,
    "serverSide" : true,
    "order" : [],
    "ajax" : "{{ route('machine.list.ajax') }}",
    "bLengthChange": false,
    "bAutoWidth": false,
    "columns" : [
      {"data":"party","sWidth": "15%"},
      {"data":"machine_no","sWidth": "10%"},
      {"data":"machine_type","sWidth": "25%"},
      {"data":"machine_area","sWidth": "10%"},
      {"data":"machine_head","sWidth": "10%"},
      {"data":"machine_frame","sWidth": "10%"},
      {"data":"action", "searchable": false , "orderable": false,"sWidth": "20%"},
    ],
    "fnDrawCallback": function() {
            $('.status_checkbox').bootstrapToggle();
            // $('.make-switch').bootstrapSwitch();
        },
  });
});
</script>

@endsection
