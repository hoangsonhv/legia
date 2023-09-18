@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <style type="text/css">
        .block-file {
            margin-bottom: 10px;
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
                    <div class="card form-root">
                        {!! Form::open(array('route' => 'admin.warehouse-export-sell.store', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="core_customer_id">CO<b style="color: red;"> (*)</b></label>
                                {!! Form::select('co_id', $co, null,
                                array('class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly')) !!}
                            </div>
                            <div class="form-group">
                                <label for="core_customer_id">Mã khách hàng<b style="color: red;"> (*)</b></label>
                                {!! Form::select('core_customer_id', $coreCustomers, $coModel->core_customer_id,
                                array('class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly')) !!}
                            </div>
                            <div class="form-group">
                                <label for="buyer_name">Tên khách hàng<b style="color: red;"> (*)</b></label>
                                {!! Form::text('buyer_name', $coModel->core_customer ? $coModel->core_customer->name : null,
                                array('class' => 'form-control', 'required' => 'required')) !!}
                            </div>
                            <div class="form-group">
                                <label for="buyer_address">Địa chỉ<b style="color: red;"> (*)</b></label>
                                {!! Form::text('buyer_address', $coModel->core_customer ? $coModel->core_customer->address : null,
                                array('class' => 'form-control', 'required' => 'required')) !!}
                            </div>
                            <div class="form-group">
                                <label for="buyer_phone">Điện thoại<b style="color: red;"> (*)</b></label>
                                {!! Form::text('buyer_phone',  $coModel->core_customer ? $coModel->core_customer->phone : null,
                                array('class' => 'form-control', 'required' => 'required')) !!}
                            </div>
                            <div class="form-group">
                                <label for="buyer_tax_code">Mã số thuế<b style="color: red;"> (*)</b></label>
                                {!! Form::text('buyer_tax_code', $coModel->core_customer ? $coModel->core_customer->tax_code : null,
                                array('class' => 'form-control', 'required' => 'required')) !!}
                            </div>
                            <div class="form-group">
                                <label for="buyer_tax_code">Ghi chú</label>
                                {!! Form::textarea('note', null, array('class' => 'form-control', 'rows' => 2)) !!}
                            </div>
                            <div class="form-group">
                                <label>Chứng từ đi kèm</label>
                                <div class="input-group block-file">
                                    <div class="custom-file">
                                        <input type="file" name="document[]" class="custom-file-input" multiple />
                                        <label class="custom-file-label">Chọn file</label>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success add-upload">
                                    Thêm file upload
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="title text-primary">Nội dung</h3>
                            @include('admins.warehouse_export_sell.includes.list-products')
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                            <a href="{{ route('admin.warehouse-export-sell.index') }}" class="btn btn-default">Quay lại</a>
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
    <script type="text/javascript" src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/admin/warehouse_export_sell.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/convertVNese/n2vi.min.js') }}"></script>
    <script type="text/javascript">
        const coreCustomerOrigin = <?php echo json_encode($coreCustomerOrigin); ?>;
        $( document ).ready(function() {
            // Init data
            bsCustomFileInput.init();

            $('[name=core_customer_id]').change(function(e) {
                let cusId = $(this).val();
                let customer = coreCustomerOrigin.find(c => c.id == cusId);
                if(customer) {
                    $('[name=buyer_name]').val(customer.name);
                    $('[name=buyer_phone]').val(customer.phone);
                    $('[name=buyer_address]').val(customer.address);
                    $('[name=buyer_tax_code]').val(customer.tax_code);
                }
            });
        });
    </script>
@endsection
