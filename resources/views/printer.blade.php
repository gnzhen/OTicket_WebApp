@extends('layouts.app')

@section('title', '| Printer')

@section('content')

@include('modals._printer')

<div class="container">
	<h2 style="text-align:center; padding-bottom: 30px;">Welcome!!</h2>
  @foreach($branchServices as $branchService)
		<button type="button" class="btn btn-outline-primary btn-lg btn-block" id="btnIssueTicket" data-id="{{ $branchService->id }}">{{ $branchService->service->name }}</button>
	@endforeach
</div>

@endsection

@section('javascript')
<script type="text/javascript">
	 $.noConflict();

	 jQuery( document ).ready(function( $ ) {
	 	//
	 });
</script>
@endsection