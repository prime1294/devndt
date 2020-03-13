@extends('admin.v1.layout.app', ['title' => 'Users'])

@section('content')


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-user"></i> Users
    <?php if($type == "edit") {
      ?><small>Edit User</small><?php
    } else {
      ?><small>Add New User</small><?php
    } ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <?php if($type == "edit") {
      ?><li class="active">Edit User</li><?php
    } else {
      ?><li class="active">Add New User</li><?php
    } ?>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <?php if($type == "edit") {  ?>
        <form method="post" action="{{ route('user.update',[$pinfo->id]) }}" enctype="multipart/form-data">
        <?php } else { ?>
        <form method="post" action="{{ route('user.register') }}" enctype="multipart/form-data">
        <?php } ?>
          {!! csrf_field() !!}
            <div class="row">
              <div class="col-md-4">
                <!-- <center> -->
                  <div class="fbpickermain">
                      <div class=fbpiker>
                          <span class="fbremove"><i class="fa fa-times"></i></span>
                          <img id="fbholdernew" data-default="{{ @$pinfo->image ? asset($pinfo->image) : asset('user_404.jpg') }}" src="{{ @$pinfo->image ? asset($pinfo->image) : asset('user_404.jpg') }}"  onclick="triggerfile('fbholdernew','fbinputtxt','image/party/','.jpg,.png,.jpeg','box')">
                      </div>
                      <input id="fbinputtxt" name="fbinputtxt" class='fbinputtxt' value="{{ @$pinfo->image  }}" type="hidden" >
                  </div>
              </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="trial_start">Start Date</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="text" id="trial_start" name="trial_start" class="form-control datepicker" default-date="{{ @$pinfo->trial_start != null ? date('D M d Y H:i:s O',strtotime($pinfo->trial_start)) : date('D M d Y H:i:s O',strtotime("now")) }}" value="{{ @$pinfo->trial_start != null ? date('d-m-Y',strtotime($pinfo->trial_start)) : "" }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="trial_end">End Date</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="text" id="trial_end" name="trial_end" class="form-control datepicker" default-date="{{ @$pinfo->trial_end != null ? date('D M d Y H:i:s O',strtotime($pinfo->trial_end)) : date('D M d Y H:i:s O',strtotime("now")) }}" value="{{ @$pinfo->trial_end != null ? date('d-m-Y',strtotime($pinfo->trial_end)) : "" }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="first_name">Name</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="text" id="first_name" name="first_name" class="form-control" value="{{ @$pinfo->first_name }}">
                        </div>
                    </div>
                </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="business_name">Business Name</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <input type="text" id="business_name" name="business_name" class="form-control" value="{{ @$pinfo->business_name }}">
                      </div>
                  </div>
              </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mobile">Login Mobile No</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="hidden" name="mobilecode" value="91">
                            <input type="text" id="mobile" name="mobile" class="form-control" value="{{ @$pinfo->mobile }}" data-inputmask='"mask": "9999999999"' data-mask>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

              <div class="col-md-4">
                <div class="form-group">
                      <label for="email_id">Email</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <input type="text" id="email_id" name="email_id" class="form-control" value="{{ @$pinfo->email_id }}">
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="mo_number">Mobile No</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <input type="text" id="mo_number" name="mo_number" class="form-control" value="{{ @$pinfo->mo_number }}">
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="alt_number">Alternative Mobile No</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <input type="text" id="alt_number" name="alt_number" class="form-control" value="{{ @$pinfo->alt_number }}">
                      </div>
                  </div>
              </div>
            </div>


            <div class="row">

              <div class="col-md-4">
                <div class="form-group">
                      <label for="gstno">GSTIN NO <a target="_blank" href="https://services.gst.gov.in/services/searchtp">Check GSTIN No</a> </label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <input type="text" id="gstno" maxlength="15" name="gstno" onpaste="verifyGST(this)" onkeyup="this.value = this.value.toUpperCase();" class="form-control" value="{{ @$pinfo->gstno }}">
                      </div>
                  </div>
              </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="pan">Pan No.</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="text" id="pan" name="pan" class="form-control" onkeyup="this.value = this.value.toUpperCase();"  value="{{ @$pinfo->pan }}">
                    </div>
                </div>
            </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="address">Address</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <input type="text" id="address" name="address" class="form-control" value="{{ @$pinfo->address }}">
                      </div>
                  </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                      <label for="state">State</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <select  style="width: 100%;" id="state" name="state" class="form-control select2">
                      <option value="">Select State</option>
                      <?php
                      foreach($states as $row) {
                        ?>
                        <option value="{{ $row->state_id }}" data-gst="{{ $row->gst_code }}" {{ @$pinfo->state == $row->state_id ? "selected" : "" }}>{{ $row->state }}</option>
                        <?php
                      }
                      ?>
                      </select>
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="city">City</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <select  style="width: 100%;" id="city" name="city" class="form-control select2">
                        <option value="">Select City</option>
                      </select>
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="pincode">Pincode</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <input type="text" id="pincode" name="pincode" class="form-control" value="{{ @$pinfo->pincode }}" data-inputmask='"mask": "999999"' data-mask>
                      </div>
                  </div>
              </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="website">Website</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="text" id="website" name="website" class="form-control" value="{{ @$pinfo->website }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="about_business">Description</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="text" id="about_business" name="about_business" class="form-control" value="{{ @$pinfo->about_business }}">
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea id="remarks" name="remarks" class="form-control" rows="2">{{ @$pinfo->remarks }}</textarea>
                    </div>
                </div>
            </div>

          <div class="form-devider"></div>
          <div class="form-group">
          <button type="submit" class="btn btn-primary" onclick="return val_add_party();"><i class="fa {{ $type == 'edit' ? 'fa-upload' : 'fa-plus' }}"></i> {{ $type == 'edit' ? 'Update' : 'Add New' }}</button>
          <!-- <button type="reset" class="btn btn-danger"><i class="fa fa-trash"></i> Clear</button> -->
          </div>
          </form>
      </div>
      <!-- /.box-body -->
    </div>
    </div>
  </div>
</section>

<div class="buy-now">
<a href="{{ route('user.list') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<script type="text/javascript">
function val_add_party()
{
	if($("#trial_start").val() == "")
	{
		toastr.error("Please, Enter Trial Start Date");
		$("#trial_start").focus();
		return false;
	}
    if($("#trial_end").val() == "")
    {
        toastr.error("Please, Enter Trial End Date");
        $("#trial_end").focus();
        return false;
    }
	if($("#first_name").val() == "")
	{
		toastr.error("Please, Enter User Name");
		$("#first_name").focus();
		return false;
	}
  if($("#business_name").val() == "")
	{
		toastr.error("Please, Enter Business Name");
		$("#business_name").focus();
		return false;
	}
  if($("#mobile").val() == "")
	{
		toastr.error("Please, Enter Login Mobile Number");
		$("#mobile").focus();
		return false;
	}
  if(isPhone($("#mobile").val()) === false) {
      toastr.error("Please, Enter Valid Mobile Number");
      $("#mobile").focus();
      return false;
  }
    if($("#state").val() == "")
    {
        toastr.error("Please, Select State");
        $("#state").focus();
        return false;
    }
    if($("#city").val() == "")
    {
        toastr.error("Please, Select City");
        $("#city").focus();
        return false;
    }
}


function changecityhtml(id,val) {
  $.ajax({
    url:'{{ route("city.ajax") }}',
    type:'POST',
    data: "id="+id+"&_token={{ csrf_token() }}",
    success:function(e) {
      // $("#"+to).html(e);
      $("#city").select2('destroy');
      $('#city').find('option').remove().end();
      $("#city").select2({ data: e.cities });
      $('#city').val(val).trigger('change');
    }
  });
}

<?php
if($type == "edit") {
  ?>
  changecityhtml('{{ $pinfo->state }}','{{ $pinfo->city }}');
  <?php
}
?>

$(document).on("change","#state",function(e) {
    changecityhtml($(this).val(),0);
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

$(document).on("change",".confirmAns",function(e){
	if($(this).prop('checked')) {
    $(".bank_container").slideDown();
  } else {
    $(".bank_container").slideUp();
  }
});


$(document).ready(function(e){
$("#opening_type").trigger('change');
});

</script>

@endsection
