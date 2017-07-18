<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php require APPPATH . 'views/parts/header.php'; ?>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="<?php echo '//' . SITE_URL; ?>/"><b>JJ</b>Admin</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg text-danger"><?php echo $errormsg; ?></p>

    <form action="<?php echo '//' . SITE_URL; ?>/admin/home/login_submit" method="post">
      <div class="form-group has-feedback">
        <input type="userid" name="userid" class="form-control" placeholder="用戶名">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="密碼">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="captcha" name="captcha" class="form-control" placeholder="驗證碼">
        <span class="glyphicon glyphicon-eye-open form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <a href="javascript:void(0);" onclick="recaptcha()">
          <img id="login_captcha_img" src="<?php echo $captcha_img; ?>" style="width: 200; height: 50; border: 0;"/>
        </a>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block">登錄</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<script type="text/javascript">
function recaptcha(){
  $.get('/admin/home/recaptcha',function(data) {
      $('#login_captcha_img').attr('src',data.captcha);
  },'json');
}
</script>
<?php require APPPATH . 'views/parts/footer.php'; ?>