<div class="card margin-bottom-15">
    <div class="card-body">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-5">Counter Now :</div>

                <div class="col-md-auto"><strong>{{ $user->branchCounter->counter->name }} ({{ $user->branchCounter->counter->code }})</strong></div>

                <div class="col-md-4">
                    {!! Form::open(['route' => ['call.closeCounter', $user->branchCounter->id], 'method' => 'POST']) !!}
                        {!! Form::submit('Close Counter', ['class' => 'btn btn-outline-danger']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>