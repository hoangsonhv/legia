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
              {!! Form::open(array('route' => 'admin.price-survey.index', 'method' => 'get')) !!}
              <div class="input-group">
                <input type="text" name="key_word" class="form-control float-right" placeholder="Nhóm sản phẩm" value="{{old('key_word')}}">
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
                  <th>Mã CO</th>
                  <th>Mã yêu cầu</th>
                  <th>IMPO/ DOME</th>
                  <th>Nhà cung cấp</th>
                  <th>Tên nguyên vật liệu</th>
                    <th>Chứng từ</th>
                  <th>Người yêu cầu</th>
                  <th>Ngày yêu cầu</th>
                  <th>Ngày có kết quả</th>
                  <th>Giá trị báo giá</th>
                  <th>Người thực hiện</th>
                  <th>&nbsp</th>
                </tr>
              </thead>
              <tbody>
                @foreach($datas as $key => $data)
                  <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->co ? $data->co->code : '' }}</td>
                    <td>{{ $data->request ? $data->request->code : '' }}</td>
                    <td>{{ $types[$data->type] }}</td>
                    <td>{{ $data->supplier }}</td>
                    <td>{{ (isset($info_product_n_sup[$key])) ? $info_product_n_sup[$key]->product->first()->attribute['mo_ta'] : null }}</td>
                      <td>
                          @php
                            $survey_price = App\Models\SurveyPrice::query()->where('core_price_survey_id', $data->id)->first();
                          @endphp
                          @if($survey_price)
                              @if($survey_price->accompanying_document != '[]')
                                  <button type="button" class="btn btn-success" data-toggle="modal"
                                          data-target="#accompanying_document_survey_price_modal{{ $survey_price->id }}">
                                      Hiển thị chứng từ đã
                                      tồn tại
                                  </button>
                              @else
                                Không tồn tại chứng từ
                              @endif
                              <div class="modal fade" id="accompanying_document_survey_price_modal{{ $survey_price->id }}">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header bg-success">
                                              <h4 class="modal-title">Chứng từ khảo sát
                                                  giá</h4>
                                              <button type="button" class="close" data-dismiss="modal"
                                                      aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                              </button>
                                          </div>
                                          <div class="modal-body">
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
                                              @foreach (json_decode($survey_price->accompanying_document, true) as $index => $file)
                                              <div class="data-file">
                                                  {!! \App\Helpers\AdminHelper::checkFile($file) !!}
                                                  @if (!in_array($survey_price->request->status, $statusNotEdit))
                                                  <div class="mt-2">
                                                      <button type="button" class="btn btn-danger form-control"
                                                              onclick="removeFile(this)"
                                                              data-id={{ $survey_price->id }}
                                                          data-path="{{ $file['path'] }}">
                                                          Xoá file
                                                      </button>
                                                  </div>
                                                  @endif
                                              </div>
                                              @endforeach
                                          </div>
                                          <div class="modal-footer">
                                              <button type="button" class="btn btn-outline-dark"
                                                      data-dismiss="modal">Đóng
                                              </button>
                                          </div>
                                      </div>
                                      <!-- /.modal-content -->
                                  </div>
                                  <!-- /.modal-dialog -->
                              </div>
                          @endif
                      </td>
                    <td>{{ $data->admin->name }}</td>
                    <td>{{ $data->created_at }}</td>
                    <td>{{ ($data->status) ? $data->updated_at : null }}</td>
                    <td>{{ number_format($data->price, 0)  }}</td>
                    <td>{{ $data->admin ? $data->admin->name : '' }}</td>
                    <td>
                      @permission('admin.price-survey.edit')
                        <a href="{{ route('admin.price-survey.edit', ['id' => $data->id]) }}"
                           role="button"
                           class="btn btn-outline-primary btn-sm"
                           title="Cập nhật">
                          <i class="fas fa-solid fa-pen"></i>
                        </a>
                      @endpermission
                      @permission('admin.price-survey.destroy')
                        <a href="{{ route('admin.price-survey.destroy', ['id' => $data->id]) }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa khảo sát giá này không ?')">
                          <i class="fas fa-trash-alt"></i>
                        </a>
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
