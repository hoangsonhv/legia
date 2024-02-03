<div class="table-responsive">
    <table class="table table-hover text-nowrap">
        <thead>
        <tr>
            <th class="text-center">
                Tổng doanh thu
            </th>
            <th class="text-center">
                Tổng đã thu
            </th>
            <th class="text-center">
                Tổng chi
            </th>
            <th class="text-center">
                Tổng công nợ
            </th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center btn-info">
                    {{ number_format(array_sum(array_column($coSummary, 'tong_gia'))) }}
                </td>
                <td class="text-center btn-success">
                    {{ number_format(array_sum(array_column($coSummary, 'sumReceipt'))) }}
                </td>
                <td class="text-center btn-warning">
                    {{ number_format(array_sum(array_column($coSummary, 'sumPayment'))) }}
                </td>
                <td class="text-center btn-danger">
                    {{ number_format(array_sum(array_column($coSummary, 'sumCN'))) }}
                </td>
            </tr>
        </tbody>
    </table>
</div>