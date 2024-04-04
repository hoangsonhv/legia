@extends('layouts.admin')

@section('content')

@include('admins.breadcrumb')

@section('css')
  <style>
    .card-co {
      background-color: #fff;
      border-radius: 5px;
      padding: 12px;
    }
    .step-progress-bar-vertical {
        list-style: none;
        counter-reset: step;
    }
    .step-progress-bar-vertical li {
        position: relative;
        padding-left: 30px;
        text-align: left;
        margin-bottom: 10px;
    }
    .step-progress-bar-vertical li:before {
        content: counter(step);
        counter-increment: step;
        width: 20px;
        height: 20px;
        line-height: 20px;
        border: 1px solid #ddd;
        display: block;
        text-align: center;
        margin: 0 auto 10px 0;
        border-radius: 50%;
        background-color: #f0f0f0;
        position: absolute;
        left: 0;
        top: 0;
        color: #fff;
    }
    .step-progress-bar-vertical li:after {
        content: '';
        position: absolute;
        width: 1px;
        height: 100%;
        background-color: #ddd;
        top: 20px;
        left: 10px;
    }
    .step-progress-bar-vertical li:last-child:after {
        content: none;
    }
    .step-progress-bar-vertical li.active:before {
        border-color: red;
        background-color: red;
    }
    .step-progress-bar-vertical li.completed:before {
        border-color: #28a745;
        background-color: #28a745;
    }
    .step-progress-bar-vertical li.completed + li:after {
        background-color: #28a745;
    }
    .step-progress-bar-vertical li.completed {
        color: #888;
    }

    .step-progress-bar-vertical li .date {
        position: absolute;
        left: -150px;
        top: 0;
    }
    .bordered-cell {
        border-bottom: 2px solid #000;
    }
  </style>
@endsection
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Main row -->
    <div class="row">
      <section class="col-lg-12">
        {{-- @include('admins.dashboard.list-co-tmps') --}}
        @include('admins.dashboard.list-treking-co')
{{--        <div class="card card-success">--}}
{{--          <div class="card-header">--}}
{{--            <h3 class="card-title">{{ $titleForChart }}</h3>--}}
{{--            --}}{{-- <div class="card-tools">--}}
{{--              <button type="button" class="btn btn-tool" data-card-widget="collapse">--}}
{{--                <i class="fas fa-minus"></i>--}}
{{--              </button>--}}
{{--              <button type="button" class="btn btn-tool" data-card-widget="remove">--}}
{{--                <i class="fas fa-times"></i>--}}
{{--              </button>--}}
{{--            </div> --}}
{{--            --}}
{{--            <div class="card-tools">--}}
{{--              {!! Form::open(array('route' => 'admin.dashboard.index', 'method' => 'get')) !!}--}}
{{--              <div class="input-group">--}}
{{--                {!! Form::select('range_date', $listRangeDate, old('range_date'), array('class' => 'form-control float-right')) !!}--}}
{{--                <div class="input-group-append">--}}
{{--                  <button type="submit" class="btn btn-default">--}}
{{--                    <i class="fas fa-search"></i>--}}
{{--                  </button>--}}
{{--                </div>--}}
{{--              </div>--}}
{{--              {!! Form::close() !!}--}}
{{--            </div>--}}
{{--          </div>--}}
{{--          <div class="card-body">--}}
{{--            <div class="chart">--}}
{{--              <canvas id="dashboard-chart-canvas"></canvas>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--          <!-- /.card-body -->--}}
{{--        </div>--}}
        <!-- /.card -->
      </section>
    </div>
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

{{-- @section('js')
  <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
  <script type="text/javascript">
    $( document ).ready(function() {
      var data     = JSON.parse('{!! $data !!}');
      var context  = $('#dashboard-chart-canvas').get(0).getContext('2d');
      const config = {
        type: 'line',
        data: data,
        options: {
          plugins: {
            legend: {
              position: 'top',
            },
          }
        }
      };
      new Chart(context, config);
    });
  </script>
@endsection --}}
