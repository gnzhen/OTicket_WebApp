@extends('layouts.app')

@section('title', '| Configurations')

@section('content')

<h3>Configurations</h3>

<div class="row row-space">
    <h4>Branch</h4>

    <button class="btn btn-info btn-sm btn-add" id="btnAddBranch" data-target="#addBranchModal"><span>Add Branch</span></button>

    <table id="branchTable" class="table table-bordered dataTable" cellspacing="0">
        <thead>
            <tr>
                <th>Id</th>
                <th>Branch Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @for ($i = 0; $i < 100; $i++)
        {{-- @foreach ($branches as $branch) --}}
            <tr>
                <td>B1</td>
                <td>Kepong</td>
                <td>20, JLN Kepong 4/7, Kepong Ulu</td>
                <td class="td-action">
                    <a href="#" class="btn btn-secondary btn-sm" id="btnEditStaff"><span>Edit</span></a>
                    <a href="#" class="btn btn-danger btn-sm" id="btnDeleteStaff"><span>Delete</span></a>
                </td>
            </tr>
        {{-- @endforeach --}}
        @endfor
        </tbody>
    </table>
</div>

<div class="row row-space">   
    <h4>Service</h4>

    <button class="btn btn-info btn-sm btn-add" id="btnAddService" data-target="#addServiceModal"><span>Add Service</span></button>

    <table id="serviceTable" class="table table-bordered dataTable" cellspacing="0">
        <thead>
            <tr>
                <th>Id</th>
                <th>Service Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @for ($i = 0; $i < 100; $i++)
        {{-- @foreach ($branches as $branch) --}}
            <tr>
                <td>S1</td>
                <td>Customer Service</td>
                <td class="td-action">
                    <a href="#" class="btn btn-secondary btn-sm" id="btnEditStaff"><span>Edit</span></a>
                    <a href="#" class="btn btn-danger btn-sm" id="btnDeleteStaff"><span>Delete</span></a>
                </td>
            </tr>
        {{-- @endforeach --}}
        @endfor
        </tbody>
    </table>
</div>

<div class="row row-space">
    <h4>Counter</h4>

    <button class="btn btn-info btn-sm btn-add" id="btnAddCounter" data-target="#addCounterModal"><span>Add Counter</span></button>

     <table id="counterTable" class="table table-bordered dataTable" cellspacing="0">
        <thead>
            <tr>
                <th>Id</th>
                <th>Counter Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        {{-- @foreach ($branches as $branch) --}}
        @for ($i = 0; $i < 10; $i++)
            <tr>
                <td>C1</td>
                <td>Counter 1</td>
                <td class="td-action">
                    <a href="#" class="btn btn-secondary btn-sm" id="btnEditStaff"><span>Edit</span></a>
                    <a href="#" class="btn btn-danger btn-sm" id="btnDeleteStaff"><span>Delete</span></a>
                </td>
            </tr>
        {{-- @endforeach --}}
        @endfor
        </tbody>
    </table>
</div>

<div class="row row-space">
    <h4>Branch Service</h4>
    
    <table id="branchServiceTable" class="table table-bordered dataTable" cellspacing="0">
        <thead>
            <tr>
                <th>Branch</th>
                <th>Service</th>
                <th>Counter</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @for ($i = 0; $i < 10; $i++)
        {{-- @foreach ($services as $service) --}}
            <tr>
                <td>Ampang</td>
                <td>
                    <select multiple class="btn" name="role_id">
                        <option>Customer Service</option>
                        <option>Deposit</option>
                        <option>Other serevices</option>
                        <option>Customer Service</option>
                        <option>Deposit</option>
                        <option>Other serevices</option>
                    </select>
                    <button class="btn btn-info btn-sm btn-add" id="btnAddCounter" data-target="#addCounterModal"><span>Add</span></button>
                </td>
                <td>
                    <select multiple class="btn" name="role_id">
                        <option>Counter 1</option>
                        <option>Counter 2</option>
                        <option>Counter 3</option>
                        <option>Counter 1</option>
                        <option>Counter 2</option>
                        <option>Counter 3</option>
                    </select>
                    <button class="btn btn-info btn-sm btn-add" id="btnAddCounter" data-target="#addCounterModal"><span>Add</span></button>
                </td>
                <td class="td-action">
                    <a href="#" class="btn btn-secondary btn-sm" id="btnEditStaff"><span>Edit</span></a>
                    <a href="#" class="btn btn-danger btn-sm" id="btnDeleteStaff"><span>Delete</span></a>
                </td>
            </tr>
        @endfor
        {{-- @endforeach --}}
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
