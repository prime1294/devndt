@extends('admin.v1.layout.app', ['title' => 'Stock Settlement'])

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
    <i class="fa fa-cubes"></i> Stock Settlement
    <small>Stock Settlement</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Stock Settlement</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <form method="post" action="{{ route('settlement.register',$party_info->id) }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
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
                <select id="challan_no" name="challan_no" class="form-control select2-bank" onchange="grabchallanstock()" data-placeholder="Select Challan">
                  <option value="">Select Challan</option>
                  @foreach($stock_item as $row)
                  <option data-img="{{ $row->challan_photo }}" data-unique-id="{{ $row->stock_unique_id }}" value="{{ $row->id }}">{{ ucwords($row->challan_no) }} - {{ Admin::FormateDate($row->as_of) }}</option>
                  @endforeach
                </select>
                </div>
              </div>
              </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="settlement_type">Settlement Type</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <select id="settlement_type" name="settlement_type" class="form-control select2" data-placeholder="Select Settlement Type">
                  <option value="">Select Settlement Type</option>
                  <option value="1">Settlement Stock</option>
                  <option value="1">Return Stock</option>
                </select>
                </div>
              </div>
              </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                    <label for="transport">Transport Name</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <select id="transport" name="transport" class="form-control select2-bank" data-placeholder="Select User">
                      <option value="">Select Transport</option>
                      <option data-img="{{ 'plus.png' }}" value="0">Add New</option>
                      @foreach($transport_list as $row)
                      <option data-img="{{ $row->photo }}" value="{{ $row->id }}">{{ ucwords($row->name) }} - {{ $row->business_name }}</option>
                      @endforeach
                    </select>
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
              <h3 class="page-header">Manage Stock</h3>
            </div>
            <div class="col-md-12">
                <div class="table-reponsive">
                  <table class="table table-hover table-striped table-bordered dtable fixtablemobile">
                    <thead>
                      <tr>
                        <th>Product</th>
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

      <div class="modal-body">
        <input type="hidden" id="edit_panding_qunatity" value="0">
        <input type="hidden" id="edit_filled_qunatity" value="0">
          <div class="row">
            <div class="col-md-12">
              <button type="button" onclick="add_quantity()" class="btn btn-primary btn-xs mb-10"><i class="fa fa-plus"></i> Add New Quantity</button>
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
                <span class="totalquantityctn totalquantityctndiv">Total:</span>
              </div>
            </div>
            <div class="col-md-6">
            </div>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary modalsubmitbtn" onclick="return checkvalidation()"><i class="fa fa-upload"></i> Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
      </div>
    </form>
    </div>

  </div>
</div>

<script type="text/javascript">
var placeholder = "Meter";
var producttbl;
var editableid = 0;
function grabchallanstock() {
  var challan_id = $('#challan_no option:selected').attr('data-unique-id');
  producttbl = $('.dtable').DataTable({
    "dataSrc": "Data",
    "processing" : true,
    "searching": false,
    "ordering": false,
    "paging": false,
    "serverSide" : true,
    "ajax" : "{{ route('stock.return.ajax') }}?stock_unique_id="+challan_id,
    "bAutoWidth": false,
    "columns" : [
      {"data":"stock_info","sWidth": "30%"},
      {"data":"category_name","sWidth": "10%"},
      {"data":"stock_quantity_html","sWidth": "25%"},
      // {"data":"mesurement_name","sWidth": "15%"},
      {"data":"action","sWidth": "35%"},
    ],
    "fnDrawCallback": function() {
            // $('.status_checkbox').bootstrapToggle();
            // $('.make-switch').bootstrapSwitch();
        },
  });
}

function checkvalidation() {
  if(parseInt($("#edit_filled_qunatity").val()) > parseInt($("#edit_panding_qunatity").val())) {
    toastr.error("Quantity Should be less then "+$("#edit_panding_qunatity").val());
    return false;
  } else {
    toastr.success("Okay Accepted");
    return false;
  }
}


$(document).on("click",".editstockproductbtn",function(e) {
  $("#edit_panding_qunatity").val($(this).attr('data-panding'));
  $("#edit_filled_qunatity").val(0);
});



$(document).ready(function() {

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


function updatetotal() {
  var total = [];
  var iquantity = $( ".iquantity" ).first().val();
  var imeasurement = $(".imeasurement").first().val();
  if(iquantity && imeasurement) {

    var iquantity_arr = [];
    $('.iquantity').each(function(){
        iquantity_arr.push(parseInt($(this).val()));
    });

    var imeasurement_arr = [];
    $('.imeasurement').each(function(){
        imeasurement_arr.push(parseInt($(this).val()));
    });

    var total_imeasurement = 0;
    var total_iquantity = 0;
    for (var i = 0; i < imeasurement_arr.length; i++) {
        if(Number(imeasurement_arr[i]) > 0 && Number(iquantity_arr[i]) > 0) {
        total_imeasurement = imeasurement_arr[i] << 0;
        total_iquantity = iquantity_arr[i] << 0;
        total.push(parseInt(total_imeasurement) * parseInt(total_iquantity));
        }
    }

    $("#edit_filled_qunatity").val($.sum(total));

    $(".totalquantityctndiv").text("Total: "+ $.sum(total)+" "+placeholder);
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
$(document).on('keyup','.imeasurement',function(){
  updatetotal();
});
$(document).on('click','.remove_quantity',function(){
  $(this).parent('.form-group').parent('.rmbtn').parent('.row').remove();
  updatetotal();
});
</script>

@endsection
