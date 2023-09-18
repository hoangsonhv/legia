@extends('layouts.admin')
@section('css')
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
                    <div class="card">
                        {!! Form::open(array('route' => 'admin.documents.store', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Tên<b style="color: red;"> (*)</b></label>
                                {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'required')) !!}
                            </div>
                            <div class="form-group">
                                <label for="note">Mô tả</label>
                                {!! Form::textarea('note', null, array('class' => 'form-control', 'rows' => 3)) !!}
                            </div>
                            <div class="form-group">
                                <label>Chứng từ đi kèm</label>
                                <div class="input-group block-file">
                                    <div class="custom-file">
                                        <input type="file" name="file[]" class="custom-file-input" />
                                        <label class="custom-file-label">Chọn file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                            <a href="{{ route('admin.documents.index') }}" class="btn btn-default">Quay lại</a>
                        </div>
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
    <script type="text/javascript" src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script type="text/javascript">
        $( document ).ready(function() {
            // Init data
            bsCustomFileInput.init();
        });
    </script>
@endsection
