@extends('admin.v1.layout.app', ['title' => 'Expenses'])

@section('content')
<style type="text/css">
.mb-10 {
  margin-bottom: 10px !important;
}
.rendering_data {
  cursor: pointer;
}
  .filterdiv {
    display: none;
  }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-sign-out"></i> Expenses
    <small>Manage Expenses</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Expenses</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-4">
      <div class="box box-primary">
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <button type="button" class="btn btn-primary btn-xs pull-right" style="margin-bottom: -30px;" data-toggle="modal" data-target="#AddCategory"><i class="fa fa-plus"></i> Add Category</button>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered dtable2">
            <thead>
                <tr class="bg-info">
                  <th data-orderable="false">Category</th>
                  <th class="text-right" data-orderable="false">Amount</th>
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
    <div class="col-md-8">
      <div class="box box-primary">
        <div class="box-header">
              <button type="button" class="btn btn-info btn-xs showfilter">
                <i class="glyphicon glyphicon-filter"></i> Filter
              </button>
        </div>
      <div class="box-body">
        <div class="row filterdiv">
          <div class="col-md-2">
            <div class="form-group">
              <label for="bill_no">Bill No</label>
              <input type="text" id="bill_no" name="bill_no" class="form-control" placeholder="Bill No">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="filter_by">Name</label>
              <select style="width:100%;" id="filter_by" name="filter_by" class="form-control select2-bank" data-placeholder="Select Name">
                <option value="">Select Name</option>
                @foreach($userlist as $row)
                  <option data-img="{{ $row->userphoto }}" value="{{ $row->usertype."_".$row->userid }}">{{ ucwords($row->username) }} - {{ ucwords(config('master.'.$row->usertype)['name']) }}</option>
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
                  <th data-orderable="false">Date</th>
                  <th data-orderable="false">B. No</th>
                  <th data-orderable="false">Name</th>
                  <th data-orderable="false">Amount</th>
                  <th data-orderable="false">Paid</th>
                  <th data-orderable="false">Balance</th>
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

<!-- Modal -->
<div id="AddCategory" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Category</h4>
      </div>
      <form method="post" id="add_category">
      <div class="modal-body">
        <div class="row">
        <div class="col-md-12">
        <div class="form-group">
              <label for="category_name">Category Name</label>
              <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
              <input type="text" id="category_name" name="category_name" class="form-control">
              </div>
          </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="addCategory()"><i class="fa fa-upload"></i> Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>

<div id="EditCategory" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Category</h4>
      </div>
      <form method="post" id="edit_category">
      <input type="hidden" id="edit_category_id" name="edit_category_id" value="0">
      <input type="hidden" id="able_to_delete" name="able_to_delete" value="1">
      <div class="modal-body">
        <div class="row">
        <div class="col-md-12">
        <div class="form-group">
              <label for="edit_category_name">Category Name</label>
              <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
              <input type="text" id="edit_category_name" name="edit_category_name" class="form-control">
              </div>
          </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="editCategory()"><i class="fa fa-upload"></i> Update</button>
        <button type="button" class="btn btn-danger" onclick="deleteCategory()"><i class="fa fa-trash"></i> Delete</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>

<div class="buy-now">
<a href="{{ route('add.expenses') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-plus" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<script type="text/javascript">
var dtable;
var dtable2;
var AjaxData = {"id": "{{ $default_category }}"};

//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('#bill_no');

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

$(document).on('change', '#filter_by', function() {
  AjaxData.filter_by = $(this).val();
  dtable.ajax.reload();
});

function changeDateRange() {
  AjaxData.startdate = $("#filterStart").val();
  AjaxData.enddate = $("#filterEnd").val();
  dtable.ajax.reload();
}

$(document).on('click', '.clearfilter', function() {
  $("#filter_by").val('').trigger('change') ;
  $("#bill_no").val('');
  $('.daterange span').html('<i class="fa fa-calendar"></i> Date range picker');
  AjaxData.filter_by = "";
  AjaxData.bill_no = "";
  AjaxData.startdate = "";
  AjaxData.enddate = "";
  dtable.ajax.reload();
  $(".filterdiv").slideUp();
});

$(document).on("click",".showfilter",function(e){
  $(".filterdiv").stop().slideToggle();
});

$(document).on("click",".rendering_data",function(e){
  AjaxData.id = $(this).attr('data-id');
  $(".rendering_data").removeClass('bg-success');
  $(this).addClass('bg-success');
  dtable.ajax.reload();
});

$(document).on("dblclick",".rendering_data",function(e){
  $("#able_to_delete").val($(this).attr('data-amount'));
  $.ajax({
    url:'{{ route('get.category.info') }}',
    data:'id='+$(this).attr('data-id'),
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        var result = e.result;
        $("#edit_category_id").val(result.id);
        $("#edit_category_name").val(result.name);
        $("#EditCategory").modal('show');
      } else {
        toastr.error(e.message);
      }
    }
  });
});


var dtable_lang = {
  search: "_INPUT_",
  searchPlaceholder: "Search Category"
};
$.extend( $.fn.dataTableExt.oStdClasses, {
  "sFilterInput": "form-control",
  "sLengthSelect": "form-control"
});

$(document).ready(function() {
  dtable2 = $('.dtable2').DataTable({
    "dataSrc": "Data",
    "dom": '<"pull-left"f><"pull-right"l>tip',
    "language": dtable_lang,
    "processing" : true,
    "serverSide" : true,
    // "paging": false,
    // "searching": false,
    "bLengthChange": false,
    "bInfo": false,
    "order" : [],
    "ajax" : "{{ route('get.expenses.category') }}",
    "columns" : [
      {"data":"name"},
      {"data":"expence_amount", className: "text-right"}
    ]
  });

  dtable = $('.dtable').DataTable({
    "dataSrc": "Data",
    "searching": false,
    "processing" : true,
    "serverSide" : true,
    "bLengthChange": false,
    "order" : [],
    "ajax" : {
        "url": "{{ route('get.expenses.ajax') }}",
        "data": function (d) {
          return  $.extend(d, AjaxData);
        }
    },
    "bAutoWidth": false,
    "columns" : [
      {"data":"formate_date","width": "10%"},
      {"data":"bill_no","width": "10%"},
      {"data":"formate_name","width": "18%"},
      {"data":"formate_amount","width": "13%"},
      {"data":"formate_paid","width": "13%"},
      {"data":"formate_balace","width": "13%"},
      {"data":"action", "searchable": false , "orderable": false, "width": "23%"},
    ]
  });

});


function addCategory() {
  if($("#category_name").val() == "")
	{
		toastr.error("Please Enter Category Name");
		$("#category_name").focus();
		return false;
	}

  var category_name = $("#category_name").val();
  $.ajax({
    url:'{{ route('add.expenses.category') }}',
    type:'POST',
    data:'_token={{ csrf_token() }}&name='+category_name,
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        $("#category_name").val('');
        $("#AddCategory").modal('hide');
        toastr.success("Category Added Successfully");
      } else {
        toastr.error(e.message);
      }
      dtable2.draw();
    }
  });
}
function editCategory() {
  if($("#edit_category_id").val() == "")
	{
		toastr.error("Please Enter Category Id");
		$("#edit_category_id").focus();
		return false;
	}
  if($("#edit_category_name").val() == "")
	{
		toastr.error("Please Enter Category Name");
		$("#edit_category_name").focus();
		return false;
	}

  var category_name = $("#edit_category_name").val();
  var category_id = $("#edit_category_id").val();
  $.ajax({
    url:'{{ route('update.expenses.category') }}',
    type:'POST',
    data:'_token={{ csrf_token() }}&name='+category_name+"&id="+category_id,
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        $("#EditCategory").modal('hide');
        toastr.success("Category Updated Successfully");
      } else {
        toastr.error(e.message);
      }
      dtable2.draw();
    }
  });
}
function deleteCategory() {
  if($("#able_to_delete").val() != 0) {
    toastr.error("This expenses category can not be deleted as it already has expenses. Please delete all expenses before deleting the expense category.");
    return false;
  }

  var conf  = confirm("Are you sure want to delete this record?");
  if(conf === false) {
    return false;
  }
  if($("#edit_category_id").val() == "")
	{
		toastr.error("Please Enter Category Id");
		$("#edit_category_id").focus();
		return false;
	}
  $("#EditCategory").modal('hide');
  $.ajax({
    url:'{{ route('delete.expenses.category') }}',
    type:'POST',
    data:'_token={{ csrf_token() }}&id='+$("#edit_category_id").val(),
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        toastr.success("Category Deleted Successfully");
        setTimeout(function(){ location.reload(); }, 2000);
      } else {
        toastr.error(e.message);
      }
      dtable2.draw();
    }
  });
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




</script>

@endsection
