<div class="card p-3">
    <h5>Thanh toán vay nợ của công ty theo tháng</h5>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
            <tr>
                <th class="text-center">
                    Tổng tiền đã vay
                </th>
                <th class="text-center">
                    Tổng tiền đã trả
                </th>
                <th class="text-center">
                    Số tiền còn nợ
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center bg-danger">{{$bankLoanSummary ? number_format($bankLoanSummary->total_amount_money) : 0 }}</td>
                <td class="text-center bg-success">{{$bankLoanSummary ? number_format($bankLoanSummary->total_outstanding_balance) : 0 }}</td>
                <td class="text-center bg-gray">{{$bankLoanSummary ? number_format($bankLoanSummary->total_amount_money - $bankLoanSummary->total_outstanding_balance) : 0}}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="card-body table-responsive p-0 mt-4">
        <table class="table table-hover text-nowrap">
            <thead>
            <tr>
                <th>
                    Tháng
                </th>
                <th class="text-right">
                    Số nợ gốc đã trả
                </th>
                <th class="text-right">
                    Số lãi vay đã trả
                </th>
                <th class="text-right">
                    Tổng cộng
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($bankLoans as $data)
                <tr>
                    <td>{{$data->month}}</td>
                    <td class="text-right">{{number_format($data->total_debit_amount)}}</td>
                    <td class="text-right">{{number_format($data->total_profit_amount)}}</td>
                    <td class="text-right">{{number_format($data->total_amount)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
