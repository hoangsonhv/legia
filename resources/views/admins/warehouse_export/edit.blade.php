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
                        <div class="float-right pb-2">
                            <a href={{ route('admin.pdf.warehouse-export', ['id' => $model->id]) }}>
                                <button class="btn btn-success">
                                    <i class="nav-icon fas fa-file-export" aria-hidden="true"></i>
                                    Phiếu xuất kho
                                </button>
                            </a>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card form-root">
                            {!! Form::model($model, array('route' => ['admin.warehouse-export.update', $model->id], 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
                            {!! Form::hidden('id', null) !!}
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="shipping_unit">Mã phiếu xuất kho<b style="color: red;">(*)</b></label>
                                    {!! Form::text('code', null, array('class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly')) !!}
                                </div>
                                <div class="form-group">
                                    <label for="recipient_name">Họ tên người nhận hàng<b style="color: red;"> (*)</b></label>
                                    {!! Form::text('recipient_name', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                </div>
                                <div class="form-group">
                                    <label for="recipient_address">Địa chỉ (bộ phận)<b style="color: red;"> (*)</b></label>
                                    {!! Form::text('recipient_address', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                </div>
                                <div class="form-group">
                                    <label for="note">Lý do xuất kho</label>
                                    {!! Form::textarea('note', null, array('class' => 'form-control', 'rows' => 1)) !!}
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="warehouse_at">Xuất tại kho (ngăn lô)</label>
                                            {!! Form::text('warehouse_at', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="address">Địa điểm</label>
                                            {!! Form::text('address', null, array('class' => 'form-control')) !!}
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
                                    @if(json_decode($model->document, true))
                                        <button
                                            type="button"
                                            class="btn btn-success"
                                            id="document_display"
                                            data-toggle="modal" data-target="#document_modal"
                                            content="{{ $model->document }}"
                                        >
                                            Hiển thị chứng từ đã tồn tại
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @if($co)
                                <div class="card-body">
                                    {{-- @include('admins.coes.includes.list-products', ['warehouses' => $warehouses, 'collect' => true, 'notAction' => true]) --}}
                                    @include('admins.coes.includes.list-products', ['warehouses' => $warehouses, 'collect' => true])
                                </div>
                                <div class="card-body">
                                    @include('admins.coes.includes.list-warehouses',['warehouses' => $listWarehouse])
                                </div>
                            @endif
                            <div class="card-body">
                                <h3 class="title text-primary">Nội dung</h3>
                                @include('admins.warehouse_export.includes.list-products')
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer text-right">
                                <a href={{ route('admin.pdf.warehouse-export', ['id' => $model->id]) }}>
                                    <button class="btn btn-success">
                                        <i class="nav-icon fas fa-file-export" aria-hidden="true"></i>
                                        Phiếu xuất kho
                                    </button>
                                </a>
                                <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                                
                                <a href="{{ route('admin.warehouse-export.index') }}" class="btn btn-default">Quay lại</a>
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
        <script type="text/javascript" src="{{ asset('js/admin/warehouse_export.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/convertVNese/n2vi.min.js') }}"></script>
        <script type="text/javascript">
            $( document ).ready(function() {
                // Init data
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
                    url: "{{ route('admin.warehouse-export.remove-file') }}",
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
