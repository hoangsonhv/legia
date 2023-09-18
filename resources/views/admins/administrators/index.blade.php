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
              {!! Form::open(array('route' => 'admin.administrator.index', 'method' => 'get')) !!}
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
                  <th>Tên</th>
                  <th>Tài Khoản</th>
                  <th>Email</th>
                  <th>Phòng ban & quyền</th>
                  <th>Trạng thái</th>
                  <th>Ngày tạo</th>
                  <th>&nbsp</th>
                </tr>
              </thead>
              <tbody>
                @foreach($admins as $administrator)
                  <tr>
                    <td>{{ $administrator->id }}</td>
                    <td>{{ $administrator->name }}</td>
                    <td>{{ $administrator->username }}</td>
                    <td>{{ $administrator->mail }}</td>
                    <td>{{ isset($administrator->roles->first->pivot->display_name) ? $administrator->roles->first->pivot->display_name : '' }}</td>
                    <td>
                      @if($administrator->status == 1)
                        <span class="badge bg-success">Đang hoạt động</span>
                      @else
                        <span class="badge bg-danger">Không hoạt động</span>
                      @endif
                    </td>
                    <td>{{ $administrator->created_at }}</td>
                    <td>
                      @permission('admin.administrator.edit')
                        <a href="{{ route('admin.administrator.edit', ['id' => $administrator->id]) }}" role="button" class="btn btn-outline-primary btn-sm" title="Cập nhật"><i class="fas fa-solid fa-pen"></i></a>
                      @endpermission
                      @permission('admin.administrator.destroy')
                        <a href="{{ route('admin.administrator.destroy', ['id' => $administrator->id]) }}" role="button" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa ID ' + {{ $administrator->id }} + ' không ?')" title="Xóa"><i class="fas fa-solid fa-trash"></i></a>
                      @endpermission
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="d-flex justify-content-center">
              {!! $admins->appends(session()->getOldInput())->links() !!}
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</section>
@endsection
