@extends('admin.v1.layout.app', ['title' => 'Profile'])

@section('content')
<?php
$user = Sentinel::check();
$role = Sentinel::findRoleById($user->id);
?>
<style type="text/css">
  .rm-max-width {
    max-width: none !important;
  }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-user"></i> Profile
    <small>Manage Your Profile</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Profile</li>
  </ol>
</section>

<section class="content">
<div class="row">
  <div class="col-md-4">
  <div class="box box-primary">
    <div class="box-body box-profile">
      <div class="fbpickermain">
        <div class="fbpiker rm-max-width">
          <span class="fbremove"><i class="fa fa-times"></i></span>
          <img class="profile-user-img img-responsive img-rounded" id="fbholdernew" data-default="{{ $user->image ? asset($user->image) : asset('user_404.jpg') }}" src="{{ $user->image ? asset($user->image) : asset('user_404.jpg') }}"  onclick="triggerfile('fbholdernew','fbinputtxt','image/profile/','.jpg,.png,.jpeg','box')">
        </div>
        <input id="fbinputtxt" name="fbinputtxt" class='fbinputtxt' value="{{ $user->image  }}" type="hidden" >
      </div>
      <h3 class="profile-username text-center">{{ ucwords($user->business_name) }}</h3>
      <p class="text-muted text-center">{{ ucwords($user->first_name) }}</p>
    </div>
    <!-- /.box-body -->
  </div>


    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Change Password</h3>
      </div>
      <div class="box-body">
        <form method="post" action="{{ route('register.new.password') }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <input type="hidden" name="redirect" value="profile">
          <input type="hidden" name="user_id" value="{{  $user->id }}">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="password">New Password</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-key"></i></span>
                  <input type="password" id="password" name="password" class="form-control" autocomplete="false">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="password_confirm">Confirm Password</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-key"></i></span>
                  <input type="password" id="password_confirm" name="password_confirm" class="form-control" autocomplete="false">
                </div>
              </div>
            </div>
          </div>
          <div class="form-devider mt-sm"></div>
          <div class="form-group">
            <button type="submit" onclick="return checkpasswordvalidation()" class="btn btn-primary"><i class="fa fa-key"></i> Change Password</button>
          </div>
        </form>
      </div>
      <!-- /.box-body -->
    </div>
  </div>

  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Personal Information</h3>
      </div>
      <div class="box-body">
        <form method="post" action="{{ route('update.profile') }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <input type="hidden" name="redirect" value="profile">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="first_name">Profile Name</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $user->first_name }}">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="email_id">Email</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input type="email" id="email_id" name="email_id" class="form-control" value="{{ $user->email_id }}">
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="mo_number">Mobile Number</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                <input type="text" id="mo_number" name="mo_number" class="form-control" value="{{ $user->mo_number }}" placeholder="8733883364" data-inputmask='"mask": "9999999999"' data-mask>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="alt_number">Alt Mobile Number</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                <input type="text" id="alt_number" name="alt_number" class="form-control" value="{{ $user->alt_number }}" placeholder="8733883364" data-inputmask='"mask": "9999999999"' data-mask>
              </div>
            </div>
          </div>
        </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="business_name">Business Name</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-institution"></i></span>
                  <input type="text" id="business_name" name="business_name" class="form-control" value="{{ $user->business_name }}">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="website">Website</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
                  <input type="search" id="website" name="website" class="form-control" value="{{ $user->website }}">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="address">Address</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-institution"></i></span>
                  <input type="text" id="address" name="address" class="form-control" value="{{ $user->address }}">
                </div>
              </div>
            </div>
          </div>
          <div class="form-devider mt-sm"></div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Update</button>
          </div>
        </form>
      </div>
      <!-- /.box-body -->
    </div>
  </div>
</div>
</section>


<script type="text/javascript">

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

  function checkpasswordvalidation() {
    if($("#password").val() == "") {
      $("#password").focus();
      toastr.error('Please, Enter Password');
      return false;
    }
    if($("#password_confirm").val() == "") {
      $("#password").focus();
      toastr.error('Please, Enter Confirm Password');
      return false;
    }
    if($("#password").val()  != $("#password_confirm").val()) {
      $("#password").focus();
      toastr.error('Confirm password not match');
      return false;
    }
  }


  $("#fbinputtxt").on("propertychange change keyup paste input", function(){
    $.ajax({
      url:'{{ route('update.profile') }}',
      type:'POST',
      data:'_token={{ csrf_token() }}&image='+$(this).val(),
      success:function(e) {
        if(e.status == "true" && e.message == "success") {
          toastr.success("Profile photo updated")
        } else {
          toastr.error(e.message);
        }
      }
    });
  });

  $(document).on("change","#state",function(e) {
    changecityhtml($(this).val(),0);
  });

  $(document).on("click","#fbholdernew",function(e) {
    jcropratio = 1;
  });

  $(document).on("click","#imagebtn_bill",function(e) {
    jcropratio = 0;
  });



  $(document).ready(function(e) {
    jcropratio = 0;
    jcropresize = true;
    changecityhtml('{{ $user->state }}','{{ $user->city }}');
  });
</script>
@endsection
