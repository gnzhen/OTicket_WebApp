{!! Form::model($branchCounter, ['route' => ['branchCounter.destroy', $branchCounter->id], 'method' => 'DELETE']) !!}

    <div class="form-group">
        <div class="col-md-12">
            <p>Delete <strong>{{ $branchCounter->counter_name }} ({{ $branchCounter->counter_code }})</strong> from <strong>{{ $branchCounter->branch_name }} ({{ $branchCounter->branch_code }})</strong>?</p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12 offset-4">
             <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
        </div>
    </div>
</form>
