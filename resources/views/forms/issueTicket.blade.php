<form class="form-horizontal" method="POST" action="" >
    {{ csrf_field() }}

    <div class="form-group">
        <div class="col-md-12">
            <p><strong>Confirm take ticket?</strong></p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12 offset-6">
            <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
            <button type="submit" class="btn btn-danger" data-dismiss="modal">Yes</button>
        </div>
    </div>
</form>
