<div class="table-responsive p-0">
    <table class="table table-head-fixed table-bordered table-hover text-wrap data-products">
        <thead>
        <tr align="center">
            <th class="align-middle">Số TT</th>
            <th class="align-middle">Mã HH</th>
            <th class="align-middle">Loại vật liệu</th>
            <th class="align-middle">Độ dày (mm)</th>
            <th class="align-middle">Tiêu chuẩn</th>
            <th class="align-middle">Kích cỡ</th>
            <th class="align-middle">L L1</th>
            <th class="align-middle">Dia W W1</th>
            <th class="align-middle">Kích thước (mm)</th>
            <th class="align-middle">Chuẩn bích</th>
            <th class="align-middle">Chuẩn gasket</th>
            <th class="align-middle">Đ/v tính</th>
            <th class="align-middle">Số lượng CO</th>
            @if ($isManufactureProduct)
                <th class="align-middle">Số lượng sản xuất</th>
            @endif
            <th class="align-middle">SL xác nhận</th>
            @if ($isManufactureProduct && $enableQCCheck)
                <th class="align-middle">SL lỗi</th>
            @endif
            <th class="align-middle">Lot No</th>
        </tr>
        </thead>
        <tbody>
            @foreach($details as $key => $detail)
{{--             @dd($detail)--}}
                <tr align="center">
                    <input hidden value="{{isset($detail['id']) ? $detail['id'] : null}}" name="id[]" />
                    <input hidden value="{{$detail['offer_price_id']}}" name="offer_price_id[]" />
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $detail['code'] }}</td>
                    <td>{{ $detail['loai_vat_lieu'] }}</td>
                    <td><input type="text" name="do_day[]" value="{{$detail['do_day']}}"></td>
                    <td><input type="text" name="tieu_chuan[]" value="{{ $detail['tieu_chuan'] }}"></td>
                    <td><input type="text" name="size[]" value="{{ $detail['kich_co'] }}"></td>
                    <td><input type="text" name="l_l1[]" value="{{ @$detail['l_l1'] }}"></td>
                    <td><input type="text" name="dia_w_w1[]" value="{{ @$detail['dia_w_w1'] }}"></td>
                    <td><input type="text" name="kich_thuoc[]" value="{{ $detail['kich_thuoc'] }}"></td>
                    <td><input type="text" name="chuan_bich[]" value="{{ $detail['chuan_bich'] }}"></td>
                    <td><input type="text" name="chuan_gasket[]" value="{{ $detail['chuan_gasket'] }}"></td>
                    <td>
                        {{ $detail['dv_tinh'] }}
                        <input hidden value="{{$detail['material_type']}}" name="offer_price_material_type[]" />
                    </td>
                    <td>
                        @if($is_wait)
                            <input value="{{$detail['need_quantity']}}" name="need_quantity[]" class="form-control" style="width: 80px" />
                        @else
                            {{$detail['need_quantity']}}
                            <input value="{{$detail['need_quantity']}}" name="need_quantity[]" hidden />
                        @endif
                    </td>
                    {{-- <td>
                        <input hidden value="{{$detail['material_type']}}" name="offer_price_material_type[]" />
                        {{$detail['material_type'] == \App\Models\Manufacture::MATERIAL_TYPE_METAL ? 'Kim loại' : 'Phi kim loại'}}
                    </td> --}}
                    @if ($isManufactureProduct)
                        <td>{{ $detail['so_luong_san_xuat'] }}</td>
                    @endif
                    <td>
                        @if($is_processing)
                            <input value="{{$detail['reality_quantity']}}" name="reality_quantity[]" class="form-control" style="width: 80px" />
                        @else
                            {{$detail['reality_quantity']}}
                            <input value="{{$detail['reality_quantity']}}" name="reality_quantity[]" hidden />
                        @endif
                    </td>
                    @if ($isManufactureProduct && $enableQCCheck)
                        <td>
                            @if($model->qc_check == \App\Enums\QCCheckStatus::WAITING)
                                @permission('admin.manufacture.confirm-quantity')
                                    <input value="{{$detail['error_quantity']}}" name="error_quantity[]" class="form-control" style="width: 80px" type="number" min="0" />
                                @endpermission
                            @else
                                {{$detail['error_quantity']}}
                                <input value="{{$detail['error_quantity']}}" name="error_quantity[]" hidden />
                            @endif
                        </td>
                    @endif
                    <td>
                        @if($is_processing)
                            <input value="{{$detail['lot_no']}}" name="lot_no[]" class="form-control" style="width: 80px" />
                        @else
                            {{$detail['lot_no']}}
                            <input value="{{$detail['lot_no']}}" name="lot_no[]" hidden />
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
