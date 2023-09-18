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
          {!! Form::model($warehousePlate, array('route' => ['admin.warehouse-plate.update', $model, $warehousePlate->id], 'method' => 'patch')) !!}
            {!! Form::hidden('id', null) !!}
            <div class="card-body">
              <div class="row">
                <div class="col-3">
                  <div class="form-group">
                    <label for="code">Mã hàng hoá<b style="color: red;"> (*)</b></label>
                    {!! Form::text('code', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                </div>
                <div class="col-9">
                  <div class="form-group">
                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                    {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="do_day">Độ dày</label>
                    {!! Form::text('do_day', null, array('class' => 'form-control')) !!}
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="hinh_dang">Hình dạng</label>
                    {!! Form::text('hinh_dang', null, array('class' => 'form-control')) !!}
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="dia_w_w1">Dia W W1</label>
                    {!! Form::text('dia_w_w1', null, array('class' => 'form-control')) !!}
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="l_l1">L L1</label>
                    {!! Form::text('l_l1', null, array('class' => 'form-control')) !!}
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="w2">W2</label>
                    {!! Form::text('w2', null, array('class' => 'form-control')) !!}
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="l2">L2</label>
                    {!! Form::text('l2', null, array('class' => 'form-control')) !!}
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="sl_tam">SL - Tấm</label>
                    {!! Form::text('sl_tam', null, array('class' => 'form-control')) !!}
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="sl_m2">SL - m2</label>
                    {!! Form::text('sl_m2', null, array('class' => 'form-control')) !!}
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="lot_no">Lot No</label>
                    {!! Form::text('lot_no', null, array('class' => 'form-control')) !!}
                  </div>
                </div>
                <div class="col-9">
                  <div class="form-group">
                    <label for="ghi_chu">Ghi Chú</label>
                    {!! Form::text('ghi_chu', null, array('class' => 'form-control')) !!}
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="date">Date</label>
                    <div class="input-group date" id="warehouse_date" data-target-input="nearest">
                      {!! Form::text('date', null, array('class' => 'form-control datetimepicker-input', 'data-target' => '#warehouse_date')) !!}
                      <div class="input-group-append" data-target="#warehouse_date" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="ton_sl_tam">Tồn SL - Tấm</label>
                    {!! Form::text('ton_sl_tam', null, array('class' => 'form-control')) !!}
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="ton_sl_m2">Tồn SL - m2</label>
                    {!! Form::text('ton_sl_m2', null, array('class' => 'form-control')) !!}
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Lưu thông tin</button>
              <a href="{{ route('admin.warehouse-plate.index', ['model' => $model]) }}" class="btn btn-default">Quay lại</a>
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
