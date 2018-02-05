@if(Session::has('registerSuccess'))
	<div class="alert alert-success" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		{{Session::get('registerSuccess') }}
	</div>
@endif

@if(Session::has('registerFail'))
	<div class="alert alert-danger" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		{{Session::get('registerSuccess') }}
	</div>
@endif

@if(Session::has('success'))
	<div class="alert alert-success" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		{{Session::get('success') }}
	</div>
@endif