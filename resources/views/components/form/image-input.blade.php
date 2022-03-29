@php
    $class = $errors->has($name) ? ' is-invalid' : '';
@endphp    

<div style="position: relative;">
    <label for="x-image-input-{{ $key }}">
        <img 
            id="x-image-input-preview-{{ $key }}" 
            src="{{ $value }}"
            width="100%" 
            style="{{ null === $value ? 'display: none;' : '' }} margin-bottom: .5rem"
            class="{{ $imageClass }}"        
        >         
        <span id="x-image-button-{{ $key }}" class="btn btn-outline-secondary"> {{ $value ? 'Change' : 'Upload' }} image </span>
    </label>

    <input 
        id="x-image-input-{{ $key }}"
        type="file" 
        name="{{ $name }}" 
        accept="image/*" 
        {{ $attributes->merge(['class' => $class]) }}
        onchange="xImageInputPreview(this, '{{ $key }}');" 
        style="visibility: hidden; height: 0; position: absolute; top: 0" 
    >
    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror   
</div>