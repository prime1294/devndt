<?php
$user = Sentinel::check();
$role = Sentinel::findRoleById($user->id);
?>
<!--Search Modal-->
<div class="remodal contact-form text-left" data-remodal-id="uploadmodal" data-remodal-options="hashTracking: false, closeOnOutsideClick: false">

  <form id="upload_form" enctype="multipart/form-data" method="post" onsubmit="return checkForm()">
    {!! csrf_field() !!}
    <div class="bbody">
      <!-- upload form -->
      <!-- hidden crop params -->
      <input type="hidden" id="upload_dir" name="upload_dir">
      <input type="hidden" id="x1" name="x1" />
      <input type="hidden" id="y1" name="y1" />
      <input type="hidden" id="x2" name="x2" />
      <input type="hidden" id="y2" name="y2" />

      <div><input type="file" name="image_file" id="image_file" onchange="fileSelectHandler()" /></div>
      <div class="error text-center" style="color:white;"></div>
      <div class="step2">
          <center><img id="preview" class="img-responsive" height="200" /></center>
          <div class="info">
              <input type="hidden" id="filesize" placeholder="File size" name="filesize" />
              <input type="hidden" id="filetype" placeholder="Type" name="filetype" />
              <input type="hidden" id="filedim" placeholder="Image dimension" name="filedim" />
              <input type="hidden" placeholder="W" id="w" name="w" />
              <input type="hidden" placeholder="H" id="h" name="h" />
          </div>
      </div>
  </div>
  <center><br><button type="button" class="pickeruploadbtn btn btn-success btn-lg" onclick="executeupload()" disabled="disabled"><i class="fa fa-check"></i> Upload</button>
  <button data-remodal-action="cancel" class="btn btn-danger btn-lg"><i class="fa fa-times"></i> Cancel</button></center>
</form>





</div>


<div class="remodal zoom-form text-left" data-remodal-id="imagezoom" data-remodal-options="hashTracking: false">
    <center><img id="photozoom" class="img-responsive" src="{{ asset('placeholder.jpg')  }}"></center>
</div>


<footer class="main-footer">
<div class="pull-right hidden-xs">

</div>
<strong>Copyright &copy; <?php echo date('Y',strtotime("now")); ?> <a href="<?php echo url('/'); ?>">{{ config('setting.app_owner') }}</a>.</strong> All rights
reserved.
</footer>

