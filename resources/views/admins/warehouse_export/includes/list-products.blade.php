<style type="text/css">
  .data-materials {
    max-height: 500px;
  }
  .delete-item {
    cursor: pointer;
  }
  .divider {
    border-top: 50px solid #dee2e6 !important; /* Đường kẻ chia phần */
  }
  #content_table {
      overflow-x: auto; /* Thêm thanh cuộn ngang khi cần */
  }

  #content_table table {
      border-collapse: collapse !important; /* Xóa khoảng cách giữa các ô */
      width: auto !important; /* Chiều rộng tự động theo nội dung */
      table-layout: auto !important;
  }
</style>
<div class="table-responsive p-0" id="content_table">
  <table class="table table-head-fixed table-bordered table-hover">
    <thead>
      <tr align="center">
        <th class="align-middle">&nbsp</th>
        <th class="align-middle">Số TT</th>
        <th class="align-middle w-100">Tên</th>
        <th class="align-middle w-100">Mã hàng</th>
        <th class="align-middle w-100">Độ dày</th>
        <th class="align-middle w-100">Hình dạng</th>
        <th class="align-middle w-100">Dia W W1</th>
        <th class="align-middle w-100">L L1</th>
        <th class="align-middle w-100">W2</th>
        <th class="align-middle w-100">L2</th>
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
        <th class="align-middle w-100">Lot No</th>
        <th class="align-middle w-100">Đ/v tính</th>
        <th class="align-middle w-100">Tồn kho</th>
        <th class="align-middle w-100">Tồn kho (đơn vị phụ)</th>
        <th class="align-middle w-100">Số lượng (Yêu cầu)</th>
        <th class="align-middle w-100">Số lượng (Thực xuất)</th>
        <th class="align-middle w-100">Đơn giá</th>
        <th class="align-middle w-100">Thành tiền</th>
      </tr>
    </thead>
    <tbody>
      @if(!empty($products))
        @foreach($products as $index => $product)
          @php
            $base_warehouse = \App\Models\Warehouse\BaseWarehouseCommon::where('l_id', $product['merchandise_id'])
              ->first();
           $merchandise = \App\Helpers\WarehouseHelper::getModel($base_warehouse->model_type)
              ->where('l_id', $product['merchandise_id'])->first();
          @endphp
          <tr align="center">
            <td class="">
              <i class="fas fa-minus-circle text-danger delete-item" title="Xoá hàng hoá" onclick="deteleItem(this)"></i>
            </td>
            <td class="sequence">{{ $index + 1 }}</td>
            <td class="">
              <textarea class="form-control w-auto" name="product[name][]" rows="1" readonly>{{ $product['name'] }}</textarea>
            </td>
            <td class=" code">
                <input type="hidden" name="product[merchandise_id][]" value="{{ $product['merchandise_id'] }}">
                <input class="form-control w-auto" type="text" name="product[code][]" value="{{ $product['code'] ?? (isset($base_warehouse) ? $base_warehouse->code : null) }}" readonly>
            </td>
            <td class=" do_day">
              <input class="form-control w-auto" type="text" name="product[do_day][]" value="{{ $product['do_day'] ?? (isset($base_warehouse) ? $base_warehouse->do_day : null) }}" readonly>
            </td>
            <td class=" hinh_dang">
              <input class="form-control w-auto" type="text" name="product[hinh_dang][]" value="{{ $product['hinh_dang'] ?? (isset($base_warehouse) ? $base_warehouse->hinh_dang : null) }}" readonly>
            </td>
            <td class=" dia_w_w1">
              <input class="form-control w-auto" type="text" name="product[dia_w_w1][]" value="{{ $product['dia_w_w1'] ?? (isset($base_warehouse) ? $base_warehouse->dia_w_w1 : null) }}" readonly>
            </td>
            <td class=" l_l1">
              <input class="form-control w-auto" type="text" name="product[l_l1][]" value="{{ $product['l_l1'] ?? (isset($base_warehouse) ? $base_warehouse->l_l1 : null) }}" readonly>
            </td>
            <td class=" w2">
              <input class="form-control w-auto" type="text" name="product[w2][]" value="{{ $product['w2'] ?? (isset($base_warehouse) ? $base_warehouse->w2 : null) }}" readonly>
            </td>
            <td class=" l2">
              <input class="form-control w-auto" type="text" name="product[l2][]" value="{{ $product['l2'] ?? (isset($base_warehouse) ? $base_warehouse->l2 : null) }}" readonly>
            </td>
              <td class="">
                  <input class="form-control" type="text" name="product[inner][]" value="{{ isset($base_warehouse) ? $base_warehouse->inner : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[hoop][]" value="{{ isset($base_warehouse) ? $base_warehouse->hoop : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[filler][]" value="{{ isset($base_warehouse) ? $base_warehouse->filler : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[outer][]" value="{{ isset($base_warehouse) ? $base_warehouse->outer : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[thick][]" value="{{ isset($base_warehouse) ? $base_warehouse->thick : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[tieu_chuan][]" value="{{ isset($base_warehouse) ? $base_warehouse->tieu_chuan : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[kich_co][]" value="{{ isset($base_warehouse) ? $base_warehouse->kich_co : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[trong_luong_cuon][]" value="{{ isset($base_warehouse) ? $base_warehouse->trong_luong_cuon : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[m_cuon][]" value="{{ isset($base_warehouse) ? $base_warehouse->m_cuon : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[m_cay][]" value="{{ isset($base_warehouse) ? $base_warehouse->m_cay : null }}" readonly>
              </td>
            <td class="">
                <input class="form-control w-auto" type="text" name="product[lot_no][]" value="{{ (isset($product['lot_no']) && $product['lot_no'] !== '') ? $product['lot_no'] : (isset($base_warehouse) ? $base_warehouse->lot_no : null) }}" readonly>
            </td>
            <td class="">
              <input class="form-control w-auto" style="width: 70px" type="text" name="product[unit][]" value="{{ $product['unit'] }}" readonly>
            </td>
            <td class="">
              <input readonly class="form-control w-auto" type="text" name="product[ton_kho][]" value="{{
                $merchandise->ton_kho[\App\Helpers\WarehouseHelper::groupTonKhoKey($merchandise->model_type)]
              }}">
            </td>
            <td class="">
              <input disabled class="form-control w-auto" type="text" name="product[ton_kho][]" value="{{
                isset(array_values($merchandise->ton_kho)[1]) ? customRound(array_values($merchandise->ton_kho)[1]) : 0
              }}">
            </td>
            <td class="">
              <input class="form-control w-auto" style="width: 70px" name="product[quantity_doc][]" value="{{ $product['quantity_doc'] }}">
            </td>
            <td class="">
              <input type="number" max="{{
                $merchandise->ton_kho[\App\Helpers\WarehouseHelper::groupTonKhoKey($merchandise->model_type)]
              }}" class="form-control w-auto data-quantity" style="width: 120px" name="product[quantity_reality][]" value="{{ $product['quantity_reality'] }}"
                     onKeyUp="return getNumberFormatQuantity(this)">
            </td>
            <td class="">
              <input class="form-control w-auto" style="width: 120px" min="1" type="text" name="tmp_product[unit_price][]"
                     onKeyUp="return getNumberFormatUnitPrice(this)" value="{{ number_format($product['unit_price']) }}">
              <input class="form-control w-auto data-origin data-unit-price" type="hidden" name="product[unit_price][]" value="{{ $product['unit_price'] }}">
            </td>
            <td class="">
              <input class="form-control w-auto data-into-money" style="width: 120px" min="1" type="text" name="tmp_product[into_money][]"
                     onKeyUp="return getNumberFormat(this)" value="{{ number_format($product['into_money']) }}">
              <input class="form-control w-auto data-origin-into-money" type="hidden" name="product[into_money][]" value="{{$product['into_money']}}">
            </td>
          </tr>
        @endforeach
      @endif
      @if(!empty($merchadiseEcomerceExport))
        @foreach($merchadiseEcomerceExport as $index => $product)
          @php
            $base_warehouse = \App\Models\Warehouse\BaseWarehouseCommon::where('l_id', $product['merchandise_id'])
              ->first();
           $merchandise = \App\Helpers\WarehouseHelper::getModel($base_warehouse->model_type)
              ->where('l_id', $product['merchandise_id'])->first();
              if(!isset($merchandise->ton_kho[\App\Helpers\WarehouseHelper::groupTonKhoKey($merchandise->model_type)])) {
                dump($merchandise, $merchandise->ton_kho, \App\Helpers\WarehouseHelper::groupTonKhoKey($merchandise->model_type), $merchandise->model_type);
              }
          @endphp
          @if ($index > 0)
            @if($product['code'] !== $merchadiseEcomerceExport[$index - 1]['code'])
              </tbody>
              <tbody class="divider">
            @endif
          @endif
          <tr align="center">
            <td class="">
              <i class="fas fa-minus-circle text-danger delete-item" title="Xoá hàng hoá" onclick="deteleItem(this)"></i>
            </td>
            <td class="sequence">{{ $index + 1 }}</td>
            <td class="">
              <input type="hidden" name="product[merchandise_id][]" value="{{ $product['merchandise_id'] }}">
              <textarea class="form-control" name="product[name][]" rows="1" readonly>{{ $product['name'] }}</textarea>
            </td>
            <td class="code">
              <input class="form-control" type="text" name="product[code][]" value="{{ $product['code'] ?? (isset($base_warehouse) ? $base_warehouse->code : null) }}" readonly>
            </td>
            <td class="do_day">
              <input class="form-control" type="text" name="product[do_day][]" value="{{ $product['do_day'] ?? (isset($base_warehouse) ? $base_warehouse->do_day : null) }}" readonly>
            </td>
            <td class="hinh_dang">
              <input class="form-control" type="text" name="product[hinh_dang][]" value="{{ $product['hinh_dang'] ?? (isset($base_warehouse) ? $base_warehouse->hinh_dang : null) }}" readonly>
            </td>
            <td class="dia_w_w1">
              <input class="form-control" type="text" name="product[dia_w_w1][]" value="{{ $product['dia_w_w1'] ?? (isset($base_warehouse) ? $base_warehouse->dia_w_w1 : null) }}" readonly>
            </td>
            <td class="l_l1">
              <input class="form-control" type="text" name="product[l_l1][]" value="{{ $product['l_l1'] ?? (isset($base_warehouse) ? $base_warehouse->l_l1 : null) }}" readonly>
            </td>
            <td class="w2">
              <input class="form-control" type="text" name="product[w2][]" value="{{ $product['w2'] ?? (isset($base_warehouse) ? $base_warehouse->w2 : null) }}" readonly>
            </td>
            <td class="l2">
              <input class="form-control" type="text" name="product[l2][]" value="{{ $product['l2'] ?? (isset($base_warehouse) ? $base_warehouse->l2 : null) }}" readonly>
            </td>
              <td class="">
                  <input class="form-control" type="text" name="product[inner][]" value="{{ isset($base_warehouse) ? $base_warehouse->inner : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[hoop][]" value="{{ isset($base_warehouse) ? $base_warehouse->hoop : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[filler][]" value="{{ isset($base_warehouse) ? $base_warehouse->filler : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[outer][]" value="{{ isset($base_warehouse) ? $base_warehouse->outer : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[thick][]" value="{{ isset($base_warehouse) ? $base_warehouse->thick : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[tieu_chuan][]" value="{{ isset($base_warehouse) ? $base_warehouse->tieu_chuan : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[kich_co][]" value="{{ isset($base_warehouse) ? $base_warehouse->kich_co : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[trong_luong_cuon][]" value="{{ isset($base_warehouse) ? $base_warehouse->trong_luong_cuon : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[m_cuon][]" value="{{ isset($base_warehouse) ? $base_warehouse->m_cuon : null }}" readonly>
              </td>
              <td class="">
                  <input class="form-control" type="text" name="product[m_cay][]" value="{{ isset($base_warehouse) ? $base_warehouse->m_cay : null }}" readonly>
              </td>
            <td class="">
              <input class="form-control" type="text" name="product[lot_no][]" value="{{ $base_warehouse->lot_no }}" readonly>
            </td>
            <td class="">
              <input class="form-control" style="width: 70px" type="text" name="product[unit][]" value="{{ $product['unit'] }}" readonly>
            </td>
            <td class="">
              <input readonly class="form-control" type="text" name="product[ton_kho][]" value="{{
                $merchandise->ton_kho[\App\Helpers\WarehouseHelper::groupTonKhoKey($merchandise->model_type)]
              }}">
            </td>
            <td class="">
              <input disabled class="form-control" type="text" name="product[ton_kho][]" value="{{
                isset(array_values($merchandise->ton_kho)[1]) ? customRound(array_values($merchandise->ton_kho)[1]) : 0
              }}">
            </td>
            <td class="">
              <input class="form-control" style="width: 70px" name="product[quantity_doc][]" value="{{ $product['quantity_doc'] }}">
            </td>
            <td class="">
              <input type="number" max="{{
                $merchandise->ton_kho[\App\Helpers\WarehouseHelper::groupTonKhoKey($merchandise->model_type)]
              }}" class="form-control data-quantity" style="width: 120px" name="product[quantity_reality][]" value="{{ $product['quantity_reality'] }}"
                     onKeyUp="return getNumberFormatQuantity(this)">
            </td>
            <td class="">
              <input class="form-control" style="width: 120px" min="1" type="text" name="tmp_product[unit_price][]"
                     onKeyUp="return getNumberFormatUnitPrice(this)" value="{{ number_format($product['unit_price']) }}">
              <input class="form-control data-origin data-unit-price" type="hidden" name="product[unit_price][]" value="{{ $product['unit_price'] }}">
            </td>
            <td class="">
              <input class="form-control data-into-money" style="width: 120px" min="1" type="text" name="tmp_product[into_money][]"
                     onKeyUp="return getNumberFormat(this)" value="{{ number_format($product['into_money']) }}">
              <input class="form-control data-origin-into-money" type="hidden" name="product[into_money][]" value="{{$product['into_money']}}">
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
    <tfoot>
      <tr align="left">
        <td colspan="11">
          <button type="button" class="btn btn-success" id="display-material">+ Thêm</button>
{{--            <button type="button" class="btn btn-success" id="add-row-material">+ Thêm</button>--}}
        </td>
      </tr>
      <tr align="right">
        @php
          $priceTotal = 0;
            if(is_array($products)) {
                $priceTotal = array_reduce($products, function($carry, $item) {
                    $carry += $item['into_money'];
                    return $carry;
                });
            }
        @endphp
        <td colspan="8">Tổng giá (VNĐ): </td>
        <td colspan="3">
          <input class="form-control" name="tmp_price_total" value="{{ number_format($priceTotal) }}">
          <input type="hidden" name="price_total" value="{{$priceTotal}}">
        </td>
      </tr>
      <tr align="right">
        <td colspan="2">Thuế (%): </td>
        <td colspan="1">
          <input style="width: 70px" id="data-vat" class="form-control" name="vat" onKeyUp="return formatTotalVat(this)"
            value="{{$model ? $model->vat : null}}"/>
        </td>
        <td colspan="5">
          Tiền thuế (VNĐ):
        </td>
        <td colspan="3">
          <input class="form-control" name="tmp_total_vat" value="{{$model ? number_format($model->total_vat) : null}}">
          <input type="hidden" name="total_vat" value="{{$model ? $model->total_vat : null}}">
        </td>
      </tr>
      <tr align="right">
        <td colspan="8">Tổng tiền thanh toán (VNĐ): </td>
        <td colspan="3">
          <input class="form-control" name="tmp_total_payment" value="{{$model ? number_format($model->total_payment) : null}}">
          <input type="hidden" name="total_payment" value="{{$model ? $model->total_payment : null}}">
        </td>
      </tr>
      <tr align="right">
        <td colspan="8">Số tiền bằng chữ (VNĐ): </td>
        <td colspan="3" style="width: 30%">
          <b class="total_payment_vnese">{{$model ? \App\Helpers\AdminHelper::VndText(floatval($model->total_payment)) : null}}</b>
        </td>
      </tr>
      <tr align="right">
        <td colspan="8">Nợ (VNĐ): </td>
        <td colspan="3">
          <input class="form-control" name="tmp_amount_owed" onKeyUp="return getNumberFormat(this)"
            value="{{ $model ? number_format($model->amount_owed) : null }}">
          <input type="hidden" name="amount_owed" class="data-origin" value="{{ $model ? $model->amount_owed : null }}">
        </td>
      </tr>
      <tr align="right">
        <td colspan="8">Có (VNĐ): </td>
        <td colspan="3">
          <input class="form-control" name="tmp_amount_paid" onKeyUp="return getNumberFormat(this)"
                 value="{{ $model ? number_format($model->amount_paid) : null }}">
          <input type="hidden" name="amount_paid" class="data-origin" value="{{ $model ? $model->amount_paid : null }}">
        </td>
      </tr>
    </tfoot>
  </table>
</div>
