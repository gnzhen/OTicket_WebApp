@extends('layouts.app')

@section('title', '| Configurations')

@section('content')

<h3>Configurations</h3>

<div class="row row-space">
    <h4>Branch</h4>

    <button class="btn btn-info btn-sm btn-add" id="btnAddBranch" data-target="#addBranchModal"><span>Add Branch</span></button>
    
    <div class="col-md-10">
        <table id="branchTable" class="table table-bordered dataTable" cellspacing="0">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Branch Name</th>
                    <th>Description</th>
                    <th class="th-action">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($branches as $branch)
                <tr>
                    <td>{{ $branch->branch_id }}</td>
                    <td>{{ $branch->name }}</td>
                    <td>{{ $branch->desc }}</td>
                    <td class="td-action">
                        <a href="#" class="btn btn-secondary btn-sm" id="btnEditStaff"><span>Edit</span></a>
                        <a href="#" class="btn btn-danger btn-sm" id="btnDeleteStaff"><span>Delete</span></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row row-space">   
    <h4>Service</h4>

    <button class="btn btn-info btn-sm btn-add" id="btnAddService" data-target="#addServiceModal"><span>Add Service</span></button>

    <div class="col-md-10">
        <table id="serviceTable" class="table table-bordered dataTable" cellspacing="0">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Service Name</th>
                    <th class="th-action">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($services as $service)
                <tr>
                    <td>{{ $service->service_id }}</td>
                    <td>{{ $service->name }}</td>
                    <td class="td-action">
                        <a href="#" class="btn btn-secondary btn-sm" id="btnEditStaff"><span>Edit</span></a>
                        <a href="#" class="btn btn-danger btn-sm" id="btnDeleteStaff"><span>Delete</span></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row row-space">
    <h4>Counter</h4>

    <button class="btn btn-info btn-sm btn-add" id="btnAddCounter" data-target="#addCounterModal"><span>Add Counter</span></button>

    <div class="col-md-10">
        <table id="counterTable" class="table table-bordered dataTable" cellspacing="0">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Counter Name</th>
                    <th class="th-action">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($counters as $counter)
                <tr>
                    <td>{{ $counter->counter_id }}</td>
                    <td>{{ $counter->name }}</td>
                    <td class="td-action">
                        <a href="#" class="btn btn-secondary btn-sm" id="btnEditStaff"><span>Edit</span></a>
                        <a href="#" class="btn btn-danger btn-sm" id="btnDeleteStaff"><span>Delete</span></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row row-space">
    <h4>Branch Counter</h4>

    <table id="branchCounterTable" class="table table-bordered dataTable" cellspacing="0">
        <thead>
            <tr>
                <th>Branch</th>
                <th>Counter</th>
                <th class="th-action">Action(Counter)</th>
                <th class="th-action">Action(Branch)</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($branchCounters as $branchCounter)
            <tr>
                <td>{{ $branchCounter->branch_name }} ({{ $branchCounter->branch_id }})</td>
                <td>{{ $branchCounter->counter_name }} ({{ $branchCounter->counter_id }})
                    {{-- <select multiple="multiple" id="multiSelectBranchCoutner" name="multiSelect[]">
                        @foreach ($branchCounters as $branchCounter)
                        @if($branch->branch_id == $branchCounter->branch_id)
                        <option value='{{ $branchCounter->counter_id }}'>
                            {{ $branchCounter->counter_name }} ({{ $branchCounter->counter_id }})
                        </option>
                        @endif
                        @endforeach
                    </select> --}}
                </td>
                <td class="td-action">
                    <a href="#" class="btn btn-danger btn-sm" id="btnDeleteBranchCounter"><span>Delete</span></a>
                </td>
                <td class="td-action">
                    <a href="#" class="btn btn-info btn-sm" id="btnAddBranchCounter"><span>Add Counter to Branch</span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{-- @foreach($branchServices as $branchService)
<p>{{ $branchService }}</p>
@endforeach --}}

<div class="row row-space">
    <h4>Branch Service</h4>

    <table id="branchServiceTable" class="table table-bordered dataTable" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th rowspan="2">Branch</th>
                <th colspan="3">Service</th>
                <th rowspan="2" class="th-action">Action (Service)</th>
                <th rowspan="2" style="max-width:200px;"">Action (Branch)</th>
            </tr>
            <tr>
                <th>Name</th>
                <th>Default time</th>
                <th>System time</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($branchServices as $branchService)
            <tr>
                <td>
                    {{ $branchService->branch_name }} 
                    ({{ $branchService->branch_id }})
                </td>
                <td>
                    {{ $branchService->service_name }} 
                    ({{ $branchService->service_id }})
                </td>
                <td>
                    {{ $appController->secToString($branchService->default_avg_wait_time) }}
                </td>
                <td>
                    {{ $branchService->avg_wait_time == 'null' ? ($appController->secToString($branchService->avg_wait_time)) : '-' }}
                </td>
                <td class="td-action">
                    <a href="#" class="btn btn-secondary btn-sm" id="btnEditBranchService"><span>Edit</span></a>
                    <a href="#" class="btn btn-danger btn-sm" id="btnDeleteBranchService"><span>Delete</span></a>
                </td>
                <td class="td-action">
                    <a href="#" class="btn btn-info btn-sm" id="btnAddBranchService"><span>Add Service to Branch</span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


<div class="row row-space">
    <div class="container-fluid">
        <h4>Settings</h4>

        <div class="col-md-6">
            <form class="form" method="POST" action="">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('QRScanner') ? ' has-error' : '' }} row">
                    <label for="QRScanner" class="col-sm-4 col-form-label">QR Scanner</label>
                    <div class="col-md-8">
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>

                        @if ($errors->has('QRScanner'))
                            <span class="help-block">
                                <strong>{{ $errors->first('QRScanner') }}</strong>
                            </span>
                        @endif

                        <small class="text-muted">Enable/Disable QR Code scanner on mobile app.</small>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('maxPostpone') ? ' has-error' : '' }} row">
                    <label for="maxPostpone" class="col-sm-4 col-form-label" data-placement="top">Max Postpone</label>

                    <div class="col-md-8">
                        <input id="maxPostpone" type="maxPostpone" class="form-control" name="maxPostpone" required>

                        @if ($errors->has('maxPostpone'))
                            <span class="help-block">
                                <strong>{{ $errors->first('maxPostpone') }}</strong>
                            </span>
                        @endif

                        <small class="text-muted">Maximum times a ticket can be postponed.</small>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('maxTicket') ? ' has-error' : '' }} row">
                    <label for="maxTicket" class="col-sm-4 col-form-label">Max Ticket</label>

                    <div class="col-md-8">
                        <input id="maxTicket" type="maxTicket" class="form-control" name="maxTicket" required>

                        @if ($errors->has('maxTicket'))
                            <span class="help-block">
                                <strong>{{ $errors->first('maxTicket') }}</strong>
                            </span>
                        @endif

                        <small class="text-muted">Maximum number of tickets allowed per customer at one time.</small>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection
