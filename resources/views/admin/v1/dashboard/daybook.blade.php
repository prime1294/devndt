@extends('admin.v1.layout.app', ['title' => 'Day Book'])

@section('content')
<style type="text/css">
  .small-box > .small-box-footer {
    background: rgb(60, 141, 188);
  }
  .amountno {
    border-radius: 10px;
    font-size: 20px;
    font-weight: 900;
    width: auto;
    float: right;
    min-width: 70px;
    text-align: center;
  }
</style>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Day Book</h3>
          <div class="box-tools pull-right">
            <span class="amountno"><span class="text-bold ramount text-success"><i class="fa fa-inr"></i> 0</span></span>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="text" id="filter_date" name="filter_date" value="{{ date('d-m-Y',strtotime("now")) }}" class="form-control datepicker">
                </div>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered dtable tbldblclick fixtablemobile">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Number</th>
                  <th>Type</th>
                  <th>Total</th>
                  <th>Money In</th>
                  <th>Money Out</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>
</section>
<div class="buy-now">
  <a href="{{ route('user.dashboard') }}" class="btn btn-primary buy-now-btn">
    <i class="fa fa-arrow-left" aria-hidden="true"></i>
  </a>
  <div class="ripple"></div>
</div>
  <script type="text/javascript">
    var dtable;
    var AjaxData = {};
    var dtable_lang = {
      search: "_INPUT_",
      searchPlaceholder: "Search Receivable"
    };
    $.extend( $.fn.dataTableExt.oStdClasses, {
      "sFilterInput": "form-control",
      "sLengthSelect": "form-control"
    });
    $(document).ready(function() {
      dtable = $('.dtable').DataTable({
        "dataSrc": "Data",
        "dom": '<"pull-left"f><"pull-right"l>tip',
        "language": dtable_lang,
        "searching": false,
        "processing" : true,
        "serverSide" : true,
        "order" : [],
        "ajax" : {
          "url": "{{ route('daybook.ajax') }}",
          "data": function (d) {
            return  $.extend(d, AjaxData);
          }
        },
        "bLengthChange": false,
        "bAutoWidth": false,
        "columns" : [
          {"data":"formated_name","sWidth": "20%", "orderable": false},
          {"data":"formated_number","sWidth": "10%", "orderable": false},
          {"data":"formated_type","sWidth": "20%", "orderable": false},
          {"data":"formated_total","sWidth": "20%", "orderable": false},
          {"data":"formated_in","sWidth": "20%", "orderable": false,"className": "text-success"},
          {"data":"formated_out","sWidth": "20%", "orderable": false,"className": "text-danger"},
        ],
        "initComplete": function (settings, json) {
          $(".ramount").html(json.money_balance);
        },
      });
    });


    $(document).on("change","#filter_date",function(e){
      AjaxData.filter_date = $(this).val();
      dtable.ajax.reload(
          function ( json ) {
            $(".ramount").html(json.money_balance);
          }
      );
    })
  </script>
@endsection
