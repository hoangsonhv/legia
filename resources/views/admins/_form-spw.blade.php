<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @include('admins.message')
            </div>
            <div class="col-12">
                <div class="card">
                    {!! Form::open(['route' => ['admin.warehouse-spw.store', $model], 'method' => 'post']) !!}
                    <div class="card-body">
                        <div class="form-group">
                            <label for="code">Mã hàng hoá<b style="color: red;"> (*)</b></label>
                            {!! Form::text('code', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        </div>
                        @switch ($model)
                            @case ('filler')
                                <div class="form-group">
                                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                                    {!! Form::text('vat_lieu', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="size">Size</label>
                                    {!! Form::number('size', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="trong_luong_cuon">Trọng lượng - Kg/Cuộn</label>
                                    {!! Form::number('trong_luong_cuon', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="m_cuon">m/cuộn</label>
                                    {!! Form::number('m_cuon', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="sl_cuon">SL - Cuộn</label>
                                    {!! Form::number('sl_cuon', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="sl_kg">SL - Kg</label>
                                    {!! Form::number('sl_kg', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('glandpackinglatty')
                                <div class="form-group">
                                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                                    {!! Form::text('vat_lieu', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="size">Size</label>
                                    {!! Form::text('size', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="sl_cuon">SL - Cuộn</label>
                                    {!! Form::number('sl_cuon', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('hoop')
                                <div class="form-group">
                                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                                    {!! Form::text('vat_lieu', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="size">Size</label>
                                    {!! Form::number('size', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="trong_luong_cuon">Trọng lượng - Kg/Cuộn</label>
                                    {!! Form::number('trong_luong_cuon', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="m_cuon">m/cuộn</label>
                                    {!! Form::number('m_cuon', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="sl_cuon">SL - Cuộn</label>
                                    {!! Form::number('sl_cuon', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="sl_kg">SL - Kg</label>
                                    {!! Form::number('sl_kg', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('oring')
                                <div class="form-group">
                                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                                    {!! Form::text('vat_lieu', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="size">Size</label>
                                    {!! Form::text('size', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="sl_cai">SL - Cái</label>
                                    {!! Form::number('sl_cai', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('ptfeenvelope')
                                <div class="form-group">
                                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                                    {!! Form::text('vat_lieu', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="do_day">Độ dày</label>
                                    {!! Form::number('do_day', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="std">STD</label>
                                    {!! Form::text('std', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="size">Size</label>
                                    {!! Form::text('size', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="od">OD</label>
                                    {!! Form::number('od', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="attr_id">ID</label>
                                    {!! Form::number('attr_id', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="sl_cai">SL - Cái</label>
                                    {!! Form::number('sl_cai', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('ptfetape')
                                <div class="form-group">
                                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                                    {!! Form::text('vat_lieu', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="size">Size</label>
                                    {!! Form::text('size', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="m_cuon">m/cuộn</label>
                                    {!! Form::number('m_cuon', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="sl_cuon">SL - Cuộn</label>
                                    {!! Form::number('sl_cuon', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="sl_m">SL - m</label>
                                    {!! Form::number('sl_m', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('rtj')
                                <div class="form-group">
                                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                                    {!! Form::text('vat_lieu', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="size">Size</label>
                                    {!! Form::text('size', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="sl_cai">SL - Cái</label>
                                    {!! Form::number('sl_cai', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('thanhphamswg')
                                <div class="form-group">
                                    <label for="inner">Inner</label>
                                    {!! Form::text('inner', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="hoop">Hoop</label>
                                    {!! Form::text('hoop', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="filler">Filler</label>
                                    {!! Form::text('filler', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="outer">Outer</label>
                                    {!! Form::text('outer', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="thick">Thick</label>
                                    {!! Form::text('thick', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="tieu_chuan">Tiêu chuẩn</label>
                                    {!! Form::text('tieu_chuan', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="kich_co">Kích cỡ</label>
                                    {!! Form::text('kich_co', null, ['class' => 'form-control']) !!}
                                </div>
                            @break

                            @case ('vanhtinhinnerswg')
                                <div class="form-group">
                                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                                    {!! Form::text('vat_lieu', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="do_day">Độ dày</label>
                                    {!! Form::number('do_day', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="d1">D1</label>
                                    {!! Form::number('d1', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="d2">D2</label>
                                    {!! Form::number('d2', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="sl_cai">SL - Cái</label>
                                    {!! Form::number('sl_cai', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('vanhtinhouterswg')
                                <div class="form-group">
                                    <label for="vat_lieu">Vật liệu<b style="color: red;"> (*)</b></label>
                                    {!! Form::text('vat_lieu', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="do_day">Độ dày</label>
                                    {!! Form::number('do_day', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="d3">D3</label>
                                    {!! Form::number('d3', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="d4">D4</label>
                                    {!! Form::number('d4', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="sl_cai">SL - Cái</label>
                                    {!! Form::number('sl_cai', null, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break
                        @endswitch
                        <div class="form-group">
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
                        @switch ($model)
                            @case ('filler')
                                <div class="form-group d-none">
                                    <label for="ton_sl_cuon">Tồn SL - Cuộn</label>
                                    {!! Form::number('ton_sl_cuon', 0, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group d-none">
                                    <label for="ton_sl_kg">Tồn SL - Kg</label>
                                    {!! Form::number('ton_sl_kg', 0, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('glandpackinglatty')
                                <div class="form-group d-none">
                                    <label for="ton_sl_cuon">Tồn SL - Cuộn</label>
                                    {!! Form::number('ton_sl_cuon', 0, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('hoop')
                                <div class="form-group d-none">
                                    <label for="ton_sl_cuon">Tồn SL - Cuộn</label>
                                    {!! Form::number('ton_sl_cuon', 0, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group d-none">
                                    <label for="ton_sl_kg">Tồn SL - Kg</label>
                                    {!! Form::number('ton_sl_kg', 0, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('oring')
                                <div class="form-group d-none">
                                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                                    {!! Form::number('ton_sl_cai', 0, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('ptfeenvelope')
                                <div class="form-group d-none">
                                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                                    {!! Form::number('ton_sl_cai', 0, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('ptfetape')
                                <div class="form-group d-none">
                                    <label for="ton_sl_cuon">Tồn SL - Cuộn</label>
                                    {!! Form::number('ton_sl_cuon', 0, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                                <div class="form-group d-none">
                                    <label for="ton_sl_m">Tồn SL - m</label>
                                    {!! Form::number('ton_sl_m', 0, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('rtj')
                                <div class="form-group d-none">
                                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                                    {!! Form::number('ton_sl_cai', 0, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('thanhphamswg')
                                <div class="form-group d-none">
                                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                                    {!! Form::number('ton_sl_cai', 0, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('vanhtinhinnerswg')
                                <div class="form-group d-none">
                                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                                    {!! Form::number('ton_sl_cai', 0, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break

                            @case ('vanhtinhouterswg')
                                <div class="form-group d-none">
                                    <label for="ton_sl_cai">Tồn SL - Cái</label>
                                    {!! Form::number('ton_sl_cai', 0, ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            @break
                        @endswitch
                    </div>
                    <!-- /.card-body -->
                    {{-- <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                        <a href="{{ route('admin.warehouse-spw.index', ['model' => $model]) }}"
                            class="btn btn-default">Quay lại</a>
                    </div> --}}
                    {!! Form::close() !!}
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</section>
