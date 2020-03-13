@extends('admin.v1.layout.app', ['title' => 'Add Machine'])

@section('content')

<style type="text/css">
#fbholdernew {
    max-width: none;
    height: 143px !important;
}
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-user"></i> Machine
    <?php if($type == "edit") {
      ?><small>Edit Machine</small><?php
    } else {
      ?><small>Add New Machine</small><?php
    } ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <?php if($type == "edit") {
      ?><li class="active">Edit Machine</li><?php
    } else {
      ?><li class="active">Add New Machine</li><?php
    } ?>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <?php if($type == "edit") {  ?>
        <form method="post" action="{{ route('machine.update',[$pinfo->id]) }}" enctype="multipart/form-data">
        <?php } else { ?>
        <form method="post" action="{{ route('machine.register') }}" enctype="multipart/form-data">
        <?php } ?>
          {!! csrf_field() !!}
            <div class="row">
              <div class="col-md-12">
                <!-- <center> -->
                  <div class="fbpickermain">
                      <div class=fbpiker>
                          <span class="fbremove"><i class="fa fa-times"></i></span>
                          <img id="fbholdernew" data-default="{{ @$pinfo->photo ? asset($pinfo->photo) : asset('machine.jpg') }}" src="{{ @$pinfo->photo ? asset($pinfo->photo) : asset('machine.jpg') }}"  onclick="triggerfile('fbholdernew','fbinputtxt','image/machine/','.jpg,.png,.jpeg','box')">
                      </div>
                      <input id="fbinputtxt" name="fbinputtxt" class='fbinputtxt' value="{{ @$pinfo->photo ? $pinfo->photo : "machine.jpg"  }}" type="hidden" >
                  </div>
                <!-- </center> -->
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                      <label for="machine_no">Machine Number <?= COMPICON ?></label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-hand-right"></i></span>
                      <input type="text" id="machine_no" name="machine_no" class="form-control" value="{{ @$pinfo->machine_no }}">
                      </div>
                  </div>
              </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="company_name">Company Name <?= COMPICON ?></label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-tags"></i></span>
                        <select style="width: 100%;" id="company_name" name="company_name" class="form-control select2">
                            <option value="Alliance" {{ @$pinfo->company_name == "Alliance" ? "selected" : "" }}>Alliance</option>
                            <option value="Dolphine" {{ @$pinfo->company_name == "Dolphine" ? "selected" : "" }}>Dolphine</option>
                            <option value="Pallu" {{ @$pinfo->company_name == "Pallu" ? "selected" : "" }}>Pallu</option>
                            <option value="Golden Falcon" {{ @$pinfo->company_name == "Golden Falcon" ? "selected" : "" }}>Golden Falcon</option>
                            <option value="Leberty" {{ @$pinfo->company_name == "Leberty" ? "selected" : "" }}>Leberty</option>
                        </select>
                    </div>
                </div>
            </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="machine_type">Machine Type <?= COMPICON ?></label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                      <select style="width: 100%;" id="machine_type" name="machine_type[]" multiple="multiple" class="form-control select2">
                          <?php
                              $in_mtype = [];
                          if(@$pinfo->machine_type != "") {
                              $explode = explode(', ',$pinfo->machine_type);
                              foreach($explode as $row) {
                                  $in_mtype[] = $row;
                              }
                          }
                          ?>
                        <option value="Multi" {{ in_array("Multi", $in_mtype) ? "selected" : "" }}>Multi</option>
                        <option value="Cording" {{ in_array("Cording", $in_mtype) ? "selected" : "" }}>Cording</option>
                        <option value="Sequence" {{ in_array("Sequence", $in_mtype) == "Sequence" ? "selected" : "" }}>Sequence</option>
                        <option value="Chain-Stitch" {{ in_array("Chain-Stitch", $in_mtype) == "Chain-Stitch" ? "selected" : "" }}>Chain-Stitch</option>
                        <option value="Sifly" {{ in_array("Sifly", $in_mtype) == "Sifly" ? "selected" : "" }}>Sifly</option>
                      </select>
                      </div>
                  </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                      <label for="machine_area">Area</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-modal-window"></i></span>
                      <input type="text" id="machine_area" name="machine_area" class="form-control onlyint" onkeyup="calculatelenth()" value="{{ @$pinfo->machine_area ? $pinfo->machine_area : 0 }}">
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="machine_head">Head</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-yen"></i></span>
                      <input type="text" id="machine_head" name="machine_head" class="form-control onlyint" onkeyup="calculatelenth()" value="{{ @$pinfo->machine_head ? $pinfo->machine_head : 0 }}">
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                      <label for="machine_frame">Frame Length</label>
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-object-ungroup"></i></span>
                      <input type="text" id="machine_frame" name="machine_frame" class="form-control onlyint" readonly value="{{ @$pinfo->machine_frame ? $pinfo->machine_frame : 0 }}">
                      </div>
                  </div>
              </div>
            </div>


            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                      <label for="remarks">Remarks</label>
                      <textarea id="remarks" name="remarks" class="form-control" rows="2">{{ @$pinfo->remarks }}</textarea>
                  </div>
              </div>
            </div>

          <div class="form-devider"></div>
          <div class="form-group">
          <button type="submit" class="btn btn-primary" onclick="return val_add_party();"><i class="fa {{ $type == 'edit' ? 'fa-upload' : 'fa-plus' }}"></i> {{ $type == 'edit' ? 'Update' : 'Save' }}</button>
          <!-- <button type="reset" class="btn btn-danger"><i class="fa fa-trash"></i> Clear</button> -->
          </div>
          </form>
      </div>
      <!-- /.box-body -->
    </div>
    </div>
  </div>
</section>

<div class="buy-now">
<a href="{{ route('machine') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<script type="text/javascript">

function calculatelenth()
{
  var area = $("#machine_area").val();
  var head = $("#machine_head").val();
  if(area != "" && head != "" && area != 0 && head != 0) {
    var c1 = area*head;
    var c2 = c1/1000;
    $("#machine_frame").val(c2.toFixed(2));
  } else {
    $("#machine_frame").val(0);
  }
}

function val_add_party()
{
	if($("#machine_no").val() == "")
	{
		toastr.error("Please, Enter Machine Number");
		$("#machine_no").focus();
		return false;
	}
	if($("#machine_type").val() == "")
	{
		toastr.error("Please, Select Machine Type");
		$("#machine_type").focus();
		return false;
	}
	if($("#company_name").val() == "")
	{
		toastr.error("Please, Select Machine Type");
		$("#company_name").focus();
		return false;
	}
}

$(document).ready(function(e) {
    jcropratio = 16 / 9;
    jcropresize = true;
});
</script>

@endsection
