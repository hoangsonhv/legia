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
        <hr>
        @foreach ($datas->bankLoans as $key => $bankLoan)
        <div class="p-4">

            @php 
            $type = '';
            switch ($bankLoan->loan_type) {
                case 1:
                    $type = 'Trung hạn';
                    break;
                case 2:
                    $type = 'Dài hạn';
                break;
                default:
                    $type = 'Ngắn hạn';
                    break;
            }
            @endphp 
            <span class="badge bg-success m-2">
                Khoản vay {{ $key + 1 }}
            </span>
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th class="text-center">
                        Nội dung vay
                    </th>
                    <th class="text-center">
                        Hình thức vay
                    </th>
                    <th class="text-center">
                        Nội dung chi tiết 
                    </th>
                    <th class="text-center">
                        Ngày vay
                    </th>
                    <th class="text-center">
                        Ngày đáo hạn
                    </th>
                    <th class="text-center">
                        Tổng vay
                    </th>
                    <th class="text-center">
                        Lãi xuất
                    </th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">{{ $bankLoan->lead }}</td>
                        <td class="text-center">{{ $type }}</td>
                        <td class="text-center">{{ $bankLoan->content }}</td>
                        <td class="text-center">{{ $bankLoan->date }}</td>
                        <td class="text-center">{{ $bankLoan->date_due }}</td>
                        <td class="text-center">{{ number_format($bankLoan->amount_money)}}</td>
                        <td class="text-center">{{ $bankLoan->profit_amount }}%</td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th class="text-center">
                        Tổng tiền đã vay
                    </th>
                    <th class="text-center">
                        Tổng tiền gốc đã trả
                    </th>
                    <th class="text-center">
                        Tổng tiền lãi đã trả
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
                    <td class="text-center bg-danger">{{ number_format($bankLoan->amount_money) }}</td>
                    <td class="text-center bg-info">{{number_format($bankLoan->bankLoanDetails()->sum('debit_amount')) }}</td>
                    <td class="text-center bg-dark">{{number_format($bankLoan->bankLoanDetails()->sum('profit_amount')) }}</td>
                    <td class="text-center bg-success">{{number_format($bankLoan->bankLoanDetails()->sum('total_amount')) }}</td>
                    <td class="text-center bg-gray">{{ number_format($bankLoan->amount_money - $bankLoan->bankLoanDetails()->sum('debit_amount')) }}</td>
                </tr>
                </tbody>
            </table>
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr class="text-center" style="background-color: yellow;"><th colspan="7">Chi tiết</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="text-center">
                            Số tiền giao dịch
                        </th>
                        <th class="text-center">
                            Số tiền lãi
                        </th>
                        <th class="text-center">
                            Tổng cộng
                        </th>
                        <th class="text-center">
                            Ghi chú
                        </th>
                        <th class="text-center">
                            Ngày tạo
                        </th>
                        <th class="text-center">
                            Người thực hiện
                        </th>
                    </tr>
                    @foreach ($bankLoan->bankLoanDetails as $detail)
                        <tr>
                            <td class="text-center">{{ number_format($detail->debit_amount) }}</td>
                            <td class="text-center">{{ number_format($detail->profit_amount) }}</td>
                            <td class="text-center">{{ number_format($detail->total_amount) }}</td>
                            <td class="text-center">{{ $detail->note }}</td>
                            <td class="text-center">{{ dateTimeFormat($detail->created_at) }}</td>
                            <td class="text-center">{{ $detail->admin ? $detail->admin->name : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
</div>
