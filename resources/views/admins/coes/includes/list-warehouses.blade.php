<style type="text/css">
    .list-materials {
        max-height: 500px;
    }
    tfoot {
        display: table-header-group;
    }
</style>
<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

<h3 class="text-primary">Danh sách vật liệu kho</h3>
@php
    $warehouseGroup = $warehouses->groupBy('model_type');
    $index = 0;
@endphp
<ul class="nav nav-tabs" id="myTabs">
    @foreach ($warehouseGroup as $key => $item)
        <li class="nav-item">
            <a class="nav-link {{ $index == 0 ? 'active' : '' }}" id="tab-{{ $key }}" data-toggle="tab"
                href="#warehouse-{{ $key }}">{{ \App\Helpers\WarehouseHelper::warehouseName($key) }}</a>
        </li>
        @php
            $index++;
        @endphp
    @endforeach
</ul>
<div class="table-responsive list-materials p-0">
    <div class="mt-4 table-head-fixed ">

        @php
            $index = 0;
        @endphp
        <div class="tab-content mt-2">
            @foreach ($warehouseGroup as $key => $item)
                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="warehouse-{{ $key }}">
                    <table class="table table-head-fixed table-bordered table-hover dataTable text-wrap data-products">
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Mã HH</th>
                                <th class="align-middle">Vật liệu</th>
                                @switch($key)
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
                                    @case(\App\Helpers\WarehouseHelper::KHO_THANH_PHAM_PHI_KIM_LOAI)
                                        <th class="align-middle">Độ dày</th>
                                        <th class="align-middle">Mức áp lực</th>
                                        <th class="align-middle">Tiêu chuẩn</th>
                                        <th class="align-middle">Kích cỡ</th>
                                        <th class="align-middle">Kích thước</th>
                                        <th class="align-middle">Chuẩn mặt bích</th>
                                        <th class="align-middle">Chuẩn gasket</th>
                                        <th class="align-middle">Tồn sl cái</th>
                                        @break
                                    @case(\App\Helpers\WarehouseHelper::KHO_THANH_PHAM_KIM_LOAI)
                                        <th class="align-middle">Độ dày</th>
                                        <th class="align-middle">Mức áp lực</th>
                                        <th class="align-middle">Tiêu chuẩn</th>
                                        <th class="align-middle">Kích cỡ</th>
                                        <th class="align-middle">Kích thước</th>
                                        <th class="align-middle">Chuẩn mặt bích</th>
                                        <th class="align-middle">Chuẩn gasket</th>
                                        <th class="align-middle">Tồn sl cái</th>
                                        @break
                                    @case(\App\Helpers\WarehouseHelper::HOOP)
                                        <th class="align-middle">Size</th>
                                        <th class="align-middle">Trọng lượng Kg/cuộn</th>
                                        <th class="align-middle">m/cuộn</th>
                                        <th class="align-middle">SL Cuộn</th>
                                        <th class="align-middle">SL Kg</th>
                                        @break
                                    @case(\App\Helpers\WarehouseHelper::FILLER)
                                        <th class="align-middle">Size</th>
                                        <th class="align-middle">Trọng lượng Kg/cuộn</th>
                                        <th class="align-middle">m/cuộn</th>
                                        <th class="align-middle">SL Cuộn</th>
                                        <th class="align-middle">SL Kg</th>
                                        @break
                                    @case(\App\Helpers\WarehouseHelper::VANH_TINH_INNER_SWG)
                                        <th class="align-middle">Độ dày</th>
                                        <th class="align-middle">D1</th>
                                        <th class="align-middle">D2</th>
                                        <th class="align-middle">Tồn sl cái</th>
                                        @break
                                    @case(\App\Helpers\WarehouseHelper::VANH_TINH_OUTER_SWG)
                                        <th class="align-middle">Độ dày</th>
                                        <th class="align-middle">D3</th>
                                        <th class="align-middle">D4</th>
                                        <th class="align-middle">Tồn sl cái</th>
                                        @break
                                @endswitch
                                <th>Lot no</th>
                                <th>Ghi chú</th>
                                <th>Date</th>
                            </tr>
                        </tfoot>
                        <thead>
                            <tr align="center">
                                <th class="align-middle">Số TT</th>
                                <th class="align-middle">Mã HH</th>
                                <th class="align-middle">Vật liệu</th>
                                @switch($key)
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
                                    @case(\App\Helpers\WarehouseHelper::KHO_THANH_PHAM_PHI_KIM_LOAI)
                                        <th class="align-middle">Độ dày</th>
                                        <th class="align-middle">Mức áp lực</th>
                                        <th class="align-middle">Tiêu chuẩn</th>
                                        <th class="align-middle">Kích cỡ</th>
                                        <th class="align-middle">Kích thước</th>
                                        <th class="align-middle">Chuẩn mặt bích</th>
                                        <th class="align-middle">Chuẩn gasket</th>
                                        <th class="align-middle">Tồn sl cái</th>
                                        @break
                                    @case(\App\Helpers\WarehouseHelper::KHO_THANH_PHAM_KIM_LOAI)
                                        <th class="align-middle">Độ dày</th>
                                        <th class="align-middle">Mức áp lực</th>
                                        <th class="align-middle">Tiêu chuẩn</th>
                                        <th class="align-middle">Kích cỡ</th>
                                        <th class="align-middle">Kích thước</th>
                                        <th class="align-middle">Chuẩn mặt bích</th>
                                        <th class="align-middle">Chuẩn gasket</th>
                                        <th class="align-middle">Tồn sl cái</th>
                                        @break
                                    @case(\App\Helpers\WarehouseHelper::HOOP)
                                        <th class="align-middle">Size</th>
                                        <th class="align-middle">Trọng lượng Kg/cuộn</th>
                                        <th class="align-middle">m/cuộn</th>
                                        <th class="align-middle">SL Cuộn</th>
                                        <th class="align-middle">SL Kg</th>
                                        @break
                                    @case(\App\Helpers\WarehouseHelper::FILLER)
                                        <th class="align-middle">Size</th>
                                        <th class="align-middle">Trọng lượng Kg/cuộn</th>
                                        <th class="align-middle">m/cuộn</th>
                                        <th class="align-middle">SL Cuộn</th>
                                        <th class="align-middle">SL Kg</th>
                                        @break
                                    @case(\App\Helpers\WarehouseHelper::VANH_TINH_INNER_SWG)
                                        <th class="align-middle">Độ dày</th>
                                        <th class="align-middle">D1</th>
                                        <th class="align-middle">D2</th>
                                        <th class="align-middle">Tồn sl cái</th>
                                        @break
                                    @case(\App\Helpers\WarehouseHelper::VANH_TINH_OUTER_SWG)
                                        <th class="align-middle">Độ dày</th>
                                        <th class="align-middle">D3</th>
                                        <th class="align-middle">D4</th>
                                        <th class="align-middle">Tồn sl cái</th>
                                        @break
                                @endswitch
                                <th class="align-middle">Lot No</th>
                                <th class="align-middle">Ghi Chú</th>
                                <th class="align-middle">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($item))
                                @php
                                    $sequence = 1;
                                @endphp
                                @foreach ($item as $warehouse)
                                    <tr align="center">
                                        <td>{{ $sequence }}</td>
                                        <td>{{ $warehouse->code }}</td>
                                        <td>{{ $warehouse->vat_lieu }}</td>
                                        @switch($key)
                                            @case(\App\Helpers\WarehouseHelper::BIA)
                                                <td class="align-middle">{{ $warehouse->do_day }}</td>
                                                <td class="align-middle">{{ $warehouse->hinh_dang }}</td>
                                                <td class="align-middle">{{ $warehouse->dia_w_w1}}</td>
                                                <td class="align-middle">{{ $warehouse->l_l1 }}</td>
                                                <td class="align-middle">{{ $warehouse->w2 }}</td>
                                                <td class="align-middle">{{ $warehouse->l2 }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_tam }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_m2 }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::CAO_SU)
                                                <td class="align-middle">{{ $warehouse->do_day }}</td>
                                                <td class="align-middle">{{ $warehouse->hinh_dang }}</td>
                                                <td class="align-middle">{{ $warehouse->dia_w_w1}}</td>
                                                <td class="align-middle">{{ $warehouse->l_l1 }}</td>
                                                <td class="align-middle">{{ $warehouse->w2 }}</td>
                                                <td class="align-middle">{{ $warehouse->l2 }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_tam }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_m2 }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::CAO_SU_VN_ZA)
                                                <td class="align-middle">{{ $warehouse->do_day }}</td>
                                                <td class="align-middle">{{ $warehouse->hinh_dang }}</td>
                                                <td class="align-middle">{{ $warehouse->dia_w_w1}}</td>
                                                <td class="align-middle">{{ $warehouse->l_l1 }}</td>
                                                <td class="align-middle">{{ $warehouse->w2 }}</td>
                                                <td class="align-middle">{{ $warehouse->l2 }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_tam }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_m2 }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::TAM_KIM_LOAI)
                                                <td class="align-middle">{{ $warehouse->do_day }}</td>
                                                <td class="align-middle">{{ $warehouse->hinh_dang }}</td>
                                                <td class="align-middle">{{ $warehouse->dia_w_w1}}</td>
                                                <td class="align-middle">{{ $warehouse->l_l1 }}</td>
                                                <td class="align-middle">{{ $warehouse->w2 }}</td>
                                                <td class="align-middle">{{ $warehouse->l2 }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_tam }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_m2 }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::CREAMIC)
                                                <td class="align-middle">{{ $warehouse->do_day }}</td>
                                                <td class="align-middle">{{ $warehouse->hinh_dang }}</td>
                                                <td class="align-middle">{{ $warehouse->dia_w_w1}}</td>
                                                <td class="align-middle">{{ $warehouse->l_l1 }}</td>
                                                <td class="align-middle">{{ $warehouse->w2 }}</td>
                                                <td class="align-middle">{{ $warehouse->l2 }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_tam }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_m2 }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::GRAPHITE)
                                                <td class="align-middle">{{ $warehouse->do_day }}</td>
                                                <td class="align-middle">{{ $warehouse->hinh_dang }}</td>
                                                <td class="align-middle">{{ $warehouse->dia_w_w1}}</td>
                                                <td class="align-middle">{{ $warehouse->l_l1 }}</td>
                                                <td class="align-middle">{{ $warehouse->w2 }}</td>
                                                <td class="align-middle">{{ $warehouse->l2 }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_tam }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_m2 }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::PTFE)
                                                <td class="align-middle">{{ $warehouse->do_day }}</td>
                                                <td class="align-middle">{{ $warehouse->hinh_dang }}</td>
                                                <td class="align-middle">{{ $warehouse->dia_w_w1}}</td>
                                                <td class="align-middle">{{ $warehouse->l_l1 }}</td>
                                                <td class="align-middle">{{ $warehouse->w2 }}</td>
                                                <td class="align-middle">{{ $warehouse->l2 }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_tam }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_m2 }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::TAM_NHUA)
                                                <td class="align-middle">{{ $warehouse->do_day }}</td>
                                                <td class="align-middle">{{ $warehouse->hinh_dang }}</td>
                                                <td class="align-middle">{{ $warehouse->dia_w_w1}}</td>
                                                <td class="align-middle">{{ $warehouse->l_l1 }}</td>
                                                <td class="align-middle">{{ $warehouse->w2 }}</td>
                                                <td class="align-middle">{{ $warehouse->l2 }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_tam }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_m2 }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::THANH_PHAM_SWG)
                                                <td class="align-middle">{{ $warehouse->inner }}</td>
                                                <td class="align-middle">{{ $warehouse->hoop }}</td>
                                                <td class="align-middle">{{ $warehouse->filler }}</td>
                                                <td class="align-middle">{{ $warehouse->outer }}</td>
                                                <td class="align-middle">{{ $warehouse->thick }}</td>
                                                <td class="align-middle">{{ $warehouse->tieu_chuan }}</td>
                                                <td class="align-middle">{{ $warehouse->kich_co }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_cai }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::RTJ)
                                                <td class="align-middle">{{ $warehouse->size}}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_cai }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::GLAND_PACKING)
                                                <td class="align-middle">{{ $warehouse->size}}</td>
                                                <td class="align-middle">{{ $warehouse->trong_luong_kg_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->m_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_kg }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::GLAND_PACKING_LATTY)
                                                <td class="align-middle">{{ $warehouse->size}}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_cuon }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::DAY_CREAMIC)
                                                <td class="align-middle">{{ $warehouse->size}}</td>
                                                <td class="align-middle">{{ $warehouse->m_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_m }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::DAY_CAO_SU_VA_SILICON)
                                                <td class="align-middle">{{ $warehouse->size}}</td>
                                                <td class="align-middle">{{ $warehouse->m_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_m }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::NHU_KY_THUAT_CAY_ONG)
                                                <td class="align-middle">{{ $warehouse->size}}</td>
                                                <td class="align-middle">{{ $warehouse->m_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_m }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::PTFE_CAYONG)
                                                <td class="align-middle">{{ $warehouse->size}}</td>
                                                <td class="align-middle">{{ $warehouse->m_cay }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_cay }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_m }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::ORING)
                                                <td class="align-middle">{{ $warehouse->size }}</td>
                                                <td class="align-middle">{{ $warehouse->m_cay }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::PTFE_TAPE)
                                                <td class="align-middle">{{ $warehouse->size}}</td>
                                                <td class="align-middle">{{ $warehouse->m_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_m }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::KHO_THANH_PHAM_PHI_KIM_LOAI)
                                                <td class="align-middle">{{ $warehouse->do_day}}</td>
                                                <td class="align-middle">{{ $warehouse->muc_ap_luc}}</td>
                                                <td class="align-middle">{{ $warehouse->tieu_chuan}}</td>
                                                <td class="align-middle">{{ $warehouse->kich_co}}</td>
                                                <td class="align-middle">{{ $warehouse->kich_thuoc}}</td>
                                                <td class="align-middle">{{ $warehouse->chuan_mat_bich}}</td>
                                                <td class="align-middle">{{ $warehouse->chuan_gasket}}</td>
                                                <td class="align-middle">{{ $warehouse->sl_ton}}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::KHO_THANH_PHAM_KIM_LOAI)
                                                <td class="align-middle">{{ $warehouse->do_day}}</td>
                                                <td class="align-middle">{{ $warehouse->muc_ap_luc}}</td>
                                                <td class="align-middle">{{ $warehouse->tieu_chuan}}</td>
                                                <td class="align-middle">{{ $warehouse->kich_co}}</td>
                                                <td class="align-middle">{{ $warehouse->kich_thuoc}}</td>
                                                <td class="align-middle">{{ $warehouse->chuan_mat_bich}}</td>
                                                <td class="align-middle">{{ $warehouse->chuan_gasket}}</td>
                                                <td class="align-middle">{{ $warehouse->sl_ton}}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::HOOP)
                                                <td class="align-middle">{{ $warehouse->size}}</td>
                                                <td class="align-middle">{{ $warehouse->trong_luong_kg_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->m_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_kg }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::FILLER)
                                                <td class="align-middle">{{ $warehouse->size}}</td>
                                                <td class="align-middle">{{ $warehouse->trong_luong_kg_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->m_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_cuon }}</td>
                                                <td class="align-middle">{{ $warehouse->ton_sl_kg }}</td>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::VANH_TINH_INNER_SWG)
                                                <th class="align-middle">{{ $warehouse->do_day }}</th>
                                                <th class="align-middle">{{ $warehouse->d1}}</th>
                                                <th class="align-middle">{{ $warehouse->d2}}</th>
                                                <th class="align-middle">{{ $warehouse->ton_sl_cai}}</th>
                                                @break
                                            @case(\App\Helpers\WarehouseHelper::VANH_TINH_OUTER_SWG)
                                                <th class="align-middle">{{ $warehouse->do_day }}</th>
                                                <th class="align-middle">{{ $warehouse->d3}}</th>
                                                <th class="align-middle">{{ $warehouse->d4}}</th>
                                                <th class="align-middle">{{ $warehouse->ton_sl_cai}}</th>
                                                @break
                                        @endswitch
                                        <td>{{ $warehouse->lot_no }}</td>
                                        <td>{{ $warehouse->ghi_chu }}</td>
                                        <td>{{ $warehouse->date }}</td>
                                    </tr>
                                    @php
                                        $sequence++;
                                    @endphp
                                @endforeach
                            @endif
                        </tbody>

                    </table>
                </div>
                @php
                    $index++;
                @endphp
            @endforeach
        </div>
    </div>
</div>
<script>
        $('.dataTable').each(function() {
            var table = $(this).DataTable(); // Khởi tạo DataTable cho mỗi bảng

            // Thêm các dropdown Select2 cho mỗi cột trong footer
            $(this).find('tfoot th').each(function(index) {
                var title = $(this).text();
                var select = $('<select class="select2" multiple="multiple" style="width:100%" ><option value="">' + title + '</option></select>');
                $(this).html(select);

                // Lấy tất cả các giá trị duy nhất từ cột và thêm vào Select2
                table.column(index).data().unique().sort().each(function(d) {
                    // Loại bỏ các thẻ HTML khỏi dữ liệu nếu cần
                    select.append('<option value="' + d + '">' + d + '</option>');
                });

                // Khởi tạo Select2
                select.select2();
            });
            console.log(1);
            // Khởi tạo Select2 cho các dropdown vừa tạo
            $(this).find('.select2').select2();

            // Thêm sự kiện tìm kiếm cho mỗi cột
            table.columns().every(function() {
                var column = this;
                $('select', this.footer()).on('change', function() {
                    var val = $(this).val(); // Lấy giá trị đã chọn
                    if (val.length > 0) {
                        // Tạo chuỗi regex để tìm kiếm với tất cả các lựa chọn
                        val = val.map(function(v) {
                            return $.fn.dataTable.util.escapeRegex(v);
                        }).join('|');
                    }
                    column.search(val ? val : '', true, false).draw();
                });
            });
        });
</script>
