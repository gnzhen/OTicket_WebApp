@extends('layouts.app')

@section('title', '| Home')

@section('content')
<div class="row">   
    <div class="col-md-4 offset-1 div-dashboard">
        <div class="row">   
            <div class="card card-dashboard">
                <div class="card-header">Total tickets</div>
                <div class="card-body">15210</div>
            </div>

            <div class="card card-dashboard">
                <div class="card-header">Tickets yesterday</div>
                <div class="card-body">720</div>
            </div>

            <div class="card card-dashboard">
                <div class="card-header">Avg Wait Time</div>
                <div class="card-body">45min</div>
            </div>
        </div>
    </div>

    <div class="col-md-6 offset-1 div-table">
        <div class="row">
            <h3>Average Wait Time</h3>
        </div>
        
        <div class="row">
            <table id="example" class="table table-responsive-md" cellspacing="0">
                <thead>
                    <tr>
                        <th>Branch</th>
                        <th>Service</th>
                        <th>Avg Wait Time</th>
                    </tr>
                </thead>
                {{-- <tfoot>
                    <tr>
                        <th>Branch</th>
                        <th>Service</th>
                        <th>Avg Wait Time</th>
                    </tr>
                </tfoot> --}}
                <tbody>
                    <tr>
                        <td>Kepong</td>
                        <td>Customer Service</td>
                        <td>35 min</td>
                    </tr>
                    <tr>
                        <td>Ampang</td>
                        <td>Customer Service</td>
                        <td>35 min</td>
                    </tr>
                    <tr>
                        <td>Kepong</td>
                        <td>Customer Service</td>
                        <td>35 min</td>
                    </tr>
                    <tr>
                        <td>Kepong</td>
                        <td>Customer Service</td>
                        <td>35 min</td>
                    </tr>
                    <tr>
                        <td>Kepong</td>
                        <td>Customer Service</td>
                        <td>39 min</td>
                    </tr>
                    <tr>
                        <td>Kepong</td>
                        <td>Other Service</td>
                        <td>35 min</td>
                    </tr>
                    <tr>
                        <td>Kepong</td>
                        <td>Customer Service</td>
                        <td>35 min</td>
                    </tr>
                    <tr>
                        <td>Kepong</td>
                        <td>Customer Service</td>
                        <td>30 min</td>
                    </tr>
                    <tr>
                        <td>Kepong</td>
                        <td>Customer Service</td>
                        <td>35 min</td>
                    </tr>
                </tbody>
            </table>
        </div>
                      
    </div>
</div>
@endsection
