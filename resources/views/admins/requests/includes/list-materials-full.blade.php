<style type="text/css">
  .data-materials {
    max-height: 500px;
  }
  .delete-item {
    cursor: pointer;
  }
</style>
<div class="table-responsive data-materials p-0">
  <table id="dataTable" class="table table-head-fixed table-bordered table-hover" style="width:100%">
    <thead>
      <tr align="center">
        <th class="align-middle">&nbsp</th>
        <th class="align-middle check-all"><input type="checkbox" value="1" onclick="checkedAllRows(this)"></th>
        <th class="align-middle">#ID</th>
        <th class="align-middle">Mã HH</th>
        <th class="align-middle">Vật liệu</th>
        @if(!empty($materials))
          @switch($materials->first()->model_type)
            @case(\App\Helpers\WarehouseHelper::BIA)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::CAO_SU)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::CAO_SU_VN_ZA)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::TAM_KIM_LOAI)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::CREAMIC)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::GRAPHITE)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::PTFE)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::TAM_NHUA)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::THANH_PHAM_SWG)
                <th class="align-middle">Inner</th>
                <th class="align-middle">Hoop</th>
                <th class="align-middle">Filler</th>
                <th class="align-middle">Outer</th>
                <th class="align-middle">Thick</th>
                <th class="align-middle">Tiêu Chuẩn</th>
                <th class="align-middle">Kích cỡ</th>
                <th class="align-middle">SL cái</th>
                @break
            @case(\App\Helpers\WarehouseHelper::RTJ)
                <th class="align-middle">Size</th>
                <th class="align-middle">SL cái</th>
                @break
            @case(\App\Helpers\WarehouseHelper::GLAND_PACKING)
                <th class="align-middle">Size</th>
                <th class="align-middle">Trọng lượng Kg/cuộn</th>
                <th class="align-middle">m/cuộn</th>
                <th class="align-middle">SL Cuộn</th>
                <th class="align-middle">SL Kg</th>
                @break
            @case(\App\Helpers\WarehouseHelper::GLAND_PACKING_LATTY)
                <th class="align-middle">Size</th>
                <th class="align-middle">SL Cuộn</th>
                @break
            @case(\App\Helpers\WarehouseHelper::DAY_CREAMIC)
                <th class="align-middle">Size</th>
                <th class="align-middle">m/cuộn</th>
                <th class="align-middle">SL Cuộn</th>
                <th class="align-middle">SL m</th>
                @break
            @case(\App\Helpers\WarehouseHelper::DAY_CAO_SU_VA_SILICON)
                <th class="align-middle">Size</th>
                <th class="align-middle">m/cuộn</th>
                <th class="align-middle">SL Cuộn</th>
                <th class="align-middle">SL m</th>
                @break
            @case(\App\Helpers\WarehouseHelper::NHU_KY_THUAT_CAY_ONG)
                <th class="align-middle">Size</th>
                <th class="align-middle">m/cuộn</th>
                <th class="align-middle">SL Cuộn</th>
                <th class="align-middle">SL m</th>
                @break
            @case(\App\Helpers\WarehouseHelper::PTFE_CAYONG)
                <th class="align-middle">Size</th>
                <th class="align-middle">m/cây</th>
                <th class="align-middle">SL cây</th>
                <th class="align-middle">SL m</th>
                @break
            @case(\App\Helpers\WarehouseHelper::ORING)
                <th class="align-middle">Size</th>
                <th class="align-middle">m/cái</th>
                @break
            @case(\App\Helpers\WarehouseHelper::PTFE_TAPE)
                <th class="align-middle">Size</th>
                <th class="align-middle">m/cuộn</th>
                <th class="align-middle">SL Cuộn</th>
                <th class="align-middle">SL m</th>
                @break
          @endswitch
        @endif
        <th class="align-middle">Lot No</th>
        <th class="align-middle">Date</th>
        <th class="align-middle">Tồn kho</th>
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
            <td class="do_day d-none">
              <input type="hidden" name="material[do_day][]" value="{{ $material->do_day }}">
            </td>
            <td class="hinh_dang d-none">
              <input type="hidden" name="material[hinh_dang][]" value="{{ $material->hinh_dang }}">
            </td>
            <td class="dia_w_w1 d-none">
              <input type="hidden" name="material[dia_w_w1][]" value="{{ $material->dia_w_w1 }}">
            </td>
            <td class="l_l1 d-none">
              <input type="hidden" name="material[l_l1][]" value="{{ $material->l_l1 }}">
            </td>
            <td class="w2 d-none">
              <input type="hidden" name="material[w2][]" value="{{ $material->w2 }}">
            </td>
            <td class="l2 d-none">
              <input type="hidden" name="material[l2][]" value="{{ $material->l2 }}">
            </td>
            <td class="tieu_chuan d-none">
              <input type="hidden" name="material[tieu_chuan][]" value="{{ $material->tieu_chuan }}">
            </td>
            <td class="kich_co d-none">
              <input type="hidden" name="material[kich_co][]" value="{{ $material->kich_co ?? '' }}">
            </td>
            <td class="size d-none">
              <input type="hidden" name="material[size][]" value="{{ $material->size ?? '' }}">
            </td>
            <td class="kich_thuoc d-none">
              <input type="hidden" name="material[kich_thuoc][]" value="{{ $material->kich_thuoc }}">
            </td>
            <td class="chuan_bich d-none">
              <input type="hidden" name="material[chuan_bich][]" value="{{ $material->chuan_mat_bich }}">
            </td>
            <td class="chuan_gasket d-none">
              <input type="hidden" name="material[chuan_gasket][]" value="{{ $material->chuan_gasket }}">
            </td>
            @switch($material->model_type)
              @case(\App\Helpers\WarehouseHelper::BIA)
                  <td class="align-middle m_do_day">{{ $material->do_day }}</td>
                  <td class="align-middle m_hinh_dang">{{ $material->hinh_dang }}</td>
                  <td class="align-middle m_dia_w_w1">{{ $material->dia_w_w1 }}</td>
                  <td class="align-middle m_l_l1">{{ $material->l_l1 }}</td>
                  <td class="align-middle m_w2">{{ $material->w2 }}</td>
                  <td class="align-middle m_l2">{{ $material->l2 }}</td>
                  <td class="align-middle m_ton_sl_tam">{{ $material->ton_sl_tam }}</td>
                  <td class="align-middle m_ton_sl_m2">{{ $material->ton_sl_m2 }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::CAO_SU)
                  <td class="align-middle m_do_day">{{ $material->do_day }}</td>
                  <td class="align-middle m_hinh_dang">{{ $material->hinh_dang }}</td>
                  <td class="align-middle m_dia_w_w1">{{ $material->dia_w_w1 }}</td>
                  <td class="align-middle m_l_l1">{{ $material->l_l1 }}</td>
                  <td class="align-middle m_w2">{{ $material->w2 }}</td>
                  <td class="align-middle m_l2">{{ $material->l2 }}</td>
                  <td class="align-middle m_ton_sl_tam">{{ $material->ton_sl_tam }}</td>
                  <td class="align-middle m_ton_sl_m2">{{ $material->ton_sl_m2 }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::CAO_SU_VN_ZA)
                  <td class="align-middle m_do_day">{{ $material->do_day }}</td>
                  <td class="align-middle m_hinh_dang">{{ $material->hinh_dang }}</td>
                  <td class="align-middle m_dia_w_w1">{{ $material->dia_w_w1 }}</td>
                  <td class="align-middle m_l_l1">{{ $material->l_l1 }}</td>
                  <td class="align-middle m_w2">{{ $material->w2 }}</td>
                  <td class="align-middle m_l2">{{ $material->l2 }}</td>
                  <td class="align-middle m_ton_sl_tam">{{ $material->ton_sl_tam }}</td>
                  <td class="align-middle m_ton_sl_m2">{{ $material->ton_sl_m2 }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::TAM_KIM_LOAI)
                  <td class="align-middle m_do_day">{{ $material->do_day }}</td>
                  <td class="align-middle m_hinh_dang">{{ $material->hinh_dang }}</td>
                  <td class="align-middle m_dia_w_w1">{{ $material->dia_w_w1 }}</td>
                  <td class="align-middle m_l_l1">{{ $material->l_l1 }}</td>
                  <td class="align-middle m_w2">{{ $material->w2 }}</td>
                  <td class="align-middle m_l2">{{ $material->l2 }}</td>
                  <td class="align-middle m_ton_sl_tam">{{ $material->ton_sl_tam }}</td>
                  <td class="align-middle m_ton_sl_m2">{{ $material->ton_sl_m2 }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::CREAMIC)
                  <td class="align-middle m_do_day">{{ $material->do_day }}</td>
                  <td class="align-middle m_hinh_dang">{{ $material->hinh_dang }}</td>
                  <td class="align-middle m_dia_w_w1">{{ $material->dia_w_w1 }}</td>
                  <td class="align-middle m_l_l1">{{ $material->l_l1 }}</td>
                  <td class="align-middle m_w2">{{ $material->w2 }}</td>
                  <td class="align-middle m_l2">{{ $material->l2 }}</td>
                  <td class="align-middle m_ton_sl_tam">{{ $material->ton_sl_tam }}</td>
                  <td class="align-middle m_ton_sl_m2">{{ $material->ton_sl_m2 }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::GRAPHITE)
                  <td class="align-middle m_do_day">{{ $material->do_day }}</td>
                  <td class="align-middle m_hinh_dang">{{ $material->hinh_dang }}</td>
                  <td class="align-middle m_dia_w_w1">{{ $material->dia_w_w1 }}</td>
                  <td class="align-middle m_l_l1">{{ $material->l_l1 }}</td>
                  <td class="align-middle m_w2">{{ $material->w2 }}</td>
                  <td class="align-middle m_l2">{{ $material->l2 }}</td>
                  <td class="align-middle m_ton_sl_tam">{{ $material->ton_sl_tam }}</td>
                  <td class="align-middle m_ton_sl_m2">{{ $material->ton_sl_m2 }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::PTFE)
                  <td class="align-middle m_do_day">{{ $material->do_day }}</td>
                  <td class="align-middle m_hinh_dang">{{ $material->hinh_dang }}</td>
                  <td class="align-middle m_dia_w_w1">{{ $material->dia_w_w1 }}</td>
                  <td class="align-middle m_l_l1">{{ $material->l_l1 }}</td>
                  <td class="align-middle m_w2">{{ $material->w2 }}</td>
                  <td class="align-middle m_l2">{{ $material->l2 }}</td>
                  <td class="align-middle m_ton_sl_tam">{{ $material->ton_sl_tam }}</td>
                  <td class="align-middle m_ton_sl_m2">{{ $material->ton_sl_m2 }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::TAM_NHUA)
                  <td class="align-middle m_do_day">{{ $material->do_day }}</td>
                  <td class="align-middle m_hinh_dang">{{ $material->hinh_dang }}</td>
                  <td class="align-middle m_dia_w_w1">{{ $material->dia_w_w1 }}</td>
                  <td class="align-middle m_l_l1">{{ $material->l_l1 }}</td>
                  <td class="align-middle m_w2">{{ $material->w2 }}</td>
                  <td class="align-middle m_l2">{{ $material->l2 }}</td>
                  <td class="align-middle m_ton_sl_tam">{{ $material->ton_sl_tam }}</td>
                  <td class="align-middle m_ton_sl_m2">{{ $material->ton_sl_m2 }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::THANH_PHAM_SWG)
                  <td class="align-middle m_inner">{{ $material->inner }}</td>
                  <td class="align-middle m_hoop">{{ $material->hoop }}</td>
                  <td class="align-middle m_filler">{{ $material->filler }}</td>
                  <td class="align-middle m_outer">{{ $material->outer }}</td>
                  <td class="align-middle m_thick">{{ $material->thick }}</td>
                  <td class="align-middle m_tieu_chuan">{{ $material->tieu_chuan }}</td>
                  <td class="align-middle m_kich_co">{{ $material->kich_co }}</td>
                  <td class="align-middle m_ton_sl_cai">{{ $material->ton_sl_cai }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::RTJ)
                  <td class="align-middle m_size">{{ $material->size }}</td>
                  <td class="align-middle m_ton_sl_cai">{{ $material->ton_sl_cai }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::GLAND_PACKING)
                  <td class="align-middle m_size">{{ $material->size }}</td>
                  <td class="align-middle m_trong_luong_cuon">{{ $material->trong_luong_cuon }}</td>
                  <td class="align-middle m_m_cuon">{{ $material->m_cuon }}</td>
                  <td class="align-middle m_ton_sl_cuon">{{ $material->ton_sl_cuon }}</td>
                  <td class="align-middle m_ton_sl_kg">{{ $material->ton_sl_kg }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::GLAND_PACKING_LATTY)
                  <td class="align-middle m_size">{{ $material->size }}</td>
                  <td class="align-middle m_ton_sl_cuon">{{ $material->ton_sl_cuon }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::DAY_CREAMIC)
                  <td class="align-middle m_size">{{ $material->size }}</td>
                  <td class="align-middle m_m_cuon">{{ $material->m_cuon }}</td>
                  <td class="align-middle m_ton_sl_cuon">{{ $material->ton_sl_cuon }}</td>
                  <td class="align-middle m_ton_sl_m">{{ $material->ton_sl_m }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::DAY_CAO_SU_VA_SILICON)
                  <td class="align-middle m_size">{{ $material->size }}</td>
                  <td class="align-middle m_m_cuon">{{ $material->m_cuon }}</td>
                  <td class="align-middle m_ton_sl_cuon">{{ $material->ton_sl_cuon }}</td>
                  <td class="align-middle m_ton_sl_m">{{ $material->ton_sl_m }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::NHU_KY_THUAT_CAY_ONG)
                  <td class="align-middle m_size">{{ $material->size }}</td>
                  <td class="align-middle m_m_cuon">{{ $material->m_cuon }}</td>
                  <td class="align-middle m_ton_sl_cuon">{{ $material->ton_sl_cuon }}</td>
                  <td class="align-middle m_ton_sl_m">{{ $material->ton_sl_m }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::PTFE_CAYONG)
                  <td class="align-middle m_size">{{ $material->size }}</td>
                  <td class="align-middle m_m_cay">{{ $material->m_cay }}</td>
                  <td class="align-middle m_ton_sl_cay">{{ $material->ton_sl_cay }}</td>
                  <td class="align-middle m_ton_sl_m">{{ $material->ton_sl_m }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::ORING)
                  <td class="align-middle m_size">{{ $material->size }}</td>
                  <td class="align-middle m_m_cay">{{ $material->m_cay }}</td>
                  @break
              @case(\App\Helpers\WarehouseHelper::PTFE_TAPE)
                  <td class="align-middle m_size">{{ $material->size }}</td>
                  <td class="align-middle m_m_cuon">{{ $material->m_cuon }}</td>
                  <td class="align-middle m_ton_sl_cuon">{{ $material->ton_sl_cuon }}</td>
                  <td class="align-middle m_ton_sl_m">{{ $material->ton_sl_m }}</td>
                  @break
            @endswitch
            {{-- <td class="chi-tiet" align="left">

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
            </td> --}}
            <input type="hidden" name="material[vat_lieu][]" value="{{ $material->vat_lieu }}">
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
    <tfoot>
      <tr align="center">
        <th class="align-middle">&nbsp</th>
        <th class="align-middle"></th>
        <th class="align-middle">#ID</th>
        <th class="align-middle">Mã HH</th>
        <th class="align-middle">Vật liệu</th>
        @if(!empty($materials))
          @switch($materials->first()->model_type)
            @case(\App\Helpers\WarehouseHelper::BIA)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::CAO_SU)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::CAO_SU_VN_ZA)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::TAM_KIM_LOAI)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::CREAMIC)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::GRAPHITE)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::PTFE)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::TAM_NHUA)
                <th class="align-middle">Độ dày</th>
                <th class="align-middle">Hình dạng</th>
                <th class="align-middle">Dia W W1</th>
                <th class="align-middle">L L1</th>
                <th class="align-middle">W2</th>
                <th class="align-middle">L2</th>
                <th class="align-middle">SL Tấm</th>
                <th class="align-middle">SL m2</th>
                @break
            @case(\App\Helpers\WarehouseHelper::THANH_PHAM_SWG)
                <th class="align-middle">Inner</th>
                <th class="align-middle">Hoop</th>
                <th class="align-middle">Filler</th>
                <th class="align-middle">Outer</th>
                <th class="align-middle">Thick</th>
                <th class="align-middle">Tiêu Chuẩn</th>
                <th class="align-middle">Kích cỡ</th>
                <th class="align-middle">SL cái</th>
                @break
            @case(\App\Helpers\WarehouseHelper::RTJ)
                <th class="align-middle">Size</th>
                <th class="align-middle">SL cái</th>
                @break
            @case(\App\Helpers\WarehouseHelper::GLAND_PACKING)
                <th class="align-middle">Size</th>
                <th class="align-middle">Trọng lượng Kg/cuộn</th>
                <th class="align-middle">m/cuộn</th>
                <th class="align-middle">SL Cuộn</th>
                <th class="align-middle">SL Kg</th>
                @break
            @case(\App\Helpers\WarehouseHelper::GLAND_PACKING_LATTY)
                <th class="align-middle">Size</th>
                <th class="align-middle">SL Cuộn</th>
                @break
            @case(\App\Helpers\WarehouseHelper::DAY_CREAMIC)
                <th class="align-middle">Size</th>
                <th class="align-middle">m/cuộn</th>
                <th class="align-middle">SL Cuộn</th>
                <th class="align-middle">SL m</th>
                @break
            @case(\App\Helpers\WarehouseHelper::DAY_CAO_SU_VA_SILICON)
                <th class="align-middle">Size</th>
                <th class="align-middle">m/cuộn</th>
                <th class="align-middle">SL Cuộn</th>
                <th class="align-middle">SL m</th>
                @break
            @case(\App\Helpers\WarehouseHelper::NHU_KY_THUAT_CAY_ONG)
                <th class="align-middle">Size</th>
                <th class="align-middle">m/cuộn</th>
                <th class="align-middle">SL Cuộn</th>
                <th class="align-middle">SL m</th>
                @break
            @case(\App\Helpers\WarehouseHelper::PTFE_CAYONG)
                <th class="align-middle">Size</th>
                <th class="align-middle">m/cây</th>
                <th class="align-middle">SL cây</th>
                <th class="align-middle">SL m</th>
                @break
            @case(\App\Helpers\WarehouseHelper::ORING)
                <th class="align-middle">Size</th>
                <th class="align-middle">m/cái</th>
                @break
            @case(\App\Helpers\WarehouseHelper::PTFE_TAPE)
                <th class="align-middle">Size</th>
                <th class="align-middle">m/cuộn</th>
                <th class="align-middle">SL Cuộn</th>
                <th class="align-middle">SL m</th>
                @break
          @endswitch
        @endif
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
    </tfoot>
  </table>
</div>
{{-- <script type="text/javascript" src="{{ asset('js/admin/dataTable.js') }}" scoped></script> --}}
