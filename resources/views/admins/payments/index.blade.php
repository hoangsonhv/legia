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
              @if(!empty($countPending))
              @include('admins.includes.noti-pending', ['label' => sprintf('Có %s Phiếu Chi chưa xử lý', $countPending), 'url' => route('admin.payment.index', ['used' => 0])])
              @endif
              {!! Form::open(array('route' => 'admin.payment.index', 'method' => 'get')) !!}
              <div class="input-group">
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
                  <th>Mã CO</th>
                  <th>Mã Phiếu Yêu Cầu</th>
                  <th>Mã Phiếu Chi</th>
                  <th>Danh mục</th>
                  <th>Người thực hiện</th>
                  <th>Người nhận</th>
                  <th>Số tiền</th>
                  <th>Trạng thái</th>
                  <th>&nbsp</th>
                </tr>
              </thead>
              <tbody>
                @foreach($payments as $payment)
                  <tr>
                    <td>{{ $payment->co_code }}</td>
                    <td>{{ (!empty($payment->co->first()) && $payment->co->first()->request->first()) ? $payment->co->first()->request->first()->code : '' }}</td>
                    <td>{{ $payment->code }}</td>
                    <td>{!! isset($categories[$payment->category]) ? $categories[$payment->category] : '' !!}</td>
                    <td>
                      @if($payment->admin)
                        {{ $payment->admin->name }}
                      @endif
                    </td>
                    <td>{{ $payment->name_receiver }}</td>
                    <td>{{ number_format($payment->money_total) }}</td>
                    <td>
                      @if($payment->status == \App\Enums\ProcessStatus::Approved)
                        <span class="badge bg-success">
                          {{ \App\Enums\ProcessStatus::all()[$payment->status] }}
                        </span>
                      @elseif($payment->status == \App\Enums\ProcessStatus::Unapproved)
                        <span class="badge bg-danger">
                          {{ \App\Enums\ProcessStatus::all()[$payment->status] }}
                        </span>
                      @else
                        <span class="badge bg-warning">
                          {{ \App\Enums\ProcessStatus::all()[$payment->status] }}
                        </span>
                      @endif
                    </td>
                    <td>
                      <button type="button" class="btn btn-success accompanying_document ml-2" content="{{ $payment->accompanying_document }}">Chứng từ</button>
                      @permission('admin.payment.edit')
                        <a href="{{ route('admin.payment.edit', ['id' => $payment->id]) }}" role="button" class="btn btn-outline-primary btn-sm" title="Cập nhật"><i class="fas fa-solid fa-pen"></i></a>
                      @endpermission
                      @permission('admin.payment.destroy')
                        <a href="{{ route('admin.payment.destroy', ['id' => $payment->id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa Phiếu Chi này không ?')"><i class="fas fa-trash-alt"></i></a>
                      @endpermission
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="d-flex justify-content-center">
              {!! $payments->appends(session()->getOldInput())->links() !!}
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
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
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/admin/payments.js') }}"></script>
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
</script>
@endsection
