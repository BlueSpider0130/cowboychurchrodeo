@extends('layouts.welcome')

@section('main')
    <div class="container mb-5">

        <div class="row justify-content-center mt-3 mt-md-5 mb-5">
            <div class="col-12 col-md-10" >

                <p class="mb-3 text-secondary"> To register for rodeo events, view results, etc. first find your church or organization.</p>

                <x-list-builder.search name="search" action="{{ route('organizations.index') }}" />

            </div>
        </div>


        <h2> Organizations </h2>
        <hr>        
        @if( $search )
            <div class="row text-secondary"> 
                <div class="col"> 
                    {{ $organizations->total() }} result{{ 1 == $organizations->total() ? '' : 's' }} for: "{{ $search }}"
                </div>
                <div class="col text-right">
                    <a href="{{ route('organizations.index') }}"> see all organizations </a>
                </div>
            </div>
        @endif

        @if( $organizations->count() > 0 )

            @foreach( $organizations as $organization )
                
                <div class="my-3 p-3 border rounded bg-white shadow-sm">
                    <a href="{{ route('organizations.show', $organization->id) }}" class="d-block text-dark" style="text-decoration: none;">
                        {{ $organization->name }}
                    </a>
                </div>

            @endforeach
            {{ $organizations->links() }}

        @elseif( !$search )
            <p class="text-muted" style="font-style: italic;"> There are no organizations. </p>
        @endif
        
    </div>
@endsection
