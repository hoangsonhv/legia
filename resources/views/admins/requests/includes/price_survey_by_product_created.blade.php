<?php

use App\Services\CoService;
    $prices = \App\Models\SupplierProduct::query()->where('product_code', $material->code)->get();
    $mat_compare = [
            'code' => $material->code,
            'l2' => $material->l2,
            'w2' => $material->w2,
            'l_l1' => $material->l_l1,
            'dia_w_w1' => $material->dia_w_w1,
            'hinh_dang' => $material->hinh_dang,
            'do_day' => $material->do_day,
            'mo_ta' => $material->mo_ta,
            'dv_tinh' => $material->dv_tinh,
            'dinh_luong' => $material->dinh_luong
        ];
?>
<table class="w-100 bg-white table table-content table-head-fixed table-bordered table-hover" style="max-height: 400px;">
    <thead>
    <tr align="center" style="font-weight: bold;">
        <td class="align-middle">Nhà cung cấp đã khảo sát giá</td>
        <td class="align-middle">Giá tiền</td>
    </tr>
    </thead>
    <tbody>
    @foreach ($prices as $index => $price)
        @php $flag = true; @endphp
        @foreach($mat_compare as $key => $mat)
            @if($price->attribute[$key] != $mat)
            @php $flag = false @endphp
            @endif
        @endforeach

        @if($flag)
            <tr>
                <td>
                    <input class="form-control" placeholder="Nhà cung cấp"
                           value="{{ $price->supplier->name }}" disabled/>
                </td>
                <td>
                    <input class="form-control" placeholder="Giá tiền"
                           value="{{ number_format($price->price, 0, '.', ',') }}" disabled/>
                </td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
