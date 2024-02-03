<div class="table-responsive">
    <table class="table table-hover text-nowrap">
        <thead>
        <tr>
            <th class="text-center">
                Tổng mua
            </th>
            <th class="text-center">
                Tổng đã chi
            </th>
            <th class="text-center">
                Tổng công nợ
            </th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center btn-info">
                    {{ number_format(array_sum(array_column($requestsMaterials, 'sumBuy'))) }}
                </td>
                <td class="text-center btn-warning">
                    {{ number_format(array_sum(array_column($requestsMaterials, 'sumPayment'))) }}
                </td>
                <td class="text-center btn-danger">
                    {{ number_format(array_sum(array_column($requestsMaterials, 'sumCN'))) }}
                </td>
            </tr>
        </tbody>
    </table>
</div>