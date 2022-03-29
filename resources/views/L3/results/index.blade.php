@extends('layouts.producer')

@section('content')
<div class="mt-n4 mx-n4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"> <a href="{{ route('L3.results.home', [$organization->id]) }}"> Rodeos </a> </li>
            <li class="breadcrumb-item active" aria-current="page"> {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} </li>
        </ol>
    </nav>
</div>
<div class="container-fluid">

    <h1> Record results </h1>
    <hr class="mb-2">

    <div class="card mb-5">
        <div class="card-body">
            {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} <br>
            <x-rodeo-dates :model="$rodeo" />
        </div>
    </div>


    <table class="table table-responsive-cards bg-white border">
        <thead>
            <tr>
                <th> Group </th>
                <th> Event </th>
                <th> </th>
            </tr>
        </thead>
        <tbody>
            @foreach( $competitions as $competition )
                <tr>
                    <td> {{ $competition->group->name }} </td>
                    <td> {{ $competition->event->name }} </td>
                    <td> 
                        <a 
                            href="{{ route('L3.results.show', [$organization->id, $rodeo->id, $competition->id]) }}" 
                            class="btn btn-outline-primary btn-sm"
                        > 
                            Results 
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="my-2">
        <a href="{{ route('L3.results.home', [$organization->id]) }}" class="btn btn-outline-secondary btn-sm"> 
            <i class="fas fa-chevron-left"></i>
            Back to rodeos
        </a>
    </div>



</div>
@endsection