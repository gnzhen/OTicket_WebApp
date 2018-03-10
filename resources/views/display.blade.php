@extends('layouts.app')

@section('title', '| Display')

@section('content')
<div class="container-fluid" id="display">
    <display :messages="messages"></display>
</div>
@endsection 

@section('javascript')
	<script src="{{ asset('js/app.js') }}"></script>
	
    <script type="text/javascript">

        $(document).ready(function( $ ) {

            $(document).keypress(function(e) {

                $( "#topbar" ).slideToggle( "slow" );
            });
            
        });
    </script>
@endsection