<div class="table-responsive p-0">
    <table class="table table-head-fixed table-bordered table-hover text-wrap data-products">
        <thead>
        <tr align="center">
            <th class="align-middle">Số TT</th>
            <th class="align-middle">Mã HH</th>
            <th class="align-middle">Loại vật liệu</th>
            <th class="align-middle">Độ dày (mm)</th>
            <th class="align-middle">Tiêu chuẩn</th>
            <th class="align-middle">K.Cỡ</th>
            <th class="align-middle">Kích thước (mm)</th>
            <th class="align-middle">Chuẩn bích</th>
            <th class="align-middle">Chuẩn gasket</th>
            <th class="align-middle">Đ/v tính</th>
            <th class="align-middle" style="width: 150px">Kim loại/Phi kim loại</th>
            <th class="align-middle">Số lượng cần</th>
            <th class="align-middle">Số lượng sản xuất</th>
            <th class="align-middle">SL xác nhận</th>
        </tr>
        </thead>
        <tbody>
            @foreach($details as $key => $detail)
                <tr align="center">
                    <input hidden value="{{isset($detail['id']) ? $detail['id'] : null}}" name="id[]" />
                    <input hidden value="{{$detail['offer_price_id']}}" name="offer_price_id[]" />
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $detail['code'] }}</td>
                    <td>{{ $detail['loai_vat_lieu'] }}</td>
                    <td>{{ $detail['do_day'] }}</td>
                    <td>{{ $detail['tieu_chuan'] }}</td>
                    <td>{{ $detail['kich_co'] }}</td>
                    <td>{{ $detail['kich_thuoc'] }}</td>
                    <td>{{ $detail['chuan_bich'] }}</td>
                    <td>{{ $detail['chuan_gasket'] }}</td>
                    <td>{{ $detail['dv_tinh'] }}</td>
                    <td>
                        <input hidden value="{{$detail['material_type']}}" name="offer_price_material_type[]" />
                        {{$detail['material_type'] == \App\Models\Manufacture::MATERIAL_TYPE_METAL ? 'Kim loại' : 'Phi kim loại'}}
                    </td>
                    <td>
                        @if($is_wait)
                            <input value="{{$detail['need_quantity']}}" name="need_quantity[]" class="form-control" style="width: 80px" />
                        @else
                            {{$detail['need_quantity']}}
                            <input value="{{$detail['need_quantity']}}" name="need_quantity[]" hidden />
                        @endif
                    </td>
                    <td>{{ $detail['so_luong_san_xuat'] }}</td>
                    <td>
                        @if($is_processing)
                            <input value="{{$detail['reality_quantity']}}" name="reality_quantity[]" class="form-control" style="width: 80px" />
                        @else
                            {{$detail['reality_quantity']}}
                            <input value="{{$detail['reality_quantity']}}" name="reality_quantity[]" hidden />
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>