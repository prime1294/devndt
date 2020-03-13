/**
 *
 * HTML5 Image uploader with Jcrop
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2012, Script Tutorials
 * http://www.script-tutorials.com/
 */

 // Create variables (in this scope) to hold the Jcrop API and image size
 var jcrop_api, boundx, boundy;

// convert bytes into friendly format
function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};

function allowresize(status) {
  jcrop_api.setOptions({ allowResize: status });
	jcrop_api.focus();
}

function allowrotate(size) {
  jcrop_api.setOptions({rotate : size});  //rotate 90
  jcrop_api.focus();
}

function allowSelect(status) {
  jcrop_api.setOptions({ allowSelect: status });
	jcrop_api.focus();
}

function allowratio(allow,set1 = 0,set2 = 0) {
  if(allow) {
    jcrop_api.setOptions({ aspectRatio: set1/set2 });
  } else {
    jcrop_api.setOptions({ aspectRatio: 0 , allowResize: true, minSize: [ 80, 80 ] });
  }
	jcrop_api.focus();
}

// check for selected crop region
function checkForm() {
    if (parseInt($('#w').val())) return true;
    $('.error').html('Please select a crop region and then press Upload').show();
    return false;
};

// update info by cropping (onChange and onSelect events handler)
function updateInfo(e) {
  // if (parseInt(e.w) > 0){
  //     var rx = 200 / e.w;
  //     var ry = 200 / e.h;
  //
  //     $('#preview').css({
  //         width: Math.round(rx * boundx) + 'px',
  //         height: Math.round(ry * boundy) + 'px',
  //         marginLeft: '-' + Math.round(rx * e.x) + 'px',
  //         marginTop: '-' + Math.round(ry * e.y) + 'px'
  //     });
  // }


    $('#x1').val(e.x);
    $('#y1').val(e.y);
    $('#x2').val(e.x2);
    $('#y2').val(e.y2);
    $('#w').val(e.w);
    $('#h').val(e.h);
};


// clear info by cropping (onRelease event handler)
function clearInfo() {
    $('.info #w').val('');
    $('.info #h').val('');
};

function fileSelectHandler(fileidattr = '#image_file') {
    inst.open();
    // get selected file
    fileattrid = fileidattr;
    var oFile = $(fileidattr)[0].files[0];

    // hide all errors
    $('.error').hide();

    // check for image type (jpg and png are allowed)
    var rFilter = /^(image\/jpeg|image\/png)$/i;
    if (! rFilter.test(oFile.type)) {
        $('.error').html('Please select a valid image file (jpg and png are allowed)').show();
        return;
    }

    // check for file size
    // if (oFile.size > 250 * 1024) {
    //     $('.error').html('You have selected too big file, please select a one smaller image file').show();
    //     return;
    // }

    // preview element
    var oImage = document.getElementById('preview');

    // prepare HTML5 FileReader
    var oReader = new FileReader();
        oReader.onload = function(e) {

        // e.target.result contains the DataURL which we can use as a source of the image
        oImage.src = e.target.result;
        oImage.onload = function () { // onload event handler

            // display step 2
            // $('.step2').fadeIn(500);
            $(".pickeruploadbtn").removeAttr('disabled')

            // display some basic image info
            var sResultFileSize = bytesToSize(oFile.size);
            $('#filesize').val(sResultFileSize);
            $('#filetype').val(oFile.type);
            $('#filedim').val(oImage.naturalWidth + ' x ' + oImage.naturalHeight);
            uploadedfilenamesuccess = oFile.name;
            // destroy Jcrop if it is existed
            if (typeof jcrop_api != 'undefined')
                jcrop_api.destroy();

            // var width2  = $('#preview').prop('naturalWidth');
            // var height2 = $('#preview').prop('naturalHeight');
            // alert(width2+" - "+height2);

            // initialize Jcrop
            $('#preview').Jcrop({
                minSize: [32, 32], // min crop size
                aspectRatio : jcropratio, // keep aspect ratio 1:1
                // bgFade: true, // use fade effect
                allowResize:jcropresize,
                bgOpacity: .3, // fade opacity
                onChange: updateInfo,
                onSelect: updateInfo,
                onRelease: clearInfo,
                // trueSize: [150,2000],
                rotate:0,
                boxWidth: 250
            }, function(){
                // jcrop_api = this;
                // initJcrop();

                // use the Jcrop API to get the real image size
                var bounds = this.getBounds();
                boundx = bounds[0];
                boundy = bounds[1];

                // Store the Jcrop API in the jcrop_api variable
                $('.requiresjcrop').hide();
                $('.requiresjcrop').show();
                jcrop_api = this;
                jcrop_api.animateTo([100,100,400,300]);
                jcrop_api.setImage(oImage.src);
            });
        };
    };

    // read selected file as DataURL
    oReader.readAsDataURL(oFile);
}


function executeupload() {
  uploadfn.call();
}

function uploadboximage() {
  inst.close();
  // $('#fbholder').attr('src', e.target.result);
  var form = $('#upload_form')[0];
  var formData = new FormData(form);
  $.ajax({
        url         : upload_route,
        data        : formData ? formData : form.serialize(),
        cache       : false,
        contentType : false,
        processData : false,
        type        : 'POST',
        success     : function(data, textStatus, jqXHR){
            // Callback code
            // console.log(data);
            if(data) {
              toastr.success("Image uploaded Successfully");
              $("#upload_image_text").val(data);
            } else {
              toastr.error("Image not uploaded");
            }

            $("#"+btnid).html('<i class="glyphicon glyphicon-folder-open"></i> &nbsp; '+uploadedfilenamesuccess);
        }
    });
}

function uploadimagefile() {
  inst.close();
  if(uploadeventholder == "button") {
  $("#"+btnid).html('<i class="glyphicon glyphicon-folder-open"></i> &nbsp; Please wait while image uploading...');
  }
  var form = $('#upload_form')[0];
  var formData = new FormData(form);
  $.ajax({
        url         : upload_route,
        data        : formData ? formData : form.serialize(),
        cache       : false,
        contentType : false,
        processData : false,
        type        : 'POST',
        success     : function(data, textStatus, jqXHR){
            // Callback code
            // console.log(data);
            if(data) {
              toastr.success("Image uploaded Successfully");
              $("#upload_image_text").val(data);
            } else {
              toastr.error("Image not uploaded");
            }

            if(uploadeventholder == "box") {
              // alert("this is box");
              $("#"+btnid).attr('src',public_path+"/public/"+data);
            } else {
              $("#"+btnid).html('<i class="glyphicon glyphicon-folder-open"></i> &nbsp; '+uploadedfilenamesuccess);
            }

            $("#"+filetextinput).val(data).trigger('change');
        }
    });
}


function triggerfile(btn,id,uploaddir,accepttype,holder = "button") {
  btnid = btn;
  uploadeventholder = holder;
  $("#image_file").attr('accept',accepttype);
  $("#upload_dir").val(uploaddir);
  $("#image_file").trigger('click');
  filetextinput = id;
  $(".pickeruploadbtn").attr('disabled','disabled');
  uploadfn = uploadimagefile;
}

function opencropbox(input) {
  // console.log(input);
  // alert(input.id);
  fileSelectHandler('#'+input.id);
  uploadfn = uploadboximage;
}
