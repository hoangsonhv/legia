@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
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
                        {!! Form::open(array('route' => 'admin.delivery.store', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
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
                                        {!! Form::select('core_customer_id', $coreCustomers, $coreCustomerId, array('class' => 'form-control select2', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="name">Họ tên người nhận<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('recipient_name', ($coModel && $coModel->core_customer) ? $coModel->core_customer->name : '',
                                            array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="tax_code">SĐT người nhận<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('recipient_phone', ($coModel && $coModel->core_customer) ? $coModel->core_customer->phone : '',
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
                                        <label for="delivery_date">Ngày giao hàng<b style="color: red;"> (*)</b></label>
                                        <div class="input-group" id="delivery_date" data-target-input="nearest">
                                            {!! Form::text('delivery_date', Date('Y-m-d'), array('class' => 'form-control datetimepicker-input', 'data-target' => '#delivery_date', 'required' => 'required')) !!}
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
                                            {!! Form::text('received_date_expected', null,
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
                                        <label for="shipping_fee">Phí ship<b style="color: red;"> (*)</b></label>
                                        @php
                                            $shippingFee = old('shipping_fee', ($delivery->shipping_fee ?? null));
                                        @endphp
                                        {!! Form::text('tmp_shipping_fee', ($shippingFee ? number_format($shippingFee) : 0), array('class' => 'form-control', 'onKeyUp' => 'return getNumberFormat(this)', 'required' => 'required')) !!}
                                        {!! Form::hidden('shipping_fee', $shippingFee ? $shippingFee : 0, array('class' => 'form-control data-origin')) !!}
                                    </div>
                                </div>
                            </div>
                            {!! Form::hidden('status_customer_received', null) !!}
{{--                            <div class="form-group">--}}
{{--                                <div class="icheck-success">--}}
{{--                                    {!! Form::checkbox('status_customer_received', true, null, array('id' => 'status_customer_received')) !!}--}}
{{--                                    <label for="status_customer_received">Khách hàng đã nhận được hàng</label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                            <a href="{{ route('admin.delivery.index') }}" class="btn btn-default">Quay lại</a>
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
