<?php
    if( ! isset($columns)  &&  $results->count() > 0 )
    {
        $columns = [];

        foreach( $results->first()->getFillable() as $attribute )
        {
            $columns[$attribute] = ucfirst( str_replace('_', ' ', $attribute) );
        }
    }

    $request  = $builder->getRequest();
    $sortable = $builder->getSortable();
?>
<div class="row mb-1">

    <div class="col">
        <h1>{{ $title }}</h1>
    </div>

    <div class="col text-right">
        <a href="{{ route($createRoute, $request->input()) }}" class="btn btn-primary btn-sm"> Create new </a>
    </div>

</div>

<hr class="mt-1">

<div class="row">
    <div class="col col-md-9 mx-auto mb-5">
        @include('sfp-results::search')
    </div>
</div>


@if( isset($filters) )
    <div class="mb-5">
        <p class="mt-0 mb-2" style="font-weight: bold; text-decoration: underline;"> Filters </p>
        <p class="m-0">
            <form method="get" action="{{ route( Route::currentRouteName() ) }}">
                @foreach( $request->getParametersForQuerystring() as $name => $value )
                    @if( $request->getFiltersParameterName() != $name  &&  ! is_array($value) )
                        <input type="hidden" name="{{ $name }}" value="{{ $value }}"> 
                    @endif
                @endforeach

                @foreach( $filters as $filter )
                    @php
                        $checked = $request->getFilters() && array_key_exists( $filter['attribute'], $request->getFilters() )  &&  $filter['value'] == $request->getFilters()[$filter['attribute']] 
                            ? true 
                            : false;
                    @endphp
                    <label class="mr-2"> 
                        <input type="checkbox" name="filters[{{ $filter['attribute'] }}]" value="{{ $filter['value'] }}" @if( $checked ) checked @endif> 
                        {{ $filter['name'] }} 
                    </label>
                @endforeach

                <button class="btn btn-outline-secondary btn-sm"> Apply </button>
            </form>
        </p>
    </div>
@endif


@if( $results->count() < 1 )
    <hr>
    <p> <i> No results... </i> </p>
@else    
    
    <div class="mb-2">
        @include('sfp-results::per-page-options')
    </div>

    <table class="table table-striped">
        <thead style="font-weight: bold">
            <tr>
                @foreach( $columns as $attribute => $columnName )
                    <td> 
                        @if( isset($sortable)  &&  is_array($sortable)  &&  in_array($attribute, $sortable) )
                            @include('sfp-results::sortable-link', ['attribute' => $attribute, 'columnName' => $columnName])
                        @else
                            {{ $columnName }}
                        @endif
                    </td>
                @endforeach
                <td> </td>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $record)
                <tr>
                    @foreach( $columns as $attribute => $columnName )
                        <td> {{ $record->$attribute }} </td>
                    @endforeach

                    <td class="text-right"> 
                        <a 
                            href="{{ route( $showRoute, array_merge([$record->id], $request->input()) ) }}" 
                            class="btn btn-outline-secondary btn-sm"
                        > 
                            Show 
                        </a> 
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-1">
        {{ $results->links() }}
    </div>

@endif
</div>
