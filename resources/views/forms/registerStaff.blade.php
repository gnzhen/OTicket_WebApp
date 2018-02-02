<form class="form-horizontal" method="POST" action="{{ route('register') }}" >
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
        <label for="username" class="col-md-10 control-label">Username</label>

        <div class="col-md-10">
            <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

            @if ($errors->has('username'))
                <span class="help-block">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('branch_id') ? ' has-error' : '' }}">
        <label for="branch_id" class="col-md-10 control-label">Branch</label>

        <div class="col-md-6">
            <select class="btn btn-dropdown dropdown-toggle" name="branch_id">
                    <option>-</option>
                    <option value="0">Ampang</option>
                    <option value="1">Sunway</option>
            </select>

            @if ($errors->has('branch_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('branch_id') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('role_id') ? ' has-error' : '' }}" style="display:none;">
        <label for="role_id" class="col-md-10 control-label">User Role</label>

        <div class="col-md-6">
            <select class="btn btn-dropdown dropdown-toggle" name="role_id">
                    <option value="2">Counter Staff</option>
            </select>

            @if ($errors->has('role_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('role_id') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email" class="col-md-10 control-label">E-Mail Address</label>

        <div class="col-md-10">
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label for="password" class="col-md-10 control-label">Password</label>

        <div class="col-md-10">
            <input id="password" type="password" class="form-control" name="password" required>

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="password-confirm" class="col-md-10 control-label">Confirm Password</label>

        <div class="col-md-10">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Add</button>
            </button>
        </div>
    </div>
</form>
