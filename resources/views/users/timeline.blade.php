@extends('users.profile')
@section('timeline')
<div class="active tab-pane" id="activity">
    <!-- The timeline -->
    <h3 class="text-center">{{ $timelineRange or 'Latest activity'}}</h3>
    <div class="profile-datepicker-button">
        <button type="button" class="btn btn-default" id="daterange-btn">
            <span><i class="fa fa-calendar"></i> Pick date range</span>
            <i class="fa fa-caret-down"></i>
        </button>
    </div>
    <ul class="timeline timeline-inverse">
        <!-- timeline time label -->
        @foreach($days as $index => $day)
            <?php $date = new DateTime($index); ?>
            <li class="time-label">
                <span class="bg-blue">
                    {{ $date->format('l jS F') }}
                 </span>
            </li>
            <?php
            $expect = 2;
            $error = false;
            ?>
            @foreach($days[$index] as $event)
                <?php $time = new DateTime($event->event_time);?>
                @if (!($event->direction == $expect))
                    <li class="error-event">
                        <i class="fa fa-exclamation-triangle bg-yellow"></i>
                        <span class="error-event-full-hint">
                                @if ($expect == 1)
                                <h4>Some kind of evil sorcery teleported <strong>{{$user->first_name}}</strong> out of the office!</h4>
                                ..or maybe an exit event is missing?
                            @else
                                <h4>Is <strong>{{$user->first_name}}</strong> climbing through the windows?</h4>
                                Let's just assume an event is missing...
                            @endif
                        </span>
                        <span class="error-event-brief-hint">
                            Inconsistent card usage. Is an event missing?
                        </span>
                        <span class="add-event-button">
                            <a class="btn btn-primary btn-sm"><i class="fa fa-plus-circle "></i> Add event</a>
                        </span>
                    </li>
                    <?php $error = true ?>
                @else
                    <?php
                    $expect ^= $toggleSwitch;
                    $error = false;
                    ?>
                @endif
                <li>
                    @if($event->direction == 2)
                        <i class="fa fa-arrow-circle-right bg-green-active color-palette"></i>
                    @elseif($event->direction == 1)
                        <i class="fa fa-arrow-circle-left bg-red-active color-palette"></i>
                    @endif

                    <div class="timeline-item">
                        <span class="time">
                            @if ($error)
                                <span class="after-event-error-hint">
                                    <i class="fa fa-info-circle"></i>
                                    This event is following an error warning, maybe it should be deleted as duplicate?
                                </span>
                            @endif
                            <a id="delete-event-button" class="btn bg-red btn-xs">Delete</a>
                        </span>
                        @if($event->direction == 2)
                            <h3 class="timeline-header"><i class="fa fa-clock-o"></i> {{ $time->format('H:i:s') }}</h3>
                        @elseif($event->direction == 1)
                            <h3 class="timeline-header"><i class="fa fa-clock-o"></i> {{ $time->format('H:i:s') }}</h3>
                        @endif
                    </div>
                </li>
            @endforeach
        @endforeach
        <li>
            <i class="fa fa-clock-o bg-gray"></i>
        </li>
    </ul>
</div>
@endsection