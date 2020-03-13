@extends('admin.v1.layout.app', ['title' => '401 Unauthorized Access'])

@section('content')

<style type="text/css">
.bigicon {
  font-size: 1000%;
}
.bigtext {
  font-size: 30px;
  font-weight: 900;
}
</style>
<!-- Content Header (Page header) -->
<section class="content">
<center>
  <div class="bigicon"><i class="fa fa-expeditedssl"></i></div>
  <div class="bigtext text-danger">401 - Unauthorized Access</div>
  <a class="btn btn-danger mt-sm" href="{{ URL::previous() }}"><i class="fa fa-arrow-left"></i> Go Back</a>
</center>
</section>

@endsection
