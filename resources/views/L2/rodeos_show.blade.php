@extends('layouts.producer')

@section('content')
<div class="container-fluid">
    <x-session-alerts />

    <h1> {{ $rodeo->name }} </h1>
    <hr>

    <table class="mb-5"> 
        <tr>
            <td class="font-weight-bold pr-3"> Name: </td>
            <td>{{ $rodeo->name }}</td>
        </tr>
    </table>

    <h2> Competitions </h2>
    <hr>
    @foreach( $rodeo->competitions as $competition )
        {{ $competition->name }} <br>
    @endforeach

</div>
@endsection
