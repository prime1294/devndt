@extends('admin.v1.layout.auth')

@section('content')
<div class="login-box">
<div class="login-box-body">
    <div class="login-logo">
      <center><img src="{{ asset(config('setting.app_logo')) }}" class="img-responsive" width="200px"></center>
    </div>
    <p class="login-box-msg">Enter 6 Digit OTP to recover your account</p>
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
    <form action="{{ route('verify.otp') }}" method="post">
    	{!! csrf_field() !!}

      <input type="hidden" id="mobilecode" name="mobilecode" value="91">
      <input type="hidden" id="user_id" name="user_id" value="{{  $info->id }}">
      <div class="form-group has-feedback">
        <input type="text" class="form-control onlyint" name="mobile" id="mobile" min="6" maxlength="6" placeholder="One Time Password">
        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <button type="submit" onClick="return validation();" class="btn btn-primary btn-block btn-flat">Verify OTP</button>
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

<script type="text/javascript">
function validation()
{
  if($("#mobile").val() == "") {
    $("#mobile").focus();
    toastr.error('Please, Enter OTP');
    return false;
  }
}
</script>

@endsection
