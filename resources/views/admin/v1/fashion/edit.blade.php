@extends('admin.v1.layout.app', ['title' => 'Fashion Design'])

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
    <i class="fa fa-inr"></i> Fashion Design
    <small>Add Fashion Design</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Fashion Design</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <form method="post" action="{{ route('update.fashion.design',$info->id) }}" enctype="multipart/form-data">
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
                    <label for="sale_price">Sale Price</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                    <input type="text" id="sale_price" name="sale_price" value="{{ intval($info->sale_price) }}" class="form-control">
                    </div>
                </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea id="remarks" name="remarks" class="form-control" rows="2">{{ $info->remarks }}</textarea>
                    </div>
                </div>
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
    <a href="{{ route('fashion.design') }}" class="btn btn-primary buy-now-btn">
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
