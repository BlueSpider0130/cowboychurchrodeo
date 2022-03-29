{{--
    $name 
    $entry
--}}
<tr>
    <td> 
        <input 
            type="radio" 
            id="radio-{{ $name }}-{{ $entry->id }}" 
            name="{{ $name }}" 
            value="{{ $entry->id }}" 
            @if( $checked ) checked @endif
            required 
        /> 
    </td>
    <td style="white-space: nowrap;" for="radio-{{ $name }}-{{ $entry->id }}"> 
        <label for="radio-{{ $name }}-{{ $entry->id }}">
            {{ $entry->contestant->lexical_name_order }} 
        </label>
    </td>
    <td> 
        <label for="radio-{{ $name }}-{{ $entry->id }}">
            @if( $entry->position )
                <span class="badge {{ in_array($entry->position, ['header', 'heeler']) ? "badge-{$entry->position}" : ''}}">
                    {{ $entry->position }}
                </span>
            @else
                <span class="trc-label font-weight-normal"> Position: </span>
                Any
            @endif
        </label>
    </td>
    <td class="text-secondary" style="font-size: .85rem;"> 
        Contestant entry #{{ $entry->id }} 
        <a href="{{ route('L2.entries.show', [$organization, $entry]) }}">
            <i class="far fa-file-alt pl-2" style="font-size: 1.2em"></i>
        </a>
    </td>   
    <td> 
        <label for="radio-{{ $name }}-{{ $entry->id }}">
            @if( !$entry->instance )
                <small class="text-muted"> Day / time not assigned... </small>
            @else
                <div> <x-rodeo-date :date="$entry->instance->starts_at" /> </div>
            @endif
        </label>
    </td>
    <td>
        <i class="text-secondary">
            {{ $entry->requested_teammate }}
        </i>
    </td>
</tr>