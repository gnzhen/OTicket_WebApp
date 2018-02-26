@foreach($branchServices as $branchService)
<div class="modal fade" id="issueTicketModal{{ $branchService->id }}">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
            <!-- Modal body -->
            <div class="modal-body">
              @include('forms.issueTicket')
            </div>
        </div>
    </div>
</div>
@endforeach
