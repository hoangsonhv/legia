<style type="text/css">
  .data-materials {
    max-height: 500px;
  }
  .delete-item {
    cursor: pointer;
  }
</style>
<div class="table-responsive data-materials p-0">
  <table class="table table-head-fixed table-bordered table-hover">
    <thead>
      <tr align="center">
        <th class="align-middle">&nbsp</th>
        <th class="align-middle check-all"><input type="checkbox" value="1" onclick="checkedAllRows(this)"></th>
        <th class="align-middle">#ID</th>
        <th class="align-middle">Mã HH</th>
        <th class="align-middle">Vật liệu</th>
        <th class="align-middle">Chi tiết</th>
        {{-- 
        <th class="align-middle">Độ dày</th>
        <th class="align-middle">Hình dạng</th>
        <th class="align-middle">Dia W W1</th>
        <th class="align-middle">L L1</th>
        <th class="align-middle">W2</th>
        <th class="align-middle">L2</th> --}}
        {{-- <th class="align-middle">SL - Tấm</th> --}}
        {{-- <th class="align-middle">Diện tích</th> --}}
          <th class="align-middle">Lot No</th>
          <th class="align-middle">Date</th>
          <th class="align-middle">Tồn kho</th>
          {{-- <th class="align-middle">Dv tính</th> --}}
        {{-- <th class="align-middle">Tồn SL - Tấm</th>
        <th class="align-middle">Tồn SL - m2</th> --}}
      </tr>
    </thead>
    <tbody>
      @if(!empty($materials) && $materials->count())
        @foreach($materials as $index => $material)
          <tr>
            <td class="">
              <i class="fas fa-minus-circle text-danger delete-item" title="Xoá hàng hoá" onclick="deteleItem(this)"></i>
            </td>
            <td class="check-data"><input type="checkbox" value="1"></td>
            <td class="sequence">{{ $material->l_id }}</td>
            <td class="table_name d-none">
              <input type="hidden" value="{{ get_class($material) }}">
            </td>
            <td class="warehouse_id d-none">
              <input type="hidden" value="{{ $material->id }}">
            </td>
            <td class="merchandise_id d-none">
              <input type="hidden" value="{{ $material->l_id }}">
            </td>
            <td class="lot_no d-none">
              <input type="hidden" value="{{ $material->lot_no }}">
            </td>
            <td class="dv_tinh d-none">
              <input type="hidden" value="{{ $material->dv_tinh }}">
            </td>
            <td class="ton_kho d-none">
              <input type="hidden" value="{{
                $material->ton_kho[\App\Helpers\WarehouseHelper::groupTonKhoKey($material->model_type)]
              }}">
            </td>
            <td class="ton_kho_phu d-none">
              <input disabled class="form-control" type="text" name="product[ton_kho][]" value="{{ 
                isset(array_values($material->ton_kho)[1]) ? customRound(array_values($material->ton_kho)[1], 3) : 0
              }}">
            </td>
            <td class="code">
              <input type="hidden" name="material[code][]" value="{{ $material->code }}">
              {{ $material->code }}
            </td>
            <td class="vat-lieu">
              <input type="hidden" name="material[mo_ta][]" value="{{ $material->vat_lieu }}">
              {{ $material->vat_lieu }}
            </td>
            <td class="chi-tiet" align="left">
              <ul style="list-style: circle">
                @php
                  $detail = "";   
                @endphp
                @foreach ($material->detail as $properties => $item)
                  @php
                    $detail .= \App\Helpers\WarehouseHelper::translateAtt($properties) . " : " . $item;
                  @endphp
                  <li> {{ \App\Helpers\WarehouseHelper::translateAtt($properties) }} : {{ $item }} </li>
                @endforeach
              </ul>
              <input type="hidden" name="material[detail][]" value="{{$detail}}">
            </td>
            <input type="hidden" name="material[vat_lieu][]" value="{{ $material->vat_lieu }}">
            {{-- <td class="">{{ $material->do_day }}</td>
            <td class="">{{ $material->hinh_dang }}</td>
            <td class="">{{ $material->dia_w_w1 }}</td>
            <td class="">{{ $material->l_l1 }}</td>
            <td class="">{{ $material->w2 }}</td>
            <td class="">{{ $material->l2 }}</td> --}}
            {{-- <td class="">{{ $material->sl_tam }}</td> --}}
            {{-- <td class="">{{ $material->creage }}</td> --}}
              <td class="">{{ $material->lot_no }}</td>
              <td class="">{{ $material->date }}</td>
              <td align="left">
                <ul style="list-style: circle">
                  @foreach ($material->ton_kho as $properties => $item)
                    <li> {{ \App\Helpers\WarehouseHelper::translateAtt($properties) }} : {{ $item }} </li>
                  @endforeach
                </ul>
              </td>
            
            {{-- <td class="">{{ $material->ton_sl_tam }}</td>
            <td class="">{{ $material->ton_sl_m2 }}</td> --}}
          </tr>
        @endforeach
      @else
        <tr>
          <td colspan="17" align="center">Không tồn tại dữ liệu.</td>
        </tr>
      @endif
    </tbody>
  </table>
</div>
