<table class="table table-bordered dataTable" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="sorting" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Ticket Number</th>
            <th class="sorting" tabindex="0", aria-controls="example" rowspan="1" colspan="1">Wait Time</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($queue->tickets as $ticket)
        <tr>
            <td><div>{{ $ticket->ticket_no }}</div></td>
            <td><div>{{ $appController->secToString($ticket->wait_time) }}</div></td>
        </tr>
    @endforeach
    </tbody>
</table>