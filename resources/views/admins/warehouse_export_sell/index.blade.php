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
              {!! Form::open(array('route' => 'admin.warehouse-export-sell.index', 'method' => 'get')) !!}
              <div class="input-group">
                {!! Form::select('core_customer_id', $coreCustomers, null, array('class' => 'form-control mr-1 float-right select2')) !!}
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
                  <th>Mã KH</th>
                  <th>Người mua</th>
                  <th>Số điện thoại</th>
                  <th>Mã số thuế</th>
                  <th>Tổng tiền thanh toán</th>
                  <th>Đã nhận đủ hàng</th>
                  <th>Người thực hiện</th>
                  <th>&nbsp</th>
                </tr>
              </thead>
              <tbody>
                @foreach($datas as $data)
                  <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->code }}</td>
                    <td>{{ $data->core_customer ? $data->core_customer->code : '' }}</td>
                    <td>{{ $data->buyer_name }}</td>
                    <td>{{ $data->buyer_phone }}</td>
                    <td>{{ $data->buyer_tax_code }}</td>
                    <td>{{ number_format($data->total_payment)}}</td>
                    <td>
                      @if($data->confirm_enough)
                        <span class="text-green">YES</span>
                      @else
                        <span class="text-danger">NO</span>
                      @endif
                    </td>
                    <td>{{ $data->admin ? $data->admin->name : '' }}</td>
                    <td>
                      @permission('admin.warehouse-export-sell.edit')
                        <a href="{{ route('admin.warehouse-export-sell.edit', ['id' => $data->id]) }}"
                           role="button"
                           class="btn btn-outline-primary btn-sm"
                           title="Cập nhật">
                          <i class="fas fa-solid fa-pen"></i>
                        </a>
                      @endpermission
                      @permission('admin.warehouse-export-sell.destroy')
                      <a href="{{ route('admin.warehouse-export-sell.destroy', ['id' => $data->id]) }}"
                         class="btn btn-danger btn-sm"
                         onclick="return confirm('Bạn có chắc chắn muốn xóa phiếu xuất kho bán hàng này không ?')">
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
