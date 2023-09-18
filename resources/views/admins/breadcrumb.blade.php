<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">{{ $breadcrumb['root'] }}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        @if($breadcrumb['data'])
        <ol class="breadcrumb float-sm-right">
          @foreach($breadcrumb['data'] as $val)
            <li class="breadcrumb-item">
              @if(!empty($val['href']))
                <a href="{{ $val['href'] }}">{{ $val['label'] }}</a>
              @else
                {{ $val['label'] }}
              @endif
            </li>
          @endforeach
        </ol>
        @endif
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->