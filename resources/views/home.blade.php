@extends('layouts.app')

@section('title', '| Home')

@section('content')

<div class="container">
    <div class="row">   
        <div class="col-md-4 offset-1 div-dashboard">
            <div class="row">   
                <div class="card card-dashboard">
                    {!! Form::open(['route'=>['app.generateSysWaitTime']]) !!}
                        <button type="submit" class="btn btn-outline-secondary btn-block">Refresh</button>
                    {!! Form::close() !!}
                </div>
                <div class="card card-dashboard">
                    <div class="card-header">Avg Wait Time</div>
                    <div class="card-body">{{ $avgWaitTimeString }}</div>
                </div>
                <div class="card card-dashboard">
                    <div class="card-header">Total tickets</div>
                    <div class="card-body">{{ $totalTickets }}</div>
                </div>

                <div class="card card-dashboard">
                    <div class="card-header">Tickets yesterday</div>
                    <div class="card-body">{{ $totalTicketsYtd }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 offset-1 div-table">
            <div class="row">
                <h3>Average Wait Time</h3>
            </div>
            
            <div class="row">
                <table id="homeTable" class="table table-bordered dataTable" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Branch</th>
                            <th>Service</th>
                            <th>Avg Wait Time</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($branchServices as $branchService)
                        <tr>
                            <td>{{ $branchService->branch->name }} ({{ $branchService->branch->code }})</td>
                            <td>
                                {{ $branchService->service->name }} ({{ $branchService->service->code }}) 
                            </td>
                            <td>
                                @if($branchService->system_wait_time > 0 || $branchService->system_wait_time != null)
                                    {{ $appController->secToString($branchService->system_wait_time) }}
                                @else
                                    {{ $appController->secToString($branchService->default_wait_time) }} (default)
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
                          
        </div>
    </div>
</div>
@endsection
