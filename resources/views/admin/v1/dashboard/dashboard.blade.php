@extends('admin.v1.layout.app', ['title' => 'Dashboard'])

@section('content')
<style type="text/css">
  .small-box > .small-box-footer {
    background: rgb(60, 141, 188);
  }
  .small-box .icon {
    font-size:80px;
  }
  @media only screen and (max-width: 768px) {
    .small-box .inner p {
      font-weight: bolder;
      color: black;
      font-size: 18px;
    }
    .small-box .icon {
      display: block;
      font-size:50px;
    }
  }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-dashboard"></i> Dashboard
    <small>Version 1.0</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-lg-3 col-xs-12">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ number_format(1000) }}</h3>

          <p>Admission</p>
        </div>
        <div class="icon">
          <i class="fa fa-suitcase"></i>
        </div>
        <a href="{{ route('bankaccount') }}" class="small-box-footer">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-12">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ number_format(500) }}</h3>

          <p>New Admission</p>
        </div>
        <div class="icon">
          <i class="fa fa-thumbs-up"></i>
        </div>
        <a href="{{ route('bankaccount') }}" class="small-box-footer">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-12">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ number_format(500) }}</h3>

          <p>Renew Admission</p>
        </div>
        <div class="icon">
          <i class="fa fa-recycle"></i>
        </div>
        <a href="{{ route('bankaccount') }}" class="small-box-footer">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-12">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ number_format(1000) }}</h3>

          <p>Enrollment</p>
        </div>
        <div class="icon">
          <i class="fa fa-file-text"></i>
        </div>
        <a href="{{ route('bankaccount') }}" class="small-box-footer">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
  </div>

</section>
@endsection
