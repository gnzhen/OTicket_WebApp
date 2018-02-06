<form class="form-horizontal" method="POST" action="" >
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
        <label for="id" class="col-md-12 control-label">Id</label>

        <div class="col-md-12">
            <input id="id" type="text" class="form-control" name="id" value="{{ old('id') }}" required autofocus>

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
            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
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
