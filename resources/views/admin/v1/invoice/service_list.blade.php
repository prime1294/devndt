@extends('admin.v1.layout.app', ['title' => $invoice_print_id])

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-trello"></i> {{ $invoice_print_id }}
            <small>Manage Services</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('invoice')}}">Invoice</a></li>
            <li class="active">Services</li>
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
                                <th>Charge For</th>
                                <th>Quotation Number</th>
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

<div class="buy-now">
    <a href="{{ route('invoice') }}" class="btn btn-primary buy-now-btn">
        <i class="fa fa-arrow-left" aria-hidden="true"></i>
    </a>
    <div class="ripple"></div>
</div>

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
            "searching": false,
            "pageLength": 20,
            "ajax" : "{{ route('service.list.ajax',$info->id) }}",
            "columns" : [
                {"data":"charge_info","sWidth": "35%","orderable": false},
                {"data":"quote_info","sWidth": "30%","orderable": false},
                {"data":"total_info","sWidth": "20%","orderable": false},
                {"data":"action","sWidth": "15%","searchable": false , "orderable": false}
            ]
        });
    });
</script>

@endsection