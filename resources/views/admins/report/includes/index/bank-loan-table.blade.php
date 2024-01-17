<div class="card p-3">
    <h5>Thanh toán vay nợ của công ty theo tháng - Tổng hợp</h5>
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
                @php
                    $total_amount_money = 0;
                    $total_outstanding_balance = 0;
                    $total_debit = 0;
                    foreach ($bankLoanSummary as $value) {
                        $total_amount_money += $value->bankLoan->amount_money;
                        $total_outstanding_balance += $value->total_amount;
                        $total_debit += $value->total_debit_amount;
                    }
                    $total_residual = $total_amount_money - $total_debit;
                @endphp
            <tr>
                <td class="text-center bg-danger">{{number_format($total_amount_money)}}</td>
                <td class="text-center bg-success">{{number_format($total_outstanding_balance)}}</td>
                <td class="text-center bg-gray">{{number_format($total_residual)}}</td>
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
