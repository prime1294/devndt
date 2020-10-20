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
.my-group .form-control{
  width:50%;
}
.page-header {
  font-weight: bolder;
  color: #3c8dbc;
}
.hideme {
  display:none;
}
.hide_creation {
  display: none;
}
  .special_container {
    display: none;
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
        <form method="post" action="{{ route('enrollment.register') }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <div class="row">
            <div class="col-md-10">
              <div class="enrollment_title">Enrollment No: {{ $enrollment_increment }}</div>
              <div class="form-group method_div">
                <?php
                foreach($cource as $row) {
                  if($row->is_other == 0) {
                  ?>
                  <label class="lbl1" title="{{ $row->name }}">{{ $row->short_name }} &nbsp;<input type="checkbox" data-parent="{{ $row->short_name }}" id="method" name="certificates[]" data-fees="{{ intval($row->fees) }}" value="{{ $row->id }}" class="certificates form-control"></label>
                <?php
                  }
                }
                ?>
                <button type="button" class="btn btn-info btn-xs tglspecial" style="margin-bottom:0px; margin-left:10px;"><i class="fa fa-eye"></i> View Special</button>
                <div class="special_container">
                  <?php
                  foreach($cource as $row) {
                  if($row->is_other) {
                  ?>
                  <label class="lbl1" title="{{ $row->name }}">{{ $row->short_name }} &nbsp;<input type="checkbox" data-parent="{{ $row->short_name }}" id="method" name="certificates[]" data-fees="{{ intval($row->fees) }}" value="{{ $row->id }}" class="certificates form-control"></label>
                  <?php
                  }
                  }
                  ?>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="fbpickermain pull-right">
                <div class=fbpiker>
                  <span class="fbremove"><i class="fa fa-times"></i></span>
                  <img id="fbholdernew" data-default="{{ asset('user_404.jpg') }}" src="{{ asset('user_404.jpg') }}"  onclick="triggerfile('fbholdernew','fbinputtxt','image/profile/','.jpg,.png,.jpeg','box')">
                </div>
                <input id="fbinputtxt" name="fbinputtxt" class='fbinputtxt' value="{{ 'user_404.jpg' }}" type="hidden" >
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
                  <select id="front_greet" name="front_greet" value="" class="form-control">
                    <option value="MR">MR</option>
                    <option value="MS">MS</option>
                    <option value="MD">MD</option>
                  </select>
                </div>
                  <div class="col-md-4">
                  <input type="text" id="front_fname" name="front_fname" placeholder="First Name" value="" class="form-control">
                </div>
                  <div class="col-md-4">
                  <input type="text" id="front_mname" name="front_mname" placeholder="Middle Name" value="" class="form-control">
                </div>
                  <div class="col-md-3">
                  <input type="text" id="front_lname" name="front_lname" placeholder="Last Name" value="" class="form-control">
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
                  <select id="back_greet" name="back_greet" value="" class="form-control">
                    <option value="MR">MR</option>
                    <option value="MS">MS</option>
                    <option value="MD">MD</option>
                  </select>
                </div>
                  <div class="col-md-4">
                  <input type="text" id="back_fname" name="back_fname" placeholder="First Name" value="" class="form-control">
                </div>
                  <div class="col-md-4">
                  <input type="text" id="back_mname" name="back_mname" placeholder="Middle Name" value="" class="form-control">
                </div>
                  <div class="col-md-3">
                  <input type="text" id="back_lname" name="back_lname" placeholder="Last Name" value="" class="form-control">
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
                    <select id="father_greet" name="father_greet" disabled class="form-control">
                      <option value="MR">Mr</option>
                      <option value="MS">Ms</option>
                      <option value="MD">Md</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                  <input type="text" id="father_fname" name="father_fname" placeholder="First Name" value="" class="form-control">
                </div>
                  <div class="col-md-4">
                  <input type="text" id="father_mname" name="father_mname" placeholder="Middle Name" value="" class="form-control">
                </div>
                  <div class="col-md-3">
                  <input type="text" id="father_lname" name="father_lname" placeholder="Last Name" value="" class="form-control">
                </div>
                </div>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="text" id="dob" name="dob"  class="form-control agecalculate datepicker">
              </div>
            </div>
            <div class="col-md-1">
              <div class="form-group">
                <label for="age">Age</label>
                <input type="text" id="age" name="age" placeholder="Age" readonly class="form-control">
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <label for="contact">Contact</label>
                <input type="text" id="contact" name="contact" class="form-control" placeholder="8733883364" data-inputmask='"mask": "9999999999"' data-mask>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <label for="alt_contact">Alt Contact</label>
                <input type="text" id="alt_contact" name="alt_contact" class="form-control" placeholder="8733883364" data-inputmask='"mask": "9999999999"' data-mask>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="your@email.com" value="@gmail.com" class="form-control">
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
                <input type="text" id="state" name="state" placeholder="Gujarat" value="Gujarat" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="pincode">Pincode</label>
                <input type="text" id="pincode" name="pincode" placeholder="3823**" value="" class="form-control" placeholder="382345" data-inputmask='"mask": "999999"' data-mask>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="education">Educational Qualification</label>
                <select id="education" name="education[]" data-placeholder="MSC, BSC ..." value="" multiple style="width: 100%;" class="form-control select2">
                  @foreach($education as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="year_of_complete">Year of Completion</label>
                <input type="text" id="year_of_complete" name="year_of_complete" value="" class="form-control" placeholder="2020" data-inputmask='"mask": "9999"' data-mask>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="exp_hour">Experience in NDT Field</label>
                <div class="input-group my-group">
                <input type="text" id="exp_hour" name="exp_hour" placeholder="1" value="1" class="form-control">
                  <select id="exp_type" name="exp_type" class="form-control">
                    <option value="Years">Years</option>
                    <option value="Hours">Hours</option>
                  </select>
                </div>
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
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="company_id">Company Name</label>
                    <select style="width: 100%;" id="company_id" name="company_id" class="form-control company-select2" data-placeholder="Select Company">
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
                    <label for="ref_id">Reference Name</label>
                    <select style="width: 100%;" id="ref_id" name="ref_id" class="form-control ref-select2" data-placeholder="Select Reference Name">
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
                <input type="text" id="total_fees" name="total_fees" placeholder="In INR" value="0" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="paid_fees">Paid Fees</label>
                <input type="text" id="paid_fees" name="paid_fees" placeholder="In INR" value="" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="pending_fees">Pending Fees</label>
                <input type="text" id="pending_fees" name="pending_fees" placeholder="In INR" value="" class="form-control">
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
                <label for="place">Place</label>
                <input type="text" id="place" name="place" placeholder="Ahmedabad" value="Ahmedabad" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="reg_date">Date</label>
                <input type="text" id="reg_date" name="reg_date"  class="form-control datepicker">
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-12">
              <h3 class="page-header">Certificate Details</h3>
            </div>
          </div>


          <div class="row" style="margin-bottom:10px;">
            <div class="col-md-12">
              <label class="cr_class">New &nbsp;<input type="radio" id="creation_new" name="creation" value="1" checked class="chk_creation form-control"></label>&nbsp;&nbsp;
              <label class="cr_class">Other &nbsp;<input type="radio" id="creation_other" name="creation" value="2" class="chk_creation form-control"></label>
            </div>
          </div>



          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label for="ndt_level">Level</label>
                <select id="ndt_level" name="ndt_level" style="width:100%;" class="form-control select2">
                  <option value="I">I</option>
                  <option value="II" selected>II</option>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="snt_edition">SNT TC 1A Edition</label>
                <select id="snt_edition" name="snt_edition" style="width:100%;" class="form-control select2">
                  <option value="2020">2020</option>
                  <option value="2016" selected>2016</option>
                  <option value="2011">2011</option>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="vision">Vision</label>
                <select id="vision" name="vision" style="width:100%;" class="form-control select2">
                  <option value="J1" selected>J1</option>
                  <option value="J2">J2</option>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="sponsor">Sponsor</label>
                <select id="sponsor" name="sponsor" style="width:100%;" class="form-control select2">
                  <option value="self" selected>Self</option>
                  <option value="company">Company</option>
                </select>
              </div>
            </div>
            <div class="col-md-5 hidden_comp" style="display: none">
              <div class="form-group">
                <label for="company_id">Company Name</label>
                <select style="width: 100%;" id="company_id" name="company_id_certificate" class="form-control company-select2" data-placeholder="Select Company">
                </select>
              </div>
            </div>
          </div>


          @foreach($cource as $cour)
            <div id="{{ $cour->short_name }}_holder" class="hideme">
            <div class="row">
              <div class="col-md-12">
                <h3 class="page-header">{{ $cour->short_name }} <span style="font-size:14px;" class="text-muted">({{ $cour->name }})</span></h3>
              </div>
            </div>

            <div class="row hide_creation">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="{{ $cour->short_name }}_cno">Certificate No.</label>
                  <input type="text" id="{{ $cour->short_name }}_cno" name="{{ $cour->short_name }}_cno" placeholder="Certificate No" value="" class="form-control">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="{{ $cour->short_name }}_general">Makrs</label>
                  <div class="input-group">
                  <input type="text" id="{{ $cour->short_name }}_general" data-parent="{{ $cour->short_name }}" name="{{ $cour->short_name }}_general" placeholder="General" value="" class="form-control onlyint calmarks">
                  <span class="input-group-addon">%</span>
                </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="{{ $cour->short_name }}_specific">&nbsp;</label>
                  <div class="input-group">
                  <input type="text" id="{{ $cour->short_name }}_specific" data-parent="{{ $cour->short_name }}" name="{{ $cour->short_name }}_specific" placeholder="Specific" value="" class="form-control onlyint calmarks">
                    <span class="input-group-addon">%</span>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="{{ $cour->short_name }}_practical">&nbsp;</label>
                  <div class="input-group">
                  <input type="text" id="{{ $cour->short_name }}_practical" data-parent="{{ $cour->short_name }}" name="{{ $cour->short_name }}_practical" placeholder="Practical" value="" class="form-control onlyint calmarks">
                    <span class="input-group-addon">%</span>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="{{ $cour->short_name }}_average">&nbsp;</label>
                  <div class="input-group">
                  <input type="text" id="{{ $cour->short_name }}_average" name="{{ $cour->short_name }}_average" placeholder="Average" readonly value="" class="form-control onlyint">
                  <span class="input-group-addon">%</span>
                  </div>
                </div>
              </div>
            </div>


            <div class="row hide_creation">
              <div class="col-md-12">
                <button type="button" class="btn btn-primary btn-xs pull-right" onclick="addnewrow('{{ $cour->short_name }}')" style="margin-bottom:6px;"><i class="fa fa-plus"></i> Add New Fields</button>
              </div>
            </div>


            <div class="row hide_creation">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr class="bg-info">
                        <th width="20%">From Date</th>
                        <th width="20%">To Date</th>
                        <th width="60%"></th>
                      </tr>
                    </thead>

                    <tbody class="{{ $cour->short_name }}_tbody">
                    <tr>
                      <td>
                        <input type="text" id="{{ $cour->short_name }}_fromdate" name="{{ $cour->short_name }}_fromdate[]"  class="form-control datepicker">
                      </td>
                      <td>
                        <input type="text" id="{{ $cour->short_name }}_todate" name="{{ $cour->short_name }}_todate[]"  class="form-control datepicker">
                      </td>
                      <td>

                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            </div>
          @endforeach


          <div class="form-devider"></div>
          <div class="form-group">
          <button type="submit" class="btn btn-primary" onclick="return val_submit();"><i class="fa fa-plus"></i> Save</button>
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

@include('admin.v1.enrollment.add_company')
@include('admin.v1.enrollment.reference',['comp_ids'=>$company_list,"enrollment_list"=>$enrollment_list,"comp"=>$comp])

<script type="text/javascript">
  function addnewrow(cname) {
    var html = '';
    html += '<tr>';
    html += '<td>';
    html += '<input type="text" id="'+cname+'_fromdate" name="'+cname+'_fromdate[]" value="{{ date('d-m-Y',strtotime("now")) }}"  class="form-control datepicker">';
    html += '</td>';
    html += '<td>';
    html += '<input type="text" id="'+cname+'_todate" name="'+cname+'_todate[]" value="{{ date('d-m-Y',strtotime("+5 year")) }}"  class="form-control datepicker">';
    html += '</td>';
    html += '<td>';
    html += '<button type="button" class="btn btn-danger btn-xs removeparent"><i class="fa fa-trash"></i> Remove</button>';
    html += '</td>';
    html += '</tr>';

    $("."+cname+"_tbody").append(html);
    initdatepicker(false);
  }

function val_submit() {
  if($("#front_fname").val() == "") {
      toastr.error("Please, Enter First Name of Front");
      $("#front_fname").focus();
      return false;
  }
  if($("#front_mname").val() == "") {
    toastr.error("Please, Enter Middle Name of Front");
    $("#front_mname").focus();
    return false;
  }
  if($("#front_lname").val() == "") {
    toastr.error("Please, Enter Last Name of Front");
    $("#front_lname").focus();
    return false;
  }
  if($("#back_fname").val() == "") {
    toastr.error("Please, Enter First Name of Back");
    $("#back_fname").focus();
    return false;
  }
  if($("#back_mname").val() == "") {
    toastr.error("Please, Enter Middle Name of Back");
    $("#back_mname").focus();
    return false;
  }
  if($("#back_lname").val() == "") {
    toastr.error("Please, Enter Last Name of Back");
    $("#back_lname").focus();
    return false;
  }
  if($("#father_fname").val() == "") {
    toastr.error("Please, Enter Father First Name");
    $("#father_fname").focus();
    return false;
  }
  if($("#father_lname").val() == "") {
    toastr.error("Please, Enter Father Last Name");
    $("#father_lname").focus();
    return false;
  }
  if($("#education").val() == "") {
    toastr.error("Please, Select atleast one education qualification");
    $("#education").focus();
    return false;
  }
  if($("#exp_hour").val() == "") {
    toastr.error("Please, Enter Experience");
    $("#exp_hour").focus();
    return false;
  }
  if($("#place").val() == "") {
    toastr.error("Please, Enter Place Name");
    $("#place").focus();
    return false;
  }
  if($("#reg_date").val() == "") {
    toastr.error("Please, Enter Registration Date");
    $("#reg_date").focus();
    return false;
  }
}

$(document).ready(function(e){
  jcropratio = 0;
  jcropresize = true;
  initdatepicker(true);

  $('#paid_fees').on('propertychange input', function (e) {
    var total_fees = parseInt($("#total_fees").val());
    var paid_fees = parseInt($(this).val());
    var pending_fees = total_fees - paid_fees;
    $("#pending_fees").val(pending_fees);
  });

});

$(document).on("change","#front_greet",function(e) {
  $("#back_greet").val($(this).val()).trigger('chnage');
});
$(document).on("keyup","#front_fname",function(e) {
      $("#back_fname").val($(this).val());
});
$(document).on("keyup","#front_mname",function(e) {
  $("#back_mname").val($(this).val());
  $("#father_fname").val($(this).val());
});
$(document).on("keyup","#front_lname",function(e) {
  $("#back_lname").val($(this).val());
  $("#father_lname").val($(this).val());
});

$(document).on("change","#sponsor",function(e) {
  if($(this).val() == "company") {
    $(".hidden_comp").show();
  } else {
    $(".hidden_comp").hide();
  }
});

$(document).on("click",".removeparent",function() {
  var conf = confirm("Are you sure you want to remove this row?");
  if(conf) {
    $(this).parent("td").parent("tr").remove();
  }
});

$(document).on("change",".calmarks",function(e){
  var attrname = $(this).attr('data-parent');
  var general_marks = $("#"+attrname+"_general").val() != "" ? $("#"+attrname+"_general").val() : 0;
  var specific_marks = $("#"+attrname+"_specific").val() != "" ? $("#"+attrname+"_specific").val() : 0;
  var practical_marks = $("#"+attrname+"_practical").val() != "" ? $("#"+attrname+"_practical").val() : 0;

  var total_marks = parseFloat(general_marks) + parseFloat(specific_marks) + parseFloat(practical_marks);
  var avg_marks = parseFloat(total_marks) / 3;

  $("#"+attrname+"_average").val(avg_marks.toFixed(2));

  if(avg_marks.toFixed(2) >= 80 && avg_marks.toFixed(2) <= 100) {
    $("#"+attrname+"_average").css('border-color','#d2d6de');
  } else {
    $("#"+attrname+"_average").css('border-color','#f00');
  }

});

$(document).on("change",".agecalculate",function(e){
    var dob = $(this).val();
    $.ajax({
      url: '{{ route('age.calculator') }}',
      type: 'POST',
      dataType: 'json',
      data: '_token={{ csrf_token() }}&dob=' + dob,
      success: function (e) {
        $("#age").val(e)
      }
    });
});

$(document).on("change","#company_id",function(e) {
  if($(this).val() == 0) {
    $("#addCompanyModel").modal('show');
  }
});

$(document).on("change","#ref_id",function(e) {
  if($(this).val() == 0) {
    $("#addReferenceModel").modal('show');
  }
});

$(function () {
  $('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    // increaseArea: '20%' /* optional */
  });
});




$('input.certificates').on('ifChecked', function (event) {
  $(event.target).trigger('change');
  var fees = parseInt($(this).attr('data-fees'));
  var total = parseInt($("#total_fees").val());
  total = total + fees;
  $("#total_fees").val(total);

  //show block
  var cname = $(this).attr('data-parent');
  $("#"+cname+"_holder").show();
});

$('input.certificates').on('ifUnchecked', function (event) {
  $(event.target).trigger('change');
  var fees = parseInt($(this).attr('data-fees'));
  var total = parseInt($("#total_fees").val());
  total = total - fees;
  $("#total_fees").val(total);

  //hide block
  var cname = $(this).attr('data-parent');
  $("#"+cname+"_holder").hide();
});


//hide and show fields
$('input.chk_creation').on('ifChecked', function(event){
  if($(this).val() == 2) {
    $(".hide_creation").show();
  } else {
    $(".hide_creation").hide();
  }
});

$(document).on("click",".tglspecial",function(e){
  $(".special_container").stop().toggle();
});
</script>

@endsection
