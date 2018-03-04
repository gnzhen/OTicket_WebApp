<nav class="navbar navbar-expand-lg navbar-light" id="topbar">
    <!-- Collapsed Hamburger -->
    <div class="navbar-header">
         @auth
            <button class="navbar-toggler" data-toggle="collapse" data-target="#sidebar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        @endauth

        <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name') }}</a>
    </div>
        <ul class="nav justify-content-end">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                </li> --}}
            @endguest
        </ul>
</nav>