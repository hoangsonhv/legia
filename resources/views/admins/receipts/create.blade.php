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
          {!! Form::open(array('route' => 'admin.receipt.store', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
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
{{--                <label for="payment_method">Phương thức thanh toán<b style="color: red;"> (*)</b></label>--}}
                {!! Form::hidden('payment_method', 2, array('class' => 'form-control', 'id' => 'id_payment_method')) !!}
              </div>
              <div class="form-group" id="id_bank_id">
                <label for="payment_method">Ngân hàng<b style="color: red;"> (*)</b></label>
                {!! Form::select('bank_id', $banks, null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              <div class="form-group">
                <label for="note">Ghi chú</label>
                {!! Form::text('note', $receipt ? $receipt->note : null, array('class' => 'form-control')) !!}
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
                <label for="money_total">Tổng tiền<b style="color: red;"> (*)</b></label>
                {!! Form::text('tmp_money_total', $receipt ? number_format($receipt->money_total) : null , array('class' => 'form-control', 'required' => 'required')) !!}
                {!! Form::hidden('money_total', $receipt ? $receipt->money_total : null) !!}
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
              <button type="submit" class="btn btn-primary">Lưu thông tin</button>
              <a href="{{ route('admin.receipt.index') }}" class="btn btn-default">Quay lại</a>
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
<script type="text/javascript" src="{{ asset('js/admin/receipts.js') }}"></script>
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
