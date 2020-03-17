@extends('admin.v1.layout.app', ['title' => 'Enrollment'])

@section('content')
<style type="text/css">
.select2-container .select2-selection--single .select2-selection__rendered {
  padding-left: 0px !important;
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
  .method_div {
    margin-top: 20px;
  }
.enrollment_title {
  font-size: 28px;
  font-weight: bolder;
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
            <div class="col-md-10">
              <div class="enrollment_title">Enrollment No: 3526</div>
              <div class="form-group method_div">
                <?php
                $method = array('RT','UT','MT','PT','VT','ET','LT','IR','MFL','WI');
                foreach($method as $row) {
                  ?>
                  <label class="lbl1">{{ $row }} &nbsp;<input type="checkbox" id="method" name="method[]" value="{{ $row }}" class="form-control"></label>
                <?php
                }
                ?>
              </div>
            </div>
            <div class="col-md-2">
              <div class="fbpickermain pull-right">
                <div class=fbpiker>
                  <span class="fbremove"><i class="fa fa-times"></i></span>
                  <img id="fbholdernew" data-default="{{ asset('user_404.jpg') }}" src="{{ asset('user_404.jpg') }}"  onclick="triggerfile('fbholdernew','fbinputtxt','image/profile/','.jpg,.png,.jpeg','box')">
                </div>
                <input id="fbinputtxt" name="fbinputtxt" class='fbinputtxt' value="{{ asset('user_404.jpg') }}" type="hidden" >
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <h3 class="page-header">Personal Detail</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="name">Full Name (Front)</label>
                <div class="row">
                <div class="col-md-3">
                  <select id="f_greet" name="f_greet" value="" class="form-control">
                    <option value="MR">MR</option>
                    <option value="MS">MS</option>
                    <option value="MISS">MISS</option>
                  </select>
                </div>
                  <div class="col-md-3">
                  <input type="text" id="f_fname" name="f_fname" placeholder="First Name" value="" class="form-control">
                </div>
                  <div class="col-md-3">
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
              <div class="form-group">
                <label for="b_name">Certificate Name (Back)</label>
                <input type="text" id="b_name" name="b_name" placeholder="Full Name" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="father_name">Father Name</label>
                <input type="text" id="father_name" name="father_name" placeholder="Father Name" value="" class="form-control">
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="text" id="dob" name="dob"  class="form-control datepicker">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="age">Age From Current Date</label>
                <input type="text" id="age" name="age" placeholder="Age" readonly class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="contact">Contact</label>
                <input type="text" id="contact" name="contact" placeholder="Contact" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="alt_contact">Alt Contact</label>
                <input type="text" id="alt_contact" name="alt_contact" placeholder="Alt Contact" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="your@email.com" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="alt_email">Alt Email</label>
                <input type="text" id="alt_email" name="alt_email" placeholder="your@email.com" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="address">Permanent Address</label>
                <input type="text" id="address" name="address" placeholder="Address" value="" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" placeholder="Ahmedabad" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="district">District</label>
                <input type="text" id="district" name="district" placeholder="Ahmedabad" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="state">State</label>
                <input type="text" id="state" name="state" placeholder="Gujarat" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="pincode">Pincode</label>
                <input type="text" id="pincode" name="pincode" placeholder="3823**" value="" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="education">Educational Qualification</label>
                <input type="text" id="education" name="education" placeholder="MSC, BSC ..." value="" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="year_of_complete">Year of Completion</label>
                <input type="text" id="year_of_complete" name="year_of_complete" placeholder="Year" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="experience">Experience in NDT Field (in year)</label>
                <input type="text" id="experience" name="experience" placeholder="5" value="" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
            <h3 class="page-header">Company Detail</h3>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" id="company_name" name="company_name" placeholder="Company Name" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="company_address">Company Address</label>
                <input type="text" id="company_address" name="company_address" placeholder="Company Address" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="company_mobile">Company Contact</label>
                <input type="text" id="company_mobile" name="company_mobile" placeholder="+91 XXXXXXXX" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="company_email">Company Email</label>
                <input type="text" id="company_email" name="company_email" placeholder="your@email.com" value="" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="company_city">City</label>
                <input type="text" id="company_city" name="company_city" placeholder="Ahmedabad" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="company_district">District</label>
                <input type="text" id="company_district" name="company_district" placeholder="Ahmedabad" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="company_state">State</label>
                <input type="text" id="company_state" name="company_state" placeholder="Gujarat" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="company_pincode">Pincode</label>
                <input type="text" id="company_pincode" name="company_pincode" placeholder="3823**" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="holiday">Weelky Off (In Days)</label>
                <input type="text" id="holiday" name="holiday" placeholder="2" value="" class="form-control">
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-12">
              <h3 class="page-header">Referenced Detail</h3>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="ref_name">Name</label>
                <input type="text" id="ref_name" name="ref_name" placeholder="Name" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="ref_contact">Contact</label>
                <input type="text" id="ref_contact" name="ref_contact" placeholder="Contact" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="ref_email">Email</label>
                <input type="text" id="ref_email" name="ref_email" placeholder="Email" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="ref_company">Company Name</label>
                <input type="text" id="ref_company" name="ref_company" placeholder="Company Name" value="" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <h3 class="page-header">Fees Detail</h3>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="total_fees">Total Fees</label>
                <input type="text" id="total_fees" name="total_fees" placeholder="In INR" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="total_paid">Paid Fees</label>
                <input type="text" id="total_paid" name="total_paid" placeholder="In INR" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="total_pending">Pending Fees</label>
                <input type="text" id="total_pending" name="total_pending" placeholder="In INR" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="text" id="due_date" name="due_date"  class="form-control datepicker">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <h3 class="page-header">Other Detail</h3>
            </div>
          </div>


          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="v_place">Place</label>
                <input type="text" id="v_place" name="v_place" placeholder="Ahmedabad" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="v_date">Date</label>
                <input type="text" id="v_date" name="v_date"  class="form-control datepicker">
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

$(function () {
  $('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    // radioClass: 'iradio_square-blue',
    // increaseArea: '20%' /* optional */
  });
});
</script>

@endsection
