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
              {!! Form::open(array('route' => ['admin.warehouse-plate.index', $model], 'method' => 'get')) !!}
              <div class="input-group">
                <input type="text" name="key_word" class="form-control float-right " placeholder="Từ khoá" value="{{old('key_word')}}">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-default">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
                @permission('admin.warehouse-plate.import')
                <div class="ml-3">
                  <button type="button" class="btn btn-success import-plates" data-toggle="modal" data-target="#import_plates_modal">Import dữ liệu</button>
                </div>
                @endpermission
                @permission('admin.warehouse-plate.create')
                <div class="ml-xl-3  p-sm-0">
                  <a href="{{ route('admin.warehouse-plate.create', ['model' => $model]) }}" class="btn btn-primary">Thêm vật liệu</a>
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
                    <a class="nav-link{{ ($type === $model) ? ' active' : '' }}" href="{{ route('admin.warehouse-plate.index', ['model' => $type]) }}" role="tab" aria-selected="true">{{ $name }}</a>
                  </li>
                  @endforeach
                </ul>
              </div>
              <div class="card-body overflow-auto">
                <div class="tab-content">
                  <div class="tab-pane fade show active" role="tabpanel">
                    {!! Form::open(array('route' => ['admin.warehouse-plate.index', $model], 'method' => 'get')) !!}
                    <div class="input-group p-2">
                      <input type="number" name="code" class="form-control float-right" placeholder="Code" value="{{old('code')}}">
                      <input type="number" name="vat_lieu" class="form-control float-right" placeholder="Tên Vật liệu" value="{{old('vat_lieu')}}">
                      <input type="number" name="lotno" class="form-control float-right" placeholder="Lotno" value="{{old('Lotno')}}">
                    </div>
                    <div class="input-group p-2">
                      <input type="number" name="from_do_day" class="form-control float-right" placeholder="Độ dày từ" value="{{old('from_do_day')}}">
                      <input type="number" name="to_do_day" class="form-control float-right" placeholder="Độ dày đến" value="{{old('to_do_day')}}">
                    </div>
                    <div class="input-group p-2">
                      <input type="number" name="from_dia_w_w1" class="form-control float-right" placeholder="Dia W W1 từ" value="{{old('from_dia_w_w1')}}">
                      <input type="number" name="to_dia_w_w1" class="form-control float-right" placeholder="Dia W W1 đến" value="{{old('to_dia_w_w1')}}">
                    </div>
                    <div class="input-group p-2">
                      <input type="number" name="from_l_l1" class="form-control float-right" placeholder="l l1 từ" value="{{old('from_l_l1')}}">
                      <input type="number" name="to_l_l1" class="form-control float-right" placeholder="l l1 đến" value="{{old('to_l_l1')}}">
                    </div>
                    <div class="input-group p-2">
                      <input type="number" name="from_w2" class="form-control float-right" placeholder="w2 từ" value="{{old('from_w2')}}">
                      <input type="number" name="to_w2" class="form-control float-right" placeholder="w2 đến" value="{{old('to_w2')}}">
                    </div>
                    <div class="input-group p-2">
                      <input type="number" name="from_l2" class="form-control float-right" placeholder="l2 từ" value="{{old('from_l2')}}">
                      <input type="number" name="to_l2" class="form-control float-right" placeholder="l2 đến" value="{{old('to_l2')}}">
                    </div>
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
                           <th>Vật liệu</th>
                           <th>Hình dạng</th>
                           <th>Độ dày</th>
                           <th>Lot no</th>
                           <th>Ghi chú</th>
                           <th>Date</th>
                           <th>{{\App\Helpers\WarehouseHelper::translateAtt('dia_w_w1')}}</th>
                           <th>{{\App\Helpers\WarehouseHelper::translateAtt('l_l1')}}</th>
                           <th>{{\App\Helpers\WarehouseHelper::translateAtt('w2')}}</th>
                           <th>{{\App\Helpers\WarehouseHelper::translateAtt('l2')}}</th>
                           <th>Tồn SL - Tấm</th>
                           <th>Tồn SL - m2</th>
                           <th>&nbsp</th>
                         </tr>
                       </thead>
                       <tbody>
                         @foreach($warehousePlates as $warehousePlate)
                         <tr>
                             <td>{{ $warehousePlate->l_id }}</td>
                             <td>{{ $warehousePlate->code }}</td>
                             <td>{{ $warehousePlate->vat_lieu }}</td>
                             <td>{{ $warehousePlate->hinh_dang }}</td>
                             <td>{{ $warehousePlate->do_day }}</td>
                             <td>{{ $warehousePlate->lot_no }}</td>
                             <td>{{ $warehousePlate->ghi_chu }}</td>
                             <td>{{ $warehousePlate->date }}</td>
                             <td>{{ $warehousePlate->dia_w_w1 }}</td>
                             <td>{{ $warehousePlate->l_l1 }}</td>
                             <td>{{ $warehousePlate->w2 }}</td>
                             <td>{{ $warehousePlate->l2 }}</td>
                             <td>{{ $warehousePlate->ton_kho['ton_sl_tam'] }}</td>
                             <td>{{ $warehousePlate->ton_kho['ton_sl_m2'] }}</td>
                             <td>
                               @permission('admin.warehouse-plate.edit')
                                 <a href="{{ route('admin.warehouse-plate.edit', ['model' => $model, 'id' => $warehousePlate->l_id]) }}" role="button" class="btn btn-outline-primary btn-sm" title="Cập nhật"><i class="fas fa-solid fa-pen"></i></a>
                               @endpermission
                               @permission('admin.warehouse-plate.destroy')
                                 <a href="{{ route('admin.warehouse-plate.destroy', ['model' => $model, 'id' => $warehousePlate->l_id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa Vật Liệu này không ?')"><i class="fas fa-trash-alt"></i></a>
                               @endpermission
                             </td>
                           </tr>
                         @endforeach
                       </tbody>
                       <tfoot>
                        <tr>
                          <th>ID</th>
                          <th>Mã hàng hoá</th>
                          <th>Vật liệu</th>
                          <th>Hình dạng</th>
                          <th>Độ dày</th>
                          <th>Lot no</th>
                          <th>Ghi chú</th>
                          <th>Date</th>
                          <th>Tồn SL - Tấm</th>
                          <th>Tồn SL - m2</th>
                          <th>&nbsp</th>
                        </tr>
                      </tfoot>
                     </table>
                     <div class="d-flex justify-content-center">
                       {!! $warehousePlates->appends(session()->getOldInput())->links() !!}
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
      @permission('admin.warehouse-plate.import')
      <div class="modal fade" id="import_plates_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            {!! Form::open(array('route' => ['admin.warehouse-plate.import', $model], 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'import-plates-form')) !!}
            <div class="modal-header bg-success">
              <h4 class="modal-title">Import dữ liệu</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <div class="text-right"><a href="{{ asset('samples/plates/'.$model.'.xlsx') }}" title="File mẫu">Download file mẫu</a></div>
                <label>File import</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" name="import-plate" class="custom-file-input" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required />
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
    $(document).on('submit','#import-plates-form',function(){
      $('#loading-all').addClass('show');
    });
  });
</script>
@endsection
