@extends('admin.v1.layout.app', ['title' => 'Invoice'])

@section('content')
    <style type="text/css">
        .row-spliter {
            height: 1px;
            background: #e1e1e1;
            width: 100%;
            margin-top: 5px;
            margin-bottom: 5px;
        }
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-list-alt"></i> Invoice
            <small>Invoice List</small>
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
                    <div class="box-header">
                        <button type="button" class="btn btn-info btn-xs no-margin showfilter">
                            <i class="glyphicon glyphicon-filter"></i> Filter
                        </button>
                    </div>
                    <div class="box-body">
                        <div class="row filterdiv">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bill_no">Bill No</label>
                                    <input type="text" id="bill_no" name="bill_no" class="form-control" placeholder="Bill No">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_by">Name</label>
                                    <select style="width:100%;" id="filter_by" name="filter_by" class="form-control select2-bank" data-placeholder="Select Name">
                                        <option value="">Select Name</option>
                                        @foreach($party_list as $row)
                                            <option data-img="{{ $row->photo }}" value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="stock_no">Stock No</label>
                                    <select style="width: 100%;" id="stock_no" name="stock_no" class="form-control select2">
                                        <option value="">Select Stock</option>
                                        @foreach($stock_list as $row)
                                            <option value="{{ $row->id }}" data-pending="{{ $row->pending }}" data-unit="{{ $row->unit }}">{{ Admin::FormateStockItemID($row->id) }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="design_name">Design</label>
                                    <input type="text" id="design_name" name="design_name" class="form-control" placeholder="Design Name">
                                </div>
                            </div>

                            <!-- hidden date range -->
                            <input type="hidden" id="filterStart" onchange="changeDateRange()">
                            <input type="hidden" id="filterEnd">
                            <!-- hidden date range end -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date range button:</label>
                                    <button type="button"  class="btn btn-default pull-right daterange" style="width: 100%;">
                                        <span><i class="fa fa-calendar"></i> Date range picker</span>
                                        <i class="fa fa-caret-down"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="button" style="margin-top: 24px;"  class="btn btn-danger clearfilter">
                                        <i class="fa fa-times"></i> Clear Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered dtable fixtablemobile2">
                                <thead>
                                <tr>
                                    <th data-orderable="false">Date</th>
                                    <th data-orderable="false">Party</th>
                                    <th data-orderable="false">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                Stock
                                            </div>
                                            <div class="col-md-4 col-xs-4">
                                                Design
                                            </div>
                                            <div class="col-md-4 col-xs-4">
                                                Qty
                                            </div>
                                        </div>
                                    </th>
                                    <th data-orderable="false">Amount</th>
                                    <th data-orderable="false">Paid</th>
                                    <th data-orderable="false">Balance</th>
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
        <a href="{{ route('add.invoice') }}" class="btn btn-primary buy-now-btn">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </a>
        <div class="ripple"></div>
    </div>


    <script type="text/javascript">
        var dtable;
        var AjaxData = {};
        $(document).ready(function() {
            dtable = $('.dtable').DataTable({
                "dataSrc": "Data",
                "processing" : true,
                "serverSide" : true,
                "searching": false,
                "order" : [],
                "ajax" : {
                    "url": "{{ route('view.ajax.invoice') }}",
                    "data": function (d) {
                        return  $.extend(d, AjaxData);
                    }
                },
                "bLengthChange": false,
                "bAutoWidth": false,
                "columns" : [
                    {"data":"formated_date","sWidth": "8%"},
                    {"data":"formated_process","sWidth": "18%"},
                    {"data":"formate_stock","sWidth": "32%"},
                    {"data":"formated_grand_total","sWidth": "8%"},
                    {"data":"formated_payment_total","sWidth": "8%"},
                    {"data":"formated_balance_total","sWidth": "8%"},
                    {"data":"action", "searchable": false , "orderable": false,"sWidth": "18%"},
                ]
            });
        });

        $(document).on("click",".showfilter",function(e){
            $(".filterdiv").stop().slideToggle();
        });

        //setup before functions
        var typingTimer;                //timer identifier
        var doneTypingInterval = 1000;  //time in ms, 5 second for example
        var $input = $('#bill_no');
        var $input2 = $('#design_name');

        //on keyup, start the countdown
        $input.on('keyup', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });

        //on keydown, clear the countdown
        $input.on('keydown', function () {
            clearTimeout(typingTimer);
        });

        //user is "finished typing," do something
        function doneTyping () {
            //do something
            AjaxData.bill_no = $input.val();
            dtable.ajax.reload();
        }


        //on keyup, start the countdown
        $input2.on('keyup', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(doneTyping2, doneTypingInterval);
        });

        //on keydown, clear the countdown
        $input2.on('keydown', function () {
            clearTimeout(typingTimer);
        });

        //user is "finished typing," do something
        function doneTyping2 () {
            //do something
            AjaxData.design_name = $input2.val();
            dtable.ajax.reload();
        }

        function changeDateRange() {
            AjaxData.startdate = $("#filterStart").val();
            AjaxData.enddate = $("#filterEnd").val();
            dtable.ajax.reload();
        }



        $(document).on('change', '#filter_by', function() {
            AjaxData.filter_by = $(this).val();
            dtable.ajax.reload();
        });

        $(document).on('change', '#stock_no', function() {
            AjaxData.stock_no = $(this).val();
            dtable.ajax.reload();
        });

        $(document).on('click', '.clearfilter', function() {
            $("#filter_by").val('').trigger('change') ;
            $("#stock_no").val('').trigger('change') ;
            $("#bill_no").val('');
            $("#design_name").val('');
            $('.daterange span').html('<i class="fa fa-calendar"></i> Date range picker');
            AjaxData.filter_by = "";
            AjaxData.stock_no = "";
            AjaxData.bill_no = "";
            AjaxData.design_name = "";
            AjaxData.startdate = "";
            AjaxData.enddate = "";
            dtable.ajax.reload();
            $(".filterdiv").slideUp();
        });

    </script>

@endsection