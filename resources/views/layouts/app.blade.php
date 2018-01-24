<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'OTicket') }} @yield('title')</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

</head>
<body>
    <div class="wrapper">
        {{-- Top Nav Bar--}}

        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    @auth
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#sidebar" aria-expanded="false">
                            <span class="sr-only">Toggle Navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    @endauth

                    <!-- Branding Image -->
                    <div class="header-sidebar">
                        <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name') }}</a>
                    </div>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    {{-- <ul class="nav navbar-nav">
                        &nbsp;
                    </ul> --}}

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    {{-- Sidebar --}}

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

            <!-- Link with dropdown items -->
            <li class="{{ (Request::is('config')||Request::is('manage')||Request::is('report')) ? "active" : "" }}">
                 <a href="#" class="dropdown-toggle" data-target="#adminMenu" data-toggle="collapse" aria-expanded="false">Admin &nbsp; 
                    <span class="caret"></span>
                </a> 

                <ul class="collapse list-unstyled" id="adminMenu">
                    <li class="{{ Request::is('config') ? "active" : "" }}"><a href="{{ route('config.index') }}">Configuration</a></li>
                    <li class="{{ Request::is('manage') ? "active" : "" }}"><a href="{{ route('manage.index') }}">Manage Users</a></li>
                    <li class="{{ Request::is('report') ? "active" : "" }}"><a href="{{ route('report.index') }}">Report</a></li>
                </ul>

            <li class="{{ Request::is('/counter') ? "active" : "" }}"><a href="{{ route('counter.index') }}">Counter</a></li>
            <li class="{{ Request::is('/display') ? "active" : "" }}"><a href="{{ route('display.index') }}">Caller Display</a></li>
            <li class="{{ Request::is('/printer') ? "active" : "" }}"><a href="{{ route('printer.index') }}">Ticket Printer</a></li>
        </ul>
        </div>
    </nav>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
</html>
