<form class="form-horizontal" method="POST" action="{{ route('branchService.store') }}" >
    {{ csrf_field() }}

    <div class="form-group">
        <label for="code" class="col-md-12 control-label">Branch</label>

        <div class="col-md-12">
            <input type="hidden" class="form-control" name="branch_id" value="{{ $branch->id }}">
            <input type="text" class="form-control" value="{{ $branch->name }}({{ $branch->code }})" disabled>
        </div>
    </div>

    <div class="form-group{{ $errors->has('service_id') ? ' has-error' : '' }}">
        <label for="service_id" class="col-md-12 control-label">Services</label>

        <div class="col-md-8">
            <select id="multiSelectBranchCoutner" class="form-control" name="service_id">
                @foreach ($services as $service)
                {{-- @if($counter->id == $branchCounter->branch_id) --}}
                <option value='{{ $service->counter_id }}'>
                    {{ $service->name }} ({{ $service->code }})
                </option>
                {{-- @endif --}}
                @endforeach
            </select>

            @if ($errors->has('service_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('service_id') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('default_wait_time') ? ' has-error' : '' }}">
        <label for="default_wait_time" class="col-md-12 control-label">Default Wait Time</label>

        <div class="form-inline col-md-12">
            <div class="form-group">
                <input type="number" class="form-control" name="default_wait_time_hr" min="0" max="23"></input>
                <label for="default_wait_time_hr" class="control-label inline-label">hour</label>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="default_wait_time_min" min="0" max="23"></input>
                <label for="default_wait_time_min" class="control-label inline-label">min</label>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="default_wait_time_sec" min="0" max="23"></input>
                <label for="default_wait_time_sec" class="control-label inline-label">sec</label>
            </div>

            @if ($errors->has('default_wait_time'))
                <span class="help-block">
                    <strong>{{ $errors->first('default_wait_time') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('system_wait_time') ? ' has-error' : '' }}">
        <label for="system_wait_time" class="col-md-12 control-label">System Wait Time</label>

        <div class="form-inline col-md-12">
            <div class="form-group">
                <p type="text" class="form-control" name="default_wait_time_hr">-</p>
                <label for="default_wait_time_hr" class="control-label inline-label">hour</label>
            </div>
            <div class="form-group">
                <p type="text" class="form-control" name="default_wait_time_min">-</p>
                <label for="default_wait_time_min" class="control-label inline-label">min</label>
            </div>
            <div class="form-group">
                <p type="text" class="form-control" name="default_wait_time_sec">-</p>
                <label for="default_wait_time_sec" class="control-label inline-label">sec</label>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-sm">Set to default</button>
            </div>

            @if ($errors->has('system_wait_time'))
                <span class="help-block">
                    <strong>{{ $errors->first('system_wait_time') }}</strong>
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
