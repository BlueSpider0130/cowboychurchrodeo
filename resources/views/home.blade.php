@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h1 style="font-size: 1.1rem"> Find your organization </h1>
            <hr class="mb-3">

            @if( 0 == $organizations->count() )
                <p class="text-secondary">There are no available organizations for you to access...</p>
            @endif

            @foreach( $organizations as $organization )
                
                <div class="my-3 border rounded bg-white shadow-sm">
                    <a href="{{ route('toolbox', $organization->id) }}" class="d-block text-dark p-3" style="text-decoration: none;">
                        {{ $organization->name }}
                    </a>
                </div>

            @endforeach
            
            {{ $organizations->links() }}

        </div>
    </div>
</div>
@endsection
