@extends('admin.v1.layout.app', ['title' => 'Invoice'])

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-trello"></i> Invoice
            <small>Manage Invoices</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Invoice</li>
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
                                <th>#</th>
                                <th>Information</th>
                                <th>Charge For</th>
                                <th>Total</th>
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

<script type="text/javascript">

    var dtable_lang = {
        search: "_INPUT_",
        searchPlaceholder: "Search Invoice"
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
            "ajax" : "{{ route('invoice.list.ajax') }}",
            "columns" : [
                {"data":"invoice_info","sWidth": "10%","orderable": false},
                {"data":"user_info","sWidth": "48%","orderable": false},
                {"data":"charge_info","sWidth": "25%","orderable": false},
                {"data":"grand_total_info","sWidth": "7%","orderable": false},
                {"data":"action","sWidth": "10%","searchable": false , "orderable": false}
            ]
        });
    });
</script>

@endsection