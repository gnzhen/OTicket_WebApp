{!! Form::model($counter, ['route' => ['counter.update', $counter->id], 'method' => 'PUT']) !!}

    <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
        <label for="code" class="col-md-12 control-label">Code</label>

        <div class="col-md-12">
            <input type="text" class="form-control" name="code" value="{{ $counter->code }}">

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
            <input type="text" class="form-control" name="name" value="{{ $counter->name }}" maxlength="255" required autofocus>

            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
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
