@extends('layouts.app')

@section('title', '| Configurations')

@section('content')

<div class="row">   
    <div class="col-md-5 offset-1 div-table">
        <div class="row">
            <h3>Branch</h3>
        </div>
        
        <div class="row">
            <button class="btn btn-info btn-add" id="btnAddBranch" data-target="#addBranchModal"><span>Add Branch</span></button>

            <table id="branchTable" class="table table-responsive-md" cellspacing="0">
                <thead>
                    <tr>
                        <th>Branch</th>
                        <th>Service</th>
                        <th>Avg Wait Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Kepong</td>
                        <td>Customer Service</td>
                        <td>35 min</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-5 offset-1 div-table">
        <div class="row">
            <h3>Service</h3>
        </div>
        
        <div class="row">
            <button class="btn btn-info btn-add" id="btnAddService" data-target="#addServiceModal"><span>Add Service</span></button>

            <table id="serviceTable" class="table table-responsive-md" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Service Name</th>
                    </tr>
                </thead>
                <tbody>
                {{-- @foreach ($services as $service) --}}
                    <tr>
                        <td>S1</td>
                        <td>Customer Service</td>
                    </tr>
                </tbody>
                {{-- @endforeach --}}
            </table>
        </div>
    </div>
</div>
@endsection
