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
  </style>
@endsection
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Main row -->
    <div class="row">
      <section class="col-lg-12">
        {{-- @include('admins.dashboard.list-co-tmps') --}}
        @include('admins.dashboard.list-coes')
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

@section('js')
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
@endsection
