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
              {!! Form::open(array('route' => ['admin.warehouse-supply.index', $model], 'method' => 'get')) !!}
              <div class="input-group">
                <input type="text" name="key_word" class="form-control float-right" placeholder="Từ khoá" value="{{old('key_word')}}">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-default">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
                @permission('admin.warehouse-supply.import')
                <div class="ml-3">
                  <button type="button" class="btn btn-success import-supplies" data-toggle="modal" data-target="#import_supplies_modal">Import dữ liệu</button>
                </div>
                @endpermission
                @permission('admin.warehouse-supply.create')
                <div class="ml-3">
                  <a href="{{ route('admin.warehouse-supply.create', ['model' => $model]) }}" class="btn btn-primary">Thêm vật liệu</a>
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
                    <a class="nav-link{{ ($type === $model) ? ' active' : '' }}" href="{{ route('admin.warehouse-supply.index', ['model' => $type]) }}" role="tab" aria-selected="true">{{ $name }}</a>
                  </li>
                  @endforeach
                </ul>
              </div>
              <div class="card-body overflow-auto">
                <div class="tab-content">
                  <div class="tab-pane fade show active" role="tabpanel">
                    {!! Form::open(array('route' => ['admin.warehouse-remain.index', $model], 'method' => 'get')) !!}
                    <div class="input-group p-2">
                      <input type="text" name="code" class="form-control float-right" placeholder="Code" value="{{old('code')}}">
                      <input type="text" name="lotno" class="form-control float-right" placeholder="Lotno" value="{{old('Lotno')}}">
                    </div>
                    @switch($model)
                      @case ('supply')
                        <div class="input-group p-2">
                          <input type="text" name="from_ton_sl_cai" class="form-control float-right" placeholder="Tồn kho từ" value="{{old('from_ton_sl_cai')}}">
                          <input type="text" name="to_ton_sl_cai" class="form-control float-right" placeholder="Tồn kho đến" value="{{old('to_ton_sl_cai')}}">
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
                            @case ('supply')
                              <th>Đơn vị tính</th>
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
                         @foreach($warehouseRemains as $warehouseRemain)
                           <tr>
                             <td>{{ $warehouseRemain->l_id }}</td>
                             <td>{{ $warehouseRemain->code }}</td>
                             @switch ($model)
                              @case ('supply')
                                <td>{{ $warehouseRemain->dvt }}</td>
                                <td>{{ $warehouseRemain->ton_sl_cai }}</td>
                                @break
                             @endswitch
                             <td>{{ $warehouseRemain->lot_no }}</td>
                             <td>{{ $warehouseRemain->ghi_chu }}</td>
                             <td>{{ $warehouseRemain->date }}</td>
                             <td>
                               @permission('admin.warehouse-supply.edit')
                                 <a href="{{ route('admin.warehouse-supply.edit', ['model' => $model, 'id' => $warehouseRemain->l_id]) }}" role="button" class="btn btn-outline-primary btn-sm" title="Cập nhật"><i class="fas fa-solid fa-pen"></i></a>
                               @endpermission
                               @permission('admin.warehouse-supply.destroy')
                                 <a href="{{ route('admin.warehouse-supply.destroy', ['model' => $model, 'id' => $warehouseRemain->l_id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa Vật Liệu này không ?')"><i class="fas fa-trash-alt"></i></a>
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
                           @case ('supply')
                             <th>Đơn vị tính</th>
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
      @permission('admin.warehouse-supply.import')
      <div class="modal fade" id="import_supplies_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            {!! Form::open(array('route' => ['admin.warehouse-supply.import', $model], 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'import-supplies-form')) !!}
            <div class="modal-header bg-success">
              <h4 class="modal-title">Import dữ liệu</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <div class="text-right"><a href="{{ asset('samples/supplies/'.$model.'.xlsx') }}" title="File mẫu">Download file mẫu</a></div>
                <label>File import</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" name="import-supply" class="custom-file-input" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required />
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
    $(document).on('submit','#import-supplies-form',function(){
      $('#loading-all').addClass('show');
    });
  });
</script>
@endsection
