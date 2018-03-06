<table class="table table-bordered dataTable queueTable" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="sorting" tabindex="0", aria-controls="example" rowspan="1" colspan="1">No.</th>
            <th class="sorting" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Ticket Number</th>
            <th class="sorting" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Wait Time</th>
            <th class="sorting" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($queue->tickets->whereIn('status', ['serving', 'waiting']) as $ticket)
        <tr>
            <td><div>{{ $loop->iteration }}</div></td>
            <td><div><strong>{{ $ticket->ticket_no }}</strong></div></td>
            <td><div>{{ $appController->secToString($ticket->wait_time) }}</div></td>
            <td><div>{{ $ticket->status }}</div></td>
        </tr>
    @endforeach
    </tbody>
</table>