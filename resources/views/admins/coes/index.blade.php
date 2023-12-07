@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
  <style type="text/css">
    @-webkit-keyframes my {
      0% { background-color: red; }
      50% { background-color: #fff;  }
      100% { background-color: red;  }
    }
    @-moz-keyframes my {
      0% { background-color-coloror: red;  }
      50% { background-color: #fff;  }
      100% { background-color: red;  }
    }
    @-o-keyframes my {
      0% { background-color: red; }
      50% { background-color: #fff; }
      100% { background-color: red;  }
    }
    @keyframes my {
      0% { background-color: red;  }
      50% { background-color: #fff;  }
      100% { background-color: red;  }
    }
    .nhap_nhay {
      background:#3d3d3d;
      font-size:24px;
      font-weight:bold;
      -webkit-animation: my 700ms infinite;
      -moz-animation: my 700ms infinite;
      -o-animation: my 700ms infinite;
      animation: my 700ms infinite;
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
        <div class="card">
          <div class="card-header">
            <div class="card-tools">
              @if(!empty($countPending))
              @include('admins.includes.noti-pending', ['label' => sprintf('Có %s CO chưa xử lý', $countPending), 'url' => route('admin.co.index', ['used' => 0])])
              @endif
              {!! Form::open(array('route' => 'admin.co.index', 'method' => 'get')) !!}
              <div class="input-group">
                {{-- <input type="hidden" name="used" value="{{ old('used') }}"> --}}
                {!! Form::select('core_customer_id', $coreCustomers, null, array('class' => 'form-control mr-1 float-right select2')) !!}
                {!! Form::select('status', $statuses, null, array('class' => 'form-control mr-1 float-right d-none','id' => 'selectedStatus')) !!}
                <input type="text" name="from_date" class="form-control float-right" placeholder="Từ ngày" value="{{old('from_date')}}">
                <input type="text" name="to_date" class="form-control float-right mr-1" placeholder="Đến ngày" value="{{old('to_date')}}">
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
                  <th></th>
                  <th>Mã CO</th>
                  <th>Mã KH</th>
                  <th>Mô tả</th>
                  <th>Tổng tiền</th>
                  <th><span class="bg-success" style="padding: 0 5px; border-radius: 5px">Đã thu</span></th>
                  <th><span class="bg-danger" style="padding: 0 5px; border-radius: 5px">Đã chi</span></th>
                  <th>Công nợ</th>
                  <th>Trạng thái</th>
                  <th>Tình trạng CO</th>
                  <th>Ngày tạo</th>
                  <th>Người thực hiện</th>
                  <th>&nbsp</th>
                </tr>
              </thead>
              <tbody>
                @foreach($coes as $co)
                  @php
                    $sumReceipt = 0;
                    if($co->receipt->count()) {
                      foreach($co->receipt as $val) {
                        if($val->status == \App\Enums\ProcessStatus::Approved) {
                          $sumReceipt += $val->money_total;
                        }
                      }
                    }
                  @endphp
                  @php
                    $sumPayment = 0;
                    if($co->payment->count()) {
                      foreach($co->payment as $val) {
                        if($val->status == \App\Enums\ProcessStatus::Approved) {
                          $sumPayment += $val->money_total;
                        }
                      }
                    }
                    if($co->delivery()->count()) {
                      $sumPayment += $co->delivery->first()->shipping_fee;
                    }
                  @endphp
                  <tr>
                    <td>
                      @if($sumReceipt < $sumPayment)
                        <span class="badge  p-2 nhap_nhay" style="border-radius: 50%"> </span>
                      @endif
                    </td>
                    <td>{{ $co->code }}</td>
                    <td>{{ $co->core_customer ? $co->core_customer->code : '' }}</td>
                    <td>{{ Str::limit($co->description, 30) }}</td>
                    <td>
                      @php
                        $sumPrice = $co->tong_gia + $co->vat;
                      @endphp
                      <span class="badge bg-warning">
                        {{ number_format($sumPrice) }}
                      </span>
                    </td>
                    <td>
                      <span class="badge"> {{$sumReceipt ? '+ ' . number_format($sumReceipt) : ''}}</span>
                    </td>
                    <td>
                      <span class="badge">{{$sumPayment ? '- ' . number_format($sumPayment) : ''}}</span>
                    </td>
                    <td>
                      @php
                        $CN = $sumPrice - $sumReceipt;
                      @endphp
                      @if ($CN <= 0)
                        <span class="badge bg-success">
                          Đã thu đủ
                        </span>
                      @else
                        <span class="badge bg-danger">
                          Thiếu {{ number_format($CN) }}
                        </span>
                      @endif
                    </td>
                    <td>
                      @if($co->status == \App\Enums\ProcessStatus::Approved)
                        <span class="badge">
                          {{ \App\Enums\ProcessStatus::all()[$co->status] }}
                        </span>
                      @elseif($co->status == \App\Enums\ProcessStatus::Unapproved)
                        <span class="badge">
                          {{ \App\Enums\ProcessStatus::all()[$co->status] }}
                        </span>
                      @else
                        <span class="badge">
                          {{ \App\Enums\ProcessStatus::all()[$co->status] }}
                        </span>
                      @endif
                    </td>
                    <td>
                      @if($co->confirm_done)
                        <span class="badge bg-success">CO đã xong</span>
                      @else
                        <span class="badge bg-danger">CO chưa xong</span>
                      @endif
                    </td>
                    <td>{{ $co->created_at }}</td>
                    <td>
                      @if($co->admin)
                        {{ $co->admin->name }}
                      @endif
                    </td>
                    <td>
                      @permission('admin.co.edit')
                        <a href="{{ route('admin.co.edit', ['id' => $co->id]) }}" role="button" class="btn btn-outline-primary btn-sm" title="Cập nhật"><i class="fas fa-solid fa-pen"></i></a>
                      @endpermission
                      @permission('admin.co.destroy')
                        <a href="{{ route('admin.co.destroy', ['id' => $co->id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa CO này không ?')"><i class="fas fa-trash-alt"></i></a>
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
<script type="text/javascript" src="{{ asset('vendor/moment/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
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
  function updateSelectedStatus(status) {
    $('#selectedStatus').val(status);
    $('form').submit();
  }
</script>
@endsection
