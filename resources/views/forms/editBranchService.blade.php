{!! Form::model($branchService, ['route' => ['branch.updateService', $branchService->id], 'class' => 'form-horizontal']) !!}
    {{ csrf_field() }}

    <div class="form-group">
        <label for="code" class="col-md-12 control-label">Branch</label>

        <div class="col-md-12">
            <input type="hidden" class="form-control" name="branch" value="{{ $branchService->branch_id }}">
            <input type="text" class="form-control" value="{{ $branchService->branch_name }} ({{ $branchService->branch_code }})" disabled>
        </div>
    </div>

    <div class="form-group{{ $errors->has('service') ? ' has-error' : '' }}">
        <label for="service" class="col-md-12 control-label">Services</label>

        <div class="col-md-12">
            <input type="hidden" class="form-control" name="service" value="{{ $branchService->service_id }}">
            <input type="text" class="form-control" value="{{ $branchService->service_name }} ({{ $branchService->service_code }})" disabled>

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
                <input type="number" class="form-control" name="default_wait_time_hr" min="0" max="23" value="{{ $waitTimeArray[$branchService->id]['default_wait_time_hr'] }}"></input>
                <label for="default_wait_time_hr" class="control-label inline-label">hour</label>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="default_wait_time_min" min="0" max="59" value="{{ $waitTimeArray[$branchService->id]['default_wait_time_min'] }}"></input>
                <label for="default_wait_time_min" class="control-label inline-label">min</label>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="default_wait_time_sec" min="0" max="59" value="{{ $waitTimeArray[$branchService->id]['default_wait_time_sec'] }}"></input>
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
                <input type="number" class="form-control" name="system_wait_time_hr" id="systemWaitTimeHr" value="{{ $waitTimeArray[$branchService->id]['system_wait_time_hr'] }}" disabled></input>
                <label for="system_wait_time_hr" class="control-label inline-label">hour</label>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="system_wait_time_min" id="systemWaitTimeMin" value="{{ $waitTimeArray[$branchService->id]['system_wait_time_min'] }}" disabled></input>
                <label for="system_wait_time_min" class="control-label inline-label">min</label>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="system_wait_time_sec" id="systemWaitTimeSec" value="{{ $waitTimeArray[$branchService->id]['system_wait_time_sec'] }}" disabled></input>
                <label for="system_wait_time_sec" class="control-label inline-label">sec</label>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-outline-light text-dark" id="btnResetSystemWaitTime">Reset</button>
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
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
