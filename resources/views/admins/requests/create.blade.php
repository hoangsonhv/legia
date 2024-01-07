@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
<style type="text/css">
  .block-file {
    margin-bottom: 10px;
  }
</style>
@endsection

@section('content')

@include('admins.breadcrumb')

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        @include('admins.message')
      </div>
      <div class="col-12">
        <div class="card form-root">
          {!! Form::open(array('route' => 'admin.request.store', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
            <div class="card-body">
              @if($co)
              <div class="form-group">
                <label for="co_id">CO</label>
                {!! Form::select('co_id', $co, null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
              </div>
              @endif
              <div class="form-group">
                <label for="category">Danh mục<b style="color: red;"> (*)</b></label>
                {!! Form::select('category', $categories, null, array('class' => 'form-control form-select', 'required' => 'required')) !!}
              </div>
              <div class="form-group">
                <label for="note">Ghi chú</label>
                {!! Form::text('note', null, array('class' => 'form-control')) !!}
              </div>
              <div class="form-group">
                <label>Chứng từ đi kèm</label>
                <div class="input-group block-file">
                  <div class="custom-file">
                    <input type="file" name="accompanying_document[]" class="custom-file-input" multiple />
                    <label class="custom-file-label">Chọn file</label>
                  </div>
                </div>
                <button type="button" class="btn btn-success add-upload">
                  Thêm file upload
                </button>
              </div>
            </div>
            @if($co)
            <div class="card-body offer-price">
              {{-- @include('admins.coes.includes.list-products', ['warehouses' => $warehouses, 'collect' => true, 'notAction' => true]) --}}
              @include('admins.coes.includes.list-products', ['warehouses' => $warehouses, 'collect' => true])
            </div>
            <div class="card-body check-warehouse">
              @include('admins.coes.includes.list-warehouses',['warehouses' => $listWarehouse])
            </div>
            @endif
            <div class="card-body">
              <h3 class="title text-primary">Nội dung</h3>
              @if ($co)    
                @include('admins.requests.includes.list-materials', ['co' => $co])
              @else 
                @include('admins.requests.includes.list-service')
              @endif
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Lưu thông tin</button>
              <a href="{{ route('admin.request.index') }}" class="btn btn-default">Quay lại</a>
            </div>
          {!! Form::close() !!}
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
        @include('admins.requests.includes.search-material', ['url' => route('admin.co.get-material')])
        @include('admins.requests.includes.select-warehouse', ['url' => route('admin.warehouse.show-form-create')])
      </div>
    </div>
  </div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/admin/requests.js') }}"></script>
<script type="text/javascript">
  $( document ).ready(function() {
    // Init data
    bsCustomFileInput.init();
  });
</script>
@endsection
