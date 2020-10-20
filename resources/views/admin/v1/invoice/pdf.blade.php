<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice</title>
    <style type="text/css">
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
            padding: 20px;
            padding-bottom: 0px;
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
        .ptop-15 {
            padding-top: 18px;
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
            font-size: 15px;
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
            {{--list-style-image: url('{{ asset('image/logo/bullet.png') }}');--}}
            padding: 0;
            padding-left: 20px;
            padding-right: 10px;
            font-size: 15px;
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
        .header1 {
            font-size:18px;
            color: #E3007B;
            font-weight: bolder;
            letter-spacing:1.2px;
        }
        .header2 {
            font-size:16px;
            font-weight: 700;
            letter-spacing:1px;
        }
        .header3 {
            font-size:18px;
            color: #E3007B;
            font-weight: 700;
            letter-spacing:1px;
        }
        .header4 {
            font-size:16px;
        }
        .header5 {
            font-size:16px;
            font-weight: 900;
        }
        .text-normal {

        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .invtbl thead tr th {
            padding:5px 2px;
            text-transform: uppercase;
        }
        .invtbl tbody tr td ol {
            font-weight: 600;
            letter-spacing:0.8px;
            margin-top:5px;
        }
        .invtbl tbody tr td ol li {
            margin-bottom: 7px;
        }
        .invtbl tbody tr td ul {
            font-weight: 600;
            letter-spacing:0.8px;
            margin-top:5px;
            list-style-type:none;
            font-size:13px;
            padding:0px;
        }
        .invtbl tbody tr td ul li {
            margin-bottom: 7px;
        }
        .invtbl tbody tr td{
            min-height: 500px;
        }
        .terms_footer ol {
            font-size:14px;
        }
        .terms_footer ol li {
            margin-bottom: 7px;
        }
        .bank-detail {
            font-family: "Segoe UI",Arial,sans-serif;
            margin-top:5px;
            margin-left:20px;
            font-size:12px;
        }
    </style>
</head>
<body>
@php
$min_height = 320;
@endphp
@inject('invoice_controller', 'App\Http\Controllers\Administrator\v1\InvoiceController')
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

<div class="information customp ptop-30">
    <table width="100%" style="border-collapse: collapse; border: none; text-align: center;">
        <tr>
            <td width="70%" valign="top">
                <p class="header3 text-left">INVOICE TO,</p>
                <p class="header2 text-left">{{ $info->invoice_name }}</p>
                <p class="header4 text-left">{{ $info->invoice_address }}</p>
            </td>
            <td width="30%" valign="top">
                <p class="header1 text-right">INVOICE</p>
                <p class="header2 text-right">Invoice No.: {{ $invoice_number }}</p>
                <p class="header2 text-right">Date: {{ Admin::formateDate($info->invoice_date) }}</p>
            </td>
        </tr>
    </table>
</div>

<div class="information customp ptop-15">
    <table width="100%" border="1" class="invtbl" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th width="64%">Description</th>
                <th width="12%">Unit Rate</th>
                <th width="12%">Quantity</th>
                <th width="12%">Amount</th>
            </tr>
        </thead>

        <tbody>
            @foreach($invoice_services as $iservice)
            @if(in_array($iservice->charge_for,[1,2,3]))
                @php
                    $certificate_data = unserialize($iservice->certificate_detail);
                @endphp
                <tr>
                    <td valign="top" height="{{ $min_height }}" style="padding:5px 10px;">
                        <p class="header5 text-left"><b>Charge for {{ Admin::getInvoiceType($iservice->charge_for) }}</b></p>
                        <p class="text-normal text-left"><b>{!! $iservice->quotation_no ? 'Quotation Number: '.$iservice->quotation_no : '<br>' !!}</b></p>
                        <p class="text-normal text-left"><br><b>Certificates:</b></p>

                        <ol type="1" style="padding-left:10px;">
                            @foreach($certificate_data as $cdata)
                                @php
                                    $cinfo = $invoice_controller::infoCourse($cdata['cource_id'])
                                @endphp
                                <li>{{ $cinfo->short_name }} ({{ $cinfo->name }})</li>
                            @endforeach
                        </ol>
                        <p class="text-normal text-left"><br><b>Total No. of Certificates: {{ count($certificate_data) }}</b></p>

                    </td>
                    <td valign="top">
                        <ul style="margin-top:97px; text-align:center;">
                            @foreach($certificate_data as $cdata)
                                <li>{{ number_format($cdata['fees'],2) }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td valign="top">
                        <ul style="margin-top:97px; text-align:center;">
                            @foreach($certificate_data as $cdata)
                                <li>{{ $cdata['qty'] }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td valign="top">
                        <ul style="margin-top:97px; text-align:center;">
                            @foreach($certificate_data as $cdata)
                                <li>{{ number_format($cdata['subtotal'],2) }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @elseif(in_array($iservice->charge_for,[4]))
                <tr>
                    <td valign="top" height="{{ $min_height }}" style="padding:5px 10px;">
                        <p class="header5 text-left"><b>Charge for {{ Admin::getInvoiceType($iservice->charge_for) }}</b></p>
                        <p class="text-normal text-left"><b>{!! $iservice->quotation_no ? 'Quotation Number: '.$iservice->quotation_no : '<br>' !!}</b></p>
                        <p class="text-normal text-left"><br><b>Candidates Name:</b></p>

                        @php
                        $enroll_user_ids = explode(',',$iservice->certificate_to);
                        $enroll_user_list = $invoice_controller::enrollmentIdsInfo($enroll_user_ids);
                        @endphp
                        <ol type="1" style="padding-left:10px;">
                            @foreach($enroll_user_list as $ienuser)
                            <li>{{ $ienuser->front_fname.' '.$ienuser->front_lname }}</li>
                            @endforeach
                        </ol>
                        <p class="text-normal text-left"><br><b>Total No. of Certificates: {{ count($enroll_user_ids) }}</b></p>

                    </td>
                    <td valign="top">
                        <ul style="margin-top:85px; text-align:center;">
                            <li>{{ number_format($iservice->vision_certificate_fees,2) }}</li>
                        </ul>
                    </td>
                    <td valign="top">
                        <ul style="margin-top:85px; text-align:center;">
                            <li>{{ count($enroll_user_ids) }}</li>
                        </ul>
                    </td>
                    <td valign="top">
                        <ul style="margin-top:85px; text-align:center;">
                            <li>{{ number_format($iservice->service_total,2) }}</li>
                        </ul>
                    </td>
                </tr>
            @elseif(in_array($iservice->charge_for,[5]))
                <tr>
                    <td valign="top" height="{{ $min_height }}" style="padding:5px 10px;">
                        <p class="header5 text-left"><b>Charge for {{ Admin::getInvoiceType($iservice->charge_for) }}</b></p>
                        <p class="text-normal text-left"><b>{!! $iservice->quotation_no ? 'Quotation Number: '.$iservice->quotation_no : '<br>' !!}</b></p>
                        <p class="text-normal text-left"><br>Consultancy Services charge should be calculated based on date
                            <br>From <b>{{ Admin::formateDate($iservice->const_from) }}</b> To <b>{{ Admin::formateDate($iservice->const_to) }}</b>
                        </p>
                        <p class="text-normal text-left"><br><b>Total No. of Days: {{ $iservice->const_days }}</b></p>

                    </td>
                    <td valign="top">
                        <ul style="margin-top:78px; text-align:center;">
                            <li>{{ number_format($iservice->const_charge,2) }}</li>
                        </ul>
                    </td>
                    <td valign="top">
                        <ul style="margin-top:78px; text-align:center;">
                            <li>{{ $iservice->const_days }}</li>
                        </ul>
                    </td>
                    <td valign="top">
                        <ul style="margin-top:78px; text-align:center;">
                            <li>{{ number_format($iservice->service_total,2) }}</li>
                        </ul>
                    </td>
                </tr>
            @elseif(in_array($iservice->charge_for,[8]))
                <tr>
                    <td valign="top" height="{{ $min_height }}" style="padding:5px 10px;">
                        <p class="header5 text-left"><b>Charge for {{ Admin::getInvoiceType($iservice->charge_for) }}</b></p>
                        <p class="text-normal text-left"><b>{!! $iservice->quotation_no ? 'Quotation Number: '.$iservice->quotation_no : '<br>' !!}</b></p>

                    </td>
                    <td valign="top">
                        <ul style="margin-top:10px; text-align:center;">
                            <li>{{ number_format($iservice->service_total,2) }}</li>
                        </ul>
                    </td>
                    <td valign="top">
                        <ul style="margin-top:10px; text-align:center;">
                            <li>1</li>
                        </ul>
                    </td>
                    <td valign="top">
                        <ul style="margin-top:10px; text-align:center;">
                            <li>{{ number_format($iservice->service_total,2) }}</li>
                        </ul>
                    </td>
                </tr>
            @endif
            @endforeach
            <tr>
                <td colspan="3" style="padding:5px 10px;font-size:16px; font-weight: bolder;"><b>Rupees: {{ ucwords(Admin::convertCurrency($invoice_grand_total)) }} Only</b></td>
                <td style="padding:5px 10px;font-size:16px; font-weight: bolder;text-align:center;">{{ number_format($invoice_grand_total,2) }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="information terms_footer customp ptop-10">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td>
                <ol>
                    <li>Cheque should be drawn as <b>"DEV NDT INSPECTION & ENGINEERING"</b></li>
                    <li>Subject to Ahmedabad Jurisdication</li>
                    <li>PAN: AQJPP1263J</li>
                    <li>E.&O.E</li>
                    <li>Payment should be acceptable from following details:<br>
                        <table class="bank-detail">
                            <tr>
                                <td>BANK NAME</td>
                                <td>:</td>
                                <td>STATE BANK OF INDIA (BAPUNAGAR)</td>
                            </tr>
                            <tr>
                                <td>IFSC CODE</td>
                                <td>:</td>
                                <td>SBIN0060434</td>
                            </tr>
                            <tr>
                                <td>ACCOUNT NAME</td>
                                <td>:</td>
                                <td>DEV NDT INSPECTION & ENGINEERING</td>
                            </tr>
                            <tr>
                                <td>ACCOUNT TYPE</td>
                                <td>:</td>
                                <td>CURRENT ACCOUNT</td>
                            </tr>
                            <tr>
                                <td>ACCOUNT NO</td>
                                <td>:</td>
                                <td>31991599479</td>
                            </tr>
                            <tr>
                                <td>GOOGLE PAY & PAYTM</td>
                                <td>:</td>
                                <td>96380 53503</td>
                            </tr>
                        </table>
                    </li>
                </ol>
            </td>
        </tr>
    </table>
</div>

<div class="information customp" style="padding-top: 10px;text-align:right;">
    <table width="100%" style="border-collapse: collapse; border: none;">
        <tr>
            <td>
                <p class="certificate_six text-right">For, DEV NDT INSPECTION & ENGINEERING</p>
            </td>
        </tr>
    </table>
</div>

</body>
</html>