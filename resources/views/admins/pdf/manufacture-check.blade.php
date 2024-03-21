<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lệnh sản xuất</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href={{asset('css/pdf/manufacture-check.css')}} />
</head>
<body>
<div id="pdf-manufacture">
    <div class="section-left">
        <table class="w-100">
            <tr>
                <td class="w20">
                    <div class="block-left">
                        <img width="220px" height="100px" src="{{asset('images/logo.jpg')}}"/>
                    </div>
                </td>
                <td class="w55">
                    <div class="block-center">
                        <div class="line-height1">
                            <div>CÔNG TY TNHH SX-TM-DV VẬT LIỆU LÀM KÍN LÊ GIA</div>
                            <div>
                                PHIẾU KIỂM TRA THÀNH
                                PHẨM {{$model->material_type == \App\Models\Manufacture::MATERIAL_TYPE_METAL ? 'KIM LOẠI' : 'PHI KIM LOẠI'}}
                            </div>
                        </div>
                        <div class="table-signature">
                            <table>
                                <tr>
                                    <td class="mr-2">
                                        <div class="block-signature w90">
                                            <div class="signature-time">
                                                ..... giờ ..... Ngày ...../...../ {{date("Y")}}
                                            </div>
                                            <div class="signature-title">
                                                Người kiểm tra
                                            </div>
                                            <div class="signature-content">

                                            </div>
                                            <div class="signature-name">

                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="block-signature w90">
                                            <div class="signature-time">
                                                ..... giờ ..... Ngày ...../...../ {{date("Y")}}
                                            </div>
                                            <div class="signature-title">
                                                Duyệt
                                            </div>
                                            <div class="signature-content">

                                            </div>
                                            <div class="signature-name">
                                                ĐÀO ĐÌNH HUY
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
                <td class="w25">
                    <div class="block-right">
                        <table class="table-right w-100">
                            <tr>
                                <td class="td-table-right text-right">Số CO:</td>
                                <td class="td-table-right" style="width: 170px">{{$model->co->code}}</td>
                            </tr>
                            <tr>
                                <td class="td-table-right text-right">Số lệnh SX:</td>
                                <td class="td-table-right" style="width: 170px"></td>
                            </tr>
                            <tr>
                                <td class="td-table-right text-right">Ngày SX:</td>
                                <td class="td-table-right"></td>
                            </tr>
                            <tr>
                                <td class="td-table-right">Người theo dõi:</td>
                                <td class="td-table-right"></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <section class="right">
        <table class="table-material w-100">
            <thead>
            <tr>
                <th class="w1">Số TT</th>
                <th class="w10">Mã số HH</th>
                <th class="w20">Vật liệu</th>
                <th class="w4">Độ dày</th>
                <th class="w7">Tiểu chuẩn</th>
                <th class="w4">Size</th>
                <th colspan="7">Kích thước</th>
                <th class="w7">Chuẩn mặt bích</th>
                <th class="w7">Chuẩn Gasket/B.Về</th>
                <th class="w4">ĐV tính</th>
                <th class="w4">Số lượng</th>
                <th class="w5">Ghi chú</th>
                <th class="w7">Ngày dự kiến</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>OD/W</th>
                <th>x</th>
                <th>ID/L</th>
                <th>Tâm C</th>
                <th>Số lỗ</th>
                <th>x</th>
                <th>D lỗ</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($details as $key => $detail)
                @php
                    $offerPrice = new \App\Models\OfferPrice;
                    $kichThuot = [];
                    if($detail->offerPrice) {
                        $offerPrice = $detail->offerPrice;
                        $kichThuot = explode(" x ", $offerPrice->kich_thuoc);
                    }
                @endphp
                <tr>
                    <td>{{$key + 1}}</td>
                    <td>{{$offerPrice->code}}</td>
                    <td>{{$offerPrice->loai_vat_lieu}}</td>
                    <td>{{$offerPrice->do_day}}</td>
                    <td>{{$offerPrice->tieu_chuan}}</td>
                    <td>{{$offerPrice->kich_co}}</td>
                    <td>{{isset($kichThuot[0]) ? $kichThuot[0] : ''}}</td>
                    <td>x</td>
                    <td>{{isset($kichThuot[1]) ? $kichThuot[1] : ''}}</td>
                    <td>{{isset($kichThuot[2]) ? $kichThuot[2] : ''}}</td>
                    <td>x</td>
                    <td>{{isset($kichThuot[3]) ? $kichThuot[3] : ''}}</td>
                    <td>{{isset($kichThuot[4]) ? $kichThuot[4] : ''}}</td>
                    <td>{{$offerPrice->chuan_bich}}</td>
                    <td>{{$offerPrice->chuan_gasket}}</td>
                    <td>{{$offerPrice->dv_tinh}}</td>
                    <td>{{$offerPrice->so_luong}}</td>
                    <td></td>
                    <td></td>
                </tr>
                @php
                    if($offerPrice->so_luong <= 10) {
                        $lines=1;
                    } else if($offerPrice->so_luong > 10 && $offerPrice->so_luong <= 50) {
                        $lines=2;
                    } else if($offerPrice->so_luong > 50 && $offerPrice->so_luong <= 100){
                        $lines=3;
                    } else if($offerPrice->so_luong > 100 && $offerPrice->so_luong <= 200){
                        $lines=4;
                    } else if($offerPrice->so_luong > 200 && $offerPrice->so_luong <= 500){
                        $lines=5;
                    } else if($offerPrice->so_luong > 500 && $offerPrice->so_luong <= 5000){
                        $lines=10;
                    } else if($offerPrice->so_luong > 1000){
                        $lines=15;
                    }
                @endphp
                @for($i = 0; $i <= $lines - 1 ; $i++)
                    <tr style="height: 30px;">
                        <td><span style="visibility: hidden">test</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            @endforeach
            </tbody>
        </table>
    </section>
</div>
</body>
</html>