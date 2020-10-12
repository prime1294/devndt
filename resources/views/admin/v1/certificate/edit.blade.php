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
    <small>Edit Certificate</small>
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
        <form method="post" action="{{ route('vision.update',$info->id) }}" enctype="multipart/form-data">
          {!! csrf_field() !!}

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="add_company_no">Company</label>
                <?php
                $company_default = $info->company_id != 0 ? 'data-default='.$info->company_id : '';
                ?>
                <select style="width: 100%;" id="company_id" name="company_id" class="form-control company-select2" {{ $company_default }} data-placeholder="Select Company">
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="certificate_date">Issue Date</label>
                <input type="text" id="certificate_date" name="certificate_date" value="{{ date('d-m-Y',strtotime($info->issue_date)) }}"  class="form-control datepicker">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="expire_date">Expiration Date</label>
                <input type="text" id="expire_date" name="expire_date" value="{{ date('d-m-Y',strtotime($info->expire_date)) }}"  readonly class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="name">Candidate Name</label>
                <div class="row">
                  <div class="col-md-2">
                      <select  style="width: 100%;" id="dev_ndt_id" name="dev_ndt_id" class="form-control ref_fetch_ndt select2">
                        <option value="">Find By Id</option>
                        @foreach($enrollment_list as $row)
                          <option value="{{ $row->id }}"  {{ $row->id == $info->ndt_id ? "selected" : "" }}>{{ $row->id }} - {{ $row->front_fname.' '.$row->front_lname }}</option>
                        @endforeach
                      </select>
                  </div>
                <div class="col-md-1">
                  <select id="f_greet" name="f_greet" value="" class="form-control">
                    <option value="MR" {{ $info->greet == "MR" ? "selected" : "" }}>Mr</option>
                    <option value="MS" {{ $info->greet == "MS" ? "selected" : "" }}>Ms</option>
                    <option value="MD" {{ $info->greet == "MD" ? "selected" : "" }}>Md</option>
                  </select>
                </div>
                  <div class="col-md-3">
                  <input type="text" id="f_fname" name="f_fname" placeholder="First Name" value="{{ $info->fname }}" class="form-control">
                </div>
                  <div class="col-md-3">
                  <input type="text" id="f_mname" name="f_mname" placeholder="Middle Name" value="{{ $info->mname }}" class="form-control">
                </div>
                  <div class="col-md-3">
                  <input type="text" id="f_lname" name="f_lname" placeholder="Last Name" value="{{ $info->lname }}" class="form-control">
                </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  <h3 class="page-header">Near Vision</h3>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="lbl1">Spectacles <input type="checkbox" id="method" name="spectacles" value="1" {{ $info->spectacles == 1 ? "checked" : "" }} class="form-control"></label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="lbl1">J1 <input type="radio" id="method" name="j" {{ $info->nv_type == 1 ? "checked" : ""  }} value="1" class="form-control"></label>
                    <label class="lbl1">J2 <input type="radio" id="method" name="j" {{ $info->nv_type == 2 ? "checked" : ""  }} value="2" class="form-control"></label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="lbl1">Ok <input type="radio" id="method" name="nv_condition" {{ $info->nv_condition == 1 ? "checked" : "" }} value="1" class="form-control"></label>
                    <label class="lbl1">Not OK <input type="radio" id="method" name="nv_condition" {{ $info->nv_condition == 0 ? "checked" : "" }} value="0" class="form-control"></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  <h3 class="page-header">Color Vision</h3>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="lbl1">OK <input type="radio" id="cv_yes" name="cv" {{ $info->cv_condition == 1 ? "checked" : "" }} value="1" class="form-control"></label>
                    <label class="lbl1">Not OK <input type="radio" id="cv_no" name="cv" {{ $info->cv_condition == 0 ? "checked" : "" }} value="0" class="form-control"></label>
                  </div>
                </div>
                <div class="col-md-12 hideme">
                  <div class="form-group">
                    <?php
                          $color_vision_group = explode(',',$info->cv_color);
                    ?>
                    <label class="lbl1">Red / Green <input type="checkbox" id="method" name="method[]" {{ in_array(1,$color_vision_group) ? "checked" : "" }} value="1" class="form-control primechk"></label>
                    <label class="lbl1">Blue / Yellow <input type="checkbox" id="method" name="method[]" {{ in_array(2,$color_vision_group) ? "checked" : "" }} value="2" class="form-control primechk"></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="row">
                  <div class="col-md-12">
                    <h3 class="page-header">Gray Shade Chart</h3>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="lbl1">Ok <input type="radio" id="method" name="gray_shade" {{ $info->gray_shade == 1 ? "checked" : "" }} value="1" class="form-control"></label>
                      <label class="lbl1">Not OK <input type="radio" id="method" name="gray_shade" {{ $info->gray_shade == 0 ? "checked" : "" }} value="0" class="form-control"></label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-devider"></div>
          <div class="form-group">
          <button type="submit" class="btn btn-primary" onclick="return check_val();"><i class="fa fa-upload"></i> Update</button>
          </div>
          </form>
      </div>
      <!-- /.box-body -->
    </div>
    </div>
  </div>
</section>

<div class="buy-now">
<a href="{{ route('vision') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>

@include('admin.v1.enrollment.add_company')

<script type="text/javascript">

function check_val() {
    if($("#company_id").val() == null) {
        toastr.error("Please, Select Company Name");
        $("#company_id").focus();
        return false;
    }
    if($("#certificate_date").val() == "") {
        toastr.error("Please, Select Issue Date");
        $("#certificate_date").focus();
        return false;
    }
    if($("#expire_date").val() == "") {
        toastr.error("Please, Enter Expire Date");
        $("#expire_date").focus();
        return false;
    }
    if($("#f_greet").val() == "") {
        toastr.error("Please, Select Greet of Name");
        $("#f_greet").focus();
        return false;
    }
    if($("#f_fname").val() == "") {
        toastr.error("Please, Enter First Name");
        $("#f_fname").focus();
        return false;
    }
    if($("#f_mname").val() == "") {
        toastr.error("Please, Enter Middle Name");
        $("#f_mname").focus();
        return false;
    }
    if($("#f_lname").val() == "") {
        toastr.error("Please, Enter Last Name");
        $("#f_lname").focus();
        return false;
    }
}

$(document).ready(function(e){
  initdatepicker(false);
  @if($info->cv_condition == 0)
  $(".hideme").slideDown();
  @endif
});

$(document).on("change","#company_id",function(e) {
  if($(this).val() == 0) {
    $("#addCompanyModel").modal('show');
  }
});

$(document).on("change","#certificate_date",function(e){
  var dob = $(this).val();
  $.ajax({
    url: '{{ route('age.calculator') }}',
    type: 'POST',
    data: '_token={{ csrf_token() }}&dob=' + dob+'&type=issue_date',
    success: function (ee) {
      $("#expire_date").val(ee);
    }
  });
});


$(document).on("change",".ref_fetch_ndt",function(e){
  var id = $(this).val();
  $.ajax({
    url: '{{ route('enrollment.info') }}',
    type: 'POST',
    dataType: 'json',
    data: '_token={{ csrf_token() }}&id=' + id,
    success: function (e) {
      //console.log(e);
      $("#f_greet").val(e.front_greet)
      $("#f_fname").val(e.front_fname)
      $("#f_mname").val(e.front_mname)
      $("#f_lname").val(e.front_lname)
    }
  });
});

$('#cv_no').on('ifChecked', function(event){
  //alert(event.type + ' callback');
  $(".hideme").slideDown();
});

$('#cv_yes').on('ifChecked', function(event){
  //alert(event.type + ' callback');
  $(".hideme").slideUp();
  $('.primechk').iCheck('uncheck');
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
