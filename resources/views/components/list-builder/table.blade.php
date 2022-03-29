<table {{ $attributes->merge(['class' => "table"]) }}>
    @if( isset($thead) )
        {{ $thead }}
    @else
        <thead>
            <tr>
                @foreach( $columns as $attribute )
                    <th> {{ ucfirst(strtolower(str_replace('_', ' ', $attribute))) }}asdsf </th>
                @endforeach
            </tr>
        </thead>
    @endif
    <tbody>
        @foreach( $records as $record )
            <tr>
                @foreach( $columns as $attribute )
                    <td>{{ $record->$attribute }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
