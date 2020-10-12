@extends('admin.v1.layout.app', ['title' => 'Reference'])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-street-view"></i> Reference
        <small>Manage Reference</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reference</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered dtable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Company</th>
                                <th>Address</th>
                                <th>Action</th>
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
    <a data-toggle="modal" data-target="#addReferenceModel" class="btn btn-primary buy-now-btn">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </a>
    <div class="ripple"></div>
</div>

@include('admin.v1.enrollment.reference',['comp_ids'=>$company_list,"enrollment_list"=>$enrollment_list,"comp"=>$comp])
@include('admin.v1.enrollment.edit_reference',['comp_ids'=>$company_list,"enrollment_list"=>$enrollment_list,"comp"=>$comp])

<script type="text/javascript">
function getformelement(id) {
    $.ajax({
        url:'{{ route('ref.info') }}',
        type:'POST',
        dataType: 'json',
        data: '_token={{ csrf_token() }}&id='+id,
        success:function(e) {
            $("#edit_ref_id").val(e.id);
            $("#edit_ref_dev_ndt_id").val(e.ndt_id).trigger("change");
            $("#edit_ref_fname").val(e.fname);
            $("#edit_ref_mname").val(e.mname);
            $("#edit_ref_lname").val(e.lname);
            $("#edit_ref_company_contact").val(e.contact);
            $("#edit_ref_company_email").val(e.email);
            $("#edit_ref_designation").val(e.designation).trigger("change");
            $("#edit_ref_company_no").val(e.company_id).trigger("change");
            $("#edit_ref_company_name").val(e.company_name);
            $("#edit_ref_company_address").val(e.address);
            $("#edit_ref_remarks").val(e.remarks);


            $("#editReferenceModel").modal('show');
        }
    });
}

var dtable_lang = {
    search: "_INPUT_",
    searchPlaceholder: "Search Reference"
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
        "bLengthChange": false,
        "order" : [],
        "processing" : true,
        "serverSide" : true,
        "bAutoWidth": false,
        "pageLength": 20,
        "ajax" : "{{ route('reference.list.ajax') }}",
        "columns" : [
            {"data":"user_info","sWidth": "25%","orderable": false},
            {"data":"contact_info","sWidth": "15%","orderable": false},
            {"data":"company_info","sWidth": "25%","orderable": false},
            {"data":"address_info","sWidth": "25%","orderable": false},
            {"data":"action","sWidth": "10%","searchable": false , "orderable": false}
        ]
    });
});

$(document).on("click",".buy-now-btn",function(e){
    $("#add_ref_form")[0].reset();
    $("#add_ref_designation").val('').trigger("change");
    $("#add_ref_company_no").val('').trigger("change");
});

$(document).on("change",".ref_fetch_company",function(e){
    var id = $(this).val();
    $.ajax({
        url: '{{ route('company.info') }}',
        type: 'POST',
        dataType: 'json',
        data: '_token={{ csrf_token() }}&id=' + id,
        success: function (e) {
            $("#add_ref_company_name").val(e.company_name)
            $("#edit_ref_company_name").val(e.company_name)
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
            $("#add_ref_fname").val(e.front_fname)
            $("#add_ref_mname").val(e.front_mname)
            $("#add_ref_lname").val(e.front_lname)
            $("#add_ref_company_contact").val(e.contact)
            $("#add_ref_company_email").val(e.email)
            $("#add_ref_designation").val(e.designation).trigger('change');

            $("#edit_ref_fname").val(e.front_fname)
            $("#edit_ref_mname").val(e.front_mname)
            $("#edit_ref_lname").val(e.front_lname)
            $("#edit_ref_company_contact").val(e.contact)
            $("#edit_ref_company_email").val(e.email)
            $("#edit_ref_designation").val(e.designation).trigger('change');
        }
    });
});
</script>

@endsection