@extends('admin.v1.layout.app', ['title' => 'Profit Loss Report'])

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
  .primeheading {
    font-size: 16px;
    font-weight: 600;
    margin-right: 10px;

  }
</style>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Profit Loss Report</h3>
          <div class="box-tools pull-right">
            <span class="amountno"><span class="text-muted primeheading">Receivable T.D.S: </span> <span class="text-bold grand_tds text-success"><i class="fa fa-inr"></i> 0</span></span>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-8">
            </div>
            <!-- hidden date range -->
            <input type="hidden" id="filterStart" onchange="changeDateRange()">
            <input type="hidden" id="filterEnd">
            <!-- hidden date range end -->

            <div class="col-md-4">
              <div class="form-group">
                <button type="button"  class="btn btn-default pull-right daterange" style="width: 100%;">
                  <span><i class="fa fa-calendar"></i> Date range picker</span>
                  <i class="fa fa-caret-down"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <br>
          <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered dtable">
              <thead>
                <tr class="bg-primary">
                  <th>Perticulers</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
              <tr class="bg-info">
                <th class="grand_title">Net Profit</th>
                <th class="grand_amount">0</th>
              </tr>
              </tfoot>
            </table>
          </div>
            </div>
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
          "url": "{{ route('profit.loss.report.ajax') }}",
          "data": function (d) {
            return  $.extend(d, AjaxData);
          }
        },
        "bLengthChange": false,
        "bAutoWidth": false,
        "columns" : [
          {"data":"perticulers","sWidth": "70%", "orderable": false},
          {"data":"formated_amount","sWidth": "30%", "orderable": false},
        ],
        "initComplete": function (settings, json) {
          $(".grand_title").html(json.total_title);
          $(".grand_amount").html(json.total_amount);
          $(".grand_tds").html(json.total_tds);
        },
      });
    });

    function changeDateRange() {
      AjaxData.startdate = $("#filterStart").val();
      AjaxData.enddate = $("#filterEnd").val();
      dtable.ajax.reload(
              function ( json ) {
                $(".grand_title").html(json.total_title);
                $(".grand_amount").html(json.total_amount);
                $(".grand_tds").html(json.total_tds);
              }
      );
    }
  </script>
@endsection
