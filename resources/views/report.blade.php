@extends('layouts.app')

@section('title', '| Report')

@section('content')
<div class="container">

    <div class="row">   
        <div class="col-sm-12 col-md-6">    
            <div class="dataTables_length" id="example_length"> 
                <h3>Report</h3>
            </div>
        </div> 
    </div>

    <div class="row">
        <div class="col-sm-6"> 
            <div class="card card-report">
                <div class="card-body">
                    {!! Form::open(['route' => ['report.result'], 'method' => 'POST']) !!}

                        <div class="form-group{{ $errors->has('reportOption') ? ' has-error' : '' }} row">
                            <label for="reportOption" class="col-md-4 col-form-label">Report Option:</label>

                            <div class="col-md-6">
                                <select class="btn btn-dropdown dropdown-toggle form-control" name="reportOption">
                                        <option value="0">Customer Traffic</option>
                                        <option value="1">Average Wait Time</option>
                                </select>

                                @if ($errors->has('reportOption'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('reportOption') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('groupBy') ? ' has-error' : '' }} row">
                            <label for="groupBy" class="col-md-4 col-control-label">Group By:</label>

                            <div class="col-md-6">
                                <select class="btn btn-dropdown dropdown-toggle form-control" name="groupBy">
                                        <option value=0>Branch</option>
                                        <option value=1>Service</option>
                                        <option value=2>Day</option>
                                        <option value=3>Month</option>
                                </select>

                                @if ($errors->has('groupBy'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('groupBy') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('dateFrom') || $errors->has('dateTo') ? ' has-error' : '' }} row">
                            <label for="dateFrom" class="col-md-4 col-control-label">Date: <small>(optional)</small></label>

                            <div class="col-md-6">
                                <input type="text" id="datepickerFrom" class="form-control date-picker" name="dateFrom" placeholder="From">

                                @if ($errors->has('dateFrom'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dateFrom') }}</strong>
                                    </span>
                                @endif

                                <input type="text" id="datepickerTo" class="form-control date-picker margin-top-5" name="dateTo" placeholder="To">
                                
                                @if ($errors->has('dateTo'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dateTo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">

                                {!! Form::submit('Generate', ['class' => 'btn btn-primary']) !!}
                            </div>
                        </div>
                    
                    {!! Form::close() !!}
                </div>
        </div>
        </div>
    </div>
</div>
@stop
