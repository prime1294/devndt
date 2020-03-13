@extends('admin.v1.layout.app', ['title' => 'City'])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-globe"></i> City
    <small>Manage City</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">City</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-4">
      <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Add City</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                  title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <form method="post" action="{{ route('register.cities') }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <input type="hidden" name="country_id" value="101">
          <div class="form-group">
            <label for="state_id">State</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
              <select  style="width: 100%;" id="state_id" name="state_id" class="form-control select2">
                <option value="">Select State</option>
                <?php
                foreach($states as $row) {
                ?>
                <option value="{{ $row->state_id }}" data-gst="{{ $row->gst_code }}" {{ @$pinfo->state == $row->state_id ? "selected" : "" }}>{{ $row->state }}</option>
                <?php
                }
                ?>
              </select>
            </div>
          </div>
            <div class="form-group">
              <label for="city">City Name</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="text" id="city" name="city" class="form-control" value="">
              </div>
            </div>
              <div class="form-devider"></div>
              <div class="form-group">
              <button type="submit" class="btn btn-primary" onclick="return val_add_type();"><i class="fa fa-plus"></i> Add New</button>
              <button type="reset" class="btn btn-danger"><i class="fa fa-trash"></i> Clear</button>
              </div>
          </form>
      </div>
      <!-- /.box-body -->
    </div>
    </div>
    <div class="col-md-8">
      <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">City</h3>

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
          <table class="table table-hover table-striped table-bordered dtable">
            <thead>
                <tr>
                  <th>Name</th>
                  <th>State</th>
                  <th>Action</th>
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


<div id="EditModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="overlay"><div class="text"><i class="fa fa-refresh fa-spin"></i></div></div>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update City</h4>
      </div>
      <form method="post" action="{{ route('city.update') }}" enctype="multipart/form-data">
      <div class="modal-body">
        {!! csrf_field() !!}
        <input type="hidden" id="edit_unique_id" name="edit_unique_id" value="">
        <div class="row">
        <div class="col-md-12">
          <div class="form-group">
                <label for="edit_state_id">State Name</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                  <select  style="width: 100%;" id="edit_state_id" name="state_id" data-placeholder="Select State" class="form-control select2">
                    <option value="">Select State</option>
                    <?php
                    foreach($states as $row) {
                    ?>
                    <option value="{{ $row->state_id }}" data-gst="{{ $row->gst_code }}">{{ $row->state }}</option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
            </div>
        </div>
          <div class="col-md-12">
            <div class="form-group">
              <label for="edit_city">City Name</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="text" id="edit_city" name="city" class="form-control" value="">
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
function val_edit_type() {
  if($("#edit_unique_id").val() == "")
	{
		toastr.error("Unique id required");
		$("#edit_unique_id").focus();
		return false;
	}

  if($("#edit_state_id").val() == "")
	{
		toastr.error("Please, Enter State Name");
		$("#edit_state").focus();
		return false;
	}

  if($("#edit_city").val() == "")
  {
    toastr.error("Please, Enter GST Code");
    $("#edit_city").focus();
    return false;
  }
}
function val_add_type()
{
	if($("#state_id").val() == "")
	{
		toastr.error("Please, Select State");
		$("#state_id").focus();
		return false;
	}

    if($("#city").val() == "")
    {
      toastr.error("Please, Enter City Name");
      $("#city").focus();
      return false;
    }
}

function getformelement(id)
{
modaloverlay("#EditModal","show");
var jqxhr = $.ajax({
  url:"{{ route('cities.info') }}",
  data:"id="+id
  })
  .done(function(e) {
	if(e)
	{
		//console.log(e);
		$("#edit_unique_id").val(e.city_id);
		$("#edit_state_id").val(e.state_id).trigger('change');
		$("#edit_city").val(e.city);
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
    url:'{{ route("manufacturer.types.activation") }}',
    data:'id='+id+"&status="+status+"&_token={{ csrf_token() }}",
    type:'POST'
  })
	  .done(function(e) {
		if(e)
		{
			toastr.success("Area "+status_txt+" Successfully.");
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
  searchPlaceholder: "Search City"
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
    "bLengthChange": false,
    "order" : [],
    "processing" : true,
    "serverSide" : true,
    "ajax" : "{{ route('city.list.ajax') }}",
    "columns" : [
      {"data":"city","orderable": false},
      {"data":"state_name","orderable": false,"searchable": false },
      {"data":"action","searchable": false , "orderable": false}
    ],
    "initComplete": function(settings, json) {
      // alert( 'DataTables has finished its initialisation.' );
      $('#toggle-demo').bootstrapToggle();
    },
    "fnDrawCallback": function() {
            // $('#toggle-demo').bootstrapToggle();
            // $('.make-switch').bootstrapSwitch();
        },
  });
});
</script>

@endsection
