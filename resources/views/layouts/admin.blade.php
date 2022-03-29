<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Rodeo App') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}?v=2" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    @stack('head')
</head>
<body class="admin-body">

    <div id="app">

        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow"  id="admin-top-nav" >
            <div class="container-fluid px-0">
                <a class="navbar-brand bg-dark col-8 col-sm-3 col-md-2 mr-0" href="{{ url('/') }}"> {{ config('app.name', 'Cowboy Church Rodeo') }} </a>

                <button class="navbar-toggler mr-2" type="button" data-toggle="collapse" data-target="#admin-sidebar" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    @if( Session::has('operator') )
                        <ul class="navbar-nav text-warning">
                            <li class="nav-item"> 
                                <x-operator />
                            </li>
                        </ul>
                    @endif 

                    <ul class="navbar-nav px-3 mr-auto">                       
                    </ul>

                    <ul class="navbar-nav px-3 ml-auto">
                        <li id="navbar-user-dropdown" class="nav-item dropdown">
                            <a 
                                id="navbarDropdown" 
                                class="nav-link dropdown-toggle" 
                                href="#" 
                                role="button" 
                                data-toggle="dropdown" 
                                aria-haspopup="true" 
                                aria-expanded="false" 
                                v-pre
                            >
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>


                            <div class="dropdown-menu dropdown-menu-right my-0" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('home') }}">
                                    <i class="fas fa-toolbox fa-icon"></i>
                                    Toolbox
                                </a>        
                                
                                <a class="dropdown-item" href="{{ route('account.index') }}">
                                    <i class="fas fa-user-circle fa-icon"></i>
                                    Account
                                </a>

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt fa-icon"></i> 
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>        
                            </div><!--/dropdown-->
                        </li>
                    </ul>        
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">

                <nav class="d-md-block col-md-2 bg-dark sidebar collapse" id="admin-sidebar">
                    <div class="sidebar-sticky ">
                        <ul class="nav flex-column">

                            @php
                                $route = 'admin.home';
                                $isActive = $route == Request::route()->getName();
                            @endphp
                            <li class="nav-item text-nowrap">
                                <a 
                                    href="{{ route($route) }}" 
                                    class="nav-link text-light {{ $isActive ? 'active' : '' }}"
                                > 
                                    <i class="fas fa-home"></i>
                                    Dashboard
                                    @if( $isActive )
                                        <span class="sr-only">(current)</span>
                                    @endif
                                </a>
                            </li>

                            @php
                                $route = 'admin.organizations.index';
                                $isActive = $route == Request::route()->getName();
                            @endphp
                            <li class="nav-item text-nowrap">
                                <a 
                                    href="{{ route($route) }}" 
                                    class="nav-link text-light {{ $isActive ? 'active' : '' }}"
                                > 
                                    <i class="fas fa-church"></i>
                                    Organizations
                                    @if( $isActive )
                                        <span class="sr-only">(current)</span>
                                    @endif
                                </a>
                            </li>


                            @php
                                $route = 'admin.contestants.index';
                                $isActive = $route == Request::route()->getName();
                            @endphp
                            <li class="nav-item text-nowrap">
                                <a href="{{ route($route) }}" class="nav-link text-light {{ $isActive ? 'active' : '' }}"> 
                                    <i class="fas fa-hat-cowboy"></i>
                                    Contestants
                                    @if( $isActive )
                                        <span class="sr-only">(current)</span>
                                    @endif
                                </a>
                            </li>


                            @php
                                $route = 'admin.draw.index';
                                $isActive = $route == Request::route()->getName();
                            @endphp
                            <li class="nav-item text-nowrap">
                                <a 
                                    href="{{ route($route) }}" 
                                    class="nav-link text-light {{ $isActive ? 'active' : '' }}"
                                > 
                                    <i class="fas fa-sort-numeric-down"></i>
                                    Draw
                                    @if( $isActive )
                                        <span class="sr-only">(current)</span>
                                    @endif
                                </a>
                            </li>



                            @php
                                $route = 'admin.users.index';
                                $isActive = $route == Request::route()->getName();
                            @endphp
                            <li class="nav-item text-nowrap">
                                <a 
                                    href="{{ route($route) }}" 
                                    class="nav-link text-light {{ $isActive ? 'active' : '' }}"
                                > 
                                    <i class="fas fa-users"></i>
                                    Users
                                    @if( $isActive )
                                        <span class="sr-only">(current)</span>
                                    @endif
                                </a>
                            </li>

                            @php
                                $routes = ['admin.task.index.open', 'admin.task.index.closed', 'admin.task.index.all'];
                                $isActive = in_array( Request::route()->getName(), $routes );
                            @endphp
                            <li class="nav-item text-nowrap">
                                <a 
                                    href="{{ route('admin.task.index.open') }}"
                                    class="nav-link text-light {{ $isActive ? 'active' : '' }}"                                    
                                > 
                                    <i class="fas fa-tasks"></i>
                                    To do 
                                    @if( $isActive )
                                        <span class="sr-only">(current)</span>
                                    @endif                                
                                </a>
                            </li>


                            <li class="nav-item text-nowrap">
                                <a 
                                    href="{{ route('L1.import.home') }}" 
                                    class="nav-link text-light"
                                > 
                                    Import Entries
                                </a>
                            </li>


                            @php
                                $isActive = 'home' == Request::route()->getName();
                            @endphp
                            <li class="nav-item text-nowrap d-md-none">
                                <a 
                                    href="{{ route('home') }}" 
                                    class="nav-link text-light {{ $isActive ? 'active' : '' }}"
                                > 
                                    <i class="fas fa-toolbox"></i>
                                    Toolbox
                                    @if( $isActive )
                                        <span class="sr-only">(current)</span>
                                    @endif
                                </a>
                            </li>

                        </ul>
                    </div>
                </nav>

                <main role="main" class="col-12 col-md-10 ml-sm-auto">
                    @yield('content')
                </main>

            </div><!--/row-->
        </div><!--/container-->
    </div><!--/app-->

    @stack('body')
</body>
</html>