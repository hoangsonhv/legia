{{-- @permission(['admin.co-tmp.index', 'admin.co-tmp.create'])
@if(!empty($coTmps) && count($coTmps))
    <div class="mb-3 border p-3">
        <h5 class="mb-3"><b>Danh sách Chào giá</b></h5>
        <div class="row mt-2">
            @foreach($coTmps as $coTmp)
                <div class="col-3">
                    <div class="card card-info"  style="height: 300px">
                        <div class="card-header">
                            <a href="{{route('admin.co-tmp.edit', ['id' => $coTmp->id])}}">
                                {{$coTmp->code}}
                            </a>
                        </div>
                        <div class="card-body">
                            
                            <div class="row">
                                <div class="co-alert-warning w-100">
                                    Đang chờ tạo CO
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="col-11">
                                    @if($coTmp->admin)
                                        {{ $coTmp->admin->name }}
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="col-11">
                                    @if($coTmp->customer)
                                        {{$coTmp->customer->ten}}
                                        &nbsp;
                                        <span><b>#{{ $coTmp->core_customer ? $coTmp->core_customer->code : '' }}</b></span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fas fa-flag"></i>
                                </div>
                                <div class="col-11">
                                    @if($coTmp->status == \App\Enums\ProcessStatus::Approved)
                                        <span class="badge bg-success">
                                            {{ \App\Enums\ProcessStatus::all()[$coTmp->status] }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                                @if($coTmp->co_not_approved)
                                <div class="row bage bg-warning p-1 pl-2 mt-1 mb-2 rounded">
                                    <div class="">{{$coTmp->co_not_approved->note}}</div>
                                </div>
                                @endif
                            <div class="row float-right">
                                <a href="{{route('admin.co.create', ['coTmpId' => $coTmp->id])}}" target="_blank">
                                    <button class="btn btn-success">
                                        Tạo CO
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endpermission --}}

@permission(['admin.co-tmp.index', 'admin.co-tmp.create'])
@if(!empty($coTmps) && count($coTmps))
<div class="mb-3 border p-3">
    <div class="d-flex  justify-content-between">
        <h5 class="mb-3"><b>Danh sách Chào giá</b></h5>
        <div class="w-50">
            <form action="">
                <div class="form-group d-flex">
                    <input type="text" class="form-control mr-3"  name="code" id="" placeholder="Nhập mã CO">
                    <button class="btn btn-primary w-25" type="submit">Tìm kiêm</button>
                </div>

               
            </form>
           
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>Mã CO</th>
              <th>Tên người tạo</th>
              <th>Công ty</th>
              <th>Trạng thái</th>
              <th>Nội dung</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($coTmps as $coTmp)
            
                <td>
                    {{$coTmp->code}}
                </td>
                <td>
                    @if($coTmp->admin)
                        {{ $coTmp->admin->name }}
                    @endif
                </td>
                <td>
                    @if($coTmp->customer)
                        {{$coTmp->customer->ten}}
                        &nbsp;
                        <span><b>#{{ $coTmp->core_customer ? $coTmp->core_customer->code : '' }}</b></span>
                    @endif
                </td>
                <td>
                    @if($coTmp->tong_gia < $limitApprovalCg)
                        <span class="badge bg-success">
                            Không cần xét duyệt
                        </span>
                    @else
                        <span class="badge bg-success">
                            {{ \App\Enums\ProcessStatus::all()[$coTmp->status] }}
                        </span>
                    @endif
                </td>
                <td>
                    @if($coTmp->tong_gia < $limitApprovalCg)
                        <div class="co-alert-warning w-100">
                            Đang chờ tạo CO
                        </div>
                    @else
                        @if($coTmp->status == \App\Enums\ProcessStatus::Pending)
                            <div class="co-alert-warning w-100">
                                Đang chờ xét duyệt
                            </div>
                        @else
                            <div class="co-alert-warning w-100">
                                Đang chờ tạo CO
                            </div>
                        @endif
                    @endif
                </td>
                <td>
                    @if($coTmp->tong_gia < $limitApprovalCg)
                        <a href="{{route('admin.co.create', ['coTmpId' => $coTmp->id])}}" target="_blank">
                            <button class="btn btn-success">
                                Tạo CO
                            </button>
                        </a>
                    @else
                        @if($coTmp->status == \App\Enums\ProcessStatus::Pending)
                            <a href="{{route('admin.co-tmp.edit', $coTmp->id)}}" target="_blank">
                                <button class="btn btn-success">
                                    Duyệt khảo sát
                                </button>
                            </a>
                        @else
                            <a href="{{route('admin.co.create', ['coTmpId' => $coTmp->id])}}" target="_blank">
                                <button class="btn btn-success">
                                    Tạo CO
                                </button>
                            </a>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {!! $coTmps->appends(session()->getOldInput())->links() !!}
        </div>
    </div>
</div>
      @endif
@endpermission