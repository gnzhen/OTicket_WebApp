{!! Form::model($branch, ['route' => ['branch.addService', $branch->id], 'class' => 'form-horizontal']) !!}


    <div class="form-group">
        <label for="code" class="col-md-12 control-label">Branch</label>

        <div class="col-md-12">
            <input type="hidden" class="form-control" name="branch_id" value="{{ $branch->id }}">
            <input type="text" class="form-control" value="{{ $branch->name }}({{ $branch->code }})" disabled>
        </div>
    </div>

    <div class="form-group{{ $errors->has('service') ? ' has-error' : '' }}">
        <label for="service" class="col-md-12 control-label">Services</label>

        <div class="col-md-8">
            <select id="multiSelectBranchCoutner" class="form-control" name="service">
                @foreach ($services as $service)
                    <option value='{{ $service->id }}' {{ $branch->services->contains($service->id) ? "disabled" : "" }}>
                        {{ $service->name }} ({{ $service->code }})
                    </option>
                @endforeach
            </select>

            @if ($errors->has('service'))
                <span class="help-block">
                    <strong>{{ $errors->first('service') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('default_wait_time') ? ' has-error' : '' }}">
        <label for="default_wait_time" class="col-md-12 control-label">Default Wait Time</label>

        <div class="form-inline col-md-12">
            <div class="form-group">
                <input type="number" class="form-control" name="default_wait_time_hr" min="0" max="23" value="0"></input>
                <label for="default_wait_time_hr" class="control-label inline-label">hour</label>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="default_wait_time_min" min="0" max="59" value="5"></input>
                <label for="default_wait_time_min" class="control-label inline-label">min</label>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="default_wait_time_sec" min="0" max="59" value="0"></input>
                <label for="default_wait_time_sec" class="control-label inline-label">sec</label>
            </div>

            @if ($errors->has('default_wait_time_hr'))
                <span class="help-block">
                    <strong>{{ $errors->first('default_wait_time_hr') }}</strong>
                </span>
            @endif
            @if ($errors->has('default_wait_time_min'))
                <span class="help-block">
                    <strong>{{ $errors->first('default_wait_time_min') }}</strong>
                </span>
            @endif
            @if ($errors->has('default_wait_time_sec'))
                <span class="help-block">
                    <strong>{{ $errors->first('default_wait_time_sec') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary hv-loading ">Add</button>
        </div>
    </div>
</form>
