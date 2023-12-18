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
          {!! Form::open(array('route' => 'admin.customer.store', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
            <div class="card-body">
              <div class='row'>
                <div class="col-sm-12 col-xl-4">
                  <div class="form-group">
                    <label for="code">Loại<b style="color: red;"> (*)</b></label>
                    {!! Form::select('type', \App\Models\CoreCustomer::ARR_TYPE ,null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                </div>
                <div class='col-sm-12 col-xl-4'>
                  <div class="form-group">
                    <label for="code">Mã khách hàng<b style="color: red;"> (*)</b></label>
                    {!! Form::text('code', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                </div>
                <div class='col-sm-12 col-xl-4'>
                  <div class="form-group">
                    <label for="name">Tên khách hàng<b style="color: red;"> (*)</b></label>
                    {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                </div>
                <div class='col-sm-12 col-xl-4'>
                  <div class="form-group">
                    <label for="tax_code">Mã số thuế<b style="color: red;"> (*)</b></label>
                    {!! Form::text('tax_code', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                </div>
                <div class='col-sm-12 col-xl-4'>
                  <div class="form-group">
                    <label for="tax_code">Người liên hệ<b style="color: red;"> (*)</b></label>
                    {!! Form::text('recipient', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                </div>
                <div class='col-sm-12 col-xl-4'>
                  <div class="form-group">
                    <label for="address">Địa chỉ<b style="color: red;"> (*)</b></label>
                    {!! Form::text('address', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                </div>
                <div class='col-sm-12 col-xl-4'>
                  <div class="form-group">
                    <label for="phone">Số điện thoại<b style="color: red;"> (*)</b></label>
                    {!! Form::number('phone', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                </div>
                <div class='col-sm-12 col-xl-4'>
                  <div class="form-group">
                    <label for="email">Email</label>
                    {!! Form::email('email', null, array('class' => 'form-control')) !!}
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Lưu thông tin</button>
              <a href="{{ route('admin.customer.index') }}" class="btn btn-default">Quay lại</a>
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
