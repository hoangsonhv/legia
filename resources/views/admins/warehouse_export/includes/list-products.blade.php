<style type="text/css">
  .data-materials {
    max-height: 500px;
  }
  .delete-item {
    cursor: pointer;
  }
</style>
<div class="table-responsive p-0" id="content_table">
  <table class="table table-head-fixed table-bordered table-hover">
    <thead>
      <tr align="center">
        <th class="align-middle">&nbsp</th>
        <th class="align-middle">Số TT</th>
        <th class="align-middle">Tên</th>
        <th class="align-middle">Mã hàng</th>
        <th class="align-middle">Lot No</th>
        <th class="align-middle">Đ/v tính</th>
        <th class="align-middle">Tồn kho</th>
        <th class="align-middle">Số lượng (Yêu cầu)</th>
        <th class="align-middle">Số lượng (Thực xuất)</th>
        <th class="align-middle">Đơn giá</th>
        <th class="align-middle">Thành tiền</th>
      </tr>
    </thead>
    <tbody>
      @if(!empty($products))
        @foreach($products as $index => $product)
          @php
            $base_warehouse = \App\Models\Warehouse\BaseWarehouseCommon::where('l_id', $product['id'])
              ->first();
            $merchandise = \App\Helpers\WarehouseHelper::getModel($base_warehouse->model_type)
              ->where('l_id', $product['id'])->first();
          @endphp
          <tr align="center">
            <td class="">
              <i class="fas fa-minus-circle text-danger delete-item" title="Xoá hàng hoá" onclick="deteleItem(this)"></i>
            </td>
            <td class="sequence">{{ $index + 1 }}</td>
            <td class="">
              <textarea class="form-control" name="product[name][]" rows="1" readonly>{{ $product['name'] }}</textarea>
            </td>
            <td class="code">
              <input class="form-control" type="text" name="product[code][]" value="{{ $product['code'] }}" readonly>
            </td>
            <td class="">
              <input class="form-control" type="text" name="product[lot_no][]" value="{{ $merchandise->lot_no }}" readonly>
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
              <input class="form-control" style="width: 70px" name="product[quantity_doc][]" value="{{ $product['quantity_doc'] }}">
            </td>
            <td class="">
              <input class="form-control data-quantity" style="width: 120px" name="product[quantity_reality][]" value="{{ $product['quantity_reality'] }}"
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
