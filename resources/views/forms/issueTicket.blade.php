<div class="form-group">
    <div class="col-md-12">
        <p style="font-size: 18px;">Take ticket for <strong>{{ $branchService->service->name }}</strong><p>
    </div>
</div>

<div class="form-group">


    <div class="form-control" style="border:none;">
        {!! Form::open(['route'=>['printer.store']]) !!}
            <input type="hidden"name="branchServiceId" value="{{ $branchService->id }}">
            <button type="submit" class="btn btn-primary btn-lg btn-block hv-loading">Paper Ticket</button>
        {!! Form::close() !!}
    </div>
    

    <div class="form-control" style="border:none;">
        {!! Form::open(['route'=>['printer.qrcode']]) !!}
            <input type="hidden" class="form-control" name="branchServiceId" value="{{ $branchService->id }}">
            <button type="submit" class="btn btn-success btn-lg btn-block hv-loading">QR Code</button>
        {!! Form::close() !!}
    </div>

    <div class="form-control" style="border:none;>
        {!! Form::open(['route'=>['printer.index']]) !!}
            <input type="hidden" class="form-control" name="branchServiceId" value="{{ $branchService->id }}">
            <button type="button" class="btn btn-light btn-lg btn-block" data-dismiss="modal">Cancel</button>
        {!! Form::close() !!}
    </div>
</div>
    