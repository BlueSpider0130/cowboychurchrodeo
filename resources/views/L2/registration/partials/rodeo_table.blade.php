       
        @if( $rodeos->count() < 1 )
            <i> <small class="text-muted"> There are no active rodeos... </small> </i> 
        @else
        <table class="table table-responsive-cards bg-white border">
            <thead>
                <tr>
                    <th> Rodeo </th>
                    <th> Dates </th>
                    <th class="text-md-center"> Registration open </th>
                    <th> Registration closes </th>
                    <th> </th>
                </tr>
            </thead>

            <tbody>
                @foreach( $rodeos as $rodeo )
                    <tr>
                        <td> {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} </td>
                        <td> 
                            <x-rodeo-dates :model="$rodeo" /> 
                        </td>
                        <td class="text-md-center"> 
                            @if( $rodeo->isRegistrationOpen() )
                                <span class="text-success" title="open"> <i class="fas fa-check"></i> </span>
                            @else
                                <span class="text-danger" title="closed"> <i class="fas fa-times"></i> </span>
                            @endif
                            <span class="d-inline-block ml-2 d-md-none"> registration {{ $rodeo->isRegistrationOpen() ? 'open' : 'closed' }} </span>
                        </td>
                        <td>
                            @if( $rodeo->closes_at ) 
                                <x-rodeo-date-time :date="$rodeo->closes_at" /> 
                            @else 
                                <x-rodeo-date-time :date="$rodeo->starts_at->copy()->subSeconds(1)" />
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('L2.registration.contestants.index', [$organization->id, $rodeo->id]) }}" class="btn btn-outline-primary btn-sm">
                                Registration 
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif