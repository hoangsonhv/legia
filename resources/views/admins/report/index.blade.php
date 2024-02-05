@extends('layouts.admin')
@section('content')
    @include('admins.breadcrumb')
@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
@endsection
<style>
    .card {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2)
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-tabs">
            <div class="card-header">
                @include('admins/report/includes/navigator')
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-6">
                                @include('admins.report.includes.index.bank-chart')
                            </div>
                            <div class="col-lg-6">
                                @include('admins.report.includes.index.bank-loan-table')
                            </div>
                            @foreach ($bankLoansByBank as $name => $bank)
                                <div class="col-lg-6">
                                    <img class="rounded float-left img-fluid p-2" width="300px" src="{{\App\Helpers\DataHelper::logoBanks($name)}}" alt="{{$name}}">
                                </div>
                                <div class="col-lg-12">
                                    <div class="row">
                                        @foreach ($bank as $data)
                                        @if ($data->bankLoans->count())
                                        <div class="col-lg-12">
                                            @include('admins.report.includes.index.list-bank-loan-table', ['datas' => $data])
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-lg-12">
                                @include('admins.report.includes.index.co-chart')
                            </div>
                            <div class="col-lg-12">
                                <div class="row card card-cyan p-3">
                                    <div class="p-2"><span class="badge bg-info">Tổng doanh thu CO</span></div>
                                    <div class="col-12">
                                        @include('admins.report.includes.co.summary', ['coSummary' => $coSummary])
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row card card-cyan p-3">
                                    <div class="p-2"><span class="badge bg-info ">ổng tiền mua hàng</span></div>
                                    <div class="col-12">
                                        @include('admins.report.includes.request.summary', ['requestsMaterials' => $requestsMaterials])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript">
        const CHART_COLORS = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 192, 192)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };
        const COLORS = [
            '#4dc9f6',
            '#f67019',
            '#f53794',
            '#537bc4',
            '#acc236',
            '#166a8f',
            '#00a950',
            '#58595b',
            '#8549ba'
        ];
        $(document).ready(function () {
            $('[name="from_date"]').daterangepicker({
                autoUpdateInput: false,
                singleDatePicker: true,
                // maxDate: moment(),
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            $('[name="from_date"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });
            $('[name="from_date"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

            $('[name="to_date"]').daterangepicker({
                autoUpdateInput: false,
                singleDatePicker: true,
                // maxDate: moment(),
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            $('[name="to_date"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });
            $('[name="to_date"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

            initChartCo();
            initChartBank();
            initChartPaymentReceipt();
        });

        function initChartCo() {
            let datas = JSON.parse('{!! $arrCoes !!}');
            let labels = [];
            let dataSource = [];
            datas.map(d => {
                labels.push(d.date);
                dataSource.push(d.total);
            });
            var context = $('#co-chart');
            const data = {
                labels: labels,
                datasets: [
                    {
                        label: 'Số CO đã tạo theo từng ngày',
                        data: dataSource,
                        borderColor: '#f53794',
                        backgroundColor: '#f53794',
                    },
                ]
            };
            const config = {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false
                        }
                    }
                }
            };
            new Chart(context, config);
        }
        function initChartBank() {
            let datas = JSON.parse('{!! $arrBanks !!}');
            function handelClickBank(index) {
                if(typeof index[0].index != 'undefined') {
                    window.open('/admin/bank/edit/' + datas.id[index[0].index])
                }
            }
            const data = {
                labels: datas.label,
                datasets: [{
                    axis: 'y',
                    label: 'Số dư hiện tại',
                    data: datas.data,
                    fill: false,
                    backgroundColor: COLORS,
                }]
            };

            var context = $('#chart-bank');
            const config = {
                type: 'bar',
                data,
                options: {
                    indexAxis: 'y',
                    onClick: (e, index) => handelClickBank(index)
                }
            };
            new Chart(context, config);
        }
        function initChartPaymentReceipt() {
            let datas = JSON.parse('{!! $arrPaymentReceipts !!}');
            let arr = [];
            Object.keys(datas).map(date => arr.push(datas[date]));
            arr.sort(function(a, b){
                var aa = a.date.split('/').reverse().join(),
                    bb = b.date.split('/').reverse().join();
                return aa < bb ? -1 : (aa > bb ? 1 : 0);
            });

            let labels = [];
            let dataPayment = [];
            let dataReceipt = [];
            arr.map(a => {
                labels.push(a.date);
                dataPayment.push(a.total_payment);
                dataReceipt.push(a.total_receipt);
            });

            const data = {
                labels: labels,
                datasets: [
                    {
                        label: 'Tổng thu theo từng ngày',
                        data: dataReceipt,
                        backgroundColor: 'rgb(255, 99, 132)',
                    },
                    {
                        label: 'Tổng chi theo từng ngày',
                        data: dataPayment,
                        backgroundColor: 'rgb(54, 162, 235)',
                    },
                ]
            };
            const config = {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false,
                        }
                    }
                }
            };
            var context = $('#payment-receipt-chart');
            new Chart(context, config);
        }
    </script>
@endsection
