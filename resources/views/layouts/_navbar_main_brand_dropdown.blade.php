                @if( Request::route('organization') )
                    <div class="d-none d-md-block">
                        <a 
                            class="navbar-brand"
                            id="navbarBrandDropdown" 
                            href="#" 
                            role="button" 
                            data-toggle="dropdown" 
                            aria-haspopup="true" 
                            aria-expanded="false" 
                            v-pre                        
                        >
                            {{ config('app.name', 'Rodeo App') }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-left my-0" style="min-width: 300px" aria-labelledby="navbarBrandDropdown">

                            <div class="px-3" style="font-size: .85rem">
                                {{ Request::route('organization')->name }}
                            </div>
                            <hr class="my-1"> 
                            <a class="dropdown-item" href="{{ route('organizations.show', Request::route('organization')->id) }}">
                                <i class="fas fa-home fa-icon mr-1"></i>
                                Homepage
                            </a>
                            <a class="dropdown-item" href="{{ route('toolbox', Request::route('organization')->id) }}">
                                <i class="fas fa-toolbox fa-icon mr-1"></i>
                                Toolbox
                            </a>

                            <hr class="mt-4 mb-1"> 
                            <a class="dropdown-item" href="{{ url('/') }}">
                                Switch organization                                
                            </a>
                        </div>
                    </div>

                    <a class="navbar-brand d-md-none" href="{{ url('/') }}">
                        {{ config('app.name', 'Rodeo App') }}
                    </a>
                @else
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Rodeo App') }}
                    </a>
                @endif     