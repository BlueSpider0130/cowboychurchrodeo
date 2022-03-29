@extends('layouts.producer')

@section('content')
    @include('L2.reports._rodeo_report_header', ['active' => 'entries'])

    <table class="table bg-white border">
        <thead>
            <th> Email </th>
            <th> User </th>
            <th> Contestants </th>  
        </thead>

        <tbody>
            @foreach( $users as $user )
                <tr>
                    <td> {{ $user->email }} </td>
                    <td> {{ $user->name }} </td>
                    <td>
                        @php
                            $names = [];
                            
                            foreach( $user->contestants->sortBy('first_name') as $contestant )
                            {
                                $names[] = $contestant->name;
                            }
                        @endphp
                        @if( count($names) > 0 )
                            {!! implode(', <br>', $names) !!}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
