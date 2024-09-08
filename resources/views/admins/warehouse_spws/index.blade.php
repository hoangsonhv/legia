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
              {!! Form::open(array('route' => ['admin.warehouse-spw.index', $model], 'method' => 'get')) !!}
              <div class="input-group">
                <input type="text" name="key_word" class="form-control float-right" placeholder="Từ khoá" value="{{old('key_word')}}">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-default">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
                @permission('admin.warehouse-spw.import')
                <div class="ml-3">
                  <button type="button" class="btn btn-success import-spws" data-toggle="modal" data-target="#import_spws_modal">Import dữ liệu</button>
                </div>
                @endpermission
                @permission('admin.warehouse-spw.create')
                <div class="ml-3">
                  <a href="{{ route('admin.warehouse-spw.create', ['model' => $model]) }}" class="btn btn-primary">Thêm vật liệu</a>
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
                    <a class="nav-link{{ ($type === $model) ? ' active' : '' }}" href="{{ route('admin.warehouse-spw.index', ['model' => $type]) }}" role="tab" aria-selected="true">{{ $name }}</a>
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
                      @case ('filler')
                        <div class="input-group p-2">
                          <input type="number" name="from_size" class="form-control float-right" placeholder="Size từ" value="{{old('from_size')}}">
                          <input type="number" name="to_size" class="form-control float-right" placeholder="Size đến" value="{{old('to_size')}}">
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
                      @case ('glandpackinglatty')
                        <div class="input-group p-2">
                          <input type="number" name="from_size" class="form-control float-right" placeholder="Size từ" value="{{old('from_size')}}">
                          <input type="number" name="to_size" class="form-control float-right" placeholder="Size đến" value="{{old('to_size')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cuon" class="form-control float-right" placeholder="Tồn kho cuộn từ" value="{{old('from_ton_sl_cuon')}}">
                          <input type="number" name="to_ton_sl_cuon" class="form-control float-right" placeholder="Tồn kho cuộn đến" value="{{old('to_ton_sl_cuon')}}">
                        </div>
                        @break
                      @case ('hoop')
                        <div class="input-group p-2">
                          <input type="number" name="from_size" class="form-control float-right" placeholder="Size từ" value="{{old('from_size')}}">
                          <input type="number" name="to_size" class="form-control float-right" placeholder="Size đến" value="{{old('to_size')}}">
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
                      @case ('oring')
                        <div class="input-group p-2">
                          <input type="number" name="from_size" class="form-control float-right" placeholder="Size từ" value="{{old('from_size')}}">
                          <input type="number" name="to_size" class="form-control float-right" placeholder="Size đến" value="{{old('to_size')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cai" class="form-control float-right" placeholder="SL cái từ" value="{{old('from_ton_sl_cai')}}">
                          <input type="number" name="to_ton_sl_cai" class="form-control float-right" placeholder="SL cái đến" value="{{old('to_ton_sl_cai')}}">
                        </div>
                        @break
                      @case ('ptfeenvelope')
                        <div class="input-group p-2">
                          <input type="number" name="from_do_day" class="form-control float-right" placeholder="Độ dày từ" value="{{old('from_do_day')}}">
                          <input type="number" name="to_do_day" class="form-control float-right" placeholder="Độ dày đến" value="{{old('to_do_day')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_size" class="form-control float-right" placeholder="Size từ" value="{{old('from_size')}}">
                          <input type="number" name="to_size" class="form-control float-right" placeholder="Size đến" value="{{old('to_size')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cai" class="form-control float-right" placeholder="SL cái từ" value="{{old('from_ton_sl_cai')}}">
                          <input type="number" name="to_ton_sl_cai" class="form-control float-right" placeholder="SL cái đến" value="{{old('to_ton_sl_cai')}}">
                        </div>
                        @break
                      @case ('ptfetape')
                        <div class="input-group p-2">
                          <input type="number" name="from_size" class="form-control float-right" placeholder="Size từ" value="{{old('from_size')}}">
                          <input type="number" name="to_size" class="form-control float-right" placeholder="Size đến" value="{{old('to_size')}}">
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
                      @case ('rtj')
                        <div class="input-group p-2">
                          <input type="number" name="from_size" class="form-control float-right" placeholder="Size từ" value="{{old('from_size')}}">
                          <input type="number" name="to_size" class="form-control float-right" placeholder="Size đến" value="{{old('to_size')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cai" class="form-control float-right" placeholder="SL cái từ" value="{{old('from_ton_sl_cai')}}">
                          <input type="number" name="to_ton_sl_cai" class="form-control float-right" placeholder="SL cái đến" value="{{old('to_ton_sl_cai')}}">
                        </div>
                        @break
                      @case ('thanhphamswg')
                        <div class="input-group p-2">
                          <input type="number" name="from_inner" class="form-control float-right" placeholder="Inner từ" value="{{old('from_inner')}}">
                          <input type="number" name="to_inner" class="form-control float-right" placeholder="Inner đến" value="{{old('to_inner')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_hoop" class="form-control float-right" placeholder="Hoop từ" value="{{old('from_hoop')}}">
                          <input type="number" name="to_hoop" class="form-control float-right" placeholder="Hoop đến" value="{{old('to_hoop')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_filler" class="form-control float-right" placeholder="Filler từ" value="{{old('from_filler')}}">
                          <input type="number" name="to_filler" class="form-control float-right" placeholder="Filler đến" value="{{old('to_filler')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_outer" class="form-control float-right" placeholder="Outer từ" value="{{old('from_outer')}}">
                          <input type="number" name="to_outer" class="form-control float-right" placeholder="Outer đến" value="{{old('to_outer')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_thick" class="form-control float-right" placeholder="Thick từ" value="{{old('from_thick')}}">
                          <input type="number" name="to_thick" class="form-control float-right" placeholder="Thick đến" value="{{old('to_thick')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cai" class="form-control float-right" placeholder="SL cái từ" value="{{old('from_ton_sl_cai')}}">
                          <input type="number" name="to_ton_sl_cai" class="form-control float-right" placeholder="SL cái đến" value="{{old('to_ton_sl_cai')}}">
                        </div>
                        @break
                      @case ('vanhtinhinnerswg')
                        <div class="input-group p-2">
                          <input type="number" name="from_do_day" class="form-control float-right" placeholder="Độ dày từ" value="{{old('from_do_day')}}">
                          <input type="number" name="to_do_day" class="form-control float-right" placeholder="Độ dày đến" value="{{old('to_do_day')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_d1" class="form-control float-right" placeholder="D1 từ" value="{{old('from_d1')}}">
                          <input type="number" name="to_d1" class="form-control float-right" placeholder="D1 đến" value="{{old('to_d1')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_d2" class="form-control float-right" placeholder="D2 từ" value="{{old('from_d2')}}">
                          <input type="number" name="to_d2" class="form-control float-right" placeholder="D2 đến" value="{{old('to_d2')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cai" class="form-control float-right" placeholder="SL cái từ" value="{{old('from_ton_sl_cai')}}">
                          <input type="number" name="to_ton_sl_cai" class="form-control float-right" placeholder="SL cái đến" value="{{old('to_ton_sl_cai')}}">
                        </div>
                        @break
                      @case ('vanhtinhouterswg')
                        <div class="input-group p-2">
                          <input type="number" name="from_do_day" class="form-control float-right" placeholder="Độ dày từ" value="{{old('from_do_day')}}">
                          <input type="number" name="to_do_day" class="form-control float-right" placeholder="Độ dày đến" value="{{old('to_do_day')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_D3" class="form-control float-right" placeholder="D3 từ" value="{{old('from_D3')}}">
                          <input type="number" name="to_D3" class="form-control float-right" placeholder="D3 đến" value="{{old('to_D3')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_D4" class="form-control float-right" placeholder="D4 từ" value="{{old('from_D4')}}">
                          <input type="number" name="to_D4" class="form-control float-right" placeholder="D4 đến" value="{{old('to_D4')}}">
                        </div>
                        <div class="input-group p-2">
                          <input type="number" name="from_ton_sl_cai" class="form-control float-right" placeholder="SL cái từ" value="{{old('from_ton_sl_cai')}}">
                          <input type="number" name="to_ton_sl_cai" class="form-control float-right" placeholder="SL cái đến" value="{{old('to_ton_sl_cai')}}">
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
                            @case ('filler')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>Tồn SL - Cuộn</th>
                              <th>Tồn SL - KG</th>
                              @break
                            @case ('glandpackinglatty')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>Tồn SL - Cuộn</th>
                              @break
                            @case ('hoop')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>Tồn SL - Cuộn</th>
                              <th>Tồn SL - KG</th>
                              @break
                            @case ('oring')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>Tồn SL - Cái</th>
                              @break
                            @case ('ptfeenvelope')
                              <th>Vật liệu</th>
                              <th>Độ dày</th>
                              <th>Size</th>
                              <th>Tồn SL - Cái</th>
                              @break
                            @case ('ptfetape')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>Tồn SL - Cuộn</th>
                              <th>Tồn SL - m</th>
                              @break
                            @case ('rtj')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>Tồn SL - Cái</th>
                              @break
                            @case ('thanhphamswg')
                              <th>Inner</th>
                              <th>Hoop</th>
                              <th>Filler</th>
                              <th>Outer</th>
                              <th>Thick</th>
                              <th>Tồn SL - Cái</th>
                              @break
                            @case ('vanhtinhinnerswg')
                              <th>Vật liệu</th>
                              <th>Độ dày</th>
                              <th>D1</th>
                              <th>D2</th>
                              <th>Tồn SL - Cái</th>
                              @break
                            @case ('vanhtinhouterswg')
                              <th>Vật liệu</th>
                              <th>Độ dày</th>
                              <th>D3</th>
                              <th>D4</th>
                              <th>Tồn SL - Cái</th>
                              @break
                           @endswitch
                           <th>Lot no</th>
                           <th>Ghi chú</th>
                           <th>Date</th>
                           <th>&nbsp</th>
                         </tr>
                       </thead>
                       <tbody>
                         @foreach($warehouseSpws as $warehouseSpw)
                           <tr>
                             <td>{{ $warehouseSpw->l_id }}</td>
                             <td>{{ $warehouseSpw->code }}</td>
                             @switch ($model)
                              @case ('filler')
                                <td>{{ $warehouseSpw->vat_lieu }}</td>
                                <td>{{ $warehouseSpw->size }}</td>
                                <td>{{ $warehouseSpw->ton_sl_cuon }}</td>
                                <td>{{ $warehouseSpw->ton_sl_kg }}</td>
                                @break
                              @case ('glandpackinglatty')
                                <td>{{ $warehouseSpw->vat_lieu }}</td>
                                <td>{{ $warehouseSpw->size }}</td>
                                <td>{{ $warehouseSpw->ton_sl_cuon }}</td>
                                @break
                              @case ('hoop')
                                <td>{{ $warehouseSpw->vat_lieu }}</td>
                                <td>{{ $warehouseSpw->size }}</td>
                                <td>{{ $warehouseSpw->ton_sl_cuon }}</td>
                                <td>{{ $warehouseSpw->ton_sl_kg }}</td>
                                @break
                              @case ('oring')
                                <td>{{ $warehouseSpw->vat_lieu }}</td>
                                <td>{{ $warehouseSpw->size }}</td>
                                <td>{{ $warehouseSpw->ton_sl_cai + $warehouseSpw->sl_ton }}</td>
                                @break
                              @case ('ptfeenvelope')
                                <td>{{ $warehouseSpw->vat_lieu }}</td>
                                <td>{{ $warehouseSpw->do_day }}</td>
                                <td>{{ $warehouseSpw->size }}</td>
                                <td>{{ $warehouseSpw->ton_sl_cai + $warehouseSpw->sl_ton }}</td>
                                @break
                              @case ('ptfetape')
                                <td>{{ $warehouseSpw->vat_lieu }}</td>
                                <td>{{ $warehouseSpw->size }}</td>
                                <td>{{ $warehouseSpw->ton_sl_cuon }}</td>
                                <td>{{ $warehouseSpw->ton_sl_m }}</td>
                                @break
                              @case ('rtj')
                                <td>{{ $warehouseSpw->vat_lieu }}</td>
                                <td>{{ $warehouseSpw->size }}</td>
                                <td>{{ $warehouseSpw->ton_sl_cai + $warehouseSpw->sl_ton }}</td>
                                @break
                              @case ('thanhphamswg')
                                <td>{{ $warehouseSpw->inner }}</td>
                                <td>{{ $warehouseSpw->hoop }}</td>
                                <td>{{ $warehouseSpw->filler }}</td>
                                <td>{{ $warehouseSpw->outer }}</td>
                                <td>{{ $warehouseSpw->thick }}</td>
                                <td>{{ $warehouseSpw->ton_sl_cai + $warehouseSpw->sl_ton }}</td>
                                @break
                              @case ('vanhtinhinnerswg')
                                <td>{{ $warehouseSpw->vat_lieu }}</td>
                                <td>{{ $warehouseSpw->do_day }}</td>
                                <td>{{ $warehouseSpw->d1 }}</td>
                                <td>{{ $warehouseSpw->d2 }}</td>
                                <td>{{ $warehouseSpw->ton_sl_cai + $warehouseSpw->sl_ton }}</td>
                                @break
                              @case ('vanhtinhouterswg')
                                <td>{{ $warehouseSpw->vat_lieu }}</td>
                                <td>{{ $warehouseSpw->do_day }}</td>
                                <td>{{ $warehouseSpw->d3 }}</td>
                                <td>{{ $warehouseSpw->d4 }}</td>
                                <td>{{ $warehouseSpw->ton_sl_cai + $warehouseSpw->sl_ton }}</td>
                                @break
                             @endswitch
                             <td>{{ $warehouseSpw->lot_no }}</td>
                             <td>{{ $warehouseSpw->ghi_chu }}</td>
                             <td>{{ $warehouseSpw->date }}</td>
                             <td>
                               @permission('admin.warehouse-spw.edit')
                                 <a href="{{ route('admin.warehouse-spw.edit', ['model' => $model, 'id' => $warehouseSpw->l_id]) }}" role="button" class="btn btn-outline-primary btn-sm" title="Cập nhật"><i class="fas fa-solid fa-pen"></i></a>
                               @endpermission
                               @permission('admin.warehouse-spw.destroy')
                                 <a href="{{ route('admin.warehouse-spw.destroy', ['model' => $model, 'id' => $warehouseSpw->l_id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa Vật Liệu này không ?')"><i class="fas fa-trash-alt"></i></a>
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
                           @case ('filler')
                             <th>Vật liệu</th>
                             <th>Size</th>
                             <th>Tồn SL - Cuộn</th>
                             <th>Tồn SL - KG</th>
                             @break
                           @case ('glandpackinglatty')
                             <th>Vật liệu</th>
                             <th>Size</th>
                             <th>Tồn SL - Cuộn</th>
                             @break
                           @case ('hoop')
                             <th>Vật liệu</th>
                             <th>Size</th>
                             <th>Tồn SL - Cuộn</th>
                             <th>Tồn SL - KG</th>
                             @break
                           @case ('oring')
                             <th>Vật liệu</th>
                             <th>Size</th>
                             <th>Tồn SL - Cái</th>
                             @break
                           @case ('ptfeenvelope')
                             <th>Vật liệu</th>
                             <th>Độ dày</th>
                             <th>Size</th>
                             <th>Tồn SL - Cái</th>
                             @break
                           @case ('ptfetape')
                             <th>Vật liệu</th>
                             <th>Size</th>
                             <th>Tồn SL - Cuộn</th>
                             <th>Tồn SL - m</th>
                             @break
                           @case ('rtj')
                             <th>Vật liệu</th>
                             <th>Size</th>
                             <th>Tồn SL - Cái</th>
                             @break
                           @case ('thanhphamswg')
                             <th>Inner</th>
                             <th>Hoop</th>
                             <th>Filler</th>
                             <th>Outer</th>
                             <th>Thick</th>
                             <th>Tồn SL - Cái</th>
                             @break
                           @case ('vanhtinhinnerswg')
                             <th>Vật liệu</th>
                             <th>Độ dày</th>
                             <th>D1</th>
                             <th>D2</th>
                             <th>Tồn SL - Cái</th>
                             @break
                           @case ('vanhtinhouterswg')
                             <th>Vật liệu</th>
                             <th>Độ dày</th>
                             <th>D3</th>
                             <th>D4</th>
                             <th>Tồn SL - Cái</th>
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
                       {!! $warehouseSpws->appends(session()->getOldInput())->links() !!}
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
      @permission('admin.warehouse-spw.import')
      <div class="modal fade" id="import_spws_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            {!! Form::open(array('route' => ['admin.warehouse-spw.import', $model], 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'import-spws-form')) !!}
            <div class="modal-header bg-success">
              <h4 class="modal-title">Import dữ liệu</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <div class="text-right"><a href="{{ asset('samples/spws/'.$model.'.xlsx') }}" title="File mẫu">Download file mẫu</a></div>
                <label>File import</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" name="import-spw" class="custom-file-input" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required />
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
    $(document).on('submit','#import-spws-form',function(){
      $('#loading-all').addClass('show');
    });
  });
</script>
@endsection
