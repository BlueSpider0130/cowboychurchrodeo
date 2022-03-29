    <div class="card mb-3 w-50">
        <div class="card-body">
            {{ $rodeo->name ? $rodeo->name : 'Rodeo #'.$rodeo->id }}  <br>
            <x-rodeo-date :date="$rodeo->starts_at" /> &ndash; <x-rodeo-date :date="$rodeo->ends_at" />
        </div>
    </div>