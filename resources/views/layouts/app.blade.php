<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'OTicket') }} @yield('title')</title>

    {{-- Bootstrap 4 --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    {{-- Sorting Table --}}
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>

    <!-- Styles -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/redmond/jquery-ui.css">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

</head>
<body>
    <div class="wrapper" id="app">   
        {{-- Top Nav Bar--}}
        @include('partials._topbar')

        @include('partials._messages')
            
        @yield('content')

        {{-- Sidebar --}}

        @include('partials._sidebar')
    </div>


    {{-- Firebase --}}
    <script src="https://www.gstatic.com/firebasejs/4.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.10.1/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.10.1/firebase-database.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.10.1/firebase-firestore.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.10.1/firebase-messaging.js"></script>

    <script src="https://www.gstatic.com/firebasejs/4.11.0/firebase.js"></script>
    
    <script>
        // Initialize Firebase
        var config = {
            apiKey: "AIzaSyCvHc55d1mDCFWGm4Gxe-bxRq_AMwqBnM0",
            authDomain: "oticket-fa080.firebaseapp.com",
            databaseURL: "https://oticket-fa080.firebaseio.com",
            projectId: "oticket-fa080",
            storageBucket: "oticket-fa080.appspot.com",
            messagingSenderId: "508395572323"
        };

        firebase.initializeApp(config);
    </script>

    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
    
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    {{-- Data Table --}}
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/fbd569b8768155c7a9a62568e66a64115887d7d0/dataTables.rowsGroup.js"></script>

     {{-- Bootstrap 4 --}}
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


    <!-- Custom Scripts -->
    <script src="{{ asset('js/custom.js') }}"></script>

    @yield('javascript')

</body>
</html>
