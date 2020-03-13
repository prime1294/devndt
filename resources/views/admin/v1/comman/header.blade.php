<?php
$user = Sentinel::check();
$role = Sentinel::findRoleById($user->id);
?>
<style type="text/css">
</style>
<header class="main-header">
<!-- Logo -->
<a href="{{ route('user.dashboard') }}" class="logo">
  <!-- mini logo for sidebar mini 50x50 pixels -->
  <span class="logo-mini"><b>{{ Admin::initials(config('setting.app_name')) }}</b></span>
  <!-- logo for regular state and mobile devices -->
  <span class="logo-lg"><b>{{ config('setting.app_name') }}</b></span>
</a>

<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
  <!-- Sidebar toggle button-->
  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
    <span class="sr-only">Toggle navigation</span>
  </a>
  <!-- Navbar Right Menu -->
  <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
      <!-- Messages: style can be found in dropdown.less-->

      <!-- Notifications: style can be found in dropdown.less -->

      <!-- Tasks: style can be found in dropdown.less -->

      <!-- User Account: style can be found in dropdown.less -->
      <li class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <img src="{{ asset($user->image) }}" class="user-image" alt="User Image">
          <span class="hidden-xs">{{ ucwords($user->first_name) }}</span>
        </a>
        <ul class="dropdown-menu">
          <!-- User image -->
          <li class="user-header">
            <img src="{{ asset($user->image) }}" class="img-circle" alt="User Image">

            <p>
              {{ ucwords($user->first_name) }}
              <small>Member since {{ date('d F, Y',strtotime($user->created_at)) }}</small>
            </p>
          </li>
          <!-- Menu Body -->
          <!--<li class="user-body">
            <div class="row">
              <div class="col-xs-4 text-center">
                <a href="#">Followers</a>
              </div>
              <div class="col-xs-4 text-center">
                <a href="#">Sales</a>
              </div>
              <div class="col-xs-4 text-center">
                <a href="#">Friends</a>
              </div>
            </div>
          </li>-->
          <!-- Menu Footer-->
          <li class="user-footer">
            <div class="pull-left">
              <a href="{{ route('profile') }}" class="btn btn-default btn-flat">Profile</a>
            </div>
            <div class="pull-right">
              <a href="{{ route('user.logout') }}" class="btn btn-default btn-flat">Sign out</a>
            </div>
          </li>
        </ul>
      </li>
      <!-- Control Sidebar Toggle Button -->
    </ul>
  </div>

</nav>
</header>
