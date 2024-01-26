<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lệnh sản xuất</title>
    
</head>
<body>
<div class="container">
    <div class="header">
        <div class="logo">
            <img width="220px" height="100px" src="{{asset('images/logo.jpg')}}"/>
        </div>
        <div class="information">
            <p style="font-weight: bold">
                KÍN LÊ GIA
            </p>
            <p style="font-weight: bold">
                Địa chỉ : 26/12E, Ấp Xuân Thới Đông 1, Xã Xuân Thới Đông, Huyện Hóc Môn, Tp.HCM
            </p>
          <div class="flex">
            <div class="information__left">
                <p style="font-weight: bold">
                    Điện thoại :
                </p>
                <p style="font-weight: bold">
                    Fax :  
                </p>
                <p style="font-weight: bold">
                    Email :      
                </p>
                <p style="font-weight: bold">
                    Web :    
                </p>
            </div>
            <div class="information__right">
                <p>
                    +84 283 620 8651/620 8653/620 8654
                </p>
                <p>
                    +84 283 811 1867	
                </p>
                <p>
                    sales@legiaseal.com	      
                </p>
                <p>
                    http://legia-sealingmaterial.vn		    
                </p>
            </div>
          </div>
        </div>

       
    </div>
    <div class="second_part">
        <p class="text-center" style="font-weight: bold">
            PHIẾU XUẤT KHO NGUYÊN VẬT LIỆU SẢN XUẤT	
        </p>
        <div class="second_part-two">
            <div class="second_part-left">
                <p>
                    Số:
                </p>
                <p>
                    Ngày:
                </p>
                <p>
                    Ref:
                </p>
            </div>
            <div class="second_part-left">
                <p>
                    XKSX190001
                </p>
                <p>
                    26/7/2019
                </p>
                <p>
                    CO1900001
                </p>
            </div>
        </div>
    </div>
    <div class="third-part">
        <table  cellpadding="5" style="width: 100%;margin-top: 20px">
  <tr>
    <th>Số TT</th>
    <th>Mã HH</th>
    <th>Loại Vật liệu</th>
    <th>Độ dày (mm)</th>
    <th>Tiêu chuẩn</th>
    <th>K.Cỡ</th>
    <th>Kích thước (mm)</th>
    <th>Đ/v tính</th>
    <th>Số lượng</th>
  </tr>
  <tr>
    <td>1</td>
    <td>VQ02 030</td>
    <td>VS6602- Non Asbestos Gasket Sheet</td>
    <td>3mm</td>
    <td></td> <!-- Điền thông tin tiêu chuẩn vào đây -->
    <td></td> <!-- Điền thông tin kích cỡ vào đây -->
    <td>1270 x 1270</td>
    <td>Tấm</td>
    <td>4</td>
  </tr>
</table>    
    <div class="fourth-part">
        <div class="fourth-part_left">
            <p style="font-weight: bold">
                BÊN GIAO: (kho)
            </p>
            <p>
                Bộ phận:
            </p>
            <p>
                Ký & họ tên
            </p>
        </div>
        <div class="fourth-part_right">
            <p style="font-weight: bold">
                BÊN NHẬN: (sản xuất)
            </p>
            <p>
                Bộ phận:
            </p>
            <p>
                Ký & họ tên
            </p>
        </div>
    </div>
        </div>

</div>
</body>
</html>

<style>
    p{
        padding: 3px;
        margin: 0;
    }
  .header{
     display: flex;
     width: 100%;
  }
  .flex{
    display: flex;
  }
  .information__right{
    margin-left: 100px;
  }
  .text-center{
    text-align: center;
  }
  .flex-col{
    flex-direction: column;
  }
  .second_part{
    margin-top: 20px;
  }
  .container{
    width: 1000px;
  }
  .second_part-two{
    margin-left: 20px;
    display: flex;
  }
  .second_part-left{
    margin-left: 20px;  
  }
  /* table{
    border: 1px solid black;
  } */
  table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
.fourth-part{
    margin-top: 40px;
    display: flex;
    justify-content: space-between;
    padding: 0 60px;
}
</style>