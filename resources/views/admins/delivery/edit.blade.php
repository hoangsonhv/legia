@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <style type="text/css">
        .card-header .title {
            text-align: center;
            font-weight: bold;
            color: #03b1fc;
        }
        .block-file {
            margin-bottom: 10px;
        }
        hr.hor {
            color: red;
            border: 3px solid #007bff;
        }

        .time-line {
            margin: 0 auto;
            display: flex;
            justify-content: left;
        }
        .time-line .begin-line {
            position: relative;
            background-color: #bebebe;
            height: 3px;
            border-radius: 4px;
            margin: 4em -1em 1em 0;
            font-weight: bold;
        }
        .time-line .events {
            position: relative;
            background-color: #bebebe;
            height: 3px;
            width: 80%;
            border-radius: 4px;
            margin: 4em 0 1em 0;
        }
        .time-line .events ol {
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .time-line .events ul {
            list-style: none;
        }
        .time-line .events ul li {
            display: inline-block;
            width: 33%;
            margin: 0;
            padding: 0;
        }
        .time-line .events ul li span {
            position: relative;
            top: -52px;
            padding-bottom: 24px;
            font-weight: bold;
        }
        .time-line .events ul li span:after {
            content: '';
            position: absolute;
            bottom: -32px;
            left: 50%;
            right: auto;
            height: 50px;
            width: 50px;
            border-radius: 50%;
            border: 3px solid #606060;
            background-color: #fff;
            transition: 0.3s ease;
            transform: translateX(-50%);
        }
        .time-line .events ul li span.step1.selected:after {
            background-color: #28a745;
            border-color: #28a745;
        }
        .time-line .events ul li span.step2.selected:after {
            background-color: #e6e31b;
            border-color: #e6e31b;
        }
        .time-line .events ul li span.step3.selected:after {
            background-color: #ef2d1f;
            border-color: #ef2d1f;
        }
    </style>
@endsection

@section('content')

    @include('admins.breadcrumb')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @if($coModel->start_timeline)
                            <div class="card-body">
                                @php
                                    $dataTime   = \Carbon\Carbon::parse($coModel->start_timeline);
                                    $startTime  = \Carbon\Carbon::parse($dataTime->format('Y-m-d 00:00:00'));
                                    $curentTime = \Carbon\Carbon::parse(\Carbon\Carbon::now()->format('Y-m-d 00:00:00'));
                                    $numberTime = $curentTime->diffInDays($startTime);
                                @endphp
                                @if($coModel->enough_money)
                                    <h5 class="text-primary">
                                        Thời gian hoàn tất thu tiền: {{ \Carbon\Carbon::parse($coModel->enough_money)->format('Y-m-d H:i:s') }}
                                    </h5>
                                @else
                                    <h5 class="{{ ($numberTime <= 15) ? 'text-success' : (($numberTime <= 30) ? 'text-warning' : 'text-danger') }}">
                                        Thời gian thu tiền đã trôi qua {{ $numberTime }} ngày
                                    </h5>
                                @endif
                                <div class="time-line">
                                    <div class="begin-line">
                                        {{ $dataTime->format('Y-m-d') }}
                                    </div>
                                    <div class="events">
                                        <ol>
                                            <ul>
                                                <li>
                            <span class="text-success step1{{ ($numberTime >= 15) ? ' selected' : '' }}">15 ngày</a>
                                                </li>
                                                <li>
                                                    <span class="text-warning step2{{ ($numberTime >= 30) ? ' selected' : '' }}">30 ngày</span>
                                                </li>
                                                <li>
                                                    <span class="text-danger step3{{ ($numberTime >= 45) ? ' selected' : '' }}"> 45 ngày</span>
                                                </li>
                                            </ul>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @include('admins.message')
                </div>
                <div class="col-12">
                    <div class="card">
                        {!! Form::model($model, array('route' => ['admin.delivery.update', $model->id], 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
                        {!! Form::hidden('id', null) !!}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="code">Mã CO<b style="color: red;"> (*)</b></label>
                                        {!! Form::select('co_id', $co, null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="code">Mã khách hàng<b style="color: red;"> (*)</b></label>
                                        {!! Form::select('core_customer_id', $coreCustomers, null, array('class' => 'form-control select2', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="name">Họ tên người nhận<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('recipient_name', null,
                                            array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="tax_code">SĐT người nhận<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('recipient_phone', null,
                                            array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="shipping_unit">Đơn vị vận chuyển</label>
                                        {!! Form::select('shipping_unit', $shippingUnit, null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="delivery_date">Ngày giao hàng</label>
                                        <div class="input-group" id="delivery_date" data-target-input="nearest">
                                            {!! Form::text('delivery_date', \Carbon\Carbon::parse($model->delivery_date)->format('Y-m-d'), array('class' => 'form-control datetimepicker-input', 'data-target' => '#delivery_date')) !!}
                                            <div class="input-group-append" data-target="#delivery_date"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="delivery_date">Ngày nhận hàng dự kiến</label>
                                        <div class="input-group" id="received_date_expected" data-target-input="nearest">
                                            {!! Form::text('received_date_expected', \Carbon\Carbon::parse($model->received_date_expected)->format('Y-m-d'),
                                                array('class' => 'form-control datetimepicker-input',
                                                'data-target' => '#received_date_expected')) !!}
                                            <div class="input-group-append" data-target="#received_date_expected"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="shipping_method">Phương thức vận chuyển</label>
                                        {!! Form::select('shipping_method', $shippingMethod, null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="shipping_fee">Phí ship</label>
                                        @php
                                            $shippingFee = old('shipping_fee', ($model->shipping_fee ?? null));
                                        @endphp
                                        {!! Form::text('tmp_shipping_fee', ($shippingFee ? number_format($shippingFee) : null), array('class' => 'form-control', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                                        {!! Form::hidden('shipping_fee', $shippingFee, array('class' => 'form-control data-origin')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="icheck-success">
                                    {!! Form::checkbox('status_customer_received', true, null, array('id' => 'status_customer_received')) !!}
                                    <label for="status_customer_received">Khách hàng đã nhận được hàng</label>
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
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#delivery_date').datetimepicker({
                format: 'YYYY-MM-DD',
                icons: {time: 'far fa-clock'}
            });
            $('#received_date_expected').datetimepicker({
                format: 'YYYY-MM-DD',
                icons: {time: 'far fa-clock'}
            });
        });

        $('[name="tmp_shipping_fee"]').keyup(function () {
            var data = formatCurrent(this.value);
            $(this).val(data.format);
            $('[name="shipping_fee"]').val(data.original);
        });
    </script>
@endsection
