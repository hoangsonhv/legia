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
              {!! Form::open(array('route' => 'admin.warehouse-export.index', 'method' => 'get')) !!}
              <div class="input-group">
                <input type="text" name="key_word" class="form-control float-right" placeholder="Từ khoá" value="{{old('key_word')}}">
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
                  <th>Mã Phiếu xuất kho</th>
                  <th>Mã CO</th>
                  <th>Tên người nhận hàng</th>
                  <th>Xuất tại kho</th>
                  <th>Địa điểm</th>
                  <th>Tổng tiền thanh toán</th>
                  <th>Người thực hiện</th>
                  <th>&nbsp</th>
                </tr>
              </thead>
              <tbody>
                @foreach($datas as $data)
                  <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->co ? $data->co->code: '' }}</td>
                    <td>{{ $data->code }}</td>
                    <td>{{ $data->recipient_name }}</td>
                    <td>{{ $data->warehouse_at }}</td>
                    <td>{{ $data->address }}</td>
                    <td>{{ number_format($data->total_payment)}}</td>
                    <td>{{ $data->admin ? $data->admin->name : '' }}</td>
                    <td>
                      @permission('admin.warehouse-export.edit')
                        <a href="{{ route('admin.warehouse-export.edit', ['id' => $data->id]) }}"
                           role="button"
                           class="btn btn-outline-primary btn-sm"
                           title="Cập nhật">
                          <i class="fas fa-solid fa-pen"></i>
                        </a>
                      @endpermission
                      @permission('admin.warehouse-export.destroy')
                      <a href="{{ route('admin.warehouse-export.destroy', ['id' => $data->id]) }}"
                         class="btn btn-danger btn-sm"
                         onclick="return confirm('Bạn có chắc chắn muốn xóa phiếu xuất kho này không ?')">
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
