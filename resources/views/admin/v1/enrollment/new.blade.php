@extends('admin.v1.layout.app', ['title' => 'Enrollment'])

@section('content')
<style type="text/css">
.select2-container .select2-selection--single .select2-selection__rendered {
  padding-left: 0px !important;
}
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-file-text"></i> Enrollment
    <small>Add New Enrollment</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Enrollment</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <form method="post" action="{{ route('delivery.challan.register') }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <div class="row">
            <div class="col-md-12">
              <div class="fbpickermain">
                <div class=fbpiker>
                  <span class="fbremove"><i class="fa fa-times"></i></span>
                  <img id="fbholdernew" data-default="{{ asset('user_404.jpg') }}" src="{{ asset('user_404.jpg') }}"  onclick="triggerfile('fbholdernew','fbinputtxt','image/profile/','.jpg,.png,.jpeg','box')">
                </div>
                <input id="fbinputtxt" name="fbinputtxt" class='fbinputtxt' value="{{ asset('user_404.jpg') }}" type="hidden" >
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="name">Full Name</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <input type="text" id="name" name="name" value="" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="method">Method</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-bookmark-o"></i></span>
                  <input type="text" id="method" name="method" value="" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="dob">Date of Birth</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="text" id="dob" name="dob"  class="form-control datepicker">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="address">Address</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                  <input type="text" id="address" name="address" value="" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="mobile">Contact No</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                  <input type="text" id="mobile" name="mobile" value="" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="email">Email</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                  <input type="text" id="email" name="email" value="" class="form-control">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="company_name">Company Name & Address</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-university"></i></span>
                  <input type="text" id="company_name" name="company_name" value="" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="company_mobile">Company Contact</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                  <input type="text" id="company_mobile" name="company_mobile" value="" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="company_email">Company Email</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                  <input type="text" id="company_email" name="company_email" value="" class="form-control">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="holiday">Holiday</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="text" id="holiday" name="holiday" value="" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="holiday">Educational Qualification</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-graduation-cap"></i></span>
                  <input type="text" id="holiday" name="holiday" value="" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="year_of_complete">Year Of Completion</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar-check-o"></i></span>
                  <input type="text" id="year_of_complete" name="year_of_complete" value="" class="form-control">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="experience">Experience in NDT Field</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-suitcase"></i></span>
                  <input type="text" id="experience" name="experience" value="" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="ref_name">Referenced By</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-thumbs-up"></i></span>
                  <input type="text" id="ref_name" name="ref_name" value="" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="ref_contact">Referenced Contact</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-tty"></i></span>
                  <input type="text" id="ref_contact" name="ref_contact" value="" class="form-control">
                </div>
              </div>
            </div>
          </div>


          <div class="form-devider"></div>
          <div class="form-group">
          <button type="submit" class="btn btn-primary" onclick="return val_add_party();"><i class="fa fa-plus"></i> Save</button>
          </div>
          </form>
      </div>
      <!-- /.box-body -->
    </div>
    </div>
  </div>
</section>

<div class="buy-now">
<a href="{{ route('enrollment') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>



<script type="text/javascript">
$(document).ready(function(e){
  initdatepicker(true);
});
</script>

@endsection
