<?php
$user = Sentinel::check();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Programme Card</title>
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
</style>
</head>
<body>

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
            <td width="50%"><p class="certificate_no" style="text-align: left;"><b>Certificate No. <span class="h_address">RT-II/11-19/R-3526</span></b></p>
            </td>
            <td width="50%"><p class="enroll_no" style="text-align: right;"><b>Enrollment No. <span class="h_address">R-R3526</span></b></p>
            </td>
        </tr>
    </table>
</div>
<div class="photo-content ptop-40" style="position: absolute; clear: both;">
    <img src="{{ asset('image/profile/passport.jpg') }}" style="max-width: 120px; border: 1px solid #000000;" alt="Photo"/>
    <img src="{{ asset('image/logo/stamp.png') }}" style="max-width: 100px; margin-left: -60px; margin-top: 30px;" alt="Photo"/>
</div>
<div class="information customp ptop-10">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td>
                <p class="certificate_name" style="text-align: center;">Certificate of Proficiency</p>
                <p class="certificate_second" style="text-align: center;">This is to certify that</p>
                <p class="certificate_user h_other" style="text-align: center;text-transform: capitalize;">Mr. <span style="text-transform: uppercase;">Sojitra Hardhikkumar Rameshbhai</span></p>
                <p class="certificate_third" style="text-align: center;">has met the certification requirements and has demonstrated proficiency<br>by qualifying certification examination and is hereby certified to</p>
                <p class="certificate_cource h_other" style="text-align: center;text-transform: uppercase;">NDT LEVEL II<br>IN<br>RADIOGRAPHIC TESTING</p>
                <p class="certificate_forth" style="text-align: center;">Training course and examination conducted as per recommendations<br>of ASNT document SNT TC -1A 2016 edition</p>
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
            <td>80.00</td>
        </tr>
        <tr>
            <td>Specific</td>
            <td>70</td>
            <td>82.00</td>
        </tr>
        <tr>
            <td>Practical</td>
            <td>70</td>
            <td>84.00</td>
        </tr>
        <tr>
            <td>Average</td>
            <td>80</td>
            <td>82.00</td>
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
            <td>{!! Admin::ordinal(1) !!} Re-Certification</td>
            <td>10-11-2019</td>
            <td>10-11-2024</td>
        </tr>
    </table>
</div>
<div class="information customp ptop-50 padding-lr-20">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td>
                <p class="certificate_five">COURCE DIRECTOR & EXAMINER</p>
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
            <td colspan="2" width="68%" style="text-transform: capitalize;">Mr. Sojitra Hardikkumar Rameshbhai</td>
        </tr>
        <tr>
            <td>Company Name</td>
            <td>:</td>
            <td colspan="2">Self</td>
        </tr>
        <tr>
            <td>Reference Document</td>
            <td>:</td>
            <td colspan="2">SNT TC -1A 2016 Edition</td>
        </tr>
        <tr>
            <td>Written Practice No.</td>
            <td>:</td>
            <td colspan="2">DNIE/CERTI./07-2011/01 , Rev. 00</td>
        </tr>
        <tr>
            <td>Certificate No.</td>
            <td>:</td>
            <td colspan="2">RT-II/11-19/R-3526</td>
        </tr>
        <tr>
            <td>Method</td>
            <td>:</td>
            <td colspan="2">Radiographic Testing</td>
        </tr>
        <tr>
            <td>Level</td>
            <td>:</td>
            <td colspan="2">II</td>
        </tr>
        <tr>
            <td>Educational Background</td>
            <td>:</td>
            <td colspan="2">D.M.E.</td>
        </tr>
        <tr>
            <td>Experience ( NDT )</td>
            <td>:</td>
            <td colspan="2">06 years</td>
        </tr>
        <tr>
            <td>Near Vision Acuity</td>
            <td>:</td>
            <td colspan="2">J2</td>
        </tr>
        <tr>
            <td>Color Contrast</td>
            <td>:</td>
            <td colspan="2">OK</td>
        </tr>
        <tr valign="top">
            <td>Renewal</td>
            <td>:</td>
            <td colspan="2">Based on continuous satisfactory technical performance<br>( Initial Certificate No RT-II/11-14/2032 - DEV NDT )</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Issue Date</td>
            <td>Expiration Date</td>
        </tr>
        <tr>
            <td>Initial Certification</td>
            <td>:</td>
            <td>10/11/2014</td>
            <td>10/11/2019</td>
        </tr>
        <tr>
            <td>{!! Admin::ordinal(1) !!} Re-Certification</td>
            <td>:</td>
            <td>10/11/2019</td>
            <td>10/11/2024</td>
        </tr>
    </table>
</div>
<div class="information customp ptop-20">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td>
                <p class="certificate_five">COURCE DIRECTOR & EXAMINER</p>
                <p class="certificate_six h_other">BALDEV L. PATEL</p>
                <p class="certificate_seven">ASNT NDT Level-III, RT, UT, MT, PT, VT, ET, LT, IR, MFL</p>
                <p class="certificate_eight">Certificate No.: 200510</p>
            </td>
        </tr>
    </table>
</div>


</body>
</html>