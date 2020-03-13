@extends('admin.v1.layout.app', ['title' => 'Frame Report'])

@section('content')
<style type="text/css">
  .mytbl2 .ele4 {
    width: 18%;
  }
.mytbl2 .ele2,.mytbl2 .ele3,.mytbl2 .ele5,.mytbl2 .ele6,.mytbl2 .ele8,.mytbl2 .ele10 {
  width: 10%;
}
.mytbl2 .ele11 {
  width: 20%;
}
.mytbl2 .ele12 {
  width: 2%;
}
.mt-sm {
  margin-top: 8px;
}
.mytbl2 .select2-container {
  width: 100% !important;
}
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa fa-calendar"></i> Frame Report
    <small>Manage Frame Report</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Frame Report</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Manage Frame Report</h3>

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
          <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered mytbl2 fixtablemobile2">
              <thead>
                  <tr>
                    <th class="text-center" data-orderable="false">Frame</th>
                    <th class="text-center" data-orderable="false">Prog. Card</th>
                    <th class="text-center" data-orderable="false">Design</th>
                    <th class="text-center" data-orderable="false">Monit.No</th>
                    <th class="text-center" data-orderable="false">Stitch</th>
                    <th class="text-center" data-orderable="false">Quantity</th>
                    <th class="text-center" data-orderable="false">Unit</th>
                    <th class="text-center" data-orderable="false">Rate</th>
                    <th class="text-center" data-orderable="false">Remarks</th>
                    <th class="text-center" data-orderable="false"></th>
                  </tr>
              </thead>
              <tbody class="appendrow">
                <tr>
                  <td class="ele2">
                    <div class="form-group">
                    <input type="text" id="fr_frame" name="fr_frame[]" class="form-control fr_machine text-center onlyint" autocomplete="false">
                    </div>
                  </td>
                  <td class="ele3">
                    <div class="form-group">
                      <select id="fr_programmecard" name="fr_programmecard[]" class="form-control fr_programmecard select2">
                        <option value="0">Select Pr.cd</option>
                        @foreach($pc_list as $row)
                        <option value="{{ $row->id }}">{{ $row->pc_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </td>
                  <td class="ele4">
                    <div class="form-group">
                    <input type="text" id="fr_design" name="fr_design[]" class="form-control fr_design text-center" autocomplete="false">
                    </div>
                  </td>
                  <td class="ele5">
                    <div class="form-group">
                    <input type="text" id="fr_monitor" name="fr_monitor[]" class="form-control fr_monitor text-center" autocomplete="false">
                    </div>
                  </td>
                  <td class="ele6">
                    <div class="form-group">
                    <input type="text" id="fr_stitch" name="fr_stitch[]" class="form-control fr_stitch text-center" autocomplete="false">
                    </div>
                  </td>
                  <td class="ele8">
                    <div class="form-group">
                    <input type="text" id="fr_quantity" name="fr_quantity[]" class="form-control fr_quantity text-center onlyint" autocomplete="false">
                    </div>
                  </td>
                  <td class="ele9">
                    <div class="form-group">
                      <select id="fr_unit" name="fr_unit[]" class="form-control fr_unit select2">
                        @foreach($stock_unit as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </td>
                  <td class="ele10">
                    <div class="form-group">
                    <input type="text" id="fr_rate" name="fr_rate[]" class="form-control fr_rate text-center onlyint" autocomplete="false">
                    </div>
                  </td>
                  <td class="ele11">
                    <div class="form-group">
                    <input type="text" id="fr_remarks" name="fr_remarks[]" class="form-control fr_remarks text-center" autocomplete="false">
                    </div>
                  </td>
                  <td class="ele12">
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
  retrivedate();
});

function retrivedate() {
  $.ajax({
    url:'{{ route('fetch.frame.report') }}',
    type:'POST',
    data:"report_id={{ $daily_report_id }}&_token={{ csrf_token() }}",
    success:function(e) {
      if(e.status == "true" && e.message == "success") {
        if(e.result.length) {
          $(".removerowbtn").closest('tr').remove();
        }
        $.each(e.result, function(k, v) {
            add_more_tbl(v.frame,v.pcard,v.design,v.monitor_no,v.stitch,v.quantity,v.unit,v.rate,v.remarks);
        });
      }
    }
  });
}

function add_more_tbl(frame = "",pc = "", design = "",monitor = "", stitch = "", quantity = "", sunit = "", rate = "", remarks =  "") {
  var html = '';
  html += '<tr>';
    html += '<td class="ele2">';
      html += '<div class="form-group">';
      html += '<input type="text" id="fr_frame" name="fr_frame[]" class="form-control fr_machine text-center onlyint" value="'+frame+'" autocomplete="false">';
      html += '</div>';
    html += '</td>';
      html += '<div class="form-group">';
      html += '<td class="ele3">';
        html += '<select id="fr_programmecard" name="fr_programmecard[]" class="form-control fr_programmecard select2">';
          html += '<option value="0">Select Pr.cd</option>';
          html += '@foreach($pc_list as $row)';
          var pc_select = (pc == '{{ $row->id }}') ? "selected" : "";
          html += '<option value="{{ $row->id }}" '+pc_select+'>{{ $row->pc_name }}</option>';
          html += '@endforeach';
        html += '</select>';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele4">';
      html += '<div class="form-group">';
      html += '<input type="text" id="fr_design" name="fr_design[]" value="'+design+'" class="form-control fr_design text-center" autocomplete="false">';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele5">';
      html += '<div class="form-group">';
      html += '<input type="text" id="fr_monitor" name="fr_monitor[]" class="form-control fr_monitor text-center" value="'+monitor+'" autocomplete="false">';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele6">';
      html += '<div class="form-group">';
      html += '<input type="text" id="fr_stitch" name="fr_stitch[]" class="form-control fr_stitch text-center" value="'+stitch+'" autocomplete="false">';
    html += '</td>';
    html += '</div>';
    html += '<td class="ele8">';
      html += '<div class="form-group">';
      html += '<input type="text" id="fr_quantity" name="fr_quantity[]" value="'+quantity+'" class="form-control fr_quantity text-center onlyint" autocomplete="false">';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele9">';
      html += '<div class="form-group">';
        html += '<select id="fr_unit" name="fr_unit[]" class="form-control fr_unit select2">';
          html += '@foreach($stock_unit as $row)';
          var sunit_select = (sunit == '{{ $row->id }}') ? "selected" : "";
          html += '<option value="{{ $row->id }}" '+sunit_select+'>{{ $row->name }}</option>';
          html += '@endforeach';
        html += '</select>';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele10">';
      html += '<div class="form-group">';
      html += '<input type="text" id="fr_rate" name="fr_rate[]" class="form-control fr_rate text-center onlyint" value="'+rate+'" autocomplete="false">';
      html += '</div>';
      html += '</td>';
    html += '<td class="ele11">';
      html += '<div class="form-group">';
      html += '<input type="text" id="fr_remarks" name="fr_remarks[]" class="form-control fr_remarks text-center" value="'+remarks+'" autocomplete="false">';
      html += '</div>';
    html += '</td>';
    html += '<td class="ele12">';
      html += '<div class="form-group">';
      html += '<button class="btn btn-danger btn-xs removerowbtn mt-sm"><i class="fa fa-trash"></i></button>';
      html += '</div>';
    html += '</td>';
  html += '</tr>';
  if(frame != "" && pc != "" && stitch != "" && quantity != "" && rate != "") {
    $(".appendrow").prepend(html);
  } else {
  $(".appendrow").append(html);
  }

  $(".submitbtnreport").show();
   $('.fr_unit').select2();
   $('.fr_programmecard').select2();
}

$(document).on("click",".submitbtnreport",function(e){
  if($("#fr_frame").val() == "") {
    toastr.error("Please Enter total frame");
    $("#fr_frame").focus();
    return false;
  }
  if($("#fr_programmecard").val() == "") {
    toastr.error("Please select Programme Card");
    $("#fr_programmecard").focus();
    return false;
  }
  if($("#fr_stitch").val() == "") {
    toastr.error("Please Enter Total Stitch");
    $("#fr_stitch").focus();
    return false;
  }
  if($("#fr_quantity").val() == "") {
    toastr.error("Please Enter Quantity");
    $("#fr_quantity").focus();
    return false;
  }
  if($("#fr_unit").val() == "") {
    toastr.error("Please Select Unit");
    $("#fr_unit").focus();
    return false;
  }
  if($("#fr_rate").val() == "") {
    toastr.error("Please Enter Rate");
    $("#fr_rate").focus();
    return false;
  }
  var daily_production = $("#form_daily_production").serialize();
  $.ajax({
    url:'{{ route('register.frame.report',$daily_report_id) }}',
    type:'POST',
    data:daily_production,
    beforeSend: function() {
       $(".submitbtnreport").hide();
    },
    success:function(e) {
      $(".submitbtnreport").show();
      if(e.status == "true" && e.message == "success") {
        toastr.success("Frame report saved Successfully");
      } else {
        toastr.error(e.message);
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
</script>

@endsection
