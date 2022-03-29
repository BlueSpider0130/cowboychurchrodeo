@extends('layouts.producer')

@section('content')
<div class="container-fluid py-4">

    <x-session-alerts />

    <h1> Rodeo entries </h1>
    @if( $rodeos->count() < 1 )
        <hr>
        <p style="font-style: italic;"> There are no active rodeos... </p>
    @endif

    @if( $rodeos->count() > 0 )

        <table class="table bg-white border table-responsive-cards">
            <thead>
                <tr>
                    <th> Rodeo </th>
                    <th> Start </th>
                    <th> End </th>
                    <th class="text-md-center"> Contestants </th>
                    <th> &nbsp; </th>
                </tr>
            </thead>
            <tbody>
                @foreach( $rodeos as $rodeo )
                    <tr>
                        <td class="font-weight-bold font-weight-md-normal"> 
                            {{ $rodeo->name }} 
                            <hr class="my-2 d-md-none">
                        </td>
                        <td>
                            <span class="trc-label"> Start: </span>
                            <x-rodeo-date :date="$rodeo->starts_at">
                                <x-slot name="default"><i>TBA</i></x-slot>
                            </x-rodeo-date>                            
                        </td>
                        <td>
                            <span class="trc-label"> End: </span>
                            <x-rodeo-date :date="$rodeo->ends_at">
                                <x-slot name="default"><i>TBA</i></x-slot>
                            </x-rodeo-date>                            
                        </td>
                        <td class="text-md-center">
                            <span class="trc-label"> Contestants: </span>
                            {{ $rodeo->entries->count() }}
                        </td>
                        <td class="text-md-center">
                            <a href="{{ route('L2.entries.rodeo', [$organization, $rodeo]) }}" class="btn btn-primary btn-sm"> Select </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endif

    @if( $previous->count() > 0 )

        <h2 class="mt-5"> Previous rodeos </h2>
        <table class="table bg-white border">
            <thead>
                <tr>
                    <th> Rodeo </th>
                    <th> Start </th>
                    <th> End </th>
                    <th> &nbsp; </th>
                </tr>
            </thead>
            <tbody>
                @foreach( $previous as $rodeo )
                    <tr>
                        <td class="font-weight-bold font-weight-md-normal"> 
                            {{ $rodeo->name }} 
                            <hr class="my-2 d-md-none">
                        </td>
                        <td>
                            <span class="trc-label"> Start: </span>
                            <x-rodeo-date :date="$rodeo->starts_at">
                                <x-slot name="default"><i>TBA</i></x-slot>
                            </x-rodeo-date>                            
                        </td>
                        <td>
                            <span class="trc-label"> End: </span>
                            <x-rodeo-date :date="$rodeo->ends_at">
                                <x-slot name="default"><i>TBA</i></x-slot>
                            </x-rodeo-date>                            
                        </td>
                        <td class="text-md-center">
                            <a href="{{ route('L2.entries.rodeo', [$organization, $rodeo]) }}" class="btn btn-outline-secondary btn-sm"> Select </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endif

</div>
@endsection
