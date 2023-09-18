<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Phiếu xuất kho bán hàng</title>
    {{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"--}}
    {{--          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">--}}
    <link rel="stylesheet" href={{asset('css/pdf/warehouse-export-sell.css')}} />
</head>
<body>
<div id="pdf-warehouse-export-sell">
    <section class="head-bill">
        <div class="fs14">
            {{$compName ? $compName : ''}}
        </div>
        <div>
            {{$compAddress ? $compAddress : ''}}
        </div>
    </section>
    <section class="title-bill text-center">
        <h2>PHIẾU XUẤT KHO BÁN HÀNG</h2>
        <b>
            <div>
                Ngày {{date('d')}} tháng {{date('m')}} năm {{date('Y')}}
            </div>
            <div>
                Số: {{$model->code}}
            </div>
        </b>
    </section>
    <section class="info-buyer">
        <div><b>Tên khách hàng:</b> {{$model->buyer_name}}</div>
        <div><b>Địa chỉ:</b> {{$model->buyer_address}}</div>
        <div><b>Mã số thuế:</b> {{$model->buyer_tax_code}}</div>
        <div><b>Ghi chú:</b> {{$model->note}}</div>
        <div><b>Nhân viên bán hàng:</b> {{$model->admin ? $model->admin->name : ''}}</div>
    </section>
    <section class="products">
        <table class="table-product">
            <thead>
            <tr>
                <th class="w5">STT</th>
                <th class="w10">Mã hàng</th>
                <th class="w30">Tên hàng</th>
                <th class="w5">Đơn vị tính</th>
                <th class="w10">Số lượng</th>
                <th class="w10">Đơn giá</th>
                <th class="w10">Thành tiền</th>
            </tr>
            </thead>
            <tbody>
            @php
                $priceTotal = 0;
            @endphp
            @foreach($details as $index => $product)
                @php
                    $priceTotal += $product['into_money'];
                @endphp
                <tr>
                    <td class="text-center">{{$index + 1}}</td>
                    <td>{{$product->code}}</td>
                    <td>{{$product->name}}</td>
                    <td class="text-center">{{$product->unit}}</td>
                    <td class="text-center">{{$product->quantity}}</td>
                    <td class="text-right">{{$product->unit_price ? number_format($product->unit_price) : ''}}</td>
                    <td class="text-right">{{$product->into_money ? number_format($product->into_money) : ''}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <table class="table-product" style="text-align: right">
            <thead class="no-border">
            <tr>
                <th class="w5"></th>
                <th class="w10"></th>
                <th class="w30"></th>
                <th class="w5"></th>
                <th class="w10"></th>
                <th class="w10"></th>
                <th class="w10"></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="3">Tổng giá (VNĐ)</td>
                <td colspan="4"><b>{{$priceTotal ? number_format($priceTotal) : ''}}</b></td>
            </tr>
            <tr>
                <td><b>Thuế (%)</b></td>
                <td>{{$model->vat}}</td>
                <td colspan="1">Tiền thuế (VNĐ)</td>
                <td colspan="4"><b>{{$model->total_vat ? number_format($model->total_vat) : ''}}</b></td>
            </tr>
            <tr>
                <td colspan="3">Tổng tiền thanh toán (VNĐ)</td>
                <td colspan="4"><b>{{$model->total_payment ? number_format($model->total_payment) : ''}}</b></td>
            </tr>
            <tr>
                <td colspan="3">Số tiền bằng chữ (VNĐ)</td>
                <td colspan="4"><b>{{$model->total_payment ? \App\Helpers\AdminHelper::VndText(floatval($model->total_payment)) : ''}}</b></td>
            </tr>
            <tr>
                <td colspan="3">Nợ (VNĐ)</td>
                <td colspan="4"><b>{{$model->amount_owed ? number_format($model->amount_owed) : ''}}</b></td>
            </tr>
            <tr>
                <td colspan="3">Có (VNĐ)</td>
                <td colspan="4"><b>{{$model->amount_paid ? number_format($model->amount_paid) : ''}}</b></td>
            </tr>
            </tbody>
        </table>
    </section>
    <section>
        <div style="margin-top: 20px; margin-bottom: 20px">
            Xác nhận đã nhận đủ hàng.
        </div>
        <div class="text-right">
            <i>Ngày ... tháng ... năm {{date("Y")}}</i>
        </div>
        <table class="table-signature" style="width: 100%; text-align: center; font-size: 12px; vertical-align: top">
            <tr>
                <td>
                    <b>Người lập phiếu</b><br/>
                    <span>(Ký, họ tên)</span>
                </td>
                <td>
                    <b>Người nhận hàng</b><br/>
                    <span>(Ký, họ tên)</span>
                </td>
                <td>
                    <b>Thủ kho</b><br/>
                    <span>(Ký, họ tên)</span>
                </td>
                <td>
                    <b>Kế toán trưởng</b><br/>
                    <span>(Ký, họ tên)</span>
                </td>
                <td>
                    <b>Giám đốc</b><br/>
                    <span>(Ký, họ tên, đóng dấu)</span>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <b>Lê Văn Dũng</b>
                </td>
            </tr>
        </table>
    </section>
</div>
</body>
</html>
