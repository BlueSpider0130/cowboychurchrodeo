<div>
@php
    $class = 'form-control';
    $class .= $errors->has($name) ? ' is-invalid' : '';
@endphp    
<input 
    type="{{ $type }}" 
    name="{{ $name }}" 
    value="{{ $value }}" 
    {{ $attributes->merge(['class' => $class]) }}
>
@if($withError)
    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror   
@endif
</div>