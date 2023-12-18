@extends('layouts.admin')
@section('css')
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
                        {!! Form::model($model, array('route' => ['admin.bank-loans.update', $model->id], 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name_bank">Ngân hàng<b style="color: red;"> (*)</b></label>
                                {!! Form::select('bank_id', $banks, null, array('class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly')) !!}
                            </div>
                            <div class="form-group">
                                <label for="name_bank">Nội dung vay<b style="color: red;"> (*)</b></label>
                                {!! Form::text('lead', null, array('class' => 'form-control', 'required' => 'required')) !!}
                            </div>
                            <div class="form-group">
                                <label for="name_bank">Nội dung chi tiết</label>
                                {!! Form::text('content', null, array('class' => 'form-control')) !!}
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="date">Ngày vay<b style="color: red;"> (*)</b></label>
                                        <div class="input-group" id="date" data-target-input="nearest">
                                            {!! Form::text('date', $model ? $model->date : date('Y-m-d'), array('class' => 'form-control datetimepicker-input',
                                              'data-target' => '#date', 'required' => 'required')) !!}
                                            <div class="input-group-append" data-target="#date"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="date">Ngày đáo hạn<b style="color: red;"> (*)</b></label>
                                        <div class="input-group" id="date_due" data-target-input="nearest">
                                            {!! Form::text('date_due', null, array('class' => 'form-control datetimepicker-input',
                                              'data-target' => '#date_due', 'required' => 'required')) !!}
                                            <div class="input-group-append" data-target="#date_due"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="date">Ngày trả hàng tháng<b style="color: red;"> (*)</b></label>
                                        <div class="input-group">
                                            {!! Form::number('date_pay', 15, array('class' => 'form-control', 'min' => 1, 'max' => 31,
                                              'required' => 'required')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-xl-8">
                                    <div class="form-group">
                                        <label for="name_bank">Số tiền vay<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('tmp_amount_money', number_format($model->amount_money), array('class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly')) !!}
                                        {!! Form::hidden('amount_money', null) !!}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-4">
                                    <div class="form-group">
                                        <label for="name_bank">Lãi (%)<b style="color: red;"> (*)</b></label>
                                        {!! Form::number('profit_amount', $model->profit_amount, array('class' => 'form-control', 'step' => '0.1', 'required' => 'required', 'readonly' => 'readonly')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-xl-12">
                                    <div class="form-group">
                                        <label for="name_bank">Số dư nợ vay<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('tmp_outstanding_balance', number_format($model->outstanding_balance), array('class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly')) !!}
                                        {!! Form::hidden('outstanding_balance', null) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name_bank">Ghi chú</label>
                                        {!! Form::textarea('note', null, array('class' => 'form-control', 'rows' => 3)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                            <a href="{{ route('admin.bank-loans.index') }}" class="btn btn-default">Quay lại</a>
                        </div>
                    {!! Form::close() !!}
                    <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    @permission('admin.bank-loans.insert-detail')
                    <div class="col-12">
                        <h3>Chi tiết trả vay nợ</h3>
                        <div class="card">
                            <div class="card-header">
                                <div class="card-tools">
                                    {!! Form::open(array('route' => ['admin.bank-loans.edit', $model->id], 'method' => 'get')) !!}
                                    <div class="input-group">
                                        <input type="text" name="from_date" class="form-control float-right"
                                               placeholder="Từ ngày" value="{{old('from_date')}}">
                                        <input type="text" name="to_date" class="form-control float-right mr-1"
                                               placeholder="Đến ngày" value="{{old('to_date')}}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ route('admin.bank-loans.edit', $model->id) }}" class="btn btn-default"
                                               title="Hiển thị all">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                        </div>
                                        <button type="button" class="btn btn-success ml-2" data-toggle="modal"
                                                data-target="#bank_loan_detail_modal">
                                            Thanh toán
                                        </button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                    <tr>
                                        <th>Số tiền giao dịch</th>
                                        <th>Số tiền lãi</th>
                                        <th>Tổng cộng</th>
                                        <th>Ghi chú</th>
                                        <th>Ngày tạo</th>
                                        <th>Người thực hiện</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @if($bankLoanDetails->count())
                                            @foreach($bankLoanDetails as $detail)
                                                <tr>
                                                    <td>{{number_format($detail->debit_amount)}}</td>
                                                    <td>{{number_format($detail->profit_amount)}}</td>
                                                    <td>{{number_format($detail->total_amount)}}</td>
                                                    <td>{{$detail->note}}</td>
                                                    <td>{{dateTimeFormat($detail->created_at)}}</td>
                                                    <td>{{$detail->admin ? $detail->admin->name : ''}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                    {!! $bankLoanDetails->appends(session()->getOldInput())->links() !!}
                                </div>
                                <div class="d-flex justify-content-center">
                            </div>
                        </div>

                        <div class="modal fade" id="bank_loan_detail_modal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    {!! Form::open(array('route' => ['admin.bank-loans.insert-detail', $model->id], 'method' => 'post')) !!}
                                    {!! Form::hidden('bank_loan_id', $model->id) !!}
                                    <div class="modal-header bg-success">
                                        <h4 class="modal-title">Thanh toán vay nợ</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="debit_amount">Số tiền giao dịch<b style="color: red;"> (*)</b></label>
                                            {!! Form::text('tmp_debit_amount', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                            {!! Form::hidden('debit_amount', null, array('id' => 'debit_amount')) !!}
                                        </div>
                                        <div class="form-group">
                                            <label for="debit_amount">Số tiền lãi</label>
                                            {!! Form::text('tmp_profit_amount', 0, array('class' => 'form-control', 'id' => 'tmp_profit_amount_detail')) !!}
                                            {!! Form::hidden('profit_amount', 0, array('id' => 'profit_amount_detail')) !!}
                                        </div>
                                        <div class="form-group">
                                            <label for="total_amount">Tổng cộng<b style="color: red;"> (*)</b></label>
                                            {!! Form::text('tmp_total_amount', null, array('class' => 'form-control', 'required' => 'required', 'id' => 'tmp_total_amount')) !!}
                                            {!! Form::hidden('total_amount', null, array('id' => 'total_amount')) !!}
                                        </div>
                                        <div class="form-group">
                                            <label for="note">Ghi chú</label>
                                            {!! Form::text('note', null, array('class' => 'form-control', 'rows' => 2)) !!}
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
                    </div>
                    @endpermission
                    </div>
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
            $('#date').datetimepicker({
                format: 'YYYY-MM-DD',
                icons: {time: 'far fa-clock'}
            });
            $('#date_due').datetimepicker({
                format: 'YYYY-MM-DD',
                icons: {time: 'far fa-clock'}
            });
            $('[name="tmp_amount_money"]').keyup(function () {
                var data = formatCurrent(this.value);
                $(this).val(data.format);
                $('[name="amount_money"]').val(data.original);
            });

            $('[name="tmp_debit_amount"]').keyup(function () {
                var data = formatCurrent(this.value);
                $(this).val(data.format);
                $('[name="debit_amount"]').val(data.original);

                let valueProfitAmount = $('#profit_amount_detail').val();
                let total = formatCurrent(String(Number(valueProfitAmount) + Number(data.original)))
                $('#total_amount').val(total.original)
                $('#tmp_total_amount').val(total.format)
            });
            $('#tmp_profit_amount_detail').keyup(function () {
                var data = formatCurrent(this.value);
                $(this).val(data.format);
                $('#profit_amount_detail').val(data.original);

                let valueDebitAmount = $('[name="debit_amount"]').val();
                let total = formatCurrent(String(Number(valueDebitAmount) + Number(data.original)))
                $('#total_amount').val(total.original)
                $('#tmp_total_amount').val(total.format)
            });
            $('[name="tmp_total_amount"]').keyup(function () {
                var data = formatCurrent(this.value);
                $(this).val(data.format);
                $('[name="total_amount"]').val(data.original);
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