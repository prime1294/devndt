<?php
$user = Sentinel::check();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
@if(isset($cinfo))
<title>{{ config('setting.app_name') }} | {{ $cinfo->counce_name }}</title>
@else
<title>{{ config('setting.app_name') }} | Certificate of {{ $info->front_fname.' '.$info->front_lname }}</title>
@endif
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
    .img-responsive {
        display: block;
        max-width: 100%;
        height: auto;
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
    .ptop-5 {
        padding-top: 5px;
    }
    .ptop-10 {
        padding-top: 10px;
    }
    .ptop-20 {
        padding-top: 20px;
    }
    .ptop-30 {
        padding-top: 30px;
    }
    .ptop-40 {
        padding-top: 40px;
    }
    .ptop-50 {
        padding-top: 80px;
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
/*    new*/
    .h_address {
        color: #51C1F1;
    }
    .h_other {
        color: #E3007B;
    }
    .certificate_no {
        font-size: 20px;
        font-weight: bold;
    }
    .enroll_no {
        font-size: 20px;
        font-weight: bold;
    }
    .certificate_name {
        font-size: 27px;
        font-weight: bolder;
        padding-top:35px;
    }
    .certificate_second {
        font-size: 20px;
        font-weight: bold;
        padding-top: 18px;
    }
    .certificate_user {
        font-size: 18px;
        font-weight: bolder;
        padding-top: 18px;
    }
    .certificate_third {
        font-size: 20px;
        font-weight: bolder;
        padding-top: 25px;
    }
    .certificate_cource {
        font-size: 18px;
        font-weight: bolder;
        padding-top: 18px;
    }
    .certificate_forth {
        font-size: 20px;
        font-weight: bolder;
        padding-top: 18px;
    }
    .padding-lr-20 {
        padding-left: 30px;
        padding-right: 30px;
    }
    .tbl-bold {
        font-weight: bold;
        font-size: 20px !important;
    }
    .tbl-bold-back {
        font-weight: bold;
        font-size: 20px !important;
    }
    .certificate_five {
        font-size: 17px;
        font-weight: bolder;
    }
    .certificate_six {
        font-size: 17px;
        font-weight: bolder;
    }
    .certificate_seven {
        font-size: 17px;
        font-weight: bolder;
    }
    .certificate_eight {
        font-size: 17px;
        font-weight: bolder;
    }

    .back_title {
        font-size: 24px;
        font-weight: bolder;
        padding-top: 15px;
    }
    .tbl-bold-back td {
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .fixed-footer {
        position: absolute;
        left: 20px;
        bottom: 0px;
    }
</style>
</head>
<body>
@inject('provider', 'App\Http\Controllers\Administrator\v1\EnrollmentController')
<?php
    if($cid) {
        $forloop = [$cid];
    } else {
        $forloop = [];
        $allcertif = $provider::getallCretificates($info->id);
        foreach($allcertif as $devndtcertificate) {
            $forloop[] = $devndtcertificate->id;
        }
    }

    $lastElement = end($forloop);
    foreach($forloop as $cloop) {
    $cinfo = $provider::getCertificateInfo($cloop);
    $certificate_no = $provider::generateCertificateNumber($info->id,$cinfo->id);
    $enrollment_no = $cinfo->short_name.'-'.$info->id;

    //get company info if not self
    $company_name_back = $info->sponsor != "self" ? Admin::getCompanyInfo($info->company_id_certificate)->company_name : "Self";

    //unserialized history
    $history = unserialize($cinfo->chistory);
?>
<div class="information customp ptop-10">
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
<div class="information customp ptop-10">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td width="50%"><p class="certificate_no" style="text-align: left;"><b>Certificate No. <span class="h_address">{{ $certificate_no }}</span></b></p>
            </td>
            <td width="50%"><p class="enroll_no" style="text-align: right;"><b>Enrollment No. <span class="h_address">{{ $enrollment_no }}</span></b></p>
            </td>
        </tr>
    </table>
</div>
<div class="photo-content ptop-40" style="position: absolute; clear: both;">
    <img src="{{ asset($info->photo) }}" style="max-width: 120px; border: 1px solid #000000;" alt="Photo"/>
    <img src="{{ asset('image/logo/stamp.png') }}" style="max-width: 100px; margin-left: -60px; margin-top: 30px;" alt="Photo"/>
</div>
<div class="information customp ptop-10">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td>
                <p class="certificate_name" style="text-align: center;">Certificate of Proficiency</p>
                <p class="certificate_second" style="text-align: center;">This is to certify that</p>
                <p class="certificate_user h_other" style="text-align: center;text-transform: capitalize;">{{ $info->front_greet }}. <span style="text-transform: uppercase;">{{ $info->front_fname.' '.$info->front_mname.' '.$info->front_lname }}</span></p>
                <p class="certificate_third" style="text-align: center;">has met the certification requirements and has demonstrated proficiency<br>by qualifying certification examination and is hereby certified to</p>
                <p class="certificate_cource h_other" style="text-align: center;text-transform: uppercase;">NDT LEVEL {{ $info->ndt_level }}<br>IN<br>{{ $cinfo->counce_name }}</p>
                <p class="certificate_forth" style="text-align: center;">Training course and examination conducted as per recommendations<br>of ASNT document SNT TC -1A {{ $info->snt_edition }} edition</p>
            </td>
        </tr>
    </table>
</div>
<div class="information customp ptop-20 padding-lr-20 tbl-bold">
    <table border="1" width="100%" style="border-collapse: collapse; border: none; text-align: center;">
        <tr>
            <td width="33.33%">Paper</td>
            <td width="33.33%">% Required</td>
            <td width="33.33%">% Scored</td>
        </tr>
        <tr>
            <td>General</td>
            <td>70</td>
            <td>{{ $cinfo->marks_general }}</td>
        </tr>
        <tr>
            <td>Specific</td>
            <td>70</td>
            <td>{{ $cinfo->marks_specific }}</td>
        </tr>
        <tr>
            <td>Practical</td>
            <td>70</td>
            <td>{{ $cinfo->marks_practical }}</td>
        </tr>
        <tr>
            <td>Average</td>
            <td>80</td>
            <td>{{ $cinfo->marks_average }}</td>
        </tr>
    </table>
</div>
<div class="information customp ptop-30 padding-lr-20 tbl-bold">
    <table border="1" width="100%" style="border-collapse: collapse; border: none; text-align: center;">
        <tr>
            <td width="33.33%"></td>
            <td width="33.33%">Issue Date</td>
            <td width="33.33%">Expiration Date</td>
        </tr>
        <tr>
            <td>{!! Admin::ordinal($cinfo->tno) !!} {{ $info->creation == 3 && $cinfo->tno != 0 ? "Re-" : "" }}Certification</td>
            <td>{{ date('d/m/Y',strtotime($cinfo->from_date)) }}</td>
            <td>{{ date('d/m/Y',strtotime($cinfo->to_date)) }}</td>
        </tr>
    </table>
</div>
<div class="information customp ptop-50 padding-lr-20">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td>
                @if($info->creation == 2 || $info->creation == 3)
                    <p class="certificate_five">RENEWED BY</p>
                @else
                    <p class="certificate_five">COURCE DIRECTOR & EXAMINER</p>
                @endif
                <p class="certificate_six h_other">BALDEV L. PATEL</p>
                <p class="certificate_seven">ASNT NDT Level-III, RT, UT, MT, PT, VT, ET, LT, IR, MFL</p>
                <p class="certificate_eight">Certificate No.: 200510</p>
            </td>
        </tr>
    </table>
</div>

<div class="page-break"></div>

<div class="information customp ptop-10">
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
<p class="back_title h_other" style="text-align: center; text-transform: capitalize;">Personnel Certification Record</p>
<div class="information customp ptop-10 tbl-bold-back">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td width="30%">Name</td>
            <td width="4%">:</td>
            <td colspan="2" width="68%" style="text-transform: capitalize;">{{ ucwords(strtolower($info->back_greet)).'. '.ucwords(strtolower($info->back_fname)).' '.ucwords(strtolower($info->back_mname)).' '.ucwords(strtolower($info->back_lname)) }}</td>
        </tr>
        <tr>
            <td>Company Name</td>
            <td>:</td>
            <td colspan="2">{{ $company_name_back }}</td>
        </tr>
        <tr>
            <td>Reference Document</td>
            <td>:</td>
            <td colspan="2">SNT TC -1A, {{ $info->snt_edition }} Edition</td>
        </tr>
        <tr>
            <td>Written Practice No.</td>
            <td>:</td>
            <td colspan="2">DNIE/CERTI./07-2011/01 , Rev. 00</td>
        </tr>
        <tr>
            <td>Certificate No.</td>
            <td>:</td>
            <td colspan="2">{{ $certificate_no }}</td>
        </tr>
        <tr>
            <td>Method</td>
            <td>:</td>
            <td colspan="2">{{ $cinfo->counce_name }}</td>
        </tr>
        <tr>
            <td>Level</td>
            <td>:</td>
            <td colspan="2">{{ strtoupper($info->ndt_level) }}</td>
        </tr>
        <tr>
            <td>Educational Background</td>
            <td>:</td>
            <td colspan="2">{{ $provider::getEducationGroupName($info->education,false) }}</td>
        </tr>
        <tr>
            <td>Experience ( NDT )</td>
            <td>:</td>
            <td colspan="2">{{ $info->exp_hour }} {{ $info->exp_hour <= 1 ? substr(strtolower($info->exp_type),0,-1) : strtolower($info->exp_type) }}  ( > {{ $info->ndt_level == 'I' ? $cinfo->min_exp_hours_1 : $cinfo->min_exp_hours_2 }} hours)</td>
        </tr>
        <tr>
            <td>Near Vision Acuity</td>
            <td>:</td>
            <td colspan="2">{{ $info->vision }}</td>
        </tr>
        <tr>
            <td>Color Contrast</td>
            <td>:</td>
            <td colspan="2">OK</td>
        </tr>
        @if($info->creation == 1)
        <tr>
            <td>Total Training Hours</td>
            <td>:</td>
            <td colspan="2">{{ $info->ndt_level == 'I' ? $cinfo->level1_hours : $cinfo->level2_hours }}</td>
        </tr>
        @endif
        @if($info->creation == 2 || $info->creation == 3)
        <tr valign="top">
            <td>Renewal</td>
            <td>:</td>
            <td colspan="2">Based on continuous satisfactory technical performance<br>( Previous Certificate No. {{ $cinfo->cno }} {{ $cinfo->previous_certificate == 1 ? "- DEV NDT" : "" }} )</td>
        </tr>
        @endif
        <tr>
            <td></td>
            <td></td>
            <td>Issue Date</td>
            <td>Expiration Date</td>
        </tr>
        <?php $adjustment = Admin::adjustmentTblpadding(count($history)); ?>
        @foreach($history as $row)
        <tr>
            <td {!! $adjustment !!}>{!! Admin::ordinal($row['no']) !!} {{ $row['no'] != 0 && $info->creation == 3 ? "Re-" : ""  }}Certification</td>
            <td {!! $adjustment !!}>:</td>
            <td {!! $adjustment !!}>{{ date('d/m/Y',strtotime($row['from_date'])) }}</td>
            <td {!! $adjustment !!}>{{ date('d/m/Y',strtotime($row['to_date'])) }}</td>
        </tr>
        @endforeach
    </table>
</div>
<div class="information customp ptop-20 fixed-footer">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td>
                @if($info->creation == 2 || $info->creation == 3)
                    <p class="certificate_five">RENEWED BY</p>
                @else
                <p class="certificate_five">COURCE DIRECTOR & EXAMINER</p>
                @endif
                <p class="certificate_six h_other">BALDEV L. PATEL</p>
                <p class="certificate_seven">ASNT NDT Level-III, RT, UT, MT, PT, VT, ET, LT, IR, MFL</p>
                <p class="certificate_eight">Certificate No.: 200510</p>
            </td>
        </tr>
    </table>
</div>
@if($cloop != $lastElement)
    <div class="page-break"></div>
@endif
<?php
    }
?>
</body>
</html>