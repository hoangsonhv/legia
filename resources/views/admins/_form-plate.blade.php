<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @include('admins.message')
            </div>
            <div class="col-12">
                <div class="card">
                    {!! Form::open(['route' => ['admin.warehouse-plate.store', $model], 'method' => 'post']) !!}
                    <div class="card-body">
                        <div class="form-group">
                            <label for="code">Mã hàng hoá<b style="color: red;"> (*)</b></label>
                            {!! Form::text('code', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        </div>
                        <div class="form-group">
                            <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                            {!! Form::text('vat_lieu', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        </div>
                        <div class="form-group">
                            <label for="do_day">Độ dày</label>
                            {!! Form::text('do_day', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="hinh_dang">Hình dạng</label>
                            {!! Form::text('hinh_dang', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="dia_w_w1">Dia W W1</label>
                            {!! Form::text('dia_w_w1', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="l_l1">L L1</label>
                            {!! Form::text('l_l1', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="w2">W2</label>
                            {!! Form::text('w2', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="l2">L2</label>
                            {!! Form::text('l2', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="sl_tam">SL - Tấm</label>
                            {!! Form::text('sl_tam', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="sl_m2">SL - m2</label>
                            {!! Form::text('sl_m2', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group d-none">
                            <label for="lot_no">Lot No</label>
                            {!! Form::text('lot_no', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="ghi_chu">Ghi Chú</label>
                            {!! Form::text('ghi_chu', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <div class="input-group date" id="warehouse_date" data-target-input="nearest">
                                {!! Form::text('date', null, [
                                    'class' => 'form-control datetimepicker-input',
                                    'data-target' => '#warehouse_date',
                                ]) !!}
                                <div class="input-group-append" data-target="#warehouse_date"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-none">
                            <label for="ton_sl_tam">Tồn SL - Tấm</label>
                            {!! Form::text('ton_sl_tam', 0, ['class' => 'form-control ']) !!}
                        </div>
                        <div class="form-group d-none">
                            <label for="ton_sl_m2">Tồn SL - m2</label>
                            {!! Form::text('ton_sl_m2', 0, ['class' => 'form-control ']) !!}
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer text-right d-none">
                        <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                    </div>
                    {!! Form::close() !!}
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</section>
