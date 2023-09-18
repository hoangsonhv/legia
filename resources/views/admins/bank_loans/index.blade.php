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
                                {!! Form::open(array('route' => 'admin.bank-loans.index', 'method' => 'get')) !!}
                                <div class="input-group">
                                    <input type="text" name="key_word" class="form-control float-right"
                                           placeholder="Từ khoá" value="{{old('key_word')}}">
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
                                    <th>Ngân hàng</th>
                                    <th>Mã khế ước</th>
                                    <th>Nội dung vay</th>
                                    <th>Nội dung chi tiết</th>
                                    <th>Ngày vay</th>
                                    <th>Ngày đáo hạn</th>
                                    <th>Ngày trả hàng tháng</th>
                                    <th>Số tiền vay</th>
                                    <th>Lãi (%)</th>
                                    <th>Số dư nợ</th>
                                    <th>Người thực hiện</th>
                                    <th>&nbsp</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($datas as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>
                                            @if($data->bank)
                                                <a target="_blank" href="{{route('admin.bank.edit', ['id' => $data->bank_id])}}">
                                                    {{$data->bank->name_bank}}
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ $data->code }}</td>
                                        <td>{{ $data->lead }}</td>
                                        <td>{{ $data->content }}</td>
                                        <td>{{ $data->date }}</td>
                                        <td>{{ $data->date_due }}</td>
                                        <td>{{ $data->date_pay }}</td>
                                        <td>{{ number_format($data->amount_money, 0) }}</td>
                                        <td>{{ $data->profit_amount }}</td>
                                        <td>{{ number_format($data->outstanding_balance, 0) }}</td>
                                        <td>{{ $data->admin ? $data->admin->name : '' }}</td>
                                        <td>
                                            @permission('admin.bank-loans.edit')
                                            <a href="{{ route('admin.bank-loans.edit', ['id' => $data->id]) }}"
                                               role="button"
                                               class="btn btn-outline-primary btn-sm" title="Cập nhật"><i
                                                        class="fas fa-solid fa-pen"></i></a>
                                            @endpermission
                                            @permission('admin.bank-loans.destroy')
                                            <a href="{{ route('admin.bank-loans.destroy', ['id' => $data->id]) }}"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa vay nợ này không ?')"><i
                                                        class="fas fa-trash-alt"></i></a>
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
                </div>
            </div>
        </div>
    </section>
@endsection
