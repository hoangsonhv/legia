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
              {!! Form::open(array('route' => 'admin.logadmin.index', 'method' => 'get')) !!}
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
                  <th>IP</th>
                  <th>Trình duyệt</th>
                  <th>URL</th>
                  <th>Ngày tạo</th>
                  <th>&nbsp</th>
                </tr>
              </thead>
              <tbody>
                @foreach($logadmins as $logadmin)
                  <tr>
                    <td>{{ $logadmin->admin_id }}</td>
                    <td>{{ $logadmin->admin_name }}</td>
                    <td>{{ $logadmin->ip }}</td>
                    <td>{{ Str::limit($logadmin->browser, 70) }}</td>
                    <td>{{ Str::limit($logadmin->link, 30) }}</td>
                    <td>{{ $logadmin->created_at }}</td>
                    <td>
                      @permission('admin.logadmin.show')
                        <a href="{{ route('admin.logadmin.show', ['id' => $logadmin->id]) }}" role="button" class="btn btn-outline-primary btn-sm" title="Chi tiết"><i class="fas fa-info-circle"></i></a>
                      @endpermission
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="d-flex justify-content-center">
              {!! $logadmins->appends(session()->getOldInput())->links() !!}
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
