@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <style type="text/css">
        .block-file {
            margin-bottom: 10px;
        }
    </style>
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
                        {!! Form::open(array('route' => 'admin.warehouse-receipt.store', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
                        {{Form::hidden('co_id', $coModel ? $coModel->id : null)}}
                        {{Form::hidden('request_id', $request_id ?? null)}}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="delivery_name">Họ tên người giao<b style="color: red;"> (*)</b></label>
                                {!! Form::text('delivery_name', null, array('class' => 'form-control', 'required' => 'required')) !!}
                            </div>
                            <div class="form-group">
                                <label for="note">Ghi chú</label>
                                {!! Form::textarea('note', null, array('class' => 'form-control', 'rows' => 1)) !!}
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="warehouse_at">Nhập tại kho<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('warehouse_at', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="address">Địa điểm<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('address', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Chứng từ đi kèm</label>
                                <div class="input-group block-file">
                                    <div class="custom-file">
                                        <input type="file" name="document[]" class="custom-file-input" multiple />
                                        <label class="custom-file-label">Chọn file</label>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success add-upload">
                                    Thêm file upload
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="title text-primary">Nội dung</h3>
                            @include('admins.warehouse_receipt.includes.list-products')
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                            <a href="{{ route('admin.warehouse-receipt.index') }}" class="btn btn-default">Quay lại</a>
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
    <script type="text/javascript" src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/admin/warehouse_receipt.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/convertVNese/n2vi.min.js') }}"></script>
    <script type="text/javascript">
        $( document ).ready(function() {
            // Init data
            bsCustomFileInput.init();
        });
    </script>
@endsection
