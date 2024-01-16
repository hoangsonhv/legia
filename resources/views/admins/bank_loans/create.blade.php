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
          {!! Form::open(array('route' => 'admin.bank-loans.store', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
            <div class="card-body">
              <div class="form-group">
                <label for="name_bank">Ngân hàng<b style="color: red;"> (*)</b></label>
                {!! Form::select('bank_id', $banks, null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              <div class="form-group">
                <label for="name_bank">Nội dung vay<b style="color: red;"> (*)</b></label>
                {!! Form::text('lead', null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              <div class="form-group">
                <label for="name_bank">Mã khế ước<b style="color: red;"> (*)</b></label>
                {!! Form::text('code', null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              <div class="form-group">
                <label for="name_bank">Nội dung chi tiết</label>
                {!! Form::text('content', null, array('class' => 'form-control')) !!}
              </div>
              <div class="row">
                <div class="col-sm-12 col-xl-4">
                  <div class="form-group">
                    <label for="date">Ngày vay<b style="color: red;"> (*)</b></label>
                    <div class="input-group" id="date" data-target-input="nearest">
                      {!! Form::text('date', date('Y-m-d'), array('class' => 'form-control datetimepicker-input',
                        'data-target' => '#date', 'required' => 'required')) !!}
                      <div class="input-group-append" data-target="#date"
                           data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12 col-xl-4">
                  <div class="form-group">
                    <label for="date">Ngày đáo hạn<b style="color: red;"> (*)</b></label>
                    <div class="input-group" id="date_due" data-target-input="nearest">
                      {!! Form::text('date_due', null, array('class' => 'form-control datetimepicker-input',
                        'data-target' => '#date_due', 'required' => 'required')) !!}
                      <div class="input-group-append" data-target="#date_due"
                           data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12 col-xl-4">
                  <div class="form-group">
                    <label for="date">Ngày trả hàng tháng<b style="color: red;"> (*)</b></label>
                    <div class="input-group">
                      {!! Form::number('date_pay', 15, array('class' => 'form-control', 'min' => 1, 'max' => 31,
                        'required' => 'required')) !!}
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 col-xl-8">
                  <div class="form-group">
                    <label for="name_bank">Số tiền vay<b style="color: red;"> (*)</b></label>
                    {!! Form::text('tmp_amount_money', null, array('class' => 'form-control', 'required' => 'required')) !!}
                    {!! Form::hidden('amount_money', null) !!}
                  </div>
                </div>
             
                <div class="col-sm-12 col-xl-4">
                  <div class="form-group">
                    <label for="name_bank">Lãi (%)<b style="color: red;"> (*)</b></label>
                    {!! Form::number('profit_amount', null, array('class' => 'form-control', 'step' => '0.1', 'required' => 'required')) !!}
                  </div>
                </div>
                <div class="col-sm-12 col-xl-12">
                  <div class="form-group">
                    <label for="name_bank">Hình thức vay<b style="color: red;"> (*)</b></label>
                    {!! Form::select('loan_type', [0 => 'Ngắn hạn', 1 => 'Trung hạn', 2 => 'Dài hạn'], null, array('class' => 'form-control', 'required' => 'required')) !!}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label for="name_bank">Ghi chú</label>
                    {!! Form::textarea('note', null, array('class' => 'form-control', 'rows' => 3)) !!}
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Lưu thông tin</button>
              <a href="{{ route('admin.bank-loans.index') }}" class="btn btn-default">Quay lại</a>
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
      $('#date').datetimepicker({
        format: 'YYYY-MM-DD',
        icons: { time: 'far fa-clock' }
      });
      $('#date_due').datetimepicker({
        format: 'YYYY-MM-DD',
        icons: { time: 'far fa-clock' }
      });
      $('[name="tmp_amount_money"]').keyup(function(){
        var data = formatCurrent(this.value);
        $(this).val(data.format);
        $('[name="amount_money"]').val(data.original);
      });
    });
  </script>
@endsection