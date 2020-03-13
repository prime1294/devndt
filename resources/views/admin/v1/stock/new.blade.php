@extends('admin.v1.layout.app', ['title' => 'Add Stock'])

@section('content')
<style type="text/css">
.select2-container .select2-selection--single .select2-selection__rendered {
  padding-left: 0px !important;
}
.banktransferinput {
  display: none;
}
.bggray {
  background-color: #ECF0F5;
}
.qty .count {
    color: #000;
    display: inline-block;
    vertical-align: top;
    font-size: 25px;
    font-weight: 700;
    line-height: 30px;
    padding: 0 2px
    ;min-width: 70px;
    text-align: center;
}
.qty .plus {
    cursor: pointer;
    display: inline-block;
    vertical-align: top;
    color: #717FE0;
    width: 21px;
    border: 1px solid #717FE0;
    height: 22px;
    font: 21px/1 Arial,sans-serif;
    text-align: center;
    border-radius: 50%;
    margin-top: 3px;
    }
.qty .minus {
    cursor: pointer;
    display: inline-block;
    vertical-align: top;
    color: #717FE0;
    border: 1px solid #717FE0;
    width: 21px;
    height: 22px;
    font: 19px/1 Arial,sans-serif;
    text-align: center;
    border-radius: 50%;
    background-clip: padding-box;
    margin-top: 3px;
}
.minus:hover{
    background-color: #717fe0 !important;
    color:#FFFFFF;
}
.plus:hover{
    background-color: #717fe0 !important;
    color:#FFFFFF;
}
/*Prevent text selection*/
span{
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}
.count{
    border: 0;
    width: 2%;
}
.count:disabled{
    background-color:white;
}
.seprator {
  margin-bottom: 10px;
  border-bottom: 1px solid #fbf4f4;
}
.totalquantityctndiv {
    padding: 10px 8px;
}
.totalquantityctn {
  font-size: 20px;
  font-weight: 900;
  text-transform: uppercase;
}
.totalctndiv {
  display: none;
}
.editsubmit , .editreset {
  display: none;
}
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-cubes"></i> Add Stock
    <small>Add New Stock</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Add Stock</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <form method="post" action="{{ route('stock.register',$party_info->id) }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <input type="hidden" id="sui" name="sui" value="{{ $unique_stock_id }}">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="adjustment_date">Date</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="adjustment_date" name="adjustment_date" value="" class="form-control datepicker">
                </div>
              </div>
              </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="challan_no">Challan No.</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" id="challan_no" name="challan_no" value="" class="form-control">
                </div>
              </div>
              </div>
            <div class="col-md-4">
              <div class="form-group">
                    <label for="ref_img">Challan Photo</label>
                    <button type="button" class="btn btn-default btn-block" id="challanphotobtn" onclick="triggerfile('challanphotobtn','upload_challanphotobtn','image/stock/','.jpg,.png,.jpeg')" ><i class="glyphicon glyphicon-folder-open"></i> &nbsp; Browse File</button>
                    <input type="hidden" id="upload_challanphotobtn" value="placeholder.jpg" name="upload_challanphotobtn">
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                    <label for="agent">Agent Name</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <select id="agent" name="agent" class="form-control select2-bank" data-placeholder="Select User">
                      <option value="">Select Agent</option>
                      <option data-img="{{ 'plus.png' }}" value="0">Add New</option>
                      @foreach($agent_list as $row)
                      <option data-img="{{ $row->photo }}" value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                      @endforeach
                    </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                    <label for="transport">Transport Name</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="text" id="transport" name="transport" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="lrno">L.R No.</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" id="lrno" name="lrno" value="" class="form-control">
                </div>
              </div>
              </div>
          </div>


          <div class="row">
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="complete_date">Completed Date</label>
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                          <input type="text" id="complete_date" name="complete_date" value="" class="form-control datepicker">
                      </div>
                  </div>
              </div>
            <div class="col-md-4">
              <div class="form-group">
                    <label for="ref_img">Sample Photo</label>
                    <button type="button" class="btn btn-default btn-block" id="imagebtn" onclick="triggerfile('imagebtn','upload_image_text','image/stock/','.jpg,.png,.jpeg')" ><i class="glyphicon glyphicon-folder-open"></i> &nbsp; Browse File</button>
                    <input type="hidden" id="upload_image_text" name="upload_image_text">
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="location">Location</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" id="location" name="location" value="" class="form-control">
                </div>
              </div>
              </div>
          </div>


          <div class="row">
            <div class="col-md-12">
              <h3 class="page-header">Manage Fabric <button type="button" data-toggle="modal" data-target="#addmyproduct" class="btn btn-primary btn-xs pull-right addproductbtn"><i class="fa fa-plus"></i> Add New Fabric</button></h3>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-hover table-striped table-bordered dtable fixtablemobile">
                    <thead>
                      <tr>
                        <th>Fabric</th>
                        <th>Length</th>
                        <th>Stock No</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <!-- <th>Unit</th> -->
                        <th>Action</th>
                      </tr>
                    </thead>
                  </table>
                </div>
            </div>
          </div>


          <div class="row mt-sm">
            <div class="col-md-12">
              <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea id="remarks" name="remarks" class="form-control" rows="2"></textarea>
                </div>
            </div>
          </div>

          <div class="form-devider"></div>
          <div class="form-group">
          <button type="submit" class="btn btn-primary" onclick="return val_add_party();"><i class="fa fa-plus"></i> Save</button>
          </div>
          </form>
      </div>
      <!-- /.box-body -->
    </div>
    </div>
  </div>
</section>

<div class="buy-now">
<a href="{{ route('party.view',$party_info->id) }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<!-- Modal -->
<div id="addmyproduct" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Manage Quantity</h4>
      </div>
      <form method="post" id="quantity_form" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" id="stock_unique_id" name="stock_unique_id" value="{{ $unique_stock_id }}">
      <div class="modal-body">

          <div class="row">
            <!-- File upload -->
            <div class="col-md-12">
              <div class="fbpickermain">
                <div class=fbpiker>
                    <span class="fbremove"><i class="fa fa-times"></i></span>
                    <img id="fbholdernew" data-default="{{ asset('placeholder.jpg') }}" src="{{ asset('placeholder.jpg') }}"  onclick="triggerfile('fbholdernew','fbinputtxt','image/stock/','.jpg,.png,.jpeg','box')">
                </div>
                <input id="fbinputtxt" name="fbinputtxt" class='fbinputtxt' value="placeholder.jpg" type="hidden" >
              </div>
            </div>
            <!-- end of file upload -->
            <div class="col-md-3">
              <div class="form-group">
                <label for="product_name">Fabric Name</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" id="product_name" name="product_name" value="" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="category">Category</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <select style="width:100%;" id="category" name="category" class="form-control select2">
                  <option value="">Select Category</option>
                  @foreach($stock_category as $row)
                  <option value="{{ $row->id }}">{{ $row->name }}</option>
                  @endforeach
                </select>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="unit">Unit</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <select style="width:100%;" id="unit" class="form-control select2" name="unit">
                  <option value="">Select Unit</option>
                  @foreach($stock_unit as $row)
                  <option value="{{ $row->id }}">{{ $row->name }}</option>
                  @endforeach
                </select>
                </div>
              </div>
            </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="product_length">Length</label>
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-user"></i></span>
                          <input type="text" id="product_length" name="product_length" value="" class="form-control">
                      </div>
                  </div>
              </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <h3 class="page-header">Quantity <button type="button" onclick="add_quantity()" class="btn btn-primary btn-xs pull-right "><i class="fa fa-plus"></i> Add New Quantity</button> </h3>
            </div>
          </div>

          <div class="qunatity_holder">
          <div class="row">
            <div class="col-md-2">
              <div class="qty mt-5">
                  <span class="minus bg-dark">-</span>
                  <input type="text" id="avaliblity" class="count iquantity onlyint" onkeyup="updatetotal()" name="qty[]" min="1" max="1000000" value="1">
                  <span class="plus bg-dark">+</span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" id="measurement" name="measurement[]" placeholder="Unit" value="" class="imeasurement form-control onlyint">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" id="colors" name="colors[]" placeholder="Colors" value="" class="icolorfirst form-control">
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <!-- <div class="form-group">
                <button type="button" class="btn btn-danger remove_quantity btn-xs"><i class="fa fa-trash"></i> Delete</button>
              </div> -->
            </div>
          </div>
          </div>

          <div class="row totalctndiv">
            <div class="col-md-2">
            </div>
            <div class="col-md-4">
              <div class="bg-info">
                <span class="totalquantityctn totalquantityctndiv">Total: 200 Meter (MTR)</span>
              </div>
            </div>
            <div class="col-md-6">
            </div>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary modalsubmitbtn" onclick="return checkquantityval('add')"><i class="fa fa-upload"></i> Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
      </div>
    </form>
    </div>

  </div>
</div>

<script type="text/javascript">
var placeholder = "Unit";
var producttbl;
var editableid = 0;

function assignvaluetoedit(e) {
  resetquantityform();
  // console.log(e);
  editableid = e.id;
  $('#fbholdernew').attr('src','<?php echo url('public'); ?>/'+e.photo);
  $('.fbinputtxt').val(e.photo);
  $("#product_name").val(e.product_name);
  $('#category').val(e.category).trigger('change');
  $('#unit').val(e.unit).trigger('change');
  $("#product_length").val(e.product_length);
  console.log("lenght",e.product_length)
  $( ".iquantity" ).first().val(e.quantity[0].quantity);
  $(".imeasurement").first().val(e.quantity[0].mesurement);
  $(".icolorfirst").val(e.quantity[0].color);

  $.each(e.quantity, function(i, item) {
    if(i != 0) {
      add_quantity(item.quantity,item.mesurement,item.color);
    }
  });

  $(".modalsubmitbtn").attr("onclick","return checkquantityval('update')");
  updatetotal();
  $("#addmyproduct").modal('show');
}

function resetquantityform() {
  var default_holder = $('#fbholdernew').attr('data-default');
  $('#fbholdernew').attr('src',default_holder);
  $('.fbinputtxt').val('placeholder.jpg');

  $("#product_name").val('');
  // $('#category').val('').trigger('change');
  // $('#unit').val('').trigger('change');
  $('#category').val(null).trigger('change');
  $('#unit').val(null).trigger('change');
    $('#product_length').val('');

  $( ".iquantity" ).first().val('1');
  $(".imeasurement").first().val('');
  $(".icolorfirst").val('');

  $('.remove_quantity').each(function(){
    $(this).parent('.form-group').parent('.rmbtn').parent('.row').remove();
  });

  updatetotal();
}

$(document).on("click",".addproductbtn",function(e){
resetquantityform();
$(".modalsubmitbtn").attr("onclick","return checkquantityval('add')");
});


$(document).on("click",".editstockproductbtn",function(e) {
  var url = '{{ route('stock.item.info',":ID") }}';
  url = url.replace(':ID', $(this).attr('data-id'));
  $.ajax({
    url:url,
    type:'POST',
    data:"_token={{ csrf_token() }}",
    success:function(e) {
        if(e.status == "true" && e.message == "success") {
          assignvaluetoedit(e.result);
        } else {
          toastr.error("Oops..! Something went wrong");
          $("#addmyproduct").modal('hide');
        }
    }
  });
});

function editproductsync() {
  var data = $("#quantity_form").serialize();
  $.ajax({
    url:'{{ route('stock.quantity.update') }}',
    type:'POST',
    data: data+"&mainid="+editableid,
    success: function(e) {
      // console.log(e);
      if(e.status == "true" && e.message == "success") {
        toastr.success("Product Updated Successfully");
        producttbl.draw(true);
        $("#addmyproduct").modal('hide');
        resetquantityform();
      } else {
        toastr.error(e.message);
      }
    }
  })
}

$(document).ready(function() {
  producttbl = $('.dtable').DataTable({
    "dataSrc": "Data",
    "processing" : true,
    "searching": false,
    "ordering": false,
    "paging": false,
    "serverSide" : true,
    "ajax" : "{{ route('stock.product.ajax') }}?stock_unique_id={{ $unique_stock_id }}",
    "bAutoWidth": false,
    "columns" : [
      {"data":"stock_info","sWidth": "20%"},
      {"data":"product_length","sWidth": "10%"},
      {"data":"stock_number","sWidth": "15%"},
      {"data":"category_name","sWidth": "10%"},
      {"data":"stock_quantity_html","sWidth": "20%"},
      // {"data":"mesurement_name","sWidth": "15%"},
      {"data":"action","sWidth": "25%"},
    ],
    "fnDrawCallback": function() {
            // $('.status_checkbox').bootstrapToggle();
            // $('.make-switch').bootstrapSwitch();
        },
  });
});


function removeproduct(id) {

  var conf = confirm('Are you sure want to delete this record?');
  if(conf === false) {
    return false;
  }

  var route = '{{ route('stock.product.remove',":ID") }}';
  route = route.replace(':ID', id);
  $.ajax({
    url:route,
    type:'POST',
    data: "_token={{ csrf_token() }}",
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        toastr.success("Product Removed Successfully");
      } else {
        toastr.error(e.message);
      }
      producttbl.draw(true);
    }
  });
}


function syncproduct() {
  // console.log();
  var data = $("#quantity_form").serialize();
  $.ajax({
    url:'{{ route('stock.quantity.register',$party_info->id) }}',
    type:'POST',
    data: data,
    success: function(e) {
      if(e.status == "true" && e.message == "success") {
        toastr.success("Product Added Successfully");
        producttbl.draw(true);
        $("#addmyproduct").modal('hide');
        resetquantityform();
      } else {
        toastr.error(e.message);
      }

    }
  })
}



function checkquantityval(type) {
  if($("#product_name").val() == "")
	{
		toastr.error("Please, Enter Fabric Name");
		$("#product_name").focus();
		return false;
	}

  if($("#category").val() == "")
	{
		toastr.error("Please, Select Category");
		$("#category").focus();
		return false;
	}

  if($("#unit").val() == "")
	{
		toastr.error("Please, Select Unit");
		$("#unit").focus();
		return false;
	}

  if(type == "update") {
    //update product
    editproductsync();
  } else {
    syncproduct();
  }

}
function updatetotal() {
  var total = [];
  var iquantity = $( ".iquantity" ).first().val();
  var imeasurement = $(".imeasurement").first().val();
  if(iquantity && imeasurement) {

    var iquantity_arr = [];
    $('.iquantity').each(function(){
        iquantity_arr.push(parseFloat($(this).val())); //add miunys
    });

    var imeasurement_arr = [];
    $('.imeasurement').each(function(){
        imeasurement_arr.push(parseFloat($(this).val()));
    });

    var total_imeasurement = 0;
    var total_iquantity = 0;
    for (var i = 0; i < imeasurement_arr.length; i++) {
        if(Number(imeasurement_arr[i]) > 0 && Number(iquantity_arr[i]) > 0) {
        total_imeasurement = imeasurement_arr[i];
        total_iquantity = iquantity_arr[i];
        // console.log(total_imeasurement);
        total.push(parseFloat(total_imeasurement) * parseFloat(total_iquantity));
        }
    }

    $(".totalquantityctndiv").text("Total: "+ $.sum(total).toFixed(2)+" "+placeholder);
    $(".totalctndiv").show();
    // alert(iquantity+" "+imeasurement);
  } else {
    $(".totalctndiv").hide();
  }
}

function add_quantity(quantity = 1, mesurement = "", colors = "") {
  var html = '';
  html += '<div class="row">';
    html += '<div class="col-md-2">';
      html += '<div class="qty mt-5">';
          html += '<span class="minus bg-dark">-</span>';
          html += '<input type="text" id="avaliblity" class="count iquantity onlyint" onkeyup="updatetotal()" name="qty[]" min="1" max="1000000" value="'+quantity+'">';
          html += '<span class="plus bg-dark">+</span>';
      html += '</div>';
    html += '</div>';
    html += '<div class="col-md-4">';
      html += '<div class="form-group">';
        html += '<div class="input-group">';
        html += '<span class="input-group-addon"><i class="fa fa-user"></i></span>';
        html += '<input type="text" id="measurement" name="measurement[]" placeholder="'+placeholder+'" value="'+mesurement+'" class="imeasurement form-control onlyint">';
        html += '</div>';
      html += '</div>';
    html += '</div>';
    html += '<div class="col-md-4">';
      html += '<div class="form-group">';
        html += '<div class="input-group">';
        html += '<span class="input-group-addon"><i class="fa fa-user"></i></span>';
        html += '<input type="text" id="colors" name="colors[]" placeholder="Colors" value="'+colors+'" class="form-control">';
        html += '</div>';
      html += '</div>';
    html += '</div>';
    html += '<div class="col-md-2 rmbtn">';
      html += '<div class="form-group">';
        html += '<button type="button" class="btn btn-danger remove_quantity btn-xs"><i class="fa fa-trash"></i> Delete</button>';
      html += '</div>';
  html += '</div>';
  html += '</div>';

  $(".qunatity_holder").append(html);
  $(".onlyint").ForceNumericOnly();

}

function val_add_party()
{
  if($("#entry_type").val() == "")
	{
		toastr.error("Please, Select Entry Type");
		$("#entry_type").focus();
		return false;
	}

  if($("#adjustment_date").val() == "")
	{
		toastr.error("Please, Select Date");
		$("#adjustment_date").focus();
		return false;
	}
  if($('#entry_type').val() == "cash1" || $('#entry_type').val() == "cash2") {
    if($("#bank_account").val() == "") {
      toastr.error("Please, Select Bank Account");
  		$("#bank_account").focus();
  		return false;
    }
  }
}


$(document).on("change","#agent",function(e) {

  if($(this).val() == 0) {
    var redirect_route = "{{ config('master.master2')['new'] }}";
    window.location.href = '{{ route("redirecting") }}?redirectback=stock.new&id={{ $party_info->id }}&redirect='+redirect_route;
  }
});

$(document).on("change","#transport",function(e) {

  if($(this).val() == 0) {
    var redirect_route = "{{ config('master.master7')['new'] }}";
    window.location.href = '{{ route("redirecting") }}?redirectback=stock.new&id={{ $party_info->id }}&redirect='+redirect_route;
  }
});


$(document).on("change","#unit",function(e) {

  if($(this).val() != "") {
    placeholder = $("#unit option:selected").text();
  } else {
    placeholder = "Unit";
  }

  $(".imeasurement").attr('placeholder',placeholder);
  updatetotal();
});

$(document).on('change','#entry_type',function(e) {
  if($(this).val() == "cheque4") {
    $(".banklist").show();
  } else {
    $(".banklist").hide();
  }
});

$(document).on('keyup','.imeasurement',function(){
  updatetotal();
});

$(document).on('click','.remove_quantity',function(){
  $(this).parent('.form-group').parent('.rmbtn').parent('.row').remove();
  updatetotal();
});

$(document).on('click','.plus',function(){
 var maxval = $(this).parent(".qty").children('.count').attr("max");
 var x =  $(this).parent(".qty").children('.count').val();
 if(parseInt(x) < parseInt(maxval))
 {
 $(this).parent(".qty").children('.count').val(parseInt($(this).parent(".qty").children('.count').val()) + 1 );
 // dosync('control',this);
 updatetotal();
}
});

$(document).on('click','.minus',function(){
 var minval = $(this).parent(".qty").children('.count').attr("min");
 var x =  $(this).parent(".qty").children('.count').val();
 if(parseInt(x) > parseInt(minval))
 {
    $(this).parent(".qty").children('.count').val(parseInt($(this).parent(".qty").children('.count').val()) - 1 );
    // dosync('control',this);
    updatetotal();
 }
});

$(document).ready(function(e) {
    initdatepicker(true);
    jcropratio = 0;
    jcropresize = true;
});
</script>

@endsection
