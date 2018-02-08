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

<div class="modal fade modal-edit" id="editStaffModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Edit Counter Staff</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.editStaff')
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-edit" id="editAdminModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Edit Admin</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.editAdmin')
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-edit" id="editSuperAdminModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Edit Super Admin</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.editSuperAdmin')
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteStaffModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
            <!-- Modal body -->
            <div class="modal-body">
                Are you sure you want to delete?
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteAdminModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <!-- Modal body -->
            <div class="modal-body">
                Are you sure you want to delete?
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-add" id="addBranchModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Add Branch</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.addBranch')
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-add" id="addServiceModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Add Service</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              {{-- @include('forms.addService') --}}
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-add" id="addCounterModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Add Counter</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              {{-- @include('forms.addCounter') --}}
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-add" id="addBranchCounterModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Add Branch Counter</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.addBranchCounter')
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-add" id="addBranchServiceModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Add Branch Service</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.addBranchService')
            </div>
        </div>
    </div>
</div>

@foreach($branches as $branch)
<div class="modal fade modal-edit" id="editBranchModal{{ $branch->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Edit Branch</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.editBranch')
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($branches as $branch)
<div class="modal fade" id="deleteBranchModal{{ $branch->id }}">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.deleteBranch')
            </div>
        </div>
    </div>
</div>
@endforeach

