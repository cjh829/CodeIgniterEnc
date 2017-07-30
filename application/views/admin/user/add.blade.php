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
                  <input type="text" class="form-control" placeholder="id" value="{{ $vdata['id'] }}" {{ $is_edit ? 'disabled' : '' }}>
                  @if($is_edit)
                  <input type="hidden" name="id" id="input_id" value="{{ $vdata['id'] }}">  <!-- preserve id by hidden input -->
                  @endif
                </div>
                <div class="form-group">
                  <label for="input_password">Password</label>
                  <input type="password" class="form-control" name="password" id="input_password" placeholder="Password">
                </div>
                <div class="form-group">
                  <label for="input_group">group</label>
                  <select class="form-control" name="group_id" id="input_group">
                    @foreach($groups as $row)
                    <option value="{{$row['id']}}" {{ ($row['id'] == $vdata['group_id']) ? 'selected' : '' }}>{{ $row['name'] }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                    <input type="checkbox" name="is_enabled" id="input_is_enabled" {{ ($is_edit && $vdata['is_enabled'] == 1) ? 'checked' : '' }}>
                    <label for="input_is_enabled">enabled</label>
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