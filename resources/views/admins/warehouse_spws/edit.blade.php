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
          {!! Form::model($warehouseSpw, array('route' => ['admin.warehouse-spw.update', $model, $warehouseSpw->l_id], 'method' => 'patch')) !!}
            {!! Form::hidden('id', null) !!}
            <div class="card-body">
              <div class="form-group">
                <label for="code">Mã hàng hoá<b style="color: red;"> (*)</b></label>
                {!! Form::text('code', null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              @switch ($model)
                @case ('filler')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="size">Size</label>
                    {!! Form::number('size', null, array('class' => 'form-control', 'step' => 'any')) !!}
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
                    <label for="sl_kg">SL - Kg</label>
                    {!! Form::number('sl_kg', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('glandpackinglatty')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="size">Size</label>
                    {!! Form::text('size', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_cuon">SL - Cuộn</label>
                    {!! Form::number('sl_cuon', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('hoop')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="size">Size</label>
                    {!! Form::number('size', null, array('class' => 'form-control', 'step' => 'any')) !!}
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
                    <label for="sl_kg">SL - Kg</label>
                    {!! Form::number('sl_kg', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('oring')
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
                @case ('ptfeenvelope')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="do_day">Độ dày</label>
                    {!! Form::number('do_day', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="std">STD</label>
                    {!! Form::text('std', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="size">Size</label>
                    {!! Form::text('size', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="od">OD</label>
                    {!! Form::number('od', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="attr_id">ID</label>
                    {!! Form::number('attr_id', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_cai">SL - Cái</label>
                    {!! Form::number('sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('ptfetape')
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
                @case ('rtj')
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
                @case ('thanhphamswg')
                  <div class="form-group">
                    <label for="inner">Inner</label>
                    {!! Form::text('inner', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="hoop">Hoop</label>
                    {!! Form::text('hoop', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="filler">Filler</label>
                    {!! Form::text('filler', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="outer">Outer</label>
                    {!! Form::text('outer', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="thick">Thick</label>
                    {!! Form::text('thick', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="tieu_chuan">Tiêu chuẩn</label>
                    {!! Form::text('tieu_chuan', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="kich_co">Kích cỡ</label>
                    {!! Form::text('kich_co', null, array('class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_cai">SL - Cái</label>
                    {!! Form::number('sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('vanhtinhinnerswg')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="do_day">Độ dày</label>
                    {!! Form::number('do_day', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="d1">D1</label>
                    {!! Form::number('d1', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="d2">D2</label>
                    {!! Form::number('d2', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_cai">SL - Cái</label>
                    {!! Form::number('sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('vanhtinhouterswg')
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                  <div class="form-group">
                    <label for="do_day">Độ dày</label>
                    {!! Form::number('do_day', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="d3">D3</label>
                    {!! Form::number('d3', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="d4">D4</label>
                    {!! Form::number('d4', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="sl_cai">SL - Cái</label>
                    {!! Form::number('sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
              @endswitch
              <div class="form-group">
                <label for="lot_no">Lot No</label>
                {!! Form::text('lot_no', null, array('class' => 'form-control')) !!}
              </div>
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
                @case ('filler')
                  <div class="form-group">
                    <label for="ton_sl_cuon">Tồn SL - Cuộn</label>
                    {!! Form::number('ton_sl_cuon', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="ton_sl_kg">Tồn SL - Kg</label>
                    {!! Form::number('ton_sl_kg', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('glandpackinglatty')
                  <div class="form-group">
                    <label for="ton_sl_cuon">Tồn SL - Cuộn</label>
                    {!! Form::number('ton_sl_cuon', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('hoop')
                  <div class="form-group">
                    <label for="ton_sl_cuon">Tồn SL - Cuộn</label>
                    {!! Form::number('ton_sl_cuon', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="ton_sl_kg">Tồn SL - Kg</label>
                    {!! Form::number('ton_sl_kg', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('oring')
                  <div class="form-group">
                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                    {!! Form::number('ton_sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('ptfeenvelope')
                  <div class="form-group">
                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                    {!! Form::number('ton_sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('ptfetape')
                  <div class="form-group">
                    <label for="ton_sl_cuon">Tồn SL - Cuộn</label>
                    {!! Form::number('ton_sl_cuon', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  <div class="form-group">
                    <label for="ton_sl_m">Tồn SL - m</label>
                    {!! Form::number('ton_sl_m', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('rtj')
                  <div class="form-group">
                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                    {!! Form::number('ton_sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('thanhphamswg')
                  <div class="form-group">
                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                    {!! Form::number('ton_sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('vanhtinhinnerswg')
                  <div class="form-group">
                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                    {!! Form::number('ton_sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
                @case ('vanhtinhouterswg')
                  <div class="form-group">
                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                    {!! Form::number('ton_sl_cai', null, array('class' => 'form-control', 'step' => 'any')) !!}
                  </div>
                  @break
              @endswitch
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Lưu thông tin</button>
              <a href="{{ route('admin.warehouse-spw.index', ['model' => $model]) }}" class="btn btn-default">Quay lại</a>
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
