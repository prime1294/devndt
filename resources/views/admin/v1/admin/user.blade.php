@extends('admin.v1.layout.app', ['title' => 'Users'])

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> Users
            <small>Manage Users</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Users</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">User List</h3>

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
                                    <th>Personal Info</th>
                                    <th>Contact</th>
                                    <th>Account Info</th>
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
        <a href="{{ route('user.new') }}" class="btn btn-primary buy-now-btn">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </a>
        <div class="ripple"></div>
    </div>

    <script type="text/javascript">

        var dtable_lang = {
            search: "_INPUT_",
            searchPlaceholder: "Search User"
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
                "ajax" : "{{ route('user.list.ajax') }}",
                "columns" : [
                    {"data":"personal_info","orderable": false},
                    {"data":"contact_info","orderable": false},
                    {"data":"account_info","orderable": false},
                    {"data":"action","searchable": false , "orderable": false}
                ],
                "initComplete": function(settings, json) {
                },
                "fnDrawCallback": function() {
                    $('.status_checkbox').bootstrapToggle();
                },
            });
        });


        $(document).on("change",".status_checkbox",function(e){
            // alert('hello');
            var status_txt = $(this).prop('checked') ? "Active" : "Deactive";
            var default_status = $(this).prop('checked') ? "off" : "on";
            var status = $(this).prop('checked') ? 1 : 0;
            var id = $(this).attr('data-id');

            var jqxhr = $.ajax({
                url:'{{ route("user.activation") }}',
                data:'id='+id+"&status="+status+"&_token={{ csrf_token() }}",
                type:'POST'
            })
                .done(function(e) {
                    if(e)
                    {
                        toastr.success("User "+status_txt+" Successfully.");
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
    </script>

@endsection