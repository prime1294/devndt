@extends('admin.v1.layout.app', ['title' => 'Verify Stock Number'])

@section('content')
<style type="text/css">
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-cubes"></i> Verify Stock Number
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Verify Stock Number</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <div class="row">
          <div class="col-md-12 stkdiv">
            <div class="form-group">
              <label for="stock_number">Stock Number</label>
              <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <select id="stock_number" name="stock_number" class="form-control select2-bank" data-placeholder="Stock Number">
                <option value="">Stock Number</option>
                @foreach($stock_item as $row)
                <option data-img="{{ $row->photo }}" value="{{ $row->id }}" {{ $row->pending < 0 ? "disabled" : "" }}>{{ $row->stock_name }} - {{ $row->product_name }} {{ $row->pending < 0 ? "- Out of stock" : "" }}</option>
                @endforeach
              </select>
              </div>
            </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <select style="width:100%;" id="epcard" name="epcard" data-placeholder="Select Programme Card" class="form-control select2">
                  <option value="">Select Programme Card</option>
                  @foreach($pc_item as $row)
                  <option value="{{ $row->id }}">#{{ Admin::FormatePCId($row->id) }}</option>
                  @endforeach
                </select>
                </div>
              </div>
            </div>


        </div>
        <div class="form-devider"></div>
        <div class="form-group">
        <button type="submit" class="btn btn-primary" onclick="return val_stock();"><i class="fa fa-arrow-right"></i> Next Step</button>
        </div>
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
var checkboxstatus = "show";
var duplicatebox = 0;
var avarage = 1;
function makeajaxcall(url,data,redirecturl) {
  $.ajax({
    url:url,
    type:'POST',
    data:data,
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        window.location.href = redirecturl;
      } else {
        toastr.error(e.message);
      }
    }
  })
}

function makeduplicateajaxcall(redirecturl) {
  var url = '{{ route('make.duplicate.pc',":ID") }}';
  url = url.replace(':ID',$("#epcard").val());
  var data = '_token={{ csrf_token() }}';
  if($("#stock_number").val() != "") {
    data += '&stock_id='+$("#stock_number").val();
    makeajaxcall(url,data,redirecturl);
  } else if($("#epcard").val() != "") {
    makeajaxcall(url,data,redirecturl);
  }
}

function val_stock() {
  var url = '{{ route('add.programme.card') }}';

   if($("#stock_number").val() != "") {
   var url = '{{ route('add.programme.card',":ID") }}';
    url = url.replace(':ID',$("#stock_number").val());
   }



    if($("#epcard").val() != "") {
      makeduplicateajaxcall(url);
    } else {
      window.location.href = url;
    }

}



</script>



@endsection
