<caption>
    <h2 calass="h-reset font-weight-bold">{{ $rodeo->name }}</h2>
    {{ rodeo_date_format($day) }}
</caption>
<table class="table bg-white border mt-2 mb-3">
    <tbody>
        @foreach( $competitionData as $competitionName => $entries )
            <tr class="bg-secondary">
                <th colspan="3"><strong>{{ $competitionName }}</strong></th>
            </tr>
            <tr class="bg-light font-weight-bold">
                <td></td>
                <td>Contestant</td>
                <td>City</td>
            </tr>
            @if( $entries->count() > 0)
                @foreach( $entries as $entry )
                    <tr>
                        <td>{{ $entry->draw }}</td>
                        <td>{{ $entry->contestant->name }}</td>
                        <td>@if($entry->contestant->city){{ $entry->contestant->city }}, @endif {{ $entry->contestant->state }}</td>
                        <td> competition {{ $entry->competition_id }} instance {{ $entry->instance_id }}</td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="3"> <i>No entries...</i> </td></tr>
            @endif
            <tr><td colspan="3" class="py-4"></td></tr>
        @endforeach
    </tbody>