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
              {!! Form::open(array('route' => 'admin.customer.index', 'method' => 'get')) !!}
              <div class="input-group">
                <input type="text" name="key_word" class="form-control float-right" placeholder="Mã, Tên, Mã số thuế" value="{{old('key_word')}}">
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
                  <th>Loại</th>
                  <th>Mã khách hàng</th>
                  <th>Tên khách hàng</th>
                  <th>Mã số thuế</th>
                  <th>Địa chỉ</th>
                  <th>Email</th>
                  <th>Số điện thoại</th>
                  <th>Người thực hiện</th>
                  <th>&nbsp</th>
                </tr>
              </thead>
              <tbody>
                @foreach($datas as $data)
                  <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ \App\Models\CoreCustomer::ARR_TYPE[$data->type] }}</td>
                    <td>{{ $data->code }}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->tax_code }}</td>
                    <td>{{ $data->address }}</td>
                    <td>{{ $data->email }}</td>
                    <td>{{ $data->phone }}</td>
                    <td>{{ $data->admin ? $data->admin->name : '' }}</td>
                    <td>
                      @permission('admin.customer.edit')
                        <a href="{{ route('admin.customer.edit', ['id' => $data->id]) }}"
                           role="button"
                           class="btn btn-outline-primary btn-sm"
                           title="Cập nhật">
                          <i class="fas fa-solid fa-pen"></i>
                        </a>
                      @endpermission
                      @permission('admin.customer.destroy')
                        <a href="{{ route('admin.customer.destroy', ['id' => $data->id]) }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này không ?')">
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
