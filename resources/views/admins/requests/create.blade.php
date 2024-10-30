@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
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
          {!! Form::open(array('route' => 'admin.request.store', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
            <div class="card-body">
              @if($co)
              <div class="form-group">
                <label for="co_id">CO</label>
                {!! Form::select('co_id', $co, null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
              </div>
              @endif
              <div class="form-group">
                <label for="category">Danh mục<b style="color: red;"> (*)</b></label>
                {!! Form::select('category', $categories, null, array('class' => 'form-control form-select', 'required' => 'required')) !!}
              </div>
              <div class="form-group">
                <label for="note">Ghi chú</label>
                {!! Form::text('note', null, array('class' => 'form-control')) !!}
              </div>
              <div class="form-group">
                <label>Chứng từ đi kèm</label>
                <div class="input-group block-file">
                  <div class="custom-file">
                    <input type="file" name="accompanying_document[]" class="custom-file-input" multiple />
                    <label class="custom-file-label">Chọn file</label>
                  </div>
                </div>
                <button type="button" class="btn btn-success add-upload">
                  Thêm file upload
                </button>
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
            <div class="card-body">
              <h3 class="title text-primary">Nội dung</h3>
              @if ($co)
                @include('admins.requests.includes.list-materials', ['co' => $co, 'coStep' => $coStep])
              @else
                @include('admins.requests.includes.list-service')
              @endif
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Lưu thông tin</button>
              <a href="{{ route('admin.request.index') }}" class="btn btn-default">Quay lại</a>
            </div>
          {!! Form::close() !!}
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
        @include('admins.requests.includes.search-material', ['url' => route('admin.co.get-material')])
        @include('admins.requests.includes.select-warehouse', ['url' => route('admin.warehouse.show-form-create')])
      </div>
    </div>
  </div>
</section>
@endsection
<style>
    #search-results {
        position: absolute; /* Đặt vị trí là tuyệt đối */
        z-index: 1000; /* Đảm bảo nó hiển thị trên các phần tử khác */
        background: white; /* Nền trắng để dễ đọc */
        border: 1px solid #ccc; /* Đường viền nhẹ */
        max-height: 100px; /* Chiều cao tối đa để tránh che khuất màn hình */
        overflow-y: auto; /* Thêm thanh cuộn nếu quá nhiều kết quả */
        width: calc(100% - 45px); /* Chiều rộng bằng với ô input trừ đi viền */
        margin-top: -4px;
        border-radius: 0 0 5px 5px;
    }
    .search-item {
        padding: 8px; /* Khoảng cách bên trong cho mỗi mục */
        cursor: pointer; /* Hiển thị con trỏ khi di chuột qua */
        margin-bottom: 0;
    }
    .search-item:hover {
        background-color: #f0f0f0; /* Hiệu ứng hover */
    }
</style>
@section('js')
<script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/admin/requests.js') }}"></script>
<script type="text/javascript">
  $( document ).ready(function() {
    // Init data
    bsCustomFileInput.init();
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


</script>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('keyup', 'input[name="code"]', function () {
            let input = $(this);
            input.attr('autocomplete', 'off');
            input.addClass('relative')
            $('#loading-all').hide()
            let type = $(this).parents('.modal-body:first').find('[name=model_type]').val();
            let url = window.location.origin + '/admin/warehouse/show-material-by-code';
            let param = $(this).val();
            if (param.length < 1) {
                $('#search-results').remove();
            }
            $.ajax({
                method: "GET",
                url: url,
                data: {
                    'model_type': type,
                    'code': param,
                }
            })
                .done(function(data) {
                    input.parent().find('#search-results').remove()
                    input.parent().append(data)
                    if ($(data).find('.search-item').length < 1) {
                        $('#search-results').remove();
                    }
                });
        });
        $(document).on('focusout', 'input[name="code"]', function () {
            setTimeout(function() {
                $('#search-results').remove();
            }, 200);
        });
        $(document).on('click', '#search-results', function (event) {
            event.stopPropagation();
        });
        $(document).on('click', '.search-item', function () {
            let selectedValue = $(this).data('value');
            $('input[name="code"]').val($(this).html());
            $('input[name="vat_lieu"]').val(selectedValue);
            $('#search-results').remove();
        });
    });
</script>
@endsection
