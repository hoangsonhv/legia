<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Lệnh sản xuất</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href={{ asset('css/pdf/manufacture-check.css') }} />
</head>

<body>
    <div id="pdf-manufacture" class="container">
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td rowspan="5" style="padding-right: 10px;">
                    <img width="220px" height="100px" src="{{ asset('images/logo.jpg') }}" />
                </td>
                <td colspan="2" style="font-weight: bold;width: 800px">CÔNG TY TNHH SX TM DV VẬT LIỆU LÀM KÍN LÊ GIA
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight: bold;">Địa chỉ : 26/12E, Ấp Xuân Thới Đông 1, Xã Xuân Thới Đông,
                    Huyện Hóc Môn, Tp.HCM </td>
                {{-- <td>26/12E, Ấp Xuân Thới Đông 1, Xã Xuân</td> --}}
            </tr>
            <tr>
                <td style="font-weight: bold;">Điện thoại :</td>
                <td>+84 283 620 8651/620 8653/620 8654</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Fax :</td>
                <td>+84 283 811 1867</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Email:</td>
                <td>sales@legiaseal.com</td>
            </tr>

            <tr style="font-weight: bold; text-align: center;">
                <td colspan="7" style="padding: 10px;font-size: 18px">PHIẾU XUẤT KHO NGUYÊN VẬT LIỆU SẢN XUẤT</td>
            </tr>
            <tr>
                <td><span style="margin-left: 50px">Số:</span> <span style="margin-left: 50px">XKSX190001</span></td>

            </tr>
            <tr>
                <td><span style="margin-left: 50px"> Ngày: </span> <span
                        style="margin-left: 50px">{{ date('d/n/Y', strtotime(now())) }}
                    </span>
                </td>

            </tr>
            <tr>
                <td>@if($co)
                    <span style="margin-left: 50px"> Ref: </span> <span style="margin-left: 50px">
                        {{ $co->code }}</span>
                    @endif
                    
                </td>
            </tr>
        </table>

        <table cellspacing="0" cellpadding="5" style="margin-top: 20px">
            <tr style="border: 1px solid black">
                <th style="border: 1px solid black">Số TT</th>
                <th style="border: 1px solid black">Mã HH</th>
                <th style="border: 1px solid black">Loại Vật liệu</th>
                <th style="border: 1px solid black">Độ dày (mm)</th>
                <th style="border: 1px solid black">Tiêu chuẩn</th>
                <th style="border: 1px solid black">K.Cỡ</th>
                <th style="border: 1px solid black">Kích thước (mm)</th>
                <th style="border: 1px solid black">Đ/v tính</th>
                <th style="border: 1px solid black">Số lượng</th>
            </tr>
            @if (!empty($products))
                @foreach ($products as $index => $product)
                
                    @php
                        $base_warehouse = \App\Models\Warehouse\BaseWarehouseCommon::where('l_id', $product['merchandise_id'])->first();
                        $merchandise = \App\Helpers\WarehouseHelper::getModel($base_warehouse->model_type)
                            ->where('l_id', $product['merchandise_id'])
                            ->first();
                    @endphp
                    <tr style="border: 1px solid black">
                        <td style="border: 1px solid black">{{ $index + 1 }}</td>
                        <td style="border: 1px solid black">{{ $product['code'] }}</td>
                        <td style="border: 1px solid black">{{$merchandise->vat_lieu}}</td>
                        <td style="border: 1px solid black">{{$merchandise->do_day}}</td>
                        <td style="border: 1px solid black">{{$merchandise->tieu_chuan}}</td>
                        <td style="border: 1px solid black">{{$merchandise->kich_co}}</td>
                        <td style="border: 1px solid black">{{$merchandise->kich_thuoc}}</td>
                        <td style="border: 1px solid black">{{ $product['unit'] }}</td>
                        <td style="border: 1px solid black">{{ $merchandise->ton_kho[\App\Helpers\WarehouseHelper::groupTonKhoKey($merchandise->model_type)]}}</td>
                    </tr>
                @endforeach
            @endif


            <tr>
                <td style="width: 50px"></td>
                <td>BÊN GIAO (kho)</td>
                <td colspan="6">
                </td>
                <td>BÊN NHẬN (sản xuất)</td>
            </tr>
            <tr>
                <td></td>
                <td>Bộ phận: </td>
                <td colspan="6"></td>
                <td>Bộ phận: </td>
            </tr>
            <tr>
                <td></td>
                <td>ký & họ tên </td>
                <td colspan="6"></td>
                <td>ký & họ tên </td>
            </tr>
        </table>


    </div>
</body>

</html>
<style>
    .container{
        transform: rotate(0deg) !important;
    }

</style>