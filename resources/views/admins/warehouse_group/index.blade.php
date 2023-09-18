@extends('layouts.admin')

@section('content')

    @include('admins.breadcrumb')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-right mb-2">
                    @permission('admin.warehouse-group.create')
                    <a href="{{route('admin.warehouse-group.create')}}">
                        <button class="btn btn-success">
                            <i class="nav-icon fas fa-plus" aria-hidden="true"></i>
                            Thêm
                        </button>
                    </a>
                    @endpermission
                </div>
                <div class="col-12">
                    @include('admins.message')
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                {!! Form::open(array('route' => 'admin.warehouse-group.index', 'method' => 'get')) !!}
                                <div class="input-group">
                                    <input type="text" name="key_word" class="form-control float-right"
                                           placeholder="Từ khoá" value="{{old('key_word')}}">
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
                                    <th>ID</th>
                                    <th>Mã nhóm</th>
                                    <th>Tên nhóm</th>
                                    <th>Sản xuất / Thương mại</th>
                                    <th>Kim loại / Phi kim loại</th>
                                    <th>Người thực hiện</th>
                                    <th>&nbsp</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($datas as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->code }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ \App\Models\WarehouseGroup::ARR_MANUFACTURE_TYPE[$data->manufacture_type] }}</td>
                                        <td>{{ \App\Models\WarehouseGroup::ARR_TYPE[$data->type] }}</td>
                                        <td>
                                            @permission('admin.warehouse-group.edit')
                                            <a href="{{ route('admin.warehouse-group.edit', ['id' => $data->id]) }}"
                                               role="button"
                                               class="btn btn-outline-primary btn-sm"
                                               title="Cập nhật">
                                                <i class="fas fa-solid fa-pen"></i>
                                            </a>
                                            @endpermission
                                            @permission('admin.warehouse-group.destroy')
                                            <a href="{{ route('admin.warehouse-group.destroy', ['id' => $data->id]) }}"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa nhóm hàng hóa này không ?')">
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