@extends('admin.v1.layout.app', ['title' => 'New Service'])

@section('content')
<style type="text/css">
    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 0px !important;
    }
    .enrollment_title {
        font-size: 28px;
        font-weight: bolder;
        margin-bottom: 10px;
    }
    .icheckbox_square-blue {
        /* margin-left: -19px; */
        margin-right: 3px;
    }
    .lbl1 {
        margin-left: 15px;
    }
    .lbl1:first-child {
        margin-left: 0px;
    }
    .hideme {
        display: none;
    }
    .editorto , .editordiv, .editorconsultant, .editorwritten {
        display: none;
    }
</style>
{!!Html::style('/public/admin/theme1/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')!!}
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-trello"></i> New Service for {{ $invoice_print_id }}
        <small>Add New Service</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('invoice') }}"><i class="fa fa-dashboard"></i> Invoice</a></li>
        <li class="active">Add Services</li>

    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <form method="post" action="{{ route('invoice.register.service',$info->id) }}" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="quotation_no">Quotation Number</label>
                                    <input type="text" id="quotation_no" name="quotation_no" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="charge_for">Charge For</label>
                                    <select  style="width: 100%;" id="charge_for" name="charge_for" class="form-control select2">
                                        <?php
                                        $invoice_types = Admin::getInvoiceType();
                                        foreach($invoice_types as $key=>$row) {
                                        ?>
                                        <option value="{{ $key }}">{{ $row }}</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="row editorcource">
                            <div class="col-md-12">
                                <h3 class="page-header">Certificate Details</h3>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr class="bg-primary">
                                                <td width="40%">Name</td>
                                                <td width="20%" class="text-center">Quantity</td>
                                                <td width="20%" class="text-center">Fees</td>
                                                <td width="20%" class="text-center">Total</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($cource as $row)
                                            <tr data-parent="{{ $row->short_name }}">
                                                <td>
                                                    <label>
                                                    <input type="checkbox" id="chk_{{ $row->short_name }}" name="chk_{{ $row->short_name }}" data-parent="{{ $row->short_name }}" class="form-control checkmytotal"> &nbsp;
                                                    <span>{{ $row->short_name }}</span> ({{ $row->name }})
                                                    </label>
                                                </td>
                                                <td>
                                                    <input type="text" id="qty_{{ $row->short_name }}" name="qty_{{ $row->short_name }}" value="1" class="form-control calculateme onlyint">
                                                </td>
                                                <td>
                                                    <input type="text" id="fees_{{ $row->short_name }}" name="fees_{{ $row->short_name }}" value="{{ intval($row->fees) }}" class="form-control calculateme onlyint">
                                                </td>
                                                <td>
                                                    <input type="text" id="subtotal_{{ $row->short_name }}" name="subtotal_{{ $row->short_name }}" readonly value="{{ $row->fees }}" placeholder="Total" class="form-control subtot onlyint">
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row editorto">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="vision_certificate_fees">Certificate Fees</label>
                                    <input type="text" id="vision_certificate_fees" name="vision_certificate_fees" class="form-control onlyint" value="300" placeholder="Fees Amount">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="certificate_to">Certificate to</label>
                                    <select  style="width: 100%;" id="certificate_to" name="certificate_to[]" multiple="multiple" class="form-control select2">
                                        @foreach($enrollment_list as $row)
                                            <option value="{{ $row->id }}">{{ $row->id }} - {{ $row->front_fname.' '.$row->front_lname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="row editorwritten">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="written_fees">Written Practice Fees</label>
                                    <input type="text" id="written_fees" name="written_fees" class="form-control onlyint" value="5000" placeholder="Fees Amount">
                                </div>
                            </div>
                        </div>

                        <div class="row editorconsultant">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="const_from">Consultancy From</label>
                                    <input type="text" id="const_from" name="const_from" onchange="calculateDays()" class="form-control datepicker" placeholder="From Date">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="const_to">Consultancy To</label>
                                    <input type="text" id="const_to" name="const_to" onchange="calculateDays()" class="form-control datepicker" placeholder="To Date">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="const_days">Number of Days</label>
                                    <input type="text" id="const_days" name="const_days" class="form-control onlyint calculateconsultancy" value="1" placeholder="Days">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="const_charge">Charges Per Day</label>
                                    <input type="text" id="const_charge" name="const_charge" class="form-control onlyint calculateconsultancy" value="0" placeholder="Charges per Day">
                                </div>
                            </div>
                        </div>


                        <div class="row editordiv">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="about_invoice">Write Something</label>
                                <textarea class="form-control custom-editor" id="about_invoice" name="about_invoice" rows="5"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-2 text-right" style="padding-top:5px;"><b>Grand Total:</b></div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input class="form-control" id="grand_total" readonly value="0.00" name="grand_total" />
                                </div>
                            </div>
                        </div>

                        <div class="form-devider"></div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" onclick="return check_val();"><i class="fa fa-plus"></i> Save</button>
                        </div>
                    </form>
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
    $(document).ready(function(){
       initdatepicker(true);
        $('.custom-editor').wysihtml5({
            toolbar: {
                "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
                "emphasis": true, //Italics, bold, etc. Default true
                "blockquote": false,
                "lists": false, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
                "html": false, //Button which allows you to edit the generated HTML. Default false
                "link": false, //Button to insert a link. Default true
                "image": false, //Button to insert an image. Default true,
                "color": false //Button to change color of font
            },
            showToolbarAfterInit: true,
        });
    });

    function calculateDays() {
        var start = $('#const_from').datepicker('getDate');
        var end   = $('#const_to').datepicker('getDate');
        var days   = (end - start)/1000/60/60/24;
        $("#const_days").val(parseInt( Math.round(days)) + 1)
        $('.calculateconsultancy').trigger('change');
    }

    //written_fees
    $('#written_fees').on('propertychange change input', function (e) {
        var written_fees = $(this).val();
        $("#grand_total").val(parseInt(written_fees).toFixed(2));
    });


    $('.calculateconsultancy').on('propertychange change input', function (e) {
        var days = $("#const_days").val();
        var fees = $("#const_charge").val();
        var total = parseInt(days) * parseInt(fees);
        $("#grand_total").val(total.toFixed(2));
    });

    //vision_certificate_fees
    $('#vision_certificate_fees').on('propertychange change input', function (e) {
        var uldiv = $('#certificate_to').siblings('span.select2').find('ul')
        var count = uldiv.find('li').length - 1;
        var vision_certificate_fees = $(this).val();
        var total = parseInt(count) * parseInt(vision_certificate_fees);
        $("#grand_total").val(total);
    });

    $('#certificate_to').on('select2:close', function (evt) {
        var uldiv = $(this).siblings('span.select2').find('ul')
        var count = uldiv.find('li').length - 1;
        var vision_certificate_fees = $("#vision_certificate_fees").val();
        var total = parseInt(count) * parseInt(vision_certificate_fees);
        $("#grand_total").val(total);
    });

    function check_val() {
        if($("#grand_total").val() == 0) {
            toastr.error("Please, Service Total must be grater then 0");
            $("#grand_total").focus();
            return false;
        }
    }

    function recalculatetotal() {
        var grand_total = 0;
        $('input[type=checkbox].checkmytotal').each(function () {
            if(this.checked) {
                var short_name = $(this).attr('data-parent');
                grand_total += parseInt($("#subtotal_"+short_name).val());
            }
        });
        $("#grand_total").val(grand_total.toFixed(2));
    }

    $('.calculateme').on('propertychange input', function (e) {
        var short_name = $(this).parent('td').parent('tr').attr('data-parent');
        var qty = $("#qty_"+short_name).val();
        var fees = $("#fees_"+short_name).val();
        var total = parseInt(qty) * parseInt(fees);
        $("#subtotal_"+short_name).val(total.toFixed(2));
        recalculatetotal();
    });

    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            // increaseArea: '20%' /* optional */
        });
    });

    function resetform() {
        $('.checkmytotal').iCheck('uncheck');
        $('#certificate_to').val("").trigger('change');
        $('#const_charge').val(0).trigger('change');
        //$("#about_invoice").wysihtml5().setValue("jefdfdf");
        initdatepicker(true);
        $("#grand_total").val("0.00");
    }

    $(document).on("change","#charge_for",function(e){

        resetform();

        var x = parseInt($(this).val());
        var input1 = $(".editorcource");
        var input2 = $(".editorto");
        var input3 = $(".editordiv");
        var input4 = $(".editorconsultant");
        var input5 = $(".editorwritten");
        switch (x) {
            case 1:
                input1.show();
                input2.hide();
                input3.hide();
                input4.hide();
                input5.hide();
                break;
            case 2:
                input1.show();
                input2.hide();
                input3.hide();
                input4.hide();
                input5.hide();
                break;
            case 3:
                input1.show();
                input2.hide();
                input3.hide();
                input4.hide();
                input5.hide();
                break;
            case 4:
                input1.hide();
                input2.show();
                input3.hide();
                input4.hide();
                input5.hide();
                break;
            case 5:
                input1.hide();
                input2.hide();
                input3.hide();
                input4.show();
                input5.hide();
                break;
            case 6:
                input1.hide();
                input2.hide();
                input3.show();
                input4.hide();
                input5.hide();
                break;
            case 7:
                input1.hide();
                input2.hide();
                input3.show();
                input4.hide();
                input5.hide();
                break;
            case 8:
                input1.hide();
                input2.hide();
                input3.hide();
                input4.hide();
                input5.show();
                var written_fees = $("#written_fees").val();
                $("#grand_total").val(parseInt(written_fees).toFixed(2));
                break;
            default:
                input1.hide();
                input2.hide();
                input3.show();
                input4.hide();
                input5.hide();
                break;
        }
    });


    $(document).on("change","#company_id",function(e){
        var id = $(this).val();
        if(id != "") {
            $.ajax({
                url: '{{ route('company.info') }}',
                type: 'POST',
                dataType: 'json',
                data: '_token={{ csrf_token() }}&id=' + id,
                success: function (e) {
                    $("#enrollment_id").val('').trigger('change');
                    var full_address = e.address + ' ' + e.city + ' ' + e.district + ' ' + e.state + ' - ' + e.pincode;
                    $("#invoice_name").val(e.company_name);
                    $("#invoice_address").val(full_address);
                }
            });
        }
    });


    $(document).on("change","#enrollment_id",function(e){
        var id = $(this).val();
        if(id != "") {
            $.ajax({
                url: '{{ route('enrollment.info') }}',
                type: 'POST',
                dataType: 'json',
                data: '_token={{ csrf_token() }}&id=' + id,
                success: function (e) {
                    $("#company_id").val('').trigger('change');
                    var front_greet = e.front_greet;
                    front_greet = front_greet.toLowerCase();
                    front_greet = front_greet.charAt(0).toUpperCase() + front_greet.slice(1);
                    var full_name = front_greet + '. ' + e.front_fname + ' ' + e.front_lname;
                    var full_address = e.address + ' ' + e.city + ' ' + e.district + ' ' + e.state + ' - ' + e.pincode;
                    $("#invoice_name").val(full_name);
                    $("#invoice_address").val(full_address);
                }
            });
        }
    });

    $('input.checkmytotal').on('ifChecked', function (event) {
        var short_name = $(this).attr('data-parent');
        var grand_total = $("#grand_total").val();
        var additional_total = $("#subtotal_"+short_name).val();
        var final_total = parseInt(grand_total) + parseInt(additional_total);
        $("#grand_total").val(final_total.toFixed(2));
    });

    $('input.checkmytotal').on('ifUnchecked', function (event) {
        var short_name = $(this).attr('data-parent');
        var grand_total = $("#grand_total").val();
        var additional_total = $("#subtotal_"+short_name).val();
        var final_total = parseInt(grand_total) - parseInt(additional_total);
        $("#grand_total").val(final_total.toFixed(2));
    });
</script>
@endsection
