<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lệnh sản xuất</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href={{asset('css/pdf/manufacture.css')}} />
</head>
<body>
<div id="pdf-manufacture">
    <section class="section-left">
        <table class="table-top">
            <thead></thead>
            <tbody>
            <tr>
                <td rowspan="2" class="w60 text-center text-bold">
                    Xưởng {{$model->material_type == \App\Models\Manufacture::MATERIAL_TYPE_METAL ? 'Kim Loại' : 'Phi Kim Loại'}}
                    - LỆNH SẢN XUẤT - {{$model->co->code}}
                </td>
                <td class="w10 fs10">Ngày:</td>
                <td class="w10 fs10">{{date("d/m/Y")}}</td>
                <td class="w10 fs10">Số lệnh sản xuất</td>
                <td class="w10 fs10"></td>
            </tr>
            <tr>
                <td class="fs10">Người theo dõi</td>
                <td class="fs10"></td>
                <td class="fs10">Ngày giao hàng</td>
                <td class="fs10"></td>
            </tr>
            </tbody>
        </table>
    </section>
    <section class="section-center w-100">
        <table class="w-100">
            <tr>
                <td>
                    <div class="block-signature w90">
                        <div class="signature-time">
                            ..... giờ ..... Ngày ...../...../ {{date("Y")}}
                        </div>
                        <div class="signature-title">
                            Kiểm soát
                        </div>
                        <div class="signature-content">

                        </div>
                        <div class="signature-name">
                            ĐÀO ĐÌNH HUY
                        </div>
                    </div>
                </td>
                <td>
                    <div class="block-signature">
                        <div class="signature-time">
                            ..... giờ ..... Ngày ...../...../ {{date("Y")}}
                        </div>
                        <div class="signature-title">
                            ĐĐSX
                        </div>
                        <div class="signature-content">

                        </div>
                        <div class="signature-name">
                            LÊ THANH SƠN
                        </div>
                    </div>
                </td>
                <td>
                    <div class="block-signature">
                        <div class="signature-time">
                            ..... giờ ..... Ngày ...../...../ {{date("Y")}}
                        </div>
                        <div class="signature-title">
                            Người thực hiện
                        </div>
                        <div class="signature-content">

                        </div>
                        <div class="signature-name">

                        </div>
                    </div>
                </td>
                <td>
                    <div class="block-signature">
                        <div class="signature-time">
                            ..... giờ ..... Ngày ...../...../ {{date("Y")}}
                        </div>
                        <div class="signature-title">
                            QC/Đóng gói
                        </div>
                        <div class="signature-content">

                        </div>
                        <div class="signature-name">

                        </div>
                    </div>
                </td>
                <td>
                    <div class="block-signature">
                        <div class="signature-time">
                            ..... giờ ..... Ngày ...../...../ {{date("Y")}}
                        </div>
                        <div class="signature-title">
                            Thủ kho
                        </div>
                        <div class="signature-content">

                        </div>
                        <div class="signature-name">
                            LÊ THANH SƠN
                        </div>
                    </div>
                </td>
                <td>
                    <div class="block-signature">
                        <div class="signature-time">
                            ..... giờ ..... Ngày ...../...../ {{date("Y")}}
                        </div>
                        <div class="signature-title">
                            Đóng kiện/Giao nhận
                        </div>
                        <div class="signature-content">

                        </div>
                        <div class="signature-name">
                            NGUYỄN THỊ KIM NGUYÊN
                        </div>
                    </div>
                </td>
                <td>
                    <div class="block-signature w90">
                        <div class="signature-time">
                            ..... giờ ..... Ngày ...../...../ {{date("Y")}}
                        </div>
                        <div class="signature-title">
                            Kiểm soát
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
    </section>
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
                <th class="w5">Ngày dự kiến</th>
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
            <?php $i=0 ?>
            @foreach($details as $key => $detail)
                <?php $i++;
                ?>
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
                <?php 
                    if( $i % 12 == 0 ){ echo '<tr class="breakNow"></tr>';}
                ?>
            @endforeach
            @for($i = 0; $i + count($details) <= 12; $i++)
                <tr>
                    <td>{{ $i + count($details) + 1  }}</td>
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
            </tbody>
        </table>
    </section>
</div>
</body>
</html>
