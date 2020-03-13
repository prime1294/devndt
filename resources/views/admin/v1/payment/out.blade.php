@extends('admin.v1.layout.app', ['title' => 'Payment Out'])

@section('content')
<style type="text/css">
    .input-group {
        width: 100%;
    }
.select2-container .select2-selection--single .select2-selection__rendered {
  padding-left: 0px !important;
}
.banktransferinput {
  display: none;
}
.popover {
  max-width: none !important;
  width:100% !important;
  left: 0px !important;
}
.popover-content #dynamic {
  width: auto !important;
  height: 200px !important;
}
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-inr"></i> Payment Out
    <small>Adjust Your Payment</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Payment Out</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <form method="post" action="{{ route('paymentout.register') }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                    <label for="master_type">Master Type <?= COMPICON ?></label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-users"></i></span>
                    <select style="width: 100%;" id="master_type" name="master_type" class="form-control select2">
                      <option value="master1">{{ config('master.master1')['name'] }}</option>
                      <option value="master2">{{ config('master.master2')['name'] }}</option>
                      <option value="master3">{{ config('master.master3')['name'] }}</option>
                      <!--<option value="master4">{{ config('master.master4')['name'] }}</option>-->
                      <option value="master5">{{ config('master.master5')['name'] }}</option>
                      <option value="master6">{{ config('master.master6')['name'] }}</option>
                      <!--<option value="master7">{{ config('master.master7')['name'] }}</option>-->
                      <option value="master8">{{ config('master.master8')['name'] }}</option>
                    </select>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                    <label for="master_user">Name <?= COMPICON ?></label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <select style="width: 100%;" id="master_user" name="master_user" class="form-control select2-bank" data-placeholder="Select User">

                    </select>
                    </div>
                </div>
            </div>
          </div>

          <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                <label for="adjustment_date">Date <?= COMPICON ?></label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="adjustment_date" name="adjustment_date" value="" class="form-control datepicker">
                </div>
              </div>
            </div>
              <div class="col-md-8">
                  <div class="form-group">
                      <label for="bank_amount">Amount <?= COMPICON ?></label>
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                          <input type="text" id="bank_amount" name="bank_amount" value="" class="form-control onlyint">
                      </div>
                  </div>
              </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="ref_no">Reference No</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-keyboard-o"></i></span>
                  <input type="text" id="ref_no" name="ref_no" value="" class="form-control">
                </div>
            </div>

            </div>
              <div class="col-md-8">
                  <div class="form-group">
                      <label for="payment_type">Payment Type <?= COMPICON ?></label>
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                          <select style="width: 100%;" id="payment_type" name="payment_type" class="form-control select2">
                              <option value="cash">Cash</option>
                              <option value="cheque">Cheque</option>
                              @if(count($bank_list) != 0)
                                  <optgroup label="Banks">
                                      @foreach ($bank_list as $row)
                                          <option value="bank_ref_{{ $row->id }}">{{ $row->name }} - {{ $row->bankname }} - {{ $row->account_no }} - {{ $row->type == 2 ? "Current" : "Saving" }}</option>
                                      @endforeach
                                  </optgroup>
                              @endif
                          </select>
                      </div>
                  </div>
              </div>

          </div>

          <div class="row">
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="recipt_no">Recipt No.</label>
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-newspaper-o"></i></span>
                          <input type="text" id="recipt_no" name="recipt_no" value="" class="form-control">
                      </div>
                  </div>

              </div>
            <div class="col-md-8">
              <div class="form-group">
                    <label for="ref_img">Image</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>
                    <!-- file picker -->
                    <!-- image-preview-filename input [CUT FROM HERE]-->
                    <div class="input-group image-preview">
                        <input type="text" class="form-control image-preview-filename" disabled="disabled"> <!-- don't give a name === doesn't send on POST/GET -->
                        <span class="input-group-btn">
                            <!-- image-preview-clear button -->
                            <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                <span class="glyphicon glyphicon-remove"></span> Clear
                            </button>
                            <!-- image-preview-input -->
                            <div class="btn btn-default image-preview-input">
                                <span class="glyphicon glyphicon-folder-open"></span>
                                <span class="image-preview-input-title">Browse</span>
                                <input type="file" id="ref_img" accept="image/png, image/jpeg" name="ref_img"/> <!-- rename it -->
                            </div>
                        </span>
                    </div><!-- /input-group image-preview [TO HERE]-->
                    <!-- end of file picker -->
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


<script type="text/javascript">
var route = "{{ route(config('master.master1')['json']) }}";
function val_add_party()
{
  if($("#master_type").val() == "")
	{
		toastr.error("Please, Select Master Type");
		$("#master_type").focus();
		return false;
	}
  if($("#master_user").val() == "" || $("#master_user").val() == 0)
	{
		toastr.error("Please, Select Master User");
		$("#master_user").focus();
		return false;
	}
  if($("#adjustment_date").val() == "")
	{
		toastr.error("Please, Select Date");
		$("#adjustment_date").focus();
		return false;
	}
  if($("#payment_type").val() == "")
	{
		toastr.error("Please, Select Payment Type");
		$("#payment_type").focus();
		return false;
	}
  if($("#payment_type").val() == "cheque")
	{
    if($("#ref_no").val() == "") {
      toastr.error("Please, Enter Cheque Ref. number");
      $("#ref_no").focus();
      return false;
    }
	}
  if($("#bank_amount").val() == "")
	{
		toastr.error("Please, Enter Amount");
		$("#bank_amount").focus();
		return false;
	}
}


$(document).on("change","#master_user",function(e) {
  var redirect_route = "{{ config('master.master1')['new'] }}";
  if($(this).val() == 0) {
    if($("#master_type").val() == "master2") {
        redirect_route = "{{ config('master.master2')['new'] }}";
    } else if ($("#master_type").val() == "master3") {
        redirect_route = "{{ config('master.master3')['new'] }}";
    } else if ($("#master_type").val() == "master4") {
        redirect_route = "{{ config('master.master4')['new'] }}";
    } else if ($("#master_type").val() == "master5") {
        redirect_route = "{{ config('master.master5')['new'] }}";
    } else if ($("#master_type").val() == "master6") {
        redirect_route = "{{ config('master.master6')['new'] }}";
    } else if ($("#master_type").val() == "master7") {
        redirect_route = "{{ config('master.master7')['new'] }}";
    } else if ($("#master_type").val() == "master8") {
        redirect_route = "{{ config('master.master8')['new'] }}";
    } else {
        redirect_route = "{{ config('master.master1')['new'] }}";
    }

    window.location.href = '{{ route("redirecting") }}?redirectback=paymentin&redirect='+redirect_route;
  }
});

function getUserList(url) {
  $.ajax({
    url: url,
    type:'GET',
    success:function(e) {
      $("#master_user").select2('destroy');
      $('#master_user').find('option').remove().end();
      var html = '<option></option>';
      html += '<option data-img="plus.png" value="0">Add New</option>';
      $.each(e, function(i, item) {
          var business_name = item.business_name != null ? ' - '+item.business_name : "";
        html += '<option data-img="'+item.photo+'" value="'+item.id+'">'+item.name+business_name+'</option>';
      });
      $('#master_user').html(html);
      select2Bank();
      // $('#transfer_to').val(val).trigger('change');
    }
  });
}

$(document).on("change","#master_type",function(e) {
    if($(this).val() == "master2") {
        route = "{{ route(config('master.master2')['json']) }}";
    } else if ($(this).val() == "master3") {
        route = "{{ route(config('master.master3')['json']) }}";
    } else if ($(this).val() == "master4") {
        route = "{{ route(config('master.master4')['json']) }}";
    } else if ($(this).val() == "master5") {
        route = "{{ route(config('master.master5')['json']) }}";
    } else if ($(this).val() == "master6") {
        route = "{{ route(config('master.master6')['json']) }}";
    } else if ($(this).val() == "master7") {
        route = "{{ route(config('master.master7')['json']) }}";
    } else if ($(this).val() == "master8") {
        route = "{{ route(config('master.master8')['json']) }}";
    } else {
        route = "{{ route(config('master.master1')['json']) }}";
    }
    getUserList(route);
});

$(document).ready(function(e) {
    initdatepicker(true);
  getUserList(route);
});
</script>

@endsection
