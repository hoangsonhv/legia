@extends('layouts.admin')

@section('css')
<style type="text/css">
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
          {!! Form::open(array('route' => 'admin.payment.store', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
          {!! Form::hidden('step_id', $payment ? $payment->step_id : null) !!}
            <div class="card-body">
              <div class="form-group">
                <label for="request_id">Phiếu Yêu Cầu</label>
                {!! Form::select('request_id', $requests, null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
              </div>
              <div class="form-group">
                <label for="category">Danh mục<b style="color: red;"> (*)</b></label>
                {!! Form::select('category', $categories, $requestModel->category, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
              </div>
              <div class="form-group">
                <label for="note">Ghi chú</label>
                {!! Form::text('note', $payment ? $payment->note : null, array('class' => 'form-control')) !!}
              </div>
              <div class="form-group">
                <label for="name_receiver">Người nhận<b style="color: red;"> (*)</b></label>
                {!! Form::text('name_receiver', null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              <div class="form-group">
                <label>Chứng từ đi kèm</label>
                <div class="input-group block-file">
                  <div class="custom-file">
                    <input type="file" name="accompanying_document[]" class="custom-file-input" multiple />
                    <label class="custom-file-label">Chọn file</label>
                  </div>
                </div>
                <button type="button" class="btn btn-success" id="add-upload">
                  Thêm file upload
                </button>
              </div>
              <div class="form-group">
                <label for="money_total">Số tiền<b style="color: red;"> (*)</b></label>
                {!! Form::text('tmp_money_total', $payment ? number_format($payment->money_total) : null, array('class' => 'form-control', 'required' => 'required')) !!}
                {!! Form::hidden('money_total', $payment ? $payment->money_total : null) !!}
              </div>
              <div class="form-group">
{{--                <label for="payment_method">Phương thức thanh toán<b style="color: red;"> (*)</b></label>--}}
                {!! Form::hidden('payment_method', 2, array('class' => 'form-control', 'id' => 'id_payment_method')) !!}
              </div>
              <div class="form-group" id="id_bank_id">
                <label for="payment_method">Ngân hàng<b style="color: red;"> (*)</b></label>
                {!! Form::select('bank_id', $banks, null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Lưu thông tin</button>
              <a href="{{ route('admin.payment.index') }}" class="btn btn-default">Quay lại</a>
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
<script type="text/javascript" src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/admin/payments.js') }}"></script>
<script type="text/javascript">
  $( document ).ready(function() {
    // Init data
    bsCustomFileInput.init();

    $('#id_payment_method').change(function(){
      let val = $(this).val();
      if(val == 2) {
        $('#id_bank_id').removeClass('d-none');
      } else {
        $('#id_bank_id').addClass('d-none');
      }
    })
  });
</script>
@endsection
