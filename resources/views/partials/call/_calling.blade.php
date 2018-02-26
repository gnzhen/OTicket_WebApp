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

        {{-- <div class="col-md-12 margin-bottom-15">
            <div class="row">
                <div class="col-md-4">Status :</div>

                <div class="col-md-auto" id="status" style="color:red;"><strong>-</strong></div>
            </div>
        </div> --}}

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
                    {!! Form::open(['route'=>['calling.store']]) !!}
                        <input type="hidden" name="queue_id" value="{{ $queue->id }}">
                        <button type="submit" id="btnRecall" data-id="{{ $queue->id }}" class="btn btn-success btn-block {{ $user->branchCounter->serving_queue == $queue->id  ? '' : 'disabled' }}">Recall</button>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-6">
                    {!! Form::open(['route'=>['calling.store']]) !!}
                        <input type="hidden" name="queue_id" value="{{ $queue->id }}">
                        <button type="submit" id="btnSkip" data-id="{{ $queue->id }}" class="btn btn-secondary btn-block {{ $user->branchCounter->serving_queue == $queue->id ? '' : 'disabled' }}">Skip</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="col-md-12 margin-bottom-10">
            {!! Form::open(['route'=>['calling.store']]) !!}
                <input type="hidden" name="queue_id" value="{{ $queue->id }}">
                <button type="submit" id="btnDone" data-id="{{ $queue->id }}" class="btn btn-dark btn-block btn-lg {{ $user->branchCounter->serving_queue == $queue->id ? '' : 'disabled' }}">Done</button>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@section('javascript')
    @if($user->branchCounter->serving_queue != null)
        <script type="text/javascript">
            $.noConflict();

            jQuery( document ).ready(function( $ ) {

                /* Timer */
                var count = 0;
                var timer;

                startTimer({{ $queue->id }});

                function startTimer(id) {
                    count ++;
                    $('#timer'+id).text(secToStr(count));
                    timer = setTimeout(function(){ startTimer(id) }, 1000);
                }

                function stopTimer(){
                    clearTimeout(timer);
                    count = 0;
                }

                function secToStr(sec){
                    var hours = Math.floor(sec / 3600);
                    var minutes = Math.floor((sec / 60) % 60);
                    var seconds = sec % 60;

                    if(hours < 10)
                        hours = '0' + hours;
                    if(minutes < 10)
                        minutes = '0' + minutes;
                    if(seconds < 10)
                        seconds = '0' + seconds;
              
                    return hours + ':' + minutes + ':' + seconds;
                }
            });
        </script>

    @endif
@endsection
