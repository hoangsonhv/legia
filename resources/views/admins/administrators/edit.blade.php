@extends('layouts.admin')

@section('css')
  <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endsection

@section('content')

@include('admins.breadcrumb')

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        @include('admins.message')
      </div>
      <div class="col-12">
        <div class="card">
          @php
            if (!empty($me)) {
              $route = 'admin.administrator.meUpdate';
            } else {
              $route = ['admin.administrator.update', $admin->id];
            }
          @endphp
          {!! Form::model($admin, array('route' => $route, 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
            {!! Form::hidden('id', null) !!}
            <div class="card-body">
              @permission('admin.administrator.resetPassword')
              @if(empty($me))
              <div class="form-group text-right">
                <a class="btn btn-success" href="{{ route('admin.administrator.resetPassword', ['id' => $admin->id]) }}">Tạo lại mật khẩu mới</a>
              </div>
              @endif
              @endpermission
              <div class="form-group">
                <label for="name">Tên<b style="color: red;"> (*)</b></label>
                {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              <div class="form-group">
                <label for="username">Tài khoản<b style="color: red;"> (*)</b></label>
                {!! Form::text('username', null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              <div class="form-group">
                <label for="mail">Email<b style="color: red;"> (*)</b></label>
                {!! Form::email('mail', null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              <div class="form-group">
                <label for="mail">Chức vụ<b style="color: red;"> (*)</b></label>
                {!! Form::select('position_id', $positions, null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              @if(empty($me))
              <div class="form-group">
                <label for="roles">Phòng ban & quyền<b style="color: red;"> (*)</b></label>
                {!! Form::select('roles', $roles, null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              <div class="form-group">
                <label>Trạng thái<b style="color: red;"> (*)</b></label>
                <div class="form-group">
                  <div class="icheck-success d-inline">
                    {!! Form::radio('status', 1, true, array('id' => 'enable')) !!}
                    <label for="enable">Hoạt động</label>
                  </div>
                  <div class="icheck-success d-inline ml-5">
                    {!! Form::radio('status', 0, false, array('id' => 'disable')) !!}
                    <label for="disable">Không hoạt động</label>
                  </div>
                </div>
              </div>
              @endif
              @if(!empty($me))
              <div class="form-group">
                <label for="password">Mật khẩu</label>
                <button class="btn btn-warning float-right change-password" type="button">
                  Thay đổi mật khẩu
                </button>
                <div class="input-group d-none mt-2 update-password">
                  {!! Form::text('password', '', array('class' => 'form-control')) !!}
                  <div class="input-group-append">
                    <button class="btn btn-success" id="generate-password" type="button">Tạo mật khẩu</button>
                  </div>
                </div>
              </div>
              @endif
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Lưu thông tin</button>
              <a href="{{ route('admin.administrator.index') }}" class="btn btn-default">Quay lại</a>
            </div>
          {!! Form::close() !!}
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</section>
@endsection

@section('js')
<script type="text/javascript">
  $( document ).ready(function() {
    if($('#generate-password').length) {
      $('#generate-password').click(function(){
        $('[name="password"]').val(randomString(30));
      });
      $('.change-password').click(function(){
        $('.update-password').removeClass('d-none');
      });
    }
  });
</script>
@endsection
