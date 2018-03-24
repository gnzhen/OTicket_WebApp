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
                    <option value="">-</option>
                    @foreach($branches as $branch)
                    <option value={{ $branch->id }} {{ $branch->id == $user->branch_id ? "selected" : ""}}>{{ $branch->name }}</option>
                    @endforeach
            </select>

            @if ($errors->has('branch'))
            <div>
                <span class="help-block">
                    <strong>{{ $errors->first('branch') }}</strong>
                </span>
            </div>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}" style="display:none;">
        <label for="role" class="col-md-10 control-label">User Role</label>

        <div class="col-md-6">
            <input type="number" class="form-control" name="role" value={{ $user->role_id }} required>
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

    <div class="form-group">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary hv-loading">Save</button>
            </button>
        </div>
    </div>
</form>
