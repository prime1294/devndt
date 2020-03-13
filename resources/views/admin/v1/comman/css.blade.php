
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta property="og:title" content="{{ config('setting.app_name') }} Inspection & Engineering" />
<meta property="og:url" content="{{ env('APP_URL') }}" />
<meta property="og:description" content="{{ config('setting.app_name') }} Inspection & Engineering">
<meta property="og:image" content="{{ asset(config('setting.favicon2')) }}">
<meta property="og:type" content="article" />
<meta property="og:locale" content="en_US" />


<link rel="apple-touch-icon-precomposed" href="{{ asset(config('setting.favicon2')) }}">
<link rel="icon" href="{{ asset(config('setting.favicon2')) }}">

{!!Html::style('/public/admin/theme1/bower_components/bootstrap/dist/css/bootstrap.min.css')!!}
{!!Html::style('https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css')!!}
{!!Html::style('/public/admin/theme1/bower_components/font-awesome/css/font-awesome.min.css')!!}
{!!Html::style('/public/admin/theme1/bower_components/Ionicons/css/ionicons.min.css')!!}
{!!Html::style('/public/admin/theme1/general/toogle/css/bootstrap-toggle.min.css')!!}
{!!Html::style('/public/admin/theme1/plugins/bootstrap-toggle/bootstrap-toggle.min.css')!!}
{!!Html::style('/public/admin/theme1/plugins/iCheck/square/blue.css')!!}
{!!Html::style('/public/admin/theme1/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')!!}
{!!Html::style('/public/admin/theme1/dist/css/AdminLTE.min.css')!!}
{!!Html::style('/public/admin/theme1/dist/css/skins/_all-skins.min.css')!!}

{!!Html::style('/public/admin/theme1/bower_components/bootstrap-daterangepicker/daterangepicker.css')!!}
{!!Html::style('/public/admin/theme1/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')!!}

{!!Html::style('/public/admin/theme1/general/remodal/remodal.css')!!}
{!!Html::style('/public/admin/theme1/general/remodal/remodal-default-theme.css')!!}

{!!Html::style('/public/admin/theme1/bower_components/select2/dist/css/select2.min.css')!!}
{!!Html::style('/public/admin/theme1/general/css/toastr.min.css')!!}
{!!Html::style('/public/admin/theme1/general/css/offline-theme-slide.css')!!}
{!!Html::style('/public/admin/theme1/general/css/offline-language-english.min.css')!!}
{!!Html::style('/public/admin/theme1/general/ripplebtn/ripple.min.css')!!}
{!!Html::style('/public/admin/theme1/general/jcrop/css/jquery.Jcrop.min.css')!!}
{!!Html::style('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic')!!}


<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<style type="text/css">
    .ui-widget.ui-widget-content
    {
        border: none;
    }
    .ui-autocomplete-row
    {
        background-color: white;
        font-weight:bold;
        z-index: 10000;
        width: auto;
    }
    .ui-autocomplete-row:hover
    {
        background-color: white;
        padding: 0px;
        color: black;
        font-weight: bold;
    }
    .ui-menu .ui-menu-item-wrapper {
        padding: 10px;
        color: black;
        font-weight: bold;
    }
    .ui-autocomplete-row img {
        padding: 0;
    }

    .ui-menu-item .ui-menu-item-wrapper:hover
    {
        color: black;
        font-weight: bold;
    }

    .ui-state-active,
    .ui-widget-content .ui-state-active,
    .ui-widget-header .ui-state-active,
    a.ui-button:active,
    .ui-button:active,
    .ui-button.ui-state-active:hover {
        background: white;
        border: none;
    }
    .ui-widget-content .ui-state-active img {
        border-left: 1px solid black;
        border-bottom: 1px solid black;
    }

    .main-header .logo {
        text-transform: uppercase;
    }
    .requiredicon {
        font-weight: bolder;
    }
    .bg-info:hover {
        color: black;
        cursor: pointer;
    }
    .filterdiv {
        display: none;
    }
        /* .modal {
          z-index: 9999 !important;
        } */
#photozoom {
    width: 300px;
}
.w100 {
    width: 100%;
}
#opening_type {
    color: red;
}
.zoom-form {
    max-width: 350px !important;
    padding: 0px !important;
}
.mb-10 {
  margin-bottom: 10px;
}
#image_file {
  display: none;
}
.remodal {
  background: transparent !important;
}
.jcrop-keymgr {
  display: none !important;
}
.step2 {
  /* overflow-y:scroll;
  overflow-x: scroll; */
  width: 100%;
}
/* .col-sm-6:has(div) { display: none !important; } */

.text-overflow {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.border-right {
  border-right: 1px solid #f2f2f2;
}
.bg-white {
  background-color: #FFFFFF;
}
.centered-modal.in {
    display: flex !important;
}
.centered-modal .modal-dialog {
    margin: auto;
}


.gridview .destination-list{width:100%;float:left;padding:0px 6px;}

.gridview .destination-list .tour-cate{
display: flex;
flex-direction: column;
border-radius: 15px;
border: 1px solid #dfdddd;
margin-top:10px ;
}

.gridview .destination-title {
  font-size: 15px;
  font-weight: 900;
  color: #3c8dbc;
  margin-top: 25px;
}

.gridview .fullscreen {
  padding-right: 5px;
padding-left: 5px;
}

.gridview .destination-list .tour-cate .description{
background: #fff;
padding: 0px 10px;
position: relative;
border-radius: 15px 15px 0 0;
margin-top: -15px;
position: relative;
}
.gridview .destination-list .tour-cate .description h1{margin:0px;padding: 16px 0px;font-size: 15px;line-height: 21px;color: #3D3D3D;font-weight: bold;}
.gridview .destination-list .tour-cate .description .priceing-info span{width:50%;padding: 10px 15px 10px 0;/* vertical-align: middle; *//* display: grid; */font-size: 15px;display: inline-block;color: #7c7c7c;}
.gridview .destination-list .tour-cate .description .priceing-info span:last-child{text-align:center;float: right;border: 1px solid #545151;padding: 5px 15px;border-radius: 30px;font-size: 18px;font-weight: bold;color: #3d3d3d;border: 1px solid #dfdddd;}
.gridview .destination-list .tour-cate .description .priceing-info{
margin: 15px 0;
justify-content: left;
align-items: center;
width: 100%;
float: left;
}
.gridview .destination-list .tour-cate img {    max-width: 100%;    border-radius: 15px 15px 0 0;}
.gridview .destination-list .tour-tag {
background: #ff6600;
width: 16%;
left: 0;
right: 0;
position: absolute;
top: -20px;
left: 0;
right: 0;
margin: 0 auto;
text-align: center;
padding: 7px 11px;
color: #fff;
font-size: 17px;
border-radius: 30px;
}
/* .info-box.active:after
 {
  content: "";
  position: relative;
  bottom: -49px;
  left: 16%;
  border: 15px solid transparent;
  border-top-color: #3c8dbc ;
} */
.tbldblclick tbody tr {
  cursor: pointer;
}
.inline {
  display: inline-block !important;
}
.sidebar-action-icon {
  float: right !important;
  margin-top: 8px !important;
  margin-right: 5px !important;
}
.sidebar-action-icon .label .fa {
  margin-top: 2px !important;
}
.mt-sm {
  margin-top: 10px;
}
.mt-lg {
  margin-top: 30px;
}
.mt-24 {
  margin-top: 24px;
}
.mr-sm {
  margin-right: 10px;
}
.modal-content .overlay {
    position: fixed;
    display: none;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    z-index: 3;
    cursor: pointer;
}

.modal-content .overlay .text{
    position: absolute;
    top: 50%;
    left: 50%;
    font-size: 50px;
    color: white;
    transform: translate(-50%,-50%);
    -ms-transform: translate(-50%,-50%);
}
.form-devider
{
	border-top: 1px #EBEFF5 solid;
    margin-bottom: 10px;
}
.image-preview-clear {
  margin-bottom: 0px !important;
}
.box-body button[type='submit'] , .box-body button[type='reset'] , .box-body button[type='button'], .box-body a[class='btn']
{
	margin-bottom: -10px;
}
.fbpickermain {
  margin-bottom: 10px;
}
#fbinput{
  display: none;
}
.fbpiker{
  cursor: pointer;
  max-width:100px;
}
#fbholder{
  max-width:100px;
  height:100px;
  border-radius: 10px;
}
#fbholdernew{
  max-width:100px;
  height:100px;
  border-radius: 10px;
}
.fbremove {
  display: none;
  line-height: 12px;
  font-size: 10pt;
  margin-top: -5px;
  margin-left: -8px;
  border-radius: 50%;
  border: none;
  position: absolute;
  z-index: 99;
  background: red;
  padding: 4px;
  color: white;
 }
 .select2-container .select2-selection--single
 {
   height: 35px;
 }
 .select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 0px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
  top: 5px;
}
.select2-container--default .select2-selection--multiple {
  border: 1px solid #ccc;
  border-radius: 0px;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
  background-color: #3c8dbc;
  border: 1px solid #3c8dbc;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
  color: #FFFFFF;
}
.input-group-select {
  width: 37% !important;
}
.input-group-input {
  width: 63% !important;
}
.image-preview-input {
    position: relative;
	overflow: hidden;
	margin: 0px;
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.image-preview-input input[type=file] {
	position: absolute;
	top: 0;
	right: 0;
	margin: 0;
	padding: 0;
	font-size: 20px;
	cursor: pointer;
	opacity: 0;
	filter: alpha(opacity=0);
}
.image-preview-input-title {
    margin-left:2px;
}

.mb24-mobile.clearfilter {
    margin-top: 24px !important;
}
div.dataTables_wrapper div.dataTables_filter input {
    margin-left: 0px !important;
    margin-bottom: 10px !important;
}
    .buy-now {
        z-index: 10000;
    }
    .buy-now .buy-now-btn {
        z-index: 10001;
    }

/* Note: Try to remove the following lines to see the effect of CSS positioning */
@media (min-width: 768px) {
.affix {
  top: 20px;
  width: 25.33%;
}
}

@media only screen and (max-width: 768px) {
  .text-center-mobile {
    text-align: center !important;
    display: block;
  }
  .border-right-mobile {
    border-right: 1px solid #f2f2f2;
  }
  .fixtablemobile {
    width: 1000px;
  }
    .fixtablemobile2 {
        width: 1300px;
    }
    div.dataTables_wrapper div.dataTables_filter input {
        display: block !important;
        margin-left: 10px !important;
        margin-top: 10px;
    }
    .main-header .logo {
        background: #367fa9 !important;
    }
    .hidemobile {
        display: none;
    }
    .rmmobrowpadding .row {
        margin-right: 0px;
        margin-left: 0px;
    }

    .mb24-mobile {
        margin-bottom: 24px !important;
    }

    .mb20-mobile {
        margin-bottom: 20px !important;
    }
}
    /* Layout */

</style>
