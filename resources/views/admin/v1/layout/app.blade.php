<!DOCTYPE html>
<html>
<head>
<title>{{ config('setting.app_name') }} | {{ $title }}</title>
<?php
echo View::make('admin.v1.comman.css');
echo View::make('admin.v1.comman.js');
?>
</head>
<body class="skin-blue sidebar-mini sidebar-collapse">
<div class="wrapper">
<?php
echo View::make('admin.v1.comman.header');
echo View::make('admin.v1.comman.sidebar');
?>



  <div class="content-wrapper">
    @yield('content')
  </div>
  <!-- /.content-wrapper -->

<?php
echo View::make('admin.v1.comman.footer');
?>

</div>
<!-- ./wrapper -->
</body>
</html>
