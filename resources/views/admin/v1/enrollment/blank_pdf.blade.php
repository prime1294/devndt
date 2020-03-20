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
</style>
</head>
<body>
<div class="information customp">
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
                    <span class="h_address">{{ $user->address }}</span>
                    <span class="h_other">&nbsp;&nbsp;&nbsp;M. {{ $user->mo_number.', '.$user->alt_number }}</span>
                    <span class="h_other">&nbsp;&nbsp;&nbsp;e-mail : {{ $user->email_id }}</span>
                    <span class="h_other">&nbsp;&nbsp;&nbsp;{{ $user->website }}</span>
                </p></td>
        </tr>
    </table>
</div>
</body>
</html>