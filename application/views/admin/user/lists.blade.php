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
              <div class="col-md-2 pull-right" >
              <button type="button" class="btn btn-block btn-default" onclick="location.href='/admin/user/add'">add user</button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <tr>
                  <th>id</th>
                  <th>group</th>
                  <th>status</th>
                  <th>OPERATION</th>
                </tr>
                @foreach($data as $row)
                <tr>
                  <td>{{ $row['id'] }}</td>
                  <td>{{ $row['group_name'] }}</td>
                  <td><span class="label {{ ($row['is_enabled'] == 1) ? 'label-success' : 'label-danger' }}">{{ ($row['is_enabled'] == 1) ? 'enabled' : 'disabled' }}</span></td>
                  <td></td>
                </tr>
                @endforeach
              </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              @include('admin._parts.pager')
            </div>
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