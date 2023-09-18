@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.min.css') }}">
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
          {!! Form::model($configs, array('route' => ['admin.config.store'], 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
            {!! Form::hidden('id', null) !!}
            <div class="card-body">
              @foreach($configs as $config)
              <div class="form-group">
                <label for="{{ $config->key }}">{{ $config->label }}</label>
                @if($config->type === 'textarea')
                  {!! Form::textarea($config->key, old($config->key, $config->value), array('class' => 'form-control')) !!}
                @elseif($config->type === 'file')
                  <div class="input-group">
                    <div class="custom-file">
                      {!! Form::file($config->key, array('class' => 'custom-file-input')) !!}
                      <label class="custom-file-label">Chọn file</label>
                    </div>
                  </div>
                  @if($config->value)
                  <div class="mt-2">
                    <img src="{{ asset($config->value) }}" title="{{ $config->label }}" height="100" />
                  </div>
                  @endif
                @else
                  @php
                    $valText = old($config->key, $config->value);
                    // if (is_numeric($valText)) {
                    //   $valText = number_format($valText);
                    // }
                  @endphp
                  {!! Form::text($config->key, $valText, array('class' => 'form-control')) !!}
                @endif
              </div>
              @endforeach
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Lưu thông tin</button>
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
<script type="text/javascript" src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
<script>
  $(function () {
    // Init data
    bsCustomFileInput.init();
    $('textarea').summernote();
  });
</script>
@endsection
