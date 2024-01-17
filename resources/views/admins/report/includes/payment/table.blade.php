<div class="card-body table-responsive p-0">
    <table class="table table-hover text-nowrap">
      <thead>
        <tr>
          <th>STT</th>
          <th>Mã CO</th>
          <th>Danh mục</th>
          <th>Tổng tiền</th>
        </tr>
      </thead>
      <tbody>
        @php
            $sumPayment = 0;
        @endphp
        @foreach($tablePayment as $index => $payment)
          <tr>
            @php
                $route = $payment->co_id ? 'admin.co.edit' : 'admin.payment.edit';
                $id = $payment->co_id ?? $payment->id;
                $sumPayment += $payment->sum_tong_tien;
            @endphp
            <td> {{ $index + 1 }}</td>
            <td><a href="{{route($route,['id' => $id])}}">{{ $payment->co_code ?? $payment->code }}</a></td>
            <td>{!! isset($categories[$payment->category]) ? $categories[$payment->category] : '' !!}</td>
            <td>{{ number_format($payment->sum_tong_tien) }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr class="table-success font-weight-bold">
            <td><b>Tổng chi</b></td>
            <td colspan="2"></td>
            <td >{{ number_format($sumPayment) }}</td>
        </tr>
      </tfoot>
    </table>
  </div>