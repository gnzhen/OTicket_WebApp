@extends('layouts.app')

@section('title', '| Counter')

@section('content')

<div class="container">

    <h3>Ticket Queue</h3>

    @if(!$branchServices->isEmpty() && $user->branch_id != null)

        @if(!$queues->isEmpty())

            @if($user->branchCounter == null)
                <div class="col-md-5">
                    @include('partials.call._openCounter')
                </div>
            @endif

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
                        <div class="{{ $user->branchCounter != null ? 'col-md-5' : 'col-md-12' }} hidePageOf hideSearch hideEntries">
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
        @else
            @if($user->branchCounter != null)
                <div class="col-md-5">
                    @include('partials.call._closeCounter')
                </div>
            @endif
            <div>
                <p>All queues are empty.</p>
            </div>
        @endif
    @else
        @if($user->branch_id == null)
            <div>
                <p>This user does not belongs to any branch.</p>
            </div>
        @elseif($branchServices->isEmpty())
            <div>
                <p>There are no service in this branch.</p>
            </div>
        @endif
    @endif
</div>
@endsection

@section('javascript')

    {{-- Tab --}}

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


    {{-- Timer --}}

    @if($calling != null)
        <script type="text/javascript">
            // $.noConflict();

            $( document ).ready(function( $ ) {

                var count = {{ $timer }};
                var timer;

                startTimer({{ $user->branchCounter->serving_queue }});

                function startTimer(id) {
                    count ++;
                    $('#timer'+id).text(secToStr(count));
                    timer = setTimeout(function(){ startTimer(id) }, 1000);
                }

                function secToStr(sec){
                    var hours = Math.floor(sec / 3600);
                    var minutes = Math.floor((sec / 60) % 60);
                    var seconds = sec % 60;

                    if(hours < 10)
                        hours = '0' + hours;
                    if(minutes < 10)
                        minutes = '0' + minutes;
                    if(seconds < 10)
                        seconds = '0' + seconds;
              
                    return hours + ':' + minutes + ':' + seconds;
                }
            });
        </script>
    @endif
@endsection