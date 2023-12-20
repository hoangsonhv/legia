<div class="modal fade" id="modal-another-material">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Thêm hàng hoá</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="col-md-3">
                        {!! Form::select(
                            'model_type',
                            [null => 'Chọn loại kho'] + \App\Helpers\WarehouseHelper::listWarehouseNames(),
                            null,
                            ['class' => 'form-control form-select', 'required' => 'required'],
                        ) !!}
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="show-form" class="btn btn-primary form-control"
                            data-url="{{ $url }}">
                            <i class="fas fa-plus"></i>Thêm
                        </button>
                    </div>
                </div>
                <div class="form-material mt-5 d-none"></div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" data-dismiss="modal" class="btn btn-default">Đóng</button>
                <button type="button" id="load-other-material" class="btn bg-primary">Thêm hàng hoá</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
