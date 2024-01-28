{!! Form::model($requestModel, array('route' => ['admin.request.update', $requestModel->id], 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
{!! Form::hidden('id', null) !!}
{!! Form::hidden('is_request_payment', true) !!}
{!! Form::hidden('category', $requestModel->category) !!}
@php
    $hide = !empty($hidePercentPayment) && $hidePercentPayment;
@endphp
<div class="card-body">
    <div class="form-group">
        <div class="row mb-3">
            <div class="d-flex align-items-center">
                <div>
                    <label for="thanh_toan">
                        Tổng thanh toán
                    </label>
                </div>
                <div class="ml-2">
                    {!! Form::text('tmp_money_total', $requestModel && $requestModel->money_total != null ? number_format($requestModel->money_total) : number_format($totalPayment), array('class' => 'form-control', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                    {!! Form::hidden('money_total', $requestModel && $requestModel->money_total != null ? $requestModel->money_total : $totalPayment, array('class' => 'data-origin')) !!}
                </div>
            </div>
        </div>
        {{--    <p class="text-danger">Giá trị đơn hàng: <span class="money_total"><b></b></span></p>--}}
        <div class="table-responsive p-0">
            <table class="table table-bordered text-wrap">
                <thead>
                <tr class="text-center {{ $hide ? 'd-none' : ''}}">
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
                    <th class="align-middle">Thời gian nợ (ngày)</th>
                </tr>
                </thead>
                <tbody>
                <tr class="{{ $hide ? 'd-none' : ''}}">
                    <td class="text-right" width="20%">% tổng giá trị đơn hàng</td>
                    <td>
                        {!! Form::text('thanh_toan[percent][truoc_khi_lam_hang]', null,
                        array('class' => 'form-control text-center',
                        'onKeyUp' => "return calPaymentPer(this, 'truoc_khi_lam_hang')" )) !!}
                    </td>
                    <td>
                        {!! Form::text('thanh_toan[percent][truoc_khi_giao_hang]', null,
                        array('class' => 'form-control text-center',
                        'onKeyUp' => "return calPaymentPer(this, 'truoc_khi_giao_hang')" )) !!}
                    </td>
                    <td>
                        {!! Form::text('thanh_toan[percent][ngay_khi_giao_hang]', null,
                        array('class' => 'form-control text-center',
                        'onKeyUp' => "return calPaymentPer(this, 'ngay_khi_giao_hang')")) !!}
                    </td>
                    <td>
                        {!! Form::text('thanh_toan[percent][sau_khi_giao_hang_va_cttt]', null,
                        array('class' => 'form-control text-center',
                        'onKeyUp' => "return calPaymentPer(this, 'sau_khi_giao_hang_va_cttt')")) !!}
                    </td>
                    <td>
                        {!! Form::text('thanh_toan[percent][thoi_gian_no]', null, array('class' => 'form-control text-center')) !!}
                    </td>
                </tr>
                <tr class="text-center {{ $hide ? 'd-none' : ''}}">
                    <td class="text-right">Giá trị - VNĐ</td>
                    <td>
                        @php
                            $valVnd = number_format(old('thanh_toan[amount_money][truoc_khi_lam_hang]',
                                $requestModel->thanh_toan ? $requestModel->thanh_toan['amount_money']['truoc_khi_lam_hang'] : null));
                            if (!$valVnd) {
                              $valVnd = null;
                            }
                        @endphp
                        {!! Form::text('tmp[amount_money][truoc_khi_lam_hang]', $valVnd, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                        {!! Form::hidden('thanh_toan[amount_money][truoc_khi_lam_hang]', null, array('class' => 'form-control data-origin')) !!}
                    </td>
                    <td>
                        @php
                            $valVnd = number_format(old('thanh_toan[amount_money][truoc_khi_giao_hang]',
                                $requestModel->thanh_toan ? $requestModel->thanh_toan['amount_money']['truoc_khi_giao_hang'] : null));
                            if (!$valVnd) {
                              $valVnd = null;
                            }
                        @endphp
                        {!! Form::text('tmp[amount_money][truoc_khi_giao_hang]', $valVnd, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                        {!! Form::hidden('thanh_toan[amount_money][truoc_khi_giao_hang]', null, array('class' => 'form-control data-origin')) !!}
                    </td>
                    <td>
                        @php
                            $valVnd = number_format(old('thanh_toan[amount_money][ngay_khi_giao_hang]',
                                $requestModel->thanh_toan ? $requestModel->thanh_toan['amount_money']['ngay_khi_giao_hang'] : null));
                            if (!$valVnd) {
                              $valVnd = null;
                            }
                        @endphp
                        {!! Form::text('tmp[amount_money][ngay_khi_giao_hang]', $valVnd, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                        {!! Form::hidden('thanh_toan[amount_money][ngay_khi_giao_hang]', null, array('class' => 'form-control data-origin')) !!}
                    </td>
                    <td>
                        @php
                            $valVnd = number_format(old('thanh_toan[amount_money][sau_khi_giao_hang_va_cttt]',
                                $requestModel->thanh_toan ? $requestModel->thanh_toan['amount_money']['sau_khi_giao_hang_va_cttt'] : null));
                            if (!$valVnd) {
                              $valVnd = null;
                            }
                        @endphp
                        {!! Form::text('tmp[amount_money][sau_khi_giao_hang_va_cttt]', $valVnd, array('class' => 'form-control text-center', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                        {!! Form::hidden('thanh_toan[amount_money][sau_khi_giao_hang_va_cttt]', null, array('class' => 'form-control data-origin')) !!}
                    </td>
                    <td>
                        @php
                            $valVnd = number_format(old('thanh_toan[amount_money][thoi_gian_no]',
                                $requestModel->thanh_toan ? $requestModel->thanh_toan['amount_money']['thoi_gian_no'] : null));
                            if (!$valVnd) {
                              $valVnd = null;
                            }
                        @endphp
                        {!! Form::text('tmp[amount_money][thoi_gian_no]', $valVnd, array('class' => 'form-control text-center d-none', 'onKeyUp' => 'return getNumberFormat(this)')) !!}
                        {!! Form::hidden('thanh_toan[amount_money][thoi_gian_no]', null, array('class' => 'form-control data-origin')) !!}
                    </td>
                </tr>
                <tr>
                    <td class="text-right">Bộ chứng từ thanh toán</td>
                    <td colspan="6">
                        @php
                            $paymentDocuments = App\Services\CoService::paymentDocuments();
                        @endphp
                        <table>
                            <thead>
                            <tr>
                                <th style="width: 50%">
                                    Tên chứng từ
                                </th>
                                <th style="width: 10%" class="text-center">
                                    Yêu cầu
                                </th>
                                <th style="width: 10%" class="text-center">
                                    Hoàn thành
                                </th>
                                <th style="width: 40%" class="text-center">
                                    Tệp đính kèm
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($paymentDocuments as $key => $doc)
                                <tr>
                                    <td>
                                        {{$doc}}
                                    </td>
                                    <td class="text-center">
                                        <div class="icheck-success">
                                            {!! Form::checkbox('thanh_toan[payment_document]['. 'required_' . $key .']' , true, null, array('id' => 'required_' . $key)) !!}
                                            <label for={{'required_' . $key}}></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="icheck-success">
                                            {!! Form::checkbox('thanh_toan[payment_document]['. 'finished_' .$key .']' , true, null, array('id' => 'finished_' .$key)) !!}
                                            <label for={{'finished_' .$key}}></label>
                                        </div>
                                    </td>
                                    <td>
                                        @if(isset($requestModel->thanh_toan['payment_document']['file_' .$key]) && $requestModel->thanh_toan['payment_document']['file_' .$key])
                                            <button
                                                    type="button"
                                                    class="btn btn-success"
                                                    data-toggle="modal"
                                                    data-target={{"#request_payment_document_file_".$key}}
                                                    content="{{json_encode($requestModel->thanh_toan['payment_document']['file_' .$key])}}"
                                            >
                                                Hiển thị chứng từ đã tồn tại
                                            </button>
                                            <div class="modal fade" id="request_payment_document_file_{{ $key }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success">
                                                            <h4 class="modal-title">Chứng từ khảo sát giá</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @foreach($requestModel->thanh_toan['payment_document']['file_' .$key] as $index => $file)
                                                                <div class="data-file">
                                                                    {!! \App\Helpers\AdminHelper::checkFile($file) !!}
                                                                    <div class="mt-2">
                                                                        <button
                                                                            type="button"
                                                                            class="btn btn-danger form-control"
                                                                            onclick="removeFilePaymentDoc(this)"
                                                                            data-path="{{ $file['path'] }}"
                                                                            data-key="{{ 'file_' .$key }}"
                                                                        >
                                                                            Xoá file
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Đóng</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="input-group block-file">
                                                <div class="custom-file">
                                                    <input class="custom-file-input" type="file" name={{'thanh_toan[payment_document][' . 'file_' .$key .'][]'}} />
                                                    <label class="custom-file-label">Chọn file</label>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="float-right">
                                <button type="submit" class="btn btn-primary">Lưu thông tin thanh toán</button>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{!! Form::close() !!}