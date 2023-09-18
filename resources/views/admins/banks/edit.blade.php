@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
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
                        {!! Form::model($bank, array('route' => ['admin.bank.update', $bank->id], 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
                        {!! Form::hidden('id', null) !!}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name_bank">Loại tài chính<b style="color: red;"> (*)</b></label>
                                {!! Form::select('type', $typeBanks, null, array('class' => 'form-control', 'required' => 'required')) !!}
                            </div>
                            <div class="form-group">
                                <label for="name_bank">Tên ngân hàng / tiền mặt<b style="color: red;"> (*)</b></label>
                                {!! Form::text('name_bank', null, array('class' => 'form-control', 'required' => 'required')) !!}
                            </div>
                            <div class="form-group">
                                <label for="account_name">Tên tài khoản / tiền mặt<b style="color: red;"> (*)</b></label>
                                {!! Form::text('account_name', null, array('class' => 'form-control', 'required' => 'required')) !!}
                            </div>
                            <div class="form-group">
                                <label for="account_number">Số tài khoản / tiền mặt</label>
                                {!! Form::text('account_number', null, array('class' => 'form-control')) !!}
                            </div>
                            <div class="form-group">
                                <label for="account_number">Số dư tài khoản</label>
                                {!! Form::text('account_balance', number_format($bank->account_balance), array('class' => 'form-control', 'required' => 'required',
                                  'disabled' => 'disabled',)) !!}
                            </div>
                            <div class="form-group">
                                <label for="account_number">Số dư đầu kì</label>
                                {!! Form::text('opening_balance', number_format($bank->opening_balance), array('class' => 'form-control', 'required' => 'required',
                                  'disabled' => 'disabled',)) !!}
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                            <a href="{{ route('admin.bank.index') }}" class="btn btn-default">Quay lại</a>
                        </div>
                    {!! Form::close() !!}
                    <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                @permission('admin.bank.transaction')
                <div class="col-12">
                    <h3>Lịch sử giao dịch</h3>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                {!! Form::open(array('route' => ['admin.bank.edit', $bank->id], 'method' => 'get')) !!}
                                <div class="input-group">
                                    <input type="text" name="from_date" class="form-control float-right"
                                           placeholder="Từ ngày" value="{{old('from_date')}}">
                                    <input type="text" name="to_date" class="form-control float-right mr-1"
                                           placeholder="Đến ngày" value="{{old('to_date')}}">
                                    {!! Form::select('type', array_merge(['all' => 'Tất cả'], $types), old('type'), array('class' => 'form-control float-right')) !!}
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <a href="{{ route('admin.bank.edit', $bank->id) }}" class="btn btn-default"
                                           title="Hiển thị all">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                    </div>
                                    <button type="button" class="btn btn-success ml-2" data-toggle="modal"
                                            data-target="#transaction">
                                        Nạp / Rút tiền
                                    </button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>Loại giao dịch</th>
                                    <th>Thời gian giao dịch</th>
                                    <th>Số tiền giao dịch</th>
                                    <th>Số dư tài khoản</th>
                                    <th>Số dư đầu kì</th>
                                    <th>Ghi chú</th>
                                    <th>CO</th>
                                    <th>Phiếu chi</th>
                                    <th>Phiếu thu</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($bankHistoryTransactions->count())
                                    @foreach($bankHistoryTransactions as $bankHistoryTransaction)
                                        <div class="d-none">
                                            {{ $isWithdraw = $bankHistoryTransaction->type == \App\Enums\TransactionType::Withdraw }}
                                            {{ $isDeposit = $bankHistoryTransaction->type == \App\Enums\TransactionType::Deposit }}
                                        </div>
                                        <tr>
                                            <td>{{ $types[$bankHistoryTransaction->type] }}</td>
                                            <td>{{ $bankHistoryTransaction->created_at }}</td>
                                            <td class="{{ $isWithdraw ? 'text-danger': '' }} {{ $isDeposit ? 'text-green': '' }}">
                                                {{ $isWithdraw ? '-': '' }} {{ $isDeposit ? '+': '' }}
                                                {{ number_format($bankHistoryTransaction->transaction_amount, 0) }}
                                            </td>
                                            <td>{{ number_format($bankHistoryTransaction->current_amount, 0) }}</td>
                                            <td>{{ number_format($bankHistoryTransaction->current_opening_balance, 0) }}</td>
                                            <td>{{ $bankHistoryTransaction->note }}</td>
                                            <td>
                                                @if($bankHistoryTransaction->payment)
                                                    <a target="_blank"
                                                       href="/admin/co/edit/{{$bankHistoryTransaction->payment->co_id}}">
                                                        <button class="btn btn-primary btn-sm">Xem CO</button>
                                                    </a>
                                                @elseif($bankHistoryTransaction->receipt)
                                                    <a target="_blank"
                                                       href="/admin/co/edit/{{$bankHistoryTransaction->receipt->co_id}}">
                                                        <button class="btn btn-primary btn-sm">Xem CO</button>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                @if($bankHistoryTransaction->payment_id > 0)
                                                    <a target="_blank"
                                                       href="/admin/payment/edit/{{$bankHistoryTransaction->payment_id}}">
                                                        <button class="btn btn-primary btn-sm">Xem Phiếu chi</button>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                @if($bankHistoryTransaction->receipt_id > 0)
                                                    <a target="_blank"
                                                       href="/admin/receipt/edit/{{$bankHistoryTransaction->receipt_id}}">
                                                        <button class="btn btn-primary btn-sm">Xem Phiếu thu</button>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">Chưa có bất kì giao dịch nào ở tài khoản
                                            này.
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                                {!! $bankHistoryTransactions->appends(session()->getOldInput())->links() !!}
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="transaction">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                {!! Form::open(array('route' => ['admin.bank.transaction', $bank->id], 'method' => 'post')) !!}
                                {!! Form::hidden('id', $bank->id) !!}
                                {!! Form::hidden('name_bank', $bank->name_bank) !!}
                                {!! Form::hidden('account_name', $bank->account_name) !!}
                                {!! Form::hidden('account_number', $bank->account_number) !!}
                                <div class="modal-header bg-success">
                                    <h4 class="modal-title">Nạp / Rút tiền</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="type">Loại giao dịch<b style="color: red;"> (*)</b></label>
                                        {!! Form::select('type', $types, null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                    <div class="form-group">
                                        <label for="transaction_amount">Số tiền giao dịch<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('tmp_transaction_amount', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                        {!! Form::hidden('transaction_amount', null) !!}
                                    </div>
                                    <div class="form-group">
                                        <label for="note">Ghi chú</label>
                                        {!! Form::text('note', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn bg-success">Lưu giao dịch</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                </div>
                @endpermission
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('[name="tmp_transaction_amount"]').keyup(function () {
                var data = formatCurrent(this.value);
                $(this).val(data.format);
                $('[name="transaction_amount"]').val(data.original);
            });

            $('[name="from_date"]').daterangepicker({
                autoUpdateInput: false,
                singleDatePicker: true,
                // maxDate: moment(),
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            $('[name="from_date"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });
            $('[name="from_date"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

            $('[name="to_date"]').daterangepicker({
                autoUpdateInput: false,
                singleDatePicker: true,
                // maxDate: moment(),
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            $('[name="to_date"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });
            $('[name="to_date"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        });
    </script>
@endsection
