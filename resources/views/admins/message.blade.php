@php
	$AdminHelper = new \App\Helpers\AdminHelper;
@endphp
@if (count($errors) > 0)
    @if(is_array($errors))
        @php $listErrors = $errors; @endphp
    @else
        @php $listErrors = $errors->all(); @endphp
    @endif
    @foreach ($listErrors as $error)
        {!! $AdminHelper::message($error) !!}
    @endforeach
@elseif ($message = Session::get('error'))
    {!! $AdminHelper::message($message) !!}
@elseif ($message = Session::get('success'))
    {!! $AdminHelper::message($message, 'alert-success') !!}
@endif
