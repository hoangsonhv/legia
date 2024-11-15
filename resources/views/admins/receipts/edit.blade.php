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
          {!! Form::model($receipt, array('route' => ['admin.receipt.update', $receipt->id], 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
            {!! Form::hidden('id', null) !!}
            {!! Form::hidden('step_id', $receipt ? $receipt->step_id : null) !!}
            <div class="card-body">
              <div class="form-group">
                @switch($type)
                  @case('co')
                    <label for="co_id">CO</label>
                    {!! Form::select('co_id', $coes, null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                    @break
                  @case('payment')
                    <label for="payment_id">Phiếu Chi</label>
                    {!! Form::select('payment_id', $coes, null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                    @break
                @endswitch
              </div>
              <div class="form-group">
                <label for="code">Mã Phiếu Thu</label>
                {!! Form::text('code', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
              </div>
              <div class="form-group">
{{--                <label for="payment_method">Phương thức thanh toán<b style="color: red;"> (*)</b></label>--}}
                {!! Form::hidden('payment_method', null, array('class' => 'form-control', 'id' => "id_payment_method")) !!}
              </div>
              <div class="{{ $receipt->payment_method != 2 ? 'form-group' : 'form-group' }}" id="id_bank_id">
                <label for="payment_method">Ngân hàng</label>
                {!! Form::select('bank_id', $banks, null, array('class' => 'form-control')) !!}
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
                @if(json_decode($receipt->accompanying_document, true))
                <button type="button" class="btn btn-success" id="accompanying_document_display" data-toggle="modal" data-target="#accompanying_document_modal" content="{{ $receipt->accompanying_document }}">Hiển thị chứng từ đã tồn tại</button>
                @endif
              </div>
              <div class="form-group">
                <label for="money_total">Tổng tiền<b style="color: red;"> (*)</b></label>
                {!! Form::text('tmp_money_total', number_format(old('actual_money', $receipt->actual_money)), array('class' => 'form-control', 'required' => 'required')) !!}
                {!! Form::hidden('money_total', old('money_total', $receipt->money_total)) !!}
              </div>
            </div>
            @if ($type == 'co')
            <div class="card-body">
              <div class="form-group mt-3">
                <h5>
                  <p style="width: fit-content; padding: 5px 10px; border-radius: 5px; font-size:1rem;" class="text-danger bg-warning">
                    <b>Giá trị đơn hàng: <span class="money_total"><b>{{ number_format($co->tong_gia + $co->vat) }}</b></span></b>
                  </p>
                </h5>
                <div class="table-responsive p-0">
                  <table class="table table-bordered text-wrap">
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
                        <th class="align-middle">
                          Phiếu thu thêm (còn nợ)
                          @if(isset($receipts[4]))
                            <a target="_blank" href={{route('admin.receipt.edit', ['id' => $receipts[4]['id']])}}>
                              @if($receipts[4]['status'] == 1)
                                <span class="text-info ml-2"><i class="fas fa-file"></i></span>
                              @endif
                              @if($receipts[4]['status'] == 2)
                                <span class="text-green ml-2"><i class="fas fa-check"></i></span>
                              @endif
                            </a>
                          @endif
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="text-center">
                        <td class="text-right" width="20%">% tổng giá trị đơn hàng</td>
                        <td>
                          {{ $thanhToan['percent']['truoc_khi_lam_hang'] }}
                        </td>
                        <td>
                          {{ $thanhToan['percent']['truoc_khi_giao_hang'] }}
                        </td>
                        <td>
                          {{ $thanhToan['percent']['ngay_khi_giao_hang'] }}
                        </td>
                        <td>
                          {{ $thanhToan['percent']['sau_khi_giao_hang_va_cttt'] }}
                        </td>
                        <td>
                          {{-- {{ $receipts[4]['money_total'] }} --}}
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
                          {{ $valVnd }}
                        </td>
                        <td>
                          @php
                            $valVnd = number_format(old('thanh_toan[amount_money][truoc_khi_giao_hang]', $co->thanh_toan['amount_money']['truoc_khi_giao_hang']));
                            if (!$valVnd) {
                              $valVnd = null;
                            }
                          @endphp
                          {{ $valVnd }}
                        </td>
                        <td>
                          @php
                            $valVnd = number_format(old('thanh_toan[amount_money][ngay_khi_giao_hang]', $co->thanh_toan['amount_money']['ngay_khi_giao_hang']));
                            if (!$valVnd) {
                              $valVnd = null;
                            }
                          @endphp
                         {{ $valVnd }}
                        </td>
                        <td>
                          @php
                            $valVnd = number_format(old('thanh_toan[amount_money][sau_khi_giao_hang_va_cttt]', $co->thanh_toan['amount_money']['sau_khi_giao_hang_va_cttt']));
                            if (!$valVnd) {
                              $valVnd = null;
                            }
                          @endphp
                         {{ $valVnd }}
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="text-right">Giá trị thực thu - VNĐ</td>
                        <td>
                          @php
                            $valVnd =  isset($receipts[0]) ? number_format($receipts[0]['actual_money']) : null;
                          @endphp
                          {{ $valVnd }}
                        </td>
                        <td>
                          @php
                            $valVnd =  isset($receipts[1]) ? number_format($receipts[1]['actual_money']) : null;

                          @endphp
                          {{ $valVnd }}
                        </td>
                        <td>
                          @php
                            $valVnd =  isset($receipts[2]) ? number_format($receipts[2]['actual_money']) : null;
                          @endphp
                         {{ $valVnd }}
                        </td>
                        <td>
                          @php
                            $valVnd =  isset($receipts[3]) ? number_format($receipts[3]['actual_money']) : null;
                          @endphp
                          {{ $valVnd }}
                        </td>
                        <td>
                          @php
                            $valVnd =  isset($receipts[4]) ? number_format($receipts[4]['actual_money']) : null;
                          @endphp
                          {{ $valVnd }}
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="text-right">Giá trị còn nợ - VNĐ</td>
                        <td>
                          @php
                            $valVnd =  isset($receipts[0]) ? number_format($receipts[0]['debt_money']) : null;
                          @endphp
                          {{ $valVnd }}
                        </td>
                        <td>
                          @php
                            $valVnd =  isset($receipts[1]) ? number_format($receipts[1]['debt_money']) : null;

                          @endphp
                          {{ $valVnd }}
                        </td>
                        <td>
                          @php
                            $valVnd =  isset($receipts[2]) ? number_format($receipts[2]['debt_money']) : null;
                          @endphp
                         {{ $valVnd }}
                        </td>
                        <td>
                          @php
                            $valVnd =  isset($receipts[3]) ? number_format($receipts[3]['debt_money']) : null;
                          @endphp
                          {{ $valVnd }}
                        </td>
                        <td>
                          @php
                            $valVnd =  isset($receipts[4]) ? number_format($receipts[4]['debt_money']) : null;
                          @endphp
                          {{ $valVnd }}
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
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            @endif
            <!-- /.card-body -->
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Lưu Phiếu Thu</button>
              <a href="{{ route('admin.receipt.index') }}" class="btn btn-default">Quay lại</a>
            </div>
          {!! Form::close() !!}
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
        @if(str_contains($co->currentStep, 'waiting_approve')) 
          @include('admins.includes.approval', ['id' => $receipt->id, 'type' => 'receipt', 'status' => $receipt->status])
        @endif
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
              {!! Form::close() !!}
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
<script type="text/javascript" src="{{ asset('js/admin/receipts.js') }}"></script>
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
        var status = {{ in_array($receipt->status, [\App\Enums\ProcessStatus::Approved, \App\Enums\ProcessStatus::Unapproved]) }}
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
      url: "{{ route('admin.receipt.remove-file') }}",
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
