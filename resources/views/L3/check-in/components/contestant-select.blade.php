<div>
    <form method="GET" action="{{ route('L3.check-in.summary', [$organization->id, $rodeo->id]) }}">
        @csrf

        <div>
            <div class="mb-3">
                <button class="btn btn-primary" onclick="return submitContestants(event);"> Check in selected contestants </button>
                @push('body')
                    <script type="text/javascript">
                        function submitContestants(e) {
                            // e.preventDefault();


                            // return false;
                        }
                    </script>
                @endpush
            </div>
        </div>

        <div style="height: 500px; overflow-y: auto">
            <table class="table bg-white border">
                <thead>
                    <th></th>
                    <th style="width: 160px">Contestant</th>
                    <th></th>
                    <th>Check in Notes</th>
                    <th></th>
                    <th></th>
                </thead>
                @foreach( $contestantsByDay as $timestamp => $data )
                    @foreach( $data['contestants'] as $contestant )
                        <tr>
                            <td> 
                                <input 
                                    type="checkbox" 
                                    id="contestant-{{ $contestant->id }}" 
                                    name="contestants[]" 
                                    value="{{ $contestant->id }}"  
                                /> 
                            </td>
                            <td style="padding: 0"> 
                                <label class="d-block" style="padding: .75rem" for="contestant-{{ $contestant->id }}"> 
                                    {{ $contestant->lexical_name_order }} 
                                </label>
                            </td>
                            <td style="padding: 0"> 
                                <label class="d-block" style="padding: .75rem" for="contestant-{{ $contestant->id }}"> 
                                    <!-- {{ $contestant->sex }} -->
                                    @if( $contestant->sex )
                                    <img src="/assets/{{$contestant->sex}}.png">
                                    @else
                                    ---
                                    @endif
                                </label>
                            </td>
                            <td style="padding: 0"> 
                                <label class="d-block" style="padding: .75rem" for="contestant-{{ $contestant->id }}"> 
                                    @foreach( $checkInEntries as $entry )
                                        @if( $contestant->id == $entry->contestant_id )
                                            {{$entry->check_in_notes}}
                                        @endif
                                    @endforeach
                                </label>
                            </td>
                            <td>
                                @if( $rodeo->series_id )
                                    <x-membership-badge :contestant="$contestant" :series="$rodeo->series_id" class="pl-3" />
                                @endif 
                            </td>
                            <td> 
                                {{ $data['day']->format('l') }} 
                            </td>
                        </tr>

                    @endforeach
                @endforeach
            </table>
        </div>

    </form>
</div>
