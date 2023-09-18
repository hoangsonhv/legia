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
              {!! Form::open(array('route' => 'admin.bank.index', 'method' => 'get')) !!}
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
                  <th>Tên ngân hàng</th>
                  <th>Tên tài khoản</th>
                  <th>Số tài khoản</th>
                  <th>Số dư tài khoản</th>
                  <th>Người thực hiện</th>
                  <th>&nbsp</th>
                </tr>
              </thead>
              <tbody>
                @foreach($banks as $bank)
                  <tr>
                    <td>{{ $bank->id }}</td>
                    <td>{{ $bank->name_bank }}</td>
                    <td>{{ $bank->account_name }}</td>
                    <td>{{ $bank->account_number }}</td>
                    <td>{{ number_format($bank->account_balance, 0)  }}</td>
                    <td>{{ $bank->admin ? $bank->admin->name : '' }}</td>
                    <td>
                      @permission('admin.bank.edit')
                        <a href="{{ route('admin.bank.edit', ['id' => $bank->id]) }}" role="button" class="btn btn-outline-primary btn-sm" title="Cập nhật"><i class="fas fa-solid fa-pen"></i></a>
                      @endpermission
                      @if($bank->type == \App\Models\Bank::TYPE_ATM)
                        @permission('admin.bank.destroy')
                          <a href="{{ route('admin.bank.destroy', ['id' => $bank->id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản ngân hàng này không ?')"><i class="fas fa-trash-alt"></i></a>
                        @endpermission
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="d-flex justify-content-center">
              {!! $banks->appends(session()->getOldInput())->links() !!}
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
