@extends('layouts.app')

@section('content')
<div class="container">

    <div class="mb-4">
        <a href="{{ route('L4.results.home', [$organization->id]) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-chevron-left"></i>
            Rodeos
        </a>        
    </div> 

    <h1> Rodeo results </h1>
    <hr class="mb-4">

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
                    <td> 
                        <span class="font-weight-bold font-weight-md-normal"> {{ $competition->group->name }} </span>
                        <hr class="my-2 d-md-none">
                    </td>
                    <td> {{ $competition->event->name }} </td>
                    <td class="text-md-center"> 
                        <a 
                            href="{{ route('L4.results.show', [$organization->id, $rodeo->id, $competition->id]) }}" 
                            class="btn btn-outline-primary btn-sm"
                        > 
                            Results 
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
