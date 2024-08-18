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
            <th class="align-middle">&nbsp</th>
            <th class="align-middle">Số TT</th>
            <th class="align-middle">Mã HH</th>
            <th class="align-middle">Mô tả</th>
            <th class="align-middle">Kích thước</th>
            <th class="align-middle">Quy cách</th>
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
                    <td class="">
                        @if($requestModel->status == \App\Enums\ProcessStatus::Pending)
                            <i class="fas fa-minus-circle text-danger delete-item" title="Xoá hàng hoá"
                               onclick="deteleItem(this)"></i>
                        @endif
                    </td>
                    <td class="sequence">{{ $index + 1 }}</td>
                    <td class="code">
                        <input type="hidden" name="material[merchandise_id][]" value="{{ $material->merchandise_id }}">
                        <input class="form-control" type="text" name="material[code][]" value="{{ $material->code }}" readonly>
                    </td>
                    <td class="">
                        <textarea class="form-control" name="material[mo_ta][]"
                                  rows="1" readonly>{{ $material->mo_ta }}</textarea>
                    </td>
                    <td class="">
                        <textarea class="form-control" name="material[kick_thuoc][]"
                                  rows="1">{{ $material->kich_thuoc }}</textarea>
                    </td>
                    <td class="">
                        <textarea class="form-control" name="material[quy_cach][]"
                                  rows="1">{{ $material->quy_cach }}</textarea>
                    </td>
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
                                  rows="1">{{ $material->ghi_chu }}</textarea>
                    </td>
                </tr>
                <tr style="background-color: #f4f6f9">
                    <td colspan="10">
                        @include('admins.requests.includes.price_survey_by_product')
                    </td>
                </tr>
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
                    <td colspan="11">
                            <button type="button" class="btn btn-success" id="display-material">+ Thêm vật liệu từ KHO
                            </button>
                            <button type="button" class="btn btn-success" id="add-row-material">+ Thêm ngoài KHO</button>
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>
</div>
