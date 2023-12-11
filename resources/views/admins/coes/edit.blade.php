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
        @include('admins.message')
      </div>
      <div class="col-12 warehouse">
        <div class="row">
          <div class="col-12">
            <div class="card">
              @if($co->start_timeline)
                <div class="card-body">
                  @php
                    $dataTime   = \Carbon\Carbon::parse($co->start_timeline);
                    $startTime  = \Carbon\Carbon::parse($dataTime->format('Y-m-d 00:00:00'));
                    $curentTime = \Carbon\Carbon::parse(\Carbon\Carbon::now()->format('Y-m-d 00:00:00'));
                    $numberTime = $curentTime->diffInDays($startTime);
                  @endphp
                  @if($co->enough_money)
                    <h5 class="text-primary">
                      Thời gian hoàn tất thu tiền: {{ \Carbon\Carbon::parse($co->enough_money)->format('Y-m-d H:i:s') }}
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
        @if($coTmp)
        <div class="card">
          <div class="card-header">
            <div class="d-flex justify-content-center">
              <h3 class="title">Danh mục hàng hoá Chào Giá </h3>
              <button class="btn btn-default ml-3" id="show-table-tmp-co">Hiển thị</button>
              <button class="btn btn-default ml-3" id="hiden-table-tmp-co">Ẩn</button>
            </div>
            <div class="text-right">
              <button type="button" class="btn btn-success" id="import-offer">Import chào giá</button>
            </div>
          </div>
          <div class="card-body" style="display: none" id="card-table-tmp-co">
            {{-- @include('admins.coes.includes.list-products', ['warehouses' => $coTmp, 'collect' => true, 'notAction' => true]) --}}
            @include('admins.coes.includes.list-products', ['warehouses' => $coTmp, 'collect' => true, 'disabled' => true])
          </div>
        </div>
        @endif
        {!! Form::model($co, array('route' => ['admin.co.update', $co->id], 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
        {!! Form::hidden('id', null) !!}
        <div class="row">
          <div class="col-12">
            <div class="card" id="block-offer-price">
              <div class="card-header">
                <div class="d-flex justify-content-center">
                  <h3 class="title">Danh mục hàng hoá CO </h3>
                  <button class="btn btn-default ml-3" id="show-table-co">Hiển thị</button>
                  <button class="btn btn-default ml-3" id="hiden-table-co">Ẩn</button>
                </div>
                <div class="text-right">
                  <button type="button" class="btn btn-success" id="import-offer">Import chào giá</button>
                </div>
              </div>
              <div class="card-body offer-price" style="display: none" id="card-table-co">
                @include('admins.coes.includes.list-products', ['warehouses' => $warehouses, 'material' => $listWarehouse, 'collect' => true, 'is_co' => true])
                @if(!empty($listWarehouse))
                 @include('admins.coes.includes.list-warehouses', ['warehouses' => $listWarehouse, 'collect' => true])
               @endif
              </div>
             {{-- <div class="card-body check-warehouse">
               @if(!empty($listWarehouse))
                 @include('admins.coes.includes.list-warehouses', ['warehouses' => $listWarehouse, 'collect' => true])
               @endif
             </div> --}}
            </div>
          </div>
        </div>
        <div class="row">
          @php
            $existsRel = ($co->request->count() || $co->payment->count() || $co->receipt->count());
          @endphp
          <div class="{{ $existsRel ? 'col-6' : 'col-12'}}">
            <div class="card">
              <div class="card-body">
                <div class="card">
                  <div class="card-body">
{{--                    @if(\App\Enums\ProcessStatus::Approved == $co->status)--}}
{{--                      <div class="form-group">--}}
{{--                        <div class="icheck-success">--}}
{{--                          {!! Form::checkbox('confirm_done', true, $co->confirm_done, array('id' => 'confirm_done')) !!}--}}
{{--                          <label for="confirm_done">Xác nhận CO đã xong</label>--}}
{{--                        </div>--}}
{{--                      </div>--}}
{{--                      @if($co->confirm_done)--}}
{{--                      <div class="form-group">--}}
{{--                        <div class="icheck-success">--}}
{{--                          {!! Form::checkbox('enough_money', true, $co->enough_money, array('id' => 'enough_money')) !!}--}}
{{--                          <label for="enough_money">Đã thu tiền đủ</label>--}}
{{--                        </div>--}}
{{--                      </div>--}}
{{--                      @endif--}}
{{--                    @endif--}}
                    <div class="form-group">
                      <label for="code">Mã CO</label>
                      {!! Form::text('co_code', $co->code, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                    </div>
                    <div class="form-group">
                      <label for="description">Mô tả</label>
                      {!! Form::text('description', null, array('class' => 'form-control')) !!}
                    </div>
                    <div class="form-group">
                      <label>PO của khách hàng</label>
                      <div class="input-group block-file">
                        <div class="custom-file">
                          <input type="file" name="po_document[]" class="custom-file-input" multiple />
                          <label class="custom-file-label">Chọn file</label>
                        </div>
                      </div>
                      <button type="button" class="btn btn-success add-upload-file" type-document="po_document">
                        Thêm file upload
                      </button>
                      @if(json_decode($co->po_document, true))
                      <button type="button" class="btn btn-success" id="po_document_display" data-toggle="modal" data-target="#po_document_modal" content="{{ $co->po_document }}">Hiển thị chứng từ đã tồn tại</button>
                      @endif
                    </div>
                    <div class="form-group">
                      <label>Chứng từ hợp đồng</label>
                      <div class="input-group block-file">
                        <div class="custom-file">
                          <input type="file" name="contract_document[]" class="custom-file-input" multiple />
                          <label class="custom-file-label">Chọn file</label>
                        </div>
                      </div>
                      <button type="button" class="btn btn-success add-upload-file" type-document="contract_document">
                        Thêm file upload
                      </button>
                      @if(json_decode($co->contract_document, true))
                      <button type="button" class="btn btn-success" id="contract_document_display" data-toggle="modal" data-target="#contract_document_modal" content="{{ $co->contract_document }}">Hiển thị chứng từ đã tồn tại</button>
                      @endif
                    </div>
                    <div class="form-group">
                      <label>Chứng từ hoá đơn</label>
                      <div class="input-group block-file">
                        <div class="custom-file">
                          <input type="file" name="invoice_document[]" class="custom-file-input" multiple />
                          <label class="custom-file-label">Chọn file</label>
                        </div>
                      </div>
                      <button type="button" class="btn btn-success add-upload-file" type-document="invoice_document">
                        Thêm file upload
                      </button>
                      @if(json_decode($co->invoice_document, true))
                      <button type="button" class="btn btn-success" id="invoice_document_display" data-toggle="modal" data-target="#invoice_document_modal" content="{{ $co->invoice_document }}">Hiển thị chứng từ đã tồn tại</button>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="card more-info">
                  <div class="card-header">
                    <h3 class="title">Thông tin khách hàng</h3>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="customer[code]">Mã khách hàng<b style="color: red;"> (*)</b></label>
                          {!! Form::text('customer[code]', null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                      </div>
                      <div class="col-8">
                        <div class="form-group">
                          <label for="customer[ten]">Tên khách hàng<b style="color: red;"> (*)</b></label>
                          {!! Form::text('customer[ten]', null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-8">
                        <div class="form-group">
                          <label for="customer[dia_chi]">Địa chỉ</label>
                          {!! Form::text('customer[dia_chi]', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="customer[mst]">MST</label>
                          {!! Form::text('customer[mst]', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label for="customer[nguoi_nhan]">Người nhận</label>
                          {!! Form::text('customer[nguoi_nhan]', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label for="customer[dien_thoai]">Điện thoại</label>
                          {!! Form::text('customer[dien_thoai]', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label for="customer[email]">Email</label>
                          {!! Form::email('customer[email]', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label for="so_bao_gia">Số báo giá</label>
                          {!! Form::text('so_bao_gia', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
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
                      <div class="col-6">
                        <div class="form-group">
                          <label for="sales">Sales</label>
                          {!! Form::text('sales', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label for="thoi_han_bao_gia">Thời hạn báo giá</label>
                          {!! Form::text('thoi_han_bao_gia', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label for="dong_goi_va_van_chuyen">Đóng gói và vận chuyển</label>
                          {!! Form::text('dong_goi_va_van_chuyen', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label for="noi_giao_hang">Nơi giao hàng</label>
                          {!! Form::text('noi_giao_hang', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label for="xuat_xu">Xuất xứ</label>
                          {!! Form::text('xuat_xu', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label for="thoi_gian_giao_hang">Thời gian giao hàng</label>
                          {!! Form::text('thoi_gian_giao_hang', null, array('class' => 'form-control')) !!}
                        </div>
                      </div>
                    </div>
                    <div class="form-group mt-3">
                      <h5>
                        <p style="width: fit-content; padding: 5px 10px; border-radius: 5px" class="text-danger bg-warning">
                          <b>Giá trị đơn hàng: <span class="money_total"><b></b></span></b>
                        </p>
                      </h5>
                      <div class="table-responsive p-0">
                        <table class="table table-bordered text-nowrap">
                          <thead>
                            <tr class="text-center">
                              <th>&nbsp</th>
                              <th class="align-middle">
                                Trước khi làm hàng
                                @if(isset($receipts[0]))
                                  <a target="_blank" href={{route('admin.receipt.edit', ['id' => $receipts[0]['id']])}}>
                                    @if($receipts[0]['status'] == 1)
                                      <span class="text-info ml-2"><i class="fas fa-file"></i></span>
                                    @endif
                                    @if($receipts[0]['status'] == 2)
                                      <span class="text-green ml-2"><i class="fas fa-check"></i></span>
                                    @endif
                                  </a>
                                @endif
                              </th>
                              <th class="align-middle">
                                Trước khi giao hàng
                                @if(isset($receipts[1]))
                                  <a target="_blank" href={{route('admin.receipt.edit', ['id' => $receipts[1]['id']])}}>
                                    @if($receipts[1]['status'] == 1)
                                      <span class="text-info ml-2"><i class="fas fa-file"></i></span>
                                    @endif
                                    @if($receipts[1]['status'] == 2)
                                      <span class="text-green ml-2"><i class="fas fa-check"></i></span>
                                    @endif
                                  </a>
                                @endif
                              </th>
                              <th class="align-middle">
                                Ngay khi giao hàng
                                @if(isset($receipts[2]))
                                  <a target="_blank" href={{route('admin.receipt.edit', ['id' => $receipts[2]['id']])}}>
                                    @if($receipts[2]['status'] == 1)
                                      <span class="text-info ml-2"><i class="fas fa-file"></i></span>
                                    @endif
                                    @if($receipts[2]['status'] == 2)
                                      <span class="text-green ml-2"><i class="fas fa-check"></i></span>
                                    @endif
                                  </a>
                                @endif
                              </th>
                              <th class="align-middle">
                                Sau khi giao hàng và chứng từ thanh toán
                                @if(isset($receipts[3]))
                                  <a target="_blank" href={{route('admin.receipt.edit', ['id' => $receipts[3]['id']])}}>
                                    @if($receipts[3]['status'] == 1)
                                      <span class="text-info ml-2"><i class="fas fa-file"></i></span>
                                    @endif
                                    @if($receipts[3]['status'] == 2)
                                      <span class="text-green ml-2"><i class="fas fa-check"></i></span>
                                    @endif
                                  </a>
                                @endif
                              </th>
                              <th class="align-middle">Thời gian nợ (ngày)</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td class="text-right" width="20%">% tổng giá trị đơn hàng</td>
                              <td>
                                {!! Form::text('thanh_toan[percent][truoc_khi_lam_hang]', null,
                                array('class' => 'form-control text-center',
                                'onKeyUp' => "return calPaymentPer(this, 'truoc_khi_lam_hang')" )) !!}
                              </td>
                              <td>
                                {!! Form::text('thanh_toan[percent][truoc_khi_giao_hang]', null,
                                array('class' => 'form-control text-center',
                                'onKeyUp' => "return calPaymentPer(this, 'truoc_khi_giao_hang')" )) !!}
                              </td>
                              <td>
                                {!! Form::text('thanh_toan[percent][ngay_khi_giao_hang]', null,
                                array('class' => 'form-control text-center',
                                'onKeyUp' => "return calPaymentPer(this, 'ngay_khi_giao_hang')")) !!}
                              </td>
                              <td>
                                {!! Form::text('thanh_toan[percent][sau_khi_giao_hang_va_cttt]', null,
                                array('class' => 'form-control text-center',
                                'onKeyUp' => "return calPaymentPer(this, 'sau_khi_giao_hang_va_cttt')")) !!}
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
                                {!! Form::text('tmp[amount_money][thoi_gian_no]', $valVnd, array('class' => 'form-control text-center d-none', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                                {!! Form::hidden('thanh_toan[amount_money][thoi_gian_no]', null, array('class' => 'form-control data-origin')) !!}
                              </td>
                            </tr>
                            <tr class="text-center">
                              <td class="text-right">Thời gian xét duyệt</td>
                              <td>
                                @if(isset($receipts[0]))
                                  @if($receipts[0]['status'] == 2)
                                    {{ $receipts[0]['approved_date'] }}
                                  @endif
                                @endif
                              </td>
                              <td>
                                @if(isset($receipts[1]))
                                  @if($receipts[1]['status'] == 2)
                                    {{ $receipts[1]['approved_date'] }}
                                  @endif
                                @endif
                              </td>
                              <td>
                                @if(isset($receipts[2]))
                                  @if($receipts[2]['status'] == 2)
                                    {{ $receipts[2]['approved_date'] }}
                                  @endif
                                @endif
                              </td>
                              <td>
                                @if(isset($receipts[3]))
                                  @if($receipts[3]['status'] == 2)
                                    {{ $receipts[3]['approved_date'] }}
                                  @endif
                                @endif
                              </td>
                              <td>
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
                                    <th style="width: 50%">
                                      Tên chứng từ
                                    </th>
                                    <th style="width: 10%" class="text-center">
                                      Yêu cầu
                                    </th>
                                    <th style="width: 10%" class="text-center">
                                      Hoàn thành
                                    </th>
                                    <th style="width: 30%" class="text-center">
                                      Chứng từ
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
                                      <td class="text-center">
                                        <div class="">
                                          {!! Form::select('thanh_toan[payment_document]['. 'document_id_' .$key .']' , \App\Helpers\DataHelper::documents() , null, array('id' => 'document_id_' .$key, 'class' => 'form-control')) !!}
                                        </div>
                                      </td>
                                    </tr>
                                  @endforeach
                                  </tbody>
                                </table>
                                <div class="float-right mt-3">
                                  @permission('admin.co.store')
                                    @if($co->status == \App\Enums\ProcessStatus::Approved)
                                      {!! Form::hidden('update_thanh_toan' , true , null) !!}
                                      <button type="submit" class="btn btn-primary">Lưu thông tin chứng từ</button>
                                    @endif
                                  @endpermission
                                </div>
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
          @if($existsRel)
          <div class="col-6">
            @permission('admin.co.list-info-co')
              @if(\App\Enums\ProcessStatus::Approved == $co->status)
              <div class="card">
                <div class="card-header">
                  <h3 class="title">Các loại Phiếu CO</h3>
                </div>
                <div class="card-body">
                  @if($co->request->count())
                    @php
                      $moneyData = \App\Helpers\DataHelper::getCategoryPayment(\App\Helpers\DataHelper::DINH_KY);
                      array_push($moneyData, \App\Helpers\DataHelper::KHO.'-chiet_khau');
                    @endphp
                    @foreach($co->request as $key => $requestModel)
                      <div class="table-responsive">
                        <h5><b>Phiếu Yêu Cầu lần {{ $key + 1 }}</b></h5>
                        <table class="table table-bordered text-nowrap">
                          <tbody>
                            <tr>
                              <td>Chứng từ</td>
                              <td>Phiếu Yêu Cầu</td>
                            </tr>
                            <tr>
                              <td>Mã Phiếu Yêu Cầu</td>
                              <td>{{ $requestModel->code }}</td>
                            </tr>
                            <tr>
                              <td>Danh mục</td>
                              <td>{!! isset($categories[$requestModel->category]) ? $categories[$requestModel->category] : '' !!}</td>
                            </tr>
                            <tr>
                              <td>Người thực hiện</td>
                              <td>
                                @if($requestModel->admin)
                                  {{ $requestModel->admin->name }}
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td>Ghi chú</td>
                              <td>{{ $requestModel->note }}</td>
                            </tr>
                            <tr>
                              <td>Chứng từ đi kèm</td>
                              <td>
                                @php
                                  $accompanyingDocument = json_decode($requestModel->accompanying_document, true);
                                @endphp
                                @if($accompanyingDocument)
                                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#accompanying_document_modal_request{{$key}}">Hiển thị chứng từ đã tồn tại</button>
                                @else
                                  Không tồn tại chứng từ
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td>Chứng từ Khảo Sát Giá</td>
                              <td>
                                @php
                                  $accompanyingDocumentSurveyPrice = json_decode($requestModel->accompanying_document_survey_price, true);
                                @endphp
                                @if($accompanyingDocumentSurveyPrice)
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#accompanying_document_survey_price_modal{{$key}}">Hiển thị chứng từ đã tồn tại</button>
                                @else
                                  Không tồn tại chứng từ
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td colspan="2">Nội dung</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="table-responsive" style="margin-top: -1em;">
                        <table class="table table-bordered text-nowrap">
                          <thead>
                            <tr align="center">
                              <th class="align-middle">Số TT</th>
                              <th class="align-middle">Mã HH</th>
                              <th class="align-middle">Mô tả</th>
                              <th class="align-middle">Đ/v tính</th>
                              @if(in_array($requestModel->category, $moneyData))
                                <th class="align-middle">Số tiền</th>
                              @else
                                <th class="align-middle">Số lượng</th>
                              @endif
                              <th class="align-middle">Thời gian cần</th>
                              <th class="align-middle">Ghi chú</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($requestModel->material->count())
                              @foreach($requestModel->material as $index => $material)
                                <tr align="center">
                                  <td>{{ $index + 1 }}</td>
                                  <td>{{ $material->code }}</td>
                                  <td>{{ $material->mo_ta }}</td>
                                  <td>{{ $material->dv_tinh }}</td>
                                  <td>{{ number_format($material->dinh_luong) }}</td>
                                  <td>{{ $material->thoi_gian_can }}</td>
                                  <td>{{ $material->ghi_chu }}</td>
                                </tr>
                              @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>
                      <div class="list-survey-price mt-3">
                        <h5>Khảo Sát Giá</h5>
                        @if($requestModel->surveyPrices->count())
                          @foreach($requestModel->surveyPrices as $indexSP => $surveyPrice)
                            <div class="table-responsive">
                              <table class="table table-bordered text-nowrap">
                                <tbody>
                                  <tr>
                                    <td colspan="2">
                                      <div class="icheck-success">
                                        {!! Form::checkbox('survey_price['.$indexSP.'][is_accept]', true, $surveyPrice->is_accept, array('id' => 'is_accept'.$indexSP, 'disabled' => 'disabled')) !!}
                                        <label for="{{ 'is_accept'.$indexSP }}">Khảo Sát Giá {{ $indexSP + 1 }}</label>
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Chứng từ đi kèm</td>
                                    <td>
                                      @php
                                        $accompanyingDocumentSurveyPrice = json_decode($surveyPrice->accompanying_document, true);
                                      @endphp
                                      @if($surveyPrice->core_price_survey_id)
                                        <a class="btn btn-primary mb-2" href="/admin/price-survey/edit/{{$surveyPrice->core_price_survey_id}}" target="_blank">
                                          Xem chi tiết
                                        </a><br/>
                                      @else
                                        Không tồn tại chứng từ
                                      @endif
                                      @if($accompanyingDocumentSurveyPrice)
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#accompanying_document_survey_price_modal{{ $indexSP }}">Hiển thị chứng từ đã tồn tại</button>
                                      @endif
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Ghi chú</td>
                                    <td>{{ $surveyPrice->note }}</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                            @if($accompanyingDocumentSurveyPrice)
                            <div class="modal fade" id="accompanying_document_survey_price_modal{{ $indexSP }}">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header bg-success">
                                    <h4 class="modal-title">Chứng từ khảo sát giá</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    @foreach($accompanyingDocumentSurveyPrice as $index => $file)
                                      <div class="data-file">
                                        {!! \App\Helpers\AdminHelper::checkFile($file) !!}
                                      </div>
                                    @endforeach
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Đóng</button>
                                  </div>
                                </div>
                                <!-- /.modal-content -->
                              </div>
                              <!-- /.modal-dialog -->
                            </div>
                            @endif
                          @endforeach
                        @endif
                      </div>

                      @if($accompanyingDocument)
                      <div class="modal fade" id="accompanying_document_modal_request{{$key}}">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header bg-success">
                              <h4 class="modal-title">Chứng từ</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              @foreach($accompanyingDocument as $index => $file)
                              <div class="data-file">
                                {!! \App\Helpers\AdminHelper::checkFile($file) !!}
                              </div>
                              @endforeach
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Đóng</button>
                            </div>
                          </div>
                          <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                      </div>
                      @endif
                      @if($accompanyingDocumentSurveyPrice)
                      <div class="modal fade" id="accompanying_document_survey_price_modal{{$key}}">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header bg-success">
                              <h4 class="modal-title">Chứng từ</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              @foreach($accompanyingDocumentSurveyPrice as $index => $file)
                              <div class="data-file">
                                {!! \App\Helpers\AdminHelper::checkFile($file) !!}
                              </div>
                              @endforeach
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Đóng</button>
                            </div>
                          </div>
                          <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                      </div>
                      @endif
                    @endforeach
                  @endif
                  @if($co->payment->count())
                    <hr class="hor">
                    @foreach($co->payment as $key => $payment)
                      <div class="table-responsive bg-payment">
                        <h5><b>Phiếu Chi lần {{ $key + 1 }}</b></h5>
                        <table class="table table-bordered text-nowrap">
                          <tbody>
                            <tr>
                              <td>Chứng từ</td>
                              <td>Phiếu Chi</td>
                            </tr>
                            <tr>
                              <td>Mã Phiếu Chi</td>
                              <td>{{ $payment->code }}</td>
                            </tr>
                            <tr>
                              <td>Mã Phiếu Yêu Cầu</td>
                              <td>{{ (!empty($payment->co->first()) && $payment->co->first()->request->first()) ? $payment->co->first()->request->first()->code : '' }}</td>
                            </tr>
                            <tr>
                              <td>Danh mục</td>
                              <td>{!! isset($categories[$payment->category]) ? $categories[$payment->category] : '' !!}</td>
                            </tr>
                            <tr>
                              <td>Người thực hiện</td>
                              <td>
                                @if($payment->admin)
                                  {{ $payment->admin->name }}
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td>Ghi chú</td>
                              <td>{{ $payment->note }}</td>
                            </tr>
                            <tr>
                              <td>Người nhận</td>
                              <td>{{ $payment->name_receiver }}</td>
                            </tr>
                            <tr>
                              <td>Chứng từ đi kèm</td>
                              <td>
                                @php
                                  $accompanyingDocument = json_decode($payment->accompanying_document, true);
                                @endphp
                                @if($accompanyingDocument)
                                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#accompanying_document_modal_payment{{$key}}">Hiển thị chứng từ đã tồn tại</button>
                                @else
                                  Không tồn tại chứng từ
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td>Số tiền</td>
                              <td>{{ number_format($payment->money_total) }}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      @if($accompanyingDocument)
                      <div class="modal fade" id="accompanying_document_modal_payment{{$key}}">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header bg-success">
                              <h4 class="modal-title">Chứng từ</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              @foreach($accompanyingDocument as $index => $file)
                              <div class="data-file">
                                {!! \App\Helpers\AdminHelper::checkFile($file) !!}
                              </div>
                              @endforeach
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Đóng</button>
                            </div>
                          </div>
                          <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                      </div>
                      @endif
                    @endforeach
                  @endif
                  @if($co->receipt->count())
                    <hr class="hor">
                    @php
                      $paymentMethods = \App\Helpers\DataHelper::getPaymentMethods();
                    @endphp
                    @foreach($co->receipt as $key => $receipt)
                      <div class="table-responsive bg-receipt">
                        <h5><b>Phiếu Thu lần {{ $key + 1 }}</b></h5>
                        <table class="table table-bordered text-nowrap">
                          <tbody>
                            <tr>
                              <td>Chứng từ</td>
                              <td>Phiếu Thu</td>
                            </tr>
                            <tr>
                              <td>Mã Phiếu Thu</td>
                              <td>{{ $receipt->code }}</td>
                            </tr>
                            <tr>
                              <td>Mã Phiếu Chi</td>
                              <td>{{ !empty($receipt->payment) ? $receipt->payment->code : '' }}</td>
                            </tr>
                            <tr>
                              <td>Người thực hiện</td>
                              <td>
                                @if($receipt->admin)
                                  {{ $receipt->admin->name }}
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td>Phương thức thanh toán</td>
                              <td>{!! isset($paymentMethods[$receipt->payment_method]) ? $paymentMethods[$receipt->payment_method] : '' !!}</td>
                            </tr>
                            <tr>
                              <td>Ghi chú</td>
                              <td>{{ $receipt->note }}</td>
                            </tr>
                            <tr>
                              <td>Người nhận</td>
                              <td>{{ $receipt->name_receiver }}</td>
                            </tr>
                            <tr>
                              <td>Chứng từ đi kèm</td>
                              <td>
                                @php
                                  $accompanyingDocument = json_decode($receipt->accompanying_document, true);
                                @endphp
                                @if($accompanyingDocument)
                                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#accompanying_document_modal_receipt{{$key}}">Hiển thị chứng từ đã tồn tại</button>
                                @else
                                  Không tồn tại chứng từ
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td>Tổng tiền</td>
                              <td>{{ number_format($receipt->money_total) }}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      @if($accompanyingDocument)
                      <div class="modal fade" id="accompanying_document_modal_receipt{{$key}}">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header bg-success">
                              <h4 class="modal-title">Chứng từ</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              @foreach($accompanyingDocument as $index => $file)
                              <div class="data-file">
                                {!! \App\Helpers\AdminHelper::checkFile($file) !!}
                              </div>
                              @endforeach
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Đóng</button>
                            </div>
                          </div>
                          <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                      </div>
                      @endif
                    @endforeach
                  @endif
                </div>
              </div>
              <!-- /.card -->
              @endif
            @endpermission
          </div>
          @endif
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-footer text-right">
                @if($co->currentStep)
                  @if(in_array($co->currentStep->step, [
                      \App\Models\CoStepHistory::STEP_CREATE_RECEIPT_N1,
                      \App\Models\CoStepHistory::STEP_CREATE_RECEIPT_N2,
                      \App\Models\CoStepHistory::STEP_CREATE_RECEIPT_N3,
                      \App\Models\CoStepHistory::STEP_CREATE_RECEIPT_N4,
                    ]))
                    @permission('admin.receipt.create')
                      <a href="{{ route('admin.receipt.create', ['type' => 'co', 'id' => $co->id]) }}" class="btn btn-success">Tạo Phiếu Thu</a>
                    @endpermission
                  @endif
                    @if($co->currentStep->step == \App\Models\CoStepHistory::STEP_CREATE_REQUEST)
                      @permission('admin.request.create')
                      @if(\App\Enums\ProcessStatus::Approved == $co->status)
                        <a href="{{ route('admin.request.create', ['coId' => $co->id]) }}" class="btn btn-success">Tạo
                          Phiếu Yêu Cầu</a>
                      @endif
                      @endpermission
                    @endif
                    @if($co->currentStep->step == \App\Models\CoStepHistory::STEP_CREATE_WAREHOUSE_EXPORT_SELL)
                      @permission('admin.warehouse-export-sell.create')
                      @if(\App\Enums\ProcessStatus::Approved == $co->status)
                        <a href="{{ route('admin.warehouse-export-sell.create', ['co_id' => $co->id]) }}" class="btn btn-success">
                          Tạo Phiếu xuất kho bán hàng
                        </a>
                      @endif
                      @endpermission
                    @endif
                    @if($co->currentStep->step == \App\Models\CoStepHistory::STEP_CREATE_WAREHOUSE_EXPORT)
                      @permission('admin.warehouse-export.create')
                      @if(\App\Enums\ProcessStatus::Approved == $co->status)
                        <a href="{{ route('admin.warehouse-export.create', ['co_id' => $co->id]) }}" class="btn btn-success">
                          Tạo Phiếu xuất kho
                        </a>
                      @endif
                      @endpermission
                    @endif
                    @if($co->currentStep->step == \App\Models\CoStepHistory::STEP_CREATE_MANUFACTURE)
                      @permission('admin.manufacture.create')
                        @if(\App\Enums\ProcessStatus::Approved == $co->status && !$co->manufacture)
                          <a href="{{ route('admin.manufacture.create', ['co_id' => $co->id]) }}" class="btn btn-success">Tạo Sản xuất</a>
                        @endif
                      @endpermission
                    @endif
                    @if($co->currentStep->step == \App\Models\CoStepHistory::STEP_CREATE_DELIVERY)
                      @permission('admin.delivery.create')
                      @if(!$co->delivery_id)
                        <a href="{{ route('admin.delivery.create', ['co_id' => $co->id, 'core_customer_id' => $co->core_customer_id]) }}"
                           class="btn btn-success">Tạo Giao Nhận</a>
                      @endif
                      @endpermission
                    @endif
                @endif
                @if($co->status == \App\Enums\ProcessStatus::Pending)
                  <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                @endif
                <a href="{{ route('admin.co.index') }}" class="btn btn-default">Quay lại</a>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        {!! Form::close() !!}
      </div>
      @include('admins.coes.includes.offer-price', ['url' => route('admin.co.get-data-warehouse')])
      @include('admins.includes.approval', [
        'id' => $co->id,
        'type' => 'co',
        'status' => $co->status,
        'check_warehouse' => ($co->currentStep && $co->currentStep->step === \App\Models\CoStepHistory::STEP_CHECKWAREHOUSE) ? true : false
       ])
       <div class="modal fade" id="po_document_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-success">
              <h4 class="modal-title">PO của khách hàng</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Đóng</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <div class="modal fade" id="contract_document_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-success">
              <h4 class="modal-title">Chứng từ hợp đồng</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Đóng</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <div class="modal fade" id="invoice_document_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-success">
              <h4 class="modal-title">Chứng từ hoá đơn</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Đóng</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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
  $( document ).ready(function() {
    bsCustomFileInput.init();
    sumTotal('.warehouse .table tbody');
    $('#delivery_date').datetimepicker({
      format: 'YYYY-MM-DD',
      icons: { time: 'far fa-clock' }
    });
    getHtmlFile('contract_document');
    getHtmlFile('invoice_document');
    getHtmlFile('po_document');

    $('[name="material_type[]"]').change(function () {
      let showData = $(this).parent().find('.show-data-switch');
      if (this.checked) {
        showData.text('Kim loại')
      } else {
        showData.text('Phi kim loại')
      }
    });

    $('#show-table-tmp-co').click(function(e) {
      e.preventDefault();
      $('#card-table-tmp-co').css('display','block')
    });

    $('#hiden-table-tmp-co').click(function(e) {
      e.preventDefault();
      $('#card-table-tmp-co').css('display','none')
    });

    $('#show-table-co').click(function(e) {
      e.preventDefault();
      $('#card-table-co').css('display','block')
    });

    $('#hiden-table-co').click(function(e) {
      e.preventDefault();
      $('#card-table-co').css('display','none')
    });
  });

  function getHtmlFile(field) {
    var contentDocument = $('#'+field+'_display').attr('content');
    if (contentDocument) {
      var data     = JSON.parse(contentDocument);
      var eleModal = $('#'+field+'_modal');
      if (data.length) {
        $.each(data, function( index, value ) {
          var html = '<div class="data-file">' + checkFile(value) + '<div class="mt-2">';
          html += '<button type="button" class="btn btn-danger form-control" type-document="' + field + '" onclick="removeFile(this)" data-path="'+value.path+'">Xoá file</button>';
          html += '</div></div>';
          eleModal.find('.modal-body').append(html);
        });
      } else {
        eleModal.find('.modal-body').append('<p class="text-center">Chưa upload chứng từ.</p>');
      }
    }
  }

  function removeFile(_this) {
    var route = "{{ route('admin.co.remove-file') }}";
    var data = { id: "{{ $co->id }}", path: $(_this).attr('data-path'), type: $(_this).attr('type-document') };
    $.ajax({
      method: "POST",
      url: route,
      data: data
    })
    .done(function( data ) {
      if (data.success) {
        $(_this).parents('.data-file:first').remove();
        alert('Xoá file thành công.');
      } else {
        alert('Xoá file thất bại.');
      }
    });
  }
</script>
@endsection
