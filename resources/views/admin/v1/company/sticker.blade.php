<?php
$user = Sentinel::check();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Company Sticker</title>
<style type="text/css">
    @page {
        margin: 0px;
        size: 210mm 74.25mm;
    }

    body {
        margin: 0px;
        padding: 20px;
        background:#FFFFFF;

    }
    * {
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
        font-size: 28px;
        font-weight: bolder;
    }
    .normal-text {
        margin-bottom: 2px;
        font-weight: bold;
        text-align: left;
        font-size: 20px;
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
            <td>
                <p class="normal-text">To,</p>
                <p class="form_title" style="text-align: left;">{{ $info->person_fname.' '.$info->person_lname }}</p>
                <p class="normal-text" style="text-align: left;">{{ $info->company_name }}</p>
                <p class="normal-text" style="text-align: left;">{{ $info->address.' '.$info->city.' '.$info->district.' '.$info->state.' - '.$info->pincode }}</p>
                <p class="normal-text" style="text-align: left;">Mo: +91 {{ substr($info->mobile, 0, 5).' '.substr($info->mobile, 5, 10) }}</p>
            </td>
        </tr>
    </table>
</div>
</body>
</html>