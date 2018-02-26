@extends('layouts.app')

@section('title', '| Counter')

@section('content')

<div class="container-fluid">
    <h3>Ticket Queue</h3>

    @if(!$branchServices->isEmpty())
    <div class="col-md-5">

        @if($user->branchCounter == null)
            @include('partials.call._openCounter')
        @endif
    </div>

        @if(!$queues->isEmpty())
        <div class="col-md-12"> 
            <ul class="nav nav-tabs" role="tablist">
                @foreach($queues as $i => $queue)
                <li class="nav-item">
                    <a class="nav-link nav-link-{{ $i }} {{ $appController->getTabSession() == $queue->id ? 'active' : '' }}" href="#tab{{ $queue->id }}" role="tab" data-toggle="tab" onmousedown="setTabSession({{ $queue->id }})">{{ $queue->branchService->service->name }}</a>
                </li>
                @endforeach
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                @foreach($queues as $i => $queue)
                <div role="tabpanel" class="tab-pane tab-pane-{{ $i }} fade {{ ($appController->getTabSession() == $queue->id) ? 'show active' : '' }}" id="tab{{ $queue->id }}">
                  
                    <div class="row">
                        <div class="{{ $user->branchCounter != null ? 'col-md-5' : 'col-md-12' }} hideEntries hideSearch hidePageOf">
                            @include('partials.call._queueTable')
                        </div>

                        @if($user->branchCounter != null)
                        <div class="col-md-6">
                            @include('partials.call._calling')
                        </div>
                        @endif
                    </div>

                </div>
                @endforeach
            </div>
        </div>
        @else
        <div>
            <p>All queues are empty.</p>
        </div>
        @endif
    @else
    <div>
        <p>This user does not belongs to any branch.</p>
    </div>
    @endif
</div>
@endsection

@section('javascript')

    {{-- Set first tab active if no previous active tab --}}
    @if(!Session::has('tab'))
        <script type="text/javascript">
            $.noConflict();

            jQuery( document ).ready(function( $ ) {
                $('.nav-link-0').addClass('active');
                $('.tab-pane-0').addClass('active');
                $('.tab-pane-0').addClass('show');
            });
        </script>
    @endif
@endsection