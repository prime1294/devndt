@extends('admin.v1.layout.app', ['title' => 'Designation'])

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-address-card"></i> Designation
            <small>Manage Designation</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Designation</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-body">
                        <form method="post" action="{{ route('designation.register') }}" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label for="cource_name">Name</label>
                                <input type="text" id="cource_name" name="name" class="form-control" value="">
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
                <form method="post" action="{{ route('designation.update') }}" enctype="multipart/form-data">
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
        toastr.error("Please, Enter Designation Name");
        $("#cource_name").focus();
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
        toastr.error("Please, Enter Designation Name");
        $("#edit_cource_name").focus();
        return false;
    }
}

function getformelement(id)
{
    modaloverlay("#EditModal","show");
    var jqxhr = $.ajax({
        url:"{{ route('designation.info') }}",
        data:"id="+id
    })
        .done(function(e) {
            if(e)
            {
                //console.log(e);
                $("#edit_unique_id").val(e.id);
                $("#edit_cource_name").val(e.name);
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
    searchPlaceholder: "Search Designation"
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
        "ajax" : "{{ route('designation.list.ajax') }}",
        "columns" : [
            {"data":"name","sWidth": "80%","orderable": false},
            {"data":"action","sWidth": "20%","searchable": false , "orderable": false}
        ]
    });
});
</script>

@endsection