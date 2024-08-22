<div class="modal fade" id="modal-materials">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h4 class="modal-title">Thêm hàng hoá</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-row p-2">
          <div class="col-md-6">
            {!! Form::text('code', null, array('class' => 'form-control', 'placeholder' => 'Mã vật liệu')) !!}
          </div>
          <div class="col-md-6">
            {!! Form::text('lot_no', null, array('class' => 'form-control', 'placeholder' => 'Lot No')) !!}
          </div>
        </div>
        <div class="form-row p-2">
          <div class="col-md-6">
            {!! Form::text('vat_lieu', null, array('class' => 'form-control', 'placeholder' => 'Tên vật liệu')) !!}
          </div>
          <div class="col-md-6">
            {!! Form::text('do_day', null, array('class' => 'form-control', 'placeholder' => 'Độ dày')) !!}
          </div>
        </div>
        <div class="form-row p-2">
          <div class="col-md-3">
            <button type="button" id="search-material" class="btn btn-default form-control" data-url="{{ $url }}">
              <i class="fas fa-search"></i>Tìm kiếm
            </button>
          </div>
        </div>
        <div class="item-material mt-5 d-none"></div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" data-dismiss="modal" class="btn btn-default">Đóng</button>
        <button type="button" id="load-material" class="btn bg-success">Thêm hàng hoá</button>
      </div>
      {!! Form::close() !!}
      <div class="modal-footer justify-content-between">
        @if(isset($coModel)) 
          @include('admins.coes.includes.list-products', [
              'warehouses' => $warehouses,
              'collect' => true,
              'is_co' => true,
              'hiddenShowPrice' => true,
          ])
        @endif
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>