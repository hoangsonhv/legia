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
                        {!! Form::open(array('route' => 'admin.manufacture.store', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="code">Mã CO<b style="color: red;"> (*)</b></label>
                                {!! Form::select('co_id', $arrCo, null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                            </div>
                            <div class="form-group">
                                <label for="code">Mô tả</label>
                                {!! Form::textarea('note', null, array('class' => 'form-control', 'rows' => 2, 'placehodel')) !!}
                            </div>
                        </div>
                        <div class="card-body">
                            @include('admins.manufacture.includes.list-product', ['co' => $co])
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
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
