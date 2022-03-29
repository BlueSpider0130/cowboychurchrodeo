        <table class="table bg-white border mb-5 table-responsive-cards">
            <thead>
                <tr>
                    <th> Series </th>
                    <th> Dates </th>
                    <th> Members </th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>
                @foreach( $seriesCollection as $series )
                    <tr>
                        <td> 
                            {{ $series->name ? $series->name : "#{$series->id}" }} 
                        </td>

                        <td> 
                            @if( $series->starts_at )
                                <x-rodeo-date :date="$series->starts_at" /> 

                                @if( $series->ends_at )
                                    &ndash;
                                    <x-rodeo-date :date="$series->ends_at" /> 
                                @endif
                            @endif
                        </td>

                        <td>
                            {{ $series->memberships->count() ? $series->memberships->count() : '' }}
                        </td>

                        <td class="text-md-center">
                            <a href="{{ route('L2.memberships.index', [$organization->id, $series->id]) }}" class="btn btn-outline-primary btn-sm"> 
                                View memberships 
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>