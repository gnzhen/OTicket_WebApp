<form class="form-horizontal" method="POST" action="{{ route('branchCounter.store') }}" >
    {{ csrf_field() }}

    <div class="form-group">
        <label for="code" class="col-md-12 control-label">Branch</label>

        <div class="col-md-12">
            <input type="hidden" class="form-control" name="branch_id" value="{{ $branch->id }}">
            <input type="text" class="form-control" value="{{ $branch->name }}({{ $branch->code }})" disabled>
        </div>
    </div>

    <div class="form-group{{ $errors->has('counter_id') ? ' has-error' : '' }}">
        <label for="counter_id" class="col-md-12 control-label">Counters</label>

        <div class="col-md-6">
            <select id="multiSelectBranchCoutner" class="form-control" name="counter_id">
                @foreach ($counters as $counter)
                {{-- @if($counter->id == $branchCounter->branch_id) --}}
                <option value='{{ $counter->id }}'>
                    {{ $counter->name }} ({{ $counter->code }})
                </option>
                {{-- @endif --}}
                @endforeach
            </select>

            @if ($errors->has('counter_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('counter_id') }}</strong>
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
