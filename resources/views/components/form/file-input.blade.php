@php
    $class = $errors->has($name) ? ' is-invalid' : '';
@endphp    
<input 
    type="file" 
    name="{{ $name }}" 
    {{ $attributes->merge(['class' => $class]) }}
>
@error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror   
