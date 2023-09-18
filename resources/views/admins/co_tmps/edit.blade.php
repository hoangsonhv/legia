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
  hr.hor {
    color: red;
    border: 3px solid #007bff;
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
      <div class="col-12 warehouse">
        {!! Form::model($co, array('route' => ['admin.co-tmp.update', $co->id], 'method' => 'patch')) !!}
        {!! Form::hidden('id', null) !!}
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="form-group">
                  <label for="description">Mô tả</label>
                  {!! Form::text('description', null, array('class' => 'form-control')) !!}
                </div>
                <div class="card" id="block-offer-price">
                  <div class="card-header">
                    <h3 class="title">Danh mục hàng hoá</h3>
                    <div class="text-right">
                      <button type="button" class="btn btn-success" id="import-offer">Import chào giá</button>
                    </div>
                  </div>
                  <div class="card-body offer-price">
                    @include('admins.coes.includes.list-products', ['warehouses' => $warehouses, 'collect' => true, 'isCoTmp' => true])
                  </div>
                  <div class="card-body more-info">
                    <div class="row">
                      <div class="col-2">
                        <div class="form-group">
                          <label for="customer[code]">Mã khách hàng<b style="color: red;"> (*)</b></label>
                          {!! Form::text('customer[code]', null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="customer[ten]">Tên khách hàng<b style="color: red;"> (*)</b></label>
                          {!! Form::text('customer[ten]', null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="customer[mst]">MST</label>
                          {!! Form::text('customer[mst]', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="customer[dien_thoai]">Điện thoại</label>
                          {!! Form::text('customer[dien_thoai]', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="customer[email]">Email</label>
                          {!! Form::email('customer[email]', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="customer[address]">Địa chỉ</label>
                          {!! Form::text('customer[dia_chi]', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="customer[nguoi_nhan]">Người nhận</label>
                          {!! Form::text('customer[nguoi_nhan]', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="so_bao_gia">Số báo giá</label>
                          {!! Form::text('so_bao_gia', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                      <div class="col-4">
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
                      <div class="col-4">
                        <div class="form-group">
                          <label for="thoi_han_bao_gia">Thời hạn báo giá</label>
                          {!! Form::text('thoi_han_bao_gia', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="sales">Sales</label>
                          {!! Form::text('sales', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="dong_goi_va_van_chuyen">Đóng gói và vận chuyển</label>
                          {!! Form::text('dong_goi_va_van_chuyen', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="noi_giao_hang">Nơi giao hàng</label>
                          {!! Form::text('noi_giao_hang', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label for="xuat_xu">Xuất xứ</label>
                          {!! Form::text('xuat_xu', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label for="thoi_gian_giao_hang">Thời gian giao hàng</label>
                          {!! Form::text('thoi_gian_giao_hang', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                    </div>


{{--                    <div class="form-group">--}}
{{--                      <label for="customer[code]">Mã khách hàng<b style="color: red;"> (*)</b></label>--}}
{{--                      {!! Form::text('customer[code]', null, array('class' => 'form-control', 'required' => 'required')) !!}--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                      <label for="customer[ten]">Tên khách hàng<b style="color: red;"> (*)</b></label>--}}
{{--                      {!! Form::text('customer[ten]', null, array('class' => 'form-control', 'required' => 'required')) !!}--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                      <label for="customer[dia_chi]">Địa chỉ</label>--}}
{{--                      {!! Form::text('customer[dia_chi]', null, array('class' => 'form-control')) !!}--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                      <label for="customer[mst]">MST</label>--}}
{{--                      {!! Form::text('customer[mst]', null, array('class' => 'form-control')) !!}--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                      <label for="customer[nguoi_nhan]">Người nhận</label>--}}
{{--                      {!! Form::text('customer[nguoi_nhan]', null, array('class' => 'form-control')) !!}--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                      <label for="customer[dien_thoai]">Điện thoại</label>--}}
{{--                      {!! Form::text('customer[dien_thoai]', null, array('class' => 'form-control')) !!}--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                      <label for="customer[email]">Email</label>--}}
{{--                      {!! Form::email('customer[email]', null, array('class' => 'form-control')) !!}--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                      <label for="so_bao_gia">Số báo giá</label>--}}
{{--                      {!! Form::text('so_bao_gia', null, array('class' => 'form-control')) !!}--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                      <label for="ngay_bao_gia">Ngày báo giá</label>--}}
{{--                      <div class="input-group" id="ngay_bao_gia" data-target-input="nearest">--}}
{{--                        {!! Form::text('ngay_bao_gia', null, array('class' => 'form-control datetimepicker-input', 'data-target' => '#ngay_bao_gia')) !!}--}}
{{--                        <div class="input-group-append" data-target="#ngay_bao_gia" data-toggle="datetimepicker">--}}
{{--                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>--}}
{{--                        </div>--}}
{{--                      </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                      <label for="sales">Sales</label>--}}
{{--                      {!! Form::text('sales', null, array('class' => 'form-control')) !!}--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                      <label for="thoi_han_bao_gia">Thời hạn báo giá</label>--}}
{{--                      {!! Form::text('thoi_han_bao_gia', null, array('class' => 'form-control')) !!}--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                      <label for="dong_goi_va_van_chuyen">Đóng gói và vận chuyển</label>--}}
{{--                      {!! Form::text('dong_goi_va_van_chuyen', null, array('class' => 'form-control')) !!}--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                      <label for="noi_giao_hang">Nơi giao hàng</label>--}}
{{--                      {!! Form::text('noi_giao_hang', null, array('class' => 'form-control')) !!}--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                      <label for="xuat_xu">Xuất xứ</label>--}}
{{--                      {!! Form::text('xuat_xu', null, array('class' => 'form-control')) !!}--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                      <label for="thoi_gian_giao_hang">Thời gian giao hàng</label>--}}
{{--                      {!! Form::text('thoi_gian_giao_hang', null, array('class' => 'form-control')) !!}--}}
{{--                    </div>--}}
                    <div class="form-group">
{{--                      <label for="thanh_toan">Thanh toán</label>--}}
{{--                      <p class="text-danger">Giá trị đơn hàng: <span class="money_total"><b></b></span></p>--}}

                      <h5 class="{{!\App\Helpers\PermissionHelper::hasPermission('admin.co.price') ? 'd-none' : ''}}">
                        <p style="width: fit-content; padding: 5px 10px; border-radius: 5px" class="text-danger bg-warning">
                          <b>Giá trị đơn hàng: <span class="money_total"><b></b></span></b>
                        </p>
                      </h5>
                      <div class="table-responsive p-0 {{!\App\Helpers\PermissionHelper::hasPermission('admin.co.price') ? 'd-none' : ''}}">
                        <table class="table table-head-fixed table-bordered text-wrap">
                          <thead>
                            <tr class="text-center">
                              <th>&nbsp</th>
                              <th class="align-middle">Trước khi làm hàng</th>
                              <th class="align-middle">Trước khi giao hàng</th>
                              <th class="align-middle">Ngay khi giao hàng</th>
                              <th class="align-middle">Sau khi giao hàng và chứng từ thanh toán</th>
                              <th class="align-middle">Thời gian nợ (ngày)</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
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
                                  $valVnd = number_format(old('thanh_toan[amount_money][truoc_khi_lam_hang]', $co->thanh_toan['amount_money']['truoc_khi_lam_hang']));
                                  if (!$valVnd) {
                                    $valVnd = null;
                                  }
                                @endphp
                                {!! Form::text('tmp[amount_money][truoc_khi_lam_hang]', $valVnd, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                                {!! Form::hidden('thanh_toan[amount_money][truoc_khi_lam_hang]', null, array('class' => 'form-control data-origin')) !!}
                              </td>
                              <td>
                                @php
                                  $valVnd = number_format(old('thanh_toan[amount_money][truoc_khi_giao_hang]', $co->thanh_toan['amount_money']['truoc_khi_giao_hang']));
                                  if (!$valVnd) {
                                    $valVnd = null;
                                  }
                                @endphp
                                {!! Form::text('tmp[amount_money][truoc_khi_giao_hang]', $valVnd, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                                {!! Form::hidden('thanh_toan[amount_money][truoc_khi_giao_hang]', null, array('class' => 'form-control data-origin')) !!}
                              </td>
                              <td>
                                @php
                                  $valVnd = number_format(old('thanh_toan[amount_money][ngay_khi_giao_hang]', $co->thanh_toan['amount_money']['ngay_khi_giao_hang']));
                                  if (!$valVnd) {
                                    $valVnd = null;
                                  }
                                @endphp
                                {!! Form::text('tmp[amount_money][ngay_khi_giao_hang]', $valVnd, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                                {!! Form::hidden('thanh_toan[amount_money][ngay_khi_giao_hang]', null, array('class' => 'form-control data-origin')) !!}
                              </td>
                              <td>
                                @php
                                  $valVnd = number_format(old('thanh_toan[amount_money][sau_khi_giao_hang_va_cttt]', $co->thanh_toan['amount_money']['sau_khi_giao_hang_va_cttt']));
                                  if (!$valVnd) {
                                    $valVnd = null;
                                  }
                                @endphp
                                {!! Form::text('tmp[amount_money][sau_khi_giao_hang_va_cttt]', $valVnd, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                                {!! Form::hidden('thanh_toan[amount_money][sau_khi_giao_hang_va_cttt]', null, array('class' => 'form-control data-origin')) !!}
                              </td>
                              <td>
                                @php
                                  $valVnd = number_format(old('thanh_toan[amount_money][thoi_gian_no]', $co->thanh_toan['amount_money']['thoi_gian_no']));
                                  if (!$valVnd) {
                                    $valVnd = null;
                                  }
                                @endphp
                                {!! Form::text('tmp[amount_money][thoi_gian_no]', $valVnd, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                                {!! Form::hidden('thanh_toan[amount_money][thoi_gian_no]', null, array('class' => 'form-control data-origin')) !!}
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
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-footer text-right">
                @permission('admin.co.create')
                @if((\App\Enums\ProcessStatus::Approved == $co->status && $limitApprovalCg && $co->tong_gia > $limitApprovalCg)
                  || ($limitApprovalCg && $co->tong_gia <= $limitApprovalCg))
                <a href="{{ route('admin.co.create', ['coTmpId' => $co->id]) }}" class="btn btn-success">Tạo CO</a>
                @endif
                @endpermission
                @if(\App\Enums\ProcessStatus::Pending == $co->status)
                <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                @endif
                <a href="{{ route('admin.co-tmp.index') }}" class="btn btn-default">Quay lại</a>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        {!! Form::close() !!}
      </div>
      @include('admins.co_tmps.includes.offer-price', ['url' => route('admin.co-tmp.get-data-warehouse')])
  
      @if($limitApprovalCg && $co->tong_gia > $limitApprovalCg)
      @include('admins.includes.approval', ['id' => $co->id, 'type' => 'co-tmp', 'status' => $co->status])
      @endif
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
  $( document ).ready(function() {
    bsCustomFileInput.init();
    sumTotal('.warehouse .table tbody');
  });
</script>
@endsection
