<style type="text/css">
  .list-materials {
    max-height: 500px;
  }
</style>
<h3 class="text-primary">Danh sách vật liệu kho</h3>
<div class="table-responsive list-materials p-0">
  <table class="table table-head-fixed table-bordered table-hover text-wrap data-products">
    <thead>
      <tr align="center">
        <th class="align-middle">Số TT</th>
        <th class="align-middle">Mã HH</th>
        <th class="align-middle">Vật liệu</th>
        <th class="align-middle">Độ dày</th>
        <th class="align-middle">Hình dạng</th>
        <th class="align-middle">Dia W W1</th>
        <th class="align-middle">L L1</th>
        <th class="align-middle">W2</th>
        <th class="align-middle">L2</th>
        <th class="align-middle">SL - Tấm</th>
        <th class="align-middle">SL - m2</th>
        <th class="align-middle">Lot No</th>
        <th class="align-middle">Ghi Chú</th>
        <th class="align-middle">Date</th>
        <th class="align-middle">Tồn SL - Tấm</th>
        <th class="align-middle">Tồn SL - m2</th>
        <th class="align-middle">Tồn SL - Cái</th>
      </tr>
    </thead>
    <tbody>
      @if(!empty($warehouses))
        @php
          $sequence = 1;
        @endphp
        @foreach($warehouses as $warehouse)
          <tr align="center">
            <td>{{ $sequence }}</td>
            <td>{{ $warehouse->code }}</td>
            <td>{{ $warehouse->vat_lieu }}</td>
            <td>{{ $warehouse->do_day }}</td>
            <td>{{ $warehouse->hinh_dang }}</td>
            <td>{{ $warehouse->dia_w_w1 }}</td>
            <td>{{ $warehouse->l_l1 }}</td>
            <td>{{ $warehouse->w2 }}</td>
            <td>{{ $warehouse->l2 }}</td>
            <td>{{ $warehouse->sl_tam }}</td>
            <td>{{ $warehouse->sl_m2 }}</td>
            <td>{{ $warehouse->lot_no }}</td>
            <td>{{ $warehouse->ghi_chu }}</td>
            <td>{{ $warehouse->date }}</td>
            <td>{{ $warehouse->ton_sl_tam }}</td>
            <td>{{ $warehouse->ton_sl_m2 }}</td>
            <td>{{ $warehouse->ton_sl_cai }}</td>
          </tr>
          @php
            $sequence ++;
          @endphp
        @endforeach
      @endif
    </tbody>
  </table>
</div>
