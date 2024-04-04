{{--@permission(['admin.treking.index', 'admin.treking.create'])--}}
{{-- @if(!empty($coes) && count($coes))
    <div class="mb-3 border p-3">
        <h5 class="mb-3"><b>Treking CO</b></h5>
        <div class="row mt-2">
            @php
                $steps = \App\Services\CoService::stepCo();
            @endphp
            @foreach($coes as $co)
                <div class="col-3">
                    <div class="card card-info" style="height: 250px">
                        <div class="card-header">
                            <a href="{{route('admin.co.edit', ['id' => $co->id])}}">
                                {{$co->code}}
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($co->currentStep && isset($steps[$co->currentStep->step]))
                                    <div class="co-alert-warning w-100">
                                        {{$steps[$co->currentStep->step]['title']}}
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="col-11">
                                    @if($co->admin)
                                        {{ $co->admin->name }}
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="col-11">
                                    @if($co->customer)
                                        {{$co->customer->ten}}
                                        &nbsp;
                                        <span><b>#{{ $co->core_customer ? $co->core_customer->code : '' }}</b></span>
                                    @endif
                                </div>
                            </div>
                            <div class="row float-right">
                                @if($co->currentStep && $steps[$co->currentStep->step]['act_router'])
                                    <a target="_blank" href={{ route($steps[$co->currentStep->step]['act_router'],
                                        $co->currentStep->step == \App\Models\CoStepHistory::STEP_WAITING_APPROVE_MANUFACTURE ?
                                            ['co_id' => $co->currentStep->object_id] :
                                         ['id' => $co->currentStep->object_id]) }}>
                                        <button class="btn btn-success">
                                            @if($steps[$co->currentStep->step]['action'] == \App\Models\CoStepHistory::ACTION_CREATE)
                                                Tạo
                                            @endif
                                            @if($steps[$co->currentStep->step]['action'] == \App\Models\CoStepHistory::ACTION_APPROVE)
                                                Duyệt
                                            @endif
                                            @if($steps[$co->currentStep->step]['action'] == \App\Models\CoStepHistory::ACTION_SELECT)
                                                Kiểm tra
                                            @endif
                                        </button>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif --}}
@if(!empty($coes) && count($coes))
    <div class="mb-3 border p-3">
        <div class="d-flex  justify-content-between">
        <h5 class="mb-3"><b>Treking CO</b></h5>
        <div class="w-50">
            <form action="">
                <div class="form-group d-flex">
                    <input type="text" class="form-control mr-3"  name="code" id="" placeholder="Nhập mã CO">
                    <button class="btn btn-primary w-25" type="submit">Tìm kiêm</button>
                </div>


            </form>

        </div>
        </div>
        <div class="row mt-2">
            @php
                $steps = \App\Services\CoService::stepCo();
            @endphp
            {{-- @foreach($coes as $co)
                <div class="col-3">
                    <div class="card card-info" style="height: 250px">
                        <div class="card-header">
                            <a href="{{route('admin.co.edit', ['id' => $co->id])}}">
                                {{$co->code}}
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($co->currentStep && isset($steps[$co->currentStep->step]))
                                    <div class="co-alert-warning w-100">
                                        {{$steps[$co->currentStep->step]['title']}}
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="col-11">
                                    @if($co->admin)
                                        {{ $co->admin->name }}
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="col-11">
                                    @if($co->customer)
                                        {{$co->customer->ten}}
                                        &nbsp;
                                        <span><b>#{{ $co->core_customer ? $co->core_customer->code : '' }}</b></span>
                                    @endif
                                </div>
                            </div>
                            <div class="row float-right">
                                @if($co->currentStep && $steps[$co->currentStep->step]['act_router'])
                                    <a target="_blank" href={{ route($steps[$co->currentStep->step]['act_router'],
                                        $co->currentStep->step == \App\Models\CoStepHistory::STEP_WAITING_APPROVE_MANUFACTURE ?
                                            ['co_id' => $co->currentStep->object_id] :
                                         ['id' => $co->currentStep->object_id]) }}>
                                        <button class="btn btn-success">
                                            @if($steps[$co->currentStep->step]['action'] == \App\Models\CoStepHistory::ACTION_CREATE)
                                                Tạo
                                            @endif
                                            @if($steps[$co->currentStep->step]['action'] == \App\Models\CoStepHistory::ACTION_APPROVE)
                                                Duyệt
                                            @endif
                                            @if($steps[$co->currentStep->step]['action'] == \App\Models\CoStepHistory::ACTION_SELECT)
                                                Kiểm tra
                                            @endif
                                        </button>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach --}}
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <!-- <th>Mã CO</th> -->
                      <!-- <th>Tên người tạo</th>
                      <th>Công ty</th> -->
                      <!-- <th>Nội dung</th> -->
                      <!-- <th>Action</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($coes as $co)

                    <tr>
                        <td class="bordered-cell" style="width:350px">
                            <a href="{{route('admin.co.edit', ['id' => $co->id])}}">
                                {{$co->code}}
                            </a>
                            <br>
                            @if($co->customer)
                                <span><b>Tên công ty: {{$co->customer->ten}}</b></span>
                                <br>
                                <span><b>#{{ $co->core_customer ? $co->core_customer->code : '' }}</b></span>
                                <br>
                                <span><b>Tổng giá: {{ number_format($co->tong_gia, 0, ',', '.') }} đ</b></span>
                            @endif
                        </td>
                        <td class="bordered-cell">
                            <ul class="step-progress-bar-vertical">
                                @foreach($co->stepHistories as $history)
                                    <li class="{{ $history->step == $co->currentStep->step ? 'active' : 'completed' }}">
                                        <span class="date">{{ $history->created_at->format('d-m-Y H:i:s') }}</span>
                                        {{ $steps[$history->step]['title'] }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {!! $coes->appends(session()->getOldInput())->links() !!}
                </div>
            </div>
        </div>
    </div>
@endif
{{--@endpermission--}}
