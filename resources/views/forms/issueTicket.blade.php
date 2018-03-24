<form class="form-horizontal" method="POST" action="{{ route('printer.store') }}" >
    {{ csrf_field() }}

    <div class="form-group">
        <div class="col-md-12">
            <p>Confirm take ticket for <strong>{{ $branchService->service->name }}</strong>?</p>
        </div>

        <input type="hidden" class="form-control" name="branchServiceId" value="{{ $branchService->id }}">
    </div>

    <div class="form-group">
        <div class="col-md-12 offset-6">
            <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
            <button type="submit" class="btn btn-primary hv-loading">Yes</button>
        </div>
    </div>
</form>
