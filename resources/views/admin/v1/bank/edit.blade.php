@extends('admin.v1.layout.app', ['title' => 'Bank Account'])

@section('content')
<style type="text/css">
.select2-container .select2-selection--single .select2-selection__rendered {
  padding-left: 0px !important;
}
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-user"></i> Bank Account
    <small>Edit Bank Account</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Edit Bank Account</li>

  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-body">
        <form method="post" action="{{ route('bank.update',$info->id) }}" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                    <label for="bank_person_name">Account Name <?= COMPICON ?></label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" id="bank_person_name" name="bank_person_name" class="form-control" value="{{ $info->name }}">
                    </div>
                </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                    <label for="bank_name">Bank Name <?= COMPICON ?></label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-institution"></i></span>
                    <select id="bank_name" name="bank_name" class="form-control select2-bank">
                      <?php
                      // dd($bank_list);
                      foreach($bank_list as $row) {
                        ?>
                        <option data-img="{{ $row->icon }}" value="{{ $row->id }}" {{ $info->bank_id == $row->id ? "selected" : "" }}>{{ $row->name }}</option>
                        <?php
                      }
                      ?>
                    </select>
                    </div>
                </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                    <label for="bank_account_no">Account Number <?= COMPICON ?></label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
                    <input type="text" id="bank_account_no" name="bank_account_no" class="form-control" value="{{ $info->account_no }}">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                    <label for="bank_branch">Branch <?= COMPICON ?></label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-star-half-o"></i></span>
                    <input type="text" id="bank_branch" name="bank_branch" class="form-control" value="{{ $info->branch }}">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                    <label for="bank_ifsc">IFSC Code <?= COMPICON ?></label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-compressed"></i></span>
                    <input type="text" id="bank_ifsc" name="bank_ifsc" class="form-control" onkeyup="this.value = this.value.toUpperCase();"  value="{{ $info->ifsc }}">
                    </div>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                    <label for="account_type">Account Type <?= COMPICON ?></label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                    <select id="account_type" name="account_type" class="form-control">
                      <option value="1" {{ $info->type == 1 ? "selected" : "" }}>Saving Account</option>
                      <option value="2" {{ $info->type == 2 ? "selected" : "" }}>Current Account</option>
                    </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="opening_balance">Opening Balance <?= COMPICON ?></label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                  <input type="text" id="opening_balance" name="opening_balance" value="{{ $info->opening_balance }}" class="form-control onlyint">
                </div>
            </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label for="asof">As Of Date <?= COMPICON ?></label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="asof" name="asof" value="{{ date('d-m-Y',strtotime($info->asof)) }}" class="form-control datepicker">
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
          <button type="submit" class="btn btn-primary" onclick="return val_add_party();"><i class="fa fa-plus"></i> Update</button>
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
<a href="{{ route('bankaccount') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<script type="text/javascript">
function val_add_party()
{
	if($("#bank_name").val() == "")
	{
		toastr.error("Please, Select Bank");
		$("#bank_name").focus();
		return false;
	}
  if($("#bank_person_name").val() == "")
	{
		toastr.error("Please, Enter Account Name");
		$("#bank_person_name").focus();
		return false;
	}
  if($("#bank_account_no").val() == "")
	{
		toastr.error("Please, Enter Account Number");
		$("#bank_account_no").focus();
		return false;
	}
  if($("#bank_ifsc").val() == "")
	{
		toastr.error("Please, Enter IFSC Code");
		$("#bank_ifsc").focus();
		return false;
	}
  if($("#account_type").val() == "")
	{
		toastr.error("Please, Select Account Type");
		$("#account_type").focus();
		return false;
	}
  if($("#bank_branch").val() == "")
	{
		toastr.error("Please, Enter Branch Name");
		$("#bank_branch").focus();
		return false;
	}
  if($("#opening_balance").val() == "")
	{
		toastr.error("Please, Enter Opening Balance");
		$("#opening_balance").focus();
		return false;
	}
  if($("#asof").val() == "")
	{
		toastr.error("Please, Enter Balance As of");
		$("#asof").focus();
		return false;
	}

}


</script>

@endsection
