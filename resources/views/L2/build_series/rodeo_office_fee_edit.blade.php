@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> Rodeo: {{ $rodeo->name ? $rodeo->name : "#{$rodeo->id}" }} </h1>
    <div class="row">
        <div class="col-12 col-md-10 col-lg-8">      

            <div class="card mt-3 mb-5">
                <div class="card-body">
                    Series: {{ $series->name }}
                    <hr class="my-2">
                    <div>
                        {{ $series->starts_at ? $series->starts_at->toFormattedDateString() : 'TBA'}} 
                        &ndash; 
                        {{ $series->ends_at ? $series->ends_at->toFormattedDateString() : 'TBA' }}
                    </div>         
                </div>
            </div><!--/card-->

        </div><!--/col-->
    </div><!--/row-->


    <h2> Office fee exceptions </h2>
    <div class="row">
        <div class="col-12 col-md-10 col-lg-8">

            <div class="card">
                <div class="card-body">

                    <h3 class="h-reset mb-4">Select groups / events that the office fee should not apply to.</h3>

                    <form method="post" action="{{ route('L2.build.series.rodeo.office.fee.update', [$organization, $series, $rodeo]) }}">
                        @method('put')
                        @csrf


                        <legend class="legend-reset font-weight-bold"> Groups </legend>
                        <hr class="my-2">
                        @foreach( $groups as $group )
                            @php
                                $checked = in_array($group->id, $notApplicableGroupIds);
                            @endphp
                            <div class="custom-control custom-checkbox mb-2">
                                <input 
                                    type="checkbox" 
                                    name="groups[]"
                                    value="{{ $group->id }}"
                                    {{ $checked ? 'checked' : '' }}
                                    class="custom-control-input" 
                                    id="group-{{ $group->id }}"
                                >
                                <label class="custom-control-label" for="group-{{ $group->id }}"> 
                                    {{ $group->name }}
                                </label>                                    
                            </div>
                        @endforeach
                        <br>

                        <legend class="legend-reset font-weight-bold"> Events </legend>
                        <hr class="my-2">
                        @foreach( $events as $event )
                            @php
                                $checked = in_array($event->id, $notApplicableEventIds);
                            @endphp
                            <div class="custom-control custom-checkbox mb-2">
                                <input 
                                    type="checkbox" 
                                    name="events[]"
                                    value="{{ $event->id }}"
                                    {{ $checked ? 'checked' : '' }}
                                    class="custom-control-input" 
                                    id="event-{{ $event->id }}"
                                >
                                <label class="custom-control-label" for="event-{{ $event->id }}"> 
                                    {{ $event->name }}
                                </label>                                    
                            </div>
                        @endforeach

                        <hr class="mt-1">
                        <x-form.buttons submit-name="Update" :cancel-url="route('L2.build.series.rodeos.show', [$organization, $series, $rodeo])" />


                    </form>
                </div>
            </div><!--/card-->

        </div>
    </div><!--/row--> 

</div>
@endsection
