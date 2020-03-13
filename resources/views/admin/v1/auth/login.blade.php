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
    <p class="login-box-msg">Sign in to start your session</p>

    @if(Session::has('alert-error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fa fa-ban"></i> {{ Session::get('alert-error') }}
      </div>
    @endif

    @if(Session::has('alert-success'))
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fa fa-check"></i> {{ Session::get('alert-success') }}
      </div>
    @endif

    <form action="{{ route('user.authorized') }}" method="post">
    	{!! csrf_field() !!}
      <input type="hidden" id="mobilecode" name="mobilecode" value="91">
      <div class="form-group has-feedback">
        <input type="text" class="form-control onlyint" name="mobile" id="mobile" min="10" maxlength="10" placeholder="Mobile Number">
        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" id="password_box" name="password" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-12 mb-10">
          <div class="checkbox">
          <label>
            <input type="checkbox" id="show_password" class="icheckbox_square-blue" value="1" name="show_password"> Show Password
          </label>
          </div>
        </div>
        <div class="col-xs-8">
          <div class="checkbox">
            <label>
              <input type="checkbox" class="icheckbox_square-blue" value="1" name="remember"> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" onClick="return validation();" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
    <div class="col-md-12">
    <br>
    <a href="{{ route('user.forgot.password') }}" class="pull-right">I forgot my password</a>
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

  if($("#password").val() == "") {
    $("#password").focus();
    toastr.error('Please, Enter Password');
    return false;
  }

}

</script>

@endsection
