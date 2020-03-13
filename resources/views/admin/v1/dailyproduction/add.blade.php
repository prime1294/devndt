@extends('admin.v1.layout.app', ['title' => 'Daily Production'])

@section('content')
<style type="text/css">
.mytbl .form-group, .mytbl2 .form-group {
  margin-bottom: 0px;
}
.mytbl tr td, .mytbl2 tr td {
  padding: 5px !important;
}
.mytbl .ele1, .mytbl .ele2 {
  width: 29%;
}
.mytbl .ele3, .mytbl .ele4, .mytbl .ele5 {
  width: 10%;
}
.mytbl .ele9{
  width: 2%;
}
.mt-sm {
  margin-top: 8px;
}

</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-calendar"></i> Daily Production
    <small>Manage Daily Production</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Daily Production</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Manage Daily Production</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form method="post" id="form_daily_production">
              {!! csrf_field() !!}
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="adjustment_date">Report Date</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="adjustment_date" name="adjustment_date" default-date="{{ $today_date }}" value="{{ $today_date }}" class="form-control datepicker">
                </div>
              </div>
              </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered mytbl fixtablemobile">
              <thead>
                  <tr>
                    <th class="text-center" data-orderable="false">Machine</th>
                    <th class="text-center" data-orderable="false">Karigar</th>
                    <th class="text-center" data-orderable="false">Ship</th>
                    <th class="text-center" data-orderable="false">Stitch</th>
                    <th class="text-center" data-orderable="false">T.B.No</th>
                    <th class="text-center" data-orderable="false"></th>
                  </tr>
              </thead>
              <tbody class="appendrow">
                <tr>
                  <td class="ele1">
                    <div class="form-group">
                    <select style="width:100%;" id="machine" name="machine[]" class="form-control machineselect select2-bank" data-placeholder="Select Machine">
                      <option value="">Select Machine</option>
                      <option data-img="{{ 'plus.png' }}" value="0">Add New</option>
                      @foreach($machine_list as $row)
                      <option data-img="{{ $row->photo }}" value="{{ $row->id }}">{{ $row->machine_no }} - {{ ucwords($row->company_name) }}</option>
                      @endforeach
                    </select>
                    </div>
                  </td>
                  <td class="ele2">
                    <div class="form-group">
                    <select style="width:100%;" id="karigar" name="karigar[]" class="form-control karigarselect select2-bank" data-placeholder="Select Karigar">
                      <option value="">Select Karigar</option>
                      <option data-img="{{ 'plus.png' }}" value="0">Add New</option>
                      @foreach($karigar_list as $row)
                      <option data-img="{{ $row->photo }}" value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                      @endforeach
                    </select>
                    </div>
                  </td>
                  <td class="ele3">
                    <div class="form-group">
                    <select style="width:100%;" id="ship" name="ship[]" class="form-control ship select2">
                      <option value="1">Day</option>
                      <option value="2">Night</option>
                    </select>
                    </div>
                  </td>
                  <td class="ele5">
                    <div class="form-group">
                    <input type="text" id="stitch" name="stitch[]" class="form-control stitch text-center onlyint" autocomplete="false">
                    </div>
                  </td>
                  <td class="ele4">
                    <div class="form-group">
                      <input type="text" id="tbno" name="tbno[]" class="form-control tbno text-center onlyint" autocomplete="false">
                    </div>
                  </td>
                  <td class="ele9">
                    <div class="form-group">
                    <button class="btn btn-danger btn-xs removerowbtn mt-sm"><i class="fa fa-trash"></i></button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="form-devider"></div>
          <div class="row">
            <div class="col-md-12 mb-10">
              <button type="button" class="btn btn-primary" onclick="add_more_tbl();"><i class="fa fa-plus"></i> Add Row</button>
              <button type="button" class="btn btn-primary submitbtnreport"><i class="fa fa-upload"></i> Submit Report</button>
            </div>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>


<div class="buy-now">
<a href="{{ route('daily.production') }}" class="btn btn-primary buy-now-btn">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
</a>
<div class="ripple"></div>
</div>


<script type="text/javascript">
$(document).ready(function(e) {
  $(".datepicker" ).datepicker( "setDate", new Date('{{ date('D M d Y H:i:s O',strtotime($today_date)) }}'));
  retrivedate($("#adjustment_date").val());
});

$(document).on("change","#adjustment_date",function(e){
  //$(".appendrow").html('');
  //retrivedate($(this).val());
  //add_more_tbl();
});

function retrivedate(date) {
  $.ajax({
    url:'{{ route('fetch.daily.production') }}',
    type:'POST',
    data:"adjustment_date="+date+"&_token={{ csrf_token() }}",
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        if(e.result.length) {
          $(".removerowbtn").closest('tr').remove();
        }
        $.each(e.result, function(k, v) {
            add_more_tbl(v.machine,v.karigar,v.ship,v.tbno,v.stitch);
        });
      }
    }
  });
}

function add_more_tbl(machine = "",karigar = "", ship = "", tbno = "", stitch = "") {
  var html = '';
  html += '<tr>';
    html += '<td class="ele1">';
      html += '<div class="form-group">';
      html += '<select style="width:100%;" id="machine" name="machine[]" class="form-control machineselect select2-bank" data-placeholder="Select Machine">';
        html += '<option value="">Select Machine</option>';
        html += '<option data-img="{{ 'plus.png' }}" value="0">Add New</option>';
        html += '@foreach($machine_list as $row)';
        var machine_select = (machine == '{{ $row->id }}') ? "selected" : "";
        html += '<option data-img="{{ $row->photo }}" value="{{ $row->id }}" '+machine_select+'>{{ $row->machine_no }} - {{ ucwords($row->company_name) }}</option>';
        html += '@endforeach';
      html += '</select>';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele2">';
      html += '<div class="form-group">';
      html += '<select style="width:100%;" id="karigar" name="karigar[]" class="form-control karigarselect select2-bank" data-placeholder="Select Karigar">';
        html += '<option value="">Select Karigar</option>';
        html += '<option data-img="{{ 'plus.png' }}" value="0">Add New</option>';
        html += '@foreach($karigar_list as $row)';
        var karigar_select = (karigar == '{{ $row->id }}') ? "selected" : "";
        html += '<option data-img="{{ $row->photo }}" value="{{ $row->id }}" '+karigar_select+'>{{ ucwords($row->name) }}</option>';
        html += '@endforeach';
      html += '</select>';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele3">';
      html += '<div class="form-group">';
      html += '<select style="width:100%;" id="ship" name="ship[]" class="form-control ship select2">';
        var ship1 = (ship == "1") ? "selected" : "";
        var ship2 = (ship == "2") ? "selected" : "";
        html += '<option value="1" '+ ship1 +'>Day</option>';
        html += '<option value="2" '+ ship2 +'>Night</option>';
      html += '</select>';
    html += '</td>';
    html += '</div>';
    html += '<td class="ele5">';
      html += '<div class="form-group">';
      html += '<input type="text" id="stitch" name="stitch[]" value="'+stitch+'" class="form-control stitch text-center onlyint" autocomplete="false">';
      html += '</div>';
    html += '</td>';
  html += '<td class="ele4">';
    html += '<div class="form-group">';
    html += '<input type="text" id="tbno" name="tbno[]" value="'+tbno+'" class="form-control tbno text-center onlyint" autocomplete="false">';
    html += '</div>';
  html += '</td>';
    html += '<td class="ele9">';
      html += '<div class="form-group">';
      html += '<button class="btn btn-danger removerowbtn btn-xs mt-sm"><i class="fa fa-trash"></i></button>';
      html += '</div>';
    html += '</td>';
  html += '</tr>';
  if(machine != "" && karigar != "") {
    $(".appendrow").prepend(html);
  } else {
  $(".appendrow").append(html);
  }

  $(".submitbtnreport").show();
   $('.ship').select2();
  select2Bank();
}

$(document).on("click",".submitbtnreport",function(e){
  if($("#machine").val() == "") {
    toastr.error("Please select machine");
    $("#machine").focus();
    return false;
  }
  if($("#karigar").val() == "") {
    toastr.error("Please select Karigar");
    $("#karigar").focus();
    return false;
  }
  if($("#stitch").val() == "") {
    toastr.error("Please Enter Total Stitch");
    $("#stitch").focus();
    return false;
  }
  var daily_production = $("#form_daily_production").serialize();
  $.ajax({
    url:'{{ route('register.daily.production') }}',
    type:'POST',
    data:daily_production,
    beforeSend: function() {
       $(".submitbtnreport").hide();
    },
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        toastr.success("Production report submited Successfully");
        setTimeout(function(){
          window.location.href = "{{ route('daily.production') }}";
        }, 3000);
      } else {
        toastr.error(e.message);
        $(".submitbtnreport").show();
      }
    }
  });
})

$(document).on("click",".removerowbtn",function(e){
$(this).closest('tr').remove();
if($(".appendrow tr").length == 0) {
  $(".submitbtnreport").hide();
}
});


$(document).on("change",".machineselect",function(e) {
  if($(this).val() == 0) {
    var redirect_route = 'machine.new';
    window.location.href = '{{ route("redirecting") }}?redirectback=add.daily.production&redirect='+redirect_route;
  }
});

$(document).on("change",".karigarselect",function(e) {
  if($(this).val() == 0) {
    var redirect_route = 'karigar.new';
    window.location.href = '{{ route("redirecting") }}?redirectback=add.daily.production&redirect='+redirect_route;
  }
});
</script>

@endsection
