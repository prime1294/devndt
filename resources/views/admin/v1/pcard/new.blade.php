@extends('admin.v1.layout.app', ['title' => 'Add Programme Card'])

@section('content')
{!!Html::style('/public/admin/theme1/general/imagepicker/image-picker.css')!!}
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
.center_placeholder:-ms-input-placeholder {
    /* Internet Explorer 10-11 */
    text-align: center;
}
.design-container {
  height: 270px;
  width: 150px;
}
.design-container {
    height: auto;
    width: 150px;
}
.design-container p {
    text-align: center;
    margin-top: 4px;
    margin-bottom: 0px;
}
ul.thumbnails.image_picker_selector {
  overflow-y: scroll;
  height: 75vh;
}
ul.thumbnails.image_picker_selector li .thumbnail {
  background:#eeededb3;
}
ul.thumbnails.image_picker_selector li .thumbnail.selected {
  background:#00a65a;
  color:white;
}

.btngroupedit {
  display: none;
}
.icheckbox_square-blue {
  margin-right: 7px;
}
<?php
if($pre_added_design != 0) {
  ?>
.hidewhensave {
  /* display: none; */
}
  <?php
} else {
  ?>
  .hidewithoutsave {
    display: none;
  }
  <?php
}
?>
.image_picker_selector img {
  object-fit: contain;
  object-position: center;
  height: 160px;
  background:white;
}
ul.thumbnails.image_picker_selector li {
  margin: 0px 12px -10px 0px;
}
ul.thumbnails.image_picker_selector {
  margin-top:15px;
}
  .imagepickeropen {
    height: 250px;
  }
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-th"></i> Programme Card
    <small>Manage Programme Card</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Manage Programme Card</li>

  </ol>
</section>

<section class="content">
  <div class="row hidewhensave">
  <div class="col-md-12">
    <div class="box box-primary">
    <div class="box-body">
      <input type="hidden" id="stock_inputed_till" value="{{ $filled_total_quantity }}">
      <input type="hidden" id="main_stock_id" name="main_stock_id" value="{{ $main_stock_id }}">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="adjustment_date">Date</label>
            <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            <input type="text" id="adjustment_date" name="adjustment_date" value="{{ isset($pc_info['date']) ? date('d-m-Y',strtotime($pc_info['date'])) : "" }}" class="form-control datepicker">
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="txt_design_name">Design Name</label>
            <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
            <input type="text" id="txt_design_name" name="txt_design_name" value="{{ isset($pc_info['dname']) ? $pc_info['dname'] : "" }}" class="form-control">
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="unit">Unit</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <select style="width:100%;" id="unit" class="form-control select2" name="unit">
                <option value="">Select Unit</option>
                @foreach($stock_unit as $row)
                  <option value="{{ $row->id }}" {{ isset($pc_info['unit_id']) ? ($pc_info['unit_id'] == $row->id ? "selected" : "") : (isset($stock_item_info) ? ($stock_item_info->unit == $row->id ? "selected" : "") : "") }}>{{ $row->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</div>

<div class="row hidewithoutsave">
  <div class="col-md-12">
    <div class="box box-primary">
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-hover table-striped table-bordered dtable fixtablemobile">
                <thead>
                  <tr>
                    <th>Design</th>
                    <th>Qty</th>
                    <th>Stitch</th>
                    <th>Area</th>
                    <th>Category</th>
                    <th>Head</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</div>


<form method="post" id="quantitypostform">
  {!! csrf_field() !!}
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
          <div class="row">
            <div class="col-md-3">
              <img class="img-responsive imagepickeropen img-rounded" src="{{ asset('placeholder.jpg') }}">
            </div>
            <div class="col-md-9">
              <div class="row">
                <input type="hidden" id="design_id" name="design_id" value="0">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="design_code">Design Code</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" id="design_code" readonly name="design_code"  value="" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="stitch">Stitch</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" id="stitch" readonly name="stitch" value="" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="design_area">Area</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" id="design_area" readonly name="design_area" value="" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="category">Category</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" id="category" name="category" value="" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="working_type">Head</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" id="working_type" name="working_type" value="" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="pendrive_design_number">Pendrive Design Number</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" id="pendrive_design_number" name="pendrive_design_number" value="" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <button type="button" onclick="add_quantity()" class="btn btn-primary pull-right no-margin btn-xs"><i class="fa fa-plus"></i> Add New Quantity</button>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-sm">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped fixtablemobile">
                  <thead>
                    <tr class="text-center bg-blue">
                      <td>Color</td>
                      <td>Quantity</td>
                      @for($x=1;$x<=6;$x++)
                      <td>{{ $x }}</td>
                      @endfor
                      <td>Action</td>
                    </tr>
                  </thead>
                  <tbody class="qunatity_holder">
                    <tr class="colorlinerow">
                      <td>
                        <div class="form-group">
                          <input type="text" name="color[]"  value="" class="iamcolor form-control text-center">
                        </div>
                      </td>

                      <td>
                        <div class="form-group">
                          <input type="text" name="qty[]" value="" class="iamqty form-control onlyint text-center">
                        </div>
                      </td>

                      @for($x=1;$x<=6;$x++)
                      <td>
                        <div class="form-group">
                          <input type="text" name="color{{ $x }}[]" value="" class="iamnumber{{ $x }} form-control text-center">
                        </div>
                      </td>
                      @endfor

                      <td>
                        <button type="button" class="btn btn-danger btn-xs remove_quantity"><i class="fa fa-trash"></i> Delete</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

          </div>

          <div class="row mb-10">
            <div class="col-md-12 btngroupadd">
              <button type="button" class="btn btn-primary" onclick="saveandfinish()"><i class="fa fa-upload"></i> Save</button>
              <button type="button" class="btn btn-primary" onclick="saveandnew()"><i class="fa fa-plus-square"></i> Save and New</button>
            </div>
            <div class="col-md-12 btngroupedit">
              <button type="button" class="btn btn-primary" onclick="editonsave()"><i class="fa fa-upload"></i> Update</button>
              <button type="button" class="btn btn-danger" onclick="resetquantityform()"><i class="fa fa-times"></i> Cancel</button>
            </div>
          </div>



      </div>
      <!-- /.box-body -->
    </div>
    </div>
  </div>
  </form>
</section>

<div class="buy-now">
<a href="{{ route('programme.card') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<!-- Modal -->
<div id="designPicker" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
              <select class="imagepicker form-control select2" style="width: 100%;" onchange="validateselection(this)" data-placeholder="Select Design">
                <option value="">Select Design</option>
                @foreach($desgin_list as $row)
                <option data-img-src="{{ asset($row->image) }}" designcode="{{ $row->name }}" stitch="{{ $row->stitch }}" areacode="{{ $row->area }}" data-img-class="img-responsive design-container" data-img-alt="{{ $row->name }}" value="{{ $row->id }}"> {{ $row->name }}  </option>
                @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-upload"></i> Apply Design</button>
        <button type="button" class="btn btn-danger resetpickerbtn disabled" onclick="resetimagepickerselection()"><i class="fa fa-times"></i> Reset Selection</button>
      </div>
    </div>

  </div>
</div>

{!!Html::script('/public/admin/theme1/general/imagepicker/image-picker.min.js')!!}
<script type="text/javascript">
var placeholder = "Unit";
var producttbl;
var editableid = 0;
var saveandfinishid = 0;
var checkboxstatus = '{{ isset($pc_info['avrage_quantity']) ? $pc_info['reduce_qunatity'] : "1" }}';
function assignvaluetoedit(e) {
  resetquantityform();
  // console.log(e);
  editableid = e.id;
  $(".btngroupadd").hide();
  $(".btngroupedit").show();
  $("#design_id").val(e.design_id);
  $(".imagepicker").val(e.design_id).trigger('change');
  $('.imagepickeropen').attr('src','<?php echo url('public'); ?>/'+e.design.image);
  $('#design_code').val(e.design.name);
  $('#stitch').val(e.design.stitch);
  $('#design_area').val(e.design.area);
  $('#category').val(e.category);
  $('#working_type').val(e.working_type);
  $('#pendrive_design_number').val(e.pendrive_design_number);
  $( ".iamcolor" ).first().val(e.quantity[0].color);
  $(".iamqty").first().val(e.quantity[0].quantity);
  $(".iamnumber1").val(e.quantity[0].n1 ? e.quantity[0].n1 : "");
  $(".iamnumber2").val(e.quantity[0].n2 ? e.quantity[0].n2 : "");
  $(".iamnumber3").val(e.quantity[0].n3 ? e.quantity[0].n3 : "");
  $(".iamnumber4").val(e.quantity[0].n4 ? e.quantity[0].n4 : "");
  $(".iamnumber5").val(e.quantity[0].n5 ? e.quantity[0].n5 : "");
  $(".iamnumber6").val(e.quantity[0].n6 ? e.quantity[0].n6 : "");

  $.each(e.quantity, function(i, item) {
    if(i != 0) {
      var icolor = item.color ? item.color : "";
      var iqty = item.quantity ? item.quantity : "";
      var in1 = item.n1 ? item.n1 : "";
      var in2 = item.n2 ? item.n2 : "";
      var in3 = item.n3 ? item.n3 : "";
      var in4 = item.n4 ? item.n4 : "";
      var in5 = item.n5 ? item.n5 : "";
      var in6 = item.n6 ? item.n6 : "";
      add_quantity(icolor,iqty,in1,in2,in3,in4,in5,in6);
    }
  });
  //
  // $(".modalsubmitbtn").attr("onclick","return checkquantityval('update')");
  // updatetotal();
  // $("#addmyproduct").modal('show');
}


$('input[type="checkbox"]').on('ifChanged', function (e) {
     // alert($(this).prop(":checked"));
     if(!$(".icheckbox_square-blue").hasClass('checked')) {
       checkboxstatus = 1;
     } else {
       checkboxstatus = 0;
     }
});

$(document).on("click",".editstockproductbtn",function(e) {
  editableid = $(this).attr('data-id');
  var url = '{{ route('pc.item.info',":ID") }}';
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

function removeproduct(id) {
  checkverification();
  if($("#stock_inputed_till").val() == $("#quantity").val()) {
    toastr.error("Programme Card must have atleast 1 Colorline");
    return false;
  }

  var conf = confirm('Are you sure want to delete this record?');
  if(conf === false) {
    return false;
  }

  var route = '{{ route('pc.design.remove',":ID") }}';
  route = route.replace(':ID', id);
  $.ajax({
    url:route,
    type:'POST',
    async:true,
    data: "_token={{ csrf_token() }}",
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        toastr.success("Design Removed Successfully");
        checkverification();
      } else {
        toastr.error(e.message);
      }
      producttbl.draw(true);
    }
  });
}

$(document).ready(function() {
  producttbl = $('.dtable').DataTable({
    "dataSrc": "Data",
    "processing" : true,
    "searching": false,
    "ordering": false,
    "paging": false,
    "bInfo" : false,
    "serverSide" : true,
    "ajax" : "{{ route('pc.design.ajax') }}?pc_unique_id={{ $unique_pc_id }}",
    "bAutoWidth": false,
    "columns" : [
      {"data":"desing_info","sWidth": "20%"},
      {"data":"desing_total","sWidth": "10%"},
      {"data":"stitch","sWidth": "10%"},
      {"data":"area","sWidth": "10%"},
      {"data":"category","sWidth": "15%"},
      {"data":"working_type","sWidth": "10%"},
      {"data":"action","sWidth": "25%"},
    ],
    "fnDrawCallback": function() {
            // $('.status_checkbox').bootstrapToggle();
            // $('.make-switch').bootstrapSwitch();
        },
  });
});

function editPCDesign() {
  var route = '{{ route('pc.quantity.update',":ID") }}';
  route = route.replace(':ID', editableid);
  $.ajax({
    url: route,
    type:'POST',
    data:$("#quantitypostform").serialize(),
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        toastr.success("Design Line updated Successfully");
        resetquantityform();
        checkverification();
        producttbl.draw(true);
      } else {
        toastr.error(e.message);
      }
    }
  });
}

function registerPcDesign() {
  $.ajax({
    url:'{{ route('register.pc.design') }}',
    type:'POST',
    data:$("#quantitypostform").serialize(),
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        toastr.success("Design Line register Successfully");
        resetquantityform();
        checkverification();
        producttbl.draw(true);
        if(saveandfinishid == 1) {
          window.location.href = '{{ route('programme.card') }}';
        }
      } else {
        toastr.error(e.message);
      }
    }
  });


}
function registerpc() {
  var date = $("#adjustment_date").val();
  var quantity = $("#quantity").val();
  var unit = $("#unit").val();
  var main_stock_id = $("#main_stock_id").val();
  var average = $("#average").val();
  var txt_design_name = $("#txt_design_name").val();
  $.ajax({
    url:'{{ route('register.programme.card') }}',
    type:'POST',
    async: false,
    data:'_token={{ csrf_token() }}&date='+date+"&quantity="+quantity+"&unit="+unit+"&main_stock_id="+main_stock_id+"&stock_less="+checkboxstatus+"&average="+average+"&txt_design_name="+txt_design_name,
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        checkboxstatus = 0;
        //$(".hidewhensave").slideUp();
        $(".hidewithoutsave").slideDown();
      }
    }
  });
}
function checkpcdesignvalidation(type = "add") {
  if($("#design_id").val() == 0) {
    toastr.error("Please Select any design");
    $("#design_id").focus();
    return false;
  }
  if($("#design_code").val() == "") {
    toastr.error("Please Enter Design Code");
    $("#design_code").focus();
    return false;
  }
  if($("#stitch").val() == "") {
    toastr.error("Please Enter number of stitch");
    $("#stitch").focus();
    return false;
  }
  if($(".iamqty").first().val() == "") {
    toastr.error("Please Enter Qunatity");
    $(".iamqty").first().focus();
    return false;
  }
  // for(var i=1;i<=6;i++) {
  var i = 1;
    if($(".iamnumber"+i).first().val() == "") {
      toastr.error("Please Enter Details");
      $(".iamnumber"+i).first().focus();
      return false;
    }
  // }

  if(type == "edit") {
    editPCDesign();
  } else {
    registerPcDesign();
  }
}
function checkvalidation(type = "add") {
  if($("#adjustment_date").val() == "") {
    toastr.error("Please Select Date");
    $("#adjustment_date").focus();
    return false;
  }

  if($("#quantity").val() == "") {
    toastr.error("Please Enter Quantity");
    $("#quantity").focus();
    return false;
  }

  // if((parseInt($("#stock_inputed_till").val()) <= parseInt($("#quantity").val())) === false) {
  //   toastr.error("Stock already assigned..!<br>Enter quantity Greater then "+$("#stock_inputed_till").val());
  //   $("#quantity").focus();
  //   return false;
  // }

  if($("#unit").val() == "") {
    toastr.error("Please Select Unit");
    $("#unit").focus();
    return false;
  }

  if(type == "add") {
    registerpc();
  }

  //changes at 12-feb-2020
  if($("#design_id").val() == 0 && saveandfinishid == 1) {
      window.location.href = '{{ route('programme.card') }}';
  } else {
    checkpcdesignvalidation(type);
  }

}

function saveandfinish() {
  saveandfinishid = 1;
  checkvalidation();
}

function saveandnew() {
  checkvalidation();
}

function editonsave() {
  checkvalidation('edit');
}

function validateselection(e) {
  if($(e).val() == "") {
    $(".resetpickerbtn").addClass("disabled");
  } else {
    $(".resetpickerbtn").removeClass("disabled");
  }
}

function checkverification() {
  $("#quantity").attr('readonly','readonly');
  $.ajax({
    url:'{{ route('pc.quantity.total') }}',
    type:'GET',
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        $("#stock_inputed_till").val(e.count);
      }
    }
  });
  return true;
}


function resetimagepickerselection() {
  $(".imagepicker").val("");
  $(".design-container").each(function(e){
    $(this).removeClass("selected");
  });
  $(".resetpickerbtn").addClass("disabled");
  $(".imagepickeropen").attr('src','{{ asset('placeholder.jpg') }}');
  $("#designPicker").modal('hide');
}


function resetquantityform() {
  resetimagepickerselection();
  $(".btngroupadd").show();
  $(".btngroupedit").hide();
  $("#design_id").val(0);
  $("#design_code").val('');
  $("#stitch").val('');
  $("#design_area").val('');
  $("#category").val('');
  $("#working_type").val('');
  $("#pendrive_design_number").val('');

  $( ".iamcolor" ).first().val('');
  $(".iamqty").first().val('');
  for(var i=1;i<=6;i++) {
    $(".iamnumber"+i).first().val('');
  }

  var no = 1;
  $('.remove_quantity').each(function(){
    if(no != 1) {
      $(this).closest('tr').remove();
    }
    no++;
  });
}



$(document).on("click",".addproductbtn",function(e){
resetquantityform();
$(".modalsubmitbtn").attr("onclick","return checkquantityval('add')");
});


$(document).on("click",".imagepickeropen",function(e) {
  $("#designPicker").modal('show');
});


$(document).ready(function() {

  @if(!isset($pc_info['date']))
  initdatepicker(true);
  @endif

    $(".imagepicker").imagepicker({
      show_label: true,
      hide_select: false,
      selected: function(option){
        console.log("selected");
      },
      changed: function(option){
        console.log("changed");
      },
      selected: function(option){
        console.log("selected");
      },
      clicked: function(select, picker, option, event) {
          $(".imagepickeropen").attr('src',picker.target.attributes.src.nodeValue);
          $("#design_code").val(select.option[0].attributes.designcode.nodeValue);
          $("#stitch").val(select.option[0].attributes.stitch.nodeValue);
          $("#design_area").val(select.option[0].attributes.areacode.nodeValue);
          $("#design_id").val($(".imagepicker").val());
      }
    });


  $('select:not(.normal)').each(function () {
    $(this).select2({
      dropdownParent: $(this).parent()
    });
  });

});



function add_quantity(color = "", quantity = "", n1 = "", n2 = "", n3 = "", n4 = "", n5 = "", n6 = "") {
  var html = '';
  html += '<tr class="colorlinerow">';
    html += '<td>';
      html += '<div class="form-group">';
        html += '<input type="text" name="color[]"  value="'+color+'" class="iamcolor form-control text-center">';
      html += '</div>';
    html += '</td>';

    html += '<td>';
      html += '<div class="form-group">';
        html += '<input type="text" name="qty[]" value="'+quantity+'" class="iamqty form-control onlyint text-center">';
      html += '</div>';
    html += '</td>';

    html += '@for($x=1;$x<=6;$x++)';
    html += '<td>';
      html += '<div class="form-group">';
        html += '<input type="text" name="color{{ $x }}[]" value="'+n{{ $x }}+'" class="iamnumber{{ $x }} form-control text-center">';
      html += '</div>';
    html += '</td>';
    html += '@endfor';

    html += '<td>';
      html += '<button type="button" class="btn btn-danger btn-xs remove_quantity"><i class="fa fa-trash"></i> Delete</button>';
    html += '</td>';
  html += '</tr>';
  $(".qunatity_holder").append(html);
  $(".onlyint").ForceNumericOnly();

}


$(document).on('click','.remove_quantity',function(){
  // $(this).parent('.form-group').parent('.rmbtn').parent('.row').remove();
  if($(".iamcolor").length == 1) {
    toastr.error("Color Line need atleast one row.<br>You can not delete this row");
  } else {
    $(this).closest('.colorlinerow').remove();
    updatetotal();
  }
});

$(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });

</script>

@endsection
