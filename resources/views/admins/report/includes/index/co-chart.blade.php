<div class="card card-gray mt-3">
    <div class="card-header">
        Thống kê theo thời gian
    </div>
    <div class="card-body" style="background-color: #f4f6f9">
        <div class="p-3">
{{--            {!! Form::open(array('route' => 'admin.report.index', 'method' => 'get')) !!}--}}
{{--            <div class="input-group">--}}
{{--                <input type="text" name="from_date" class="form-control float-right"--}}
{{--                       placeholder="Từ ngày" value="{{old('from_date')}}">--}}
{{--                <input type="text" name="to_date" class="form-control float-right mr-1"--}}
{{--                       placeholder="Đến ngày" value="{{old('to_date')}}">--}}
{{--                <div class="input-group-append">--}}
{{--                    <button type="submit" class="btn btn-default">--}}
{{--                        <i class="fas fa-search"></i>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            {!! Form::close() !!}--}}
            @include('admins.report.includes.search-report', ['route' => 'admin.report.index'])
        </div>
        <div class=" p-3" style="height: 500px">
            <div class="row">
                <div class="col-6">
                    <canvas id="co-chart"/>
                </div>
                <div class="col-6">
                    <canvas id="payment-receipt-chart"/>
                </div>
            </div>
        </div>
        <div class="row card card-cyan p-3">
            <div class="card-header">Danh sách nhân viên đã chào giá</div>
            <div class="col-12">
                @include('admins.report.includes.tmp_co.table', ['datas' => $tableTmpCo, 'arrRequest' => $arrRequest])
            </div>
        </div>
        <div class="row card card-cyan p-3">
            <div class="card-header">Danh sách nhân viên đã tạo CO</div>
            <div class="col-12">
                @include('admins.report.includes.co.table', ['datas' => $tableCo, 'arrRequest' => $arrRequest])
            </div>
        </div>
        <div class="row card card-cyan p-3">
            <div class="card-header">Danh sách nhân viên đã tạo phiếu yêu cầu</div>
            <div class="col-12">
                @include('admins.report.includes.request.table', ['datas' => $tableRequest, 'arrRequest' => $arrRequest])
            </div>
        </div>
        <div class="row card card-cyan p-3">
            <div class="card-header">Danh sách khách hàng đã yêu cầu chào giá</div>
            <div class="col-12">
                @include('admins.report.includes.customer_tmp_co.table', ['datas' => $tableCustomerTmpCo, 'arrRequest' => $arrRequest])
            </div>
        </div>
        <div class="row card card-cyan p-3">
            <div class="card-header">Danh sách khách hàng đã mua hàng</div>
            <div class="col-12">
                @include('admins.report.includes.customer_co.table', ['datas' => $tableCustomerCo, 'arrRequest' => $arrRequest])
            </div>
        </div>
    </div>
</div>
