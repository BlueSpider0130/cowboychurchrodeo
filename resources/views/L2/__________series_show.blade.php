@extends('layouts.producer')

@section('content')
<div class="container-fluid">
    <x-session-alerts />

    <h1> {{ $series->name }} </h1>
    <hr>

    <table class="mb-5"> 
        <tr>
            <td class="font-weight-bold pr-3"> Name: </td>
            <td>{{ $series->name }}</td>
        </tr>
    </table>

    <h2> Rodeos </h2>
    <hr>
    @foreach( $series->rodeos as $rodeo )
        {{ $rodeo->name }} <br>
    @endforeach

</div>
@endsection
