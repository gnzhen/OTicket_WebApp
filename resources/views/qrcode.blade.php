@extends('layouts.app')

@section('title', '| QRCode')

@section('content')

<div class="container">

    <h3>Scan QR Code</h3>

    <div class="text-center">
    	<h1>{{ $branchService->service->name }}</h1>
    </div>

    <a href="{{ route('printer.index') }}">
    	<button type="button" class="btn btn-primary btn-lg btn-block hv-loading" style="margin-top:20px; margin-bottom: 20px;">Other Services</button>
    </a>

    <div class="visible-print text-center">
		{!! QrCode::size(500)->generate($branchService->id); !!}
	</div>
</div>
@endsection