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
                        {!! Form::model($model, array('route' => ['admin.documents.update', $model->id], 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
                        {!! Form::hidden('id', $model->id) !!}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Tên<b style="color: red;"> (*)</b></label>
                                {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'required')) !!}
                            </div>
                            <div class="form-group">
                                <label for="code">Mô tả</label>
                                {!! Form::textarea('note', null, array('class' => 'form-control', 'rows' => 3, 'placehodel')) !!}
                            </div>
                            <div class="form-group">
                                <label>Chứng từ đi kèm</label>
                                <div class="input-group block-file">
                                    @if(!json_decode($model->path, true))
                                        <div class="custom-file">
                                            <input type="file" name="file[]" class="custom-file-input"  />
                                            <label class="custom-file-label">Chọn file</label>
                                        </div>
                                    @endif
                                </div>
                                @if(json_decode($model->path, true))
                                    <button
                                            type="button"
                                            class="btn btn-success"
                                            id="document_display"
                                            data-toggle="modal" data-target="#document_modal"
                                            content="{{ $model->path }}"
                                    >
                                        Hiển thị chứng từ đã tồn tại
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                            <a href="{{ route('admin.documents.index') }}" class="btn btn-default">Quay lại</a>
                        </div>
                    </div>
                {!! Form::close() !!}
                <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <div class="modal fade" id="document_modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h4 class="modal-title">Chứng từ đi kèm</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                    <!-- /.card-body -->
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
            bsCustomFileInput.init();
            getHtmlFile('document');
        });

        function getHtmlFile(field) {
            var contentDocument = $('#'+field+'_display').attr('content');
            if (contentDocument) {
                var data     = JSON.parse(contentDocument);
                var eleModal = $('#'+field+'_modal');
                if (data.length) {
                    $.each(data, function( index, value ) {
                        var html = '<div class="data-file">' + checkFile(value) + '<div class="mt-2">';
                        html += '<button type="button" class="btn btn-danger form-control" onclick="removeFile(this)" data-path="'+value.path+'">Xoá file</button>';
                        html += '</div></div>';
                        eleModal.find('.modal-body').append(html);
                    });
                } else {
                    eleModal.find('.modal-body').append('<p class="text-center">Chưa upload chứng từ.</p>');
                }
            }
        }

        function removeFile(_this) {
            $.ajax({
                method: "POST",
                url: "{{ route('admin.documents.remove-file') }}",
                data: { id: $('[name=id]').val(), path: $(_this).attr('data-path') }
            })
                .done(function( data ) {
                    if (data.success) {
                        $(_this).parents('.data-file:first').remove();
                        alert('Xoá file thành công.');
                    } else {
                        alert('Xoá file thất bại.');
                    }
                });
        }
    </script>
@endsection