@extends('admin.v1.layout.app', ['title' => 'Edit Invoice'])

@section('content')
<style type="text/css">
    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 0px !important;
    }
    .editorto , .editordiv {
        display: none;
    }
</style>
{!!Html::style('/public/admin/theme1/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')!!}
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-trello"></i> Edit Invoice
        <small>Edit Invoice</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('invoice')}}">Invoice</a></li>
        <li class="active">Edit Invoice</li>

    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <form method="post" action="{{ route('invoice.update',$info->id) }}" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="invoice_date">Invoice Date</label>
                                    <input type="text" id="invoice_date" name="invoice_date" value="{{ date('d-m-Y',strtotime($info->invoice_date)) }}" class="form-control datepicker">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="enrollment_id">Enrollment No.</label>
                                    <select  style="width: 100%;" id="enrollment_id" name="enrollment_id" class="form-control select2">
                                        <option value="">Find By Id</option>
                                        @foreach($enrollment_list as $row)
                                            <option value="{{ $row->id }}" {{ $row->id == $info->enrollment_id ? "selected" : "" }}>{{ $row->id }} - {{ $row->front_fname.' '.$row->front_lname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="company_id">Company No.</label>
                                    <select  style="width: 100%;" id="company_id" name="company_id" class="form-control select2">
                                        <option value="">Find By Id</option>
                                        @foreach($company_list as $row)
                                            <option value="{{ $row->id }}" {{ $row->id == $info->company_id ? "selected" : "" }}>{{ $row->id }} - {{ $row->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="invoice_name">Full Name</label>
                                    <input type="text" id="invoice_name" name="invoice_name" placeholder="Baldev Patel" value="{{ $info->invoice_name }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="invoice_address">Address</label>
                                    <input type="text" id="invoice_address" name="invoice_address" placeholder="Ahmedabad - 382345" value="{{ $info->invoice_address }}" class="form-control">
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
       initdatepicker(false);
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

    function check_val() {
        if($("#invoice_name").val() == "") {
            toastr.error("Please, Enter Name");
            $("#invoice_name").focus();
            return false;
        }
        if($("#invoice_address").val() == "") {
            toastr.error("Please, Enter Invoice Address");
            $("#invoice_address").focus();
            return false;
        }
    }

    function recalculatetotal() {
        var total = 0;
        $('.subtot').each(function(index, element) {
            total += parseInt($(this).val());
        });
        $("#grand_total").val(total);
    }

    $('.calculateme').on('propertychange input', function (e) {
        var short_name = $(this).parent('td').parent('tr').attr('data-parent');
        var qty = $("#qty_"+short_name).val();
        var fees = $("#fees_"+short_name).val();
        var total = parseInt(qty) * parseInt(fees);
        $("#subtotal_"+short_name).val(total.toFixed(2));
    });

    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            // increaseArea: '20%' /* optional */
        });
    });

    $(document).on("change","#charge_for",function(e){
        var x = parseInt($(this).val());
        var input1 = $(".editorcource");
        var input2 = $(".editorto");
        var input3 = $(".editordiv");
        switch (x) {
            case 1:
                input1.show();
                input2.hide();
                input3.hide();
                break;
            case 2:
                input1.show();
                input2.hide();
                input3.hide();
                break;
            case 3:
                input1.show();
                input2.hide();
                input3.hide();
                break;
            case 4:
                input1.hide();
                input2.show();
                input3.hide();
                break;
            case 5:
                input1.hide();
                input2.hide();
                input3.show();
                break;
            case 6:
                input1.hide();
                input2.hide();
                input3.show();
                break;
            case 7:
                input1.hide();
                input2.hide();
                input3.show();
                break;
            case 8:
                input1.hide();
                input2.hide();
                input3.hide();
                break;
            default:
                input1.hide();
                input2.hide();
                input3.show();
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
