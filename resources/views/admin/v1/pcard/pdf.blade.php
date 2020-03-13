<?php
$user = Sentinel::check();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Programme Card</title>
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
        padding: 25px;
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
        font-size: 10px;
        line-height: 0.6;
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
        padding: 5.5px;
        text-align: center;
    }
    .primetbl tr th {
        background: #3f51b5;
        color: white;
    }
    .primetblhead tr td p {
        font-size: 18px;
    }
    .pendrive_no {
        color: #3f51b5;
        font-size: 24px !important;
        font-weight: bolder;
    }
</style>
</head>
<body>
@foreach($parent_list as $row)
<div class="information customp">
<table width="100%" style="border-collapse: collapse; border: none; border-bottom: 2px dashed black;">
    <tr>
        <td style="padding: 10px;">
            <p class="txt-heading">Programme Card No: {{ str_replace(config('setting.programmecard_prefix'),'',$info->pc_name) }}</p>
        </td>
    </tr>
</table>
</div>
<div class="information primetblhead customp phorizontal-10">
    <table width="100%" style="border-collapse: collapse;">
        <tr>
            <td rowspan="9" style="width: 50%;">
                <center><img src="{{ asset($row['image']) }}" height="300px" style="max-width: 400px" alt="Design Image"/></center>
            </td>
            <td>
                <p><b>Date: </b> {{ Admin::FormateDate($info->date) }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><b>Stock No: </b> {{ $info->stock_id != 0 ? Admin::FormateStockItemID($info->stock_id) : "" }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><b>Design Name: </b> {{ $row['name'] }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><b>Category: </b> {{ $row['category'] }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><b>Qunatity: </b> {{ $row['total_quantity'].' '.$info->mesurement_name }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><b>Stitch: </b> {{ $row['stitch'] }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><b>Color: </b> {{ $row['color'] }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><b>Head: </b> {{ $row['working_type'] }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><b>Pendrive Design Number: </b> <span class="pendrive_no">{{ $row['pendrive_design_number'] }}</span></p>
            </td>
        </tr>
    </table>
</div>
<div class="information primetbl customp phorizontal-10">
    <table border="2" width="100%" style="border-collapse: separate;">
        <tr>
            <th>Color</th>
            <th>Quantity</th>
            <th>Niddle 1</th>
            <th>Niddle 2</th>
            <th>Niddle 3</th>
            <th>Niddle 4</th>
            <th>Niddle 5</th>
            <th>Niddle 6</th>
        </tr>
        @foreach($row['item_list'] as $rr)
        <tr>
            <td>{{ $rr['color'] }}</td>
            <td>{{ $rr['quantity'] }}</td>
            <td>{{ $rr['n1'] }}</td>
            <td>{{ $rr['n2'] }}</td>
            <td>{{ $rr['n3'] }}</td>
            <td>{{ $rr['n4'] }}</td>
            <td>{{ $rr['n5'] }}</td>
            <td>{{ $rr['n6'] }}</td>
        </tr>
        @endforeach
    </table>
</div>
<div class="information footer-bg newmanager" style="">
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
@if(next( $parent_list ) == true)
<div class="page-break"></div>
@endif
@endforeach
</body>
</html>