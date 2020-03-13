@extends('admin.v1.layout.app', ['title' => 'Add Process'])

@section('content')



<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-user"></i> Process
    <?php if($type == "edit") {
      ?><small>Edit Process</small><?php
    } else {
      ?><small>Add New Process</small><?php
    } ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <?php if($type == "edit") {
      ?><li class="active">Edit Process</li><?php
    } else {
      ?><li class="active">Add New Process</li><?php
    } ?>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <?php if($type == "edit") {  ?>
        <form method="post" action="{{ route('process.update',[$pinfo->id]) }}" enctype="multipart/form-data">
        <?php } else { ?>
        <form method="post" action="{{ route('process.register') }}" enctype="multipart/form-data">
        <?php } ?>
          {!! csrf_field() !!}
            <div class="row">
              <div class="col-md-12">
                <!-- <center> -->
                  <div class="fbpickermain">
                      <div class=fbpiker>
                          <span class="fbremove"><i class="fa fa-times"></i></span>
                          <img id="fbholdernew" data-default="{{ @$pinfo->photo ? asset($pinfo->photo) : asset('user_404.jpg') }}" src="{{ @$pinfo->photo ? asset($pinfo->photo) : asset('user_404.jpg') }}"  onclick="triggerfile('fbholdernew','fbinputtxt','image/process/','.jpg,.png,.jpeg','box')">
                      </div>
                      <input id="fbinputtxt" name="fbinputtxt" class='fbinputtxt' value="{{ @$pinfo->photo  }}" type="hidden" >
                  </div>
                <!-- </center> -->
              </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="person_name">Owner Name <?= COMPICON ?></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input type="text" id="person_name" name="person_name" class="form-control" value="{{ @$pinfo->name }}">
                        </div>
                    </div>
                </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="party_name">Business Name <?= COMPICON ?></label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-institution"></i></span>
                      <input type="text" id="party_name" name="party_name" class="form-control" value="{{ @$pinfo->business_name }}">
                      </div>
                  </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                      <label for="type_of_manufacturer">Type of Process</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                      <select style="width: 100%;" id="type_of_manufacturer" multiple="multiple" name="type_of_manufacturer[]" class="form-control select2">
                        <?php
                        foreach($manufacturer as $row) {
                          ?>
                          <option value="{{ $row->id }}" {{ Admin::find_in_set(@$pinfo->types_of_menufecture,$row->id) ? "selected" : "" }}>{{ $row->name }}</option>
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
                      <label for="email">Email</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                      <input type="text" id="email" name="email" class="form-control" value="{{ @$pinfo->email }}">
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="mobile">Mobile No</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                      <input type="text" id="mobile" name="mobile" class="form-control" value="{{ @$pinfo->mobile }}" data-inputmask='"mask": "9999999999"' data-mask>
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="alt_no">Alternative Mobile No</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                      <input type="text" id="alt_no" name="alt_no" class="form-control" value="{{ @$pinfo->alt_mobile }}">
                      </div>
                  </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="opening_balance">Opening Balance</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                    <input type="text" id="opening_balance" name="opening_balance" value="{{ @$pinfo->opening_balance }}" class="form-control onlyint">
                  </div>
              </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="opening_type">Balance Type</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-recycle"></i></span>
                    <select id="opening_type" name="opening_type" onchange="changeselectcolor(this);" class="form-control">
                      <option style="color: red;" value="1" {{ @$pinfo->opening_type == 1 ? "selected" : "" }}>Payable</option>
                      <option style="color: green;" value="2" {{ @$pinfo->opening_type == 2 ? "selected" : "" }}>Receivable</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="asof">As Of Date</label>
                  <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="text" id="asof" name="asof" default-date="{{ @$pinfo->opening_asof != null ? date('D M d Y H:i:s O',strtotime($pinfo->opening_asof)) : date('D M d Y H:i:s O',strtotime("now")) }}" value="{{ @$pinfo->opening_asof != null ? date('d-m-Y',strtotime($pinfo->opening_asof)) : "" }}" class="form-control datepicker">
                  </div>
                </div>
                </div>
              </div>


            <div class="row">

              <div class="col-md-4">
                <div class="form-group">
                      <label for="gstno">GSTIN NO <a target="_blank" href="https://services.gst.gov.in/services/searchtp">Check GSTIN No</a></label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                      <input type="text" id="gstno" name="gstno" maxlength="15" class="form-control" onpaste="verifyGST(this)" value="{{ @$pinfo->gstin_no }}">
                      </div>
                  </div>
              </div>
              <div class="col-md-8 addressclass">
                <div class="form-group">
                      <label for="address">Address</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
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
                      <span class="input-group-addon"><i class="fa fa-flag"></i></span>
                      <select style="width: 100%;" id="state" name="state" class="form-control select2">
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
                      <span class="input-group-addon"><i class="fa fa-street-view"></i></span>
                      <select style="width: 100%;" id="city" name="city" class="form-control select2">

                      </select>
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="pincode">Pincode</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-map-pin"></i></span>
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
                    Want to add bank details? <input type="checkbox" value="1" id="confirmAns" name="confirmAns" class="confirmAns" {{ $type == 'edit' ? $pinfo->is_bank_detail != 1 ? "" : "checked" : "" }} data-size="mini" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger">
                  </label>
                </div>
              </div>
            </div>

            <div class="bank_container" style="{{ $type == 'edit' ? $pinfo->is_bank_detail != 1 ? 'display: none;' : '' : 'display: none;' }}">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                      <label for="bank_person_name">Account Name</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-user"></i></span>
                      <input type="text" id="bank_person_name" name="bank_person_name" class="form-control" value="{{ @$pinfo->bank_person_name }}">
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="bank_account_no">Account Number</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
                      <input type="text" id="bank_account_no" name="bank_account_no" class="form-control" value="{{ @$pinfo->account_number }}">
                      </div>
                  </div>
              </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="account_type">Account Type</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                            <select id="account_type" name="account_type" class="form-control">
                                <option value="1" {{ @$pinfo->account_type == 1 ? "selected" : "" }}>Saving Account</option>
                                <option value="2" {{ @$pinfo->account_type == 2 ? "selected" : "" }}>Current Account</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="bank_name">Bank Name</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-institution"></i></span>
                            <select style="width: 100%;" id="bank_name" name="bank_name" class="form-control select2-bank">
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
              <div class="col-md-4">
                <div class="form-group">
                      <label for="bank_branch">Branch</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-star-half-o"></i></span>
                      <input type="text" id="bank_branch" name="bank_branch" class="form-control" value="{{ @$pinfo->branch }}">
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="bank_ifsc">IFSC Code</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-compressed"></i></span>
                      <input type="text" id="bank_ifsc" name="bank_ifsc" class="form-control" onkeyup="this.value = this.value.toUpperCase();" value="{{ @$pinfo->ifsc_code }}">
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
<a href="{{ route('process') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<script type="text/javascript">
function val_add_party()
{

  if($("#person_name").val() == "")
	{
		toastr.error("Please, Enter Owner Name");
		$("#person_name").focus();
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
if($type == "add") {
?>
initdatepicker(true);
<?php
}
?>
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
