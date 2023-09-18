@php
  $AdminHelper = new \App\Helpers\AdminHelper; 
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng nhập | {{ config('app.name') }}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
  <!-- icheck-bootstrap -->
  <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="javascript:void(0)"><b>{{ config('app.name') }}</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <h4 class="login-box-msg">Đăng nhập</h4>

      {!! Form::open(array('route' => 'admin.login.postLogin', 'method' => 'post')) !!}
      @if (count($errors) > 0)
        @foreach ($errors->all() as $error)
          {!! $AdminHelper::message($error) !!} 
        @endforeach
      @elseif ($message = Session::get('error'))
        {!! $AdminHelper::message($message) !!} 
      @endif
        <div class="input-group mb-3">
          <input type="text" class="form-control" required name="username" value="{{ isset($_COOKIE['remember-user'])?$_COOKIE['remember-user']:'' }}" placeholder="Tài khoản" />
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" required value="{{ isset($_COOKIE['remember-pass'])?base64_decode(base64_decode($_COOKIE['remember-pass'])):'' }}" placeholder="Mật khẩu">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 text-right">
            <div class="icheck-primary">
              <input type="checkbox" name="remember"{{ isset($_COOKIE['remember-user'])?' checked':'' }} id="remember">
              <label for="remember" style="color: #007bff; font-weight: 500;">
                Ghi nhớ tài khoản
              </label>
            </div>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
            {{-- <a class="btn btn-danger btn-block" href="{{ route('admin.login.getReset') }}">Quên mật khẩu</a> --}}
          </div>
        </div>
      {!! Form::close() !!}

    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
</body>
</html>