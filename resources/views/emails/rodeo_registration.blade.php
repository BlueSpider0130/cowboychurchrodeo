<div style="font-family: Arial">

    <h1> Rodeo registration </h1>
    <hr>
    <p>
        {{ $rodeo->name }}
        <x-rodeo-dates :model="$rodeo" />
    </p>

    <table>
        <tbody>
            <tr>
                <td colspan="3" style="padding: .75rem 1rem .75rem 0;">{{ $contestant->name }} </td>
            </tr>
            @foreach($competitionEntries as $entry)
                <tr>
                    <td style="padding: .75rem 1rem .75rem 0;">{{ $entry->competition->group->name }}</td>
                    <td style="padding: .75rem 1rem .75rem 0;">{{ $entry->competition->event->name }}</td>
                    @if( $entry->instance  &&  $entry->instance->starts_at )
                        <td style="padding: .75rem 1rem .75rem 0;"><x-rodeo-date :date="$entry->instance->starts_at" /></td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

</div>