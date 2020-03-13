<!DOCTYPE html>
<html>
<head>
<title>{{ config('setting.app_name') }} | Login</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<meta property="og:title" content="{{ config('setting.app_name') }} Inspection & Engineering" />
<meta property="og:url" content="{{ env('APP_URL') }}" />
<meta property="og:description" content="{{ config('setting.app_name') }} Inspection & Engineering">
<meta property="og:image" content="{{ asset(config('setting.favicon2')) }}">
<meta property="og:type" content="article" />
<meta property="og:locale" content="en_US" />

<link rel="apple-touch-icon-precomposed" href="{{ asset(config('setting.favicon2')) }}">
<link rel="icon" href="{{ asset(config('setting.favicon2')) }}">

{!!Html::style('/public/admin/theme1/bower_components/bootstrap/dist/css/bootstrap.min.css')!!}
{!!Html::style('/public/admin/theme1/bower_components/font-awesome/css/font-awesome.min.css')!!}
{!!Html::style('/public/admin/theme1/bower_components/Ionicons/css/ionicons.min.css')!!}
{!!Html::style('/public/admin/theme1/plugins/iCheck/square/blue.css')!!}
{!!Html::style('/public/admin/theme1/plugins/bootstrap-toggle/bootstrap-toggle.min.css')!!}
{!!Html::style('/public/admin/theme1/bower_components/jvectormap/jquery-jvectormap.css')!!}
{!!Html::style('/public/admin/theme1/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')!!}
{!!Html::style('/public/admin/theme1/dist/css/AdminLTE.min.css')!!}
{!!Html::style('/public/admin/theme1/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')!!}
{!!Html::style('/public/admin/theme1/dist/css/skins/_all-skins.min.css')!!}


{!!Html::style('/public/admin/theme1/general/css/toastr.min.css')!!}
{!!Html::style('/public/admin/theme1/general/css/offline-theme-slide.css')!!}
{!!Html::style('/public/admin/theme1/general/css/offline-language-english.min.css')!!}
{!!Html::style('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic')!!}


<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- <script src='https://www.google.com/recaptcha/api.js'></script> -->
<style type="text/css">
.checkbox {
  margin-top: 0px;
  margin-bottom: 0px;  
}
.icheckbox_square-blue {
  margin-left: -19px;
  margin-right: 3px;
}
.main_header {
    font-weight: 900;
    font-size: 37px;
    text-transform: uppercase;
    color: white;
    font-family: unset;
}
.chage-bg {
	background: linear-gradient(-45deg, #EE7752, #E73C7E, #23A6D5, #23D5AB);
	background-size: 400% 400%;
	-webkit-animation: Gradient 15s ease infinite;
	-moz-animation: Gradient 15s ease infinite;
	animation: Gradient 15s ease infinite;
}
@-webkit-keyframes Gradient {
	0% {
		background-position: 0% 50%
	}
	50% {
		background-position: 100% 50%
	}
	100% {
		background-position: 0% 50%
	}
}
@-moz-keyframes Gradient {
	0% {
		background-position: 0% 50%
	}
	50% {
		background-position: 100% 50%
	}
	100% {
		background-position: 0% 50%
	}
}
@keyframes Gradient {
	0% {
		background-position: 0% 50%
	}
	50% {
		background-position: 100% 50%
	}
	100% {
		background-position: 0% 50%
	}
}
.rc-anchor-light.rc-anchor-normal
{
	border: none !important;
}
.rc-anchor-light
{
	background:transparent !important;
}
.rc-anchor
{
	box-shadow:none !important;
}
</style>
</head>
<body class="hold-transition login-page chage-bg">
@yield('content')
</body>

{!!Html::script('/public/admin/theme1/bower_components/jquery/dist/jquery.min.js')!!}
{!!Html::script('/public/admin/theme1/bower_components/bootstrap/dist/js/bootstrap.min.js')!!}
{!!Html::script('/public/admin/theme1/bower_components/fastclick/lib/fastclick.js')!!}
{!!Html::script('/public/admin/theme1/plugins/bootstrap-toggle/bootstrap-toggle.min.js')!!}
{!!Html::script('/public/admin/theme1/dist/js/adminlte.min.js')!!}
{!!Html::script('/public/admin/theme1/general/js/toastr.min.js')!!}
{!!Html::script('/public/admin/theme1/general/js/offline.min.js')!!}
{!!Html::script('/public/admin/theme1/general/js/snake.js')!!}
{!!Html::script('/public/admin/theme1/plugins/iCheck/icheck.min.js')!!}
{!!Html::script('/public/admin/theme1/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')!!}
{!!Html::script('/public/admin/theme1/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')!!}
{!!Html::script('/public/admin/theme1/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')!!}
{!!Html::script('/public/admin/theme1/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')!!}
{!!Html::script('/public/admin/theme1/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')!!}
{!!Html::script('/public/admin/theme1/bower_components/datatables.net/js/jquery.dataTables.min.js')!!}
{!!Html::script('/public/admin/theme1/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')!!}
{!!Html::script('/public/admin/theme1/dist/js/demo.js')!!}
<script type="application/javascript">
// Numeric only control handler
jQuery.fn.ForceNumericOnly =
function()
{
   return this.each(function()
   {
       $(this).keydown(function(e)
       {
           var key = e.charCode || e.keyCode || 0;
           // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
           // home, end, period, and numpad decimal
           return (
               key == 8 ||
               key == 9 ||
               key == 13 ||
               key == 46 ||
               key == 110 ||
               key == 190 ||
               (key >= 35 && key <= 40) ||
               (key >= 48 && key <= 57) ||
               (key >= 96 && key <= 105));
       });
   });
};

function isPhone(phone){
  var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
  if(phone.match(phoneno)) {
    return true;
  } else {
    return false;
  }
}

$(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });

$(document).ready(function(e) {
    $(".onlyint").ForceNumericOnly();
    $('.dtable').DataTable();
	$('.editor').wysihtml5();

});

setInterval(function(){
	     Offline.check();
	  	  if(Offline.state == "down")
		  {
			  $("#show_internet_status").html('<i class="fa fa-circle text-default"></i> Offline');
		  }
		  else
		  {
			  $("#show_internet_status").html('<i class="fa fa-circle text-success"></i> Online');
		  }
	  }, 3000);
</script>
<!-- <script type="text/javascript">
function validation()
{
	if(document.querySelector('.g-recaptcha-response').value == "")
	{
		alert("Please..! Verify You are not a robot");
		return false;
	}
}
</script> -->
<script type="text/javascript">
    $('#show_password').on('ifChanged', function() {
        var x = document.getElementById("password_box");
        if(this.checked) {
            x.type = "text";
        } else {
            x.type = "password";
        }
    });

    $('#show_password_new').on('ifChanged', function() {
        var x = document.getElementById("password_box");
        var y = document.getElementById("password_box_confirm");
        if(this.checked) {
            x.type = "text";
            y.type = "text";
        } else {
            x.type = "password";
            y.type = "password";
        }
    });
</script>
</html>
