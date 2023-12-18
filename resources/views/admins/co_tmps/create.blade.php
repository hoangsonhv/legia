@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<style type="text/css">
  .card-header .title {
    text-align: center;
    font-weight: bold;
    color: #03b1fc;
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
          {!! Form::open(array('route' => 'admin.co-tmp.store', 'method' => 'post', 'id' => 'co-form')) !!}
            {!! Form::hidden('url-get-data-warehouses', route('admin.co-tmp.get-data-warehouse')) !!}
            <div class="card-body warehouse">
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
                <div class="card-body offer-price"></div>
                <div class="card-body check-warehouse"></div>
                <div class="card-body more-info">
                  <div class="row">
                    <div class="col-sm-12 col-xl-2">
                      <div class="form-group">
                        <label for="customer[code]">Mã khách hàng<b style="color: red;"> (*)</b></label>
                        {!! Form::text('customer[code]', null, array('class' => 'form-control', 'required' => 'required')) !!}
                      </div>
                    </div>
                    <div class="col-sm-12 col-xl-4">
                      <div class="form-group">
                        <label for="customer[ten]">Tên khách hàng<b style="color: red;"> (*)</b></label>
                        {!! Form::text('customer[ten]', null, array('class' => 'form-control', 'required' => 'required')) !!}
                      </div>
                    </div>
                    <div class="col-sm-12 col-xl-3">
                      <div class="form-group">
                        <label for="customer[mst]">MST</label>
                        {!! Form::text('customer[mst]', null, array('class' => 'form-control')) !!}
                      </div>
                    </div>
                    <div class="col-sm-12 col-xl-3">
                      <div class="form-group">
                        <label for="customer[dien_thoai]">Điện thoại</label>
                        {!! Form::text('customer[dien_thoai]', null, array('class' => 'form-control')) !!}
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 col-xl-4">
                      <div class="form-group">
                        <label for="customer[email]">Email</label>
                        {!! Form::email('customer[email]', null, array('class' => 'form-control')) !!}
                      </div>
                    </div>
                    <div class="col-sm-12 col-xl-4">
                      <div class="form-group">
                        <label for="customer[address]">Địa chỉ</label>
                        {!! Form::text('customer[dia_chi]', null, array('class' => 'form-control')) !!}
                      </div>
                    </div>
                    <div class="col-sm-12 col-xl-4">
                      <div class="form-group">
                        <label for="customer[nguoi_nhan]">Người nhận</label>
                        {!! Form::text('customer[nguoi_nhan]', null, array('class' => 'form-control')) !!}
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 col-xl-4">
                      <div class="form-group">
                        <label for="so_bao_gia">Số báo giá</label>
                        {!! Form::text('so_bao_gia', null, array('class' => 'form-control')) !!}
                      </div>
                    </div>
                    <div class="col-sm-12 col-xl-4">
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
                    <div class="col-sm-12 col-xl-4">
                      <div class="form-group">
                        <label for="thoi_han_bao_gia">Thời hạn báo giá</label>
                        {!! Form::text('thoi_han_bao_gia', null, array('class' => 'form-control')) !!}
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 col-xl-4">
                      <div class="form-group">
                        <label for="sales">Sales</label>
                        {!! Form::text('sales', null, array('class' => 'form-control')) !!}
                      </div>
                    </div>
                    <div class="col-sm-12 col-xl-4">
                      <div class="form-group">
                        <label for="dong_goi_va_van_chuyen">Đóng gói và vận chuyển</label>
                        {!! Form::text('dong_goi_va_van_chuyen', null, array('class' => 'form-control')) !!}
                      </div>
                    </div>
                    <div class="col-sm-12 col-xl-4">
                      <div class="form-group">
                        <label for="noi_giao_hang">Nơi giao hàng</label>
                        {!! Form::text('noi_giao_hang', null, array('class' => 'form-control')) !!}
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 col-xl-6">
                      <div class="form-group">
                        <label for="xuat_xu">Xuất xứ</label>
                        {!! Form::text('xuat_xu', null, array('class' => 'form-control')) !!}
                      </div>
                    </div>
                    <div class="col-sm-12 col-xl-6">
                      <div class="form-group">
                        <label for="thoi_gian_giao_hang">Thời gian giao hàng</label>
                        {!! Form::text('thoi_gian_giao_hang', null, array('class' => 'form-control')) !!}
                      </div>
                    </div>
                  </div>
                  <div class="form-group mt-3">
                    <h5>
                      <p style="width: fit-content; padding: 5px 10px; border-radius: 5px; font-size:1rem;;" class="text-danger bg-warning">
                        <b>Giá trị đơn hàng: <span class="money_total"><b>0</b></span></b>
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
                            <th class="align-middle">Sau khi giao hàng và chứng từ thanh toán</th>
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
                              {!! Form::text('tmp[amount_money][truoc_khi_lam_hang]', null, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                              {!! Form::hidden('thanh_toan[amount_money][truoc_khi_lam_hang]', null, array('class' => 'form-control data-origin')) !!}
                            </td>
                            <td>
                              {!! Form::text('tmp[amount_money][truoc_khi_giao_hang]', null, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                              {!! Form::hidden('thanh_toan[amount_money][truoc_khi_giao_hang]', null, array('class' => 'form-control data-origin')) !!}
                            </td>
                            <td>
                              {!! Form::text('tmp[amount_money][ngay_khi_giao_hang]', null, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                              {!! Form::hidden('thanh_toan[amount_money][ngay_khi_giao_hang]', null, array('class' => 'form-control data-origin')) !!}
                            </td>
                            <td>
                              {!! Form::text('tmp[amount_money][sau_khi_giao_hang_va_cttt]', null, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                              {!! Form::hidden('thanh_toan[amount_money][sau_khi_giao_hang_va_cttt]', null, array('class' => 'form-control data-origin')) !!}
                            </td>
                            <td>
                              {!! Form::text('tmp[amount_money][thoi_gian_no]', null, array('class' => 'form-control text-center d-none', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
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
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Lưu thông tin</button>
              <a href="{{ route('admin.co-tmp.index') }}" class="btn btn-default">Quay lại</a>
            </div>
          {!! Form::close() !!}
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      @include('admins.co_tmps.includes.offer-price', ['url' => route('admin.co-tmp.get-data-warehouse')])
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
  });
</script>
@endsection
