@extends('admin.v1.layout.app', ['title' => 'Receive Stock'])

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
    <i class="fa fa-cubes"></i> Receive Stock
    <small>Receive Stock</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Receive Stock</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <form method="post" action="{{ route('pc.receive.register',["id"=>$info->id]) }}" enctype="multipart/form-data">
            <input type="hidden" id="current_stock" name="current_stock" value="{{ $validation_amount }}">
          {!! csrf_field() !!}
            <div class="qunatity_holder mt-sm">
                <div class="row hidemobile" style="margin-bottom: 10px;">
                    <div class="col-md-2">
                        <b>Date</b>
                    </div>
                    <div class="col-md-2">
                        <b>Type</b>
                    </div>
                    <div class="col-md-3">
                        <b>Quantity</b>
                    </div>
                    <div class="col-md-4">
                        <b>Remarks</b>
                    </div>
                    <div class="col-md-1">
                    </div>
                </div>
                @if($settlement_list->isNotEmpty())
                @foreach($settlement_list as $row)
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="text" id="adjustment_date" name="adjustment_date[]" value="{{ date('d-m-Y',strtotime($row->date)) }}" placeholder="Date" class="form-control datepicker">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select id="type" name="type[]" class="form-control">
                                    <option value="1" {{ $row->type == 1 ? "selected" : "" }}>Receive</option>
                                    <option value="2" {{ $row->type == 2 ? "selected" : "" }}>Settlement</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" id="measurement" name="measurement[]" placeholder="Quantity" value="{{ $row->qty }}" class="imeasurement form-control onlyint">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" id="remarks" name="remarks[]" placeholder="Remarks" value="{{ $row->remarks }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                          <button type="button" class="btn btn-danger remove_quantity btn-xs"><i class="fa fa-trash"></i> Delete</button>
                        </div>
                    </div>
                </div>
                    @endforeach
                    @else
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" id="adjustment_date" name="adjustment_date[]" placeholder="Date" class="form-control datepicker">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select id="type" name="type[]" class="form-control">
                                    <option value="1">Receive</option>
                                    <option value="2">Settlement</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" id="measurement" name="measurement[]" placeholder="Quantity" class="imeasurement form-control onlyint">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" id="remarks" name="remarks[]" placeholder="Remarks" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <button type="button" class="btn btn-danger remove_quantity btn-xs"><i class="fa fa-trash"></i> Delete</button>
                            </div>
                        </div>
                    </div>
                    @endif
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
<a href="{{ route('programme.card') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>



<script type="text/javascript">
function add_quantity() {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    var html = '';
    html += '<div class="row">';
    html += '<div class="col-md-2">';
    html += '<div class="form-group">';
    html += '<input type="text" id="adjustment_date" name="adjustment_date[]" placeholder="Date" value="'+dd+'-'+mm+'-'+yyyy+'"  class="form-control datepicker">';
    html += '</div>';
    html += '</div>';
    html += '<div class="col-md-2">';
    html += '<div class="form-group">';
    html += '<select id="type" name="type[]" class="form-control">';
    html += '<option value="1">Receive</option>';
    html += '<option value="2">Settlement</option>';
    html += '</select>';
    html += '</div>';
    html += '</div>';
    html += '<div class="col-md-3">';
    html += '<div class="form-group">';
    html += '<input type="text" id="measurement" name="measurement[]" placeholder="Quantity" class="imeasurement form-control onlyint">';
    html += '</div>';
    html += '</div>';
    html += '<div class="col-md-4">';
    html += '<div class="form-group">';
    html += '<input type="text" id="remarks" name="remarks[]" placeholder="Remarks" class="form-control">';
    html += '</div>';
    html += '</div>';
    html += '<div class="col-md-1">';
    html += '<div class="form-group">';
    html += '<button type="button" class="btn btn-danger remove_quantity btn-xs"><i class="fa fa-trash"></i> Delete</button>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    $(".qunatity_holder").append(html);
    initdatepicker(false);
}
@if($settlement_list->isEmpty())
initdatepicker(true);
@endif
function val_add_party() {

    if($(".imeasurement").length == 0) {
        toastr.error("Please Enter atleast 1 Unit");
        return false;
    }

    var total = 0;
    $('.imeasurement').each(function() {
        if($(this).val() != "") {
            total  += parseInt($(this).val());
        }
    });

    if((total <= $("#current_stock").val()) === false) {
        toastr.error("You can't receive more then quantity");
        return false;
    }

}

$(document).on('click','.remove_quantity',function(){
    $(this).closest('.row').remove();
    if($(".imeasurement").length == 0) {
        add_quantity();
    }
});
</script>

@endsection
