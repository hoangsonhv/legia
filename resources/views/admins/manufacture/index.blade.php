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
                                {!! Form::open(array('route' => 'admin.manufacture.index', 'method' => 'get')) !!}
                                <div class="input-group">
                                    {!! Form::select('co_id', $coes, old('co_id'), array('class' => 'form-control mr-1 float-right')) !!}
                                    {!! Form::select('material_type', $materialTypes, null, array('class' => 'form-control mr-1 float-right')) !!}
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
                                    <th>Mã CO</th>
                                    <th>Mô tả</th>
                                    <th>Loại</th>
                                    <th>Tình trạng</th>
                                    <th>Ngày tạo</th>
                                    <th>Người thực hiện</th>
                                    <th>&nbsp</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($datas as $index => $data)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($data->co)
                                                <a target="_blank" href={{'/admin/co/edit/'.$data->co_id }}>
                                                    {{$data->co->code}}
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ $data->note }}</td>
                                        <td>
                                            @if($data->material_type == \App\Models\Manufacture::MATERIAL_TYPE_METAL)
                                                <span class="badge bg-gray">Kim loại</span>
                                            @else
                                                <span class="badge bg-info">Phi kim loại</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($data->is_completed == \App\Models\Manufacture::IS_COMPLETED)
                                                <span class="badge bg-success">Đã xong</span>
                                            @elseif($data->is_completed == \App\Models\Manufacture::PROCESSING)
                                                <span class="badge bg-primary">Tiến hành sản xuất</span>
                                            @else
                                                <span class="badge bg-warning">Đang chờ</span>
                                            @endif
                                        </td>
                                        <td>{{$data->created_at}}</td>
                                        <td>{{$data->admin ? $data->admin->name : ''}}</td>
                                        <td>
                                            @permission('admin.manufacture.edit')
                                                <a href="{{ route('admin.manufacture.edit', ['id' => $data->id]) }}"
                                                   role="button"
                                                   class="btn btn-outline-primary btn-sm"
                                                   title="Cập nhật">
                                                    <i class="fas fa-solid fa-pen"></i>
                                                </a>
                                            @endpermission
                                            @permission('admin.manufacture.destroy')
                                            <a href="{{ route('admin.manufacture.destroy', ['id' => $data->id]) }}"
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
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
