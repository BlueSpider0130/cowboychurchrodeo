                        <div class="row">
                            <div class="col-12 col-md-10">
                                <b>{{ $rodeo->name }}</b> <br>
                                <x-rodeo-date :date="$rodeo->starts_at" /> &ndash; <x-rodeo-date :date="$rodeo->ends_at" />
                            </div>
                            @if( $rodeo->isRegistrationOpen() )
                                <div class="col-12 col-md-2 mt-2 mt-md-0 text-md-right">
                                    <a href="{{ route('toolbox', [$organization->id]) }}" class="btn btn-outline-primary btn-sm"> Registration </a>
                                </div>
                            @endif
                        </div>
