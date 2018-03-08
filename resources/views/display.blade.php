@extends('layouts.app')

@section('title', '| Display')

@section('content')
<div class="container-fluid">
    <display :messages="messages"></display>
</div>
@endsection 

@section('javascript')
    <script type="text/javascript">

        $(document).ready(function( $ ) {

            $(document).keypress(function(e) {

                $( "#topbar" ).slideToggle( "slow" );
            });
            
        });
    </script>
@endsection