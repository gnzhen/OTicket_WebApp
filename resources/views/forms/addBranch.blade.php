<form class="form-horizontal" method="POST" action="{{ route('branch.store') }}" >
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
        <label for="id" class="col-md-12 control-label">Id</label>

        <div class="col-md-12">
            <input id="branch-id" type="text" class="form-control" name="id" value="{{ old('id') }}" required autofocus>

            @if ($errors->has('id'))
                <span class="help-block">
                    <strong>{{ $errors->first('id') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label for="name" class="col-md-12 control-label">Name</label>

        <div class="col-md-12">
            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" maxlength="255" required autofocus>

            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('desc') ? ' has-error' : '' }}">
        <label for="desc" class="col-md-12 control-label">Description</label>

        <div class="col-md-12">
            <textarea id="desc" type="text" class="form-control" name="desc" maxlength="255" autofocus>{{ old('desc') }}</textarea>

            @if ($errors->has('desc'))
                <span class="help-block">
                    <strong>{{ $errors->first('desc') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Add</button>
            </button>
        </div>
    </div>
</form>
