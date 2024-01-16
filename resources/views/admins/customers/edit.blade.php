@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
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
                        {!! Form::model($model, array('route' => ['admin.customer.update', $model->id], 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
                        {!! Form::hidden('id', null) !!}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="code">Loại<b style="color: red;"> (*)</b></label>
                                        {!! Form::select('type', \App\Models\CoreCustomer::ARR_TYPE ,null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="code">Mã khách hàng<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('code', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="name">Tên khách hàng<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="tax_code">Mã số thuế<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('tax_code', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="tax_code">Người liên hệ<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('recipient', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="address">Địa chỉ<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('address', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="phone">Số điện thoại<b style="color: red;"> (*)</b></label>
                                        {!! Form::number('phone', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        {!! Form::email('email', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-12">
                                    <div class="form-group">
                                        <div class="badge bg-danger">
                                            <label for="email">Tổng chi:</label>
                                        <span class="text-bold ">
                                           {{number_format($totalExpenditure)}}
                                        </span>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-12">
                                    <div class="form-group">
                                        <div class="badge bg-success">
                                            <label for="email">Tổng thu:</label>
                                            <span class="text-bold">
                                                {{number_format($totalRevenue)}}
                                             </span>
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                            <a href="{{ route('admin.customer.index') }}" class="btn btn-default">Quay lại</a>
                        </div>
                    {!! Form::close() !!}
                    <!-- /.card-body -->
                    </div>

                    @if($model->type == \App\Models\CoreCustomer::TYPE_CUSTOMER)
                        {{--Lịch sử chào giá--}}
                        <div class="col-12">
                            <h3 class="mt-2 mb-2">Lịch sử chào giá</h3>
                        </div>
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Người thực hiện</th>
                                        <th>Mã khách hàng</th>
                                        <th>Tên khách hàng</th>
                                        <th>Số báo giá</th>
                                        <th>Tổng tiền</th>
                                        <th>Lợi nhuận</th>
                                        <th>Trạng thái</th>
                                        <th>&nbsp</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($co_tmps as $key => $co)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                @if($co->admin)
                                                    {{ $co->admin->name }}
                                                @endif
                                            </td>
                                            <td>{{ $co->core_customer ? $co->core_customer->code : '' }}</td>
                                            <td>{{ $co->customer ? $co->customer->ten : '' }}</td>
                                            <td>{{ $co->so_bao_gia }}</td>
                                            <td>
                                        <span class="badge bg-warning">
                                            {{ number_format($co->tong_gia + $co->vat) }}
                                        </span>
                                            </td>
                                            <td>
                                        <span class="badge bg-success">
                                            {{ number_format(($co->tong_gia + $co->vat) * 0.3) }}
                                        </span>
                                            </td>
                                            <td>
                                                @if($co->tong_gia < $limitApprovalCg)
                                                    <span class="badge bg-success">
                                                Không cần xét duyệt
                                            </span>
                                                @elseif($co->status == \App\Enums\ProcessStatus::Approved)
                                                    <span class="badge bg-success">
                                                {{ \App\Enums\ProcessStatus::all()[$co->status] }}
                                            </span>
                                                @elseif($co->status == \App\Enums\ProcessStatus::Unapproved)
                                                    <span class="badge bg-danger">
                                                {{ \App\Enums\ProcessStatus::all()[$co->status] }}
                                            </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                {{ \App\Enums\ProcessStatus::all()[$co->status] }}
                                            </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a target="_blank"
                                                   href="{{ route('admin.co-tmp.edit', ['id' => $co->id]) }}"
                                                   role="button"
                                                   class="btn btn-outline-primary btn-sm" title="Xem chi tiết">
                                                    <i class="fas fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {!! $coes->appends(session()->getOldInput())->links() !!}
                                </div>
                            </div>
                        </div>
                        {{-- Hết Lịch sử chào giá--}}

                        {{--Lịch sử mua hàng--}}
                        <div class="col-12">
                            <h3 class="mt-2 mb-2">Lịch sử mua hàng</h3>
                        </div>
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                    <tr>
                                        <th>Mã CO</th>
                                        <th>Mã KH</th>
                                        <th>Mô tả</th>
                                        <th>Người thực hiện</th>
                                        <th>Tổng tiền</th>
                                        <th>Đã chi</th>
                                        <th>Đã thu</th>
                                        <th>Công nợ</th>
                                        <th>Trạng thái</th>
                                        <th>Tình trạng CO</th>
                                        <th>Ngày tạo</th>
                                        <th>&nbsp</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($coes as $co)
                                        <tr>
                                            <td>{{ $co->code }}</td>
                                            <td>{{ $co->core_customer ? $co->core_customer->code : '' }}</td>
                                            <td>{{ Str::limit($co->description, 30) }}</td>
                                            <td>
                                                @if($co->admin)
                                                    {{ $co->admin->name }}
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $sumPrice = $co->tong_gia + $co->vat;
                                                @endphp
                                                <span class="badge bg-warning">
                                                {{ number_format($sumPrice) }}
                                            </span>
                                            </td>
                                            <td id="tong_chi_table">
                                                @php
                                                    $sumPayment = 0;
                                                    if($co->payment->count()) {
                                                      foreach($co->payment as $val) {
                                                        if($val->status == \App\Enums\ProcessStatus::Approved) {
                                                          $sumPayment += $val->money_total;
                                                        }
                                                      }
                                                    }
                                                    if($co->delivery()->count()) {
                                                      $sumPayment += $co->delivery->first()->shipping_fee;
                                                    }
                                                @endphp
                                                <span class="badge bg-danger">{{ number_format($sumPayment) }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $sumReceipt = 0;
                                                    if($co->receipt->count()) {
                                                      foreach($co->receipt as $val) {
                                                        if($val->status == \App\Enums\ProcessStatus::Approved) {
                                                          $sumReceipt += $val->money_total;
                                                        }
                                                      }
                                                    }
                                                @endphp
                                                <span class="badge bg-success">{{ number_format($sumReceipt) }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $CN = $sumPrice - $sumReceipt;
                                                @endphp
                                                @if ($CN <= 0)
                                                    <span class="badge bg-success">
                                                    Đã thu đủ
                                                </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                    Thiếu {{ number_format($CN) }}
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($co->status == \App\Enums\ProcessStatus::Approved)
                                                    <span class="badge bg-success">
                                                    {{ \App\Enums\ProcessStatus::all()[$co->status] }}
                                                </span>
                                                @elseif($co->status == \App\Enums\ProcessStatus::Unapproved)
                                                    <span class="badge bg-danger">
                                                    {{ \App\Enums\ProcessStatus::all()[$co->status] }}
                                                </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                    {{ \App\Enums\ProcessStatus::all()[$co->status] }}
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($co->confirm_done)
                                                    <span class="badge bg-success">CO đã xong</span>
                                                @else
                                                    <span class="badge bg-danger">CO chưa xong</span>
                                                @endif
                                            </td>
                                            <td>{{ $co->created_at }}</td>
                                            <td>
                                                <a target="_blank"
                                                   href="{{ route('admin.co.edit', ['id' => $co->id]) }}"
                                                   role="button"
                                                   class="btn btn-outline-primary btn-sm" title="Xem chi tiết">
                                                    <i class="fas fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {!! $coes->appends(session()->getOldInput())->links() !!}
                                </div>
                            </div>
                        </div>
                        {{-- Hết Lịch sử mua hàng--}}

                        {{-- Lịch sử giao nhận--}}
                        <div class="col-12">
                            <h3 class="mt-2 mb-2">Lịch sử giao nhận</h3>
                        </div>
                        <div class="card">
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
                                        <th>&nbsp</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($deliveries as $deli)
                                        <tr>
                                            <td>{{ $deli->id }}</td>
                                            <td>
                                                @if($deli->co)
                                                    <a target="_blank" href={{'/admin/co/edit/'.$deli->co_id }}>
                                                        {{$deli->co->code}}
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                {{$deli->core_customer->code}}
                                            </td>
                                            <td>{{ $deli->recipient_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($deli->delivery_date)->format('Y-m-d') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($deli->received_date_expected)->format('Y-m-d') }}</td>
                                            <td>{{ $deli->shipping_unit }}</td>
                                            <td>{{ $deli->shipping_fee }}</td>
                                            <td>
                                                @if($deli->status_customer_received)
                                                    <span class="text-green">YES</span>
                                                @else
                                                    <span class="text-danger">NO</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a target="_blank"
                                                   href="{{ route('admin.delivery.edit', ['id' => $deli->id]) }}"
                                                   role="button"
                                                   class="btn btn-outline-primary btn-sm" title="Xem chi tiết">
                                                    <i class="fas fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- Hết Lịch sử giao nhận--}}
                    @endif
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('[name="tmp_transaction_amount"]').keyup(function () {
                var data = formatCurrent(this.value);
                $(this).val(data.format);
                $('[name="transaction_amount"]').val(data.original);
            });
        });
    </script>
@endsection
