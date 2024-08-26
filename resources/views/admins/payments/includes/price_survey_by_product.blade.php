<table class="w-100 bg-white table table-content table-head-fixed table-bordered table-hover">
    <thead>
    <tr align="center" style="font-weight: bold">
        <td class="align-middle">Nhà cung cấp</td>
        {{-- <td class="align-middle">IMPO/DOME</td>
        <td class="align-middle">Nhóm sản phẩm</td> --}}
        <td class="align-middle">Deadline cần hàng</td>
        <td class="align-middle">File đính kèm</td>
        <td class="align-middle">Giá trị (VAT)</td>
        <td class="align-middle">Số ngày quá hạn thanh toán</td>
        <td class="align-middle"></td>
    </tr>
    </thead>
    <tbody class="tbody-request-price-survey-{{$material->id}}">
    @foreach($material->price_survey as $index => $priceSurvey)
        @php
            if ($priceSurvey->status != \App\Models\PriceSurvey::TYPE_BUY) {
                continue;
            }    
        @endphp
        <tr>
            <td>
                {{$priceSurvey->supplier}}
            </td>
            <td>
                {{$priceSurvey->deadline}}
            </td>
            <td>
                <div class="d-block">
                    @php
                        $surveyPrice = $priceSurvey->surveyPrices()->first();
                    @endphp
                    @if($surveyPrice) 
                        @if($surveyPrice->accompanying_document != "[]")
                            <button type="button" class="btn btn-success"
                                    data-toggle="modal"
                                    data-target="#accompanying_document_survey_price_modal{{ $surveyPrice->id }}">
                                Hiển thị chứng từ đã
                                tồn tại
                            </button>
                        @else
                            Không tồn tại chứng từ
                        @endif
                        <div class="modal fade"
                            id="accompanying_document_survey_price_modal{{ $surveyPrice->id }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-success">
                                        <h4 class="modal-title">Chứng từ khảo sát
                                            giá</h4>
                                        <button type="button" class="close"
                                                data-dismiss="modal"
                                                aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            $statusAcceptRequest = [
                                            \App\Enums\ProcessStatus::Approved,
                                            \App\Enums\ProcessStatus::PendingSurveyPrice,
                                            ];
                                            $statusNotEdit = [
                                            \App\Enums\ProcessStatus::Approved,
                                            \App\Enums\ProcessStatus::Unapproved,
                                            ];
                                        @endphp
                                        @foreach(json_decode($surveyPrice->accompanying_document, true) as $index => $file)
                                            <div class="data-file">
                                                {!! \App\Helpers\AdminHelper::checkFile($file) !!}
                                                @if(!in_array($requestModel->status, $statusNotEdit))
                                                    <div class="mt-2">
                                                        <button type="button"
                                                                class="btn btn-danger form-control"
                                                                onclick="removeFile(this)"
                                                                data-id = {{ $surveyPrice->id }}
                                                                data-path="{{ $file['path'] }}">
                                                            Xoá file
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button"
                                                class="btn btn-outline-dark"
                                                data-dismiss="modal">Đóng
                                        </button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                    @endif
                </div>
            </td>
            <td>
                {{number_format($priceSurvey->price)}}
            </td>
            <td>
                {{$priceSurvey->number_date_wait_pay}}
            </td>
            <td>
                <a href="{{route('admin.price-survey.edit', ['id' => $priceSurvey->id])}}" target="_blank">
                    <i class="nav-icon fas fa-eye" aria-hidden="true"></i>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
