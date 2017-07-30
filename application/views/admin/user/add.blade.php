@extends('admin._layouts.default')

@section('title', 'Dashboard')
@section('bodyclass', 'hold-transition skin-blue sidebar-mini')

@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">&nbsp;</h3>
            </div>
            <!-- /.box-header -->
            <form role="form" method="POST" action="{{ $is_edit ? '/admin/user/edit_submit' : '/admin/user/add_submit' }}">
              <div class="box-body">
                <div class="form-group">
                  <label for="input_id">id</label>
                  <input type="text" class="form-control" name="id" id="input_id" placeholder="id">
                </div>
                <div class="form-group">
                  <label for="input_password">Password</label>
                  <input type="password" class="form-control" name="password" id="input_password" placeholder="Password">
                </div>
                <div class="form-group">
                  <label for="input_group">group</label>
                  <select class="form-control" name="group_id" id="input_group">
                    @foreach($groups as $row)
                    <option value="{{$row['id']}}">{{ $row['name'] }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="is_enabled"> enabled
                  </label>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
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