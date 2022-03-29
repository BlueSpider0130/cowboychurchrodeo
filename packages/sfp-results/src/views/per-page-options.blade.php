<div class="row">

    <div class="col-12 col-md">
        <i> Showing {{$results->count() }} of {{ $results->total() }} total results </i> 
    </div>

    <div class="col-12 col-md text-md-right">
        @include('sfp-results::per-page-links')
    </div>

</div>