<nav class="sidebar" id="sidebar">
        
    <!-- Sidebar Header -->
    <div class="sidebar-header">

       <ul class="nav navbar-nav">
            @auth
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                        {{ Auth::user()->username }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            @endauth
        </ul>
    </div>

    <!-- Sidebar Links -->
    <div class="sidebar-body">
        <ul class="list-unstyled components" role="button">
            <li class="{{ Request::is('home') ? "active" : "" }}"><a href="{{ route('home') }}">Home</a></li>

             @if(Auth::check())
                @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())

                    <!-- Link with dropdown items -->
                    <li class="{{ (Request::is('config')||Request::is('user')||Request::is('report')) ? "active" : "" }}" id="adminDropdownMenu">
                         {{-- <a href="#" class="dropdown-toggle" data-toggle="collapse"  data-target="#adminMenu" aria-expanded="false">Admin &nbsp; 
                            <span class="caret"></span>
                        </a>  --}}
            
                        <a href="#">Admin &nbsp; 
                            <span class="caret"></span>
                        </a> 

                        <ul class="list-unstyled" id="adminMenu">
                            <li class="{{ Request::is('config') ? "active" : "" }}"><a href="{{ route('config.index') }}">Configurations</a></li>
                            <li class="{{ Request::is('user') ? "active" : "" }}"><a href="{{ route('user.index') }}">Manage Users</a></li>
                            <li class="{{ Request::is('report') ? "active" : "" }}"><a href="{{ route('report.index') }}">Report</a></li>
                        </ul>
                    </li>
                @endif
            @endif

            <li class="{{ Request::is('call') ? "active" : "" }}"><a href="{{ route('call.index') }}">Ticket Calling</a></li>
            <li class="{{ Request::is('printer') ? "active" : "" }}"><a href="{{ route('printer.index') }}">Ticket Machine</a></li>
            <li class="{{ Request::is('display') ? "active" : "" }}"><a href="{{ route('display.index') }}">Display</a></li>
        </ul>
    </div>
</nav>