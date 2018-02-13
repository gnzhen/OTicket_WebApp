{!! Form::model($branch, ['route' => ['branch.updateCounter', $branch->id], 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        <label for="code" class="col-md-12 control-label">Branch</label>

        <div class="col-md-12">
            <input type="hidden" class="form-control" name="branch_id" value="{{ $branch->id }}">
            <input type="text" class="form-control" value="{{ $branch->name }}({{ $branch->code }})" disabled>
        </div>
    </div>

    <div class="form-group{{ $errors->has('counters') ? ' has-error' : '' }}">
        <label for="counter_id" class="col-md-12 control-label">Counters</label>

        <div class="col-md-6">
            @foreach($counters as $counter)
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="counters[]" value="{{ $counter->id }}" {{ $branch->counters->contains($counter->id) ? "checked" : "" }}>
                    {{ $counter->name }} ({{ $counter->code }})
                </label>
            </div>
            @endforeach

            {{-- @foreach($branch->counters as $counter)
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" value="{{ $counter->id }}">{{ $counter->name }} ({{ $counter->code }})
                </label>
            </div>
            @endforeach --}}
            {{-- <input name="agree" type="checkbox" value="yes"> --}}
            {{-- <select id="multiSelectBranchCoutner" class="form-control" name="counter_id">
                @foreach ($counters as $counter)
                <option value='{{ $counter->id }}'>
                    {{ $counter->name }} ({{ $counter->code }})
                </option>
                @endforeach
            </select> --}}

            @if ($errors->has('counters'))
                <span class="help-block">
                    <strong>{{ $errors->first('counters') }}</strong>
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
