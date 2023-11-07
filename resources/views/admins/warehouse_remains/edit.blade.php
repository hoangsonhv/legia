@extends('layouts.admin')

@section('css')
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
          {{-- {{dd($warehouseRemain)}} --}}
          {!! Form::model($warehouseRemain, array('route' => ['admin.warehouse-remain.update', $model, $warehouseRemain->l_id], 'method' => 'patch')) !!}
            {!! Form::hidden('l_id', null) !!}
            <div class="card-body">
              <div class="form-group">
                <label for="code">Mã hàng hoá<b style="color: red;"> (*)</b></label>
                {!! Form::text('code', null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              @switch ($model)
                @case ('ccdc')
                  <div class="form-group">
                    <label for="mo_ta">Mô tả</label>
                    {!! Form::text('mo_ta', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="bo_phan">Bộ phận</label>
                    {!! Form::text('bo_phan', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="dvt">Đơn vị tính</label>
                    {!! Form::text('dvt', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl">Số lượng</label>
                    {!! Form::number('sl', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('daycaosusilicone')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="size">Size</label>
                    {!! Form::text('size', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="m_cuon">m/cuộn</label>
                    {!! Form::number('m_cuon', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_cuon">SL - Cuộn</label>
                    {!! Form::number('sl_cuon', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_m">SL - m</label>
                    {!! Form::number('sl_m', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('dayceramic')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="size">Size</label>
                    {!! Form::text('text', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="m_cuon">m/cuộn</label>
                    {!! Form::number('m_cuon', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_cuon">SL - Cuộn</label>
                    {!! Form::number('sl_cuon', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_m">SL - m</label>
                    {!! Form::number('sl_m', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('glandpacking')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="size">Size</label>
                    {!! Form::text('size', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="trong_luong_cuon">Trọng lượng - Kg/Cuộn</label>
                    {!! Form::number('trong_luong_cuon', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="m_cuon">m/cuộn</label>
                    {!! Form::number('m_cuon', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_cuon">SL - Cuộn</label>
                    {!! Form::number('sl_cuon', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_kg">SL - kg</label>
                    {!! Form::number('sl_kg', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('nhuakythuatcayong')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="size">Size</label>
                    {!! Form::text('size', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="m_cay">m/cây</label>
                    {!! Form::number('m_cay', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_cay">SL - Cây</label>
                    {!! Form::number('sl_cay', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_m">SL - m</label>
                    {!! Form::number('sl_m', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('ongglassepoxy')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="size">Size</label>
                    {!! Form::text('size', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="m_cay">m/cây</label>
                    {!! Form::number('m_cay', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_cay">SL - Cây</label>
                    {!! Form::number('sl_cay', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_m">SL - m</label>
                    {!! Form::number('sl_m', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('phutungdungcu')
                  <div class="form-group">
                    <label for="mo_ta">Mô tả</label>
                    {!! Form::text('mo_ta', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="cho_may_moc_thiet_bi">Cho máy móc, thiết bị</b></label>
                    {!! Form::text('cho_may_moc_thiet_bi', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_cai">SL - Cái</label>
                    {!! Form::number('sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="so_hopdong_hoadon">Số hợp đồng, hoá đơn</label>
                    {!! Form::text('so_hopdong_hoadon', null, array('class' => 'form-control')) !!}
                  </div>
                  @break
                @case ('ptfecayong')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="size">Size</label>
                    {!! Form::text('size', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="m_cay">m/cây</label>
                    {!! Form::number('m_cay', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_cay">SL - Cây</label>
                    {!! Form::number('sl_cay', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_m">SL - m</label>
                    {!! Form::number('sl_m', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('ndloaikhac')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="size">Size</label>
                    {!! Form::text('size', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_cai">SL - Cái</label>
                    {!! Form::number('sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('nkloaikhac')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="size">Size </label>
                    {!! Form::text('size', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_cai">SL - Cái</label>
                    {!! Form::number('sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('tpphikimloai')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="do_day">Độ dày</label>
                    {!! Form::text('text', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="tieu_chuan">Tiêu chuẩn</label>
                    {!! Form::text('tieu_chuan', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="muc_ap_luc">Mức áp lực</label>
                    {!! Form::text('muc_ap_luc', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="kich_co">Kích cỡ</label>
                    {!! Form::text('kich_co', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="kich_thuoc">Kích thước</label>
                    {!! Form::text('kich_thuoc', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="chuan_mat_bich">Chuẩn mặt bích</label>
                    {!! Form::text('chuan_mat_bich', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="chuan_gasket">Chuẩn gasket</label>
                    {!! Form::text('chuan_gasket', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="dvt">Đơn vị tính</label>
                    {!! Form::text('dvt', null, array('class' => 'form-control')) !!}
                  </div>
                  @break
                @case ('tpkimloai')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="do_day">Độ dày</label>
                    {!! Form::text('text', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="tieu_chuan">Tiêu chuẩn</label>
                    {!! Form::text('tieu_chuan', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="muc_ap_luc">Mức áp lực</label>
                    {!! Form::text('muc_ap_luc', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="kich_co">Kích cỡ</label>
                    {!! Form::text('kich_co', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="kich_thuoc">Kích thước</label>
                    {!! Form::text('kich_thuoc', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="chuan_mat_bich">Chuẩn mặt bích</label>
                    {!! Form::text('chuan_mat_bich', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="chuan_gasket">Chuẩn gasket</label>
                    {!! Form::text('chuan_gasket', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="dvt">Đơn vị tính</label>
                    {!! Form::text('dvt', null, array('class' => 'form-control')) !!}
                  </div>
                  @break
              @endswitch
              @if($model !== 'phutungdungcu')
                <div class="form-group">
                  <label for="lot_no">Lot No</label>
                  {!! Form::text('lot_no', null, array('class' => 'form-control')) !!}
                </div>
              @endif
              <div class="form-group">
                <label for="ghi_chu">Ghi Chú</label>
                {!! Form::text('ghi_chu', null, array('class' => 'form-control')) !!}
              </div>
              <div class="form-group">
                <label for="date">Date</label>
                <div class="input-group date" id="warehouse_date" data-target-input="nearest">
                  {!! Form::text('date', null, array('class' => 'form-control datetimepicker-input', 'data-target' => '#warehouse_date')) !!}
                  <div class="input-group-append" data-target="#warehouse_date" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
                </div>
              </div>
              @switch ($model)
                @case ('ccdc')
                  <div class="form-group">
                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                    {!! Form::number('ton_sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('daycaosusilicone')
                  <div class="form-group">
                    <label for="ton_sl_cuon">Tồn SL - Cuộn</label>
                    {!! Form::number('ton_sl_cuon', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="ton_sl_m">Tồn SL - m</label>
                    {!! Form::number('ton_sl_m', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('dayceramic')
                  <div class="form-group">
                    <label for="ton_sl_cuon">Tồn SL - Cuộn</label>
                    {!! Form::number('ton_sl_cuon', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="ton_sl_m">Tồn SL - m</label>
                    {!! Form::number('ton_sl_m', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('glandpacking')
                  <div class="form-group">
                    <label for="ton_sl_cuon">Tồn SL - Cuộn</label>
                    {!! Form::number('ton_sl_cuon', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('nhuakythuatcayong')
                  <div class="form-group">
                    <label for="ton_sl_cay">Tồn SL - Cây</label>
                    {!! Form::number('ton_sl_cay', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="ton_sl_m">Tồn SL - m</label>
                    {!! Form::number('ton_sl_m', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('ongglassepoxy')
                  <div class="form-group">
                    <label for="ton_sl_cay">Tồn SL - Cây</label>
                    {!! Form::number('ton_sl_cay', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="ton_sl_m">Tồn SL - m</label>
                    {!! Form::number('ton_sl_m', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('phutungdungcu')
                  <div class="form-group">
                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                    {!! Form::number('ton_sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('ptfecayong')
                  <div class="form-group">
                    <label for="ton_sl_cay">Tồn SL - Cây</label>
                    {!! Form::number('ton_sl_cay', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="ton_sl_m">Tồn SL - m</label>
                    {!! Form::number('ton_sl_m', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('ndloaikhac')
                  <div class="form-group">
                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                    {!! Form::number('ton_sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('nkloaikhac')
                  <div class="form-group">
                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                    {!! Form::number('ton_sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('tpphikimloai')
                  <div class="form-group">
                    <label for="sl_ton">Tồn SL - Cái</label>
                    {!! Form::number('sl_ton', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('tpkimloai')
                  <div class="form-group">
                    <label for="sl_ton">Tồn SL - Cái</label>
                    {!! Form::number('sl_ton', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
              @endswitch
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Lưu thông tin</button>
              <a href="{{ route('admin.warehouse-remain.index', ['model' => $model]) }}" class="btn btn-default">Quay lại</a>
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
  $( document ).ready(function() {
    $('#warehouse_date').datetimepicker({
      format: 'YYYY-MM-DD',
      icons: { time: 'far fa-clock' }
    });
  });
</script>
@endsection
