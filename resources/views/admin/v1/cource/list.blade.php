@extends('admin.v1.layout.app', ['title' => 'Courses'])

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-certificate"></i> Courses
            <small>Manage Courses</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Courses</li>
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
                                    <th>Short Name</th>
                                    <th>Training Hours</th>
                                    <th>Fees</th>
                                    <th>Renewal Fees</th>
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


    <div id="EditModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="overlay"><div class="text"><i class="fa fa-refresh fa-spin"></i></div></div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Update Course</h4>
                </div>
                <form method="post" action="{{ route('course.update') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <input type="hidden" id="edit_unique_id" name="edit_unique_id" value="">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="edit_cource_name">Name</label>
                                    <input type="text" id="edit_cource_name" name="name" class="form-control" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_short_name">Short Name</label>
                                    <input type="text" id="edit_short_name" name="short_name" class="form-control" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_training_hour">Training Hours</label>
                                    <input type="text" id="edit_training_hour" name="trainning_hours" class="form-control onlyint" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_fees">Fees</label>
                                    <input type="text" id="edit_fees" name="fees" class="form-control onlyint" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_renewal_fees">Renewal Fees</label>
                                    <input type="text" id="edit_renewal_fees" name="renew_fees" class="form-control onlyint" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" onclick="return val_edit_type();"><i class="fa fa-upload"></i> Update</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

<script type="text/javascript">
function val_edit_type() {
    if($("#edit_unique_id").val() == "")
    {
        toastr.error("Unique id required");
        $("#edit_unique_id").focus();
        return false;
    }
    if($("#edit_cource_name").val() == "")
    {
        toastr.error("Please, Enter Course Name");
        $("#edit_cource_name").focus();
        return false;
    }
    if($("#edit_short_name").val() == "")
    {
        toastr.error("Please, Enter Short Name");
        $("#edit_short_name").focus();
        return false;
    }
    if($("#edit_training_hour").val() == "")
    {
        toastr.error("Please, Enter Training Hours");
        $("#edit_training_hour").focus();
        return false;
    }
    if($("#edit_fees").val() == "")
    {
        toastr.error("Please, Enter Fees");
        $("#edit_fees").focus();
        return false;
    }
    if($("#edit_renewal_fees").val() == "")
    {
        toastr.error("Please, Enter Renewal Fees");
        $("#edit_renewal_fees").focus();
        return false;
    }
}

function getformelement(id)
{
    modaloverlay("#EditModal","show");
    var jqxhr = $.ajax({
        url:"{{ route('course.info') }}",
        data:"id="+id
    })
        .done(function(e) {
            if(e)
            {
                //console.log(e);
                $("#edit_unique_id").val(e.id);
                $("#edit_cource_name").val(e.name);
                $("#edit_short_name").val(e.short_name);
                $("#edit_training_hour").val(e.trainning_hours);
                $("#edit_fees").val(e.fees);
                $("#edit_renewal_fees").val(e.renew_fees);
                modaloverlay("#EditModal","hide");
            }
            else
            {
                toastr.error('Ooops..! Something went wrong');
                modaloverlay("#EditModal","hide");
                $("#EditModal").modal('hide');
            }
        })
        .fail(function() {
            toastr.error('Ooops..! Something went wrong');
            modaloverlay("#EditModal","hide");
            $("#EditModal").modal('hide');
        });
}

var dtable_lang = {
    search: "_INPUT_",
    searchPlaceholder: "Search Courses"
};
$.extend( $.fn.dataTableExt.oStdClasses, {
    "sFilterInput": "form-control",
    "sLengthSelect": "form-control"
});

$(document).ready(function() {
    $('.dtable').DataTable({
        "dataSrc": "Data",
        "dom": '<"pull-left"f><"pull-right"l>tip',
        "language": dtable_lang,
        "bLengthChange": false,
        "order" : [],
        "processing" : true,
        "serverSide" : true,
        "bAutoWidth": false,
        "pageLength": 20,
        "ajax" : "{{ route('course.list.ajax') }}",
        "columns" : [
            {"data":"name","sWidth": "30%","orderable": false},
            {"data":"short_name","sWidth": "15%","orderable": false},
            {"data":"trainning_hours","sWidth": "15%","orderable": false},
            {"data":"fees","sWidth": "15%","orderable": false},
            {"data":"renew_fees","sWidth": "15%","orderable": false},
            {"data":"action","sWidth": "10%","searchable": false , "orderable": false}
        ]
    });
});
</script>

@endsection