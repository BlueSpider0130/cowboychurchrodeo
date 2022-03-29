@extends('layouts.producer')

@section('content')
<div class="container-fluid py-4">

    <x-session-alerts />

    <h1> {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} entries </h1>
    <hr>

    <div class="card mb-5">
        <div class="card-body">
            <table>
                <tr> 
                    <td class="pr-2"> Start date: </td> 
                    <td>
                        <x-rodeo-date :date="$rodeo->starts_at">
                            <x-slot name="default"><i>TBA</i></x-slot>
                        </x-rodeo-date>     
                    </td> 
                </tr>
                <tr> 
                    <td class="pr-2"> End date: </td> 
                    <td>
                        <x-rodeo-date :date="$rodeo->ends_at">
                            <x-slot name="default"><i>TBA</i></x-slot>
                        </x-rodeo-date>     
                    </td> 
                </tr>
                @if( $rodeo->opens_at )
                    <tr> 
                        <td class="pr-2"> Registration opens: </td> 
                        <td>
                            <x-rodeo-date-time :date="$rodeo->opens_at" />
                        </td> 
                    </tr>
                @endif
                @if( $rodeo->closes_at )
                    <tr> 
                        <td class="pr-2"> Restration closes: </td> 
                        <td>
                            <x-rodeo-date-time :date="$rodeo->opens_at" />
                        </td> 
                    </tr>
                @endif
            </table>

            <div class="mt-3">
                @if( $rodeo->isRegistrationOpen() )
                    <span class="text-success">Registration open</span>
                @else
                    <span class="text-danger">Registration closed</span>
                @endif
            </div>
        </div>
    </div>


    <h2> Contestants </h2>
    <div class="card mb-5">
        <div class="card-body">

            <div class="row">
                <div class="col-10">
                    {{ $rodeo->contestants->count() }} contestants 
                </div>
                <div class="col-2 pr-4 text-right @if($rodeo->contestants->count() < 1) d-none @endif">
                    <a 
                        href="#" 
                        role="button" 
                        id="contestant-max-button"
                        class="text-dark" 
                        style="font-size: 1.05rem;" 
                        onclick="
                            document.getElementById('contestant-table').style.display='block'; 
                            document.getElementById('contestant-min-button').style.display='inline';
                            this.style.display='none'; 
                            return false;
                        "
                    >
                        <i class="far fa-plus-square"></i>
                    </a> 

                    <a 
                        href="#" 
                        role="button" 
                        id="contestant-min-button"
                        class="text-dark" 
                        style="font-size: 1.05rem; display: none" 
                        onclick="
                           document.getElementById('contestant-table').style.display='none'; 
                           getElementById('contestant-max-button').style.display='inline'; 
                           this.style.display='none'; 
                           return false;
                        "
                    >
                        <i class="far fa-minus-square"></i>
                    </a>             

                </div>
            </div>               


            <div id="contestant-table" style="display: none;">      
                <table class="table table-striped bg-light border ta ble-responsive-cards">
                    <thead> 
                        <tr>
                            <th> Last name </th>
                            <th> First name </th>
                            <th class="text-md-center"> Contestant details </th>
                            <th> &nbsp; </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach( $rodeo->contestants as $contestant )
                            <tr>
                                <td>{{ $contestant->last_name }}</td>
                                <td>{{ $contestant->first_name }}</td>
                                <td class="text-md-center">
                                    <a href="{{ route('L2.contestants.show', [$organization, $contestant]) }}">
                                        <i class="far fa-address-card fa-lg"></i>
                                    </a>
                                </td>
                                <td class="text-md-center">
                                    <a href="{{ route('L2.registration.show', [$organization, $rodeo, $contestant]) }}" class="btn btn-outline-primary btn-sm"> View registration form </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!--/#contestant-table-->

        </div>
    </div>







    <h2> Events </h2>
    <table class="table bg-white border table-responsive-cards">
        <thead>
            <tr>
                <th> Group </th>
                <th> Event </th>
                <th> &nbsp; </th>
                <th class="text-md-center"> Entries </th>
                <th> &nbsp; </th>
            </tr>
        </thead>
        <tbody>
            @php

            @endphp
            @foreach( $competitions as $competition )
                <tr>
                    <td>
                        @if( $competition->group->name )
                            <span class="font-weight-xs-bold"> {{ $competition->group->name }} </span>
                        @else
                            ( <i>no group</i> )
                        @endif
                        <hr class="d-md-none my-1">
                    </td>
                    <td>
                        {{ $competition->event->name }}
                    </td>
                    <td>
                        @if( $competition->event->team_roping )
                            <i class="text-secondary"> Team roping </i>
                        @endif
                    </td>
                    <td class="text-md-center">
                        <span class="d-md-none">Entries: </span>
                        @if($entryCount = $competition->entries->count() ) 
                            {{ $entryCount }} 
                        @else 
                            <span style="font-style: italic;" class="text-muted">none</span>
                        @endif
                    </td>
                    <td class="text-md-center">
                        <a href="{{ route('L2.entries.index', [$organization, $competition]) }}" class="btn btn-outline-primary btn-sm"> 
                            Entries
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
