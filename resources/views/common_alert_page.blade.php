@extends('layouts.app')

@section('content')
<div class="container">
    
    @php
        $type = isset($type) ? $type : 'light';
        $message = isset($message) ? $message : '...';
    @endphp

    <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

</div>
@endsection