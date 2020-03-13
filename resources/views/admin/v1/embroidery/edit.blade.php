@extends('admin.v1.layout.app', ['title' => 'Embroidery Design'])

@section('content')
<style type="text/css">
.select2-container .select2-selection--single .select2-selection__rendered {
  padding-left: 0px !important;
}
.fbpiker {
  max-width: none !important;
}
#fbholdernew {
  max-width: 100% !important;
  /*height: 300px !important;*/
  width: auto !important;
  height: auto;
  max-height:300px;
}
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-inr"></i> Embroidery Design
    <small>Add Embroidery Design</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Embroidery Design</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <form method="post" action="{{ route('update.embroidery.design',$info->id) }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <div class="row">
            <div class="col-md-4">
              <!-- File Input -->
              <div class="fbpickermain">
                <div class=fbpiker>
                  <span class="fbremove"><i class="fa fa-times"></i></span>
                  <img id="fbholdernew" data-default="{{ asset($info->image) }}" src="{{ asset($info->image) }}"  onclick="triggerfile('fbholdernew','fbinputtxt','image/design/','.jpg,.png,.jpeg','box')">
                </div>
                <input id="fbinputtxt" name="fbinputtxt" class='fbinputtxt' value="{{ $info->image }}" type="hidden" >
              </div>
              <!-- end of File Input -->
            </div>
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                        <label for="design_name">Desgin Name / Number <?= COMPICON ?></label>
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-keyboard-o"></i></span>
                        <input type="text" id="design_name" name="design_name" value="{{ $info->name }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                        <label for="master_type">Design Type</label>
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                        <select id="master_type" name="master_type[]" multiple class="form-control select2">
                          @foreach($design_type as $row)
                          <option value="{{ $row->id }}" {{ in_array($row->id, explode(',',$info->design_type)) ? "selected" : ""}}>{{ $row->name }}</option>
                          @endforeach
                        </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                        <label for="master_user">Designer Name</label>
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                          <input type="text" id="master_user" name="master_user" value="{{ $info->designer_id  }}" class="form-control">
                        </div>
                    </div>
                </div>


                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="design_file">Update Design File
                        @if($info->design_file != "")
                        <a href="{{ asset($info->design_file) }}" target="_blank">Current File</a>
                        @endif
                      </label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>
                        <input type="file" id="design_file" name="design_file" value="" accept=".emb,.dst" class="form-control">
                      </div>
                  </div>

                  </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="stitch">Stitch</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-pushpin"></i></span>
                <input type="text" id="stitch" name="stitch" value="{{ $info->stitch }}" class="form-control">
                </div>
            </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="color">Color</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-erase"></i></span>
                <input type="text" id="color" name="color" value="{{ $info->color }}" class="form-control">
                </div>
            </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="area">Area</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-modal-window"></i></span>
                <input type="text" id="area" name="area" value="{{ $info->area }}" class="form-control">
                </div>
            </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea id="remarks" name="remarks" class="form-control" rows="2">{{ $info->remarks }}</textarea>
                </div>
            </div>
          </div>

          <div class="form-devider"></div>
          <div class="form-group">
          <button type="submit" class="btn btn-primary" onclick="return val_add_party();"><i class="fa fa-plus"></i> Save</button>
          </div>
          </form>
      </div>
      <!-- /.box-body -->
    </div>
    </div>
  </div>

  <div class="buy-now">
    <a href="{{ route('embroidery.design') }}" class="btn btn-primary buy-now-btn">
      <i class="fa fa-arrow-left" aria-hidden="true"></i>
    </a>
    <div class="ripple"></div>
  </div>
</section>


<script type="text/javascript">
function val_add_party()
{

  if($("#design_name").val() == "")
	{
		toastr.error("Please, Enter Design Name / Number");
		$("#design_name").focus();
		return false;
	}

}
$(document).ready(function(e) {
  jcropratio = 0;
  jcropresize = true;
});
</script>

@endsection
