@extends('admin._layouts.default')

@section('title', 'Log in')
@section('bodyclass', 'hold-transition login-page')

@section('content')
<div class="login-box">
  <div class="login-logo">
    <a href="{{ '//' . SITE_URL }}/"><b>Admin</b>LTE</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg text-danger">{{ $errormsg }}</p>

    <form action="{{ '//' . SITE_URL }}/admin/home/login_submit" method="post">
      <div class="form-group has-feedback">
        <input type="text" name="userid" class="form-control" placeholder="User">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="text" name="captcha" class="form-control" placeholder="captcha">
        <span class="glyphicon glyphicon-eye-open form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <a href="javascript:void(0);" onclick="recaptcha()">
          <img id="login_captcha_img" src="{{ $captcha_img }}" style="width: 200; height: 50; border: 0;"/>
        </a>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block">Login</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
@endsection
@section('scripts')
<script type="text/javascript">
function recaptcha(){
  $.get('/admin/home/recaptcha',function(data) {
      $('#login_captcha_img').attr('src',data.captcha);
  },'json');
}
</script>
@endsection