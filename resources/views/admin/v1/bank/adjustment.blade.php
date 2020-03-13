@extends('admin.v1.layout.app', ['title' => 'Bank Adjustment'])

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
    <i class="fa fa-sliders"></i> Bank Adjustment
    <small>Adjust Your Bank Account</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Bank Adjustment</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <form method="post" action="{{ route('bank.adjustment.register') }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                    <label for="bank_account">Bank Account <?= COMPICON ?></label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-institution"></i></span>
                    <select id="bank_account" name="bank_account" class="form-control select2-bank">
                      <?php
                      // dd($bank_list);
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
            <div class="col-md-4">
              <div class="form-group">
                    <label for="entry_type">Entry Type <?= COMPICON ?></label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                    <select id="entry_type" name="entry_type" class="form-control select2">
                      <option value="bank4">{{ config('transection.bank4')['type'] }}</option>
                      <option value="bank5">{{ config('transection.bank5')['type'] }}</option>
                      <option value="bank6">{{ config('transection.bank6')['type'] }}</option>
                      <option value="bank7">{{ config('transection.bank7')['type'] }}</option>
                      <?php
                      if(count($bank_list) >= 2) {
                        ?>
                      <option value="bank8">{{ config('transection.bank8')['type'] }}</option>
                        <?php
                      }
                      ?>
                      <option value="bank14">{{ config('transection.bank14')['type'] }}</option>
                    </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label for="bank_amount">Amount <?= COMPICON ?></label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                  <input type="text" id="bank_amount" name="bank_amount" value="" class="form-control onlyint">
                </div>
            </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="adjustment_date">Adjustment Date <?= COMPICON ?></label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="adjustment_date" name="adjustment_date" value="" class="form-control datepicker">
                </div>
              </div>
              </div>
            <div class="col-md-12 banktransferinput">
              <div class="form-group">
                <label for="transfer_to">Transfer To <?= COMPICON ?></label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-institution"></i></span>
                  <select style="width: 100%" id="transfer_to" name="transfer_to" class="form-control select2-bank">
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
<a href="{{ route('bankaccount') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<script type="text/javascript">
function val_add_party()
{
	if($("#bank_account").val() == "")
	{
		toastr.error("Please, Select Bank");
		$("#bank_account").focus();
		return false;
	}
  if($("#entry_type").val() == "")
	{
		toastr.error("Please, Select Entry Type");
		$("#entry_type").focus();
		return false;
	}
  if($("#entry_type").val() == "bank8")
	{
    if($("#transfer_to").val() == "")
  	{
  		toastr.error("Please, Select Transfer Account");
  		$("#transfer_to").focus();
  		return false;
  	}
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


function gettoBankList(id,val) {
  $.ajax({
    url:'{{ route("ajax.excluding.bank") }}',
    type:'POST',
    data: "id="+id+"&_token={{ csrf_token() }}",
    success:function(e) {
      // $("#"+to).html(e);
      $("#transfer_to").select2('destroy');
      $('#transfer_to').find('option').remove().end();
      var html = '';

      $.each(e, function(i, item) {
        var banktype = item.type == 2 ? "Current" : "Saving";
        html += '<option data-img="'+item.bankicon+'" value="'+item.id+'">'+item.name+' - '+item.account_no+' - '+banktype+'</option>';
      });
      $('#transfer_to').html(html);
      select2Bank();
      // $('#transfer_to').val(val).trigger('change');
    }
  });
}


$(document).on("change","#bank_account",function(e) {
    gettoBankList($(this).val(),0);
});

$(document).on("change","#entry_type",function(e) {
  if($(this).val() == "bank8") {
    $(".banktransferinput").show();
  } else {
    $(".banktransferinput").hide();
  }
});

$(document).on("change","#party_type",function(e) {
    if($(this).val() == 1) {
      $(".gstclass").hide();
      $(".addressclass").removeClass('col-md-4').addClass('col-md-8');
    } else {
      $(".gstclass").show();
      $(".addressclass").removeClass('col-md-8').addClass('col-md-4');
    }
});


$(document).ready(function(e) {
  initdatepicker(true);
  <?php
  if(count($bank_list) >= 2) {
  ?>
  gettoBankList("{{ $bank_list[0]->id }}",0);
  <?php
  }
  ?>
})


</script>

@endsection
