@extends('layouts.admin')

@section('content')
<div class="container-fluid">
        
    @php
        $type = isset($type) ? $type : 'info';
        $message = isset($message) ? $message : '...';
    @endphp

    <div class="alert alert-{{ $type }} mt-5" role="alert">
        {{ $message }}            
    </div>

</div>
@endsection