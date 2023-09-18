<div class="table-responsive">
    <table class="table table-hover text-nowrap">
        <thead>
        <tr>
            <th>
                STT
            </th>
            <th>
                Họ tên
            </th>
            <th>
                Tài khoản
            </th>
            <th>
                Tổng giá
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
                    {{$data->admin ? $data->admin->name : ''}}
                </td>
                <td>
                    <span>
                        {{$data->admin ? $data->admin->username : ''}}
                    </span>
                    <small>
                        <b>
                            ({{$data->admin ? $data->admin->mail : ''}})
                        </b>
                    </small>
                </td>
                <td>
                    {{$data->sum_tong_gia ? number_format($data->sum_tong_gia) : '' }}
                </td>
                <td class="text-center">
                    <a class="text-primary" href="{{route('admin.co.index',
                        array_merge([
                            'admin_id' => $data->admin_id
                        ], $arrRequest))}}">
                        <b>{{$data->total}}</b>
                    </a>
                </td>
                <td class="text-center">
                    <a class="text-success" href="{{route('admin.co.index',
                        array_merge([
                            'admin_id' => $data->admin_id,
                            'status' => \App\Enums\ProcessStatus::Approved,
                        ],$arrRequest))}}"
                    >
                        <b>{{$data->sum_approved}}</b>
                    </a>
                </td>
                <td class="text-center">
                    <a class="text-danger" href="{{route('admin.co.index',
                        array_merge([
                            'admin_id' => $data->admin_id,
                            'status' => \App\Enums\ProcessStatus::Unapproved,
                        ],$arrRequest))}}"
                    >
                        <b>{{$data->sum_no_approved}}</b>
                    </a>
                </td>
                <td class="text-center text-info">
                    <a class="text-info" href="{{route('admin.co.index',
                        array_merge([
                            'admin_id' => $data->admin_id,
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