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
              {!! Form::open(array('route' => 'admin.receipt.index', 'method' => 'get')) !!}
              <div class="input-group">
                {!! Form::select('status', $statuses, null, array('class' => 'form-control mr-1 float-right d-none', 'id' => 'selectedStatus')) !!}
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
          <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            @php
            $selected = 0;
            if(Session::has('_old_input.status')) {
              $selected = Session::get('_old_input.status');
            }
            @endphp
            @foreach ($statuses as $key => $item)
            <li class="nav-item">
              <a class="nav-link {{ $selected == $key ? 'active' :'' }}" onclick=updateSelectedStatus({{$key}}) data-toggle="pill" href="#" role="tab" aria-controls="pills-home" aria-selected="true"> {{ $item }} <span class="badge badge-danger">{{ $count[$key] }}</span></a>
            </li>
            @endforeach
          </ul>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>Mã CO</th>
                  <th>Mã Phiếu Chi</th>
                  <th>Mã Phiếu Thu</th>
                  <th>Người thực hiện</th>
                  <th>Phương thức thanh toán</th>
                  <th>Tổng tiền</th>
                  <th>Trạng thái</th>
                  <th>&nbsp</th>
                </tr>
              </thead>
              <tbody>
                @foreach($receipts as $receipt)
                  <tr>
                    <td>{{ $receipt->co_code }}</td>
                    <td>{{ !empty($receipt->payment) ? $receipt->payment->code : '' }}</td>
                    <td>{{ $receipt->code }}</td>
                    <td>
                      @if($receipt->admin)
                        {{ $receipt->admin->name }}
                      @endif
                    </td>
                    <td>{!! isset($paymentMethods[$receipt->payment_method]) ? $paymentMethods[$receipt->payment_method] : '' !!}</td>
                    <td>{{ number_format($receipt->money_total) }}</td>
                    <td>
                      @if($receipt->status == \App\Enums\ProcessStatus::Approved)
                        <span class="badge bg-success">
                          {{ \App\Enums\ProcessStatus::all()[$receipt->status] }}
                        </span>
                      @elseif($receipt->status == \App\Enums\ProcessStatus::Unapproved)
                        <span class="badge bg-danger">
                          {{ \App\Enums\ProcessStatus::all()[$receipt->status] }}
                        </span>
                      @else
                        <span class="badge bg-warning">
                          {{ \App\Enums\ProcessStatus::all()[$receipt->status] }}
                        </span>
                      @endif
                    </td>
                    <td>
                      <button type="button" class="btn btn-success accompanying_document ml-2" content="{{ $receipt->accompanying_document }}">Chứng từ</button>
                      @permission('admin.receipt.edit')
                        <a href="{{ route('admin.receipt.edit', ['id' => $receipt->id]) }}" role="button" class="btn btn-outline-primary btn-sm" title="Cập nhật"><i class="fas fa-solid fa-pen"></i></a>
                      @endpermission
                      @permission('admin.receipt.destroy')
                        <a href="{{ route('admin.receipt.destroy', ['id' => $receipt->id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa Phiếu Nhận này không ?')"><i class="fas fa-trash-alt"></i></a>
                      @endpermission
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="d-flex justify-content-center">
              {!! $receipts->appends(session()->getOldInput())->links() !!}
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <div class="modal fade" id="accompanying_document_modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-success">
                <h4 class="modal-title">Chứng từ đi kèm</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body"></div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Đóng</button>
              </div>
              {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/admin/receipts.js') }}"></script>
<script type="text/javascript">
  $( document ).ready(function() {
    $('.accompanying_document').click(function() {
      var content = $(this).attr('content');
      var data    = JSON.parse(content);
      if (data.length) {
        var eleModal = $('#accompanying_document_modal');
        eleModal.find('.modal-body').empty();
        $.each(data, function( index, value ) {
          eleModal.find('.modal-body').append(checkFile(value));
        });
        eleModal.modal('toggle');
      } else {
        alert('Không tồn tại chứng từ đi kèm.');
      }
    });
  });
  function updateSelectedStatus(status) {
    $('#selectedStatus').val(status);
    $('form').submit();
  }
</script>
@endsection
