@extends('layouts.admin')

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
                        <div class="card-header">
                            <div class="card-tools">
                                {!! Form::open(array('route' => 'admin.documents.index', 'method' => 'get')) !!}
                                <div class="input-group">
                                    {!! Form::text('key_word', null, array('class' => 'form-control mr-1 float-right', 'placeholder' => 'Key word')) !!}
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên</th>
{{--                                    <th>File</th>--}}
                                    <th>Người thực hiện</th>
                                    <th>Thời gian</th>
                                    <th>&nbsp</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($datas as $index => $data)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{$data->name}}</td>
{{--                                        <td>--}}
{{--                                            @if(json_decode($data->path, true))--}}
{{--                                                <button--}}
{{--                                                        type="button"--}}
{{--                                                        class="btn btn-success"--}}
{{--                                                        id="document_display"--}}
{{--                                                        data-toggle="modal" data-target="#document_modal"--}}
{{--                                                        content="{{ $data->path }}"--}}
{{--                                                >--}}
{{--                                                    Hiển thị chứng từ đã tồn tại--}}
{{--                                                </button>--}}
{{--                                            @endif--}}
{{--                                        </td>--}}
                                        <td>{{$data->admin ? $data->admin->name : ''}}</td>
                                        <td>{{dateTimeFormat($data->created_at)}}</td>
                                        <td>
                                            @permission('admin.documents.edit')
                                            <a href="{{ route('admin.documents.edit', ['id' => $data->id]) }}"
                                               role="button"
                                               class="btn btn-outline-primary btn-sm"
                                               title="Cập nhật">
                                                <i class="fas fa-solid fa-pen"></i>
                                            </a>
                                            @endpermission
                                            @permission('admin.documents.destroy')
                                            <a href="{{ route('admin.documents.destroy', ['id' => $data->id]) }}"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa phiếu sản xuất này không ?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            @endpermission
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                                {!! $datas->appends(session()->getOldInput())->links() !!}
                            </div>
                        </div>
                        <!-- /.card-body -->
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
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript">
        $( document ).ready(function() {
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
                        html += '</div></div>';
                        eleModal.find('.modal-body').append(html);
                    });
                } else {
                    eleModal.find('.modal-body').append('<p class="text-center">Chưa upload chứng từ.</p>');
                }
            }
        }
    </script>
@endsection
