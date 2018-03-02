
@extends('layouts.app')

@section('title', '| Report')

@section('content')

<div class="container">

    <h3>Report</h3>

	<div class="row">
		<div class="margin-horizontal-10 margin-left-15">
	        {!! Form::open(['route' => ['report.back'], 'method' => 'POST']) !!}
	            {!! Form::submit('Back', ['class' => 'btn btn-outline-primary']) !!}
	        {!! Form::close() !!}
	    </div>
{{-- 
	    <div>
	        {!! Form::open(['route' => ['report.download'], 'method' => 'POST']) !!}
	            {!! Form::submit('Download as pdf', ['class' => 'btn btn-primary']) !!}
	        {!! Form::close() !!}
	    </div> --}}
	</div>
    

	{!! Charts::styles() !!}

	<div class="chart margin-15 padding-15">

	    {!! $chart->html() !!}

	</div>
	{!! Charts::scripts() !!}
	{!! $chart->script() !!}
</div>
@stop
