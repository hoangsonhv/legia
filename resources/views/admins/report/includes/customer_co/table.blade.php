<div class="table-responsive">
    <table class="table table-hover text-nowrap">
        <thead>
        <tr>
            <th>
                STT
            </th>
            <th>
                Tên công ty
            </th>
            <th>
                Mã số thuê
            </th>
            <th>
                Tổng tiền
            </th>
            <th class="text-center">
                Số lượng
            </th>
            <th class="text-center">
                Đã duyệt
            </th>
            <th class="text-center">
                Không duyệt
            </th>
            <th class="text-center">
                Đang chờ xử lý
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $index => $data)
            <tr>
                <td>
                    {{$index + 1}}
                </td>
                <td>
                    {{$data->core_customer ? $data->core_customer->name : ''}}
                </td>
                <td>
                    {{$data->core_customer ? $data->core_customer->tax_code : ''}}
                </td>
                <td>
                    {{$data->sum_tong_gia ? number_format($data->sum_tong_gia) : '' }}
                </td>
                <td class="text-center">
                    <a class="text-primary" href="{{route('admin.co.index',
                        array_merge([
                            'core_customer_id' => $data->core_customer_id
                        ], $arrRequest))}}">
                        <b>{{$data->total}}</b>
                    </a>
                </td>
                <td class="text-center">
                    <a class="text-success" href="{{route('admin.co.index',
                        array_merge([
                            'core_customer_id' => $data->core_customer_id,
                            'status' => \App\Enums\ProcessStatus::Approved,
                        ],$arrRequest))}}"
                    >
                        <b>{{$data->sum_approved}}</b>
                    </a>
                </td>
                <td class="text-center">
                    <a class="text-danger" href="{{route('admin.co.index',
                        array_merge([
                            'core_customer_id' => $data->core_customer_id,
                            'status' => \App\Enums\ProcessStatus::Unapproved,
                        ],$arrRequest))}}"
                    >
                        <b>{{$data->sum_no_approved}}</b>
                    </a>
                </td>
                <td class="text-center text-info">
                    <a class="text-info" href="{{route('admin.co.index',
                        array_merge([
                            'core_customer_id' => $data->core_customer_id,
                            'status' => \App\Enums\ProcessStatus::Pending,
                        ],$arrRequest))}}"
                    >
                        <b>{{$data->sum_processing}}</b>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>