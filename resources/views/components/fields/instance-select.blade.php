<div {{ $attributes }}>

    <legend class="legend-reset font-weight-bold">{{ $label }}</legend>

    <hr class="my-1">

    <div class="@error($name) is-invalid @enderror">
        @foreach( $instances as $instance)
            @if( $instance->starts_at )

                @php
                    $checked = $value == $instance->id;
                    $checked = old()  &&  $instance->id == old($name)  ?  true  :  $checked;
                @endphp

                <div class="form-check">
                    <input 
                        class="form-check-input" 
                        type="radio" 
                        name="{{ $name }}"
                        value="{{ $instance->id }}" 
                        id="{{ $name }}-radio-{{ $instance->id }}"
                        @if($checked) checked @endif
                    >
                    <label class="form-check-label" for="{{ $name }}-radio-{{ $instance->id }}">
                        <x-rodeo-date :date="$instance->starts_at" />
                    </label>
                </div>

            @endif
        @endforeach
    </div>

    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>