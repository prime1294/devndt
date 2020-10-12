<?php
$user = Sentinel::check();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Enrollment Form</title>
<style type="text/css">
    {{--@font-face {--}}
    {{--    font-family: 'Hind Vadodara';--}}
    {{--    font-style: normal;--}}
    {{--    font-weight: 400;--}}
    {{--    src: url({{ asset('dompdf/fonts/HindVadodara-Regular.ttf') }}) format('truetype');--}}
    {{--    unicode-range: U+0964-0965, U+0A80-0AFF, U+200C-200D, U+20B9, U+25CC, U+A830-A839;--}}
    {{--}--}}
    {{--@font-face {--}}
    {{--    font-family: 'Hind Vadodara';--}}
    {{--    font-style: normal;--}}
    {{--    font-weight: 400;--}}
    {{--    src: url({{ asset('dompdf/fonts/HindVadodara-Regular.ttf') }}) format('truetype');--}}
    {{--    unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;--}}
    {{--}--}}
    {{--@font-face {--}}
    {{--    font-family: 'Hind Vadodara';--}}
    {{--    font-style: normal;--}}
    {{--    font-weight: 400;--}}
    {{--    src: url({{ asset('dompdf/fonts/HindVadodara-Regular.ttf') }}) format('truetype');--}}
    {{--    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;--}}
    {{--}--}}
    @page {
        margin: 0px;
    }

    body {
        margin: 0px;
        padding: 20px;
        background:#FFFFFF;

    }
    * {
        /*font-family: "Segoe UI",Arial,sans-serif;*/
        font-family: "Segoe UI",Arial, serif;
    }
    .sansfont {
        font-family: "Segoe UI",Arial,sans-serif;
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
    p {
        font-size: 14px;
        padding: 2px;
        margin: 0;
    }
    .information {
        /*background-color: #CBD6DD;*/
        color: #000000;
    }
    .customp p {
        line-height: 1.4;
    }
    .ptop-5 {
        padding-top: 5px;
    }
    .ptop-10 {
        padding-top: 10px;
    }
    .pbottom-10 {
        padding-bottom: 10px;
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
/*    new*/
    .h_address {
        color: #51C1F1;
    }
    .h_other {
        color: #E3007B;
    }
    .certificate_user {
        font-size: 18px;
        font-weight: bolder;
        padding-top: 18px;
    }
    .tbl-bold-back td {
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .form_title {
        text-transform: capitalize;
        font-size: 24px;
        font-weight: bolder;
    }
    .normal-text {
        margin-bottom: 2px;
        font-weight: bold;
        text-align: left;
    }
    .seprator {
        width: 100%;
        min-height: 1px;
    }
</style>
</head>
<body>

<div class="information customp ptop-5">
<table width="100%" style="border-collapse: collapse; border: none;">
    <tr>
        <td width="33%"><img src="{{ asset('image/logo/favicon2.png') }}" style="max-width: 100px" alt="Mini Logo"/><</td>
        <td width="67%"><img src="{{ asset('image/logo/logo1.png') }}" style="max-width: 300px" alt="Max Logo"/><</td>
    </tr>
</table>
</div>
<div class="information customp ptop-5">
    <table width="100%" style="border-collapse: collapse; border-top: 1px solid #E3007B; border-bottom: 1px solid #E3007B;">
        <tr>
            <td><p style="text-align: center;">
                    <span class="h_address sansfont">{{ $user->address }}</span>
                    <span class="h_other sansfont">&nbsp;&nbsp;&nbsp;M. {{ $user->mo_number.', '.$user->alt_number }}</span>
                    <span class="h_other sansfont">&nbsp;&nbsp;&nbsp;e-mail : {{ $user->email_id }}</span>
                    <span class="h_other sansfont">&nbsp;&nbsp;&nbsp;{{ $user->website }}</span>
                </p></td>
        </tr>
    </table>
</div>
<div class="information customp ptop-5">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td width="70%">
                <p class="form_title pbottom-10">APPLICATION AND ENROLLMENT FORM FOR NDT LEVEL-{{ $info->ndt_level }} COURCE</p>
                <p class="normal-text part1" style="text-align: left;"><b>Enrollment No:  <span class="h_address">{{ $info->id }}</span></b></p>
                <p class="normal-text part1" style="text-align: left;"><b>Method: <span class="h_address">{{ $certificate_short_name != "" ? str_replace(',',', ',$certificate_short_name) : "" }}</span></b></p>
            </td>
            <td width="30%"><p class="" style="text-align: right;"><img src="{{ asset($info->photo) }}" style="max-width: 100px; border: 1px solid #000000;" alt="Photo"/></p>
            </td>
        </tr>
    </table>
</div>
<div class="information customp ptop-5">
    <table width="100%" style="border-collapse: collapse; border: none; table-layout: fixed;">
        <tr>
            <td colspan="4"><p class="seprator" style="border-collapse: collapse; border-top: 1px solid #E3007B; border-bottom: 1px solid #E3007B;"></p></td>
        </tr>
        <tr>
            <td colspan="4"><p class="certificate_user h_other" style=""><span style="text-transform: uppercase;">Personal Details</span></p></td>
        </tr>
        <tr>
            <td colspan="4"><p class="normal-text"><b>Name on Certificate:  <span class="h_address">{{ ucwords(strtolower($info->front_greet)).'. '.ucwords($info->front_fname).' '.ucwords($info->front_mname).' '.ucwords($info->front_lname) }}</span></b></p></td>
        </tr>
        <tr>
            <td colspan="2"><p class="normal-text">Date of Birth:  <span class="h_address">{{ $info->age != 0 ? date('d-m-Y',strtotime($info->dob)) : '' }}</span></p></td>
            <td colspan="2"><p class="normal-text">Age: <span class="h_address">{{ $info->age != 0 ? $info->age.' Years' : '' }} </span></p></td>
        </tr>
        <tr>
            <td colspan="4"><p class="normal-text"><b>Father Name:  <span class="h_address">{{ ucwords(strtolower($info->father_greet)).'. '.ucwords($info->father_fname).' '.ucwords($info->father_mname).' '.ucwords($info->father_lname) }}</span></b></p></td>
        </tr>
        <tr>
            <td colspan="4"><p class="normal-text"><b>Permanent Address:  <span class="h_address">{{ $info->address }}</span></b></p></td>
        </tr>
        <tr>
            <td><p class="normal-text">City:  <span class="h_address">{{ $info->city }}</span></p></td>
            <td><p class="normal-text">District: <span class="h_address">{{ $info->district }}</span></p></td>
            <td><p class="normal-text">State: <span class="h_address">{{ $info->state }}</span></p></td>
            <td><p class="normal-text">Pincode: <span class="h_address">{{ $info->pincode }}</span></p></td>
        </tr>
        <tr>
            <td colspan="2"><p class="normal-text">Contact No.:  <span class="h_address">{{ str_replace(',',', ',$info->contact) }}</span></p></td>
            <td colspan="2"><p class="normal-text">E-mail ID: <span class="h_address">{{ $info->email }}</span></p></td>
        </tr>
        <tr>
            <td colspan="4"><p class="normal-text">Educational Qualification:  <span class="h_address">{{ $education_group_name != "" ? str_replace(',',', ',$education_group_name) : "" }}</span></p></td>
        </tr>
        <tr>
            <td colspan="2"><p class="normal-text">Year of Completion : <span class="h_address">{{ $info->year_of_complete }}</span></p></td>
            <td colspan="2"><p class="normal-text" style="text-align: left;"><b>Experience in NDT Field: <span class="h_address">{{ $info->exp_hour }} {{ $info->exp_hour <= 1 ? substr(strtolower($info->exp_type),0,-1) : strtolower($info->exp_type) }}</span></b></p></td>
        </tr>
    </table>
</div>
<div class="information customp ptop-5">
    <table width="100%" style="border-collapse: collapse; border: none; table-layout: fixed;">
        <tr>
            <td colspan="4"><p class="seprator" style="border-collapse: collapse; border-top: 1px solid #E3007B; border-bottom: 1px solid #E3007B;"></p></td>
        </tr>
        <tr>
            <td colspan="4"><p class="certificate_user h_other" style=""><span style="text-transform: uppercase;">Company Details</span></p></td>
        </tr>
        <tr>
            <td colspan="2"><p class="normal-text">Name:  <span class="h_address">{{ isset($cinfo->company_name) ? $cinfo->company_name : "" }}</span></p></td>
            <td colspan="2"><p class="normal-text">Type: <span class="h_address">{{ isset($cinfo->company_type) ? $cinfo->company_type : "" }}</span></p></td>
        </tr>
        <tr>
            <td colspan="4"><p class="normal-text" style="text-align: left;"><b>Address:  <span class="h_address">{{ isset($cinfo->address) ? $cinfo->address : "" }}</span></b></p></td>
        </tr>
        <tr>
            <td><p class="normal-text">City:  <span class="h_address">{{ isset($cinfo->city) ? $cinfo->city : "" }}</span></p></td>
            <td><p class="normal-text">District: <span class="h_address">{{ isset($cinfo->district) ? $cinfo->district : "" }}</span></p></td>
            <td><p class="normal-text">State: <span class="h_address">{{ isset($cinfo->state) ? $cinfo->state : "" }}</span></p></td>
            <td><p class="normal-text">Pincode: <span class="h_address">{{ isset($cinfo->pincode) ? $cinfo->pincode : "" }}</span></p></td>
        </tr>
        <tr>
            <td colspan="4"><p class="normal-text">Weekly Off: <span class="h_address">{{ isset($cinfo->week_off) ? $cinfo->week_off : "" }}</span></p></td>
        </tr>
    </table>
</div>
<div class="information customp ptop-5">
    <table width="100%" style="border-collapse: collapse; border: none; table-layout: fixed;">
        <tr>
            <td colspan="2"><p class="seprator" style="border-collapse: collapse; border-top: 1px solid #E3007B; border-bottom: 1px solid #E3007B;"></p></td>
        </tr>
        <tr>
            <td colspan="2"><p class="certificate_user h_other" style=""><span style="text-transform: uppercase;">Reference Details</span></p></td>
        </tr>
        <tr>
            <td><p class="normal-text">Name:  <span class="h_address">{{ ucwords(isset($rinfo->fname) ? $rinfo->fname : '').' '.ucwords(isset($rinfo->mname) ? $rinfo->mname : '').' '.ucwords(isset($rinfo->lname) ? $rinfo->lname : '') }}</span></p></td>
            <td><p class="normal-text">Company Name: <span class="h_address">{{ isset($rinfo->company_name) ? $rinfo->company_name : '' }}</span></p></td>
        </tr>
        <tr>
            <td><p class="normal-text">Contact No.:  <span class="h_address">{{ isset($rinfo->contact) ? str_replace(',',', ',$rinfo->contact) : '' }}</span></p></td>
            <td><p class="normal-text">E-mail ID: <span class="h_address">{{ isset($rinfo->email) ? $rinfo->email : '' }}</span></p></td>
        </tr>
    </table>
</div>
<div class="information customp ptop-5">
    <table width="100%" style="border-collapse: collapse; border: none; table-layout: fixed;">
        <tr>
            <td colspan="4"><p class="seprator" style="border-collapse: collapse; border-top: 1px solid #E3007B; border-bottom: 1px solid #E3007B;"></p></td>
        </tr>
        <tr>
            <td colspan="4"><p class="certificate_user h_other" style=""><span style="text-transform: uppercase;">Fees Details</span></p></td>
        </tr>
        <tr>
            <td><p class="normal-text">Total Fees:  <span class="h_address">{{ $info->total_fees ? 'Rs. '.number_format($info->total_fees) : '' }}</span></p></td>
            <td><p class="normal-text">Paid Fees: <span class="h_address">{{ $info->paid_fees ? 'Rs. '.number_format($info->paid_fees) : '' }}</span></p></td>
            <td><p class="normal-text">Pending Fees: <span class="h_address">{{ $info->pending_fees ? 'Rs. '.number_format($info->pending_fees) : '' }}</span></p></td>
            <td><p class="normal-text">Due Date: <span class="h_address">{{ date('d-m-Y',strtotime($info->due_date)) }}</span></p></td>
        </tr>
    </table>
</div>
<div class="information customp ptop-5">
    <table width="100%" style="border-collapse: collapse; border: none; table-layout: fixed;">
        <tr>
            <td colspan="2"><p class="seprator" style="border-collapse: collapse; border-top: 1px solid #E3007B; border-bottom: 1px solid #E3007B;"></p></td>
        </tr>
        <tr>
            <td colspan="2"><p class="certificate_user h_other" style=""><span style="text-transform: uppercase;">Other Details</span></p></td>
        </tr>
        <tr>
            <td><p class="normal-text">Place:  <span class="h_address">{{ $info->place }}</span></p></td>
            <td><p class="normal-text">Date: <span class="h_address">{{ date('d-m-Y',strtotime($info->reg_date)) }}</span></p></td>
        </tr>
        <tr valign="bottom" style="padding-top: 35px;">
            <td style="text-align: right;" colspan="2">__________________________<p class="normal-text" style="text-align: right; margin-right: 5px;">Signature of candidate</p></td>
        </tr>
    </table>
</div>



</body>
</html>