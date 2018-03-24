<table id="queueTable" class="table table-bordered queueTable" cellpadding="0">
    <thead>
        <tr>
            {{-- <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">No.</th> --}}
            <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Number</th>
            <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Issue Time</th>
            <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Status</th>
            <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1">Ticket Type</th>
        </tr>
    </thead>
    <tbody>
        {{-- @foreach ($queue->tickets->whereIn('status', ['serving', 'waiting']) as $ticket) --}}
        @foreach($tickets as $ticket)
        @if($ticket->queue->id == $queue->id)
            <tr>
                {{-- <td><div>{{ $loop->iteration }}</div></td> --}}
                <td><div><strong>{{ $ticket->ticket_no }}</strong></div></td>
                <td><div>{{ $appController->formatTime($ticket->issue_time) }}</div></td>
                <td><div>{{ $ticket->status }}</div></td>
                <td><div>{{ $ticket->mobileUser == null ? 'paper' : 'mobile' }}</div></td>
            </tr>
        @endif
        @endforeach
    </tbody>
</table>
