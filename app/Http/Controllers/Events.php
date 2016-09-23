<?php

namespace App\Http\Controllers;

use App\AddedEvent;
use App\Event;
use Illuminate\Http\Request;

class Events extends Controller
{

    public function index()
    {

        $events = Event::where('device', 1172079)
                    ->whereBetween('event_time',[getDayBoundaries()['start'], getDayBoundaries()['end']])
                    ->get();

        return response()->json($events);
    }


    public function dailyEventSets(Request $request)
    {

        $startParam = $request->query('start');
        $dayBoundaries = getDayBoundaries($startParam);

        $events = Event::where('device', 1172079)
                    ->whereBetween('event_time',[$dayBoundaries['start'], $dayBoundaries['end']])
                    ->get();

        $added_events = AddedEvent::where('device', 1172079)
                    ->whereBetween('event_time',[$dayBoundaries['start'], $dayBoundaries['end']])
                    ->get();

        $allEvents = $events->merge($added_events)->sortBy('event_time');

        //setup XOR toggle switch
        $in = 1;
        $out = 2;
        $toggleSwitch = $in ^ $out;

        $userRecords = array();
        for ($x = 0; $x <= 46; $x++) {
            $userRecords[$x]['expect'] = 2;
            $userRecords[$x]['previous'] = null;
            $userRecords[$x]['error'] = false;
        }

        $totalEventsCount = count($allEvents);
        $sets = [];
        foreach ($allEvents as $index => $event) {
            if ($event->direction == $userRecords[$event->pxt_user_id]['expect']) {
                $userRecords[$event->pxt_user_id]['expect'] ^= $toggleSwitch;
                if ($event->direction == 1) {
                    $span = array(
                        'id' => $event->id,
                        'resourceId' => $event->pxt_user_id,
                        'start' => $userRecords[$event->pxt_user_id]['previous'],
                        'end'   => $event->event_time,
                        'title' => ($userRecords[$event->pxt_user_id]['error'])?'error':'',
                        'addDay' => ""
                    );
                    array_push($sets,$span);
                    $userRecords[$event->pxt_user_id]['error'] = false;
                }
                elseif ($event->direction == 2 && $index == $totalEventsCount - 1)
                {
                    $now = new \DateTime('',new \DateTimeZone('Europe/Athens'));
                    $span = array(
                        'id' => $event->id,
                        'resourceId' => $event->pxt_user_id,
                        'start' => $event->event_time,
                        'end'   => $now->format('Y-m-d H:i:s'),
                        'title' => ($userRecords[$event->pxt_user_id]['error'])?'error':'',
                        'addDay' => ""
                    );
                    array_push($sets,$span);
                    $userRecords[$event->pxt_user_id]['error'] = false;
                }
            } else {
                $userRecords[$event->pxt_user_id]['error'] = true;
            }
            $userRecords[$event->pxt_user_id]['previous'] = $event->event_time;
        }

        return response()->json($sets);

    }

}