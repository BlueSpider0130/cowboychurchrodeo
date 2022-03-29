    <div class="row mt-3 mb-5">
        <div class="col-12 col-md-10 col-lg-8">      

            <div class="card">
                <div class="card-body">
                    Series: {{ $series->name }}
                    <hr class="my-2">
                    <div>
                        {{ $series->starts_at ? $series->starts_at->toFormattedDateString() : 'TBA'}} 
                        &ndash; 
                        {{ $series->ends_at ? $series->ends_at->toFormattedDateString() : 'TBA' }}
                    </div>         
                </div>
            </div><!--/card-->

        </div><!--/col-->
    </div><!--/row-->