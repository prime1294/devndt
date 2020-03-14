<?php
$user = Sentinel::check();
$role = Sentinel::findRoleById($user->id);
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="{{ asset($user->image) }}" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p>{{ ucwords($user->first_name) }}</p>
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>
  <!-- search form -->
{{--  <form action="#" method="get" class="sidebar-form">--}}
{{--    <div class="input-group">--}}
{{--      <input type="text" id="search_data1" name="q" class="form-control" autocomplete="false" placeholder="Search Stock">--}}
{{--      <span class="input-group-btn">--}}
{{--            <button type="submit" name="search" id="search-btn" class="btn btn-flat">--}}
{{--              <i class="fa fa-search"></i>--}}
{{--            </button>--}}
{{--          </span>--}}
{{--    </div>--}}
{{--  </form>--}}
  <!-- /.search form -->
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">MAIN NAVIGATION</li>
    <li>
      <a href="{{ route('user.dashboard') }}">
        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
      </a>
    </li>
    <li>
      <a href="{{ route('profile') }}">
        <i class="fa fa-user"></i> <span>Profile</span>
      </a>
    </li>
    <li>
      <a href="{{ route('profile') }}">
        <i class="fa fa-file-text"></i> <span>Enrollment</span>
      </a>
    </li>
    <li>
      <a href="{{ route('user.logout') }}">
        <i class="fa fa-sign-out"></i> <span>Logout</span>
      </a>
    </li>
  </ul>
</section>
<!-- /.sidebar -->
</aside>
