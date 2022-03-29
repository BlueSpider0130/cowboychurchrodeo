@php 
    $layout = isset($layout) ? $layout : 'sfp-results::layout';
@endphp

@extends($layout)

@section('content')
<div class="container-fluid py-4">

    @include('sfp-results::results')

</div>
@endsection