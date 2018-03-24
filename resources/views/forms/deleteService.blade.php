{!! Form::model($service, ['route' => ['service.destroy', $service->id], 'method' => 'DELETE']) !!}

    <div class="form-group">
        <div class="col-md-12">
            <p><strong>Delete this service?</strong></p>
            <p>{{ $service->name }} ({{ $service->code }})</p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12 offset-4">
             <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger hv-loading">Delete</button>
        </div>
    </div>
</form>
