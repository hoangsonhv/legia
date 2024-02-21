@extends('layouts.admin')
@section('content')
    @include('admins.breadcrumb')
    <section class="content">
        <div class="container-fluid">
            @php
                $isManufactureProduct = 0;
                $enableQCCheck = 0;

                if ($model->manufacture_type == \App\Models\WarehouseGroup::TYPE_MANUFACTURE) {
                    $isManufactureProduct = 1;
                }

                if ($model->is_completed == 2 || $model->qc_check == \App\Enums\QCCheckStatus::FIX) {
                    $enableQCCheck = 1;
                }
            @endphp
            @if ($model->is_completed == 2)
                @permission('admin.manufacture.confirm-quantity')
                    @php
                        $hasErrorQuantity = 0;

                        foreach ($details as $v) {
                            if ($v['error_quantity'] > 0) {
                                $hasErrorQuantity = 1;
                                break;
                            }
                        }
                    @endphp

                    @include('admins.includes.approval', [
                        'id' => $model->id,
                        'type' => 'manufacture',
                        'status' => $model->qc_check,
                        'hasErrorQuantity' => $hasErrorQuantity,
                        'isManufactureProduct' => $isManufactureProduct,
                    ])
                @endpermission
            @endif
            <div class="row">
                <div class="col-12">
                    @include('admins.message')
                </div>
                <div class="col-12">
                    <div class="float-right pb-2">
                        <a href={{ route('admin.pdf.manufacture', ['id' => $model->id]) }}>
                            <button class="btn btn-success">
                                <i class="nav-icon fas fa-file-export" aria-hidden="true"></i>
                                Lệnh sản xuất
                            </button>
                        </a>
                        <a href={{ route('admin.pdf.manufacture-check', ['id' => $model->id]) }}>
                            <button class="btn btn-success">
                                <i class="nav-icon fas fa-file-export" aria-hidden="true"></i>
                                Phiếu kiểm tra
                            </button>
                        </a>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        {!! Form::model($model, array('route' => ['admin.manufacture.update', $model->id], 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
                        {!! Form::hidden('id', $model->id) !!}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="code">Mã CO<b style="color: red;"> (*)</b></label>
                                {!! Form::select('co_id', $arrCo, null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                            </div>
                            <div class="form-group">
                                <label for="code">Mô tả</label>
                                {!! Form::textarea('note', null, array('class' => 'form-control', 'rows' => 2, 'placehodel')) !!}
                            </div>
                            <div class="form-group">
                                <label for="code">Loại:
                                    @if($model->material_type == \App\Models\Manufacture::MATERIAL_TYPE_METAL)
                                        <span class="badge bg-gray">Kim loại</span>
                                    @else
                                        <span class="badge bg-info">Phi kim loại</span>
                                    @endif
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="code">Thương mại/Sản xuất:
                                    @if($model->manufacture_type == \App\Models\WarehouseGroup::TYPE_MANUFACTURE)
                                        <span class="badge bg-info">Sản xuất</span>
                                    @else
                                        <span class="badge bg-gray">Thương mại</span>
                                    @endif
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="code">Trạng thái:
                                    @if($model->is_completed == \App\Models\Manufacture::IS_COMPLETED)
                                        <span class="badge bg-success">Đã xong</span>
                                    @elseif($model->is_completed == \App\Models\Manufacture::PROCESSING)
                                        <span class="badge bg-primary">Tiến hành sản xuất</span>
                                    @else
                                        <span class="badge bg-warning">Đang chờ</span>
                                    @endif
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="code">QC kiểm tra:
                                    @php
                                        $qcCheckColor = 'bg-warning';

                                        if($model->qc_check == \App\Enums\QCCheckStatus::DONE) {
                                            $qcCheckColor = 'bg-success';
                                        }
                                        elseif($model->qc_check == \App\Enums\QCCheckStatus::FIX)
                                        {
                                            $qcCheckColor = 'bg-danger';
                                        }
                                        elseif($model->qc_check == \App\Enums\QCCheckStatus::REMAKE)
                                        {
                                            $qcCheckColor = 'bg-danger';
                                        }
                                    @endphp
                                    <span class="badge {{ $qcCheckColor }}">{{ \App\Enums\QCCheckStatus::all()[$model->qc_check] }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('admins.manufacture.includes.list-product',
                                [
                                    'co' => $co,
                                    'is_wait' => $model->is_completed == \App\Models\Manufacture::WAITING,
                                    'is_processing' => $model->is_completed == \App\Models\Manufacture::PROCESSING,
                                    'isManufactureProduct' => $isManufactureProduct,
                                    'enableQCCheck' => $enableQCCheck,
                                ])
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
{{--                            @if($model->is_completed == \App\Models\Manufacture::PROCESSING)--}}
                                <button type="submit" class="btn btn-primary">Lưu thông tin</button>
{{--                            @endif--}}
                            <a href="{{ route('admin.manufacture.index') }}" class="btn btn-default">Quay lại</a>
                        </div>
                    </div>
                {!! Form::close() !!}
                <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('[name="material_type[]"]').change(function () {
                let showData = $(this).parent().find('.show-data-switch');
                if (this.checked) {
                    showData.text('Kim loại')
                } else {
                    showData.text('Phi kim loại')
                }
            });
        });
    </script>
@endsection