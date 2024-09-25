@extends('layouts.admin')
@section('css')
<link rel="stylesheet" href="{{ asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
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
        <div class="card">
          <div class="card-header">
            <div class="card-tools">
              {!! Form::open(array('route' => 'admin.history-active.index', 'method' => 'get')) !!}
              <div class="input-group">
                {!! Form::select('formable_type', $listFormType, null, array('class' => 'form-control mr-1 float-right', 'id' => 'formable_type')) !!}
                {!! Form::select('performed_by', $listAdmin, null, array('class' => 'form-control mr-1 float-right', 'id' => 'performed_by')) !!}
                <input type="text" name="key_word" class="form-control float-right" placeholder="Từ khoá" value="{{old('key_word')}}">
                <input type="text" name="from_date" class="form-control float-right" placeholder="Từ ngày" value="{{old('from_date')}}">
                <input type="text" name="to_date" class="form-control float-right mr-1" placeholder="Đến ngày" value="{{old('to_date')}}">
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
            {{-- @php
            $selected = 0;
            if(Session::has('_old_input.status')) {
              $selected = Session::get('_old_input.status');
            }
            @endphp
            @foreach ($statuses as $key => $item)
            <li class="nav-item">
              <a class="nav-link {{ $selected == $key ? 'active' :'' }}" onclick=updateSelectedStatus({{$key}}) data-toggle="pill" href="#" role="tab" aria-controls="pills-home" aria-selected="true"> {{ $item }} <span class="badge badge-danger">{{ $count[$key] }}</span></a>
            </li>
            @endforeach --}}
          </ul>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>Mã Phiếu</th>
                  <th>Tên Phiếu</th>
                  <th>Người thực hiện</th>
                  <th>Hành động</th>
                  <th>Trạng thái</th>
                  <th>Thực hiện lúc</th>
                </tr>
              </thead>
              <tbody>
                @foreach($activities as $activity)
                  <tr>
                    <td>{{ $activity->formable->code }}</td>
                    <td>{{ \App\Enums\ChangeHistoryValue::formTypes()[$activity->formable_type]}}</td>
                    <td>{{ $activity->admin->name }}</td>
                    <td>{{ $activity->action }}</td>
                    <td>
                      @if($activity->new_status == \App\Enums\ProcessStatus::Approved)
                        <span class="badge bg-success">
                          {{ \App\Enums\ProcessStatus::all()[$activity->new_status] }}
                        </span>
                      @elseif($activity->new_status == \App\Enums\ProcessStatus::Unapproved)
                        <span class="badge bg-danger">
                          {{ \App\Enums\ProcessStatus::all()[$activity->new_status] }}
                        </span>
                      @else
                        <span class="badge bg-warning">
                          {{ $activity->new_status ? \App\Enums\ProcessStatus::all()[$activity->new_status] : '' }}
                        </span>
                      @endif
                    </td>
                    <td>{{ $activity->performed_at }}</td>
                    
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="d-flex justify-content-center">
              {!! $activities->appends(session()->getOldInput())->links() !!}
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
<script type="text/javascript" src="{{ asset('js/admin/receipts.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/moment/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
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
<script type="text/javascript">
  $( document ).ready(function() {
    $('[name="from_date"]').daterangepicker({
      autoUpdateInput: false,
      singleDatePicker: true,
      maxDate: moment(),
      locale: {
        format: 'YYYY-MM-DD'
      }
    });
    $('[name="from_date"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });
    $('[name="from_date"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });

    $('[name="to_date"]').daterangepicker({
      autoUpdateInput: false,
      singleDatePicker: true,
      maxDate: moment(),
      locale: {
        format: 'YYYY-MM-DD'
      }
    });
    $('[name="to_date"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });
    $('[name="to_date"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });
  });

</script>
@endsection
