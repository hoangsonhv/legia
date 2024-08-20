{!! Form::open(array('route' => 'admin.price-survey.insert-multiple', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
{!! Form::hidden('request_id', $requestModel->id) !!}
{!! Form::hidden('material_id', $material->id) !!}
{!! Form::hidden('co_id', $requestModel->co_id) !!}

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
        <td class="align-middle"></td>
    </tr>
    </thead>
    <tbody class="tbody-request-price-survey-{{$material->id}}">
    @foreach($material->price_survey as $index => $priceSurvey)
        <input type="hidden" name="id[]" value="{{$priceSurvey->id}}" />
        <tr>
            <td>
                <input name="supplier[]" class="form-control" placeholder="Nhà cung cấp" value="{{$priceSurvey->supplier}}"/>
            </td>
            {{-- <td>
                {!! Form::select('type[]', \App\Models\PriceSurvey::ARR_TYPE, $priceSurvey->type, array('class' => 'form-control hidden')) !!}
            </td>
            <td>
                <input name="product_group[]" value="{{$priceSurvey->product_group}}" class="form-control" hidden placeholder="Nhóm sản phẩm"/>
            </td> --}}
            <td>
                <input value="{{$priceSurvey->deadline}}" class="form-control calendar-date" type="text"
                       name="deadline[]" value="{{ null }}">
            </td>
            <td>
                <input type="file" name="accompanying_document[]" id="" class="form-control">
                <div class="d-block">
                    @php
                        $surveyPrice = $priceSurvey->surveyPrices()->first();
                    @endphp
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
                </div>
            </td>
            <td>
                <input name="tmp_price[]" value="{{number_format($priceSurvey->price)}}" class="form-control" placeholder="giá trị báo giá"/>
                <input name="price[]" value="{{$priceSurvey->price}}" type="hidden"/>
            </td>
            <td>
                <input name="number_date_wait_pay[]" value="{{$priceSurvey->number_date_wait_pay}}" type="number" class="form-control" placeholder="Số ngày quá hạn thanh toán"/>
            </td>
            <td>
                <a href="{{route('admin.price-survey.edit', ['id' => $priceSurvey->id])}}" target="_blank">
                    <i class="nav-icon fas fa-eye" aria-hidden="true"></i>
                </a>
            </td>
            <td>
                <div class="icheck-success">
                    {!! Form::checkbox('status['.$index.']', true, $priceSurvey->status, array('id' => 'status_' .$material->id.'_'.$index, 'checked' => $priceSurvey->status ? true : false)) !!}
                    <label for={{'status_'.$material->id.'_'.$index}}></label>
                </div>
            </td>
        </tr>
    @endforeach
{{--    <tr>--}}
{{--        <input type="hidden" name="id[]" value="" />--}}
{{--        <td>--}}
{{--            <input name="supplier[]" class="form-control" placeholder="Nhà cung cấp"/>--}}
{{--        </td>--}}
{{--        <td>--}}
{{--            <select name="type[]" class="form-control">--}}
{{--                <option value="1">NVLNK</option>--}}
{{--                <option value="2">NVLND</option>--}}
{{--                <option value="3">Khác</option>--}}
{{--            </select>--}}
{{--        </td>--}}
{{--        <td>--}}
{{--            <input name="product_group[]" class="form-control" placeholder="Nhóm sản phẩm"/>--}}
{{--        </td>--}}
{{--        <td>--}}
{{--            <input class="form-control calendar-date" style="width: 120px" type="text"--}}
{{--                   name="deadline[]">--}}
{{--        </td>--}}
{{--        <td>--}}
{{--            <input name="tmp_price[]" class="form-control" placeholder="giá trị báo giá"/>--}}
{{--            <input name="price[]" type="hidden"/>--}}
{{--        </td>--}}
{{--        <td>--}}
{{--            <input type="number" class="form-control" placeholder="Số ngày quá hạn thanh toán"/>--}}
{{--        </td>--}}
{{--        <td>--}}
{{--            <a href="#">--}}
{{--                <i class="nav-icon fas fa-eye" aria-hidden="true"></i>--}}
{{--            </a>--}}
{{--        </td>--}}
{{--        <td></td>--}}
{{--    </tr>--}}
    </tbody>
    @if($requestModel->status == \App\Enums\ProcessStatus::PendingSurveyPrice)
        <tfoot>
        @if (\App\Enums\ProcessStatus::PendingSurveyPrice == $requestModel->status)
        <tr>
            <td colspan="8">
                <div class="float-right">
                    <span class="btn btn-primary btn-insert-request-price_survey" onClick="insertRequestPriceSurvey({{ $material->id }})">
                        <i class="fas fa-plus"></i> Thêm
                    </span>
                    <button class="btn btn-success" type="submit">Lưu thông tin</button>
                </div>
            </td>
        </tr>
        @endif
        </tfoot>
    @endif
</table>
{!! Form::close() !!}
