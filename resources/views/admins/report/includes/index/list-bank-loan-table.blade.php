<div class="card p-3">
    <h5>Thanh toán vay nợ của công ty theo tháng - {{ $datas->name_bank }} - {{ $datas->account_name }}</h5>
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
                @php
                    $total_amount_money = $datas->bankLoans->count() ? $datas->bankLoans()->sum('amount_money') : 0;
                    $total_outstanding_balance = 0;
                    $total_debit = 0;
                    foreach ($datas->bankLoans as $bankLoan) {
                        $total_outstanding_balance += $bankLoan->bankLoanDetails()->sum('total_amount');
                        $total_debit += $bankLoan->bankLoanDetails()->sum('debit_amount');
                    }
                    $total_residual = $total_amount_money - $total_debit;
                @endphp
                <td class="text-center bg-danger">{{ number_format($total_amount_money) }}</td>
                <td class="text-center bg-success">{{number_format($total_outstanding_balance) }}</td>
                <td class="text-center bg-gray">{{ number_format($total_residual) }}</td>
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
            @php
                $monthlyData = $datas->bankLoans->flatMap(function ($bankLoan) {
                                return $bankLoan->bankLoanDetails->groupBy(function ($date) {
                                    return \Carbon\Carbon::parse($date->created_at)->format('m-Y');
                                })->map(function ($month) {
                                    return [
                                        'month' => $month->first()->created_at->format('m-Y'),
                                        'total_debit_amount' => $month->sum('debit_amount'),
                                        'total_profit_amount' => $month->sum('profit_amount'),
                                        'total_amount' => $month->sum('total_amount'),
                                    ];
                                });
                            })->groupBy('month')->map(function ($months) {
                                return [
                                    'month' => $months->first()['month'],
                                    'total_debit_amount' => $months->sum('total_debit_amount'),
                                    'total_profit_amount' => $months->sum('total_profit_amount'),
                                    'total_amount' => $months->sum('total_amount'),
                                ];
                            });
            @endphp
            @foreach($monthlyData as $data)
                <tr>
                    <td>{{$data['month'] ?? ''}}</td>
                    <td class="text-right">{{number_format($data['total_debit_amount'] ?? 0)}}</td>
                    <td class="text-right">{{number_format($data['total_profit_amount'] ?? 0)}}</td>
                    <td class="text-right">{{number_format($data['total_amount'] ?? 0)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
