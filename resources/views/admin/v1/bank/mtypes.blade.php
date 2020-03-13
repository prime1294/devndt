@extends('admin.v1.layout.app', ['title' => 'Bank List'])

@section('content')
<style type="text/css">
    #fbholdernew {
        height: auto;
    }
    #fbholdernew2 {
        height: auto;
        max-height: 100px;
    }
</style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-bank"></i> Bank List
            <small>Manage Bank List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Bank List</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Bank</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                    title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <form method="post" action="{{ route('bank.types.register') }}" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <div class="fbpickermain">
                                <div class=fbpiker>
                                    <span class="fbremove"><i class="fa fa-times"></i></span>
                                    <img id="fbholdernew" data-default="{{ asset('placeholder.jpg') }}" src="{{ asset('placeholder.jpg') }}"  onclick="triggerfile('fbholdernew','fbinputtxt','image/logo/','.jpg,.png,.jpeg','box')">
                                </div>
                                <input id="fbinputtxt" name="fbinputtxt" class='fbinputtxt' value="image/logo/sbi.png" type="hidden" >
                            </div>
                            <div class="form-group">
                                <label for="type_name">Bank Name</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="text" id="type_name" name="type_name" class="form-control" value="">
                                </div>
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
                    <div class="box-header with-border">
                        <h3 class="box-title">Bank List</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                    title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered dtable">
                                <thead>
                                <tr>
                                    <th>Icon</th>
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
                    <h4 class="modal-title">Update Bank List</h4>
                </div>
                <form method="post" action="{{ route('bank.types.update') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <input type="hidden" id="edit_unique_id" name="edit_unique_id" value="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fbpickermain">
                                    <div class=fbpiker>
                                        <span class="fbremove"><i class="fa fa-times"></i></span>
                                        <img id="fbholdernew2" data-default="{{ asset('placeholder.jpg') }}" src="{{ asset('placeholder.jpg') }}"  onclick="triggerfile('fbholdernew2','edit_fbinputtxt','image/logo/','.jpg,.png,.jpeg','box')">
                                    </div>
                                    <input id="edit_fbinputtxt" name="edit_fbinputtxt" class='edit_fbinputtxt' value="image/logo/sbi.png" type="hidden" >
                                </div>
                                <div class="form-group">
                                    <label for="edit_type_name">Bank Name</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input type="text" id="edit_type_name" name="edit_type_name" class="form-control" value="">
                                    </div>
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

            if($("#edit_type_name").val() == "")
            {
                toastr.error("Please, Enter Bank Name");
                $("#edit_type_name").focus();
                return false;
            }
        }
        function val_add_type()
        {
            if($("#type_name").val() == "")
            {
                toastr.error("Please, Enter Bank Name");
                $("#type_name").focus();
                return false;
            }
        }

        function getformelement(id)
        {
            modaloverlay("#EditModal","show");
            var jqxhr = $.ajax({
                url:"{{ route('bank.types.info') }}",
                data:"id="+id
            })
                .done(function(e) {
                    if(e)
                    {
                        //console.log(e);
                        $("#edit_unique_id").val(e.id);
                        $("#edit_type_name").val(e.name);
                        $("#edit_fbinputtxt").val(e.icon);
                        $("#fbholdernew2").attr('src','{{ asset('/')}}'+'/'+e.icon);
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

        $(document).on("change",".status_checkbox",function(e){
            // alert('hello');
            var status_txt = $(this).prop('checked') ? "Active" : "Deactive";
            var default_status = $(this).prop('checked') ? "off" : "on";
            var status = $(this).prop('checked') ? 1 : 0;
            var id = $(this).attr('data-id');

            var jqxhr = $.ajax({
                url:'{{ route("manufacturer.types.activation") }}',
                data:'id='+id+"&status="+status+"&_token={{ csrf_token() }}",
                type:'POST'
            })
                .done(function(e) {
                    if(e)
                    {
                        toastr.success("Area "+status_txt+" Successfully.");
                    }
                    else
                    {
                        toastr.error('Ooops..! Something went wrong');
                    }
                })
                .fail(function() {
                    toastr.error('Ooops..! Something went wrong');
                });
        });

        var dtable_lang = {
            search: "_INPUT_",
            searchPlaceholder: "Search Bank List"
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
                "processing" : true,
                "order" : [],
                "serverSide" : true,
                "bLengthChange": false,
                "ajax" : "{{ route('bank.types.get') }}",
                "columns" : [
                    {"data":"formated_image","orderable": false},
                    {"data":"name","orderable": false},
                    {"data":"action","searchable": false , "orderable": false}
                ],
                "initComplete": function(settings, json) {
                    // alert( 'DataTables has finished its initialisation.' );
                    $('#toggle-demo').bootstrapToggle();
                },
                "fnDrawCallback": function() {
                    // $('#toggle-demo').bootstrapToggle();
                    // $('.make-switch').bootstrapSwitch();
                },
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(e) {
            jcropratio = 0;
            jcropresize = true;
        });
    </script>
@endsection