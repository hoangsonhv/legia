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
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane fade show active" role="tabpanel">
                     <table class="table table-hover text-nowrap">
                       <thead>
                         <tr>
                           <th>ID</th>
                           <th>Mã hàng hoá</th>
                           @switch ($model)
                            @case ('ccdc')
                              <th>Bộ Phận</th>
                              <th>Đơn vị tính</th>
                              <th>SL</th>
                              <th>Tồn SL - Cái</th>
                              @break
                            @case ('daycaosusilicone')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>m / Cuộn</th>
                              <th>SL - Cuộn</th>
                              <th>SL - m</th>
                              <th>Tồn SL - Cuộn</th>
                              <th>Tồn SL - m</th>
                              @break
                            @case ('dayceramic')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>m / Cuộn</th>
                              <th>SL - Cuộn</th>
                              <th>SL - m</th>
                              <th>Tồn SL - Cuộn</th>
                              <th>Tồn SL - m</th>
                              @break
                            @case ('glandpacking')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>Trọng lượng Kg/Cuộn</th>
                              <th>m / Cuộn</th>
                              <th>SL - Cuộn</th>
                              <th>SL - Kg</th>
                              <th>Tồn SL - Cuộn</th>
                              <th>Tồn SL - Kg</th>
                              @break
                            @case ('nhuakythuatcayong')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>m / Cây</th>
                              <th>SL - Cây</th>
                              <th>SL - m</th>
                              <th>Tồn SL - Cây</th>
                              <th>Tồn SL - m</th>
                              @break
                            @case ('ongglassepoxy')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>m / Cây</th>
                              <th>SL - Cây</th>
                              <th>SL - m</th>
                              <th>Tồn SL - Cây</th>
                              <th>Tồn SL - m</th>
                              @break
                            @case ('phutungdungcu')
                              <th>Vật liệu</th>
                              <th>Cho máy móc, thiết bị</th>
                              <th>SL - Cái</th>
                              <th>Tồn SL - Cái</th>
                              @break
                            @case ('ptfecayong')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>m / Cây</th>
                              <th>SL - Cây</th>
                              <th>SL - m</th>
                              <th>Tồn SL - Cây</th>
                              <th>Tồn SL - m</th>
                              @break
                            @case ('ndloaikhac')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>SL - Cái</th>
                              <th>Tồn SL - Cái</th>
                              @break
                            @case ('nkloaikhac')
                              <th>Vật liệu</th>
                              <th>Size</th>
                              <th>SL - Cái</th>
                              <th>Tồn SL - Cái</th>
                              @break
                           @endswitch
                           <th>&nbsp</th>
                         </tr>
                       </thead>
                       <tbody>
                         @foreach($warehouseRemains as $warehouseRemain)
                           <tr>
                             <td>{{ $warehouseRemain->id }}</td>
                             <td>{{ $warehouseRemain->code }}</td>
                             @switch ($model)
                              @case ('ccdc')
                                <td>{{ $warehouseRemain->bo_phan }}</td>
                                <td>{{ $warehouseRemain->dvt }}</td>
                                <td>{{ $warehouseRemain->sl }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cai }}</td>
                                @break
                              @case ('daycaosusilicone')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->m_cuon }}</td>
                                <td>{{ $warehouseRemain->sl_cuon }}</td>
                                <td>{{ $warehouseRemain->sl_m }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cuon }}</td>
                                <td>{{ $warehouseRemain->ton_sl_m }}</td>
                                @break
                              @case ('dayceramic')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->m_cuon }}</td>
                                <td>{{ $warehouseRemain->sl_cuon }}</td>
                                <td>{{ $warehouseRemain->sl_m }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cuon }}</td>
                                <td>{{ $warehouseRemain->ton_sl_m }}</td>
                                @break
                              @case ('glandpacking')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->trong_luong_kg_cuon }}</td>
                                <td>{{ $warehouseRemain->m_cuon }}</td>
                                <td>{{ $warehouseRemain->sl_cuon }}</td>
                                <td>{{ $warehouseRemain->sl_kg }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cuon }}</td>
                                <td>{{ $warehouseRemain->ton_sl_kg }}</td>
                                @break
                              @case ('nhuakythuatcayong')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->m_cay }}</td>
                                <td>{{ $warehouseRemain->sl_cay }}</td>
                                <td>{{ $warehouseRemain->sl_m }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cay }}</td>
                                <td>{{ $warehouseRemain->ton_sl_m }}</td>
                                @break
                              @case ('ongglassepoxy')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->m_cay }}</td>
                                <td>{{ $warehouseRemain->sl_cay }}</td>
                                <td>{{ $warehouseRemain->sl_m }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cay }}</td>
                                <td>{{ $warehouseRemain->ton_sl_m }}</td>
                                @break
                              @case ('phutungdungcu')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->cho_maymoc_thietbi }}</td>
                                <td>{{ $warehouseRemain->sl_cai }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cai }}</td>
                                @break
                              @case ('ptfecayong')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->m_cay }}</td>
                                <td>{{ $warehouseRemain->sl_cay }}</td>
                                <td>{{ $warehouseRemain->sl_m }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cay }}</td>
                                <td>{{ $warehouseRemain->ton_sl_m }}</td>
                                @break
                              @case ('ndloaikhac')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->sl_cai }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cai }}</td>
                                @break
                              @case ('nkloaikhac')
                                <td>{{ $warehouseRemain->vat_lieu }}</td>
                                <td>{{ $warehouseRemain->size }}</td>
                                <td>{{ $warehouseRemain->sl_cai }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cai }}</td>
                                @break
                             @endswitch
                             <td>
                               @permission('admin.warehouse-remain.edit')
                                 <a href="{{ route('admin.warehouse-remain.edit', ['model' => $model, 'id' => $warehouseRemain->id]) }}" role="button" class="btn btn-outline-primary btn-sm" title="Cập nhật"><i class="fas fa-solid fa-pen"></i></a>
                               @endpermission
                               @permission('admin.warehouse-remain.destroy')
                                 <a href="{{ route('admin.warehouse-remain.destroy', ['model' => $model, 'id' => $warehouseRemain->id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa Vật Liệu này không ?')"><i class="fas fa-trash-alt"></i></a>
                               @endpermission
                             </td>
                           </tr>
                         @endforeach
                       </tbody>
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
