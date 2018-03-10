@extends('layouts.app')

@section('title', '| Manage Users')

@section('content')

@include('modals._user')

<div class="container">

    <div class="row">   
        <div class="col-sm-12 col-md-6">    
            <h3>Manage Users</h3>
        </div> 
    </div>
        
    <div class="row row-tab">   
        <div class="col-sm-12"> 
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" href="#staff" role="tab" data-toggle="tab" id="tabStaff" data-id="0">Counter Staff</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Auth::user()->isSuperAdmin() ? "" : "disabled" }}" href="#admin" role="tab" data-toggle="tab" id="tabAdmin" data-id="1">Admin</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Auth::user()->isSuperAdmin() ? "" : "disabled" }}" href="#superAdmin" role="tab" data-toggle="tab" id="tabSuperAdmin" data-id="2">Super Admin</a>
              </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade show active" id="staff">

                    <button class="btn btn-info btn-add" id="btnAddStaff" data-target="#registerStaffModal"><span>Add Counter Staff</span></button>
                  
                    <table id="staffTable" class="table table-bordered dataTable" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Username</th>
                                <th class="sorting th-email" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Email</th>
                                <th class="sorting th-branch" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Branch</th>
                                {{-- <th class="sorting th-password" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Password</th> --}}
                                <th class="sorting th-action" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            @if ($user->role_id == 3)
                            <tr>
                                <td><div>{{ $user->username }}</div></td>
                                <td class="td-email"><div>{{ $user->email }}</div></td>
                                <td class="td-branch">
                                    <div>
                                        @if( $user->branch != null )
                                        {{ $user->branch->name }} ({{ $user->branch->code }}) 
                                        @else
                                        -
                                        @endif
                                    </div>
                                </td>
                                <td class="td-action">
                                    <button class="btn btn-secondary btn-sm" id="btnEditStaff" data-id="{{ $user->id }}"><span>Edit</span></button>
                                    <button class="btn btn-danger btn-sm" id="btnDeleteStaff" data-id="{{ $user->id }}"><span>Delete</span></button>
                                </td>
                            </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="admin">

                    <button class="btn btn-info btn-add" id="btnAddAdmin" data-target="#registerAdminModal"><span>Add Admin</span></button>

                    <table id="adminTable" class="table table-bordered dataTable" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Username</th>
                                <th class="sorting th-email" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Email</th>
                                <th class="sorting th-branch" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Branch</th>
                                {{-- <th class="sorting th-password" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Password</th> --}}
                                <th class="sorting th-action" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            @if ($user->role_id == 2)
                            <tr>
                                 <td><div>{{ $user->username }}</div></td>
                                <td class="td-email"><div>{{ $user->email }}</div></td>
                                <td class="td-branch">
                                    <div>
                                        @if( $user->branch != null )
                                        {{ $user->branch->name }} ({{ $user->branch->code }}) 
                                        @else
                                        -
                                        @endif
                                    </div>
                                </td>
                                <td class="td-action">
                                    <button class="btn btn-secondary btn-sm" id="btnEditAdmin" data-id="{{ $user->id }}"><span>Edit</span></button>
                                    <button class="btn btn-danger btn-sm" id="btnDeleteAdmin" data-id="{{ $user->id }}"><span>Delete</span></button>
                                </td>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="superAdmin">

                    <button class="btn btn-info btn-add" id="btnAddSuperAdmin" data-target="#registerSuperAdminModal"><span>Add Super Admin</span></button>

                    <table id="superAdminTable" class="table table-bordered dataTable" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Username</th>
                                <th class="sorting th-email" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Email</th>
                                <th class="sorting th-branch" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Branch</th>
                                {{-- <th class="sorting th-password" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Password</th> --}}
                                <th class="sorting th-action" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            @if ($user->role_id == 1)
                            <tr>
                                <td><div>{{ $user->username }}</div></td>
                                <td class="td-email"><div>{{ $user->email }}</div></td>
                                <td class="td-branch">
                                    <div>
                                        @if( $user->branch != null )
                                        {{ $user->branch->name }} ({{ $user->branch->code }}) 
                                        @else
                                        -
                                        @endif
                                    </div>
                                </td>
                                <td class="td-action">
                                    <button class="btn btn-secondary btn-sm" id="btnEditSuperAdmin" data-id="{{ $user->id }}"><span>Edit</span></button>
                                    <button class="btn btn-danger btn-sm" id="btnDeleteSuperAdmin" data-id="{{ $user->id }}"><span>Delete</span></button>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 
@stop

@section('javascript')

    {{-- Re-open different modal to show error --}}
    @if( Session::has('register_staff_error'))
        <script type="text/javascript">
            $.noConflict();

            jQuery( document ).ready(function( $ ) {
                $('#registerStaffModal').modal('show');
            });
            console.log('error');
        </script>
    @endif
    @if( Session::has('register_admin_error'))
        <script type="text/javascript">
            $.noConflict();

            jQuery( document ).ready(function( $ ) {
                $('#registerAdminModal').modal('show');
            });
        </script>
    @endif
    @if( Session::has('update_user_error'))
        <script type="text/javascript">
            $.noConflict();

            jQuery( document ).ready(function( $ ) {
                var id = {{ Session::get('update_user_error') }};
                $('#updateUserModal'+id).modal('show');
            });
        </script>
    @endif
@endsection