<div class="modal fade modal-add" id="registerStaffModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Add Counter Staff</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.registerStaff')
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-add" id="registerAdminModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Add Admin</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.registerAdmin')
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-add" id="registerSuperAdminModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Add Super Admin</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.registerSuperAdmin')
            </div>
        </div>
    </div>
</div>


@foreach($users as $user)
<div class="modal fade modal-edit" id="updateStaffModal{{ $user->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Edit Counter Staff</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.updateUser')
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($users as $user)
<div class="modal fade modal-edit" id="updateAdminModal{{ $user->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Edit Admin</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.updateUser')
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($users as $user)
<div class="modal fade modal-edit" id="updateSuperAdminModal{{ $user->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Edit Super Admin</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.updateUser')
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($users as $user)
<div class="modal fade" id="deleteStaffModal{{ $user->id }}">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.deleteStaff')
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($users as $user)
<div class="modal fade" id="deleteAdminModal{{ $user->id }}">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.deleteAdmin')
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($users as $user)
<div class="modal fade" id="deleteSuperAdminModal{{ $user->id }}">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.deleteSuperAdmin')
            </div>
        </div>
    </div>
</div>
@endforeach