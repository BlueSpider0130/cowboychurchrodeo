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
      

        @if( Request::route('organization') )            
            <a class="dropdown-item" href="{{ route('toolbox', Request::route('organization')->id) }}">
                <i class="fas fa-toolbox fa-icon"></i>
                Toolbox
            </a>
        @else
            <a class="dropdown-item" href="{{ route('home') }}">
                <i class="fas fa-toolbox fa-icon"></i>
                Toolbox
            </a>        
        @endif    
  
        @if( Request::route('organization') )
            <div class="d-md-none">
                <hr class="my-1">
                <a class="dropdown-item mb-3" href="{{ route('organizations.index') }}">
                    <i class="fas fa-exchange-alt fa-icon"></i>
                    Switch organization
                </a>
                <hr class="mt-5 mb-1">
            </div>                  
        @endif          

        <a class="dropdown-item" href="{{ route('account.index') }}">
            <i class="fas fa-user-circle fa-icon"></i>
            Account
        </a>

        @if( Auth::user()->isAdmin()  ||  Auth::user()->isSuper() )
            <a class="dropdown-item" href="{{ route('admin.home') }}">
                <i class="fas fa-user-lock fa-icon"></i>
                Admin
            </a>
        @endif

        <a class="dropdown-item" href="{{ route('logout') }}"
           onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt fa-icon"></i> 
            {{ __('Logout') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>


    </div>
</li>