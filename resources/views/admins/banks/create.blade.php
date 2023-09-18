@extends('layouts.admin')

@section('css')
  <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endsection

@section('content')

@include('admins.breadcrumb')

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        @include('admins.message')
      </div>
      <div class="col-12">
        <div class="card">
          {!! Form::open(array('route' => 'admin.bank.store', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
            <div class="card-body">
              <div class="form-group">
                <label for="name_bank">Loại tài chính<b style="color: red;"> (*)</b></label>
                {!! Form::select('type', $types, null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              <div class="form-group">
                <label for="name_bank">Tên ngân hàng / tiền mặt<b style="color: red;"> (*)</b></label>
                {!! Form::text('name_bank', null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              <div class="form-group">
                <label for="account_name">Tên tài khoản  / tiền mặt<b style="color: red;"> (*)</b></label>
                {!! Form::text('account_name', null, array('class' => 'form-control', 'required' => 'required')) !!}
              </div>
              <div class="form-group">
                <label for="account_number">Số ngân hàng  / tiền mặt</label>
                {!! Form::text('account_number', null, array('class' => 'form-control')) !!}
              </div>
              <div class="form-group">
                <label for="account_balance">Số dư tài khoản<b style="color: red;"> (*)</b></label>
                {!! Form::text('tmp_account_balance', null, array('class' => 'form-control', 'required' => 'required')) !!}
                {!! Form::hidden('account_balance', null) !!}
              </div>
              <div class="form-group">
                <label for="account_balance">Số dư đầu kì<b style="color: red;"> (*)</b></label>
                {!! Form::text('tmp_opening_balance', null, array('class' => 'form-control', 'required' => 'required')) !!}
                {!! Form::hidden('opening_balance', null) !!}
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Lưu thông tin</button>
              <a href="{{ route('admin.bank.index') }}" class="btn btn-default">Quay lại</a>
            </div>
          {!! Form::close() !!}
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</section>
@endsection
@section('js')
  <script type="text/javascript">
    $( document ).ready(function() {
      $('[name="tmp_account_balance"]').keyup(function(){
        var data = formatCurrent(this.value);
        $(this).val(data.format);
        $('[name="account_balance"]').val(data.original);
      });

      $('[name="tmp_opening_balance"]').keyup(function(){
        var data = formatCurrent(this.value);
        $(this).val(data.format);
        $('[name="opening_balance"]').val(data.original);
      });

      $('[name="type"]').change(function(e){
        let val = $(this).val();
        if(val == 1) {

        }
      });
    });
  </script>
@endsection