@if(Session::has('success'))
	<div class="alert alert-success" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		{{Session::get('success') }}
	</div>
@endif

@if(Session::has('fail'))
	<div class="alert alert-danger" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		{{Session::get('fail') }}
	</div>
@endif