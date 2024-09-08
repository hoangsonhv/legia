@extends('layouts.admin')

@section('content')

@include('admins.breadcrumb')

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        @include('admins.message')
      </div>
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="card-tools">
              {!! Form::open(array('route' => ['admin.warehouse-remain.index', $model], 'method' => 'get')) !!}
              <div class="input-group">
                <input type="text" name="key_word" class="form-control float-right" placeholder="Từ khoá" value="{{old('key_word')}}">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-default">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
                @permission('admin.warehouse-remain.import')
                <div class="ml-3">
                  <button type="button" class="btn btn-success import-remains" data-toggle="modal" data-target="#import_remains_modal">Import dữ liệu</button>
                </div>
                @endpermission
                @permission('admin.warehouse-remain.create')
                <div class="ml-3">
                  <a href="{{ route('admin.warehouse-remain.create', ['model' => $model]) }}" class="btn btn-primary">Thêm vật liệu</a>
                </div>
                @endpermission
              </div>
              {!! Form::close() !!}
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <div class="card card-primary card-tabs">
              <div class="card-header">
                <ul class="nav nav-tabs" role="tablist">
                  @foreach($types as $type => $name)
                  <li class="nav-item">
                    <a class="nav-link{{ ($type === $model) ? ' active' : '' }}" href="{{ route('admin.warehouse-remain.index', ['model' => $type]) }}" role="tab" aria-selected="true">{{ $name }}</a>
                  </li>
                  @endforeach
                </ul>
              </div>
              <div class="card-body overflow-auto">
                <div class="tab-content">
                  <div class="tab-pane fade show active" role="tabpanel">
                    {!! Form::open(array('route' => ['admin.warehouse-remain.index', $model], 'method' => 'get')) !!}
                    <div class="input-group p-2">
                      <input type="number" name="code" class="form-control float-right" placeholder="Code" value="{{old('code')}}">
                      <input type="number" name="vat_lieu" class="form-control float-right" placeholder="Tên Vật liệu" value="{{old('vat_lieu')}}">
                      <input type="number" name="lotno" class="form-control float-right" placeholder="Lotno" value="{{old('Lotno')}}">
                    </div>
                    @switch($model)
                      @case ('ccdc')
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cai" class="form-control float-right" placeholder="Tồn kho từ" value="{{old('from_ton_sl_cai')}}">
                          <input type="number" name="to_ton_sl_cai" class="form-control float-right" placeholder="Tồn kho đến" value="{{old('to_ton_sl_cai')}}">
                        </div>
                        @break
                      @case ('daycaosusilicone')
                        <div class="input-group p-2">
                          <input type="number" name="from_size" class="form-control float-right" placeholder="Size từ" value="{{old('from_size')}}">
                          <input type="number" name="to_size" class="form-control float-right" placeholder="Size đến" value="{{old('to_size')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_m_cuon" class="form-control float-right" placeholder="M/ cuộn từ" value="{{old('from_m_cuon')}}">
                          <input type="number" name="to_m_cuon" class="form-control float-right" placeholder="M/ cuộn đến" value="{{old('to_m_cuon')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cuon" class="form-control float-right" placeholder="Tồn kho cuộn từ" value="{{old('from_ton_sl_cuon')}}">
                          <input type="number" name="to_ton_sl_cuon" class="form-control float-right" placeholder="Tồn kho cuộn đến" value="{{old('to_ton_sl_cuon')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_m" class="form-control float-right" placeholder="Tồn kho m từ" value="{{old('from_ton_sl_m')}}">
                          <input type="number" name="to_ton_sl_m" class="form-control float-right" placeholder="Tồn kho m đến" value="{{old('to_ton_sl_m')}}">
                        </div>
                        @break
                      @case ('dayceramic')
                        <div class="input-group p-2">
                          <input type="number" name="from_size" class="form-control float-right" placeholder="Size từ" value="{{old('from_size')}}">
                          <input type="number" name="to_size" class="form-control float-right" placeholder="Size đến" value="{{old('to_size')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_m_cuon" class="form-control float-right" placeholder="M/ cuộn từ" value="{{old('from_m_cuon')}}">
                          <input type="number" name="to_m_cuon" class="form-control float-right" placeholder="M/ cuộn đến" value="{{old('to_m_cuon')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cuon" class="form-control float-right" placeholder="Tồn kho cuộn từ" value="{{old('from_ton_sl_cuon')}}">
                          <input type="number" name="to_ton_sl_cuon" class="form-control float-right" placeholder="Tồn kho cuộn đến" value="{{old('to_ton_sl_cuon')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_m" class="form-control float-right" placeholder="Tồn kho m từ" value="{{old('from_ton_sl_m')}}">
                          <input type="number" name="to_ton_sl_m" class="form-control float-right" placeholder="Tồn kho m đến" value="{{old('to_ton_sl_m')}}">
                        </div>
                        @break
                      @case ('glandpacking')
                        <div class="input-group p-2">
                          <input type="number" name="from_size" class="form-control float-right" placeholder="Size từ" value="{{old('from_size')}}">
                          <input type="number" name="to_size" class="form-control float-right" placeholder="Size đến" value="{{old('to_size')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_m_cuon" class="form-control float-right" placeholder="M/ cuộn từ" value="{{old('from_m_cuon')}}">
                          <input type="number" name="to_m_cuon" class="form-control float-right" placeholder="M/ cuộn đến" value="{{old('to_m_cuon')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_trong_luong_cuon" class="form-control float-right" placeholder="Kg/ cuộn từ" value="{{old('from_trong_luong_cuon')}}">
                          <input type="number" name="to_trong_luong_cuon" class="form-control float-right" placeholder="Kg/ cuộn đến" value="{{old('to_trong_luong_cuon')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cuon" class="form-control float-right" placeholder="Tồn kho cuộn từ" value="{{old('from_ton_sl_cuon')}}">
                          <input type="number" name="to_ton_sl_cuon" class="form-control float-right" placeholder="Tồn kho cuộn đến" value="{{old('to_ton_sl_cuon')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_kg" class="form-control float-right" placeholder="Tồn kho KG từ" value="{{old('from_ton_sl_kg')}}">
                          <input type="number" name="to_ton_sl_kg" class="form-control float-right" placeholder="Tồn kho KG đến" value="{{old('to_ton_sl_kg')}}">
                        </div>
                        @break
                      @case ('nhuakythuatcayong')
                        <div class="input-group p-2">
                          <input type="number" name="from_size" class="form-control float-right" placeholder="Size từ" value="{{old('from_size')}}">
                          <input type="number" name="to_size" class="form-control float-right" placeholder="Size đến" value="{{old('to_size')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_m_cay" class="form-control float-right" placeholder="M/ cây từ" value="{{old('from_m_cay')}}">
                          <input type="number" name="to_m_cay" class="form-control float-right" placeholder="M/ cây đến" value="{{old('to_m_cay')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cay" class="form-control float-right" placeholder="Tồn kho cây từ" value="{{old('from_ton_sl_cay')}}">
                          <input type="number" name="to_ton_sl_cay" class="form-control float-right" placeholder="Tồn kho cây đến" value="{{old('to_ton_sl_cay')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_m" class="form-control float-right" placeholder="Tồn kho m từ" value="{{old('from_ton_sl_m')}}">
                          <input type="number" name="to_ton_sl_m" class="form-control float-right" placeholder="Tồn kho m đến" value="{{old('to_ton_sl_m')}}">
                        </div>
                        @break
                      @case ('ongglassepoxy')
                        <div class="input-group p-2">
                          <input type="number" name="from_size" class="form-control float-right" placeholder="Size từ" value="{{old('from_size')}}">
                          <input type="number" name="to_size" class="form-control float-right" placeholder="Size đến" value="{{old('to_size')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_m_cay" class="form-control float-right" placeholder="M/ cây từ" value="{{old('from_m_cay')}}">
                          <input type="number" name="to_m_cay" class="form-control float-right" placeholder="M/ cây đến" value="{{old('to_m_cay')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cay" class="form-control float-right" placeholder="Tồn kho cây từ" value="{{old('from_ton_sl_cay')}}">
                          <input type="number" name="to_ton_sl_cay" class="form-control float-right" placeholder="Tồn kho cây đến" value="{{old('to_ton_sl_cay')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_m" class="form-control float-right" placeholder="Tồn kho m từ" value="{{old('from_ton_sl_m')}}">
                          <input type="number" name="to_ton_sl_m" class="form-control float-right" placeholder="Tồn kho m đến" value="{{old('to_ton_sl_m')}}">
                        </div>
                        @break
                      @case ('phutungdungcu')
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cai" class="form-control float-right" placeholder="SL cái từ" value="{{old('from_ton_sl_cai')}}">
                          <input type="number" name="to_ton_sl_cai" class="form-control float-right" placeholder="SL cái đến" value="{{old('to_ton_sl_cai')}}">
                        </div>
                        @break
                      @case ('ptfecayong')
                        <div class="input-group p-2">
                          <input type="number" name="from_size" class="form-control float-right" placeholder="Size từ" value="{{old('from_size')}}">
                          <input type="number" name="to_size" class="form-control float-right" placeholder="Size đến" value="{{old('to_size')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_m_cay" class="form-control float-right" placeholder="M/ cây từ" value="{{old('from_m_cay')}}">
                          <input type="number" name="to_m_cay" class="form-control float-right" placeholder="M/ cây đến" value="{{old('to_m_cay')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cay" class="form-control float-right" placeholder="Tồn kho cây từ" value="{{old('from_ton_sl_cay')}}">
                          <input type="number" name="to_ton_sl_cay" class="form-control float-right" placeholder="Tồn kho cây đến" value="{{old('to_ton_sl_cay')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_m" class="form-control float-right" placeholder="Tồn kho m từ" value="{{old('from_ton_sl_m')}}">
                          <input type="number" name="to_ton_sl_m" class="form-control float-right" placeholder="Tồn kho m đến" value="{{old('to_ton_sl_m')}}">
                        </div>
                        @break
                      @case ('ndloaikhac')
                        <div class="input-group p-2">
                          <input type="number" name="from_size" class="form-control float-right" placeholder="Size từ" value="{{old('from_size')}}">
                          <input type="number" name="to_size" class="form-control float-right" placeholder="Size đến" value="{{old('to_size')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cai" class="form-control float-right" placeholder="SL cái từ" value="{{old('from_ton_sl_cai')}}">
                          <input type="number" name="to_ton_sl_cai" class="form-control float-right" placeholder="SL cái đến" value="{{old('to_ton_sl_cai')}}">
                        </div>
                        @break
                      @case ('nkloaikhac')
                        <div class="input-group p-2">
                          <input type="number" name="from_size" class="form-control float-right" placeholder="Size từ" value="{{old('from_size')}}">
                          <input type="number" name="to_size" class="form-control float-right" placeholder="Size đến" value="{{old('to_size')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cai" class="form-control float-right" placeholder="SL cái từ" value="{{old('from_ton_sl_cai')}}">
                          <input type="number" name="to_ton_sl_cai" class="form-control float-right" placeholder="SL cái đến" value="{{old('to_ton_sl_cai')}}">
                        </div>
                        @break
                      @case ('tpphikimloai')
                        <div class="input-group p-2">
                          <input type="number" name="from_do_day" class="form-control float-right" placeholder="Độ dày từ" value="{{old('from_do_day')}}">
                          <input type="number" name="to_do_day" class="form-control float-right" placeholder="Độ dày đến" value="{{old('to_do_day')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_sl_ton" class="form-control float-right" placeholder="SL từ" value="{{old('from_sl_ton')}}">
                          <input type="number" name="to_sl_ton" class="form-control float-right" placeholder="SL đến" value="{{old('to_sl_ton')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="muc_ap_luc" class="form-control float-right" placeholder="Mức áp lực" value="{{old('muc_ap_luc')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="tieu_chuan" class="form-control float-right" placeholder="Tiêu chuẩn" value="{{old('tieu_chuan')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="kich_co" class="form-control float-right" placeholder="Kích cỡ" value="{{old('kich_co')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="kich_thuoc" class="form-control float-right" placeholder="Kích thước" value="{{old('kich_thuoc')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="chuan_mat_bich" class="form-control float-right" placeholder="Chuẩn mặt bích" value="{{old('chuan_mat_bich')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="chuan_gasket" class="form-control float-right" placeholder="Chuẩn Gasket" value="{{old('chuan_gasket')}}">
                        </div>
                        @break
                            
                    @endswitch
                    <div class="input-group-append p-2">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search">Tìm kiếm</i>
                      </button>
                    </div>
                    {!! Form::close() !!}
                     <table id="dataTable" class="table table-hover text-nowrap">
                       <thead>
                         <tr>
                           <th>ID</th>
                           <th>Mã hàng hoá</th>
                           @switch ($model)
                            @case ('ccdc')
                              <th>Bộ Phận</th>
                              <th>Đơn vị tính</th>
                              <th>Tồn SL - Cái</th>
                              @break
                            @case ('daycaosusilicone')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>m / Cuộn</th>
                              <th>Tồn SL - Cuộn</th>
                              <th>Tồn SL - m</th>
                              @break
                            @case ('dayceramic')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>m / Cuộn</th>
                              <th>Tồn SL - Cuộn</th>
                              <th>Tồn SL - m</th>
                              @break
                            @case ('glandpacking')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>Trọng lượng Kg/Cuộn</th>
                              <th>m / Cuộn</th>
                              <th>Tồn SL - Cuộn</th>
                              <th>Tồn SL - Kg</th>
                              @break
                            @case ('nhuakythuatcayong')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>m / Cây</th>
                              <th>Tồn SL - Cây</th>
                              <th>Tồn SL - m</th>
                              @break
                            @case ('ongglassepoxy')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>m / Cây</th>
                              <th>Tồn SL - Cây</th>
                              <th>Tồn SL - m</th>
                              @break
                            @case ('phutungdungcu')
                              <th>Vật liệu</th>
                              <th>Cho máy móc, thiết bị</th>
                              <th>Tồn SL - Cái</th>
                              @break
                            @case ('ptfecayong')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>m / Cây</th>
                              <th>Tồn SL - Cây</th>
                              <th>Tồn SL - m</th>
                              @break
                            @case ('ndloaikhac')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>Tồn SL - Cái</th>
                              @break
                            @case ('nkloaikhac')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>Tồn SL - Cái</th>
                              @break
                            @case ('tpphikimloai')
                              <th>Vật liệu</th>
                              <th>Độ dày</th>
                              <th>Tiêu chuẩn</th>
                              <th>Mức áp lực</th>
                              <th>Kích cỡ</th>
                              <th>Kích thước</th>
                              <th>Chuẩn mặt bích</th>
                              <th>Chuẩn gasket</th>
                              <th>Đơn vị tính</th>
                              <th>Số lượng tồn</th>
                              @break
                           @endswitch
                           <th>Lot no</th>
                           <th>Ghi chú</th>
                           <th>Date</th>
                           <th>&nbsp</th>
                         </tr>
                       </thead>
                       <tbody>
                         @foreach($warehouseRemains as $warehouseRemain)
                           <tr>
                             <td>{{ $warehouseRemain->l_id }}</td>
                             <td>{{ $warehouseRemain->code }}</td>
                             @switch ($model)
                              @case ('ccdc')
                                <td>{{ $warehouseRemain->bo_phan }}</td>
                                <td>{{ $warehouseRemain->dvt }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cai }}</td>
                                @break
                              @case ('daycaosusilicone')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->m_cuon }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cuon }}</td>
                                <td>{{ $warehouseRemain->ton_sl_m }}</td>
                                @break
                              @case ('dayceramic')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->m_cuon }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cuon }}</td>
                                <td>{{ $warehouseRemain->ton_sl_m }}</td>
                                @break
                              @case ('glandpacking')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->trong_luong_cuon }}</td>
                                <td>{{ $warehouseRemain->m_cuon }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cuon }}</td>
                                <td>{{ $warehouseRemain->ton_sl_kg }}</td>
                                @break
                              @case ('nhuakythuatcayong')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->m_cay }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cay }}</td>
                                <td>{{ $warehouseRemain->ton_sl_m }}</td>
                                @break
                              @case ('ongglassepoxy')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->m_cay }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cay }}</td>
                                <td>{{ $warehouseRemain->ton_sl_m }}</td>
                                @break
                              @case ('phutungdungcu')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->cho_may_moc_thiet_bi }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cai }}</td>
                                @break
                              @case ('ptfecayong')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->m_cay }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cay }}</td>
                                <td>{{ $warehouseRemain->ton_sl_m }}</td>
                                @break
                              @case ('ndloaikhac')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cai }}</td>
                                @break
                              @case ('nkloaikhac')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cai }}</td>
                                @break
                              @case ('tpphikimloai')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->do_day }}</td>
                                <td>{{ $warehouseRemain->tieu_chuan }}</td>
                                <td>{{ $warehouseRemain->muc_ap_luc }}</td>
                                <td>{{ $warehouseRemain->kich_co }}</td>
                                <td>{{ $warehouseRemain->kich_thuoc }}</td>
                                <td>{{ $warehouseRemain->chuan_mat_bich }}</td>
                                <td>{{ $warehouseRemain->chuan_gasket }}</td>
                                <td>{{ $warehouseRemain->dvt }}</td>
                                <td>{{ $warehouseRemain->sl_ton }}</td>
                                @break
                             @endswitch
                             <td>{{ $warehouseRemain->lot_no }}</td>
                             <td>{{ $warehouseRemain->ghi_chu }}</td>
                             <td>{{ $warehouseRemain->date }}</td>
                             <td>
                               @permission('admin.warehouse-remain.edit')
                                 <a href="{{ route('admin.warehouse-remain.edit', ['model' => $model, 'id' => $warehouseRemain->l_id]) }}" role="button" class="btn btn-outline-primary btn-sm" title="Cập nhật"><i class="fas fa-solid fa-pen"></i></a>
                               @endpermission
                               @permission('admin.warehouse-remain.destroy')
                                 <a href="{{ route('admin.warehouse-remain.destroy', ['model' => $model, 'id' => $warehouseRemain->l_id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa Vật Liệu này không ?')"><i class="fas fa-trash-alt"></i></a>
                               @endpermission
                             </td>
                           </tr>
                         @endforeach
                       </tbody>
                       <tfoot>
                        <tr>
                          <th>ID</th>
                          <th>Mã hàng hoá</th>
                          @switch ($model)
                           @case ('ccdc')
                             <th>Bộ Phận</th>
                             <th>Đơn vị tính</th>
                             <th>Tồn SL - Cái</th>
                             @break
                           @case ('daycaosusilicone')
                             <th>Vật liệu</th>
                             <th>Size</th>
                             <th>m / Cuộn</th>
                             <th>Tồn SL - Cuộn</th>
                             <th>Tồn SL - m</th>
                             @break
                           @case ('dayceramic')
                             <th>Vật liệu</th>
                             <th>Size</th>
                             <th>m / Cuộn</th>
                             <th>Tồn SL - Cuộn</th>
                             <th>Tồn SL - m</th>
                             @break
                           @case ('glandpacking')
                             <th>Vật liệu</th>
                             <th>Size</th>
                             <th>Trọng lượng Kg/Cuộn</th>
                             <th>m / Cuộn</th>
                             <th>Tồn SL - Cuộn</th>
                             <th>Tồn SL - Kg</th>
                             @break
                           @case ('nhuakythuatcayong')
                             <th>Vật liệu</th>
                             <th>Size</th>
                             <th>m / Cây</th>
                             <th>Tồn SL - Cây</th>
                             <th>Tồn SL - m</th>
                             @break
                           @case ('ongglassepoxy')
                             <th>Vật liệu</th>
                             <th>Size</th>
                             <th>m / Cây</th>
                             <th>Tồn SL - Cây</th>
                             <th>Tồn SL - m</th>
                             @break
                           @case ('phutungdungcu')
                             <th>Vật liệu</th>
                             <th>Cho máy móc, thiết bị</th>
                             <th>Tồn SL - Cái</th>
                             @break
                           @case ('ptfecayong')
                             <th>Vật liệu</th>
                             <th>Size</th>
                             <th>m / Cây</th>
                             <th>Tồn SL - Cây</th>
                             <th>Tồn SL - m</th>
                             @break
                           @case ('ndloaikhac')
                             <th>Vật liệu</th>
                             <th>Size</th>
                             <th>Tồn SL - Cái</th>
                             @break
                           @case ('nkloaikhac')
                             <th>Vật liệu</th>
                             <th>Size</th>
                             <th>Tồn SL - Cái</th>
                             @break
                           @case ('tpphikimloai')
                             <th>Vật liệu</th>
                             <th>Độ dày</th>
                             <th>Tiêu chuẩn</th>
                             <th>Mức áp lực</th>
                             <th>Kích cỡ</th>
                             <th>Kích thước</th>
                             <th>Chuẩn mặt bích</th>
                             <th>Chuẩn gasket</th>
                             <th>Đơn vị tính</th>
                             <th>Số lượng tồn</th>
                             @break
                          @endswitch
                          <th>Lot no</th>
                          <th>Ghi chú</th>
                          <th>Date</th>
                          <th>&nbsp</th>
                        </tr>
                      </tfoot>
                     </table>
                     <div class="d-flex justify-content-center">
                       {!! $warehouseRemains->appends(session()->getOldInput())->links() !!}
                     </div>
                  </div>
                </div>
              </div>
              <!-- /.card -->
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      @permission('admin.warehouse-remain.import')
      <div class="modal fade" id="import_remains_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            {!! Form::open(array('route' => ['admin.warehouse-remain.import', $model], 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'import-remains-form')) !!}
            <div class="modal-header bg-success">
              <h4 class="modal-title">Import dữ liệu</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <div class="text-right"><a href="{{ asset('samples/remains/'.$model.'.xlsx') }}" title="File mẫu">Download file mẫu</a></div>
                <label>File import</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" name="import-remain" class="custom-file-input" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required />
                    <label class="custom-file-label">Chọn file</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Đóng</button>
              <button type="submit" class="btn bg-success">Import</button>
            </div>
            {!! Form::close() !!}
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      @endpermission
    </div>
  </div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/admin/dataTable.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script type="text/javascript">
  $( document ).ready(function() {
    // Init data
    bsCustomFileInput.init();
    // Event
    $(document).on('submit','#import-remains-form',function(){
      $('#loading-all').addClass('show');
    });
  });
</script>
@endsection
