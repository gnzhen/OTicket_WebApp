@extends('layouts.app')

@section('title', '| Display')

@section('content')
<div class="container-fluid">
    {{-- <div class="fill"> --}}
        <div class="box shadow">
            <div class="box-content">
                <p style="font-size: 180px">0001</p>
                <p style="font-size: 100px">Counter 1</p>
            </div>
        </div>
        <div class="row row-box">
            <div class="box shadow col margin-right-15">
                <div class="box-content">
                    <p style="font-size: 60px">0001</p>
                    <p style="font-size: 30px">Counter 1</p>
                </div>
            </div>
            <div class="box shadow col ">
                <div class="box-content">
                    <p style="font-size: 60px">0001</p>
                    <p style="font-size: 30px">Counter 1</p>
                </div>
            </div>
            <div class="box shadow col margin-left-15">
                <div class="box-content">
                    <p style="font-size: 60px">0001</p>
                    <p style="font-size: 30px">Counter 1</p>
                </div>
            </div>
        </div>
    {{-- </div> --}}
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