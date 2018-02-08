{!! Form::model($branch, ['route' => ['branch.destroy', $branch->id], 'method' => 'DELETE']) !!}

    <div class="form-group">
        <div class="col-md-12">
            <p><strong>Delete this branch?</strong></p>
            <p>{{ $branch->code }}-{{ $branch->name }}</p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12 offset-4">
             <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
        </div>
    </div>
</form>
