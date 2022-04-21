@extends('layouts.app')

@section('content')
<div class="container">
<h1>{{ $competition->name }}</h1>
    <hr>
    {{-- $competition->starts_at->toFormattedDateString() }} &ndash; {{ $competition->ends_at->toFormattedDateString() --}}
    <!-- {{json_encode($checkedInIds)}} -->
    @php
        $place = 1;
        $result_type = $competition->event->result_type;
        $previous = '0';
        $counter = 0;
        $GLOBALS['type'] = $competition -> event -> result_type;
    @endphp  
    <table class="table bg-white mt-4">
        <tr>
            <th style="vertical-align: inherit;">Name</th>
            <th style="vertical-align: inherit;">Paid</th>
            <th style="vertical-align: inherit;">Result ({{ucfirst($competition->event->result_type)}}) </th>
            <th style="vertical-align: inherit;">Place</th>
        </tr>
        @foreach( $results->sortBy(function ($results, $result_time) {
                                    if (!is_numeric($results['score'])) {
                                        return PHP_INT_MAX;
                                    }
                                    $flag = ($GLOBALS['type'] == 'time') ? $results['score'] : -$results['score'];
                                    return $flag;
                                }) as $entry )
            <tr>
                <td>{{ $entry->contestant->lexical_name_order }}</td>
                <td>
                @foreach($checkedInIds as $paidContestantIds)
                    @if($entry->contestant->id == $paidContestantIds)
                        <i class="fas fa-check"></i>
                    @endif
                @endforeach
                </td>
                <td>{{ $entry->score }}</td>
                <td>
                    @php
                        $counter++;
                        if($previous == $entry->score){
                            if($entry->score != null){
                                if($previous == 0){
                                    echo $place;
                                }elseif(!$competition->event->team_roping){
                                    echo $place;
                                }
                                $previous = $entry->score;
                            }
                        }elseif($entry->score != null && is_numeric($entry->score)){
                                if(!$competition->event->team_roping){
                                    $place = $counter;
                                    echo $place;
                                    $previous = $entry->score;
                                }else{
                                    echo $place;
                                    $place++;
                                    $previous = $entry->score;
                                }
                            }
                    @endphp
                </td>
            </tr>
        @endforeach
        @foreach($pending->sortBy('contestant.last_name') as $entry)
            <tr>
                <td>{{ $entry->contestant->lexical_name_order }}</td>
                <td>
                    @foreach($checkedInIds as $paidContestantIds)
                        @if($entry->contestant->id == $paidContestantIds)
                            <i class="fas fa-check"></i>
                        @endif
                    @endforeach
                </td>
                <td>
                    @if(in_array($entry->contestant_id, $checkedInIds))
                        <i class="secondary">pending</i>
                    @else
                        <span class="text-secondary"> ---- </span>
                    @endif
                </td>
                <td></td>
                @php
                    $place++
                @endphp
            </tr>
        @endforeach
    </table>
</div>
@endsection
