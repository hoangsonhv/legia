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
                        {!! Form::open(array('route' => 'admin.price-survey.store', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="co_id">CO<b style="color: red;"> (*)</b></label>
                                        {!! Form::select('co_id', $arrCo, null, array('class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly')) !!}
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="request_id">Mã yêu cầu<b style="color: red;"> (*)</b></label>
                                        {!! Form::select('request_id', $arrRequest, null, array('class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly')) !!}
                                    </div>
                                </div>
                                {{-- <div class="col-4">
                                    <div class="form-group">
                                        <label for="name">IMPO/DOME<b style="color: red;"> (*)</b></label>
                                        {!! Form::select('type', $types, null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div> --}}
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="tax_code">Nhà cung cấp<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('supplier', null, array('class' => 'form-control', 'required' => 'required', 'placeholder' => 'Nhà cung cấp')) !!}
                                    </div>
                                </div>
                                {{-- <div class="col-4">
                                    <div class="form-group">
                                        <label for="tax_code">Nhóm sản phẩm<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('product_group', null, array('class' => 'form-control', 'required' => 'required', 'placeholder' => 'Nhóm sản phẩm')) !!}
                                    </div>
                                </div> --}}
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="address">Người yêu cầu</label>
                                        {!! Form::text('request_person', null, array('class' => 'form-control', 'placeholder' => 'Người yêu cầu')) !!}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="phone">Ngày yêu cầu</label>
                                        <div class="input-group" id="date_request" data-target-input="nearest">
                                            {!! Form::text('date_request', null, array('class' => 'form-control datetimepicker-input', 'data-target' => '#date_request')) !!}
                                            <div class="input-group-append" data-target="#date_request" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="phone">Deadline Cần hàng</label>
                                        <div class="input-group" id="deadline" data-target-input="nearest">
                                            {!! Form::text('deadline', null, array('class' => 'form-control datetimepicker-input', 'data-target' => '#deadline')) !!}
                                            <div class="input-group-append" data-target="#deadline" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="phone">Ngày hỏi NCC</label>
                                        <div class="input-group" id="question_date" data-target-input="nearest">
                                            {!! Form::text('question_date', null, array('class' => 'form-control datetimepicker-input', 'data-target' => '#question_date')) !!}
                                            <div class="input-group-append" data-target="#question_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="phone">Ngày có kết quả</label>
                                        <div class="input-group" id="result_date" data-target-input="nearest">
                                            {!! Form::text('result_date', null, array('class' => 'form-control datetimepicker-input', 'data-target' => '#result_date')) !!}
                                            <div class="input-group-append" data-target="#result_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="name">Duyệt mua<b style="color: red;"> (*)</b></label>
                                        {!! Form::select('status', $status, null, array('class' => 'form-control', 'required' => 'required')) !!}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="phone">Giá trị báo giá (Bao gồm VAT)<b style="color: red;"> (*)</b></label>
                                        {!! Form::text('tmp_price', null, array('class' => 'form-control','required' => 'required', 'placeholder' => 'Giá trị báo giá')) !!}
                                        {!! Form::hidden('price', null) !!}
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="phone">Số ngày quá hạn thanh toán</label>
                                        {!! Form::number('number_date_wait_pay', null, array('class' => 'form-control', 'placeholder' => 'Số ngày quá hạn thanh toán')) !!}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="phone">Ghi chú</label>
                                        {!! Form::textarea('note', null, array('class' => 'form-control', 'placeholder' => 'Ghi chú', 'rows' => 2)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                            <a href="{{ route('admin.price-survey.index') }}" class="btn btn-default">Quay lại</a>
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
    <script type="text/javascript" src="{{ asset('js/admin/coes.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('[name="tmp_price"]').keyup(function () {
                var data = formatCurrent(this.value);
                $(this).val(data.format);
                $('[name="price"]').val(data.original);
            });


            $('#date_request').datetimepicker({
                format: 'YYYY-MM-DD',
                icons: { time: 'far fa-clock' }
            });
            $('#question_date').datetimepicker({
                format: 'YYYY-MM-DD',
                icons: { time: 'far fa-clock' }
            });
            $('#result_date').datetimepicker({
                format: 'YYYY-MM-DD',
                icons: { time: 'far fa-clock' }
            });
            $('#deadline').datetimepicker({
                format: 'YYYY-MM-DD',
                icons: { time: 'far fa-clock' }
            });
        })
    </script>
@endsection
