
{!!Html::script('/public/admin/theme1/bower_components/jquery/dist/jquery.min.js')!!}
{!!Html::script('http://code.jquery.com/jquery-migrate-1.0.0.js')!!}
{!!Html::script('/public/admin/theme1/bower_components/bootstrap/dist/js/bootstrap.min.js')!!}
{!!Html::script('/public/admin/theme1/general/toogle/js/bootstrap-toggle.min.js')!!}
{!!Html::script('/public/admin/theme1/bower_components/fastclick/lib/fastclick.js')!!}
{!!Html::script('/public/admin/theme1/general/js/jquery-ui.min.js')!!}
{!!Html::script('/public/admin/theme1/general/js/jquery.ui.touch-punch.min.js')!!}

{!!Html::script('/public/admin/theme1/dist/js/adminlte.min.js')!!}

{!!Html::script('/public/admin/theme1/general/remodal/remodal.min.js')!!}

{!!Html::script('/public/admin/theme1/general/js/toastr.min.js')!!}
{!!Html::script('/public/admin/theme1/general/js/offline.min.js')!!}
{!!Html::script('/public/admin/theme1/general/js/snake.js')!!}
{!!Html::script('/public/admin/theme1/general/js/image-preview-input.js')!!}
{!!Html::script('/public/admin/theme1/general/scroll/jquery.jscroll.min.js')!!}
{!!Html::script('/public/admin/theme1/general/jcrop/js/jquery.Jcrop.min.js')!!}
{!!Html::script('/public/admin/theme1/general/jcrop/js/script.js')!!}
{!!Html::script('https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js')!!}
{!!Html::script('//rawgithub.com/stidges/jquery-searchable/master/dist/jquery.searchable-1.0.0.min.js')!!}
{!!Html::script('/public/admin/theme1/bower_components/select2/dist/js/select2.full.min.js')!!}
{!!Html::script('/public/admin/theme1/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')!!}

{!!Html::script('/public/admin/theme1/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')!!}
{!!Html::script('/public/admin/theme1/bower_components/datatables.net/js/jquery.dataTables.min.js')!!}
{!!Html::script('/public/admin/theme1/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')!!}


{!!Html::script('/public/admin/theme1/bower_components/moment/min/moment.min.js')!!}
{!!Html::script('/public/admin/theme1/bower_components/bootstrap-daterangepicker/daterangepicker.js')!!}
{!!Html::script('/public/admin/theme1/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')!!}

{!!Html::script('/public/admin/theme1/plugins/input-mask/jquery.inputmask.js')!!}
{!!Html::script('/public/admin/theme1/plugins/input-mask/jquery.inputmask.date.extensions.js')!!}
{!!Html::script('/public/admin/theme1/plugins/input-mask/jquery.inputmask.extensions.js')!!}

{!!Html::script('/public/admin/theme1/plugins/iCheck/icheck.min.js')!!}

{!!Html::script('/public/admin/theme1/dist/js/demo.js')!!}

<script type="application/javascript">
var inst;
var inst_zoom;
var jcropresize = true;
var jcropratio = 1;
var filetextinput;
var fileaccept = 'image/*';
var upload_route = "{{ route('upload.image') }}";
var btnid;
var uploadedfilenamesuccess;
var fileattrid;
var uploadfn;
var uploadeventholder;
var public_path = "{{ url('/') }}";

$.sum = function(arr) {
    var r = 0;
    $.each(arr, function(i, v) {
        r += +v;
    });
    return r;
}

// Numeric only control handler
jQuery.fn.ForceNumericOnly =
function()
{
   return this.each(function()
   {
       $(this).keydown(function(e)
       {
           var key = e.charCode || e.keyCode || 0;
           // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
           // home, end, period, and numpad decimal
           return (
               key == 8 ||
               key == 9 ||
               key == 13 ||
               key == 46 ||
               key == 110 ||
               key == 190 ||
               (key >= 35 && key <= 40) ||
               (key >= 48 && key <= 57) ||
               (key >= 96 && key <= 105));
       });
   });
};

function isPhone(phone){
  var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
  if(phone.match(phoneno)) {
    return true;
  } else {
    return false;
  }
}

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

function confirmbox()
{
  var conf = confirm('Are you sure want to delete this record?');
  return conf;
}

function modaloverlay(element,action)
 {
	 if(action == "show")
	 {
		 $(element+" .modal-content .overlay").show();
	 }
	 else
	 {
		 $(element+" .modal-content .overlay").hide();
	 }
 }

$(document).on("click","#fbholder",function(e) {
   $('#fbinput').trigger('click');
});

function changeStateBasedonGST(noWhitespaceValue) {
  // console.log($('#state option:selected').attr('data-gst'));
  var state_id = $('#state').find("[data-gst='" + noWhitespaceValue + "']").attr('value');
  if(state_id != "") {
    $('#state').val(state_id).trigger('change');
  }


}

function changeselectcolor(e) {
    if($(e).val() == "1") {
        $(e).css('color','red');
    } else {
        $(e).css('color','green');
    }
}

function verifyGST(e) {
  setTimeout(function (){
    var code = $("#gstno").val().substring(0, 2);
    if(code.length >= 1) {
      // alert();
      changeStateBasedonGST(code);
    }
   },0);
}

$(document).on("keyup","#gstno",function(e) {
  if (e.which !== 32) {
        var value = $(this).val();
        var noWhitespaceValue = value.replace(/\s+/g, '');
        var noWhitespaceCount = noWhitespaceValue.length;
        if (noWhitespaceCount === 2) {
            // Call API
            changeStateBasedonGST(noWhitespaceValue);
        }

        if(noWhitespaceValue < 1) {
          $('#state').val("").trigger('change');
        }
    }
});


function resizedatatablemobile() {
  // $("#DataTables_Table_0_length.dataTables_length").parent('.col-sm-6').removeClass('col-sm-6').addClass('col-xs-6');
}


$(document).on("click",".fbremove",function(e) {
   var default_asset = $('#fbholder').attr('data-default');
   $('#fbholder').attr('src', default_asset);
   $('#fbinput').val(null);
   $(this).hide();
});

function readURL(input) {
 if (input.files && input.files[0]) {
     var reader = new FileReader();

     reader.onload = function (e) {
         $('#fbholder').attr('src', e.target.result);
     };

     reader.readAsDataURL(input.files[0]);
     $(".fbremove").css('display','block');
     opencropbox(input);
 }
}

function select2Bank() {
  $(".select2-bank").select2({
      placeholder: "Select Bank",
      templateResult: formatbank,
      templateSelection: function (option) {
          if (option.id.length > 0 ) {
              var base_url = "{{ asset('/') }}";
              return '<img src="'+base_url+'/'+option.element.attributes['data-img'].value+'" height="17" width="17" /> &nbsp;' + option.text;
          } else {
              return option.text;
          }
      },
      escapeMarkup: function (m) {
        return m;
      }
  });
}


function daterangepicker(callback = "") {
  $('.daterange').daterangepicker({
    ranges   : {
      /*'Today'       : [moment(), moment()],
      'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month'  : [moment().startOf('month'), moment().endOf('month')],
      'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],*/
      'DEVNDT Range'  : [moment().subtract(6, 'month'), moment().add(6, 'month')]
    },
    startDate: moment().subtract(29, 'days'),
    endDate  : moment(),
      locale: {
          format: 'DD-MM-YYYY'
      }
  },
  function (start, end) {
    $('.daterange span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
    $("#filterStart").val(start.format('YYYY-MM-DD'));
    $("#filterEnd").val(end.format('YYYY-MM-DD'));
    $("#filterStart").trigger("change");

    if($("#filterStartNew").length) {
        $("#filterStartNew").val(start.format('YYYY-MM-DD'));
        $("#filterEndNew").val(end.format('YYYY-MM-DD'));
        $("#filterStartNew").trigger("change");
    }

  });
}

function initdatepicker(setdate = false) {
  $('.datepicker').datepicker({
      'autoclose': true,
      'format': 'dd-mm-yyyy',
      // 'startDate': new Date(),
      'todayHighlight': true,
    });

    if(setdate) {
    $(".datepicker" ).datepicker( "setDate", new Date());
    }

    // $('.datepicker').each(function(i, obj) {
    //     if($(this).attr('default-date') && $(this).attr('id')) {
    //         var default_date = $(this).attr('default-date');
    //         var default_id = $(this).attr('id');
    //         if(default_date != "" && default_id != "") {
    //             $("#"+default_id ).datepicker( "setDate", default_date);
    //         }
    //     }
    // });
}

function formatbank (option) {
			// console.log(option);
			if (!option.id) { return option.text; }
      var base_url = "{{ asset('/') }}";
      // console.log(base_url);
			var ob = '<img src="'+base_url+'/'+option.element.attributes['data-img'].value+'" height="30" width="30" /> &nbsp;' + option.text;	// replace image source with option.img (available in JSON)
			return ob;
		};

$(document).ready(function(e) {
    $(".onlyint").ForceNumericOnly();
    // $('.dtable').DataTable();
	$('.editor').wysihtml5();
  $('.select2').select2();
  $('[data-mask]').inputmask();


    initdatepicker();
    select2Bank();
    daterangepicker();
    resizedatatablemobile();

    inst = $('[data-remodal-id=uploadmodal]').remodal();
    inst_zoom = $('[data-remodal-id=imagezoom]').remodal();

if($('#gstno').length) {
$('#gstno').attr('autocomplete', 'off');
}

    $( ".buy-now" ).draggable({ containment: ".content-wrapper", scroll: false });

//error message
<?php
if(Session::has('error')) {
?>
toastr.error('{{ Session::get("error") }}');
<?php
}
?>

//sucess message
<?php
if(Session::has('success')) {
?>
toastr.success('{{ Session::get("success") }}');
<?php
}
?>

});

setInterval(function(){
	     Offline.check();
	  	  if(Offline.state == "down")
		  {
			  $("#show_internet_status").html('<i class="fa fa-circle text-default"></i> Offline');
		  }
		  else
		  {
			  $("#show_internet_status").html('<i class="fa fa-circle text-success"></i> Online');
		  }
	  }, 3000);

//select2 focus
// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
  $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});

// steal focus during close - only capture once and stop propogation
$('select.select2').on('select2:closing', function (e) {
  $(e.target).data("select2").$selection.one('focus focusin', function (e) {
    e.stopPropagation();
  });
});

$(document).on("click","table img",function(e){
   $("#photozoom").attr('src',$(this).attr('src'));
   inst_zoom.open();
});

$(document).on("click",".zoomme",function(e){
    $("#photozoom").attr('src',$(this).attr('src'));
    inst_zoom.open();
});

function getchequeHandler(type,opration) {
    console.log(type+" "+opration);
    if(type == "edit" && opration == "Deposit") {
        toastr.error("You can not edit this transaction. you have to reopen cheque first.");
    } else if(type == "edit" && opration == "Withdraw") {
        toastr.error("You can not edit this transaction. you have to reopen cheque first.");
    } else if(type == "delete" && opration == "Deposit") {
        toastr.error("You can not delete this transaction. you have to reopen cheque first.");
    } else if(type == "delete" && opration == "Withdraw") {
        toastr.error("You can not delete this transaction. you have to reopen cheque first.");
    } else {
        toastr.error("unknown");
    }
}

$(document).ready(function(){

    $('#search_data').autocomplete({
        source: "{{ route('find.stock') }}",
        minLength: 1,
        autoFocus: true,
        select: function(event, ui)
        {
            $('#search_data').val(ui.item.name);
            window.location.href = ui.item.value;
            return false;
        },focus :showResult,
        change: showResult,
        keydown: showResult
    }).data('ui-autocomplete')._renderItem = function(ul, item){
        return $("<li class='ui-autocomplete-row'></li>")
            .data("item.autocomplete", item)
            .append(item.label)
            .appendTo(ul);
    };

});

function showResult(event, ui) {
    event.preventDefault();
    $('#search_data').val(ui.item.name);
}


$(document).on("click","#submitref",function(red){
    red.preventDefault();

    if($("#add_ref_fname").val() == "") {
        toastr.error("Please, Enter First Name");
        $("#add_ref_fname").focus();
        return false;
    }

    var formarr = $("#add_ref_form").serialize();
    $.ajax({
        url:'{{ route('ref.register') }}',
        type:'POST',
        data:formarr,
        success:function(e) {
            if(e.status == "true") {
                toastr.success(e.message);
                $("#addReferenceModel").modal('hide');
                table.ajax.reload();
            } else {
                toastr.error(e.message);
                return false;
            }
        }
    });
});


$(document).on("click","#submiteditref",function(red){
    red.preventDefault();

    if($("#edit_ref_id").val() == "") {
        toastr.error("Please, Reference id required");
        $("#edit_ref_id").focus();
        return false;
    }
    if($("#edit_ref_fname").val() == "") {
        toastr.error("Please, Enter First Name");
        $("#edit_ref_fname").focus();
        return false;
    }

    var formarr = $("#edit_ref_form").serialize();
    $.ajax({
        url:'{{ route('ref.update') }}',
        type:'POST',
        data:formarr,
        success:function(e) {
            if(e.status == "true") {
                toastr.success(e.message);
                $("#editReferenceModel").modal('hide');
                table.ajax.reload();
            } else {
                toastr.error(e.message);
                return false;
            }
        }
    });
});


$(document).on("click","#submitcompany",function(red){
    red.preventDefault();

   if($("#ac_comp_name").val() == "") {
       toastr.error("Please, Enter Company Name");
       $("#ac_comp_name").focus();
       return false;
   }

    var formarr = $("#add_company_form").serialize();
    $.ajax({
        url:'{{ route('company.register') }}',
        type:'POST',
        data:formarr,
        success:function(e) {
            if(e.status == "true") {
                toastr.success(e.message);
                $("#addCompanyModel").modal('hide');
                table.ajax.reload();
            } else {
                toastr.error(e.message);
                return false;
            }
        }
    });
});



$(document).ready(function(e){
    // initdatepicker(true);
    $('.select2').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent()
        });
    });

    $(':input[type=text]:not([data-mask])').on('propertychange input', function (e) {
        var mystr = $(this).val();
        $(this).val(mystr.charAt(0).toUpperCase() + mystr.slice(1));
    });

    $('.calmarks').on('propertychange input', function (e) {
        var current_val = parseInt($(this).val());
        if(current_val >= 71 && current_val <= 100) {
            $(this).css('border-color','#d2d6de');
        } else {
            $(this).css('border-color','#f00');
        }
    });

    $(".company-select2").select2({
        //minimumInputLength: 2,
        ajax: {
            url: "{{ route('company.select') }}",
            type: "POST",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    _token: "{{ csrf_token() }}",
                    searchTerm: params.term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true,
            success:function(e) {
                jQuery.map(e, function(obj) {
                    if(obj.id === company_select)
                        console.log(obj.text); // or return obj.name, whatever.
                });
            }
        }
    });

    $(".ref-select2").select2({
        //minimumInputLength: 2,
        ajax: {
            url: "{{ route('ref.select') }}",
            type: "POST",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    _token: "{{ csrf_token() }}",
                    searchTerm: params.term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    $('.company-select2').each(function(index, currentElement) {
        if($(".company-select2")[index].hasAttribute('data-default')) {
            var company_select = $(".company-select2")[index].getAttribute('data-default');
            var get_id = $(".company-select2")[index].getAttribute("id");
            $.ajax({
                url: "{{ route('company.info') }}",
                type: "POST",
                data: '_token={{ csrf_token() }}&id='+company_select,
                success:function(e) {
                    var option = new Option(e.company_name, e.id, true, true);
                    $("#"+get_id).append(option).trigger('change');
                }
            });
        }
    });
    if($(".ref-select2")[0].hasAttribute('data-default')) {
        var ref_select = $(".ref-select2").attr('data-default');
        $.ajax({
            url: "{{ route('ref.info') }}",
            type: "POST",
            data: '_token={{ csrf_token() }}&id='+ref_select,
            success:function(e) {
                var fullname = e.fname+' '+e.mname+' '+e.lname;
                var option = new Option(fullname, e.id, true, true);
                $(".ref-select2").append(option).trigger('change');
            }
        });
    }

});
</script>