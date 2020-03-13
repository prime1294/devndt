<?php
$user = Sentinel::check();
?>
        <!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style type="text/css">
        @font-face {
            font-family: 'Hind Vadodara';
            font-style: normal;
            font-weight: 400;
            src: url({{ asset('dompdf/fonts/HindVadodara-Regular.ttf') }}) format('truetype');
            unicode-range: U+0964-0965, U+0A80-0AFF, U+200C-200D, U+20B9, U+25CC, U+A830-A839;
        }
        /* latin-ext */
        @font-face {
            font-family: 'Hind Vadodara';
            font-style: normal;
            font-weight: 400;
            src: url({{ asset('dompdf/fonts/HindVadodara-Regular.ttf') }}) format('truetype');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }
        /* latin */
        @font-face {
            font-family: 'Hind Vadodara';
            font-style: normal;
            font-weight: 400;
            src: url({{ asset('dompdf/fonts/HindVadodara-Regular.ttf') }}) format('truetype');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
            padding: 30px;
            background:#FFFFFF;

        }
        * {
            font-family: 'Hind Vadodara', sans-serif;
        }
        a {
            color: #fff;
            text-decoration: none;
        }
        table {
            font-size: x-small;
        }
        table th {
            color: #3f51b5;
            font-size: 13px;
        }
        .img-responsive {
            display: block;
            max-width: 100%;
            height: auto;
        }
        p {
            font-size: 12px;
            /*line-height: 0.5;*/
            padding: 2px;
            margin: 0;
        }
        .information {
            /*background-color: #CBD6DD;*/
            color: #000000;
        }
        .customp p {
            line-height: 0.7;
        }
        .txt-heading {
            font-size: 30px !important;
            font-weight: bolder;
            text-align: center;
            color: #3f51b5;
        }
        .txt-subheading {
            font-size: 18px;
            font-weight: bolder;
            text-align: left;
        }
        .ptop-10 {
            padding-top: 10px;
        }
        .pbottom-10 {
            padding-bottom: 10px;
        }
        .pleft-10 {
            padding-left: 10px;
        }
        .pright-10 {
            padding-right: 10px;
        }
        .phorizontal-10 {
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .page-break {
            page-break-after: always;
        }
        .primetbl tr td {
            padding: 0;
            padding-bottom: 3px;
            text-align: center;
        }
        .primetbl tr th {
            background: #3f51b5;
            color: white;
        }
        .primetblhead tr td p {
            font-size: 18px;
        }
        .sub-text {
            font-size: 13px !important;
            color: #888888;
            font-weight: bold;
        }
        .sub-text2 {
            font-size: 12px;
            line-height: 0.3cm;
            color: #888888;
            font-weight: bold;
        }
        .mt-sm {
            margin-top: 20px;
        }
        .tbl_total {
            font-weight: bold;
            font-size: 16px;
        }
        .bg-black td {
            background: #3f51b5;
            color: white;
        }
        td img {
           height: 50;
            padding:5px 0px;
        }
    </style>
</head>
<body>
<div class="information customp">
    <table width="100%" style="border-collapse: collapse; border: none; border-bottom: 2px dashed black;">
        <tr>
            <td style="padding: 10px;">
                <p class="txt-heading">Invoice</p>
                @if($user->about_business != "")
                <center><span class="sub-text">{{ $user->about_business }}</span></center>
                @endif
            </td>
        </tr>
    </table>
</div>
<div class="information customp mt-sm">
    <table width="100%" style="border-collapse: collapse;">
        <tr>
            <td width="38%" valign="top">
                <p class="txt-subheading">{{ $user->business_name }}</p>
                <p><b>Phone No: </b> {{ $user->mo_number }} {{ $user->alt_number != "" ? ', '.$user->alt_number : "" }}</p>
                <p><b>GSTIN No: </b> {{ $user->gstno }}</p>
                <p><b>PAN No: </b> {{ $user->pan }}</p>
                <p><b>Address: </b> {{ $user->address }}</p>
                <p>{{ $user_city->city }}, {{ $user_state->gst_code }}-{{ $user_state->state }}</p>
            </td>
            <td width="33%" valign="top">
                <p class="txt-subheading">Biling To</p>
                <p style="margin-top: 5px;"><b>{{ $info->business_name }}</b></p>
                <p>{{ $info->manufacturer }}</p>
                <p><b>GSTIN No: </b> {{ $info->process_gst }}</p>
                <p><b>PAN No: </b> {{ $info->process_gst != "" ? substr($info->process_gst,2,10) : "" }}</p>
                <p><b>Address: </b> {{ $info->process_address }}</p>
                <p>{{ isset($process_city->city) ? $process_city->city.', ' : "" }} {{ $process_state->gst_code }}-{{ $process_state->state }}</p>
            </td>
            <td width="29%" valign="top" style="text-align: right">
                <p><b>Date: </b>{{ Admin::FormateDate($info->date) }}</p>
                <?php
                if($info->invoice_type == 2) {
                ?>
                <p><b>Due Date: </b>{{ Admin::FormateDate($info->due_date) }}</p>
                <?php
                    }
                ?>
                <p><b>Invoice No: </b>{{ $info->invoice_name }}</p>
                <?php
                if($info->agent_name != "") {
                ?>
                <p><b>Agent Name: </b>{{ $info->agent_name }}</p>
                <?php
                }
                ?>
                <p><b>DC No: </b>{{ $info->delivery_no }}</p>
                <p><b>Challan No: </b>{{ $info->challan_no }}</p>
            </td>
        </tr>
    </table>
</div>
<div class="information customp mt-sm">
    <table width="100%" style="border-collapse: collapse; border-top: 2px dashed black;">
        <tr>
            <td style="padding-top: 20px;">
                <p><span class="txt-subheading">Shipping To:</span> {{ $info->transport }} {{ $info->state_gst_code != "" ? ', '.$info->state_gst_code : "" }}{{ $info->state_name != "" ? '-'.$info->state_name : "" }}</p>
            </td>
        </tr>
    </table>
</div>
<div class="information primetbl customp phorizontal-10">
    <table border="2" width="100%" style="border-collapse: separate;">
        <tr>
            <th>#</th>
            <th>Description</th>
            <th>Design</th>
            <th>HSN/SAC</th>
            <th>Quantity</th>
            <th>Rate</th>
            <th>Discount</th>
            <th>GST</th>
            <th>Amount</th>
        </tr>
        <?php $x= 1; ?>
        <?php
        $discount_total = 0;
        $gst_total = 0;
        $amount_total = 0;
        $subtotal = 0;
        $gst_array = [];
        ?>
        @foreach($item_list as $row)
            <?php
            $quantity = $row->quantity * $row->rate;
            $discount_cal = ($row->discount / 100) * $quantity;
            $discount_total += $discount_cal;
            $after_discount = $quantity - $discount_cal;
            $gst_cal = ($row->gst / 100) * $after_discount;
            $gst_total += $gst_cal;
            $amount_total += $row->total;
            $subtotal += $quantity;
            $gst_array[$row->gst] = array_key_exists($row->gst,$gst_array) ? $gst_array[$row->gst] + $gst_cal : $gst_cal;
            ?>
            <tr>
                <td width="3%">{{ $x++ }}</td>
                <td>{{ Admin::FormateStockItemID($row->stock_id) }} - {{ $row->description }}</td>
                <td>{{ $row->design_name }}</td>
                <td>{{ $row->hsn_code }}</td>
                <td>{{ $row->quantity }} {{ $row->mesurement_name }}</td>
                <td>{{ number_format($row->rate,2) }}</td>
                <td>{{ number_format($discount_cal,2) }} ({{ $row->discount }}%)</td>
                <td>{{ number_format($gst_cal,2) }} ({{ $row->gst }}%)</td>
                <td style="text-align: right; padding-right: 6px;">{{ number_format($row->total,2) }}</td>
            </tr>
        @endforeach
            <tr class="tbl_total">
                <td colspan="6" style="text-align: right;padding-right: 10px !important;padding-top: 5px !important; padding-bottom: 5px !important;">Total</td>
                <td style="padding-top: 5px !important; padding-bottom: 5px !important;">{{ number_format($discount_total,2) }}</td>
                <td style="padding-top: 5px !important; padding-bottom: 5px !important;">{{ number_format($gst_total,2) }}</td>
                <td style="text-align:right; padding-right: 6px; padding-top: 5px !important; padding-bottom: 5px !important;">{{ number_format($amount_total,2) }}</td>
            </tr>
    </table>
</div>
<div class="information  customp">
    <table width="100%" style="border-collapse: collapse;">
        <?php
        $is_same_state = $user_state->state == $process_state->state ? true : false;
            $new_number = 7;
            if($info->discount_amount == 0) {
                $new_number -= 1;
            }
            if($info->tax_total == 0) {
                $new_number -= 1;
            }
            if($discount_total == 0) {
                $new_number -= 1;
            }
            $rowspan = $is_same_state ? (count($gst_array) * 2) + $new_number  : count($gst_array) + $new_number;
        ?>
        <tr>
            <td width="66%" rowspan="{{ $rowspan }}" valign="top">
                <p class="txt-subheading" style="padding-top: 10px;">Amount in Word</p>
                <p><span class="sub-text2">{{ ucwords(Admin::convertCurrency($info->grand_total)) }}</span></p>
                <p class="txt-subheading mt-sm">Remarks</p>
                <p><span class="sub-text2">{{ $info->remarks }}</span></p>
            </td>
            <td style="padding-left: 10px;">Sub Total</td>
            <td style="text-align: right;padding-right: 12px;">{{ number_format($subtotal,2) }}</td>
        </tr>
            <?php
            if($discount_total != 0 ) {
                ?>
        <tr>
            <td style="padding-left: 10px;">Discount</td>
            <td style="text-align: right;padding-right: 12px">{{ number_format($discount_total,2) }}</td>
        </tr>
        <?php } ?>
        <?php
            foreach($gst_array as $key=>$row) {
                if($key == 0) {
                    continue;
                }
                if($is_same_state) {
                $show_percent = $key/2;
                $show_percent = sprintf('%0.1f', $show_percent);
                $gst_amount =  $row/2;
                } else {
                    $show_percent = $key;
                    $show_percent = sprintf('%0.1f', $show_percent);
                    $gst_amount =  $row;
                }
        ?>
        @if($is_same_state)
        <tr>
            <td style="padding-left: 10px;">SGST@ {{ $show_percent }}%</td>
            <td style="text-align: right;padding-right: 12px">{{ number_format($gst_amount,2) }}</td>
        </tr>
        <tr>
            <td style="padding-left: 10px;">CGST@ {{ $show_percent }}%</td>
            <td style="text-align: right;padding-right: 12px">{{ number_format($gst_amount,2) }}</td>
        </tr>
        @else
        <tr>
            <td style="padding-left: 10px;">IGST@ {{ $show_percent }}%</td>
            <td style="text-align: right;padding-right: 12px">{{ number_format($gst_amount,2) }}</td>
        </tr>
        @endif
        <?php
        }
        ?>
        <?php
            if($info->discount_amount != 0) {
        ?>
        <tr>
            <td style="padding-left: 10px;">Loss Amount</td>
            <td style="text-align: right;padding-right: 12px">{{ number_format($info->discount_amount,2) }}</td>
        </tr>
        <?php } ?>
        <?php
        if($info->tax_total != 0) {
        ?>
        <tr>
            <td style="padding-left: 10px;">T.D.S</td>
            <td style="text-align: right;padding-right: 12px">{{ number_format($info->tax_total,2) }}</td>
        </tr>
        <?php } ?>
        <tr class="bg-black">
            <td style="padding-bottom: 5px !important;padding-left: 10px;">Total Amount</td>
            <td style="text-align: right;padding-right: 12px; padding-bottom: 5px !important;">{{ number_format($info->grand_total,2) }}</td>
        </tr>
        <tr>
            <td style="padding-left: 10px;">Received</td>
            <td style="text-align: right;padding-right: 12px">{{ number_format($info->grand_payment,2) }}</td>
        </tr>
        <tr>
            <td style="padding-left: 10px;">Balance</td>
            <td style="text-align: right;padding-right: 12px">{{ number_format($info->grand_total-$info->grand_payment,2) }}</td>
        </tr>
    </table>
</div>
<div class="information customp" style="margin-top: 40px;">
    <table width="100%" style="border-collapse: collapse;">
        <tr>
            <td width="50%" valign="bottom">
                <p class="txt-subheading">Received</p>
            </td>
            <td valign="top">
                <p class="txt-subheading" style="text-align: right">For, {{ $user->business_name }}</p>
                <div class="signature-img">
                    <?php
                    if($user->signature != "") {
                    ?>
                    <img src="{{ asset($user->signature) }}" align="right" class="">
                    <?php
                    } else {
                        echo '<br><br><br><br>';
                    }
                    ?>
                </div>
                <p class="txt-subheading" style="text-align: right; clear: both">Authorized Signature</p>
            </td>
        </tr>
    </table>
</div>
<div class="information footer-bg newmanager" style="padding-top: 10px;">
    <table width="100%">
        <tr>
            <td align="left" style="width: 50%;">
                &copy; <?php echo date('Y') ?> All In One - All rights reserved.
            </td>
            <td align="right" style="width: 50%;">
                {{ $user->business_name }}
            </td>
        </tr>

    </table>
</div>
</body>
</html>