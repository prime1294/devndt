@extends('admin.v1.layout.app', ['title' => 'Outstanding'])

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
  thead {
    display: none;
  }
</style>

<section class="content">
  <div class="row">
    <div class="col-md-6">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Receivable</h3>
          <div class="box-tools pull-right">
            <span class="amountno"><span class="text-bold ramount text-success"><i class="fa fa-inr"></i> 0</span></span>
          </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered dtable">
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
    <div class="col-md-6">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Payable</h3>
          <div class="box-tools pull-right">
            <span class="amountno"><span class="text-bold pamount text-danger"><i class="fa fa-inr"></i> 0</span></span>
          </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered dtable2">
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
    var dtable_lang = {
      search: "_INPUT_",
      searchPlaceholder: "Search Receivable"
    };
    $.extend( $.fn.dataTableExt.oStdClasses, {
      "sFilterInput": "form-control",
      "sLengthSelect": "form-control"
    });
    $(document).ready(function() {
      table = $('.dtable').DataTable({
        "dataSrc": "Data",
        "dom": '<"pull-left"f><"pull-right"l>tip',
        "language": dtable_lang,
        "processing" : true,
        "serverSide" : true,
        "order" : [],
        "ajax" : {
          "url": "{{ route('outstanding.ajax') }}",
          "data": {
            "getdata":"receivable"
          }
        },
        "bLengthChange": false,
        "bAutoWidth": false,
        "columns" : [
          {"data":"name","sWidth": "75%", "orderable": false},
          {"data":"amount","sWidth": "25%", "orderable": false, "className": "text-right"},
        ],
        "initComplete": function (settings, json) {
          $(".ramount").html(json.sum_balance);
        },
      });
    });


    var dtable2_lang = {
      search: "_INPUT_",
      searchPlaceholder: "Search Payable"
    };
    $.extend( $.fn.dataTableExt.oStdClasses, {
      "sFilterInput": "form-control",
      "sLengthSelect": "form-control"
    });
    $(document).ready(function() {
      table2 = $('.dtable2').DataTable({
        "dataSrc": "Data",
        "dom": '<"pull-left"f><"pull-right"l>tip',
        "language": dtable2_lang,
        "processing" : true,
        "serverSide" : true,
        "order" : [],
        "ajax" : {
          "url": "{{ route('outstanding.ajax') }}",
          "data": {
            "getdata":"payable"
          }
        },
        "bLengthChange": false,
        "bAutoWidth": false,
        "columns" : [
          {"data":"name","sWidth": "75%", "orderable": false},
          {"data":"amount","sWidth": "25%", "orderable": false, "className": "text-right"},
        ],
        "initComplete": function (settings, json) {
          $(".pamount").html(json.sum_balance);
        },
      });
    });
  </script>
@endsection
