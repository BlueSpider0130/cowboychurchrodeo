@php
    $class = 'form-control';
    $class .= $errors->has($name) ? ' is-invalid' : '';
@endphp   

<select name="{{ $name }}" {{ $attributes->merge(['class' => $class]) }}>
    
    <option value="" @if(null === $selected) selected @endif> {{ $placeholder }} </option>

    @foreach($options as $value => $inner)
        <option value="{{ $value }}" @if(null !== $selected && $value == $selected) selected @endif>{{ $inner }}</option>
    @endforeach
</select>

@error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror   
