@php
    $activeRodeoHeaderTab = "entries";
@endphp

@extends('layouts.producer')

@section('content')
    <table class="table bg-white border">
        <thead> 
            <th> Last name </th>
            <th> First name </th>
            <th> Dates </th>
            <th> Groups </th>
            <th> Events </th>
        </thead>

        <tbody>
            @foreach( $data as $row )
                <tr>
                    <td> {{ $row['contestant']->last_name }} </td>
                    <td> {{ $row['contestant']->first_name }} </td>
                    <td> 
                        @php
                            asort($row['dates']);
                        @endphp
                        @for ($i=0; $i < count($row['dates']) ; $i++)
                            <x-rodeo-date :date="\Carbon\Carbon::createFromTimeStamp( $row['dates'][$i] )" />
                            <br>   
                        @endfor
                    </td>
                    <td>
                        @php
                            natsort($row['groups']);
                        @endphp
                        {{ implode(', ', $row['groups']) }}
                    </td>
                    <td>
                        @php
                            natsort($row['events']);
                        @endphp
                        {{ implode(', ', $row['events']) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
