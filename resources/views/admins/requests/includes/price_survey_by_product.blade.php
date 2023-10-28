{!! Form::open(array('route' => 'admin.price-survey.insert-multiple', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
{!! Form::hidden('request_id', $requestModel->id) !!}
{!! Form::hidden('material_id', $material->id) !!}
{!! Form::hidden('co_id', $requestModel->co_id) !!}

<table class="w-100 bg-white table table-content table-head-fixed table-bordered table-hover">
    <thead>
    <tr align="center" style="font-weight: bold">
        <td class="align-middle">Nhà cung cấp</td>
        <td class="align-middle">IMPO/DOME</td>
        <td class="align-middle">Nhóm sản phẩm</td>
        <td class="align-middle">Deadline cần hàng</td>
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
            <td>
                {!! Form::select('type[]', \App\Models\PriceSurvey::ARR_TYPE, $priceSurvey->type, array('class' => 'form-control')) !!}
            </td>
            <td>
                <input name="product_group[]" value="{{$priceSurvey->product_group}}" class="form-control" placeholder="Nhóm sản phẩm"/>
            </td>
            <td>
                <input value="{{$priceSurvey->deadline}}" class="form-control calendar-date" style="width: 120px" type="text"
                       name="deadline[]" value="{{ null }}">
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
                    {!! Form::checkbox('status[]' , true, ( $priceSurvey->status == \App\Models\PriceSurvey::TYPE_BUY) ? true : false, array('id' => 'status_' .$material->id.'_'.$index)) !!}
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
    {{-- @if($requestModel->status == \App\Enums\ProcessStatus::PendingSurveyPrice) --}}
        <tfoot>
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
        </tfoot>
    {{-- @endif --}}
</table>
{!! Form::close() !!}
