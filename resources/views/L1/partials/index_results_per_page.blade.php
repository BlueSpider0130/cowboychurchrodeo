        <div class="row mb-2">
            <div class="col-12 col-sm">
                <i> Showing {{ $results->count() }} of {{ $results->total() }} total results </i>
            </div>
            <div class="col-12 col-sm text-right">
                @if( $results->total() > $results->perPage() )
                    Results per page: <x-list-builder.per-page-links :paginator="$results" :options="[ 25, 50, 100, 'All']" />
                @endif
            </div>
        </div>