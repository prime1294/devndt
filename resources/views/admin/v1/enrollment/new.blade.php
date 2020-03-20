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
  .whatsapp_contact .input-group-addon {
    padding: 2px 7px;
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
              <h3 class="page-header">Personal Details</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="name">Name (Front)</label>
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
            <div class="col-md-12">
              <div class="form-group">
                <label for="name">Name (Back)</label>
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
            <div class="col-md-12">
              <div class="form-group">
                <label for="name">Father Name</label>
                <div class="row">
                  <div class="col-md-1">
                    <select id="f_greet" name="f_greet" disabled value="" class="form-control">
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
            <div class="col-md-2">
              <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="text" id="dob" name="dob"  class="form-control datepicker">
              </div>
            </div>
            <div class="col-md-1">
              <div class="form-group">
                <label for="age">Age</label>
                <input type="text" id="age" name="age" placeholder="Age" readonly class="form-control">
              </div>
            </div>
            <div class="col-lg-6 whatsapp_contact">
              <div class="form-group">
                <label for="contact">Contact</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <input type="checkbox" id="is_whatsapp" name="is_whatsapp">
                </span>
                <input type="text" id="contact" name="contact" placeholder="+91 XXXXXXXXXX" class="form-control">
              </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="your@email.com" value="" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
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
            <div class="col-md-6">
              <div class="form-group">
                <label for="education">Educational Qualification</label>
                <select id="education" name="education" data-placeholder="MSC, BSC ..." value="" multiple style="width: 100%;" class="form-control select2">
                  @foreach($education as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="year_of_complete">Year of Completion</label>
                <input type="text" id="year_of_complete" name="year_of_complete" placeholder="Year" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="experience">Experience in NDT Field (in years)</label>
                <input type="text" id="experience" name="experience" placeholder="5" value="" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-12">
                  <h3 class="page-header">Company Details</h3>
                </div>
              </div>

              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label for="company_name">Company Name</label>
                      <select style="width: 100%;" id="company_name" name="company_name" class="form-control select2-bank" data-placeholder="Select Company">
                        <option value="">Select Company</option>
                        <option data-img="{{ 'plus.png' }}" value="0">Add New</option>
                        @foreach($company_list as $row)
                          <option value="{{ $row->id }}">{{ ucwords($row->company_name).' - '.ucwords($row->company_type) }}</option>
                        @endforeach
                      </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="designation">Designation</label>
                    <select  style="width: 100%;" id="designation" name="designation" class="form-control select2">
                      <option value="">Select Designation</option>
                      <option value="Proprietor">Proprietor</option>
                      <option value="Director">Director</option>
                      <option value="Manager">Manager</option>
                      <option value="Engineer">Engineer</option>
                      <option value="Q.C.Manager">Q.C.Manager</option>
                      <option value="H.R.Manager">H.R.Manager</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-12">
                  <h3 class="page-header">Reference Details</h3>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="ref_name">Reference Name</label>
                    <select style="width: 100%;" id="ref_name" name="ref_name" class="form-control select2-bank" data-placeholder="Select Reference Name">
                      <option value="">Select Reference Name</option>
                      <option data-img="{{ 'plus.png' }}" value="0">Add New</option>
                      @foreach($ref_list as $row)
                        <option value="{{ $row->id }}">{{ ucwords($row->fname).' '.ucwords($row->lname) }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>












          <div class="row">
            <div class="col-md-12">
              <h3 class="page-header">Fees Details</h3>
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
              <h3 class="page-header">Other Details</h3>
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

@include('admin.v1.enrollment.add_company');
@include('admin.v1.enrollment.reference');

<script type="text/javascript">
$(document).ready(function(e){
  initdatepicker(true);
});

$(document).on("change","#company_name",function(e) {
  if($(this).val() == 0) {
    $("#addCompanyModel").modal('show');
  }
});

$(document).on("change","#ref_name",function(e) {
  if($(this).val() == 0) {
    $("#addReferenceModel").modal('show');
  }
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
