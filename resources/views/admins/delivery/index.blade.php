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
              {!! Form::open(array('route' => 'admin.delivery.index', 'method' => 'get')) !!}
              <div class="input-group">
                {!! Form::select('core_customer_id', $coreCustomers, null, array('class' => 'form-control mr-1 float-right select2')) !!}
                {!! Form::select('status_customer_received', [-1 => 'Tất cả', 0 => 'Chưa nhận hàng', 1 => 'Đã nhận hàng'], null, array('class' => 'form-control mr-1 float-right')) !!}
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
                  <th>Mã CO</th>
                  <th>Mã khách hàng</th>
                  <th>Tên người nhận</th>
                  <th>Ngày giao</th>
                  <th>Ngày nhận dự kiến</th>
                  <th>Đơn vị vận chuyển</th>
                  <th>Phí vận chuyển</th>
                  <th>Đã nhận hàng</th>
                  <th>Người thực hiện</th>
                  <th>&nbsp</th>
                </tr>
              </thead>
              <tbody>
                @foreach($datas as $data)
                  <tr>
                    <td>{{ $data->id }}</td>
                    <td>
                      @if($data->co)
                        <a target="_blank" href={{'/admin/co/edit/'.$data->co_id }}>
                          {{$data->co->code}}
                        </a>
                      @endif
                    </td>
                    <td>
                      @if($data->core_customer)
                        <a target="_blank" href={{'/admin/customer/edit/'.$data->core_customer_id }}>
                          {{$data->core_customer->code}}
                        </a>
                      @endif
                    </td>
                    <td>{{ $data->recipient_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->delivery_date)->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->received_date_expected)->format('Y-m-d') }}</td>
                    <td>{{ $data->shipping_unit }}</td>
                    <td>{{ $data->shipping_fee }}</td>
                    <td>
                      @if($data->status_customer_received)
                        <span class="text-green">YES</span>
                      @else
                        <span class="text-danger">NO</span>
                      @endif
                    </td>
                    <td>{{$data->admin ? $data->admin->name : ''}}</td>
                    <td>
                      @permission('admin.delivery.edit')
                        <a href="{{ route('admin.delivery.edit', ['id' => $data->id]) }}"
                           role="button"
                           class="btn btn-outline-primary btn-sm"
                           title="Cập nhật">
                          <i class="fas fa-solid fa-pen"></i>
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
