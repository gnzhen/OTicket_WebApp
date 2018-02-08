<form class="form-horizontal" method="POST" action="" >
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
        <label for="id" class="col-md-12 control-label">Id</label>

        <div class="col-md-12">
            <input type="text" class="form-control" name="id" value="{{ old('id') }}" required autofocus>

            @if ($errors->has('id'))
                <span class="help-block">
                    <strong>{{ $errors->first('id') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('branch') ? ' has-error' : '' }}">
        <label for="branch" class="col-md-12 control-label">Branch</label>

        <div class="col-md-6">
            <select class="btn btn-dropdown dropdown-toggle" name="branch">
                    <option value="0">Admin</option>
            </select>

            @if ($errors->has('branch'))
                <span class="help-block">
                    <strong>{{ $errors->first('branch') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('service') ? ' has-error' : '' }}">
        <label for="service" class="col-md-6 control-label">Service</label>

        <div class="col-md-6">
            <select class="btn btn-dropdown dropdown-toggle" name="service">
                    <option value="0">Admin</option>
            </select>

            @if ($errors->has('service'))
                <span class="help-block">
                    <strong>{{ $errors->first('service') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('default_wait_time') ? ' has-error' : '' }}">
        <label for="default_wait_time" class="col-md-4 control-label">Default wait time</label>

        <input type="number" class="form-control" name="default_wait_time" value="{{ old('default_wait_time') }}" required autofocus>

            @if ($errors->has('default_wait_time'))
                <span class="help-block">
                    <strong>{{ $errors->first('default_wait_time') }}</strong>
                </span>
            @endif
    </div>

    <div class="form-group{{ $errors->has('system_wait_time') ? ' has-error' : '' }}">
        <label for="system_wait_time" class="col-md-4 control-label">System wait time</label>

        <input type="number" class="form-control" name="system_wait_time" value="{{ old('system_wait_time') }}" required autofocus>

            @if ($errors->has('system_wait_time'))
                <span class="help-block">
                    <strong>{{ $errors->first('system_wait_time') }}</strong>
                </span>
            @endif
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </div>
</form>
