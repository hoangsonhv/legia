@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style type="text/css">
        .block-file {
            margin-bottom: 10px;
        }

        hr.hor {
            color: red;
            border: 3px solid #007bff;
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
                    {{-- @if($existsCat)
                        <h5 class="mb-3 text-danger">Đã tồn tại Phiếu Yêu Cầu Định Kỳ trong tháng. Vui lòng kiểm tra
                            trước khi xét duyệt.</h5>
                    @endif --}}
                </div>
                <div class="col-12">
                    <div class="card form-root">
                        {!! Form::model($requestModel, array('route' => ['admin.request.update', $requestModel->id], 'method' => 'patch', 'id' => 'main_form', 'enctype' => 'multipart/form-data')) !!}
                        {!! Form::hidden('id', null) !!}
                        <div class="card-body">
                            @if($co)
                                <div class="form-group">
                                    <label for="co_id">CO</label>
                                    {!! Form::select('co_id', $co, null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="code">Mã Phiếu Yêu Cầu</label>
                                {!! Form::text('code', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                            </div>
                            <div class="form-group">
                                <label for="category">Danh mục<b style="color: red;"> (*)</b></label>
                                {!! Form::select('category', $categories, null, array('class' => 'form-control', 'required' => 'required', 'disabled'=> true)) !!}
                            </div>
                            <div class="form-group">
                                <label for="note">Ghi chú</label>
                                {!! Form::text('note', null, array('class' => 'form-control')) !!}
                            </div>
                            <div class="form-group">
                                <label>Chứng từ đi kèm</label>
                                <div class="input-group block-file">
                                    <div class="custom-file">
                                        <input type="file" name="accompanying_document[]" class="custom-file-input"
                                               multiple/>
                                        <label class="custom-file-label">Chọn file</label>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success add-upload">
                                    Thêm file upload
                                </button>
                                @if(json_decode($requestModel->accompanying_document, true))
                                    <button type="button" class="btn btn-success" id="accompanying_document_display"
                                            data-toggle="modal" data-target="#accompanying_document_modal"
                                            content="{{ $requestModel->accompanying_document }}">Hiển thị chứng từ đã
                                        tồn tại
                                    </button>
                                @endif
                            </div>
                        </div>
                        @if($co)
                            <div class="card-body offer-price">
                                {{-- @include('admins.coes.includes.list-products', ['warehouses' => $warehouses, 'collect' => true, 'notAction' => true]) --}}
                                @include('admins.coes.includes.list-products', ['warehouses' => $warehouses, 'collect' => true])
                            </div>
                            <div class="card-body check-warehouse">
                                @include('admins.coes.includes.list-warehouses',['warehouses' => $listWarehouse])
                            </div>
                        @endif
                        {!! Form::close() !!}
                        <div class="card-body">
                            <h3 class="title text-primary">Nội dung</h3>
                            @if(!$co)
                            @include('admins.requests.includes.list-service', ['materials' => $materials])
                            @endif
                        </div>
                        @if($co)
                        @include('admins.requests.includes.list-materials', ['co' => $co, 'coStep' => $coStep , 'materials' => $materials])
                        @endif
                        @permission('admin.request.update-survey-price')
                        @php
                            $statusAcceptRequest = [
                            \App\Enums\ProcessStatus::Approved,
                            \App\Enums\ProcessStatus::PendingSurveyPrice,
                            ];
                            $statusNotEdit = [
                            \App\Enums\ProcessStatus::Approved,
                            \App\Enums\ProcessStatus::Unapproved,
                            ];
                        @endphp
                        {{-- @if(($co || in_array($requestModel->category,array_keys(array_values(\App\Helpers\DataHelper::getCategories([\App\Helpers\DataHelper::VAN_PHONG_PHAM]))[0]))) && in_array($requestModel->status, $statusAcceptRequest))
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="title text-primary mb-4">Khảo sát giá
                                        @if (!in_array($requestModel->status, $statusNotEdit))
                                            <a target="_blank"
                                            href="{{route('admin.price-survey.create', ['co_id' => $requestModel->co_id, 'request_id' => $requestModel->id])}}">
                                                <button class="btn btn-primary">
                                                    Tạo khảo sát giá
                                                </button>
                                            </a>
                                        @endif
                                    </h3>
                                    {!! Form::model($requestModel, array('route' => ['admin.request.update-survey-price', $requestModel->id], 'method' => 'patch', 'enctype' => 'multipart/form-data')) !!}
                                    {!! Form::hidden('id', null) !!}
                                    <div class="list-survey-price">
                                        @if($requestModel->surveyPrices->count())
                                            @foreach($requestModel->surveyPrices as $indexSP => $surveyPrice)
                                                <div class="row">
                                                    <div class="col-7">
                                                        <div class="item-survey-price" data-index="{{ $indexSP }}">
                                                            {!! Form::hidden('survey_price['.$indexSP.'][id]', $surveyPrice->id, array('class' => 'survey_price_id')) !!}
                                                            <div class="form-group">
                                                                <div class="icheck-success">
                                                                    @php
                                                                        $formOpts = array('id' => 'is_accept'.$indexSP);
                                                                        if (in_array($requestModel->status, $statusNotEdit)) {
                                                                        $formOpts['disabled'] = 'disabled';
                                                                        }
                                                                    @endphp
                                                                    {!! Form::checkbox('survey_price['.$indexSP.'][is_accept]', true, $surveyPrice->is_accept, $formOpts) !!}
                                                                    <label for="{{ 'is_accept'.$indexSP }}">Khảo Sát
                                                                        Giá {{ $indexSP + 1 }}</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Chứng từ đi kèm</label>
                                                                @php
                                                                    $accompanyingDocument = json_decode($surveyPrice->accompanying_document, true);
                                                                @endphp
                                                                <div class="d-block">
                                                                    @if($accompanyingDocument)
                                                                        <button type="button" class="btn btn-success"
                                                                                data-toggle="modal"
                                                                                data-target="#accompanying_document_survey_price_modal{{ $indexSP }}">
                                                                            Hiển thị chứng từ đã
                                                                            tồn tại
                                                                        </button>
                                                                    @else
                                                                        Không tồn tại chứng từ
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>
                                                                    Khảo sát giá
                                                                </label>
                                                                {!! Form::select('survey_price['.$indexSP.'][core_price_survey_id]', $corePriceSurvey , $surveyPrice->core_price_survey_id, array('class' => 'form-control')) !!}
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="survey_price[{{ $indexSP }}][note]">Ghi
                                                                    chú</label>
                                                                {!! Form::text('survey_price['.$indexSP.'][note]', $surveyPrice->note, array('class' => 'form-control')) !!}
                                                            </div>
                                                            @if (!in_array($requestModel->status, $statusNotEdit))
                                                                <button type="button" class="btn btn-danger"
                                                                        onclick="removeSurveyPrice(this)">Xoá Khảo Sát Giá
                                                                </button>
                                                            @endif
                                                            @if($accompanyingDocument)
                                                                <div class="modal fade"
                                                                    id="accompanying_document_survey_price_modal{{ $indexSP }}">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-success">
                                                                                <h4 class="modal-title">Chứng từ khảo sát
                                                                                    giá</h4>
                                                                                <button type="button" class="close"
                                                                                        data-dismiss="modal"
                                                                                        aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                @foreach($accompanyingDocument as $index => $file)
                                                                                    <div class="data-file">
                                                                                        {!! \App\Helpers\AdminHelper::checkFile($file) !!}
                                                                                        @if(!in_array($requestModel->status, $statusNotEdit))
                                                                                            <div class="mt-2">
                                                                                                <button type="button"
                                                                                                        class="btn btn-danger form-control"
                                                                                                        onclick="removeFile(this)"
                                                                                                        data-path="{{ $file['path'] }}">
                                                                                                    Xoá file
                                                                                                </button>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                        class="btn btn-outline-dark"
                                                                                        data-dismiss="modal">Đóng
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                    </div>
                                                                    <!-- /.modal-dialog -->
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-5 border-left pl-2">
                                                        @if($surveyPrice->corePriceSurvey)
                                                            @include('admins.requests.includes.detail-price-survey', ['surveyPrice' => $surveyPrice->corePriceSurvey])
                                                        @endif
                                                    </div>
                                                    <hr class="w-100 hor">
                                                </div>
                                            @endforeach
                                        @else
                                            @php
                                                $indexSP = $requestModel->surveyPrices->count();
                                            @endphp
                                            <div class="item-survey-price" data-index="0">
                                                <div class="form-group">
                                                    <div class="">
                                                        <label for="{{ 'is_accept'.$indexSP }}">Khảo Sát
                                                            Giá {{ $indexSP + 1 }}</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="survey_price[{{ $indexSP }}][note]">Khảo sát giá</label>
                                                    {!! Form::select('survey_price['.$indexSP.'][core_price_survey_id]', $corePriceSurvey, null, array('class' => 'form-control')) !!}
                                                </div>
                                                <div class="form-group">
                                                    <label>Chứng từ đi kèm</label>
                                                    <div class="input-group block-file">
                                                        <div class="custom-file">
                                                            <input type="file"
                                                                name="survey_price[{{ $indexSP }}][accompanying_document][]"
                                                                class="custom-file-input" multiple/>
                                                            <label class="custom-file-label">Chọn file</label>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-success add-upload"
                                                            field-file="survey_price[{{ $indexSP }}][accompanying_document]">
                                                        Thêm file upload
                                                    </button>
                                                </div>
                                                <div class="form-group">
                                                    <label for="survey_price[{{ $indexSP }}][note]">Ghi chú</label>
                                                    {!! Form::text('survey_price['.$indexSP.'][note]', null, array('class' => 'form-control')) !!}
                                                </div>
                                                <hr class="hor">
                                            </div>
                                        @endif
                                    </div>
                                    @if (!in_array($requestModel->status, $statusNotEdit))
                                        <div class="form-group">
                                            <button type="button" class="btn btn-success add-survey-price">
                                                Thêm Khảo Sát Giá
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">Lưu Khảo Sát Giá</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        @endif --}}
                        @endpermission
                    @php
                        $hidePercentPayment = in_array($requestModel->category, array_keys(\App\Helpers\DataHelper::getCategoriesForIndex([\App\Helpers\DataHelper::DINH_KY, \App\Helpers\DataHelper::HOAT_DONG])));
                    @endphp
                    @if(\App\Enums\ProcessStatus::Pending != $requestModel->status || $hidePercentPayment)
                        @include('admins.requests.includes.config_payment', ['hidePercentPayment' => $hidePercentPayment])
                    @endif
                    <!-- /.card-body -->
                        <div class="card-footer text-right">
                            @if(!empty($canCreatePayment) && $canCreatePayment || $isPaymentStep4)
                                @permission('admin.payment.create')
                                @if(\App\Enums\ProcessStatus::Approved == $requestModel->status)
                                    <a href="{{ route('admin.payment.create', ['requestId' => $requestModel->id]) }}"
                                       class="btn btn-success">Tạo Phiếu Chi</a>
                                @endif
                                @endpermission
                            @endif
                            @if(!empty($canCreateWarehouseReceipt) && $canCreateWarehouseReceipt)
                                @permission('admin.warehouse-receipt.create')
                                @if(\App\Enums\ProcessStatus::Approved == $requestModel->status)
                                    <a href="{{ route('admin.warehouse-receipt.create', ['request_id' => $requestModel->id]) }}"
                                       class="btn btn-success">
                                        Tạo Phiếu nhập kho
                                    </a>
                                @endif
                                @endpermission
                            @endif
                            @if($requestModel->status == \App\Enums\ProcessStatus::Pending)
                                <input type="submit" class="btn btn-primary" form="main_form" value="Lưu Phiếu Yêu Cầu" />
                            @endif
                            <a href="{{ route('admin.request.index') }}" class="btn btn-default">Quay lại</a>
                        </div>
                    <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    @include('admins.requests.includes.search-material', ['url' => route('admin.co.get-material')])
                    @include('admins.requests.includes.select-warehouse', ['url' => route('admin.warehouse.show-form-create')])

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
                                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Đóng
                                    </button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </div>
                @php
                    if (!$co && isset($requestModel->category) && !in_array($requestModel->category,array_keys(array_values(\App\Helpers\DataHelper::getCategories([\App\Helpers\DataHelper::VAN_PHONG_PHAM]))[0]))) {
                      $isNotCo = true;
                    } else {
                      $isNotCo = false;
                    }
                @endphp
                @include('admins.includes.approval', ['id' => $requestModel->id, 'type' => 'request', 'status' => $requestModel->status, 'isNotCo' => $isNotCo])
            </div>
        </div>

        <div id="option_price_survey" class="d-none">
            @foreach($corePriceSurvey as $key => $priceSurvey)
                <option value={{$key}}>{{ $priceSurvey }}</option>
            @endforeach
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript"
            src="{{ asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/admin/requests.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            // Init data
            bsCustomFileInput.init();
            $('.form-root .table tbody td.code input').each(function () {
                aCode.push($(this).val());
            });
            getHtmlFile('accompanying_document');
            $('.add-survey-price').click(function () {
                var length = parseInt($('.list-survey-price .item-survey-price:last').attr('data-index')) + 1;
                $('.list-survey-price').append(addHtmlSurveyPrice(length));
                bsCustomFileInput.init();
            });

            $(document).on("keyup", '[name="tmp_price[]"]', function() {
                var data = formatCurrent(this.value);
                $(this).val(data.format);
                let parent = $(this).parent();
                parent.find('[name="price[]"]').val(data.original)
            })

            $(document).on("focus", '[name="deadline[]"]', function() {
                $(this).datepicker({
                    format: 'yyyy-mm-dd'
                });
            })

            $('.dataTable').each(function() {
            var table = $(this).DataTable(); // Khởi tạo DataTable cho mỗi bảng

            // Thêm các dropdown Select2 cho mỗi cột trong footer
            $(this).find('tfoot th').each(function(index) {
                var title = $(this).text();
                var select = $('<select class="select2" multiple="multiple" style="width:100%" ><option value="">' + title + '</option></select>');
                $(this).html(select);

                // Lấy tất cả các giá trị duy nhất từ cột và thêm vào Select2
                table.column(index).data().unique().sort().each(function(d) {
                    // Loại bỏ các thẻ HTML khỏi dữ liệu nếu cần
                    select.append('<option value="' + d + '">' + d + '</option>');
                });

                // Khởi tạo Select2
                select.select2();
            });
            console.log(1);
            // Khởi tạo Select2 cho các dropdown vừa tạo
            $(this).find('.select2').select2();

            // Thêm sự kiện tìm kiếm cho mỗi cột
            table.columns().every(function() {
                var column = this;
                $('select', this.footer()).on('change', function() {
                    var val = $(this).val(); // Lấy giá trị đã chọn
                    if (val.length > 0) {
                        // Tạo chuỗi regex để tìm kiếm với tất cả các lựa chọn
                        val = val.map(function(v) {
                            return $.fn.dataTable.util.escapeRegex(v);
                        }).join('|');
                    }
                    column.search(val ? val : '', true, false).draw();
                });
            });
        });

        });

        function getHtmlFile(field) {
            var contentDocument = $('#' + field + '_display').attr('content');
            if (contentDocument) {
                var data = JSON.parse(contentDocument);
                var eleModal = $('#' + field + '_modal');
                if (data.length) {
                    var status = {{ in_array($requestModel->status, [\App\Enums\ProcessStatus::Approved, \App\Enums\ProcessStatus::Unapproved]) }}
                    $.each(data, function (index, value) {
                        var html = '<div class="data-file">' + checkFile(value) + '<div class="mt-2">';
                        if (!status) {
                            html += '<button type="button" class="btn btn-danger form-control" onclick="removeFile(this)" data-path="' + value.path + '">Xoá file</button>';
                        }
                        html += '</div></div>';
                        eleModal.find('.modal-body').append(html);
                    });
                } else {
                    eleModal.find('.modal-body').append('<p class="text-center">Chưa upload chứng từ.</p>');
                }
            }
        }

        function removeFile(_this) {
            var route = "{{ route('admin.request.remove-file') }}";
            // console.log($(_this).attr('data-id') !== "",$(_this).parents('.modal:first').attr('id').indexOf('accompanying_document_survey_price') !== -1)
            if($(_this).attr('data-id') !== "") {
                var route = "{{ route('admin.request.remove-file-survey-price') }}";
                var data = {
                    id: $(_this).attr('data-id'),
                    path: $(_this).attr('data-path')
                };
            } else if ($(_this).parents('.modal:first').attr('id').indexOf('accompanying_document_survey_price') !== -1) {
                var route = "{{ route('admin.request.remove-file-survey-price') }}";
                var data = {
                    id: $(_this).parents('.item-survey-price:first').find('.survey_price_id').val(),
                    path: $(_this).attr('data-path')
                };
            } else {
                var route = "{{ route('admin.request.remove-file') }}";
                var data = {id: $('[name=id]').val(), path: $(_this).attr('data-path')};
            }
            $.ajax({
                method: "POST",
                url: route,
                data: data
            })
                .done(function (data) {
                    if (data.success) {
                        $(_this).parents('.data-file:first').remove();
                        alert('Xoá file thành công.');
                    } else {
                        alert('Xoá file thất bại.');
                    }
                });
        }

        function removeFilePaymentDoc(_this) {
            var route = "{{ route('admin.request.remove-file-payment-document') }}";
            var data = {id: $('[name=id]').val(), path: $(_this).attr('data-path'), key: $(_this).attr('data-key')};
            $.ajax({
                method: "POST",
                url: route,
                data: data
            }).done(function (data) {
                if (data.success) {
                    alert('Xoá file thành công.');
                    window.location.reload()
                } else {
                    alert('Xoá file thất bại.');
                }
            });
        }

        function addHtmlSurveyPrice(indexSP) {
            let option = $('#option_price_survey').html();
            var html = '<div class="item-survey-price" data-index="' + indexSP + '">'
                + '<div class="form-group">'
                + '<div class="">'
                + '<label for="is_accept' + indexSP + '">Khảo Sát Giá ' + (indexSP + 1) + '</label>'
                + '</div>'
                + '</div>'
                + '<div class="form-group">'
                + '<label>Khảo sát giá</label>'
                + '<select class="form-control" name="survey_price[' + indexSP + '][core_price_survey_id]">'
                + option
                + '</select>'
                + '</div>'
                + '<div class="form-group">'
                + '<label>Chứng từ đi kèm</label>'
                + '<div class="input-group block-file">'
                + '<div class="custom-file">'
                + '<input type="file" name="survey_price[' + indexSP + '][accompanying_document][]" class="custom-file-input" multiple />'
                + '<label class="custom-file-label">Chọn file</label>'
                + '</div>'
                + '</div>'
                + '<button type="button" class="btn btn-success add-upload" field-file="survey_price[' + indexSP + '][accompanying_document]">'
                + 'Thêm file upload'
                + '</button>'
                + '</div>'
                + '<div class="form-group">'
                + '<label for="survey_price[' + indexSP + '][note]">Ghi chú</label>'
                + '<input type="text" name="survey_price[' + indexSP + '][note]" class="form-control" />'
                + '</div>'
                + '<button type="button" class="btn btn-danger" onclick="removeSurveyPrice(this)">Xoá Khảo Sát Giá</button>'
                + '<hr class="hor">'
                + '</div>';
            return html;
        }

        function removeSurveyPrice(_this) {
            $(_this).parents('.item-survey-price:first').remove();
        }
    </script>
@endsection
