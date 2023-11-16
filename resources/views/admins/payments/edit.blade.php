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
        @if($existsCat)
          <h5 class="mb-3 text-danger">Đã tồn tại Phiếu Chi Định Kỳ trong tháng. Vui lòng kiểm tra trước khi xét duyệt.</h5>
        @endif
      </div>
      <div class="col-12">
        <div class="card">
          {!! Form::model($payment, array('route' => ['admin.payment.update', $payment->id], 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
            {!! Form::hidden('id', null) !!}
            <div class="card-body">
              <div class="form-group">
                <label for="request_id">Phiếu Yêu Cầu</label>
                {!! Form::select('request_id', $requests, null, array('class' => 'form-control', 'disabled')) !!}
              </div>
              <div class="form-group">
                <label for="code">Mã Phiếu Chi</label>
                {!! Form::text('code', null, array('class' => 'form-control', 'disabled')) !!}
              </div>
              <div class="form-group">
                <label for="category">Danh mục<b style="color: red;"> (*)</b></label>
                {!! Form::select('category', $categories, null, array('class' => 'form-control','disabled')) !!}
              </div>
              <div class="form-group">
                <label for="note">Ghi chú</label>
                {!! Form::text('note', null, array('class' => 'form-control')) !!}
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
                @if(json_decode($payment->accompanying_document, true))
                <button type="button" class="btn btn-success" id="accompanying_document_display" data-toggle="modal" data-target="#accompanying_document_modal" content="{{ $payment->accompanying_document }}">Hiển thị chứng từ đã tồn tại</button>
                @endif
              </div>
              <div class="form-group">
{{--                <label for="payment_method">Phương thức thanh toán<b style="color: red;"> (*)</b></label>--}}
                {!! Form::hidden('payment_method', null, array('class' => 'form-control', 'id' => "id_payment_method")) !!}
              </div>
              <div class="{{ $payment->payment_method != 2 ? 'form-group' : 'form-group' }}" id="id_bank_id">
                <label for="payment_method">Ngân hàng</label>
                {!! Form::select('bank_id', $banks, null, array('class' => 'form-control')) !!}
              </div>
              <div class="form-group">
                <label for="money_total">Số tiền<b style="color: red;"> (*)</b></label>
                {!! Form::text('tmp_money_total', number_format(old('money_total', $payment->money_total)), array('class' => 'form-control', 'required' => 'required')) !!}
                {!! Form::hidden('money_total', old('money_total', $payment->money_total)) !!}
              </div>
            </div>
            @if ($co !== null)
            <div class="card-body">
              @include('admins.coes.includes.list-products', ['warehouses' => $co, 'collect' => true, 'notAction' => true])
            </div>   
            @endif
            <!-- /.card-body -->
            <div class="card-footer text-right">
              @permission('admin.receipt.create')
              @if(\App\Enums\ProcessStatus::Approved == $payment->status && $payment->co_id)
              <a href="{{ route('admin.receipt.create', ['type' => 'payment', 'id' => $payment->id]) }}" class="btn btn-success">Tạo Phiếu Thu</a>
              @endif
              @endpermission
              <button type="submit" class="btn btn-primary">Lưu Phiếu Chi</button>
              <a href="{{ route('admin.payment.index') }}" class="btn btn-default">Quay lại</a>
            </div>
          {!! Form::close() !!}
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
        @include('admins.includes.approval', ['id' => $payment->id, 'type' => 'payment', 'status' => $payment->status])
        <div class="modal fade" id="accompanying_document_modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-success">
                <h4 class="modal-title">Chứng từ đi kèm</h4>
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
    // Modal
    var contentDocument = $('#accompanying_document_display').attr('content');
    if (contentDocument) {
      var data     = JSON.parse(contentDocument);
      var eleModal = $('#accompanying_document_modal');
      if (data.length) {
        var status = {{ in_array($payment->status, [\App\Enums\ProcessStatus::Approved, \App\Enums\ProcessStatus::Unapproved]) }}
        $.each(data, function( index, value ) {
          var html = '<div class="data-file">' + checkFile(value) + '<div class="mt-2">';
          if (!status) {
            html += '<button type="button" class="btn btn-danger form-control" onclick="removeFile(this)" data-path="'+value.path+'">Xoá file</button>';
          }
          html += '</div></div>';
          eleModal.find('.modal-body').append(html);
        });
      } else {
        eleModal.find('.modal-body').append('<p class="text-center">Chưa upload chứng từ.</p>');
      }
    }
  });
  function removeFile(_this) {
    $.ajax({
      method: "POST",
      url: "{{ route('admin.payment.remove-file') }}",
      data: { id: $('[name=id]').val(), path: $(_this).attr('data-path') }
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
  $('#id_payment_method').change(function(){
    let val = $(this).val();
    if(val == 2) {
      $('#id_bank_id').removeClass('d-none');
    } else {
      $('#id_bank_id').addClass('d-none');
    }
  })
</script>
@endsection
