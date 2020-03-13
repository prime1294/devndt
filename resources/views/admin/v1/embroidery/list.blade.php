@extends('admin.v1.layout.app', ['title' => 'Embroidery Design'])

@section('content')
{!!Html::style('/public/admin/theme1/plugins/seiyria-bootstrap-slider/css/bootstrap-slider.min.css')!!}
<style type="text/css">
.slider-horizontal .slider-selection{
  background: #3072ac !important;
}
.filterdiv {
  display: none;
}
.info-box-number {
  font-size: 37px;
}
.destination-list .dropdown-toggle {
  position: absolute;
  float: right !important;
  right: 9px;
  top: 25px;
}
 .destination-list .dropdown-menu {
 top: 46px;
 min-width: 100px;
 right: 17px;
 left: auto;
}
/*.info-box .dropdown-menu > li > a {
  text-transform:none;
}
.info-box .dropdown-menu > li > a > .glyphicon, .dropdown-menu > li > a > .fa, .dropdown-menu > li > a > .ion {
  margin-right:1px;
} */
.affix {
  top: 0;
  width: 100%;
  z-index: 874 !important;
  /*margin-top: 50px;*/
  right: 0px;
}

.affix + .affix_cont {
  padding-top: 200px;
}
.affix_cont {
  /* background-color: red;
  min-height: 100px; */
  background-color: #ECF0F5;
  padding: 10px 12px;
}
#custom-search-input {
  margin:0;
  /* margin-top: 10px; */
  padding: 0;
  width: 40%;
  display: inline-block;
  margin-bottom: -7px;
}


#custom-search-input button {
  border: 0;
  background: none;
  /** belows styles are working good */
  padding: 2px 5px;
  margin-top: 2px;
  position: relative;
  left: -28px;
  /* IE7-8 doesn't have border-radius, so don't indent the padding */
  margin-bottom: 0;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  color:#D9230F;
}
@media only screen and (max-width: 768px) {
.affix {
  /*margin-top: 100px;*/
  right: 0;
  width: 100%;
}
.affix_cont {
  padding: 0px 10px;
}
#custom-search-input {
  margin-bottom: 10px;
  width: 77%;
  margin-top: 8px;
}
.searchbtn {
  width: 83px;
  margin-left: -31px;
  margin-right: -5px;
  padding: 6px;
  margin-top: 8px;
}
}
.hitfit {
  object-fit: contain;
  object-position: center;
  height: 250px;
  background:white;
}
.slider.slider-horizontal {
  width: 100%;
}
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="glyphicon glyphicon-grain"></i> Embroidery Design
    <small>Manage Embroidery Design</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Embroidery Design</li>
  </ol>
</section>

<section class="content">
<div class="row">
  <div class="col-md-12">
    <div class="affix_cont" data-spy="affix" data-offset-top="260">
      <!-- Searchbox -->
      <div id="custom-search-input">
          <div class="input-group">
              <input type="text" id="search-client" value="{{ @$_GET['search'] }}" class="search-query form-control" placeholder="Search by design number / name" />
              <span class="input-group-btn">
                  <button class="btn btn-danger" type="button">
                      <span class=" glyphicon glyphicon-search"></span>
                  </button>
              </span>
          </div>
      </div>
      <a href="{{ route('add.embroidery.design') }}" class="btn btn-primary searchbtn pull-right"><i class="fa fa-plus"></i> Add New</a>
      <!-- end of searchbox -->
    </div>
  </div>
</div>
<div class="row gridview searchable-container">
    <div class="destination-list">
    <div class="infinite-scroll">
    @foreach($design_list as $row)
    <div class="col-lg-3 col-md-3 col-xs-12 fullscreen searchablediv">
				<div class="tour-cate">
          <!--  action -->
          <button type="button" class="btn btn-default btn-xs mr-sm pull-right dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
               <i class="fa fa-chevron-down"></i></button>
               <ul class="dropdown-menu" role="menu">
               <li><a href="{{ route('edit.embroidery.design',$row->id) }}"><i class="fa fa-edit"></i> Edit</a></li>
               <li><a href="{{ route('delete.design',['id'=>$row->id,'type'=>'1']) }}" onclick="return confirmbox();"><i class="fa fa-trash"></i> Delete</a></li>
             </ul>
          <!-- end action -->
					<img src="{{ asset($row->image) }}" class="img-responsive zoomme hitfit" />
					<div class="description">
            <div class="row">
              <div class="col-md-12">
              <p class="destination-title text-center-mobile">{{ $row->name }}<br><small class="text-muted">{{ $row->design_type_name != "" ? $row->design_type_name : "-" }}</small></p>
              </div>
              <div class="col-md-4 col-xs-4 text-center border-right">
                <label>Stitch</label>
                <p class="text-overflow">{{ $row->stitch != "" ? $row->stitch : "-" }}</p>
              </div>
              <div class="col-md-4 col-xs-4 text-center border-right">
                <label>Color</label>
                <p class="text-overflow">{{ $row->color != "" ? $row->color : "-" }}</p>
              </div>
              <div class="col-md-4 col-xs-4 text-center">
                <label>Area</label>
                <p class="text-overflow">{{ $row->area != "" ? $row->area : "-" }}</p>
              </div>
            </div>
						<div class="tour-tag giverating" data-id="{{ $row->id }}"><i class="fa {{ $row->is_fav == 1 ? "fa-star" : "fa-star-o" }}"></i></div>
					</div>
				</div>
				</div>
    @endforeach
    <div class="col-md-12">
      <center>{!! $design_list->render() !!}</center>
    </div>
    </div>
    </div>

</div>
</section>

<div class="buy-now">
<a data-toggle="modal" data-target="#filterModal" class="btn btn-primary buy-now-btn">
<i class="glyphicon glyphicon-filter" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<!-- Modal -->
<div id="filterModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Filter Design</h4>
      </div>
      <form method="get" action="{{ route('embroidery.design') }}">
      <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="search">Stitch</label><br>
                <?php
                $getstitchval = isset($_GET['stitch_range']) ? explode(',',$_GET['stitch_range']) : array($min_stitch,$max_stitch);
                ?>
                <input type="text" name="stitch_range" value="" class="range-slider form-control" data-slider-min="{{ $min_stitch }}" data-slider-max="{{ $max_stitch }}"
                         data-slider-step="5" data-slider-value="[{{ $getstitchval[0] }},{{ $getstitchval[1] }}]" data-slider-orientation="horizontal"
                         data-slider-selection="before" data-slider-tooltip="show" data-slider-id="blue">
                <!-- <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="text" id="search" name="search" value="{{ @$_GET['search'] }}" class="form-control">
                </div> -->
            </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="area">Area</label><br>
                <input type="text" id="area" name="area" value="{{ @$_GET['area'] }}" class="form-control">
            </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="color">Color</label><br>
                <input type="text" id="color" name="color" value="{{ @$_GET['color'] }}" class="form-control">
            </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="color">Design</label>
                <select id="design" name="design" class="form-control">
                  <option value="">Show All</option>
                  <option value="1" {{ @$_GET['design'] == "1" ? "selected" : "" }}>Only Favourite</option>
                  <option value="0" {{ @$_GET['design'] == "0" ? "selected" : "" }}>Only Least favourite</option>
                </select>
            </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-filter"></i>Apply Filter</button>
        <a href="{{ route('embroidery.design') }}" class="btn btn-danger"><i class="fa fa-times"></i> Clear Filter</a>
      </div>
      </form>
    </div>

  </div>
</div>
<script type="text/javascript">
  $.fn.slider = null;
</script>
{!!Html::script('/public/admin/theme1/plugins/seiyria-bootstrap-slider/bootstrap-slider.min.js')!!}

<script type="text/javascript">
  $(document).ready(function() {
    $('.range-slider').slider();
  });
</script>

<script type="text/javascript">
  $('ul.pagination').hide();
  $(function() {
      $('.infinite-scroll').jscroll({
          autoTrigger: true,
          loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
          padding: 0,
          nextSelector: '.pagination li.active + li a',
          contentSelector: 'div.infinite-scroll',
          callback: function() {
              $('ul.pagination').remove();
          }
      });
  });
</script>

<script type="text/javascript">


//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('#search-client');

//on keyup, start the countdown
$input.on('keyup', function () {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval);
});

//on keydown, clear the countdown
$input.on('keydown', function () {
  clearTimeout(typingTimer);
});

//user is "finished typing," do something
function doneTyping () {
  //do something
  if($input.val() != "") {
    window.location.href=window.location.pathname+"?"+$.param({'search':$input.val()});
  } else {
    window.location.href=window.location.pathname;
  }
}

var datatable;
var AjaxData = {"_token": "{{ csrf_token() }}"};

$(document).on('click', '.filterbtn', function() {
  $('.filterdiv').slideToggle();
});

$(document).on('click', '.giverating', function() {
  $(this).children('i').toggleClass("fa-star fa-star-o");
  var status = $(this).children('i').hasClass('fa-star') ? 1 : 0;
  var id = $(this).attr('data-id');
  var url = '{{ route('bookmark.design',":ID") }}';
  url = url.replace(":ID",id);
  $.ajax({
    url:url,
    type:'POST',
    data:"status="+status+"&_token={{ csrf_token() }}",
    success:function(e) {
      console.log(e);
    }
  });

});


$(document).on('change', '#filter_by', function() {
  AjaxData.filter_by = $(this).val();
  datatable.ajax.reload();
});


$(document).on('click', '.clearfilter', function() {
  $("#filter_by").val('').trigger('change') ;
  $('.daterange span').html('<i class="fa fa-calendar"></i> Date range picker');
  AjaxData.filter_by = "";
  AjaxData.startdate = "";
  AjaxData.enddate = "";
  datatable.ajax.reload();
  $(".filterdiv").slideUp();
});

function changeDateRange() {
  AjaxData.startdate = $("#filterStart").val();
  AjaxData.enddate = $("#filterEnd").val();
  datatable.ajax.reload();
}

</script>

@endsection
