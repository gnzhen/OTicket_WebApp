<form class="form-horizontal" method="POST" action="{{ route('branch.store') }}" >
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
        <label for="code" class="col-md-12 control-label">Code</label>

        <div class="col-md-12">
            <input id="branch-code" type="text" class="form-control" name="code" value="{{ old('code') }}" maxlength="255" required autofocus>

            @if ($errors->has('code'))
                <span class="help-block">
                    <strong>{{ $errors->first('code') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label for="name" class="col-md-12 control-label">Name</label>

        <div class="col-md-12">
            <input id="branch-name" type="text" class="form-control" name="name" value="{{ old('name') }}" maxlength="255" required autofocus>

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
            <textarea id="branch-desc" type="text" class="form-control" name="desc" maxlength="255" autofocus>{{ old('desc') }}</textarea>

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
        </div>
    </div>
</form>
