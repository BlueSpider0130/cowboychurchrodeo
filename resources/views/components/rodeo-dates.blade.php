@if( $model->starts_at )
    <span {{ $attributes }}>
        <x-rodeo-date :date="$model->starts_at" /> 
        @if( $model->ends_at ) 
            &ndash; <x-rodeo-date :date="$model->ends_at" /> 
        @endif
    </span>
@endif