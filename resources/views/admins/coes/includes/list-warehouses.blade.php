<style type="text/css">
    .list-materials {
        max-height: 500px;
    }
</style>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        $('.dataTable').dataTable({
            "bPaginate": false
        });
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
                        <thead>
                            <tr align="center">
                                <th class="align-middle">Số TT</th>
                                <th class="align-middle">Mã HH</th>
                                <th class="">Chi tiết</th>
                                <th class="align-middle">Lot No</th>
                                <th class="align-middle">Ghi Chú</th>
                                <th class="align-middle">Date</th>
                                <th class="align-middle">Tồn kho</th>
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
                                        <td align="left">
                                            <ul style="list-style: circle">
                                                @foreach ($warehouse->detail as $properties => $item)
                                                    <li> {{ \App\Helpers\WarehouseHelper::translateAtt($properties) }} :
                                                        {{ $item }} </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ $warehouse->lot_no }}</td>
                                        <td>{{ $warehouse->ghi_chu }}</td>
                                        <td>{{ $warehouse->date }}</td>
                                        <td align="left">
                                            <ul style="list-style: circle">
                                                @foreach ($warehouse->ton_kho as $properties => $item)
                                                    <li> {{ \App\Helpers\WarehouseHelper::translateAtt($properties) }}
                                                        :
                                                        {{ $item }} </li>
                                                @endforeach
                                            </ul>
                                        </td>
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
