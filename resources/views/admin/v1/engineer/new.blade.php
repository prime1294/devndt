@extends('admin.v1.layout.app', ['title' => 'Add Engineer'])

@section('content')



<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-user"></i> Engineer
    <?php if($type == "edit") {
      ?><small>Edit Engineer</small><?php
    } else {
      ?><small>Add New Engineer</small><?php
    } ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <?php if($type == "edit") {
      ?><li class="active">Edit Engineer</li><?php
    } else {
      ?><li class="active">Add New Engineer</li><?php
    } ?>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <?php if($type == "edit") {  ?>
        <form method="post" action="{{ route('engineer.update',[$pinfo->id]) }}" enctype="multipart/form-data">
        <?php } else { ?>
        <form method="post" action="{{ route('engineer.register') }}" enctype="multipart/form-data">
        <?php } ?>
          {!! csrf_field() !!}
            <div class="row">
              <div class="col-md-12">
                <!-- <center> -->
                  <div class="fbpickermain">
                    <div class=fbpiker>
                        <span class="fbremove"><i class="fa fa-times"></i></span>
                  	    <img id="fbholder" data-default="{{ @$pinfo->photo ? asset($pinfo->photo) : asset('user_404.jpg') }}" src="{{ @$pinfo->photo ? asset($pinfo->photo) : asset('user_404.jpg') }}" >
                  	</div>
                    <input id="fbinput" name="fbinput" class='fbinput' onchange="readURL(this);" type="file" >
                  </div>
                <!-- </center> -->
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                      <label for="party_name">Types of Engineer</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <select id="party_name" name="party_name" class="form-control select2" >
                        <option value="">Select Engineer Type</option>
                        <option value="Electrical Engineer" {{ @$pinfo->business_name == "Electrical Engineer" ? "selected" : "" }}>Electrical Engineer</option>
                        <option value="Mechanical Engineer" {{ @$pinfo->business_name == "Mechanical Engineer" ? "selected" : "" }}>Mechanical Engineer</option>
                        <option value="Electrical + Mechanical Engineer" {{ @$pinfo->business_name == "Electrical + Mechanical Engineer" ? "selected" : "" }}>Electrical + Mechanical Engineer</option>
                      </select>
                      </div>
                  </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                      <label for="person_name">Engineer Name</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <input type="text" id="person_name" name="person_name" class="form-control" value="{{ @$pinfo->name }}">
                      </div>
                  </div>
              </div>
            </div>

            <div class="row">

              <div class="col-md-4">
                <div class="form-group">
                      <label for="email">Email</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <input type="text" id="email" name="email" class="form-control" value="{{ @$pinfo->email }}">
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="mobile">Mobile No</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <input type="text" id="mobile" name="mobile" class="form-control" value="{{ @$pinfo->mobile }}" data-inputmask='"mask": "9999999999"' data-mask>
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="alt_no">Alternative Mobile No</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <input type="text" id="alt_no" name="alt_no" class="form-control" value="{{ @$pinfo->alt_mobile }}" data-inputmask='"mask": "9999999999"' data-mask>
                      </div>
                  </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="opening_balance">Opening Balance</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="text" id="opening_balance" name="opening_balance" value="{{ @$pinfo->opening_balance }}" class="form-control onlyint">
                  </div>
              </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="opening_type">Balance Type</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <select id="opening_type" name="opening_type" class="form-control">
                      <option value="1" {{ @$pinfo->opening_type == 1 ? "selected" : "" }}>Payable</option>
                      <option value="2" {{ @$pinfo->opening_type == 2 ? "selected" : "" }}>Receivable</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="asof">As Of Date</label>
                  <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="text" id="asof" name="asof" value="{{ @$pinfo->opening_asof != null ? date('d/m/Y',strtotime($pinfo->opening_asof)) : "" }}" class="form-control datepicker">
                  </div>
                </div>
                </div>
              </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                      <label for="id_proff">ID Proff</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <select id="id_proff" name="id_proff" class="form-control select2">
                        <option value="1" {{ @$pinfo->staff_id_proff == "1" ? "selected" : "" }}>Aadhar Card</option>
                        <option value="2" {{ @$pinfo->staff_id_proff == "2" ? "selected" : "" }}>Driving Licence</option>
                        <option value="3" {{ @$pinfo->staff_id_proff == "3" ? "selected" : "" }}>PAN Card</option>
                        <option value="4" {{ @$pinfo->staff_id_proff == "4" ? "selected" : "" }}>Election Card</option>
                        <option value="5" {{ @$pinfo->staff_id_proff == "5" ? "selected" : "" }}>Any other Resident OR Identy Proff</option>
                      </select>
                      </div>
                  </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                      <label for="id_select">ID Proff Photocopy
                      <?php
                      if($type == "edit") {
                        echo '<a href="'.asset($pinfo->staff_image).'" target="_blank">Current ID Proff</a>';
                      }
                      ?>
                      </label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
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
                                  <input type="file" id="id_select" accept="image/png, image/jpeg" name="id_select"/> <!-- rename it -->
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
                      <select id="state" name="state" class="form-control select2">
                      <option value="">Select State</option>
                      <?php
                      foreach($states as $row) {
                        ?>
                        <option value="{{ $row->state_id }}" {{ @$pinfo->state == $row->state_id ? "selected" : "" }}>{{ $row->state }}</option>
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
                      <select id="city" name="city" class="form-control select2">

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
              <div class="col-md-12">
                <h3 class="page-header">Bank Details</h3>
                <div class="form-group">
                  <label>
                    Want to add bank details? <input type="checkbox" value="1" id="confirmAns" name="confirmAns" class="confirmAns" {{ $type == 'edit' ? $pinfo->is_bank_detail != 1 ? "" : "checked" : "checked" }} data-size="mini" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger">
                  </label>
                </div>
              </div>
            </div>

            <div class="bank_container" style="{{ $type == 'edit' ? $pinfo->is_bank_detail != 1 ? 'display: none;' : '' : '' }}">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                      <label for="bank_person_name">Account Name</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <input type="text" id="bank_person_name" name="bank_person_name" class="form-control" value="{{ @$pinfo->bank_person_name }}">
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="bank_account_no">Account Number</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <input type="text" id="bank_account_no" name="bank_account_no" class="form-control" value="{{ @$pinfo->account_number }}">
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="bank_name">Bank Name</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <select id="bank_name" name="bank_name" class="form-control select2-bank">
                        <?php
                        foreach($bank_list as $row) {
                          ?>
                          <option data-img="{{ $row->icon }}" value="{{ $row->id }}" {{ @$pinfo->bank_name == $row->id ? "selected" : "" }}>{{ $row->name }}</option>
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
                      <label for="bank_branch">Branch</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <input type="text" id="bank_branch" name="bank_branch" class="form-control" value="{{ @$pinfo->branch }}">
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="bank_ifsc">IFSC Code</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <input type="text" id="bank_ifsc" name="bank_ifsc" class="form-control" onkeyup="this.value = this.value.toUpperCase();" value="{{ @$pinfo->ifsc_code }}">
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="account_type">Account Type</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                      <select id="account_type" name="account_type" class="form-control">
                        <option value="1" {{ @$pinfo->account_type == 1 ? "selected" : "" }}>Saving Account</option>
                        <option value="2" {{ @$pinfo->account_type == 2 ? "selected" : "" }}>Current Account</option>
                      </select>
                      </div>
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
          <button type="submit" class="btn btn-primary" onclick="return val_add_party();"><i class="fa {{ $type == 'edit' ? 'fa-upload' : 'fa-plus' }}"></i> {{ $type == 'edit' ? 'Update' : 'Save' }}</button>
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
<a href="{{ route('engineer') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<script type="text/javascript">
function val_add_party()
{
	if($("#party_name").val() == "")
	{
		toastr.error("Please, Enter Business Name");
		$("#party_name").focus();
		return false;
	}
  if($("#person_name").val() == "")
	{
		toastr.error("Please, Enter Engineer Name");
		$("#person_name").focus();
		return false;
	}
  if($("#email").val() != "") {
    if(!isEmail($("#email").val())) {
      $("#email").focus();
      toastr.error('Please, Enter Valid Email');
      return false;
    }
  }
  if($("#mobile").val() == "") {
    $("#mobile").focus();
    toastr.error('Please, Enter Mobile Number');
    return false;
  }
  if(!isPhone($("#mobile").val())) {
    $("#mobile").focus();
    toastr.error('Please, Enter Valid Mobile');
    return false;
  }
  if($("#alt_no").val() != "") {
    if(!isPhone($("#alt_no").val())) {
      $("#alt_no").focus();
      toastr.error('Please, Enter Valid Mobile');
      return false;
    }
  }

  if($("#id_proff").val() == "") {
    $("#id_proff").focus();
    toastr.error('Please, Select any ID Proff');
    return false;
  }

  <?php
  if($type == "add") {
    ?>
    if($("#id_select").val() == "") {
      $("#id_select").focus();
      toastr.error('Please, Select ID Proff Image');
      return false;
    }
    <?php
  }
  ?>


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


  if($(".confirmAns").prop('checked')) {
    if($("#bank_person_name").val() == "")
  	{
  		toastr.error("Please, Enter Account Name");
  		$("#bank_person_name").focus();
  		return false;
  	}
    if($("#bank_account_no").val() == "")
  	{
  		toastr.error("Please, Enter Account Number");
  		$("#bank_account_no").focus();
  		return false;
  	}
    if($("#bank_name").val() == "")
  	{
  		toastr.error("Please, Enter Bank Name");
  		$("#bank_name").focus();
  		return false;
  	}
    if($("#bank_ifsc").val() == "")
  	{
  		toastr.error("Please, Enter IFSC code");
  		$("#bank_ifsc").focus();
  		return false;
  	}
    if($("#account_type").val() == "")
  	{
  		toastr.error("Please, Select Account Type");
  		$("#account_type").focus();
  		return false;
  	}
    if($("#bank_branch").val() == "")
  	{
  		toastr.error("Please, Enter Bank Branch");
  		$("#bank_branch").focus();
  		return false;
  	}
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


$(document).on("change",".confirmAns",function(e){
	if($(this).prop('checked')) {
    $(".bank_container").slideDown();
  } else {
    $(".bank_container").slideUp();
  }
});


</script>

@endsection
