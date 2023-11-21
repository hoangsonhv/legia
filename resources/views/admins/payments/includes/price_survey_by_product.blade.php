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
                {{ \App\Models\PriceSurvey::ARR_TYPE[$priceSurvey->type] }}
            </td>
            <td>
                {{$priceSurvey->product_group}}
            </td>
            <td>
                {{$priceSurvey->deadline}}
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
