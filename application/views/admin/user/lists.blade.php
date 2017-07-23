@extends('admin._layouts.default')

@section('title', 'Dashboard')
@section('bodyclass', 'hold-transition skin-blue sidebar-mini')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{ $_current_menu['name'] }}
        <small>preview of simple tables</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="javascript:void(0);"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);">{{ $_current_menu['parent_name'] }}</a></li>
        <li class="active">{{ $_current_menu['name'] }}</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">&nbsp;</h3>
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
                @foreach($lists as $row)
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
              @if(!empty($paginate_info))
              <ul class="pagination pagination-sm no-margin pull-right">
                <li><a href="{{ $paginate_info['first_page_url']  }}">&laquo;</a></li>
                @for ($i = 1; $i <= $paginate_info['last_page']; $i++)
                <li><a href="{{ $paginate_info['base_page_url'] . $i }}">{{ $i }}</a></li>
                @endfor
                <li><a href="{{ $paginate_info['last_page_url']  }}">&raquo;</a></li>
              </ul>
              @endif
            </div>
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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