@extends('admin.v1.layout.app', ['title' => 'Programme Card'])

@section('content')
<style type="text/css">
.row-spliter {
    height: 1px;
    background:
    #e1e1e1;
    width: 100%;
    margin-top: 5px;
    margin-bottom: 5px;
}
.forwordbtn{
  margin-bottom:0px !important;
}
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-user"></i> Programme Card
    <small>Manage Programme Card</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Programme Card</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-header with-border">
          <button type="button" class="btn btn-info btn-xs no-margin showfilter">
              <i class="glyphicon glyphicon-filter"></i> Filter
          </button>
      </div>
      <div class="box-body">
          <div class="row filterdiv">
              <div class="col-md-2">
                  <div class="form-group">
                      <label for="bill_no">Pcard No</label>
                      <input type="text" id="bill_no" name="bill_no" class="form-control" placeholder="Pcard No">
                  </div>
              </div>

              <div class="col-md-2">
                  <div class="form-group">
                      <label for="stock_no">Stock No</label>
                      <select style="width: 100%;" id="stock_no" name="stock_no" class="form-control select2">
                          <option value="">Select Stock</option>
                          @foreach($stock_list as $row)
                              <option value="{{ $row->id }}" data-pending="{{ $row->pending }}" data-unit="{{ $row->unit }}">{{ Admin::FormateStockItemID($row->id) }} </option>
                          @endforeach
                      </select>
                  </div>
              </div>

              <div class="col-md-2">
                  <div class="form-group">
                      <label for="design_name">Design</label>
                      <input type="text" id="design_name" name="design_name" class="form-control" placeholder="Design Name">
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

              <div class="col-md-2">
                  <div class="form-group">
                      <button type="button" style="margin-top: 24px;"  class="btn btn-danger clearfilter">
                          <i class="fa fa-times"></i> Clear Filter
                      </button>
                  </div>
              </div>
          </div>
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered dtable fixtablemobile">
            <thead>
                <tr>
                  <th data-orderable="false">No.</th>
                  <th data-orderable="false">Stock No</th>
                  <th data-orderable="false">Design</th>
                  <th data-orderable="false">
                      <div class="row">
                      <div class="col-md-2 col-xs-3">
                          Qty
                      </div>
                      <div class="col-md-2 col-xs-2">
                          Image
                      </div>
                      <div class="col-md-2 col-xs-3">
                          Category
                      </div>
                      <div class="col-md-6 col-xs-6">
                          Design Code
                      </div>
                      </div>
                  </th>
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
<a href="{{ route('verify.stock.number') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-plus" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<script type="text/javascript">
var dtable;
var AjaxData = {};
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

$(document).on("click",".showfilter",function(e){
    $(".filterdiv").stop().slideToggle();
});

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
    dtable = $('.dtable').DataTable({
    "dataSrc": "Data",
    "searching": false,
    "processing" : true,
    "serverSide" : true,
    "bLengthChange": false,
    "order" : [],
    "bAutoWidth": false,
    "ajax" : {
        "url": "{{ route('programme.card.ajax') }}",
        "data": function (d) {
            return  $.extend(d, AjaxData);
        }
    },
    "columns" : [
      {"data":"pc_unique_number_info","sWidth": "10%"},
      {"data":"formated_stock","sWidth": "10%"},
      {"data":"dname","sWidth": "10%"},
      {"data":"designlineinfo","sWidth": "50%"},
      {"data":"action", "searchable": false , "orderable": false,"sWidth": "20%"},
    ],
    "fnDrawCallback": function() {
            $('.status_checkbox').bootstrapToggle();
            // $('.make-switch').bootstrapSwitch();
        },
  });
});


//filter
//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('#bill_no');
var $input2 = $('#design_name');

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
    AjaxData.bill_no = $input.val();
    dtable.ajax.reload();
}


//on keyup, start the countdown
$input2.on('keyup', function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping2, doneTypingInterval);
});

//on keydown, clear the countdown
$input2.on('keydown', function () {
    clearTimeout(typingTimer);
});

//user is "finished typing," do something
function doneTyping2 () {
    //do something
    AjaxData.design_name = $input2.val();
    dtable.ajax.reload();
}

function changeDateRange() {
    AjaxData.startdate = $("#filterStart").val();
    AjaxData.enddate = $("#filterEnd").val();
    dtable.ajax.reload();
}

$(document).on('change', '#stock_no', function() {
    AjaxData.stock_no = $(this).val();
    dtable.ajax.reload();
});

$(document).on('click', '.clearfilter', function() {
    $("#stock_no").val('').trigger('change') ;
    $("#bill_no").val('');
    $("#design_name").val('');
    $('.daterange span').html('<i class="fa fa-calendar"></i> Date range picker');
    AjaxData.stock_no = "";
    AjaxData.bill_no = "";
    AjaxData.design_name = "";
    AjaxData.startdate = "";
    AjaxData.enddate = "";
    dtable.ajax.reload();
    $(".filterdiv").slideUp();
});

</script>

@endsection
