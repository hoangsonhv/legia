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
          {!! Form::model($logadmin, array('route' => ['admin.logadmin.show', $logadmin->id], 'method' => 'get')) !!}
            <fieldset disabled>
              <div class="card-body">
                <div class="form-group">
                  <label for="admin_id">ID người quản trị</label>
                  {!! Form::text('admin_id', null, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                  <label for="admin_name">Tên người quản trị</label>
                  {!! Form::text('admin_name', null, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                  <label for="ip">IP</label>
                  {!! Form::text('ip', null, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                  <label for="browser">Trình duyệt</label>
                  {!! Form::text('browser', null, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                  <label for="url">URL</label>
                  {!! Form::text('link', null, array('class' => 'form-control')) !!}
                </div>
              </div>
            </fieldset>
            <!-- /.card-body -->
            <div class="card-footer text-right">
              <a href="{{ route('admin.logadmin.index') }}" class="btn btn-default">Quay lại</a>
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
