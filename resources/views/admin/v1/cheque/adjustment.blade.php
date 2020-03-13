@extends('admin.v1.layout.app', ['title' => 'Cheque Deposit'])

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
    <i class="fa fa-sliders"></i> Cheque Deposit
    <small>Adjust Your Cheque Transaction</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Cheque Deposit</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <form method="post" action="{{ route('cheque.adjustment.register',$info->id) }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                    <label for="entry_type">Entry Type</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <select id="entry_type" name="entry_type" class="form-control select2">
                      <?php
                      if($info->amount >= 0) {
                      ?>
                      <option value="cheque4">Deposit to Bank</option>
                      <option value="cheque3">Deposit to Cash</option>
                      <?php } else { ?>
                      <option value="cheque11">Withdraw to Bank</option>
                      <option value="cheque10">Withdraw to Cash</option>
                      <?php } ?>
                    </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="adjustment_date">Adjustment Date</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="adjustment_date" name="adjustment_date" value="" class="form-control datepicker">
                </div>
              </div>
              </div>
          </div>

          <div class="row banklist">
            <div class="col-md-12">
              <div class="form-group">
                    <label for="bank_account">Bank Account</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <select style="width:100%;" id="bank_account" name="bank_account" class="form-control select2-bank">
                      <?php
                      foreach($bank_list as $row) {
                        ?>
                        <option data-img="{{ $row->bankicon }}" value="{{ $row->id }}">{{ $row->name }} - {{ $row->account_no }} - {{ $row->type == 2 ? "Current" : "Saving" }}</option>
                        <?php
                      }
                      ?>
                    </select>
                    </div>
                </div>
            </div>
          </div>


          <div class="row">
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
<a href="{{ route('cashinhand') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<script type="text/javascript">
  initdatepicker(true);
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
		toastr.error("Please, Select Adjustment Date");
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


$(document).on('change','#entry_type',function(e) {
  if($(this).val() == "cheque4" || $(this).val() == "cheque11") {
    $(".banklist").show();
  } else {
    $(".banklist").hide();
  }
})
</script>

@endsection
