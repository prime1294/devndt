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
        padding: 5px;
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
        font-size: 22px;
        font-weight: bolder;
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
    .txt_red {
        color: red;
    }
    ul {
        list-style-image: url('{{ asset('image/logo/bullet.png') }}');
        padding: 0;
        padding-left: 20px;
        padding-right: 10px;
        font-size: 14px;
    }
    ol {
        counter-reset: list;
        padding: 0;
    }
    ol > li {
        list-style: none;
    }
    ol > li:before {
        content: "(" counter(list, decimal) ") ";
        counter-increment: list;
    }
    .oth_tbl_p {
        font-weight: bold;
        font-size: 15px;
    }
    .chksize {
        max-width: 18px;
        margin-right: 5px;
    }
    .plsep {
        margin-left: 15px;
    }
</style>
</head>
<body>

<div class="information customp">
<table width="100%" style="border-collapse: collapse; border: none;">
    <tr>
        <td width="33%"><img src="{{ asset('image/logo/favicon2.png') }}" style="max-width: 100px" alt="Mini Logo"/></td>
        <td width="67%"><img src="{{ asset('image/logo/logo1.png') }}" style="max-width: 300px" alt="Max Logo"/></td>
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
<div class="information customp ptop-20">
    <table border="1px" width="100%" style="border-collapse: collapse; border: none; text-align: center;">
        <tr>
            <td><p class="certificate_name h_address">VISION ACUITY RECORD</p></td>
        </tr>
    </table>
</div>
<div class="information customp ptop-10">
    <table border="1px" width="100%" style="border-collapse: collapse; border: none; text-align: center; font-weight: bold; font-size: 15px;">
        <tr>
            <td width="20%;">APPLICANT ID No.</td>
            <td width="40%;">APPLICANT NAME</td>
            <td width="20%;">Issue Date</td>
            <td width="20%;">Expiration Date</td>
        </tr>
        <tr>
            <td><p class="txt_red">1410</p></td>
            <td><p class="txt_red">Mr. Aamir Vhora</p></td>
            <td><p class="txt_red">07/04/2018</p></td>
            <td><p class="txt_red">07/04/2019</p></td>
        </tr>
    </table>
</div>
<div class="information customp ptop-10">
    <table border="1px" width="100%" style="border-collapse: collapse; border: none; text-align: center; font-weight: bold; font-size: 15px;">
        <tr>
            <td width="50%">Anup Engineering</td>
            <td width="50%">Ahmedabad</td>
        </tr>
    </table>
</div>
<div class="information customp ptop-10">
    <table border="1px" width="100%" style="border-collapse: collapse; border: none; text-align: center;">
        <tr>
            <td><p class="txt_red" style="font-size: 16px; font-weight: bolder;">Requirements : </p></td>
        </tr>
    </table>
</div>
<div class="information customp">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td>
                <ul>
                    <li>Applicant is required to pass an eye examination,with or without eye correction to prove
                        <ol>
                            <li>Near Vision on Jaeger Chart or equilent at 12 inches</li>
                            <li>Color Perception on Ishiara Eye Chart for Red/ Green and Blue/ Yellow differentiation.</li>
                            <li>Gray shades on Gray Shade Chart</li>
                        </ol>
                    </li>
                    <li>This should be administered annually accordance with ASNT SNT TC-1A.</li>
                    <li>The eye examination must be administered by an NDT Level III, Optometrist, Medical Doctor, Registered Nurse or Certified Physician’s Assistant.</li>
                    <li>Do not alter the  printed test  requirements.</li>
                    <li>Do not add any medical terminology.</li>
                    <li>Check only one box per test.</li>
                    <li>Visual acuity Records which  do not comply with above requirements will not be accepted.</li>
                </ul>
            </td>
        </tr>
    </table>
</div>
<div class="information customp ptop-5">
    <table border="1px" width="100%" style="border-collapse: collapse; border: none; text-align: center;">
        <tr>
            <td><p class="txt_red" style="font-size: 16px; font-weight: bolder;">Test Results : </p></td>
        </tr>
    </table>
</div>
<div class="information customp ptop-5 primetbl">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td valign="top" style="padding: 0px;" colspan="4"><p class="oth_tbl_p">TESTS</p></td>
        </tr>
        <tr>
            <td valign="bottom"><p class="oth_tbl_p">1. Near distance vision</p></td>
            <td valign="top" align="center">Meets without<br>Eye correction</td>
            <td valign="top" align="center">Meets with<br>Eye correction</td>
            <td valign="top" align="center">Does not meet</td>
        </tr>
        <tr>
            <td><img src="{{ asset('image/logo/done.png') }}" class="chksize plsep" style="margin-top:1px; display: inline-block;" alt="checkbox"/> <span style="display: inline-block;" class="oth_tbl_p">Jaeger # 1</span></td>
            <td align="center"><img src="{{ asset('image/logo/done.png') }}" class="chksize" alt="checkbox"/></td>
            <td align="center"><img src="{{ asset('image/logo/checkbox.png') }}" class="chksize" alt="checkbox"/></td>
            <td align="center"><img src="{{ asset('image/logo/checkbox.png') }}" class="chksize" alt="checkbox"/></td>
        </tr>
        <tr>
            <td><img src="{{ asset('image/logo/checkbox.png') }}" class="chksize plsep" style="margin-top:1px; display: inline-block;" alt="checkbox"/> <span style="display: inline-block;" class="oth_tbl_p">Jaeger # 2</span></td>
            <td align="center"><img src="{{ asset('image/logo/done.png') }}" class="chksize" alt="checkbox"/></td>
            <td align="center"><img src="{{ asset('image/logo/checkbox.png') }}" class="chksize" alt="checkbox"/></td>
            <td align="center"><img src="{{ asset('image/logo/checkbox.png') }}" class="chksize" alt="checkbox"/></td>
        </tr>
        <tr>
            <td colspan="4"><span class="plsep">At a distance of not less than12 in (30.5cm)</span></td>
        </tr>
    </table>
</div>
<div class="information customp ptop-5 primetbl">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td colspan="3" style="padding: 0px; padding-left: 5px;"><p class="oth_tbl_p" style="padding: 0px;">2.	Color Vision</p></td>
        </tr>
        <tr>
            <td valign="top"><span class="plsep">For testing used</span> </td>
            <td align="center"  valign="top">Meets</td>
            <td align="center" valign="top">Does not meet</td>
        </tr>
        <tr>
            <td><img src="{{ asset('image/logo/done.png') }}" class="chksize plsep" style="margin-top:1px; display: inline-block;"  alt="checkbox"/> <span style="display: inline-block;" class="oth_tbl_p">Ishihara Eye Chart</span></td>
            <td align="center"><img src="{{ asset('image/logo/done.png') }}" class="chksize" alt="checkbox"/></td>
            <td align="center"><img src="{{ asset('image/logo/checkbox.png') }}" class="chksize" alt="checkbox"/></td>
        </tr>
        <tr>
            <td><span class="plsep">Red/green differentiation</span></td>
            <td align="center"><img src="{{ asset('image/logo/done.png') }}" class="chksize" alt="checkbox"/></td>
            <td align="center"><img src="{{ asset('image/logo/checkbox.png') }}" class="chksize" alt="checkbox"/></td>
        </tr>
        <tr>
            <td><span class="plsep">Blue/ yellow differentiation</span></td>
            <td align="center"><img src="{{ asset('image/logo/done.png') }}" class="chksize" alt="checkbox"/></td>
            <td align="center"><img src="{{ asset('image/logo/checkbox.png') }}" class="chksize" alt="checkbox"/></td>
        </tr>
    </table>
</div>
<div class="information customp ptop-10">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td>
                <p class="">This is to certify that I, <u><b>BALDEV PATEL</b></u>  Administrated an eye exam to  <b>Mr. Aamir Vhora</b> on <b>07/04/2018</b></p>
                <p class="">who demonstrated the vision capabilities indicated above . Check one of the following:-</p>
            </td>
        </tr>
    </table>
</div>
<div class="information customp ptop-10">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td><img src="{{ asset('image/logo/checkbox.png') }}" class="chksize plsep" style="margin-top:1px; display: inline-block;" alt="checkbox"/> <span style="display: inline-block;" class="oth_tbl_p">Optometrist</span></td>
            <td><img src="{{ asset('image/logo/checkbox.png') }}" class="chksize plsep" style="margin-top:1px; display: inline-block;" alt="checkbox"/> <span style="display: inline-block;" class="oth_tbl_p">Medical doctor</span></td>
            <td><img src="{{ asset('image/logo/done.png') }}" class="chksize plsep" style="margin-top:1px; display: inline-block;" alt="checkbox"/> <span style="display: inline-block;" class="oth_tbl_p">NDT Level III</span></td>
        </tr>
    </table>
</div>
<div class="information customp ptop-30">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td>
                <p class="certificate_six txt_red">BALDEV L. PATEL</p>
                <p class="certificate_seven">ASNT NDT Level-III, RT, UT, MT, PT, VT, ET, LT, IR, MFL</p>
                <p class="certificate_eight">Certificate No.: 200510</p>
            </td>
        </tr>
    </table>
</div>

</body>
</html>