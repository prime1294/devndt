@extends('admin.v1.layout.app', ['title' => 'Cash Adjustment'])

@section('content')
<style type="text/css">
.select2-container .select2-selection--single .select2-selection__rendered {
  padding-left: 0px !important;
}
.banktransferinput {
  display: none;
}
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-sliders"></i> Cash Adjustment
    <small>Adjust Your Cash</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Cash Adjustment</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <form method="post" action="{{ route('cash.adjustment.update',$info->id) }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                    <label for="entry_type">Entry Type <?= COMPICON ?></label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                    <select id="entry_type" name="entry_type" class="form-control select2">
                      <option value="cash3" {{ $info->type == "cash3" ? "selected" : ""}}>{{ config('transection.cash3')['type'] }}</option>
                      <option value="cash4" {{ $info->type == "cash4" ? "selected" : ""}}>{{ config('transection.cash4')['type'] }}</option>
                      <option value="cash12" {{ $info->type == "cash12" ? "selected" : ""}}>{{ config('transection.cash12')['type'] }}</option>
                    </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="bank_amount">Amount <?= COMPICON ?></label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                  <input type="text" id="bank_amount" name="bank_amount" value="{{ abs($info->amount) }}" class="form-control onlyint">
                </div>
            </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="adjustment_date">Adjustment Date <?= COMPICON ?></label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="adjustment_date" name="adjustment_date" value="{{ date('d-m-Y',strtotime($info->transection_date)) }}" class="form-control datepicker">
                </div>
              </div>
              </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea id="remarks" name="remarks" class="form-control" rows="2">{{ $info->remarks }}</textarea>
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
<a href="{{ route('cashinhand') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<script type="text/javascript">
function val_add_party()
{
  if($("#entry_type").val() == "")
	{
		toastr.error("Please, Select Entry Type");
		$("#entry_type").focus();
		return false;
	}
  if($("#bank_amount").val() == "")
	{
		toastr.error("Please, Enter Amount");
		$("#bank_amount").focus();
		return false;
	}
  if($("#adjustment_date").val() == "")
	{
		toastr.error("Please, Select Adjustment Date");
		$("#adjustment_date").focus();
		return false;
	}
}
</script>

@endsection
