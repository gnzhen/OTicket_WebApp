{!! Form::model($branchCounter, ['route' => ['branchService.destroy', $branchService->id], 'method' => 'DELETE']) !!}

    <div class="form-group">
        <div class="col-md-12">
            <p>Delete <strong>{{ $branchService->service_name }} ({{ $branchService->service_code }})</strong> from <strong>{{ $branchService->branch_name }} ({{ $branchService->branch_code }})</strong>?</p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12 offset-4">
             <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
        </div>
    </div>
</form>
