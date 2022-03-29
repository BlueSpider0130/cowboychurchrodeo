<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rodeo App') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>

        @media (max-width: 767.98px) {
            #sidebarMenu {
                background-color: white !important;
            }
        }
        
        /*
         * Sidebar
         */
        .sidebar {
          position: fixed;
          top: 0;
          bottom: 0;
          left: 0;
          z-index: 100; /* Behind the navbar */
          padding: 48px 0 0; /* Height of navbar */
          box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }

        @media (max-width: 767.98px) {
            .sidebar {
                margin-top: 55px;
                padding-top: 0;
                z-index: 9000; /* In front of navbar to cover shadow... */
            }
        }
        @media (max-width: 767.98px) {
            .sidebar hr {
                display: none;  
            }
        }

        .sidebar-sticky {
          position: relative;
          top: 0;
          height: calc(100vh - 48px);
          padding-top: .5rem;
          overflow-x: hidden;
          overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
        }

        @media (max-width: 767.98px) {
            .sidebar-sticky {
                padding-top: 0;
            }
        }

        @supports ((position: -webkit-sticky) or (position: sticky)) {
          .sidebar-sticky {
            position: -webkit-sticky;
            position: sticky;
          }
        }

        .sidebar .nav-link {
          font-weight: 500;
          color: #333;
        }

        .sidebar .nav-link.active {
            background-color: #ced4da;

        }

        .sidebar-heading {
          font-size: .75rem;
          text-transform: uppercase;
        }

        /*
         * Navbar
         */
        .navbar-brand {
            padding-top: 0.32rem;
            padding-bottom: 0.32rem;
            margin-right: 1rem;
            font-size: 1.125rem;
            line-height: inherit;
            white-space: nowrap;  
        }

        .navbar .navbar-toggler {
          top: .25rem;
          right: 1rem;
        }

        @media print {
            #top-nav {
                display: none;
            }

            #sidebarMenu {
                display: none;
            }
        }
        @media print {
            #top-nav { display: none; }
            #sidebarMenu { display: none; }
            #main-content { display:block; width: 100%; }
        }
    </style>    

    @stack('head')
</head>
<body>
    <x-operator-bar />
    <div id="app">    

        <nav id="top-nav" class="navbar navbar-light bg-white shadow-sm navbar-expand-md flex-md-nowrap sticky-top">
            <div class="container-fluid">
                @if( Request::route('organization') )
                    <a class="navbar-brand" href="{{ route('toolbox', Request::route('organization')->id) }}">
                        {{ config('app.name', 'Rodeo App') }}
                    </a>
                @else
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Rodeo App') }}
                    </a>
                @endif   
                
                <button class="navbar-toggler" 
                    type="button" 
                    data-toggle="collapse" 
                    data-target="#sidebarMenu" 
                    aria-controls="navbarSupportedContent" 
                    aria-expanded="false" 
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav ml-md-3 mr-auto">
                        @if( Request::route('organization') )
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('organizations.show', Request::route('organization')->id) }}">
                                    {{ Request::route('organization')->name }}
                                </a>
                            </li>                            
                        @endif                        
                    </ul>


                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else    
                            @include( 'layouts/_navbar_main_user_dropdown' )  
                        @endguest
                    </ul>
                </div>

            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">

                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse">
                    <div class="sidebar-sticky pt-md-3 pl-md-3">

                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('producer.home', $organization) }}">
                                    Dashboard
                                </a>
                            </li>
                        </ul>

                        @can('access-level-2-for-organization', $organization)

                            <hr class="mt-4 mb-3">
                            <h6 class="sidebar-heading d-none d-md-flex justify-content-between align-items-center text-muted px-3">
                                <span> Secretary </span>
                            </h6>
                            <hr class="mt-3 mb-2">

                            <ul class="nav flex-column">                               

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('L2.build.series.home', $organization) }}">
                                        <!--<i class="fas fa-hammer"></i>-->
                                        Build series
                                    </a>
                                </li>  

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('L2.contestants.index', $organization) }}">
                                        <!--<i class="fas fa-hat-cowboy"></i>-->
                                        Contestants
                                    </a>
                                </li>    

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('L2.membership.home', $organization) }}">
                                        <!--<i class="far fa-address-card"></i>-->
                                        Memberships
                                    </a>
                                </li>  
 
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('L2.registration.rodeos.index', $organization) }}">
                                        Rodeo registration
                                    </a>
                                </li>  

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('L2.entries.home', $organization) }}">
                                        Rodeo entries
                                    </a>
                                </li>  

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('L2.reports.home', $organization) }}">
                                        Reports
                                    </a>
                                </li>                                  
                                          
                            </ul>

                        @endcan

                        @can('access-level-3-for-organization', $organization)

                            <hr class="mt-4 mb-3">
                            <h6 class="sidebar-heading d-none d-md-flex justify-content-between align-items-center text-muted px-3">
                                <span> Data entry </span>
                            </h6>
                            <hr class="mt-3 mb-2">

                            <ul class="nav flex-column">

                                <li class="nav-item">
                                    <a class="nav-link {{ Route::is('L3.check-in.*') ? 'active' : '' }}" href="{{ route('L3.check-in.home', [$organization]) }}">
                                        <!--<i class="far fa-calendar-check"></i>-->
                                        Work check-in
                                    </a>
                                </li> 

                                <li class="nav-item">
                                    <a class="nav-link {{ Route::is('L3.results.*') ? 'active' : '' }}" href="{{ route('L3.results.home', [$organization]) }}">
                                        <!--<i class="far fa-file-alt"></i>-->
                                        Work events
                                    </a>
                                </li>    

                            </ul>

                        @endcan
                        
                        @can('access-level-2-for-organization', $organization)

                            <hr class="mt-4 mb-3">
                            <h6 class="sidebar-heading d-none d-md-flex justify-content-between align-items-center text-muted px-3">
                                <span> Profile & Settings </span>
                            </h6>
                            <hr class="mt-3 mb-2">

                            <ul class="nav flex-column mb-md-2">

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('L2.organizations.edit', $organization) }}">
                                        Organization 
                                    </a>
                                </li>    

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('L2.documents.index', $organization) }}">
                                        Documents
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('L2.events.index', $organization) }}">
                                        Events
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('L2.groups.index', $organization) }}">
                                        Groups
                                    </a>
                                </li>

                            </ul>      

                        @endcan 


                        <ul class="nav flex-column mt-1 border-top pt-1 d-md-none">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">
                                    <!--<i class="fas fa-toolbox fa-icon"></i>--> 
                                    Toolbox
                                </a>
                            </li> 
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('account.index') }}">
                                    <!--<i class="fas fa-user-circle fa-icon"></i>-->
                                    Account
                                </a>
                            </li> 
                            @if( Auth::user()->isAdmin()  ||  Auth::user()->isSuper() )
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.home') }}">
                                        <!--<i class="fas fa-user-shield fa-icon"></i>-->
                                        Admin
                                    </a>
                                </li>                             
                            @endif                            
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form-side').submit();">
                                    <!--<i class="fas fa-sign-out-alt fa-icon"></i>--> 
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form-side" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>                                
                            </li>                            
                        </ul>

                    </div>
                </nav>

                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4" id="main-content">
                    @yield('content')
                </main>

            </div><!--/row-->
        </div><!--/container-->

        @include('partials.feedback_footer')

    </div><!--/app-->

    @stack('body')
</body>
</html>