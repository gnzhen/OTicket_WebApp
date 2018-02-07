<form class="form-horizontal" method="DELETE" action="" id="formDeleteBranch" data-method="DELETE" data-token="{{ csrf_token() }}">
    {{-- {{ method_field('delete') }}
    {{ csrf_field() }} --}}

    <div class="form-group">
        <div class="col-md-12">
            <p>Are you sure you want to delete?</p>
            <input type="hidden" name="delete-branch_id" id="delete-branch_id">
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12 offset-4">
             <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
        </div>
    </div>
</form>
