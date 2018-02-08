@extends('layouts.app')

@section('title', '| Counter')

@section('content')

<div class="container">
    <h3>Ticket Queue</h3>

    <div class="col-sm-12"> 
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" href="#staff" role="tab" data-toggle="tab">Customer Service</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#admin" role="tab" data-toggle="tab">Loan Enquiry</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#superAdmin" role="tab" data-toggle="tab">Other Services</a>
          </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade show active" id="staff">
              
                <table id="staffTable" class="table table-bordered dataTable" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="sorting" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Username</th>
                            <th class="sorting th-email" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Email</th>
                            <th class="sorting th-branch" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Branch</th>
                            <th class="sorting th-password" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Password</th>
                            <th class="sorting th-action" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    {{-- @foreach ($users as $user)
                        @if ($user->role_id == 2)
                        <tr>
                            <td><div>{{ $user->username }}</div></td>
                            <td class="td-email"><div>{{ $user->email }}</div></td>
                            <td class="td-branch"><div>{{ $user->branch }}</div></td>
                            <td class="td-password"><div>{{ $user->password }}</div></td>
                            <td class="td-action">
                                <a href="#" class="btn btn-secondary btn-sm" id="btnEditStaff"><span>Edit</span></a>
                                <a href="#" class="btn btn-danger btn-sm" id="btnDeleteStaff"><span>Delete</span></a>
                            </td>
                        </tr>
                         @endif
                    @endforeach --}}
                    </tbody>
                </table>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="admin">

                <button class="btn btn-info btn-add" id="btnAddAdmin" data-target="#registerAdminModal"><span>Add Admin</span></button>

                <table id="adminTable" class="table table-bordered dataTable" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="sorting" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Username</th>
                            <th class="sorting th-email" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Email</th>
                            <th class="sorting th-branch" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Branch</th>
                            <th class="sorting th-password" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Password</th>
                            <th class="sorting th-action" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    {{-- @foreach ($users as $user)
                        @if ($user->role_id == 1)
                        <tr>
                             <td><div>{{ $user->username }}</div></td>
                            <td class="td-email"><div>{{ $user->email }}</div></td>
                            <td class="td-branch"><div>{{ $user->branch }}</div></td>
                            <td class="td-password"><div>{{ $user->password }}</div></td>
                            <td class="td-action">
                                <a href="#" class="btn btn-secondary btn-sm" id="btnEditStaff"><span>Edit</span></a>
                                <a href="#" class="btn btn-danger btn-sm" id="btnDeleteStaff"><span>Delete</span></a>
                            </td>
                            </td>
                        </tr>
                        @endif
                    @endforeach --}}
                    </tbody>
                </table>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="superAdmin">
                <table id="superAdminTable" class="table table-bordered dataTable" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="sorting" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Username</th>
                            <th class="sorting th-email" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Email</th>
                            <th class="sorting th-password" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Password</th>
                            <th class="sorting th-action" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    {{-- @foreach ($users as $user)
                        @if ($user->role_id == 0)
                        <tr>
                            <td><div>{{ $user->username }}</div></td>
                            <td class="td-email"><div>{{ $user->email }}</div></td>
                            <td class="td-password"><div>{{ $user->password }}</div></td>
                            <td class="td-action">
                                <a href="#" class="btn btn-secondary btn-sm" id="btnEditSuperAdmin"><span>Edit</span></a>
                            </td>
                        </tr>
                        @endif
                    @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
