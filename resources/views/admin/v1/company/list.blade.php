@extends('admin.v1.layout.app', ['title' => 'Company'])

@section('content')
<style type="text/css">
.select2-container .select2-selection--single .select2-selection__rendered {
  padding-left: 0px !important;
}
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-university"></i> Company
    <small>Company List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Company</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered dtable tbldblclick fixtablemobile">
            <thead>
            <tr>
              <th data-orderable="false">Company Info</th>
              <th data-orderable="false">Company Contact</th>
              <th data-orderable="false">Company Address</th>
              <th data-orderable="false">Contact Person</th>
              <th data-orderable="false">Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    </div>
  </div>
</section>


<div class="buy-now">
  <a data-toggle="modal" data-target="#addCompanyModel" class="btn btn-primary buy-now-btn">
    <i class="fa fa-plus" aria-hidden="true"></i>
  </a>
  <div class="ripple"></div>
</div>

@include('admin.v1.enrollment.add_company',["comp"=>$add_company])
@include('admin.v1.enrollment.edit_company',["comp"=>$add_company])

<script type="text/javascript">
//edit
function editcompany(id) {
  $.ajax({
    url:'{{ route('company.info') }}',
    type:'POST',
    dataType: 'json',
    data: '_token={{ csrf_token() }}&id='+id,
    success:function(e) {
        $("#edit_ac_no").val(e.id);
        $("#edit_ac_comp_name").val(e.company_name);
        $("#edit_ac_comp_type").val(e.company_type).trigger('change');
        $("#edit_ac_comp_address").val(e.address);
        $("#edit_ac_comp_city").val(e.city);
        $("#edit_ac_comp_district").val(e.district);
        $("#edit_ac_comp_state").val(e.state);
        $("#edit_ac_comp_pincode").val(e.pincode);
        $("#edit_ac_comp_weekoff").val(e.week_off);
        $("#edit_ac_comp_contact").val(e.mobile);
        $("#edit_ac_comp_email").val(e.email);
        $("#edit_ac_comp_website").val(e.website);
        $("#edit_ac_person_greet").val(e.person_greet);
        $("#edit_ac_person_fname").val(e.person_fname);
        $("#edit_ac_person_mname").val(e.person_mname);
        $("#edit_ac_person_lname").val(e.person_lname);
        $("#edit_ac_person_designation").val(e.person_designation).trigger('change');
        $("#edit_ac_person_contact").val(e.person_contact);
        $("#edit_ac_person_email").val(e.person_email);


        $("#editCompanyModel").modal('show');
    }
  });
}

//submit button
$(document).ready(function (e) {
  $(document).on("click","#edit_submitcompany",function(red){
    red.preventDefault();

    if($("#edit_ac_comp_name").val() == "") {
      toastr.error("Please, Enter Company Name");
      $("#edit_ac_comp_name").focus();
      return false;
    }

    var formarr = $("#edit_company_form").serialize();
    $.ajax({
      url:'{{ route('company.edit') }}',
      type:'POST',
      data:formarr,
      success:function(e) {
        if(e.status == "true") {
          toastr.success(e.message);
          $("#editCompanyModel").modal('hide');
          table.ajax.reload();
        } else {
          toastr.error(e.message);
          return false;
        }
      }
    });
  });
});

//datatable
var selected = [];
var dtable_lang = {
  search: "_INPUT_",
  searchPlaceholder: "Search Company"
};
$.extend( $.fn.dataTableExt.oStdClasses, {
  "sFilterInput": "form-control",
  "sLengthSelect": "form-control"
});
$(document).ready(function() {

  table = $('.dtable').DataTable({
    "dataSrc": "Data",
    "dom": '<"pull-left"f><"pull-right"l>tip',
    "language": dtable_lang,
    "processing" : true,
    "serverSide" : true,
    "order" : [],
    "ajax" : "{{ route('company.list.ajax') }}",
    "bLengthChange": false,
    "bAutoWidth": false,
    // "pageLength": 100,
    "columns" : [
      {"data":"company_info","sWidth": "25%"},
      {"data":"company_contact","sWidth": "15%"},
      {"data":"company_address","sWidth": "25%"},
      {"data":"contact_person","sWidth": "20%"},
      {"data":"action","sWidth": "15%", "searchable": false , "orderable": false},
    ],
    "fnDrawCallback": function() {

    },
    "rowCallback": function( row, data ) {
      if ( $.inArray(data.check_id, selected) !== -1 ) {
        $(row).find("#"+data.check_id).attr('checked','checked');
        // console.log($(row).find("#"+data.check_id));
        // console.log(data.check_id);
      }
    }
  });
});

$(document).on("change",".chkbox",function(e){
  var id = this.id;
  var index = $.inArray(id, selected);

  if ( index === -1 ) {
    selected.push( id );
  } else {
    selected.splice( index, 1 );
  }
});
</script>

@endsection
