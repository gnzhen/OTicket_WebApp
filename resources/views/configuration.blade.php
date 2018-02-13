<?php

use App\Branch;
use Illuminate\Support\Facades\Input;

?>

@extends('layouts.app')

@section('title', '| Configurations')

@section('content')

<div class="container">
@include('modals._config')

    <h3>Configurations</h3>

    <div class="row row-space">
        <h4>Branch</h4>

        <button class="btn btn-info btn-sm btn-add" id="btnAddBranch" data-target="#addBranchModal"><span>Add Branch</span></button>
        
        <div class="col-md-10">
            <table id="branchTable" class="table table-bordered dataTable" cellspacing="0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Branch Name</th>
                        <th>Description</th>
                        <th class="th-action">Action</th>
                    </tr>
                </thead>
                <tbody id="branch-tbody">
                @foreach ($branches as $branch)
                    <tr>
                        <td>{{ $branch->code }}</td>
                        <td>{{ $branch->name }}</td>
                        <td>{{ $branch->desc }}</td>
                        <td class="td-action">
                            <button class="btn btn-secondary btn-sm" id="btnEditBranch" data-id="{{ $branch->id }}"><span>Edit</span></button>
                            <button class="btn btn-danger btn-sm" id="btnDeleteBranch" data-id="{{ $branch->id }}"><span>Delete</span></button>
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
                        <th>Code</th>
                        <th>Service Name</th>
                        <th class="th-action">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($services as $service)
                    <tr>
                        <td>{{ $service->code }}</td>
                        <td>{{ $service->name }}</td>
                        <td class="td-action">
                            <button class="btn btn-secondary btn-sm" id="btnEditService" data-id="{{ $service->id }}"><span>Edit</span></button>
                            <button class="btn btn-danger btn-sm" id="btnDeleteService" data-id="{{ $service->id }}"><span>Delete</span></button>
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
                        <th>Code</th>
                        <th>Counter Name</th>
                        <th class="th-action">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($counters as $counter)
                    <tr>
                        <td>{{ $counter->code }}</td>
                        <td>{{ $counter->name }}</td>
                        <td class="td-action">
                            <button class="btn btn-secondary btn-sm" id="btnEditCounter" data-id="{{ $counter->id }}"><span>Edit</span></button>
                            <button class="btn btn-danger btn-sm" id="btnDeleteCounter" data-id="{{ $counter->id }}"><span>Delete</span></button>
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
                    <th class="th-action">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($branchCounters as $branchCounter)
                <tr>
                    <td>{{ $branchCounter->branch_name }} ({{ $branchCounter->branch_code }})</td>
                    <td>
                        @if( $branchCounter->counter_id != null )
                        {{ $branchCounter->counter_name }} ({{ $branchCounter->counter_code }}) 
                        @else
                        -
                        @endif
                    </td>
                    <td class="td-action">
                        <button class="btn btn-info btn-sm" id="btnEditBranchCounter" data-id="{{ $branchCounter->branch_id }}"><span>Edit Branch Counter</span></button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

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
                        ({{ $branchService->branch_code }})
                    </td>
                    <td>
                        @if( $branchService->service_id != null )
                         {{ $branchService->service_name }} ({{ $branchService->service_code }})
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        @if( $branchService->service_id != null )
                        {{ $appController->secToString($branchService->default_wait_time) }}
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        @if( $branchService->service_id != null )
                        {{ $branchService->system_wait_time != null ? ($appController->secToString($branchService->system_wait_time)) : '-' }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="td-action">
                        <button href="#" class="btn btn-secondary btn-sm" id="btnEditBranchService" data-id="{{ $branchService->id }}" {{ $branchService->service_id == null?"disabled":"" }}><span>Edit</span></button>
                        <button href="#" class="btn btn-danger btn-sm" id="btnDeleteBranchService" data-id="{{ $branchService->id }}" {{ $branchService->service_id == null?"disabled":"" }}><span>Delete</span></button>
                    </td>
                    <td class="td-action">
                        <button class="btn btn-info btn-sm" id="btnAddBranchService" data-id="{{ $branchService->branch_id }}" {{ $branchService->services_count == $service->count() ?"disabled":""}}><span>Add Service to Branch</span></button>
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
</div>
@endsection

@section('javascript')

    {{-- Re-open different modal to show error --}}
    @if( Session::has('add_branch_error'))
        <script type="text/javascript">
            $.noConflict();

            jQuery( document ).ready(function( $ ) {
                $('#addBranchModal').modal('show');
            });
        </script>
    @endif
    @if( Session::has('edit_branch_error'))
        <script type="text/javascript">
            $.noConflict();

            jQuery( document ).ready(function( $ ) {
                var id = {{ Session::get('edit_branch_error') }};
                $('#editBranchModal'+id).modal('show');
            });
        </script>
    @endif
    @if( Session::has('add_service_error'))
        <script type="text/javascript">
            $.noConflict();

            jQuery( document ).ready(function( $ ) {
                $('#addServiceModal').modal('show');
            });
        </script>
    @endif
    @if( Session::has('edit_service_error'))
        <script type="text/javascript">
            $.noConflict();

            jQuery( document ).ready(function( $ ) {
                var id = {{ Session::get('edit_service_error') }};
                $('#editServiceModal'+id).modal('show');
            });
        </script>
    @endif
    @if( Session::has('add_counter_error'))
        <script type="text/javascript">
            $.noConflict();

            jQuery( document ).ready(function( $ ) {
                $('#addCounterModal').modal('show');
            });
        </script>
    @endif
    @if( Session::has('edit_counter_error'))
        <script type="text/javascript">
            $.noConflict();

            jQuery( document ).ready(function( $ ) {
                var id = {{ Session::get('edit_counter_error') }};
                $('#editCounterModal'+id).modal('show');
            });
        </script>
    @endif
    @if( Session::has('edit_branch_counter_error'))
        <script type="text/javascript">
            $.noConflict();

            jQuery( document ).ready(function( $ ) {
                var id = {{ Session::get('edit_branch_counter_error') }};
                $('#editBranchCounterModal'+id).modal('show');
            });
        </script>
    @endif
    @if( Session::has('add_branch_service_error'))
        <script type="text/javascript">
            $.noConflict();

            jQuery( document ).ready(function( $ ) {
                var id = {{ Session::get('add_branch_service_error') }};
                $('#addBranchServiceModal'+id).modal('show');
            });
        </script>
    @endif
    @if( Session::has('edit_branch_service_error'))
        <script type="text/javascript">
            $.noConflict();

            jQuery( document ).ready(function( $ ) {
                var id = {{ Session::get('edit_branch_service_error') }};
                $('#editBranchServiceModal'+id).modal('show');
            });
        </script>
    @endif
@endsection
