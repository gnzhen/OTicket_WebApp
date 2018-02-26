<div class="card">
    <div class="card-header" style="font-size: 20px;">{{ $queue->branchService->service->name }}</div>
    <div class="card-body">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">Counter Now :</div>

                <div class="col-md-auto"><strong>{{ $user->branchCounter->counter->name }} ({{ $user->branchCounter->counter->code }})</strong></div>

                <div class="col-md-4">
                    {!! Form::open(['route' => ['call.destroy', $user->branchCounter->id], 'method' => 'DELETE']) !!}
                        {!! Form::submit('Close Counter', ['class' => 'btn btn-outline-danger']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="col-md-12 margin-bottom-15">
            <div class="row">
                <div class="col-md-4">Serving Duration :</div>

                <div class="col-md-auto"><strong id="timer{{ $queue->id }}">-</strong></div>
            </div>
        </div>

        {{-- <div class="col-md-12 margin-bottom-15">
            <div class="row">
                <div class="col-md-4">Status :</div>

                <div class="col-md-auto" id="status" style="color:red;"><strong>-</strong></div>
            </div>
        </div> --}}

        <div class="col-md-12 margin-bottom-10">
            {!! Form::open(['route'=>['calling.store']]) !!}
                <button type="submit" id="btnCallNext" data-id="{{ $queue->id }}" class="btn btn-primary btn-block btn-lg {{ $user->branchCounter->status == 'ready' ? '' : 'disabled' }}">Call Next</button>
            {!! Form::close() !!}
        </div>

        <div class="col-md-12 margin-bottom-10">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::open(['route'=>['calling.store']]) !!}
                        <button type="submit" id="btnRecall" data-id="{{ $queue->id }}" class="btn btn-success btn-block {{ $user->branchCounter->status == 'serving' ? '' : 'disabled' }}">Recall</button>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-6">
                    {!! Form::open(['route'=>['calling.store']]) !!}
                        <button type="submit" id="btnSkip" data-id="{{ $queue->id }}" class="btn btn-secondary btn-block {{ $user->branchCounter->status == 'serving' ? '' : 'disabled' }}">Skip</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="col-md-12 margin-bottom-10">
            {!! Form::open(['route'=>['calling.store']]) !!}
                <button type="submit" id="btnDone" data-id="{{ $queue->id }}" class="btn btn-dark btn-block btn-lg {{ $user->branchCounter->status == 'serving' ? '' : 'disabled' }}">Done</button>
            {!! Form::close() !!}
        </div>
    </div>
</div>