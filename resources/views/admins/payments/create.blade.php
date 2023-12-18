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
                <label for="name_receiver">Người chi<b style="color: red;"> (*)</b></label>
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
            @if ($co !== null)
              <div class="card-body">
                <div class="form-group mt-3">
                  <h5>
                    <p style="width: fit-content; padding: 5px 10px; border-radius: 5px; font-size:1rem;" class="text-danger bg-warning">
                      <b>Giá trị đơn hàng: <span class="money_total"><b>{{ number_format($requestModel->money_total) }}</b></span></b>
                    </p>
                  </h5>
                  <div class="table-responsive p-0">
                    <table class="table table-bordered text-wrap">
                        <thead>
                        <tr class="text-center">
                            <th>&nbsp</th>
                            <th class="align-middle">
                                Trước khi làm hàng
                                @if(isset($payments[0]))
                                    <a target="_blank" href={{route('admin.payment.edit', ['id' => $payments[0]['id']])}}>
                                        @if($payments[0]['status'] == 1)
                                            <span class="text-info ml-2"><i class="fas fa-file"></i></span>
                                        @endif
                                        @if($payments[0]['status'] == 2)
                                            <span class="text-green ml-2"><i class="fas fa-check"></i></span>
                                        @endif
                                    </a>
                                @endif
                            </th>
                            <th class="align-middle">
                                Trước khi giao hàng
                                @if(isset($payments[1]))
                                    <a target="_blank" href={{route('admin.payment.edit', ['id' => $payments[1]['id']])}}>
                                        @if($payments[1]['status'] == 1)
                                            <span class="text-info ml-2"><i class="fas fa-file"></i></span>
                                        @endif
                                        @if($payments[1]['status'] == 2)
                                            <span class="text-green ml-2"><i class="fas fa-check"></i></span>
                                        @endif
                                    </a>
                                @endif
                            </th>
                            <th class="align-middle">
                                Ngay khi giao hàng
                                @if(isset($payments[2]))
                                    <a target="_blank" href={{route('admin.payment.edit', ['id' => $payments[2]['id']])}}>
                                        @if($payments[2]['status'] == 1)
                                            <span class="text-info ml-2"><i class="fas fa-file"></i></span>
                                        @endif
                                        @if($payments[2]['status'] == 2)
                                            <span class="text-green ml-2"><i class="fas fa-check"></i></span>
                                        @endif
                                    </a>
                                @endif
                            </th>
                            <th class="align-middle">
                                Sau khi giao hàng và chứng từ thanh toán
                                @if(isset($payments[3]))
                                    <a target="_blank" href={{route('admin.payment.edit', ['id' => $payments[3]['id']])}}>
                                        @if($payments[3]['status'] == 1)
                                            <span class="text-info ml-2"><i class="fas fa-file"></i></span>
                                        @endif
                                        @if($payments[3]['status'] == 2)
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
                                    $valVnd = number_format(old('thanh_toan[amount_money][truoc_khi_lam_hang]',
                                        $requestModel->thanh_toan ? $requestModel->thanh_toan['amount_money']['truoc_khi_lam_hang'] : null));
                                    if (!$valVnd) {
                                      $valVnd = null;
                                    }
                                @endphp
                                {{ $valVnd }}
                            </td>
                            <td>
                                @php
                                    $valVnd = number_format(old('thanh_toan[amount_money][truoc_khi_giao_hang]',
                                        $requestModel->thanh_toan ? $requestModel->thanh_toan['amount_money']['truoc_khi_giao_hang'] : null));
                                    if (!$valVnd) {
                                      $valVnd = null;
                                    }
                                @endphp
                                {{ $valVnd }}
                            </td>
                            <td>
                                @php
                                    $valVnd = number_format(old('thanh_toan[amount_money][ngay_khi_giao_hang]',
                                        $requestModel->thanh_toan ? $requestModel->thanh_toan['amount_money']['ngay_khi_giao_hang'] : null));
                                    if (!$valVnd) {
                                      $valVnd = null;
                                    }
                                @endphp
                                {{ $valVnd }}
                            </td>
                            <td>
                                @php
                                    $valVnd = number_format(old('thanh_toan[amount_money][sau_khi_giao_hang_va_cttt]',
                                        $requestModel->thanh_toan ? $requestModel->thanh_toan['amount_money']['sau_khi_giao_hang_va_cttt'] : null));
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
                            @if(isset($payments[0]))
                              @if($payments[0]['status'] == 2)
                                {{ $payments[0]['approved_date'] }}
                              @endif
                            @endif
                          </td>
                          <td>
                            @if(isset($payments[1]))
                              @if($payments[1]['status'] == 2)
                                {{ $payments[1]['approved_date'] }}
                              @endif
                            @endif
                          </td>
                          <td>
                            @if(isset($payments[2]))
                              @if($payments[2]['status'] == 2)
                                {{ $payments[2]['approved_date'] }}
                              @endif
                            @endif
                          </td>
                          <td>
                            @if(isset($payments[3]))
                              @if($payments[3]['status'] == 2)
                                {{ $payments[3]['approved_date'] }}
                              @endif
                            @endif
                          </td>
                        </tr>
                        </tbody>
                    </table>
                  </div>
                </div>
            </div>
            <div class="card-body">
              <h3 class="title text-primary">Nội dung</h3>
              @include('admins.payments.includes.list-materials', ['materials' => $requestModel->material])
            </div> 
            @endif
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
