@extends('admin.v1.layout.app', ['title' => 'Manage Expenses'])

@section('content')
  <style type="text/css">
    .select2-container .select2-selection--single .select2-selection__rendered {
      padding-left: 0px !important;
    }
    .banktransferinput {
      display: none;
    }
    .bggray {
      background-color: #ECF0F5;
    }
    .qty .count {
      color: #000;
      display: inline-block;
      vertical-align: top;
      font-size: 25px;
      font-weight: 700;
      line-height: 30px;
      padding: 0 2px
    ;min-width: 70px;
      text-align: center;
    }
    .qty .plus {
      cursor: pointer;
      display: inline-block;
      vertical-align: top;
      color: #717FE0;
      width: 21px;
      border: 1px solid #717FE0;
      height: 22px;
      font: 21px/1 Arial,sans-serif;
      text-align: center;
      border-radius: 50%;
      margin-top: 3px;
    }
    .qty .minus {
      cursor: pointer;
      display: inline-block;
      vertical-align: top;
      color: #717FE0;
      border: 1px solid #717FE0;
      width: 21px;
      height: 22px;
      font: 19px/1 Arial,sans-serif;
      text-align: center;
      border-radius: 50%;
      background-clip: padding-box;
      margin-top: 3px;
    }
    .minus:hover{
      background-color: #717fe0 !important;
      color:#FFFFFF;
    }
    .plus:hover{
      background-color: #717fe0 !important;
      color:#FFFFFF;
    }
    /*Prevent text selection*/
    span{
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
    }
    .count{
      border: 0;
      width: 2%;
    }
    .count:disabled{
      background-color:white;
    }
    .seprator {
      margin-bottom: 10px;
      border-bottom: 1px solid #fbf4f4;
    }
    .totalquantityctndiv {
      padding: 10px 8px;
    }
    .totalquantityctn {
      font-size: 20px;
      font-weight: 900;
      text-transform: uppercase;
    }
    .totalctndiv {
      display: none;
    }
    .editsubmit , .editreset {
      display: none;
    }
    .toggle-group .btn-xs {
      line-height: 1.3;
    }
    .mytbl tr td {
      padding: 5px !important;
    }
    .mytbl .ele2 {
      width: 38%;
    }
    .mytbl .ele4, .mytbl .ele5, .mytbl .ele7, .mytbl .ele9, .mytbl .ele10 {
      width: 10%;
    }
    .mytbl .ele6, .mytbl .ele8 {
      width: 5%;
    }
    .mytbl .ele11 {
      width: 2%;
    }
    .mt-10 {
      margin-top: 10px;
    }
    .mt-20 {
      margin-top: 20px;
    }
    .sp_by {
      width: 200px;
    }
    @if($info->exp_type == 1)
      .due_date_div {
      display: none;
    }
    @endif
  </style>


  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-sign-out"></i> Manage Expenses
      <small>Manage Expenses</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Expenses</li>

    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-body">
            <form method="post" action="{{ route('update.expenses',$info->id) }}" enctype="multipart/form-data">
              {!! csrf_field() !!}
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Payment Type</label>
                    <input type="checkbox" value="1" id="confirmAns" name="confirmAns" class="confirmAns" {{ $info->exp_type == 1 ? "checked" : "" }} data-size="mini" data-toggle="toggle" data-on="Cash" data-off="Credit" data-onstyle="success" data-offstyle="info">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="adjustment_date">Date</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      <input type="text" id="adjustment_date" name="adjustment_date" value="{{ date('d-m-Y',strtotime($info->date))  }}" class="form-control datepicker">
                    </div>
                  </div>
                </div>

                <div class="col-md-4 due_date_div">
                  <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      <input type="text" id="due_date" name="due_date" value="{{ date('d-m-Y',strtotime($info->due_date))  }}" class="form-control datepicker">
                    </div>
                  </div>
                </div>


                <div class="col-md-4">
                  <div class="form-group">
                    <label for="exp_category">Category</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      <select id="exp_category" name="exp_category" data-placeholder="Select Category" class="form-control select2">
                        <option value=""></option>
                        @foreach($exp_category_list as $row)
                          <option value="{{ $row->id }}" {{ $info->category == $row->id ? "selected" : ""  }}>{{ $row->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>

              </div>



              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="exp_name">Name</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-user"></i></span>
                      <select style="width: 100%;" id="exp_name" name="exp_name" class="form-control select2-bank" data-placeholder="Select User">
                        <option value="">Select User</option>
                        @foreach($userlist as $row)
                          <option data-img="{{ $row->userphoto }}" value="{{ $row->usertype."_".$row->userid }}" <?= $row->usertype."_".$row->userid == $info->name ? "selected" : "" ?>>{{ ucwords($row->username) }} - {{ ucwords(config('master.'.$row->usertype)['name']) }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="bill_no">Bill No</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                      <input type="text" id="bill_no" name="bill_no" value="{{ $info->bill_no  }}" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="ref_img">Update Bill Ref. Image <?php echo $info->bill_photo != "" ? '<a href="'.asset($info->bill_photo).'" target="_blank">View Current</a> ' : "" ?></label>
                    <button type="button" class="btn btn-default btn-block" id="imagebtn_bill" onclick="triggerfile('imagebtn_bill','upload_image_text_bill','image/stock/','.jpg,.png,.jpeg')" ><i class="glyphicon glyphicon-folder-open"></i> &nbsp; Browse File</button>
                    <input type="hidden" id="upload_image_text_bill" name="upload_image_text_bill">
                  </div>
                </div>
              </div>

              <!-- Panel Code goes here -->
              <div class="row">
                <div class="col-md-12">
                  <h3 class="page-header">Items</h3>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-hover table-striped mytbl fixtablemobile">
                      <thead>
                      <tr class="bg-primary">
                        <td class="text-center">Items</td>
                        <td class="text-center">Quantity</td>
                        <td class="text-center">Unit</td>
                        <td class="text-center">Rate</td>
                        <td class="text-center">Disc(%)</td>
                        <td class="text-center">Disc. Amount</td>
                        <td class="text-center">GST(%)</td>
                        <td class="text-center">GST Amount</td>
                        <td class="text-center">Total</td>
                        <td class="text-center"></td>
                      </tr>
                      </thead>
                      <tbody class="append_to_me">
                      <?php
                      $no = 1;
                      $expences = unserialize($info->history);
                      if($expences) {
                        foreach($expences as $row) {
                      ?>
                      <tr class="tblrow{{ $no++  }}" data-id="1">
                        <td class="ele2">
                          <div class="form-group">
                            <input type="text" value="{{ $row['description'] }}" id="description" name="description[]" class="form-control description text-center" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele4">
                          <div class="form-group">
                            <input type="text" id="quantity" value="{{ $row['quantity']  }}" name="quantity[]" class="form-control quantity onlyint text-center checkcalculation" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele11">
                          <div class="form-group">
                            <select id="mesurement" style="width:100%;" name="mesurement[]" class="form-control mesurement select2">
                              @foreach($category_list as $rr)
                                <option value="{{ $rr->id }}" {{ $row['mesurement'] == $rr->id ? "selected" : "" }}>{{ $rr->name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </td>
                        <td class="ele5">
                          <div class="form-group">
                            <input type="text" value="{{ $row['unit']  }}" id="unit" name="unit[]" class="form-control unit onlyint text-center checkcalculation" value="0" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele6">
                          <div class="form-group">
                            <input type="text" value="{{ $row['discount']  }}" id="discount" name="discount[]" class="form-control discount onlyint text-center checkcalculation" maxlength='2' value="0" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele7">
                          <div class="form-group">
                            <input type="text" value="{{ $row['discount_amount']  }}" id="discount_amount" name="discount_amount[]" readonly class="form-control discount_amount onlyint text-center" value="0" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele8">
                          <div class="form-group">
                            <input type="text" value="{{ $row['gst']  }}" id="gst" name="gst[]" class="form-control gst onlyint text-center checkcalculation" maxlength='2' value="0" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele9">
                          <div class="form-group">
                            <input type="text" id="gst_amount" value="{{ $row['gst_amount']  }}" name="gst_amount[]" readonly class="form-control gst_amount onlyint text-center" value="0" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele10">
                          <div class="form-group">
                            <input type="text" id="total" name="total[]" value="{{ $row['total']  }}" readonly class="form-control total onlyint text-center checkcalculation" value="0" autocomplete="false">
                          </div>
                        </td>
                        <td class="ele11">
                          <div class="form-group">
                            <button type="button" class="btn btn-danger btn-xs removerowbtn"><i class="fa fa-trash"></i></button>
                          </div>
                        </td>
                      </tr>
                            <?php
                            }
                          }
                      ?>
                      </tbody>

                    </table>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <button type="button" class="btn btn-primary" onclick="addmorerow()"><i class="fa fa-plus"></i> Add Row</button>
                  </div>
                </div>
                <div class="col-md-6 form-inline">
                  <div class="form-group pull-right">
                    <label for="grand_total">Total: </label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                      <input type="text" class="form-control onlyint text-center" readonly value="0" id="grand_total" name="grand_total">
                    </div>
                  </div>
                </div>
              </div>



              <!-- End of panel -->



              <!-- panel of payment -->
              <div class="row mt-20">
                <div class="col-md-12">
                  <h3 class="page-header">Payment History</h3>
                </div>
              </div>

              <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered fixtablemobile">
                  <thead>
                  <tr class="bg-primary">
                    <th class="text-center">Date</th>
                    <th class="text-center">Pay By</th>
                    <th class="text-center">Ref No</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Remarks</th>
                    <th class="text-center"></th>
                  </tr>
                  </thead>
                  <tbody id="append_payment_row">
                  <?php
                  $payments = unserialize($info->payment_history);
                  if($payments) {
                    foreach($payments as $prow) {
                      ?>
                    <tr data-id="{{ $info->id }}" data-tid="{{ $prow['tid'] }}">
                      <td><input type="text" value="{{ date('d-m-Y',strtotime($prow['sp_date']))  }}" id="sp_date" name="sp_date[]" class="form-control datepicker"></td>
                      <td>
                        <select id="sp_by" name="sp_by[]" class="form-control sp_by select2">
                          <option value="cash" {{ $prow['sp_by'] == "cash" ? "selected" : ""  }}>Cash</option>
                          <option value="cheque" {{ $prow['sp_by']  == "cheque" ? "selected" : "" }}>Cheque</option>
                          @if(count($bank_list) != 0)
                            <optgroup label="Banks">
                              @foreach ($bank_list as $row)
                                <option {{ $prow['sp_by'] == "bank_ref_".$row['id'] ? "selected" : ""  }} value="bank_ref_{{ $row->id }}">{{ $row->name }} - {{ $row->bankname }} - {{ $row->account_no }} - {{ $row->type == 2 ? "Current" : "Saving" }}</option>
                              @endforeach
                            </optgroup>
                          @endif
                        </select>
                      </td>
                      <td><input type="text" value="{{ $prow['sp_ref_no']  }}" id="sp_ref_no" name="sp_ref_no[]" class="form-control sp_ref_no"></td>
                      <td><input type="text" value="{{ $prow['sp_amount']  }}" id="sp_amount" name="sp_amount[]" class="form-control sp_amount onlyint"></td>
                      <td><input type="text" value="{{ $prow['sp_remarks']  }}" id="sp_remarks" name="sp_remarks[]" class="form-control"></td>
                      <td><button type="button" class="btn btn-danger btn-xs removerowpayment"><i class="fa fa-trash"></i></button></td>
                    </tr>
                  <?php
                  }
                    } else {
                    ?>
                  <tr>
                    <td><input type="text" id="sp_date" name="sp_date[]" value="<?= TDATE ?>" class="form-control datepicker"></td>
                    <td>
                      <select id="sp_by" name="sp_by[]" class="form-control sp_by select2">
                        <option value="cash">Cash</option>
                        <option value="cheque">Cheque</option>
                        @if(count($bank_list) != 0)
                          <optgroup label="Banks">
                            @foreach ($bank_list as $row)
                              <option value="bank_ref_{{ $row->id }}">{{ $row->name }} - {{ $row->bankname }} - {{ $row->account_no }} - {{ $row->type == 2 ? "Current" : "Saving" }}</option>
                            @endforeach
                          </optgroup>
                        @endif
                      </select>
                    </td>
                    <td><input type="text" id="sp_ref_no" name="sp_ref_no[]" class="form-control sp_ref_no"></td>
                    <td><input type="text" id="sp_amount" name="sp_amount[]" class="form-control sp_amount onlyint"></td>
                    <td><input type="text" id="sp_remarks" name="sp_remarks[]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-xs removerowpayment"><i class="fa fa-trash"></i></button></td>
                  </tr>
                  <?php
                  }
                  ?>
                  </tbody>
                </table>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <button type="button" class="btn btn-primary" onclick="addmorepayment()"><i class="fa fa-plus"></i> Add Row</button>
                  </div>
                </div>
              </div>
              <!-- end panel of payment -->


              <div class="row mt-sm">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea id="remarks" name="remarks" class="form-control" rows="2">{{ $info->remarks }}</textarea>
                  </div>
                </div>
              </div>

              <div class="form-devider"></div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary" onclick="return val_submit();"><i class="fa fa-plus"></i> Save</button>
              </div>
            </form>
          </div>
          <!-- /.box-body -->
        </div>
      </div>
    </div>
  </section>

  <div class="buy-now">
    <a href="{{ route('expenses') }}" class="btn btn-primary buy-now-btn">
      <i class="fa fa-arrow-left" aria-hidden="true"></i>
    </a>
    <div class="ripple"></div>
  </div>



  <script type="text/javascript">
    var placeholder = "Unit";
    var producttbl;
    var editableid = 0;
    var currentrow = {{ $no }};
    function addmorepayment() {
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      var html = '';
      html += '<tr>';
      html += '<td><input type="text" id="sp_date" name="sp_date[]" value="'+dd+'-'+mm+'-'+yyyy+'" class="form-control datepicker"></td>';
      html += '<td>';
      html += '<select id="sp_by" name="sp_by[]" class="form-control sp_by select2">';
      html += '<option value="cash">Cash</option>';
      html += '<option value="cheque">Cheque</option>';
      html += '@if(count($bank_list) != 0)';
              html += '<optgroup label="Banks">';
      html += '@foreach ($bank_list as $row)';
              html += '<option value="bank_ref_{{ $row->id }}">{{ $row->name }} - {{ $row->bankname }} - {{ $row->account_no }} - {{ $row->type == 2 ? "Current" : "Saving" }}</option>';
      html += '@endforeach';
              html += '</optgroup>';
      html += '@endif';
              html += '</select>';
      html += '</td>';
      html += '<td><input type="text" id="sp_ref_no" name="sp_ref_no[]" class="form-control sp_ref_no"></td>';
      html += '<td><input type="text" id="sp_amount" name="sp_amount[]" class="form-control sp_amount onlyint"></td>';
      html += '<td><input type="text" id="sp_remarks" name="sp_remarks[]" class="form-control"></td>';
      html += '<td><button type="button" class="btn btn-danger btn-xs removerowpayment"><i class="fa fa-trash"></i></button></td>';
      html += '</tr>';
      $("#append_payment_row").append(html);
      initdatepicker(false);
      $('.select2').select2();
    }
    function val_submit() {
      if($("#adjustment_date").val() == "") {
        toastr.error("Please Select Date");
        $("#adjustment_date").focus();
        return false;
      }

      if($("#exp_category").val() == "") {
        toastr.error("Please Select Date");
        $("#exp_category").focus();
        return false;
      }
      if($("#grand_total").val() == "" || $("#grand_total").val() == 0 || $("#grand_total").val() == null) {
        toastr.error("Please add any 1 Item in Expenses");
        return false;
      }
    }

    function addmorerow() {
      currentrow++;
      var html = '';
      html += '<tr class="tblrow'+currentrow+'" data-id="'+currentrow+'">';
      html += '<td class="ele2">';
      html += '<div class="form-group">';
      html += '<input type="text" id="description" name="description[]" class="form-control description text-center" autocomplete="false">';
      html += '</div>';
      html += '</td>';
      html += '<td class="ele4">';
      html += '<div class="form-group">';
      html += '<input type="text" id="quantity" name="quantity[]" class="form-control quantity onlyint text-center checkcalculation" autocomplete="false">';
      html += '</div>';
      html += '</td>';
      html += '<td class="ele11">';
      html += '<div class="form-group">';
      html += '<select id="mesurement" style="width:100%;"  name="mesurement[]" class="form-control mesurement select2">';
      html += '@foreach($category_list as $row)';
      html += '<option value="{{ $row->id }}">{{ $row->name }}</option>';
      html += '@endforeach';
      html += '</select>';
      html += '</div>';
      html += '</td>';
      html += '<td class="ele5">';
      html += '<div class="form-group">';
      html += '<input type="text" id="unit" name="unit[]" class="form-control unit onlyint text-center checkcalculation" value="0" autocomplete="false">';
      html += '</div>';
      html += '</td>';
      html += '<td class="ele6">';
      html += '<div class="form-group">';
      html += '<input type="text" id="discount" name="discount[]" class="form-control discount onlyint text-center checkcalculation" maxlength="2" value="0" autocomplete="false">';
      html += '</div>';
      html += '</td>';
      html += '<td class="ele7">';
      html += '<div class="form-group">';
      html += '<input type="text" id="discount_amount" name="discount_amount[]" readonly class="form-control discount_amount onlyint text-center" value="0" autocomplete="false">';
      html += '</div>';
      html += '</td>';
      html += '<td class="ele8">';
      html += '<div class="form-group">';
      html += '<input type="text" id="gst" name="gst[]" class="form-control gst onlyint text-center checkcalculation" maxlength="2" value="0" autocomplete="false">';
      html += '</div>';
      html += '</td>';
      html += '<td class="ele9">';
      html += '<div class="form-group">';
      html += '<input type="text" id="gst_amount" name="gst_amount[]" readonly class="form-control gst_amount onlyint text-center" value="0" autocomplete="false">';
      html += '</div>';
      html += '</td>';
      html += '<td class="ele10">';
      html += '<div class="form-group">';
      html += '<input type="text" id="total" name="total[]" readonly class="form-control total onlyint text-center checkcalculation" value="0" autocomplete="false">';
      html += '</div>';
      html += '</td>';
      html += '<td class="ele11">';
      html += '<div class="form-group">';
      html += '<button type="button" class="btn btn-danger btn-xs removerowbtn"><i class="fa fa-trash"></i></button>';
      html += '</div>';
      html += '</td>';
      html += '</tr>';
      $(".append_to_me").append(html);
      $(".stock_no").select2();
      $(".mesurement").select2();
    }

    function updatecalculation(rowid) {
      var tabid = $(".tblrow"+rowid);
      var quantity = tabid.children('.ele4').children('.form-group').children('.quantity').val();
      var unit = tabid.children('.ele5').children('.form-group').children('.unit').val();
      var discount = tabid.children('.ele6').children('.form-group').children('.discount').val();
      var gst = tabid.children('.ele8').children('.form-group').children('.gst').val();
      total = 0;
      if(quantity != "" && unit != "") {
        total = quantity * unit;
      }
      if(discount != "" && discount != 0) {
        var dec = (discount / 100).toFixed(2);
        var mult = total * dec;
        total = total - mult;
        tabid.children('.ele7').children('.form-group').children('.discount_amount').val(mult);
      }
      else {
        tabid.children('.ele7').children('.form-group').children('.discount_amount').val(0);
      }
      if(gst != "" && gst != 0) {
        var dec = (gst / 100).toFixed(2);
        var mult = total * dec;
        total = total + mult;
        tabid.children('.ele9').children('.form-group').children('.gst_amount').val(mult);
      } else {
        tabid.children('.ele9').children('.form-group').children('.gst_amount').val(0);
      }
      // console.log(total);
      tabid.children('.ele10').children('.form-group').children('.total').val(total);

      updategrandtotal();
    }

    function updategrandtotal() {
      var grandtotal = 0;
      $(".total").each(function(e){
        grandtotal += parseFloat($(this).val());
      });

      $("#grand_total").val(grandtotal.toFixed(2));
    }
    $(document).ready(function(e){
      updategrandtotal();
    });

    $(document).on("click",".addproductbtn",function(e){
      resetquantityform();
      $(".modalsubmitbtn").attr("onclick","return checkquantityval('add')");
    });

    $(document).on("click",".removerowpayment",function(e) {
    var ele = $(this).closest('tr');
    if(ele.attr('data-id')) {
    var conf = confirm("Are you sure want to delete this record?");
    if(conf) {
      var route = '{{ route('delete.expenses.payment',["id"=>":ID","tid"=>":TID"]) }}';
      route = route.replace(':ID', ele.attr('data-id'));
      route = route.replace(':TID', ele.attr('data-tid'));

      $.ajax({
        url:route,
        type:'GET',
        success:function(e) {
          if(e.status == "true" && e.message == "success") {
            toastr.success("Payment Removed Successfully");
            ele.remove();
          } else {
            toastr.error(e.message);
          }
        }
      });
    }
    } else {
    ele.remove();
    }
      checkpaymentdivs();
    });

    function checkpaymentdivs() {
      if($("#append_payment_row > tr").length == "0") {
        addmorepayment();
      }
    }


    $(document).on("change",".stock_no",function(e) {
      var element = $(this).find(":selected");
      $(this).closest('tr').children('.ele4').children('.form-group').children('.quantity').val(element.data("pending"));
      $(this).closest('tr').children('.ele5').children('.form-group').children('.myunit').val(element.data("unit"));
      var tblid = $(this).closest('tr').attr('data-id');
      updatecalculation(tblid);
    });

    $(document).on("keyup",".checkcalculation",function(e){
      var tblid = $(this).closest('tr').attr('data-id');
      updatecalculation(tblid);
    });
    $(document).on("click",".removerowbtn",function(e){
      $(this).closest('tr').remove();
      updategrandtotal();
    });


    $(function() {
      $('#confirmAns').change(function() {
        if($(this).prop('checked')) {
          //yes readonly
          $(".due_date_div").hide();
        } else {
          //no readonly
          $(".due_date_div").show();
        }
      })
    })
  </script>
<script type="text/javascript">
  $(document).ready(function(e) {
    jcropratio = 0;
    jcropresize = true;
  });
</script>
@endsection
