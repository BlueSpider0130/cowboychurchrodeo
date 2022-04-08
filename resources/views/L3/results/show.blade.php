@extends('layouts.producer')

@section('content')
<div class="mt-n4 mx-n4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"> <a href="{{ route('L3.results.home', [$organization->id]) }}"> Rodeos </a> </li>
            <li class="breadcrumb-item"> <a href="{{ route('L3.results.index', [$organization->id, $rodeo->id]) }}"> Events </a> </li>
            <li class="breadcrumb-item active" aria-current="page"> {{ $competition->group->name }} &ndash; {{ $competition->event->name }} </li>
        </ol>
    </nav>
</div>
<div class="container-fluid">
<!-- {{$memberships}} -->
    <x-session-alerts />

    <h1> Record results </h1>
    <hr class="mb-2">

    <div class="card mb-5">
        <div class="card-body">
            {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} <br>
            <x-rodeo-dates :model="$rodeo" />
        </div>
    </div>

    <h2> {{ $competition->group->name }} &ndash; {{ $competition->event->name }} </h2>
    <hr>
    @php
        $place = 1;
        $counter = 0;
        $GLOBALS['type'] = $competition -> event -> result_type;
        $previous = '0';
    @endphp  
    <table class="table table-responsive-cards bg-white border">
        <thead>
            <tr>
                <th> Entry </th>
                <th> Contestant </th>
                <th> Member </th>
                <th> Paid </th>
                <th> Result(
                        {{ucfirst($competition->event->result_type)}}
                    ) 
                </th>
                <th>Place</th>
            </tr>
        </thead>
        <tbody>
            @foreach( $entries->sortBy(function ($entries, $result_time) {
                                    if (!is_numeric($entries['score'])) {
                                        if($entries['score'] == null){
                                            return PHP_INT_MAX;
                                        }else return PHP_INT_MAX-1;
                                    }
                                    $flag = ($GLOBALS['type'] == 'time') ? $entries['score'] : -$entries['score'];
                                    return $flag;
                                }) as $key => $entry )

                <tr>
                    <!-- {{$entry}} -->
                    <td> 
                        <span class="d-md-none"> Entry: </span>
                        #{{ $entry->id }} 
                    </td>
                    <td> 
                        {{ $entry->contestant->lexical_name_order }} 
                    </td>
                    <td>
                        @foreach($memberships[0] as $is_member)
                            @if($entry -> contestant_id == $is_member -> contestant_id)
                                <!-- <i class="fas fa-check" style="color: blue"></i> -->
                                <span class="member-badge">MEMBER</span>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($checkInIds as $paidContestantIds)
                            @if($entry->contestant->id == $paidContestantIds)
                                <i class="fas fa-check"></i>
                            @endif
                        @endforeach
                    </td>
                    <td> 
                        <span class="d-md-none"> Score: </span> 
                        @if( null !== $entry->score )
                            {{ $entry->score }} 
                        @else
                            <small class="text-muted"> <i>No score reported</i> </small>
                        @endif
                    </td>
                    <td>
                        @php
                            $counter++;
                            if($previous == $entry->score || $previous == '0'){
                                if($entry->score != null){
                                    echo $place;
                                    $previous = $entry->score;
                                }
                            }elseif($entry->score != null && is_numeric($entry->score)){
                                $place = $counter;
                                echo $place;
                                $previous = $entry->score;
                            }
                        @endphp
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="my-2">
        <a href="{{ route('L3.results.edit', [$organization->id, $rodeo->id, $competition->id]) }}" class="btn btn-primary"> Enter scores </a>
        <a href="{{ route('L3.results.index', [$organization->id, $rodeo->id]) }}" class="btn btn-outline-secondary"> Cancel </a>
    </div>


</div>
@endsection