@php
    $class = 'form-control';
    $class .= $errors->has($name) ? ' is-invalid' : '';
@endphp    
<textarea name="{{ $name }}" {{ $attributes->merge(['class' => $class]) }}>{{ $value }}</textarea>
@error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror   
