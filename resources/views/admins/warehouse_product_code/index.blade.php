@extends('layouts.admin')

@section('content')

@include('admins.breadcrumb')

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 text-right mb-2">
        @permission('admin.warehouse-product-code.create')
        <a href="{{route('admin.warehouse-product-code.create')}}">
          <button class="btn btn-success">
            <i class="nav-icon fas fa-plus" aria-hidden="true"></i>
            Thêm
          </button>
        </a>
        @endpermission
        @permission('admin.warehouse-product-code.import')
          <button
            type="button"
            class="btn btn-success import-warehouse-product-code"
            data-toggle="modal"
            data-target="#import-warehouse-product-code"
          >
            Import dữ liệu
          </button>
        @endpermission
        <a href="{{asset('/samples/warehouse-product-code/ma-hang-hoa.xlsx')}}">
          <button class="btn btn-primary">
            <i class="nav-icon fas fa-file-download" aria-hidden="true"></i>
            Download mẫu
          </button>
        </a>
      </div>
      <div class="col-12">
        @include('admins.message')
      </div>
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="card-tools">
              {!! Form::open(array('route' => 'admin.warehouse-product-code.index', 'method' => 'get')) !!}
              <div class="input-group">
                <input type="text" name="key_word" class="form-control float-right" placeholder="Từ khoá" value="{{old('key_word')}}">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-default">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
              {!! Form::close() !!}
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Mã hàng hóa</th>
                  <th>Tên hàng hóa</th>
                  <th>Nhóm hàng hóa</th>
                  <th>Người thực hiện</th>
                  <th>&nbsp</th>
                </tr>
              </thead>
              <tbody>
                @foreach($datas as $data)
                  <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->code }}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->group ? $data->group->name : '' }}</td>
                    <td>{{ $data->admin ? $data->admin->name : '' }}</td>
                    <td>
                      @permission('admin.warehouse-product-code.edit')
                        <a href="{{ route('admin.warehouse-product-code.edit', ['id' => $data->id]) }}"
                           role="button"
                           class="btn btn-outline-primary btn-sm"
                           title="Cập nhật">
                          <i class="fas fa-solid fa-pen"></i>
                        </a>
                      @endpermission
                      @permission('admin.warehouse-product-code.destroy')
                      <a href="{{ route('admin.warehouse-product-code.destroy', ['id' => $data->id]) }}"
                         class="btn btn-danger btn-sm"
                         onclick="return confirm('Bạn có chắc chắn muốn xóa mã hàng hóa này không ?')">
                        <i class="fas fa-trash-alt"></i>
                      </a>
                      @endpermission
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="d-flex justify-content-center">
              {!! $datas->appends(session()->getOldInput())->links() !!}
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
        @permission('admin.warehouse-product-code.import')
        <div class="modal fade" id="import-warehouse-product-code">
          <div class="modal-dialog">
            <div class="modal-content">
              {!! Form::open(array('route' => ['admin.warehouse-product-code.import', null], 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'import-plates-form')) !!}
              <div class="modal-header bg-success">
                <h4 class="modWal-title">Import dữ liệu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>File import</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input
                        type="file"
                        name="file"
                        class="custom-file-input"
                        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                        required
                      />
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
      $(document).on('submit','#import-warehouse-product-code',function(){
        $('#loading-all').addClass('show');
      });
    });
  </script>
@endsection
