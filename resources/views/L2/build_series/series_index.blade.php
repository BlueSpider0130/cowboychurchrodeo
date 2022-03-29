@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> Build series </h1>
    <hr>
    <a href="{{ route('L2.build.series.create', $organization) }}" class="btn btn-primary"> 
        <i class="fas fa-hammer pr-1"></i> Build new series 
    </a>

    <h2 class="mt-5"> Existing series </h2>
    @if( $organization->series->count() < 1 )
        <hr>
        <p>
            <i>You have not built any series yet...</i>
        </p>
    @else
        <table class="table bg-white border rounded table-responsive-cards">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $organization->series as $series )
                    <tr>
                        <td>
                            <span class="font-weight-bold font-weight-md-normal"> {{ $series->name }} </span>
                            <hr class="d-md-none my-2">
                        </td>
                        <td>
                            @if( $series->starts_at )
                                {{ $series->starts_at->toFormattedDateString() }} &ndash; {{ $series->ends_at ? $series->ends_at->toFormattedDateString() : 'TBA' }}
                            @else
                                TBA
                            @endif                     
                        </td>
                        <td class="text-md-center">    
                            <div class="mt-1 mt-md-0">                         
                                <a 
                                    href="{{ route('L2.build.series.show', [$organization, $series]) }}" 
                                    title="Edit" 
                                    class="btn btn-outline-primary btn-sm"
                                >Details</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
@endsection
