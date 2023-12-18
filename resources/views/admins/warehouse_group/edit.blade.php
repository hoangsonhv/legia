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
                    <div class="card form-root">
                        {!! Form::model($model, array('route' => ['admin.warehouse-group.update', $model->id], 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
                        {!! Form::hidden('id', null) !!}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="code">Mã nhóm<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('code', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="name">Tên nhóm<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="code">Kim loại / Phi kim loại</label>
                                        {!! Form::select('manufacture_type', \App\Models\WarehouseGroup::ARR_MANUFACTURE_TYPE , null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="code">Thương mại / Sản xuất</label>
                                        {!! Form::select('type', \App\Models\WarehouseGroup::ARR_TYPE , null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="warehouse">Kho nguyên liệu</label>
                                        @php
                                            $warehouseIngredientModel = explode(',', $model->warehouse_ingredient);
                                        @endphp
                                        @foreach(\App\Models\WarehouseGroup::ARR_WAREHOUSE as $keyWarehouse => $warehouse)
                                            <div class="icheck-success">
                                                {!! Form::checkbox('warehouse_ingredient['. $keyWarehouse .']', true, in_array($keyWarehouse, $warehouseIngredientModel) , array('id' => 'warehouse_ingredient_' . $keyWarehouse)) !!}
                                                <label for={{'warehouse_ingredient_' . $keyWarehouse}}>{{$warehouse}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="warehouse">Kho thành phẩm</label>
                                        @php
                                            $warehouseProductModel = explode(',', $model->warehouse_product);
                                        @endphp
                                        @foreach(\App\Models\WarehouseGroup::ARR_WAREHOUSE as $keyWarehouse => $warehouse)
                                            <div class="icheck-success">
                                                {!! Form::checkbox('warehouse_product['. $keyWarehouse .']', true, in_array($keyWarehouse, $warehouseProductModel) , array('id' => 'warehouse_product_' . $keyWarehouse)) !!}
                                                <label for={{'warehouse_product_' . $keyWarehouse}}>{{$warehouse}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                            <a href="{{ route('admin.warehouse-group.index') }}" class="btn btn-default">Quay
                                lại</a>
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
