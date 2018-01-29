@if(Session::has('registerSuccess'))
	<div class="alert alert-success" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		{{Session::get('registerSuccess') }}
	</div>
@endif