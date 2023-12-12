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
              {!! Form::open(array('route' => 'admin.co-tmp.index', 'method' => 'get')) !!}
              <div class="input-group">
                {!! Form::select('core_customer_id', $coreCustomers, null, array('class' => 'form-control mr-1 float-right select2')) !!}
                {!! Form::select('status', $statuses, null, array('class' => 'form-control mr-1 float-right')) !!}
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
                  <th>STT</th>
                  <th>Mã khách hàng</th>
                  <th>Tên khách hàng</th>
                  <th>Số báo giá</th>
                  <th>Tổng tiền</th>
                  <th>Lợi nhuận</th>
                  <th>Trạng thái</th>
                  <th>Thời gian</th>
                  <th>Người thực hiện</th>
                  <th>&nbsp</th>
                </tr>
              </thead>
              <tbody>
                @foreach($coes as $key => $co)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $co->core_customer ? $co->core_customer->code : '' }}</td>
                    <td>{{ $co->customer ? $co->customer->ten : 0 }}</td>
                    <td>{{ $co->so_bao_gia }}</td>
                    <td>
                      <span class="badge bg-danger">
                        {{ number_format($co->tong_gia + $co->vat) }}
                      </span>
                    </td>
                    <td>
                      <span class="badge bg-success">
                        {{ number_format(($co->tong_gia + $co->vat) * 0.3) }}
                      </span>
                    </td>
                    <td>
                      @if($co->tong_gia < $limitApprovalCg)
                        <span class="badge bg-success">
                          Không cần xét duyệt
                        </span>
                      @elseif($co->status == \App\Enums\ProcessStatus::Approved)
                        <span class="badge bg-success">
                          {{ \App\Enums\ProcessStatus::all()[$co->status] }}
                        </span>
                      @elseif($co->status == \App\Enums\ProcessStatus::Unapproved)
                        <span class="badge bg-danger">
                          {{ \App\Enums\ProcessStatus::all()[$co->status] }}
                        </span>
                        @if($co->note)
                        <div><small class="text-white bg-warning p-1 p-1" style="border-radius: 3px;">{{$co->note}}</small></div>
                        @endif
                      @else
                        <span class="badge bg-warning">
                          {{ \App\Enums\ProcessStatus::all()[$co->status] }}
                        </span>
                      @endif
                    </td>
                    <td>
                      {{dateTimeFormat($co->created_at)}}
                    </td>
                    <td>
                      @if($co->admin)
                        {{ $co->admin->name }}
                      @endif
                    </td>
                    <td>
                      @permission('admin.co-tmp.edit')
                        <a href="{{ route('admin.co-tmp.edit', ['id' => $co->id]) }}" role="button" class="btn btn-outline-primary btn-sm" title="Cập nhật"><i class="fas fa-solid fa-pen"></i></a>
                      @endpermission
                      @permission('admin.co-tmp.destroy')
                        <a href="{{ route('admin.co-tmp.destroy', ['id' => $co->id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa Chào Giá này không ?')"><i class="fas fa-trash-alt"></i></a>
                      @endpermission
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="d-flex justify-content-center">
              {!! $coes->appends(session()->getOldInput())->links() !!}
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
@section('js')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('.select2').select2();
    });
  </script>
@endsection
@section('css')
  <style>
    .select2-container .select2-selection--single {
      height: calc(2.25rem + 2px) !important;
      margin-right: .25rem!important;
    }
  </style>
@endsection

