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
          {!! Form::model($warehouseRemain, array('route' => ['admin.warehouse-supply.update', $model, $warehouseRemain->l_id], 'method' => 'patch')) !!}
            {!! Form::hidden('l_id', null) !!}
            <div class="card-body">
              <div class="form-group">
                <label for="code">Mã hàng hoá<b style="color: red;"> (*)</b></label>
                {!! Form::text('code', null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              @switch ($model)
                @case ('supply')
                  <div class="form-group">
                    <label for="mo_ta">Mô tả</label>
                    {!! Form::text('mo_ta', null, array('class' => 'form-control')) !!}
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
                @case ('supply')
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
              <a href="{{ route('admin.warehouse-supply.index', ['model' => $model]) }}" class="btn btn-default">Quay lại</a>
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
