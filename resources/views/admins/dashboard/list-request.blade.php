
@if(!empty($requests) && count($requests))
    <div class="mb-3 border p-3">
        <div class="d-flex  justify-content-between">
        <h5 class="mb-3"><b>Danh sách yêu cầu</b></h5>
        <div class="w-50">
            <form action="">
                <div class="form-group d-flex">
                    <input type="text" class="form-control mr-3"  name="code" id="" placeholder="Nhập mã Phiếu yêu cầu">
                    <button class="btn btn-primary w-25" type="submit">Tìm kiêm</button>
                </div>
            </form>
        </div>
        </div>
        <div class="row mt-2">
            @php
                $steps = \App\Services\CoService::stepCo();
            @endphp
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Mã CO</th>
                      <th>Tên người tạo</th>
                      <th>Nội dung</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($requests as $request)
                    <tr>
                        <td>
                            <a href="{{route('admin.request.edit', ['id' => $request->id])}}">
                                {{$request->code}}
                            </a>
                        </td>
                        <td>
                            @if($request->admin)
                                {{ $request->admin->name }}
                            @endif
                        </td>
                        <td>
                            @if($request->currentStep && isset($steps[$request->currentStep->step]))
                            <div class="co-alert-warning w-100">
                                {{$steps[$request->currentStep->step]['title']}}
                            </div>
                            @elseif($request->status == \App\Enums\ProcessStatus::Pending)
                            <div class="co-alert-warning w-100">
                                {{$steps[\App\Models\CoStepHistory::STEP_WAITING_APPROVE_REQUEST]['title']}}
                            </div>
                            @endif
                        </td>
                        <td>
                            @if($request->currentStep && $steps[$request->currentStep->step]['act_router'])
                                    <a target="_blank" href={{ route($steps[$request->currentStep->step]['act_router'],
                                         ['id' => $request->currentStep->object_id]) }}>
                                        <button class="btn btn-success">
                                            @if($steps[$request->currentStep->step]['action'] == \App\Models\CoStepHistory::ACTION_CREATE)
                                                Tạo
                                            @endif
                                            @if($steps[$request->currentStep->step]['action'] == \App\Models\CoStepHistory::ACTION_APPROVE)
                                                Duyệt
                                            @endif
                                            @if($steps[$request->currentStep->step]['action'] == \App\Models\CoStepHistory::ACTION_SELECT)
                                                Kiểm tra
                                            @endif
                                        </button>
                                    </a>
                            @elseif($request->status == \App\Enums\ProcessStatus::Pending)
                            <a target="_blank" href={{ route($steps[\App\Models\CoStepHistory::STEP_WAITING_APPROVE_REQUEST]['act_router'],
                                 ['id' => $request->id]) }}>
                                <button class="btn btn-success">
                                    Duyệt
                                </button>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {!! $requests->appends(session()->getOldInput())->links() !!}
                </div>
            </div>
        </div>
    </div>
@endif
{{--@endpermission--}}