@extends('layouts.app')

@section('title', '| Counter')

@section('content')

<div class="container">
    <h3>Ticket Queue</h3>

    @if(!$branchServices->isEmpty())
    <div class="col-md-12"> 
        <ul class="nav nav-tabs" role="tablist">
            @foreach($queues as $i => $queue)
            <li class="nav-item">
                <a class="nav-link {{ $i == 0 ? 'active' : '' }}" href="#{{ $queue->id }}" role="tab" data-toggle="tab">{{ $queue->branchService->service->name }}</a>
            </li>
            @endforeach
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            @foreach($queues as $i => $queue)
            <div role="tabpanel" class="tab-pane fade {{ $i == 0 ? 'show active' : '' }}" id="{{ $queue->id }}">
              
                <div class="row">
                    <div class="col-md-5 hideSearch hidePageOf">
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

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <form class="form-horizontal" method="POST" action="">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('counter') ? ' has-error' : '' }}">
                                        <label for="username" class="col-md-8 control-label">Serving counter:</label>

                                        <div class="col-md-11">

                                            <select id="multiSelectBranchCoutner" class="form-control" name="counter">
                                                @foreach ($branchCounters as $branchCounter)
                                                    <option value='{{ $branchCounter->id }}'>
                                                        {{ $branchCounter->counter->name }} ({{ $branchCounter->counter->code }})
                                                    </option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('counter'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('counter') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary">
                                                Start Serving
                                            </button>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @endforeach
        </div>
    </div>
    @else
    <div>
        <p>No ticket queue to be displayed.</p>
    </div>
    @endif
</div>
@endsection
