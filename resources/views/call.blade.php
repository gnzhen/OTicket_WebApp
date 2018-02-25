@extends('layouts.app')

@section('title', '| Counter')

@section('content')

<div class="container">
    <h3>Ticket Queue</h3>

    @if(!$branchServices->isEmpty())
    <div class="col-md-5">

        @if($user->branchCounter == null)
        <div class="card margin-bottom-15">
            <div class="card-body">
                {!! Form::model($user, ['route' => ['call.update', $user->id], 'method' => 'PUT', 'class'=>'form-horizontal']) !!}

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
            </div>
        </div>
        @endif
    </div>

        @if(!$queues->isEmpty())
        <div class="col-md-12"> 
            <ul class="nav nav-tabs" role="tablist">
                @foreach($queues as $i => $queue)
                <li class="nav-item">
                    <a class="nav-link nav-link-{{ $i }} {{ $appController->getTabSession() == $queue->id ? 'active' : '' }}" href="#tab{{ $queue->id }}" role="tab" data-toggle="tab" onmousedown="setTabSession({{ $queue->id }})">{{ $queue->branchService->service->name }}</a>
                </li>
                @endforeach
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                @foreach($queues as $i => $queue)
                <div role="tabpanel" class="tab-pane tab-pane-{{ $i }} fade {{ ($appController->getTabSession() == $queue->id) ? 'show active' : '' }}" id="tab{{ $queue->id }}">
                  
                    <div class="row">
                        <div class="{{ $user->branchCounter != null ? 'col-md-5' : 'col-md-12' }} hideEntries hideSearch hidePageOf">
                            <table class="table table-bordered dataTable" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="sorting" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Ticket Number</th>
                                        <th class="sorting" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Wait Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($queue->tickets as $ticket)
                                    <tr>
                                        <td><div>{{ $ticket->ticket_no }}</div></td>
                                        <td><div>{{ $appController->secToString($ticket->wait_time) }}</div></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($user->branchCounter != null)
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">{{ $queue->branchService->service->name }}</div>
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
                                            <div class="col-md-4">Serving Time :</div>

                                            <div class="col-md-auto"><strong>36 sec</strong></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 margin-bottom-15">
                                        <div class="row">
                                            <div class="col-md-4">Status :</div>

                                            <div class="col-md-auto" id="status" style="color:red;"><strong>-</strong></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 margin-bottom-10">
                                        {!! Form::open(['route'=>['calling.store']]) !!}
                                            <button type="submit" id="btnCallNext" class="btn btn-primary btn-block btn-lg {{ $user->branchCounter->status == 'ready' ? '' : 'disabled' }}">Call Next</button>
                                        {!! Form::close() !!}
                                    </div>

                                    <div class="col-md-12 margin-bottom-10">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::open(['route'=>['calling.store']]) !!}
                                                    <button type="submit" id="btnRecall" class="btn btn-success btn-block {{ $user->branchCounter->status == 'serving' ? '' : 'disabled' }}">Recall</button>
                                                {!! Form::close() !!}
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::open(['route'=>['calling.store']]) !!}
                                                    <button type="submit" id="btnSkip" class="btn btn-secondary btn-block {{ $user->branchCounter->status == 'serving' ? '' : 'disabled' }}">Skip</button>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 margin-bottom-10">
                                        {!! Form::open(['route'=>['calling.store']]) !!}
                                            <button type="submit" id="btnDone" class="btn btn-dark btn-block btn-lg {{ $user->branchCounter->status == 'serving' ? '' : 'disabled' }}">Done</button>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
                @endforeach
            </div>
        </div>
        @else
        <div>
            <p>All queues are empty.</p>
        </div>
        @endif
    @else
    <div>
        <p>This user does not belongs to any branch.</p>
    </div>
    @endif
</div>
@endsection

@section('javascript')

    {{-- Set first tab active if no previous active tab --}}
    @if(!Session::has('tab'))
        <script type="text/javascript">
            $.noConflict();

            jQuery( document ).ready(function( $ ) {
                $('.nav-link-0').addClass('active');
                $('.tab-pane-0').addClass('active');
                $('.tab-pane-0').addClass('show');
            });
        </script>
    @endif
@endsection