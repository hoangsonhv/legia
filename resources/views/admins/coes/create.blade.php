@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style type="text/css">
        .card-header .title {
            text-align: center;
            font-weight: bold;
            color: #03b1fc;
        }

        .block-file {
            margin-bottom: 10px;
        }
    </style>
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
                        {!! Form::model($co, array('route' => 'admin.co.store', 'method' => 'post', 'id' => 'co-form', 'enctype' => 'multipart/form-data')) !!}
                        {!! Form::hidden('url-get-data-warehouses', route('admin.co.get-data-warehouse')) !!}
                        {!! Form::hidden('co_tmp_id', $coTmpId) !!}
                        <div class="card-body warehouse">
                            <div class="card" id="block-offer-price">
                                <div class="card-header">
                                    <h3 class="title">Danh mục hàng hoá</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-success" id="import-offer">Import chào
                                            giá
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body offer-price">
                                    @if(!empty($warehouses))
                                        @include('admins.coes.includes.list-products', ['warehouses' => $warehouses, 'material' => $material, 'collect' => true, 'createCO' => true])
                                    @endif
                                </div>
                                <div class="card-body check-warehouse">
                                    @if(!empty($material))
                                        @include('admins.coes.includes.list-warehouses', ['warehouses' => $material, 'collect' => true])
                                    @endif
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="description">Mô tả CO</label>
                                        {!! Form::text('description', null, array('class' => 'form-control')) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>PO của khách hàng</label>
                                        <div class="input-group block-file">
                                            <div class="custom-file">
                                                <input type="file" name="po_document[]" class="custom-file-input"
                                                       multiple/>
                                                <label class="custom-file-label">Chọn file</label>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success add-upload-file"
                                                type-document="po_document">
                                            Thêm file upload
                                        </button>
                                    </div>
                                    <div class="form-group">
                                        <label>Chứng từ hợp đồng</label>
                                        <div class="input-group block-file">
                                            <div class="custom-file">
                                                <input type="file" name="contract_document[]" class="custom-file-input"
                                                       multiple/>
                                                <label class="custom-file-label">Chọn file</label>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success add-upload-file"
                                                type-document="contract_document">
                                            Thêm file upload
                                        </button>
                                    </div>
                                    <div class="form-group">
                                        <label>Chứng từ hoá đơn</label>
                                        <div class="input-group block-file">
                                            <div class="custom-file">
                                                <input type="file" name="invoice_document[]" class="custom-file-input"
                                                       multiple/>
                                                <label class="custom-file-label">Chọn file</label>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success add-upload-file"
                                                type-document="invoice_document">
                                            Thêm file upload
                                        </button>
                                    </div> 
                                </div>
                            </div>
                            <div class="card more-info">
                                <div class="card-header">
                                    <h3 class="title">Thông tin khách hàng</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-xl-4">
                                            <div class="form-group">
                                                <label for="customer[code]">Mã khách hàng<b style="color: red;"> (*)</b></label>
                                                {!! Form::text('customer[code]', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-8">
                                            <div class="form-group">
                                                <label for="customer[ten]">Tên khách hàng<b style="color: red;"> (*)</b></label>
                                                {!! Form::text('customer[ten]', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-xl-8">
                                            <div class="form-group">
                                                <label for="customer[dia_chi]">Địa chỉ</label>
                                                {!! Form::text('customer[dia_chi]', null, array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4">
                                            <div class="form-group">
                                                <label for="customer[mst]">MST</label>
                                                {!! Form::text('customer[mst]', null, array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-xl-6">
                                            <div class="form-group">
                                                <label for="customer[nguoi_nhan]">Người nhận</label>
                                                {!! Form::text('customer[nguoi_nhan]', null, array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-6">
                                            <div class="form-group">
                                                <label for="customer[dien_thoai]">Điện thoại</label>
                                                {!! Form::text('customer[dien_thoai]', null, array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-xl-6">
                                            <div class="form-group">
                                                <label for="customer[email]">Email</label>
                                                {!! Form::email('customer[email]', null, array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-6">
                                            <div class="form-group">
                                                <label for="so_bao_gia">Số báo giá</label>
                                                {!! Form::text('so_bao_gia', null, array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-xl-6">
                                            <div class="form-group">
                                                <label for="ngay_bao_gia">Ngày báo giá</label>
                                                <div class="input-group" id="ngay_bao_gia" data-target-input="nearest">
                                                    {!! Form::text('ngay_bao_gia', null, array('class' => 'form-control datetimepicker-input', 'data-target' => '#ngay_bao_gia')) !!}
                                                    <div class="input-group-append" data-target="#ngay_bao_gia" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-6">
                                            <div class="form-group">
                                                <label for="sales">Sales</label>
                                                {!! Form::text('sales', null, array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-xl-6">
                                            <div class="form-group">
                                                <label for="thoi_han_bao_gia">Thời hạn báo giá</label>
                                                {!! Form::text('thoi_han_bao_gia', null, array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-6">
                                            <div class="form-group">
                                                <label for="dong_goi_va_van_chuyen">Đóng gói và vận chuyển</label>
                                                {!! Form::text('dong_goi_va_van_chuyen', null, array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-xl-6">
                                            <div class="form-group">
                                                <label for="noi_giao_hang">Nơi giao hàng</label>
                                                {!! Form::text('noi_giao_hang', null, array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-6">
                                            <div class="form-group">
                                                <label for="xuat_xu">Xuất xứ</label>
                                                {!! Form::text('xuat_xu', null, array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-xl-6">
                                            <div class="form-group">
                                                <label for="thoi_gian_giao_hang">Thời gian giao hàng</label>
                                                {!! Form::text('thoi_gian_giao_hang', null, array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>

{{--                                    <div class="form-group">--}}
{{--                                        <label for="customer[code]">Mã khách hàng<b style="color: red;"> (*)</b></label>--}}
{{--                                        {!! Form::text('customer[code]', null, array('class' => 'form-control', 'required' => 'required')) !!}--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="customer[ten]">Tên khách hàng<b style="color: red;"> (*)</b></label>--}}
{{--                                        {!! Form::text('customer[ten]', null, array('class' => 'form-control', 'required' => 'required')) !!}--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="customer[dia_chi]">Địa chỉ</label>--}}
{{--                                        {!! Form::text('customer[dia_chi]', null, array('class' => 'form-control')) !!}--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="customer[mst]">MST</label>--}}
{{--                                        {!! Form::text('customer[mst]', null, array('class' => 'form-control')) !!}--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="customer[nguoi_nhan]">Người nhận</label>--}}
{{--                                        {!! Form::text('customer[nguoi_nhan]', null, array('class' => 'form-control')) !!}--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="customer[dien_thoai]">Điện thoại</label>--}}
{{--                                        {!! Form::text('customer[dien_thoai]', null, array('class' => 'form-control')) !!}--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="customer[email]">Email</label>--}}
{{--                                        {!! Form::email('customer[email]', null, array('class' => 'form-control')) !!}--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                            <div class="card more-info">
                                <div class="card-body">
{{--                                    <div class="form-group">--}}
{{--                                        <label for="so_bao_gia">Số báo giá</label>--}}
{{--                                        {!! Form::text('so_bao_gia', null, array('class' => 'form-control')) !!}--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="ngay_bao_gia">Ngày báo giá</label>--}}
{{--                                        <div class="input-group" id="ngay_bao_gia" data-target-input="nearest">--}}
{{--                                            {!! Form::text('ngay_bao_gia', null, array('class' => 'form-control datetimepicker-input', 'data-target' => '#ngay_bao_gia')) !!}--}}
{{--                                            <div class="input-group-append" data-target="#ngay_bao_gia"--}}
{{--                                                 data-toggle="datetimepicker">--}}
{{--                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="sales">Sales</label>--}}
{{--                                        {!! Form::text('sales', null, array('class' => 'form-control')) !!}--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="thoi_han_bao_gia">Thời hạn báo giá</label>--}}
{{--                                        {!! Form::text('thoi_han_bao_gia', null, array('class' => 'form-control')) !!}--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="dong_goi_va_van_chuyen">Đóng gói và vận chuyển</label>--}}
{{--                                        {!! Form::text('dong_goi_va_van_chuyen', null, array('class' => 'form-control')) !!}--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="noi_giao_hang">Nơi giao hàng</label>--}}
{{--                                        {!! Form::text('noi_giao_hang', null, array('class' => 'form-control')) !!}--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="xuat_xu">Xuất xứ</label>--}}
{{--                                        {!! Form::text('xuat_xu', null, array('class' => 'form-control')) !!}--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="thoi_gian_giao_hang">Thời gian giao hàng</label>--}}
{{--                                        {!! Form::text('thoi_gian_giao_hang', null, array('class' => 'form-control')) !!}--}}
{{--                                    </div>--}}
                                    <div class="form-group">
{{--                                        <label for="thanh_toan">Thanh toán</label>--}}
{{--                                        <p class="text-danger">Giá trị đơn hàng: <span--}}
{{--                                                    class="money_total"><b>0</b></span></p>--}}

                                        <h5>
                                            <p style="width: fit-content; padding: 5px 10px; border-radius: 5px; font-size:1rem;" class="text-danger bg-warning">
                                                <b>Giá trị đơn hàng: <span class="money_total"><b></b></span></b>
                                            </p>
                                        </h5>
                                        <div class="table-responsive p-0">
                                            <table class="table table-head-fixed table-bordered text-wrap">
                                                <thead>
                                                <tr class="text-center">
                                                    <th>&nbsp</th>
                                                    <th class="align-middle">Trước khi làm hàng</th>
                                                    <th class="align-middle">Trước khi giao hàng</th>
                                                    <th class="align-middle">Ngay khi giao hàng</th>
                                                    <th class="align-middle">Sau khi giao hàng và chứng từ thanh toán
                                                    </th>
                                                    <th class="align-middle">Thời gian nợ (ngày)</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="text-center">
                                                    <td class="text-right" width="20%">% tổng giá trị đơn hàng</td>
                                                    <td>
                                                        {!! Form::text('thanh_toan[percent][truoc_khi_lam_hang]', null,
                                                          array('class' => 'form-control text-center', 'onKeyUp' => "return calPaymentPer(this, 'truoc_khi_lam_hang')")) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('thanh_toan[percent][truoc_khi_giao_hang]', null,
                                                          array('class' => 'form-control text-center', 'onKeyUp' => "return calPaymentPer(this, 'truoc_khi_giao_hang')")) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('thanh_toan[percent][ngay_khi_giao_hang]', null,
                                                          array('class' => 'form-control text-center', 'onKeyUp' => "return calPaymentPer(this, 'ngay_khi_giao_hang')")) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('thanh_toan[percent][sau_khi_giao_hang_va_cttt]', null,
                                                          array('class' => 'form-control text-center', 'onKeyUp' => "return calPaymentPer(this, 'sau_khi_giao_hang_va_cttt')")) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('thanh_toan[percent][thoi_gian_no]', null, array('class' => 'form-control text-center')) !!}
                                                    </td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td class="text-right">Giá trị - VNĐ</td>
                                                    <td>
                                                        @php
                                                            if($co) {
                                                              $valVnd = number_format(old('thanh_toan[amount_money][truoc_khi_lam_hang]', $co->thanh_toan['amount_money']['truoc_khi_lam_hang']));
                                                            }
                                                            if (empty($valVnd)) {
                                                              $valVnd = null;
                                                            }
                                                        @endphp
                                                        {!! Form::text('tmp[amount_money][truoc_khi_lam_hang]', $valVnd, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                                                        {!! Form::hidden('thanh_toan[amount_money][truoc_khi_lam_hang]', null, array('class' => 'form-control data-origin')) !!}
                                                    </td>
                                                    <td>
                                                        @php
                                                            if($co) {
                                                              $valVnd = number_format(old('thanh_toan[amount_money][truoc_khi_giao_hang]', $co->thanh_toan['amount_money']['truoc_khi_giao_hang']));
                                                            }
                                                            if (empty($valVnd)) {
                                                              $valVnd = null;
                                                            }
                                                        @endphp
                                                        {!! Form::text('tmp[amount_money][truoc_khi_giao_hang]', $valVnd, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                                                        {!! Form::hidden('thanh_toan[amount_money][truoc_khi_giao_hang]', null, array('class' => 'form-control data-origin')) !!}
                                                    </td>
                                                    <td>
                                                        @php
                                                            if($co) {
                                                              $valVnd = number_format(old('thanh_toan[amount_money][ngay_khi_giao_hang]', $co->thanh_toan['amount_money']['ngay_khi_giao_hang']));
                                                            }
                                                            if (empty($valVnd)) {
                                                              $valVnd = null;
                                                            }
                                                        @endphp
                                                        {!! Form::text('tmp[amount_money][ngay_khi_giao_hang]', $valVnd, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                                                        {!! Form::hidden('thanh_toan[amount_money][ngay_khi_giao_hang]', null, array('class' => 'form-control data-origin')) !!}
                                                    </td>
                                                    <td>
                                                        @php
                                                            if($co) {
                                                              $valVnd = number_format(old('thanh_toan[amount_money][sau_khi_giao_hang_va_cttt]', $co->thanh_toan['amount_money']['sau_khi_giao_hang_va_cttt']));
                                                            }
                                                            if (empty($valVnd)) {
                                                              $valVnd = null;
                                                            }
                                                        @endphp
                                                        {!! Form::text('tmp[amount_money][sau_khi_giao_hang_va_cttt]', $valVnd, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                                                        {!! Form::hidden('thanh_toan[amount_money][sau_khi_giao_hang_va_cttt]', null, array('class' => 'form-control data-origin')) !!}
                                                    </td>
                                                    <td>
                                                        @php
                                                            if($co) {
                                                              $valVnd = number_format(old('thanh_toan[amount_money][thoi_gian_no]', $co->thanh_toan['amount_money']['thoi_gian_no']));
                                                            }
                                                            if (empty($valVnd)) {
                                                              $valVnd = null;
                                                            }
                                                        @endphp
                                                        {!! Form::text('tmp[amount_money][thoi_gian_no]', $valVnd, array('class' => 'form-control text-center d-none', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                                                        {!! Form::hidden('thanh_toan[amount_money][thoi_gian_no]', null, array('class' => 'form-control data-origin')) !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">Bộ chứng từ thanh toán</td>
                                                    <td colspan="6">
                                                        @php
                                                            $paymentDocuments = App\Services\CoService::paymentDocuments();
                                                        @endphp
                                                        <table>
                                                            <thead>
                                                            <tr>
                                                                <th style="width: 400px">
                                                                    Tên chứng từ
                                                                </th>
                                                                <th style="width: 120px" class="text-center">
                                                                    Yêu cầu
                                                                </th>
                                                                <th style="width: 120px" class="text-center">
                                                                    Hoàn thành
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($paymentDocuments as $key => $doc)
                                                                <tr>
                                                                    <td>
                                                                        {{$doc}}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <div class="icheck-success">
                                                                            {!! Form::checkbox('thanh_toan[payment_document]['. 'required_' . $key .']' , true, null, array('id' => 'required_' . $key)) !!}
                                                                            <label for={{'required_' . $key}}></label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <div class="icheck-success">
                                                                            {!! Form::checkbox('thanh_toan[payment_document]['. 'finished_' .$key .']' , true, null, array('id' => 'finished_' .$key)) !!}
                                                                            <label for={{'finished_' .$key}}></label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                            <a href="{{ route('admin.co.index') }}" class="btn btn-default">Quay lại</a>
                        </div>
                    {!! Form::close() !!}
                    <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                @include('admins.coes.includes.offer-price', ['url' => route('admin.co.get-data-warehouse')])
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript"
            src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/admin/coes.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            bsCustomFileInput.init();
            sumTotal('.warehouse .table tbody');

            $('[name="material_type[]"]').change(function () {
                let showData = $(this).parent().find('.show-data-switch');
                if (this.checked) {
                    showData.text('Kim loại')
                } else {
                    showData.text('Phi kim loại')
                }
            });
        });
    </script>
@endsection
