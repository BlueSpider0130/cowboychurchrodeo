    <div class="card mb-4 w-50">
        <div class="card-body">

            <p>
                {{ $contestant->last_name }}, {{ $contestant->first_name }} 
                <x-membership-badge :contestant="$contestant" series="{{ $rodeo->series_id }}" style="margin-left: .5rem;" />
                <br>
                {{ $contestant->birthdate ? $contestant->birthdate->toFormattedDateString() : '' }}
            </p>

            <address class="mb-0 pb-0">
                @if($contestant->address_line_1)
                    {{ $contestant->address_line_1 }}<br>
                @endif

                @if($contestant->address_line_2)
                    {{ $contestant->address_line_2 }}<br>
                @endif
                
                @if($contestant->city)
                    {{ $contestant->city }}, 
                @endif
                @if($contestant->state)
                    {{ $contestant->state }} 
                @endif
                @if($contestant->postcode) 
                    {{ $contestant->postcode }}
                @endif                    
                @if($contestant->city || $contestant->state || $contestant->postcode)
                    <br>
                @endif
            </address>

        </div>
    </div>