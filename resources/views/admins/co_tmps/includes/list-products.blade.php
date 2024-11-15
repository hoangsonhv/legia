<style type="text/css">
  .delete-item {
    cursor: pointer;
  }
</style>
<div class="table-responsive p-0">
  <table class="table table-head-fixed table-bordered table-hover text-wrap data-products">
    @php
      $total = 0;
      $totalVAT = 0;
    @endphp
    <thead>
      <tr align="center">
        @if(empty($notAction))
          @php
            $colspan = 16;
          @endphp
          <th>&nbsp</th>
        @else
          @php
            $colspan = 13;
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
        <th class="align-middle">Kim loại/Phi kim loại</th>
        <th class="align-middle">Thương mại/Sản xuất</th>
        <th class="align-middle">Đ/v tính</th>
        <th class="align-middle">Số lượng</th>
        @if(empty($notAction))
        <th class="align-middle">Đ/v chính tồn kho</th>
        <th class="align-middle">Tồn kho (Đv chính)</th>
        <th class="align-middle">Đ/v phụ tồn kho</th>
        <th class="align-middle">Tồn kho (Đv phụ)</th>
        @endif
        <th class="align-middle">Đơn giá (VNĐ)</th>
        <th class="align-middle">Thành tiền (VNĐ)</th>
        <th class="align-middle">VAT (%)</th>
        <th class="align-middle">VAT (VNĐ)</th>
      </tr>
    </thead>
    <tbody>
      @if(!empty($warehouses))
        @php
          $sequence = 1;
        @endphp
        @foreach($warehouses as $in => $warehouse)
          @php
            $detectCode = \App\Helpers\AdminHelper::detectProductCode(!empty($collect) ? $warehouse->code : $warehouse[1]);
            $code        = !empty($collect) ? $warehouse->code : $warehouse[1];
            $manufactureType = !empty($collect) ? $warehouse->manufacture_type : $detectCode['manufacture_type'];
            $materialType = !empty($collect) ? $warehouse->material_type : $detectCode['material_type'];
            $warehouseGroupId = !empty($collect) ? $warehouse->merchandise_group_id : $detectCode['merchandise_group_id'];
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
            $vatPer      = $warehouse[13];
            $vatM        = $warehouse[14];
            $tonKho = $detectCode['model_type'] ? \App\Helpers\AdminHelper::countProductMerchanInWarehouse($code, $detectCode['model_type'], false) : 0;
            $tonKhoSupport = 0;
            $merchandisePro = $detectCode['model_type'] ? \App\Helpers\WarehouseHelper::getModel($detectCode['model_type'])->find($detectCode['merchandise_id']) : null;
            $dv_chinh = '';
            $dv_phu = '';
            $arDvTinh = array_keys(@$merchandisePro->ton_kho ?? []);
            if(count(@$merchandisePro->ton_kho ?? []) > 1) {
                $dv_chinh =  \App\Helpers\WarehouseHelper::translateAtt($arDvTinh[1]);
                $dv_phu = \App\Helpers\WarehouseHelper::translateAtt($arDvTinh[0]);
                $tonKhoSupport =  $detectCode['model_type'] ? \App\Helpers\AdminHelper::countProductMerchanInWarehouse($code, $detectCode['model_type'], true) : 0;
            } else {
                $dv_chinh = $arDvTinh ? \App\Helpers\WarehouseHelper::translateAtt($arDvTinh[0]) : '';
            }
          @endphp
          <tr align="center">
            @if(empty($notAction))
              <td>
                <i class="fas fa-minus-circle text-danger delete-item" title="Xoá hàng hoá" onclick="deteleItem(this)"></i>
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
              <td >
                <input style="width: 100%;" class="form-control" onChange="caclTotalMoney(this)" type="number" min="1" name="so_luong[]" value="{{ $soLuong }}">
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
              <td class="price" data-price="{{ $donGia }}">
                <input type="hidden" name="don_gia[]" value="{{ $donGia }}">
                {{ number_format($donGia) }}
              </td>
              <td class="total">{{ number_format($soLuong * $donGia) }}</td>
              <td class="">
                <input type="hidden" name="vat_per[]" value="{{ $vatPer }}">
                {{ number_format($vatPer) }}
              </td>
              <td class="">
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
                {{ gettype($manufactureType) == 'integer' ? \App\Models\MerchandiseGroup::FACTORY_TYPE[$manufactureType] : ''}}
              </td>
              <td>
                {{ gettype($type) == 'integer' ? \App\Models\MerchandiseGroup::OPERATION_TYPE[$type] : ''}}
              </td>
              <td>
                {{ $dvTinh }}
              </td>
              <td>
                {{ $soLuong }}
              </td>
              <td class="price" data-price="{{ $donGia }}">
                {{ number_format($donGia) }}
              </td>
              <td class="total">{{ number_format($soLuong * $donGia) }}</td>
              <td class="">{{ $vatPer }}</td>
              <td class="">{{ number_format($vatM) }}</td>
            @endif
          </tr>
          @php
            $sequence ++;
            $total += $soLuong * $donGia;
            $totalVAT += $vatM;
          @endphp
        @endforeach
      @endif
    </tbody>
    <tfoot>
      @if(empty($notAction))
        <tr align="right">
          <td colspan="{{ $colspan }}">Tổng giá (VNĐ): </td>
          <td class="price_total">
            <input type="hidden" name="tong_gia" value="{{ $total }}">
            <b>{{ number_format($total) }}</b>
          </td>
        </tr>
        <tr align="right">
          <td colspan="{{ $colspan }}">VAT (VNĐ): </td>
          <td class="vat">
            <input type="hidden" name="vat" value="{{ $totalVAT }}">
            <b>{{ number_format($totalVAT) }}</b>
          </td>
        </tr>
        <tr align="right">
          <td colspan="{{ $colspan }}">Thành tiền (VNĐ): </td>
          <td class="money_total"><b>{{ number_format($total + $totalVAT) }}</b></td>
        </tr>
      @else
        <tr align="right">
          <td colspan="{{ $colspan }}">Tổng giá (VNĐ): </td>
          <td class="price_total">
            <b>{{ number_format($total) }}</b>
          </td>
        </tr>
        <tr align="right">
          <td colspan="{{ $colspan }}">VAT (VNĐ): </td>
          <td class="vat">
            <b>{{ number_format($totalVAT) }}</b>
          </td>
        </tr>
        <tr align="right">
          <td colspan="{{ $colspan }}">Thành tiền (VNĐ): </td>
          <td class="money_total"><b>{{ number_format($total + $totalVAT) }}</b></td>
        </tr>
      @endif
    </tfoot>
  </table>
</div>
