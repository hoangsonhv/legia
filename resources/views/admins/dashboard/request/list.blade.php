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
        @include('admins.dashboard.list-request')
      </section>
    </div>
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

