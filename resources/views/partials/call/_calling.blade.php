<div class="card">
    <div class="card-header" style="font-size: 20px;">{{ $queue->branchService->service->name }}</div>
    <div class="card-body">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">Counter Now :</div>

                <div class="col-md-auto"><strong>{{ $user->branchCounter->counter->name }} ({{ $user->branchCounter->counter->code }})</strong></div>

                <div class="col-md-4">
                    {!! Form::open(['route' => ['call.closeCounter', $user->branchCounter->id], 'method' => 'POST']) !!}
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

        @if($calling != null)
        <div class="col-md-12 margin-bottom-15">
            <div class="row">
                <div class="col-md-4" style="margin-top: auto">Serving Ticket:</div>

                <div class="col-md-auto" id="ticket" style="font-size: 30px"><strong>{{ $user->branchCounter->serving_queue == $queue->id ? $calling->ticket->ticket_no : '-' }}</strong></div>
            </div>
        </div>
        @endif

        <div class="col-md-12 margin-bottom-10">
            {!! Form::open(['route'=>['call.call']]) !!}
                <input type="hidden" name="queue_id" value="{{ $queue->id }}">
                <input type="hidden" name="branch_counter_id" value="{{ $user->branchCounter->id }}">
                <button type="submit" id="btnCallNext" data-id="{{ $queue->id }}" class="btn btn-primary btn-block btn-lg {{ $user->branchCounter->serving_queue == null ? '' : 'disabled' }}">Call Next</button>
            {!! Form::close() !!}
        </div>

        <div class="col-md-12 margin-bottom-10">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::open(['route'=>['call.recall']]) !!}
                        <input type="hidden" name="queue_id" value="{{ $queue->id }}">
                        <input type="hidden" name="branch_counter_id" value="{{ $user->branchCounter->id }}">
                        @if($calling != null)
                            <input type="hidden" name="calling_id" value="{{ $calling->id }}">
                        @endif
                        <button type="submit" id="btnRecall" data-id="{{ $queue->id }}" class="btn btn-success btn-block {{ $user->branchCounter->serving_queue == $queue->id && $calling != null ? '' : 'disabled' }}">Recall</button>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-6">
                    {!! Form::open(['route'=>['call.skip']]) !!}
                        <input type="hidden" name="queue_id" value="{{ $queue->id }}">
                        <input type="hidden" name="branch_counter_id" value="{{ $user->branchCounter->id }}">
                         @if($calling != null)
                            <input type="hidden" name="calling_id" value="{{ $calling->id }}">
                        @endif
                        <button type="submit" id="btnSkip" data-id="{{ $queue->id }}" class="btn btn-secondary btn-block {{ $user->branchCounter->serving_queue == $queue->id ? '' : 'disabled' }}">Skip</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="col-md-12 margin-bottom-10">
            {!! Form::open(['route'=>['call.done']]) !!}
                <input type="hidden" name="queue_id" value="{{ $queue->id }}">
                <input type="hidden" name="branch_counter_id" value="{{ $user->branchCounter->id }}">
                @if($calling != null)
                    <input type="hidden" name="calling_id" value="{{ $calling->id }}">
                @endif
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <button type="submit" id="btnDone" data-id="{{ $queue->id }}" class="btn btn-dark btn-block btn-lg {{ $user->branchCounter->serving_queue == $queue->id ? '' : 'disabled' }}">Done</button>
            {!! Form::close() !!}
        </div>
    </div>
</div>

