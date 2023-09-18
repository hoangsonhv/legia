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
          {!! Form::model($role, array('route' => ['admin.role.update', $role->id], 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
            {!! Form::hidden('id', null) !!}
            <div class="card-body">
              <div class="form-group">
                <label for="display_name">Phòng ban<b style="color: red;"> (*)</b></label>
                {!! Form::text('display_name', null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              <div class="form-group">
                <label for="description">Mô tả</label>
                {!! Form::text('description', null, array('class' => 'form-control')) !!}
              </div>
              <div class="form-group">
                <label for="mail">Danh sách quyền<b style="color: red;"> (*)</b></label>
                @foreach($permissions as $kPermission => $vPermission)
                  <div class="permisson">
                    <label>{{ $vPermission['label'] }}</label>
                    @foreach($vPermission['routes'] as $kRoute => $vRoute)
                      <div class="icheck-success">
                        {!! Form::checkbox('permission[]', $kRoute, (in_array($kRoute, $role->permission)), array('id' => $kRoute)) !!}
                        <label for="{{ $kRoute }}">{{ $vRoute['label'] }}</label>
                      </div>
                    @endforeach
                  </div>
                  <div class="dropdown-divider"></div>
                @endforeach
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Lưu thông tin</button>
              <a href="{{ route('admin.role.index') }}" class="btn btn-default">Quay lại</a>
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
