@extends('layouts.producer')

@section('content')
<div class="container-fluid py-4">

    <x-session-alerts />

    <h1> {{ $competition->group ? $competition->group->name.' - ' : '' }} {{ $competition->event->name }} </h1>
    <hr>
    <h2 class="d-none"> Rodeo </h2>
    <div class="card mb-5">
        <div class="card-body">

            {{ $rodeo->name }} 
            <hr class="my-1">
            <div>
                {{ $rodeo->starts_at ? $rodeo->starts_at->toFormattedDateString() : 'TBA'}} 
                &ndash; 
                {{ $rodeo->ends_at ? $rodeo->ends_at->toFormattedDateString() : 'TBA' }}
            </div>

            @if( $rodeo->description )
                <p class="mt-2">{{ $rodeo->description }}</p>
            @endif

            @if( $rodeo->entry_fee )
                <table class="mt-2"> 
                    <tr>
                        <td class="pr-2"> Rodeo entry fee: </td>
                        <td> ${{ $rodeo->entry_fee ? number_format( $rodeo->entry_fee, 2) : '0.00' }} </td>
                    </tr>
                </table>
            @endif

        </div>
    </div><!--/card-->


    <div class="card">
        <div class="card-header bg-white"> Entry #{{ $entry->id }} </div>
        <div class="card-body">

            <div class="row">
                <div class="col-12 col-md-10">
                    
                    <p class="font-weight-bold"> 
                        {{ $entry->contestant->lexical_name_order }} 
                    </p>

                    @if( $competition->event->team_roping )
                        <p>
                            @if( $entry->position )
                                <span class="badge {{ in_array($entry->position, ['header', 'heeler']) ? "badge-{$entry->position}" : ''}}">
                                    {{ $entry->position }}
                                </span>
                            @else
                                Position: Any
                            @endif
                        </p>
                    @endif

                    <p>
                        <span class="pr-2"> Entry fee: </span>
                        @if( $entry->no_fee )
                            N/A <br>
                            <small class="text-muted">* Contestant will not be charged entry fee. </small>
                        @else
                            ${{ number_format($entry->competition->fee, 2) }}
                        @endif
                    </p>
                   
                    <p>
                        <span class="pr-2"> Score: </span>
                        @if( $entry->no_score )
                            N/A <br>
                            <small class="text-muted">* Contestant is participating "for fun".</small>
                        @else 
                            Yes 
                        @endif
                    </p>

                    <span> Day / time </span>
                    <hr class="my-2">
                    <ul>
                        @if( !$entry->instance )
                            <small class="text-muted"> Not assigned... </small>                            
                        @else
                            <li> <x-rodeo-date :date="$entry->instance->starts_at" /> </li>
                        @endif
                    </ul>


                    @if( $entry->requested_teammate )
                        <div class="mt-2">
                            Requested teammate: 
                        </div>
                        <div class="border rounded p-3 text-secondary">{{ $entry->requested_teammate }}</div>
                    @endif

                </div><!--/col-->

                <div class="col text-md-right">
                    <a href="{{ route('L2.entries.edit', [$organization, $entry]) }}" class="btn btn-outline-secondary btn-sm"> <i class="fas fa-edit"></i> Edit </a>
                </div>
            </div><!--/row-->

        </div>
    </div>


</div>
@endsection
