@extends('layouts.app')

@section('title', '| Printer')

@section('content')

@include('modals._printer')

<div class="container">
	<h2 style="text-align:center; padding-bottom: 30px;">Welcome!!</h2>
	<button type="button" class="btn btn-outline-primary btn-lg btn-block">Customer Service</button>
  	<button type="button" class="btn btn-outline-primary btn-lg btn-block">Deposit</button>
  	<button type="button" class="btn btn-outline-primary btn-lg btn-block">Withdraw</button>
  	<button type="button" class="btn btn-outline-primary btn-lg btn-block">Loan Enquiry</button>
  	<button type="button" class="btn btn-outline-danger btn-lg btn-block">Ask for help</button>
</div>

@endsection

@section('javascript')
<script type="text/javascript">
    $.noConflict();

    jQuery( document ).ready(function( $ ) {
        $('.btn.btn-lg').click(function() {
			$('#issueTicketModal').modal('show');
		});
    });
</script>
@endsection