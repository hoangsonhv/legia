<div class="card-body table-responsive p-0">
    <table class="table table-hover text-nowrap">
      <thead>
        <tr>
          <th>STT</th>
          <th>Mã CO</th>
          <th>Tổng tiền</th>
        </tr>
      </thead>
      <tbody>
        @php
            $sumPayment = 0;
        @endphp
        @foreach($tableReceipt as $index => $receipt)
        @php
          $sumPayment += $receipt->sum_tong_tien;
        @endphp
          <tr>
            <td> {{ $index + 1 }}</td>
            <td><a href="{{route('admin.co.edit',['id' => $receipt->co_id])}}">{{ $receipt->co_code }}</a></td>
            <td>{{ number_format($receipt->sum_tong_tien) }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr class="table-success font-weight-bold">
            <td><b>Tổng thu</b></td>
            <td></td>
            <td >{{ number_format($sumPayment) }}</td>
        </tr>
      </tfoot>
    </table>
  </div>