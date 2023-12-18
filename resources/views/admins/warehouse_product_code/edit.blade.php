@extends('layouts.admin')

@section('css')
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
                    <div class="card form-root">
                        {!! Form::model($model, array('route' => ['admin.warehouse-product-code.update', $model->id], 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
                        {!! Form::hidden('id', null) !!}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="code">Nhóm hàng hóa<b style="color: red;"> (*)</b></label>
                                        {!! Form::select('warehouse_group_id', $groups, null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="code">Mã hàng hóa<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('code', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="code">Tên hàng hóa</label>
                                        {!! Form::text('name', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                            <a href="{{ route('admin.warehouse-product-code.index') }}" class="btn btn-default">Quay lại</a>
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
@endsection
