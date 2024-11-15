<style type="text/css">
    .delete-item {
        cursor: pointer;
    }
</style>
<div class="table-responsive p-0">
    <table class="table table-head-fixed table-bordered table-hover text-wrap data-products {{ !empty($is_co) ? ($is_co ? 'data-product-co' : '') : '' }} ">
        @php
            $total = 0;
            $vatTotal = 0;
            // $materials = isset($material) ? $material->toArray() : [];
            if(isset($hiddenShowPrice)) {
                
            } else {
                $hiddenShowPrice = !\App\Helpers\PermissionHelper::hasPermission('admin.co.show-price');
            }
        @endphp
        <thead>
        <tr align="center">
            @if(empty($notAction))
                @php
                    // $colspan = empty($isCoTmp) ? 17 : 17;
                    if(empty($isCoTmp)) {
                        $colspan = 17;
                    }
                    else {
                        $colspan = 16;
                    }
                @endphp
                <th>&nbsp</th>
            @else
                @php
                    $colspan = 14;
                @endphp
            @endif
            <th class="align-middle">Số TT</th>
            <th class="align-middle">Mã HH</th>
            <th class="align-middle">Loại vật liệu</th>
            <th class="align-middle">Độ dày (mm)</th>
            <th class="align-middle">Tiêu chuẩn</th>
            <th class="align-middle">K.Cỡ</th>
            <th class="align-middle">Kích thước (mm)</th>
            <th class="align-middle">Chuẩn bích</th>
            <th class="align-middle">Chuẩn gasket</th>
            <th class="align-middle">Kim loại/ Phi kim loại</th>
            <th class="align-middle">Thương mại/ Sản xuất</th>
            <th class="align-middle">Đ/v tính</th>
            {{--            @if(empty($notAction) && empty($isCoTmp))--}}
            {{--                <th class="align-middle">Kim loại/ Phi kim loại</th>--}}
            {{--            @endif--}}
            <th class="align-middle">Số lượng cần</th>
            @if(empty($notAction))
            <th class="align-middle">Đ/v chính tồn kho</th>
            <th class="align-middle">Tồn kho (Đv chính)</th>
            <th class="align-middle">Đ/v phụ tồn kho</th>
            <th class="align-middle">Tồn kho (Đv phụ)</th>
            @if(empty($isCoTmp))
                    <th class="align-middle">Số lượng sản xuất</th>
                @endif
            @endif
            <th class="align-middle {{$hiddenShowPrice ? 'd-none' : ''}}">Đơn giá (VNĐ)</th>
            <th class="align-middle {{$hiddenShowPrice ? 'd-none' : ''}}">Thành tiền (VNĐ)</th>
            <th class="align-middle {{$hiddenShowPrice ? 'd-none' : ''}}">VAT (%)</th>
            <th class="align-middle {{$hiddenShowPrice ? 'd-none' : ''}}">VAT (VNĐ)</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($warehouses))
            @php
                $sequence = 1;
            @endphp
            @foreach($warehouses as $index => $warehouse)
                @php
                    $detectCode = \App\Helpers\AdminHelper::detectProductCode(!empty($collect) ? $warehouse->code : $warehouse[1]);
                    $code        = !empty($collect) ? $warehouse->code : $warehouse[1];
                    $loaiVatLieu = !empty($collect) ? $warehouse->loai_vat_lieu : $warehouse[2];
                    $doDay       = !empty($collect) ? $warehouse->do_day : $warehouse[3];
                    $tieuChuan   = !empty($collect) ? $warehouse->tieu_chuan : $warehouse[4];
                    $kichCo      = !empty($collect) ? $warehouse->kich_co : $warehouse[5];
                    $kichThuoc   = !empty($collect) ? $warehouse->kich_thuoc : $warehouse[6];
                    $chuanBich   = !empty($collect) ? $warehouse->chuan_bich : $warehouse[7];
                    $chuanGasket = !empty($collect) ? $warehouse->chuan_gasket : $warehouse[8];
                    $dvTinh      = !empty($collect) ? $warehouse->dv_tinh : $warehouse[9];
                    $soLuong     = !empty($collect) ? $warehouse->so_luong : $warehouse[10];
                    $donGia      = !empty($collect) ? $warehouse->don_gia : $warehouse[11];
                    $vatPer      = $warehouse->vat ;
                    $vatM        = $warehouse->vat_money ;
                    $soLuongSanXuat = !empty($collect) ? $warehouse->so_luong_san_xuat : $warehouse[18];
                    $manufactureType = !empty($collect) ? $warehouse->manufacture_type : $detectCode['manufacture_type'];
                    $materialType = !empty($collect) ? $warehouse->material_type : $detectCode['material_type'];
                    $warehouseGroupId = !empty($collect) ? $warehouse->merchandise_group_id : $detectCode['merchandise_group_id'];
                    $tonKho = $detectCode['model_type'] ? \App\Helpers\AdminHelper::countProductMerchanInWarehouse($code, $detectCode['model_type']) : 0;
                    if (!empty($createCO)) {
                        if (($tonKho >= $soLuong) || $manufactureType == \App\Models\MerchandiseGroup::COMMERCE) {
                            $soLuongSanXuat = 0;
                        }
                        else {
                            $soLuongSanXuat = $soLuong - $tonKho;
                        }
                    }
                    $tonKhoSupport = 0;
                    $merchandisePro = $detectCode['model_type'] ? \App\Helpers\WarehouseHelper::getModel($detectCode['model_type'])->find($detectCode['merchandise_id']) : null;
                    $dv_chinh = '';
                    $dv_phu = '';
                    $arDvTinh = array_keys(@$merchandisePro->ton_kho ?? []);
                    if(count(@$merchandisePro->ton_kho ?? []) > 1) {
                        $dv_chinh =  \App\Helpers\WarehouseHelper::translateAtt($arDvTinh[1]);
                        $dv_phu = \App\Helpers\WarehouseHelper::translateAtt($arDvTinh[0]);
                        $tonKhoSupport = $detectCode['model_type'] ? \App\Helpers\AdminHelper::countProductMerchanInWarehouse($code, $detectCode['model_type'], true) : 0;
                    } else {
                        $dv_chinh = $arDvTinh ? \App\Helpers\WarehouseHelper::translateAtt($arDvTinh[0]) : '';
                    }
                @endphp
                <tr align="center">
                    @if(empty($notAction))
                        <td>
                            <i class="fas fa-minus-circle text-danger delete-item" title="Xoá hàng hoá"
                               onclick="deteleItem(this)"></i>
                        </td>
                        <td class="sequence">{{ $sequence }}</td>
                        <td class="code">
                            <input type="hidden" name="code[]" value="{{ $code }}">
                            {{ $code }}
                        </td>
                        <td>
                            <input type="hidden" name="loai_vat_lieu[]" value="{{ $loaiVatLieu }}">
                            {{ $loaiVatLieu }}
                        </td>
                        <td>
                            <input type="hidden" name="do_day[]" value="{{ $doDay }}">
                            {{ $doDay }}
                        </td>
                        <td>
                            <input type="hidden" name="tieu_chuan[]" value="{{ $tieuChuan }}">
                            {{ $tieuChuan }}
                        </td>
                        <td>
                            <input type="hidden" name="kich_co[]" value="{{ $kichCo }}">
                            {{ $kichCo }}
                        </td>
                        <td>
                            <input type="hidden" name="kich_thuoc[]" value="{{ $kichThuoc }}">
                            {{ $kichThuoc }}
                        </td>
                        <td>
                            <input type="hidden" name="chuan_bich[]" value="{{ $chuanBich }}">
                            {{ $chuanBich }}
                        </td>
                        <td>
                            <input type="hidden" name="chuan_gasket[]" value="{{ $chuanGasket }}">
                            {{ $chuanGasket }}
                        </td>
                        <td>
                            <input type="hidden" name="material_type[]" value="{{ $materialType }}">
                              {{gettype($materialType) == 'integer' ? \App\Models\MerchandiseGroup::FACTORY_TYPE[$materialType] : ''}}
                          </td>
                          <td>
                              <input type="hidden" name="warehouse_group_id[]" value="{{ $warehouseGroupId }}">
                            <input type="hidden" name="manufacture_type[]" value="{{ $manufactureType }}">
                            {{gettype($manufactureType) == 'integer' ? \App\Models\MerchandiseGroup::OPERATION_TYPE[$manufactureType] : ''}}
                          </td>
                        <td>
                            <input type="hidden" name="dv_tinh[]" value="{{ $dvTinh }}">
                            {{ $dvTinh }}
                        </td>
{{--                        @if(empty($isCoTmp))--}}
{{--                            <td>--}}
{{--                                <div class="custom-control custom-switch">--}}
{{--                                    @if($warehouse->material_type == \App\Models\OfferPrice::MATERIAL_TYPE_METAL)--}}
{{--                                        <input--}}
{{--                                                name="material_type[]"--}}
{{--                                                value={{$index}}--}}
{{--                                                        type="checkbox"--}}
{{--                                                checked--}}
{{--                                                class="custom-control-input"--}}
{{--                                                id="code_product_{{$index}}"--}}
{{--                                        >--}}
{{--                                    @else--}}
{{--                                        <input--}}
{{--                                                name="material_type[]"--}}
{{--                                                value={{$index}}--}}
{{--                                                        type="checkbox"--}}
{{--                                                class="custom-control-input"--}}
{{--                                                id="code_product_{{$index}}"--}}
{{--                                        >--}}
{{--                                    @endif--}}
{{--                                    <label style="cursor: pointer" class="custom-control-label"--}}
{{--                                           for="code_product_{{$index}}">--}}
{{--                            <span class="show-data-switch">--}}
{{--                                @if($warehouse->material_type == \App\Models\OfferPrice::MATERIAL_TYPE_METAL)--}}
{{--                                    Kim loại--}}
{{--                                @else--}}
{{--                                    Phi kim loại--}}
{{--                                @endif--}}
{{--                            </span>--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </td>--}}
{{--                        @endif--}}
                        <td>
                            <input style="width: 100%;" class="form-control" onChange="caclTotalMoney(this)" type="number" min="1"
                                   name="so_luong[]" value="{{ $soLuong }}">
                        </td>
                        <td>
                            {{ $dv_chinh }}
                        </td>
                        <td>
                            <span class="text-danger"><b>{{customRound($tonKho)}}</b></span>
                        </td>
                        <td>
                            {{ $dv_phu }}
                        </td>
                        <td>
                            <span class="text-danger"><b>{{customRound($tonKhoSupport)}}</b></span>
                        </td>
                        @if(empty($isCoTmp))
                            <td>
                                <input style="width: 100%;" class="form-control" type="number" min="0"
                                    name="so_luong_san_xuat[]" value="{{ $soLuongSanXuat }}"
                                    @if ($manufactureType == \App\Models\MerchandiseGroup::COMMERCE) readonly @endif>
                            </td>
                        @endif
                        <td class="price {{$hiddenShowPrice ? 'd-none' : ''}}" data-price="{{ $donGia }}">
                            <input type="hidden" name="don_gia[]" value="{{ $donGia }}">
                            {{ number_format($donGia) }}
                        </td>
                        <td class="total {{$hiddenShowPrice ? 'd-none' : ''}}">{{ number_format($soLuong * $donGia) }}</td>
                        <td class="{{$hiddenShowPrice ? 'd-none' : ''}}">
                            <input type="hidden" name="vat_per[]" value="{{ $vatPer }}">
                            {{ number_format($vatPer) }}
                        </td>
                        <td class="{{$hiddenShowPrice ? 'd-none' : ''}}">
                            <input type="hidden" name="vat_money[]" value="{{ $vatM }}">
                            {{ number_format($vatM) }}
                        </td>
                    @else
                        <td class="sequence">{{ $sequence }}</td>
                        <td class="code">
                            {{ $code }}
                        </td>
                        <td>
                            {{ $loaiVatLieu }}
                        </td>
                        <td>
                            {{ $doDay }}
                        </td>
                        <td>
                            {{ $tieuChuan }}
                        </td>
                        <td>
                            {{ $kichCo }}
                        </td>
                        <td>
                            {{ $kichThuoc }}
                        </td>
                        <td>
                            {{ $chuanBich }}
                        </td>
                        <td>
                            {{ $chuanGasket }}
                        </td>
                        <td>
                            {{gettype($materialType) == 'integer' ? \App\Models\MerchandiseGroup::FACTORY_TYPE[$materialType] : ''}}
                        </td>
                        <td>
                            {{gettype($manufactureType) == 'integer' ? \App\Models\MerchandiseGroup::OPERATION_TYPE[$manufactureType] : ''}}
                        </td>
                        <td>
                            {{ $dvTinh }}
                        </td>
                        <td>
                            {{ $soLuong }}
                        </td>
                        <td class="{{$hiddenShowPrice ? 'd-none' : ''}}">
                            {{ number_format($donGia) }}
                        </td>
                        <td class="{{$hiddenShowPrice ? 'd-none' : ''}}">
                            {{ number_format($soLuong * $donGia) }}
                        </td>
                        <td class="{{$hiddenShowPrice ? 'd-none' : ''}}">
                            {{ number_format($vatPer) }}
                        </td>
                        <td class="{{$hiddenShowPrice ? 'd-none' : ''}}">
                            {{ number_format($vatM) }}
                        </td>
                    @endif
                </tr>
                @include('admins.coes.includes.inventory-by-warehouse', compact('warehouse'))
                @php
                    $sequence ++;
                    $total += $soLuong * $donGia;
                    $vatTotal += $vatM;
                @endphp
            @endforeach
        @endif
        </tbody>
        <tfoot class="{{$hiddenShowPrice ? 'd-none' : ''}}">
        @if(empty($notAction))
            <tr align="right">
                <td colspan="{{ $colspan }}">Tổng giá (VNĐ):</td>
                <td class="price_total">
                    <input type="hidden" name="tong_gia" value="{{ $total }}">
                    <b>{{ number_format($total) }}</b>
                </td>
            </tr>
            <tr align="right">
                <td colspan="{{ $colspan }}">VAT (VNĐ):</td>
                <td class="vat">
                    <input type="hidden" name="vat" value="{{ $vatTotal }}">
                    <b>{{ number_format($vatTotal) }}</b>
                </td>
            </tr>
            <tr align="right">
                <td colspan="{{ $colspan }}">Thành tiền (VNĐ):</td>
                <td class="money_total"><b>{{ number_format($total + $vatTotal) }}</b></td>
            </tr>
        @else
            <tr align="right">
                <td colspan="{{ $colspan }}">Tổng giá (VNĐ):</td>
                <td>
                    <b>{{ number_format($total) }}</b>
                </td>
            </tr>
            <tr align="right">
                <td colspan="{{ $colspan }}">VAT (VNĐ):</td>
                <td>
                    <b>{{ number_format($vatTotal) }}</b>
                </td>
            </tr>
            <tr align="right">
                <td colspan="{{ $colspan }}">Thành tiền (VNĐ):</td>
                <td><b>{{ number_format($total + $vatTotal) }}</b></td>
            </tr>
        @endif
        </tfoot>
    </table>
</div>
