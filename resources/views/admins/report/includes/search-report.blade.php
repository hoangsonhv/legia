{!! Form::open(array('route' => $route, 'method' => 'get')) !!}
<input type="hidden" name="type" value="{{request('type')}}" />
<div class="mb-3">
    <div class="row">
        @if(request()->has('type') && request()->type == 'date')
            <div class="col-4">
                <div class="input-group">
                    <input type="text" name="from_date" class="form-control float-right"
                           placeholder="Từ ngày" value="{{ request()->has('from_date') ? request()->from_date : Carbon\Carbon::now()->subDays(7)->format('Y-m-d')}}">
                    <input type="text" name="to_date" class="form-control float-right mr-1"
                           placeholder="Đến ngày" value="{{ request()->has('to_date') ? request()->to_date : date('Y-m-d')}}">
                </div>
            </div>
        @endif
        @if(request()->has('type') && request()->type == 'month')
            <div class="col-2">
                <div class="input-group">
                    @php
                        for($i = 1; $i <= 12; $i++){
                            $months[$i < 10 ? ('0' . $i) : $i ] = 'Tháng ' . $i;
                        }
                    @endphp
                    {!! Form::select('month', $months, request()->has('month') ? request()->month : date("m"), array('class' => 'form-control', 'placeholder' => '-- Vui lòng chọn tháng --')) !!}
                </div>
            </div>
        @endif
        @if(request()->has('type') && request()->type == 'year')
            <div class="col-2">
                <div class="input-group">
                    @php
                        for($i = 2022; $i <= 2030; $i++){
                            $year[$i] = 'Năm ' . $i;
                        }
                    @endphp
                    {!! Form::select('year', $year, request()->has('year') ? request()->year : date("Y"), array('class' => 'form-control', 'placeholder' => '-- Vui lòng chọn năm --')) !!}
                </div>
            </div>
        @endif
        <div class="col-2">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
        </div>
    </div>
</div>
<div style="display: flow-root;">
<div class="float-right cursor-pointer">
    <div class="card card-gray card-tabs">
        <div class="card-header">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a href="{{route($route, ['type' => 'date'])}}"
                       class="nav-link {{(request()->has('type') && request()->type == 'date') ? 'active' : ''}}"
                       role="tab"
                       aria-selected="true">
                        Ngày
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route($route, ['type' => 'month'])}}"
                       class="nav-link {{(request()->has('type') && request()->type == 'month') ? 'active' : ''}}"
                       role="tab"
                       aria-selected="true">
                        Tháng
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route($route, ['type' => 'year'])}}"
                       class="nav-link {{(request()->has('type') && request()->type == 'year') ? 'active' : ''}}"
                       role="tab"
                       aria-selected="true">
                        Năm
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
</div>
{!! Form::close() !!}
