@extends('layouts.admin')
@section('content')
    @include('admins.breadcrumb')
    @section('css')
        <link rel="stylesheet" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
    @endsection
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-tabs">
                <div class="card-header">
                    @include('admins/report/includes/navigator')
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        @include('admins/report/includes/search-report', ['route' => 'admin.report.tmp-co'])
                        @include('admins/report/includes/tmp_co/table')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
    <script>
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

        });
    </script>
@endsection