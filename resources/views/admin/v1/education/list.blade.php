@extends('admin.v1.layout.app', ['title' => 'Education'])

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-graduation-cap"></i> Education
            <small>Manage Education</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Education</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-body">
                        <form method="post" action="{{ route('education.register') }}" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label for="cource_name">Name</label>
                                <input type="text" id="cource_name" name="name" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="full_name">Full Name</label>
                                <input type="text" id="full_name" name="full_name" class="form-control" value="">
                            </div>
                            <div class="form-devider"></div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" onclick="return val_add_type();"><i class="fa fa-plus"></i> Add New</button>
                                <button type="reset" class="btn btn-danger"><i class="fa fa-trash"></i> Clear</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered dtable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Full Name</th>
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
                    <h4 class="modal-title">Update Designation</h4>
                </div>
                <form method="post" action="{{ route('education.update') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <input type="hidden" id="edit_unique_id" name="edit_unique_id" value="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="edit_cource_name">Name</label>
                                    <input type="text" id="edit_cource_name" name="name" class="form-control" value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="edit_full_name">Full Name</label>
                                    <input type="text" id="edit_full_name" name="full_name" class="form-control" value="">
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
function val_add_type() {
    if($("#cource_name").val() == "")
    {
        toastr.error("Please, Enter Education Name");
        $("#cource_name").focus();
        return false;
    }
    if($("#full_name").val() == "")
    {
        toastr.error("Please, Enter Education Full Name");
        $("#full_name").focus();
        return false;
    }
}
function val_edit_type() {
    if($("#edit_unique_id").val() == "")
    {
        toastr.error("Unique id required");
        $("#edit_unique_id").focus();
        return false;
    }
    if($("#edit_cource_name").val() == "")
    {
        toastr.error("Please, Enter Education Name");
        $("#edit_cource_name").focus();
        return false;
    }
    if($("#edit_full_name").val() == "")
    {
        toastr.error("Please, Enter Education Full Name");
        $("#edit_full_name").focus();
        return false;
    }
}

function getformelement(id)
{
    modaloverlay("#EditModal","show");
    var jqxhr = $.ajax({
        url:"{{ route('education.info') }}",
        data:"id="+id
    })
        .done(function(e) {
            if(e)
            {
                //console.log(e);
                $("#edit_unique_id").val(e.id);
                $("#edit_cource_name").val(e.name);
                $("#edit_full_name").val(e.full_name);
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
    searchPlaceholder: "Search Education"
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
        "ajax" : "{{ route('education.list.ajax') }}",
        "columns" : [
            {"data":"name","sWidth": "35%","orderable": false},
            {"data":"full_name","sWidth": "50%","orderable": false},
            {"data":"action","sWidth": "15%","searchable": false , "orderable": false}
        ]
    });
});
</script>

@endsection