<style type="text/css">
    .data-materials {
        /*max-height: 500px;*/
    }

    .delete-item {
        cursor: pointer;
    }
</style>
@php
    $visibleShop = isset($requestModel) && $requestModel->status == 4 ? true : false;
    $materialArray = isset($materials) ? $materials[0]->toArray() : null;
    $materialColumn = !empty($materialArray) ? array_keys(array_diff_key($materialArray, array_flip(['id', 'request_id', 'created_at', 'updated_at', 'don_gia', 'quy_cach', 'thanh_tien']))) : null;
@endphp
<div class="data-materials p-0 overflow-auto">
    <table class="table table-content table-head-fixed table-bordered table-hover">
        <thead class="text-nowrap border-bottom">
        @if($co)
            <tr align="center">
                <th class="align-middle">&nbsp</th>
                <th class="align-middle">Số TT</th>
                <th class="align-middle material_th_custom">Mã HH</th>
                <th class="align-middle" style="min-width: 200px">Vật liệu</th>
                <th class="align-middle material_th_custom">Size</th>
                <th class="align-middle material_th_custom">Độ dày</th>
                <th class="align-middle material_th_custom">Hình dạng</th>
                <th class="align-middle material_th_custom">Dia W W1</th>
                <th class="align-middle material_th_custom">L L1</th>
                <th class="align-middle material_th_custom">W2</th>
                <th class="align-middle material_th_custom">L2</th>
                <th class="align-middle material_th_custom">Inner</th>
                <th class="align-middle material_th_custom">Hoop</th>
                <th class="align-middle material_th_custom">Filler</th>
                <th class="align-middle material_th_custom">Outer</th>
                <th class="align-middle material_th_custom">Thick</th>
                <th class="align-middle material_th_custom">Tiêu chuẩn</th>
                <th class="align-middle material_th_custom">K.Cỡ</th>
                <th class="align-middle material_th_custom">Trọng lượng - Kg/Cuộn</th>
                <th class="align-middle material_th_custom">m/Cuộn</th>
                <th class="align-middle material_th_custom">m/Cây</th>
                <th class="align-middle material_th_custom">Đ/v tính</th>
                <th class="align-middle material_th_custom t-dinh-luong">Số lượng</th>
                <th class="align-middle material_th_custom">Thời gian cần</th>
                <th class="align-middle material_th_custom">Ghi chú</th>
            </tr>
        @else
            <tr align="center">
                <th class="align-middle">&nbsp</th>
                <th class="align-middle">Số TT</th>
                <th class="align-middle" style="min-width: 120px">Mã HH</th>
                <th class="align-middle" style="min-width: 200px">Mô tả</th>
                <th class="align-middle">Kích thước</th>
                <th class="align-middle">Quy cách</th>
                <th class="align-middle">Đ/v tính</th>
                <th class="align-middle t-dinh-luong">Số lượng</th>
                <th class="align-middle">Thời gian cần</th>
                <th class="align-middle">Ghi chú</th>
            </tr>
        @endif
        </thead>
        <tbody>
{{--        @dd($requestModel)--}}
        @if(!empty($materials) && $materials->count())
            @foreach($materials as $index => $material)
                <tr style="background-color: #fff;">
                    <td colspan="8">

                    </td>
                </tr>
                <tr align="center" style="background-color: #f4f6f9">
                    <td class="">
                        @if($requestModel->status == \App\Enums\ProcessStatus::Pending)
                            <i class="fas fa-minus-circle text-danger delete-item" title="Xoá hàng hoá"
                               onclick="deteleItem(this)"></i>
                        @endif
                    </td>
                    <td class="sequence">{{ $index + 1 }}</td>
                    <td class="code">
                        <input type="hidden" name="material[merchandise_id][]" value="{{ $material->merchandise_id }}">
                        <input style="width: max-content" class="form-control" type="text" name="material[code][]" value="{{ $material->code }}" readonly>
                    </td>
                    <td class="">
                        <textarea class="form-control" name="material[mo_ta][]"
                                  rows="1" readonly>{{ $material->mo_ta }}</textarea>
                    </td>
                    @if($co)
                    <td class="size">
                        <input class="form-control" type="text" name="product[size][]" value="{{ $material->size ?? optional($material->merchandise)->size }}" readonly>
                    </td>
                    <td class="do_day">
                        <input class="form-control" type="text" name="product[do_day][]" value="{{ $material->do_day ?? optional($material->merchandise)->do_day }}" readonly>
                    </td>
                    <td class="hinh_dang">
                        <input class="form-control" type="text" name="product[hinh_dang][]" value="{{ $material->hinh_dang ?? optional($material->merchandise)->hinh_dang }}" readonly>
                    </td>
                    <td class="dia_w_w1">
                        <input class="form-control" type="text" name="product[dia_w_w1][]" value="{{ $material->dia_w_w1 ?? optional($material->merchandise)->dia_w_w1 }}" readonly>
                    </td>
                    <td class="l_l1">
                        <input class="form-control" type="text" name="product[l_l1][]" value="{{ $material->l_l1 ?? optional($material->merchandise)->l_l1 }}" readonly>
                    </td>
                    <td class="w2">
                        <input class="form-control" type="text" name="product[w2][]" value="{{ $material->w2 ?? optional($material->merchandise)->w2 }}" readonly>
                    </td>
                    <td class="l2">
                        <input class="form-control" type="text" name="product[l2][]" value="{{ $material->l2 ?? optional($material->merchandise)->l2 }}" readonly>
                    </td>
                    <td class="inner">
                        <input class="form-control" type="text" name="product[inner][]" value="{{ $material->inner ?? optional($material->merchandise)->inner }}" readonly>
                    </td>
                    <td class="hoop">
                        <input class="form-control" type="text" name="product[hoop][]" value="{{ $material->hoop ?? optional($material->merchandise)->hoop }}" readonly>
                    </td>
                    <td class="filler">
                        <input class="form-control" type="text" name="product[filler][]" value="{{ $material->filler ?? optional($material->merchandise)->filler }}" readonly>
                    </td>
                    <td class="outer">
                        <input class="form-control" type="text" name="product[outer][]" value="{{ $material->outer ?? optional($material->merchandise)->outer }}" readonly>
                    </td>
                    <td class="thick">
                        <input class="form-control" type="text" name="product[thick][]" value="{{ $material->thick ?? optional($material->merchandise)->thick }}" readonly>
                    </td>
                    <td class="tieu_chuan">
                        <input class="form-control" type="text" name="product[tieu_chuan][]" value="{{ $material->tieu_chuan ?? optional($material->merchandise)->tieu_chuan }}" readonly>
                    </td>
                    <td class="kich_co">
                        <input class="form-control" type="text" name="product[kich_co][]" value="{{ $material->kich_co ?? optional($material->merchandise)->kich_co }}" readonly>
                    </td>
                    <td class="trong_luong_cuon">
                        <input class="form-control" type="text" name="product[trong_luong_cuon][]" value="{{ $material->trong_luong_cuon ?? optional($material->merchandise)->trong_luong_cuon }}" readonly>
                    </td>
                    <td class="m_cuon">
                        <input class="form-control" type="text" name="product[m_cuon][]" value="{{ $material->m_cuon ?? optional($material->merchandise)->m_cuon }}" readonly>
                    </td>
                    <td class="m_cay">
                        <input class="form-control" type="text" name="product[m_cay][]" value="{{ $material->m_cay ?? optional($material->merchandise)->m_cay }}" readonly>
                    </td>
                        <td class="kich_thuoc d-none">
                            <input class="form-control" type="text" name="product[kich_thuoc][]" value="{{ $material->kich_thuoc }}" readonly>
                        </td>
                    <td class="chuan_gasket d-none">
                        <input class="form-control" type="text" name="product[chuan_gasket][]" value="{{ $material->chuan_gasket }}" readonly>
                    </td>
                    <td class="chuan_bich d-none">
                        <input class="form-control" type="text" name="product[chuan_bich][]" value="{{ $material->chuan_bich }}" readonly>
                    </td>
                    @else
                    <td class="">
                        <textarea class="form-control" name="material[kick_thuoc][]"
                                  rows="1">{{ $material->kich_thuoc }}</textarea>
                    </td>
                    <td class="">
                        <textarea class="form-control" name="material[quy_cach][]"
                                  rows="1">{{ $material->quy_cach }}</textarea>
                    </td>
                    @endif
                    <td class="">
                        <input class="form-control" style="width: 70px" type="text" name="material[dv_tinh][]"
                               value="{{ $material->dv_tinh }}">
                    </td>
                    <td class="">
                        <input class="form-control" style="width: 120px" min="1" type="text"
                               name="tmp_material[dinh_luong][]" onKeyUp="return getNumberFormat(this)"
                               value="{{ number_format($material->dinh_luong) }}">
                        <input class="form-control data-origin" type="hidden" name="material[dinh_luong][]"
                               value="{{ $material->dinh_luong }}">
                    </td>
                    <td class="">
                        <input class="form-control calendar-date" style="width: 120px" type="text"
                               name="material[thoi_gian_can][]" value="{{ $material->thoi_gian_can }}">
                    </td>
                    <td class="">
                        <textarea class="form-control" name="material[ghi_chu][]"
                                  rows="@if($material->ghi_chu) 2 @endif 1" style="min-width: 150px">{{ $material->ghi_chu }}</textarea>
                    </td>
                </tr>
                @if($visibleShop)
                    <tr style="background-color: #f4f6f9">
                        <td colspan="10">
                            @include('admins.requests.includes.price_survey_by_product')
                        </td>
                        <td colspan="5">
                            @include('admins.requests.includes.price_survey_by_product_created')
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif
        @if(!empty($filterWarehouses) && $filterWarehouses->count())
            @foreach($filterWarehouses as $index => $filterWarehouse)
                <tr style="background-color: #fff;">
                    <td colspan="8">

                    </td>
                </tr>
                <tr align="center" style="background-color: #f4f6f9">
                    <td class="">
                        <i class="fas fa-minus-circle text-danger delete-item" title="Xoá hàng hoá"
                            onclick="deteleItem(this)"></i>
                    </td>
                    <td class="sequence">{{ $index + 1 }}</td>
                    <td class="code">
                        <input type="hidden" name="material[merchandise_id][]" value="{{$filterWarehouse->l_id}}">
                        <input class="form-control" type="text" name="material[code][]" value="{{ $filterWarehouse->code }}" readonly>
                    </td>
                    <td class="">
                        <textarea class="form-control" name="material[mo_ta][]"
                                  rows="1">{{ $filterWarehouse->loai_vat_lieu }}</textarea>
                    </td>
                    <td class="">
                        <textarea class="form-control" name="material[kich_thuoc][]"
                                  rows="1">{{ $filterWarehouse->quy_cach }}</textarea>
                    </td>
                    <td class="">
                        <textarea class="form-control" name="material[quy_cach][]"
                                  rows="1">{{ $filterWarehouse->quy_cach }}</textarea>
                    </td>
                    <td class="">
                        <input class="form-control" style="width: 70px" type="text" name="material[dv_tinh][]"
                               value="{{ $filterWarehouse->dv_tinh }}">
                    </td>
                    <td>
                        <input class="form-control" style="width: 120px" min="1" name="material[dinh_luong][]"
                               value="{{ $filterWarehouse->so_luong }}">
                    </td>
                    <td class="">
                        <input class="form-control calendar-date" style="width: 120px" type="text"
                               name="material[thoi_gian_can][]" value="{{ $filterWarehouse->thoi_gian_can }}">
                    </td>
                    <td class="">
                        <textarea class="form-control" name="material[ghi_chu][]"
                                  rows="1">{{ $filterWarehouse->ghi_chu }}</textarea>
                    </td>

                    <!-- Add more fields here as per your requirement -->
                </tr>

            @endforeach
        @endif
        </tbody>
        @if (str_contains($coStep['title'], "Đang chờ tạo phiếu yêu cầu" ) || str_contains($coStep['title'], "duyệt phiếu yêu cầu" ))
            <tfoot>
                <tr align="left">
                    <td colspan="22">
                            <button type="button" class="btn btn-success" id="display-material">+ Thêm vật liệu từ KHO
                            </button>
                            <button type="button" class="btn btn-success" id="add-row-material">+ Thêm ngoài KHO</button>
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>
</div>
