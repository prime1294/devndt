@extends('admin.v1.layout.auth')

@section('content')
<style type="text/css">
  .mt-10 {
    margin-top: 10px;
  }
  .mb-10 {
    margin-bottom: 10px;
  }
</style>
<div class="login-box">
  <div class="login-box-body">
    <div class="login-logo">
      <center><img src="{{ asset(config('setting.app_logo')) }}" class="img-responsive" width="200px"></center>
    </div>
    <p class="login-box-msg">Create new account password</p>

    @if(Session::has('alert-error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fa fa-ban"></i> {{ Session::get('alert-error') }}
      </div>
    @endif

    <form action="{{ route('register.new.password') }}" method="post">
    	{!! csrf_field() !!}
      <input type="hidden" id="user_id" name="user_id" value="{{  $info->id }}">
      <div class="form-group has-feedback">
        <input type="password" class="form-control" id="password_box" name="password" placeholder="New Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input type="password" class="form-control" id="password_box_confirm" name="password_confirm" placeholder="Confirm Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-12 mb-10">
          <div class="checkbox">
          <label>
            <input type="checkbox" id="show_password_new" class="icheckbox_square-blue" value="1" name="show_password"> Show Password
          </label>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <button type="submit" onClick="return validation();" class="btn btn-primary btn-block btn-flat">Change Password</button>
        </div>
      </div>

      <div class="row">
    <div class="col-md-12">
    <br>
      <a href="{{ route('user.login') }}" class="pull-right">Go Back</a>
    </div>
    </div>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script type="text/javascript">
function validation()
{
  if($("#password_box").val() == "") {
    $("#password").focus();
    toastr.error('Please, Enter Password');
    return false;
  }
  if($("#password_box_confirm").val() == "") {
    $("#password").focus();
    toastr.error('Please, Enter Confirm Password');
    return false;
  }
  if($("#password_box").val()  != $("#password_box_confirm").val()) {
    $("#password").focus();
    toastr.error('Confirm password not match');
    return false;
  }
}

</script>

@endsection
