@extends('admin.v1.layout.app', ['title' => 'Vision'])

@section('content')
<style type="text/css">
.select2-container .select2-selection--single .select2-selection__rendered {
  padding-left: 0px !important;
}
.enrollment_title {
  font-size: 28px;
  font-weight: bolder;
  margin-bottom: 10px;
}
.icheckbox_square-blue {
  /* margin-left: -19px; */
  margin-right: 3px;
}
.lbl1 {
  margin-left: 15px;
}
.lbl1:first-child {
  margin-left: 0px;
}
  .hideme {
    display: none;
  }
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-eye"></i> Vision
    <small>Add New Certificate</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Vision</li>

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
            <div class="col-md-10">
              <div class="enrollment_title">Certificate No: 3526</div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="dev_ndt_id">Dev Ndt Id</label>
                <select  style="width: 100%;" id="dev_ndt_id" name="dev_ndt_id  " class="form-control select2">
                  <option value="">Find By Id</option>
                  <option value="3939">3939</option>
                  <option value="3940">3940</option>
                  <option value="3950">3950</option>
                  <option value="3951">3951</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" id="company_name" name="company_name" placeholder="Company Name" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="certificate_date">Certificate Date</label>
                <input type="text" id="certificate_date" name="certificate_date"  class="form-control datepicker">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="name">Candidate Name</label>
                <div class="row">
                <div class="col-md-1">
                  <select id="f_greet" name="f_greet" value="" class="form-control">
                    <option value="MR">MR</option>
                    <option value="MS">MS</option>
                    <option value="MISS">MISS</option>
                  </select>
                </div>
                  <div class="col-md-4">
                  <input type="text" id="f_fname" name="f_fname" placeholder="First Name" value="" class="form-control">
                </div>
                  <div class="col-md-4">
                  <input type="text" id="f_mname" name="f_mname" placeholder="Middle Name" value="" class="form-control">
                </div>
                  <div class="col-md-3">
                  <input type="text" id="f_lname" name="f_lname" placeholder="Last Name" value="" class="form-control">
                </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-12">
                  <h3 class="page-header">Near Vision</h3>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="lbl1">J1 <input type="radio" id="method" name="j[]" checked value="j1" class="form-control"></label>
                    <label class="lbl1">J2 <input type="radio" id="method" name="j[]" value="j2" class="form-control"></label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="lbl1">Red <input type="checkbox" id="method" name="method[]" checked value="j1" class="form-control"></label>
                    <label class="lbl1">Green <input type="checkbox" id="method" name="method[]" checked value="j2" class="form-control"></label>
                    <label class="lbl1">Blue <input type="checkbox" id="method" name="method[]" checked value="j2" class="form-control"></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-12">
                  <h3 class="page-header">Color Vision</h3>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="lbl1">Approve <input type="radio" id="cv_yes" name="cv[]" checked value="yes" class="form-control"></label>
                    <label class="lbl1">Not Approve <input type="radio" id="cv_no" name="cv[]" value="no" class="form-control"></label>
                  </div>
                </div>
                <div class="col-md-12 hideme">
                  <div class="form-group">
                    <label class="lbl1">Red / Green <input type="checkbox" id="method" name="method[]" checked value="j1" class="form-control"></label>
                    <label class="lbl1">Blue / Yellow <input type="checkbox" id="method" name="method[]" checked value="j2" class="form-control"></label>
                  </div>
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

$('#cv_no').on('ifChecked', function(event){
  //alert(event.type + ' callback');
  $(".hideme").slideDown();
});

$('#cv_yes').on('ifChecked', function(event){
  //alert(event.type + ' callback');
  $(".hideme").slideUp();
});

$(function () {
  $('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    // increaseArea: '20%' /* optional */
  });
});
</script>

@endsection
