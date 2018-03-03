<div class="card margin-bottom-15">
    <div class="card-body">
        @if(!$branchCounters->isEmpty())
            {!! Form::open(['route' => ['call.openCounter'], 'method' => 'POST', 'class'=>'form-horizontal']) !!}

                <input type="hidden" class="form-control" name="user_id" value="{{ $user->id }}">

                <div class="form-group{{ $errors->has('counter') ? ' has-error' : '' }}">
                    <label for="branchCounter" class="col-md-8 control-label">Serving counter:</label>

                    <div class="col-md-11">
                        <select class="form-control" name="branchCounter">
                            @foreach ($branchCounters as $branchCounter)
                                <option value={{ $branchCounter->id }} {{ $branchCounter->staff_id == null ? '' : 'disabled'}}>
                                    {{ $branchCounter->counter->name }} ({{ $branchCounter->counter->code }})
                                </option>
                            @endforeach
                        </select>

                        @if ($errors->has('branchCounter'))
                            <span class="help-block">
                                <strong>{{ $errors->first('branchCounter') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            Open Counter
                        </button>
                        </a>
                    </div>
                </div>
            {!! Form::close() !!}
        @else
        <div><p>No counter in this branch.</p></div>
        @endif
    </div>
</div>