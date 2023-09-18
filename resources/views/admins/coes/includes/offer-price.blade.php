<div class="modal fade" id="modal-import-offer">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h4 class="modal-title">Import danh sách sản phẩm</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>File import</label>
          <div class="input-group">
            <div class="custom-file">
              <input type="file" name="import-plate" id="file-import-offer" class="custom-file-input" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required />
              <label class="custom-file-label">Chọn file</label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" data-dismiss="modal" class="btn btn-default">Đóng</button>
        <button type="button" id="load-product" class="btn bg-success" data-url="{{ route('admin.co.get-offer-price') }}">Import thông tin</button>
      </div>
      {!! Form::close() !!}
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
