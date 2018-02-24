{!! Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'PUT']) !!}

    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
        <label for="username" class="col-md-10 control-label">Username</label>

        <div class="col-md-10">
            <input type="text" class="form-control" name="username" value="{{ $user->username }}" required autofocus>

            @if ($errors->has('username'))
                <span class="help-block">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('branch') ? ' has-error' : '' }}">
        <label for="branch" class="col-md-10 control-label">Branch</label>

        <div class="col-md-6">
            <select class="btn btn-dropdown dropdown-toggle" name="branch">
                    <option>-</option>
                    @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
            </select>

            @if ($errors->has('branch_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('branch') }}</strong>
                </span>
            @endif
        </div>
    </div>
    
    <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}" style="display:none;">
        <label for="role" class="col-md-10 control-label">User Role</label>

        <div class="col-md-6">
            <select class="btn btn-dropdown dropdown-toggle" name="role" disabled>
                <option value="1">Super Admin</option>
            </select>

            @if ($errors->has('role'))
                <span class="help-block">
                    <strong>{{ $errors->first('role') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email" class="col-md-10 control-label">E-Mail Address</label>

        <div class="col-md-10">
            <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group" >
        <a href="#password{{ $user->id }}" data-toggle="collapse">Change password</a>
    </div>

    <div id="password{{ $user->id }}" class="collapse">
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-10 control-label">Old Password</label>

            <div class="col-md-10">
                <input type="password" class="form-control" name="oldPassword" required>

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-10 control-label">New Password</label>

            <div class="col-md-10">
                <input type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="password-confirm" class="col-md-10 control-label">Confirm New Password</label>

            <div class="col-md-10">
                <input type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Add</button>
            </button>
        </div>
    </div>
</form>
