@extends('layouts.welcome')

@section('main')
<div class="pb-5">

    <div class="jumbotron" style="background-color: #edebe8">
        <div class="container">
            <h1 class="display-3">{{ config('app.name', 'Rodeo App') }}</h1>
            <p>
                Register for rodeo events, view results, and more. To get started find your church or organization for more info.
            </p>
            <p>
                <a class="btn btn-primary btn-lg" href="{{ route('organizations.index') }}" role="button">Organizations &raquo;</a>
            </p>
        </div>
    </div>

    <div class="container mb-4">
        @if( $totalOrganizations < 1 )
            <p> There are no organizations registered. </p>
        @elseif( $totalOrganizations > 3 )
            <p class="text-muted" style="margin-top: -1.5rem; font-size: .8rem">{{ $totalOrganizations }} total organizations.</p>
        @endif
    </div>

    @if( $organizations->count() > 0 )
        <div class="container">
            <div class="row">
                @foreach( $organizations as $organization ) 
                    <div class="col-md-4 mb-3">
                        <h2>{{ $organization->name }}</h2>
                        <p>{{ $organization->description }}</p>
                        <p><a class="btn btn-secondary" href="{{ route('organizations.show', $organization->id) }}" role="button">View details &raquo;</a></p>
                    </div>
                @endforeach
            </div><!--/row-->

            <div class="row">
                <div class="col-12 my-5">
                    <a href="{{ route('organizations.index') }}">View all organizations</a>
                </div>                
            </div><!--/row-->

        </div> <!-- /container -->
    @endif

</div>
@endsection

@section('footer')
    <footer class="container-fluid bg-white border-top" style="position: fixed; bottom: 0;">
        <p class="text-right my-2">&copy; {{ config('app.name', 'Rodeo App') }} {{ Carbon\Carbon::now()->format('Y') }}</p>
    </footer>
@endsection
