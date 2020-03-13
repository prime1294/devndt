@extends('admin.v1.layout.app', ['title' => 'Daily Production'])

@section('content')
<style type="text/css">
.mytbl .form-group, .mytbl2 .form-group {
  margin-bottom: 0px;
}
.mytbl tr td, .mytbl2 tr td {
  padding: 5px !important;
}
.mytbl .ele1, .mytbl .ele2 {
  width: 18%;
}
.mytbl .ele3, .mytbl .ele4, .mytbl .ele5, .mytbl .ele6, .mytbl .ele7, .mytbl .ele8 {
  width: 10%;
}
.mytbl .ele9{
  width: 4%;
}
.mytbl2 .ele1 {
  width: 18%;
}
.mytbl2 .ele3 {
  width: 13%;
}
.mytbl2 .ele5 {
  width: 10%;
}
.mytbl2 .ele9 {
  width: 8%;
}
.mytbl2 .ele2,.mytbl2 .ele4,.mytbl2 .ele6,.mytbl2 .ele7,.mytbl2 .ele8,.mytbl2 .ele10,.mytbl2 .ele11 {
  width: 7%;
}
.mytbl .ele12{
  width: 2%;
}
.mt-sm {
  margin-top: 8px;
}
.mytbl2 .select2-container {
  width: 100% !important;
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
      <!-- tabs -->
      <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Daily Production</a></li>
              <li><a href="#tab_2" data-toggle="tab">Frame Report</a></li>
              <!-- <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-edit"></i></a></li> -->
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <!-- transection -->
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="adjustment_date">Report Date</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      <input type="text" id="adjustment_date" name="adjustment_date" value="" class="form-control datepicker">
                      </div>
                    </div>
                    </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-hover table-striped table-bordered mytbl">
                    <thead>
                        <tr>
                          <th class="text-center" data-orderable="false">Machine</th>
                          <th class="text-center" data-orderable="false">Karigar</th>
                          <th class="text-center" data-orderable="false">Ship</th>
                          <th class="text-center" data-orderable="false">T.B.No</th>
                          <th class="text-center" data-orderable="false">Stitch</th>
                          <th class="text-center" data-orderable="false">Frame</th>
                          <th class="text-center" data-orderable="false">Job Work</th>
                          <th class="text-center" data-orderable="false">Remarks</th>
                          <th class="text-center" data-orderable="false"></th>
                        </tr>
                    </thead>
                    <tbody>
                      @for($x=1;$x<=3;$x++)
                      <tr>
                        <td class="ele1">
                          <div class="form-group">
                          <select style="width:100%;" id="machine" name="machine" class="form-control select2-bank" data-placeholder="Select Machine">
                            <option value="">Select Machine</option>
                            <option data-img="{{ 'plus.png' }}" value="0">Add New</option>
                            @foreach($machine_list as $row)
                            <option data-img="{{ $row->photo }}" value="{{ $row->id }}">{{ ucwords($row->machine_type) }} - {{ $row->company_name }}</option>
                            @endforeach
                          </select>
                          </div>
                        </td>
                        <td class="ele2">
                          <div class="form-group">
                          <select id="karigar" name="karigar" class="form-control select2-bank" data-placeholder="Select Karigar">
                            <option value="">Select Karigar</option>
                            <option data-img="{{ 'plus.png' }}" value="0">Add New</option>
                            @foreach($karigar_list as $row)
                            <option data-img="{{ $row->photo }}" value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                            @endforeach
                          </select>
                          </div>
                        </td>
                        <td class="ele3">
                          <div class="form-group">
                          <select id="ship" name="ship" class="form-control select2">
                            <option value="1">Day</option>
                            <option value="2">Night</option>
                          </select>
                          </div>
                        </td>
                        <td class="ele4">
                          <div class="form-group">
                          <input type="text" id="tbno" name="tbno" class="form-control tbno text-center onlyint" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele5">
                          <div class="form-group">
                          <input type="text" id="stitch" name="stitch" class="form-control stitch text-center onlyint" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele6">
                          <div class="form-group">
                          <input type="text" id="frame" name="frame" class="form-control frame text-center onlyint" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele7">
                          <div class="form-group">
                          <input type="text" id="jobwork" name="jobwork" readonly class="form-control jobwork text-center onlyint" value="0" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele8">
                          <div class="form-group">
                          <input type="text" id="remarks" name="remarks" class="form-control remarks text-center" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele9">
                          <div class="form-group">
                          <button class="btn btn-danger btn-xs mt-sm"><i class="fa fa-trash"></i></button>
                          </div>
                        </td>
                      </tr>
                      @endfor
                    </tbody>
                  </table>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <button type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Add Row</button>
                    <button type="button" class="btn btn-primary"><i class="fa fa-upload"></i> Submit Report</button>
                  </div>
                </div>
                <!-- end transection -->
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="frame_report_date">Report Date</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      <input type="text" id="frame_report_date" name="frame_report_date" value="" class="form-control datepicker">
                      </div>
                    </div>
                    </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-hover table-striped table-bordered mytbl2">
                    <thead>
                        <tr>
                          <th class="text-center" data-orderable="false">Machine</th>
                          <th class="text-center" data-orderable="false">Frame</th>
                          <th class="text-center" data-orderable="false">Prog. Card</th>
                          <th class="text-center" data-orderable="false">Design</th>
                          <th class="text-center" data-orderable="false">Monit. No</th>
                          <th class="text-center" data-orderable="false">Stitch</th>
                          <th class="text-center" data-orderable="false">Time</th>
                          <th class="text-center" data-orderable="false">Quantity</th>
                          <th class="text-center" data-orderable="false">Unit</th>
                          <th class="text-center" data-orderable="false">Rate</th>
                          <th class="text-center" data-orderable="false">Remarks</th>
                          <th class="text-center" data-orderable="false"></th>
                        </tr>
                    </thead>
                    <tbody>
                      @for($x=1;$x<=3;$x++)
                      <tr>
                        <td class="ele1">
                          <div class="form-group">
                          <select id="fr_machine" name="fr_machine" class="form-control select2-bank" data-placeholder="Select Machine">
                            <option value="">Select Machine</option>
                            <option data-img="{{ 'plus.png' }}" value="0">Add New</option>
                            @foreach($machine_list as $row)
                            <option data-img="{{ $row->photo }}" value="{{ $row->id }}">{{ ucwords($row->machine_type) }} - {{ $row->company_name }}</option>
                            @endforeach
                          </select>
                          </div>
                        </td>

                        <td class="ele2">
                          <div class="form-group">
                          <input type="text" id="fr_frame" name="fr_machine" class="form-control fr_machine text-center onlyint" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele3">
                          <div class="form-group">
                            <select id="fr_programmecard" name="fr_programmecard" class="form-control select2">
                              @foreach($pc_list as $row)
                              <option value="{{ $row->id }}">#{{ Admin::FormatePCId($row->id) }}</option>
                              @endforeach
                            </select>
                          </div>
                        </td>
                        <td class="ele4">
                          <div class="form-group">
                          <input type="text" id="fr_design" name="fr_design" class="form-control fr_design text-center" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele5">
                          <div class="form-group">
                          <input type="text" id="fr_monitor" name="fr_monitor" class="form-control fr_monitor text-center" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele6">
                          <div class="form-group">
                          <input type="text" id="fr_stitch" name="fr_stitch" class="form-control fr_stitch text-center" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele7">
                          <div class="form-group">
                          <input type="text" id="fr_time" name="fr_time" class="form-control fr_time text-center" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele8">
                          <div class="form-group">
                          <input type="text" id="fr_quantity" name="fr_quantity" class="form-control fr_quantity text-center onlyint" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele9">
                          <div class="form-group">
                            <select id="fr_unit" name="fr_unit" class="form-control select2">
                              @foreach($stock_unit as $row)
                              <option value="{{ $row->id }}">{{ $row->name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </td>
                        <td class="ele10">
                          <div class="form-group">
                          <input type="text" id="fr_rate" name="fr_rate" class="form-control fr_rate text-center onlyint" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele11">
                          <div class="form-group">
                          <input type="text" id="fr_remarks" name="fr_remarks" class="form-control fr_remarks text-center" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele12">
                          <div class="form-group">
                          <button class="btn btn-danger btn-xs mt-sm"><i class="fa fa-trash"></i></button>
                          </div>
                        </td>
                      </tr>
                      @endfor
                    </tbody>
                  </table>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <button type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Add Row</button>
                    <button type="button" class="btn btn-primary"><i class="fa fa-upload"></i> Submit Report</button>
                  </div>
                </div>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
      <!-- end tabs -->
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">List of Daily Production</h3>

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
                  <th data-orderable="false">Karigar</th>
                  <th data-orderable="false">T.B.No</th>
                  <th data-orderable="false">Stitch</th>
                  <th data-orderable="false">Frame</th>
                  <th data-orderable="false">Job Work</th>
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

$(document).ready(function() {
  // $('.dtable').DataTable({
  //   "dataSrc": "Data",
  //   "processing" : true,
  //   "serverSide" : true,
  //   "order" : [],
  //   "ajax" : "{{ route('machine.list.ajax') }}",
  //   "columns" : [
  //     {"data":"party"},
  //     {"data":"machine_area"},
  //     {"data":"machine_head"},
  //     {"data":"machine_frame"},
  //     {"data":"action", "searchable": false , "orderable": false},
  //   ],
  //   "fnDrawCallback": function() {
  //           $('.status_checkbox').bootstrapToggle();
  //           // $('.make-switch').bootstrapSwitch();
  //       },
  // });
});
</script>

@endsection
