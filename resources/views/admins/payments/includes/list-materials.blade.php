<style type="text/css">
    .data-materials {
        /*max-height: 500px;*/
    }

    .delete-item {
        cursor: pointer;
    }
</style>
<div class="data-materials p-0">
    <table class="table table-content table-head-fixed table-bordered table-hover">
        <thead>
        <tr align="center">
            <th class="align-middle">Số TT</th>
            <th class="align-middle">Mã HH</th>
            <th class="align-middle">Mô tả</th>
            <th class="align-middle">Đ/v tính</th>
            <th class="align-middle t-dinh-luong">Số lượng</th>
            <th class="align-middle">Thời gian cần</th>
            <th class="align-middle">Ghi chú</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($materials) && $materials->count())
            @foreach($materials as $index => $material)
                <tr style="background-color: #fff;">
                    <td colspan="8">

                    </td>
                </tr>
                <tr align="center" style="background-color: #f4f6f9">
                    <td class="sequence">{{ $index + 1 }}</td>
                    <td class="code">
                        {{ $material->code }}
                    </td>
                    <td class="">
                        {{$material->mo_ta}}
                    </td>
                    <td class="">
                        {{$material->dv_tinh}}
                    </td>
                    <td class="">
                        {{number_format($material->dinh_luong)}}
                    </td>
                    <td class="">
                        {{$material->thoi_gian_can}}
                    </td>
                    <td class="">
                        {{$material->ghi_chu}}
                    </td>
                </tr>
                <tr style="background-color: #f4f6f9">
                    <td colspan="8">
                        @include('admins.payments.includes.price_survey_by_product')
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
