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
              @include('forms.addService')
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
              @include('forms.addCounter')
            </div>
        </div>
    </div>
</div>

@foreach($branches as $branch)
<div class="modal fade" id="addBranchCounterModal{{ $branch->id }}">
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
@endforeach

@foreach($branches as $branch)
<div class="modal fade" id="addBranchServiceModal{{ $branch->id }}">
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
@endforeach

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

@foreach($services as $service)
<div class="modal fade modal-edit" id="editServiceModal{{ $service->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Edit Service</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.editService')
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($counters as $counter)
<div class="modal fade modal-edit" id="editCounterModal{{ $counter->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Edit Service</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.editCounter')
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($branchServices as $branchService)
<div class="modal fade modal-edit" id="editBranchServiceModal{{ $branchService->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Edit Branch Service</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.editBranchService')
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

@foreach($services as $service)
<div class="modal fade" id="deleteServiceModal{{ $service->id }}">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.deleteService')
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($counters as $counter)
<div class="modal fade" id="deleteCounterModal{{ $counter->id }}">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.deleteCounter')
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($branchCounters as $branchCounter)
<div class="modal fade" id="deleteBranchCounterModal{{ $branchCounter->id }}">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.deleteBranchCounter')
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($branchServices as $branchService)
<div class="modal fade" id="deleteBranchServiceModal{{ $branchService->id }}">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.deleteBranchService')
            </div>
        </div>
    </div>
</div>
@endforeach

