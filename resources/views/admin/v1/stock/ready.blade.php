@extends('admin.v1.layout.app', ['title' => 'Ready Stock'])

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
.lesspadding {
    padding-right: 3px;
    padding-left: 3px;
}
    .r1 {
        width: 12%;
    }
    .r2 {
        width: 15%;
    }
    .r3 {
        width: 17%;
    }
    .r4 {
        width: 12%;
    }
    .r5 {
        width: 12%;
    }
    .r6 {
        width: 12%;
    }
    .r7 {
        width: 14%;
    }
    .r8 {
        width: 5%;
    }
.amountno {
    background-color:white;
    padding: 8px;
    border-radius: 10px;
    font-size: 20px;
    font-weight: 900;
    width: auto;
    min-width: 70px;
    text-align: center;
    padding: 5px 36px;
    margin-left: 10px;
}
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-cubes"></i> Ready Stock
    <span class="amountno"><span class="text-bold text-success"><i class="fa fa-inr"></i> <span class="display_amount">{{ $total_stock }}</span></span></span>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Ready Stock</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <form method="post" action="{{ route('ready.stock.register') }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
            <div class="table-responsive">
            <table class="table table-hover table-striped fixtablemobile">
                <thead>
                <tr class="bg-primary">
                    <th>Date</th>
                    <th>Stock No</th>
                    <th>Design</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Rate</th>
                    <th>Amount</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="append_new_row">
                <?php $current_row = 1 ?>
                @if($settlement_list->isNotEmpty())
                    @foreach($settlement_list as $rr)
                        <tr class="tblrow{{ $current_row }}" data-id="{{ $current_row++ }}">
                            <td class="r1">
                                <div class="form-group">
                                    <input type="text" id="adjustment_date" name="adjustment_date[]" value="{{ date('d-m-Y',strtotime($rr->date)) }}" placeholder="Date" class="form-control datepicker">
                                </div>
                            </td>
                            <td class="r2">
                                <div class="form-group">
                                    <select style="width: 100%;" id="stock_no" name="stock_no[]" class="form-control stock_no select2">
                                        <option value="">Select Stock</option>
                                        @foreach($stock_list as $row)
                                            <option value="{{ $row->id }}" data-pending="{{ $row->pending }}" data-unit="{{ $row->unit }}" {{ $rr->stock_no == $row->id ? "selected" : "" }}>{{ Admin::FormateStockItemID($row->id) }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td class="r3">
                                <div class="form-group">
                                    <input type="text" id="design_name" name="design_name[]" placeholder="Design Name" value="{{ $rr->design_name }}" class="designname form-control">
                                </div>
                            </td>
                            <td class="r4">
                                <div class="form-group">
                                    <input type="text" id="measurement" name="measurement[]" placeholder="Quantity" value="{{ $rr->qty }}" class="imeasurement checkcalculation form-control onlyint">
                                </div>
                            </td>
                            <td class="r5">
                                <div class="form-group">
                                    <select id="mesurement" style="width:100%;" name="mesurement[]" class="form-control mesurement select2">
                                        @foreach($category_list as $row)
                                            <option value="{{ $row->id }}" {{ $row->id == $rr->unit ? "selected" : "" }}>{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td class="r6">
                                <div class="form-group">
                                    <input type="text" id="rate" name="rate[]" placeholder="Rate" value="{{ $rr->rate }}" class="rate form-control checkcalculation onlyint">
                                </div>
                            </td>
                            <td class="r7">
                                <div class="form-group">
                                    <input type="text" id="amount" name="amount[]" placeholder="Amount" value="{{ $rr->amount }}" readonly class="amount form-control onlyint">
                                </div>
                            </td>
                            <td class="r8">
                                <div class="form-group">
                                    <button type="button" class="btn btn-danger remove_quantity btn-xs"><i class="fa fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                <tr class="tblrow{{ $current_row }}" data-id="{{ $current_row++ }}">
                    <td class="r1">
                        <div class="form-group">
                            <input type="text" id="adjustment_date" name="adjustment_date[]" placeholder="Date" class="form-control datepicker">
                        </div>
                    </td>
                    <td class="r2">
                        <div class="form-group">
                            <select style="width: 100%;" id="stock_no" name="stock_no[]" class="form-control stock_no select2">
                                <option value="">Select Stock</option>
                                @foreach($stock_list as $row)
                                    <option value="{{ $row->id }}" data-pending="{{ $row->pending }}" data-unit="{{ $row->unit }}">{{ Admin::FormateStockItemID($row->id) }} </option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td class="r3">
                        <div class="form-group">
                            <input type="text" id="design_name" name="design_name[]" placeholder="Design Name" class="designname form-control">
                        </div>
                    </td>
                    <td class="r4">
                        <div class="form-group">
                            <input type="text" id="measurement" name="measurement[]" placeholder="Quantity" class="imeasurement checkcalculation form-control onlyint">
                        </div>
                    </td>
                    <td class="r5">
                        <div class="form-group">
                            <select id="mesurement" style="width:100%;" name="mesurement[]" class="form-control mesurement select2">
                                @foreach($category_list as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td class="r6">
                        <div class="form-group">
                            <input type="text" id="rate" name="rate[]" placeholder="Rate" class="rate form-control checkcalculation onlyint">
                        </div>
                    </td>
                    <td class="r7">
                        <div class="form-group">
                            <input type="text" id="amount" name="amount[]" placeholder="Amount" readonly class="amount form-control onlyint">
                        </div>
                    </td>
                    <td class="r8">
                        <div class="form-group">
                            <button type="button" class="btn btn-danger remove_quantity btn-xs"><i class="fa fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                @endif
                </tbody>
            </table>
            </div>


          <div class="form-devider"></div>
          <div class="form-group">
          <button type="submit" class="btn btn-primary" onclick="return val_add_party();"><i class="fa fa-upload"></i> Save</button>
              <button type="button" onclick="add_quantity()" class="btn btn-info"><i class="fa fa-plus"></i> Add New</button>
          </div>
          </form>
      </div>
      <!-- /.box-body -->
    </div>
    </div>
  </div>
</section>

<div class="buy-now">
<a href="{{ route('user.dashboard') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>



<script type="text/javascript">
    var current_row = {{ $current_row }};
function add_quantity() {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    var html = '';
    html += '<tr class="tblrow'+current_row+'" data-id="'+current_row+'">';
    html += '<td class="r1">';
    html += '<div class="form-group">';
    html += '<input type="text" id="adjustment_date" name="adjustment_date[]" placeholder="Date" value="'+dd+'-'+mm+'-'+yyyy+'"  class="form-control datepicker">';
    html += '</div>';
    html += '</td>';
    html += '<td class="r2">';
    html += '<div class="form-group">';
    html += '<select style="width: 100%;" id="stock_no" name="stock_no[]" class="form-control stock_no select2">';
    html += '<option value="">Select Stock</option>';
    html += '@foreach($stock_list as $row)';
    html += '<option value="{{ $row->id }}" data-pending="{{ $row->pending }}" data-unit="{{ $row->unit }}">{{ Admin::FormateStockItemID($row->id) }} </option>';
    html += '@endforeach';
    html += '</select>';
    html += '</div>';
    html += '</td>';
    html += '<td class="r3">';
    html += '<div class="form-group">';
    html += '<input type="text" id="design_name" name="design_name[]" placeholder="Design Name" class="designname form-control">';
    html += '</div>';
    html += '</td>';
    html += '<td class="r4">';
    html += '<div class="form-group">';
    html += '<input type="text" id="measurement" name="measurement[]" placeholder="Quantity" class="imeasurement checkcalculation form-control onlyint">';
    html += '</div>';
    html += '</td>';
    html += '<td class="r5">';
    html += '<div class="form-group">';
    html += '<select id="mesurement" style="width:100%;" name="mesurement[]" class="form-control mesurement select2">';
    html += '@foreach($category_list as $row)';
    html += '<option value="{{ $row->id }}">{{ $row->name }}</option>';
    html += '@endforeach';
    html += '</select>';
    html += '</div>';
    html += '</td>';
    html += '<td class="r6">';
    html += '<div class="form-group">';
    html += '<input type="text" id="rate" name="rate[]" placeholder="Rate" class="rate form-control checkcalculation onlyint">';
    html += '</div>';
    html += '</td>';
    html += '<td class="r7">';
    html += '<div class="form-group">';
    html += '<input type="text" id="amount" name="amount[]" placeholder="Amount" readonly class="amount form-control onlyint">';
    html += '</div>';
    html += '</td>';
    html += '<td class="r8">';
    html += '<div class="form-group">';
    html += '<button type="button" class="btn btn-danger remove_quantity btn-xs"><i class="fa fa-trash"></i></button>';
    html += '</div>';
    html += '</td>';
    html += '</tr>';
    $(".append_new_row").append(html);
    $(".stock_no").select2();
    $(".mesurement").select2();
    current_row++;
}

function updatecalculation(rowid) {
    var tabid = $(".tblrow"+rowid);
    var quantity = tabid.children('.r4').children('.form-group').children('.imeasurement').val();
    var unit = tabid.children('.r6').children('.form-group').children('.rate').val();
    total = 0;
    if(quantity != "" && unit != "") {
        total = quantity * unit;
    }
    tabid.children('.r7').children('.form-group').children('.amount').val(total.toFixed(2));
    updategrandtotal();
}

function updategrandtotal() {
    var grandtotal = 0;
    $(".amount").each(function(e){
        grandtotal += parseFloat($(this).val());
    });
    if(grandtotal) {
        $(".display_amount").html(grandtotal.toFixed(2));
    } else {
        $(".display_amount").html(0);
    }
}

$(document).on("keyup",".checkcalculation",function(e){
    var tblid = $(this).closest('tr').attr('data-id');
    updatecalculation(tblid);
});

$(document).on("change",".stock_no",function(e) {
    var element = $(this).find(":selected");
    $(this).closest('tr').children('.r4').children('.form-group').children('.imeasurement').val(element.data("pending"));
    $(this).closest('tr').children('.r5').children('.form-group').children('.mesurement').val(element.data("unit")).trigger('change');
    var tblid = $(this).closest('tr').attr('data-id');
    updatecalculation(tblid);
});

$(document).on('click','.remove_quantity',function(){
    $(this).closest('tr').remove();
    if($(".imeasurement").length == 0) {
        add_quantity();
    }
    updategrandtotal();
});

    @if($settlement_list->isEmpty())
        initdatepicker(true);
    @endif
</script>

@endsection
