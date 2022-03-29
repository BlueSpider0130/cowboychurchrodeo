@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    Select rodeo: 
    <hr>
    <ul>
        @foreach( $rodeos as $rodeo )
            <li class="py-4">
                {{ $rodeo->name }} <br>
                <x-rodeo-dates :model="$rodeo" /> <br>
                <a href="{{ route('L1.import.import', $rodeo) }}" class="btn btn-primary">Import Entries</a>
            </li>
        @endforeach
    </ul>
</div>
@endsection