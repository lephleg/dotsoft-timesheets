<?php

namespace App\Http\Controllers;

use App\AddedEvent;
use App\DeletedEvent;
use App\Event;
use Faker\Provider\cs_CZ\DateTime;
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

        $employeeRecords = array();
        for ($x = 0; $x <= 46; $x++) {
            $employeeRecords[$x]['expect'] = 2;
            $employeeRecords[$x]['previous'] = null;
            $employeeRecords[$x]['error'] = false;
            $employeeRecords[$x]['index'] = 0;
        }

        $employeeIds = array();
        foreach ($allEvents as $index => $event) {
            array_push($employeeIds,$event->pxt_user_id);
        }

        $occurrences = array_count_values($employeeIds);

        $sets = [];
        foreach ($allEvents as $index => $event) {
            if ($event->direction == $employeeRecords[$event->pxt_user_id]['expect']) {
                $employeeRecords[$event->pxt_user_id]['expect'] ^= $toggleSwitch;
                if ($event->direction == 1) {
                    $span = array(
                        'id' => $event->id,
                        'resourceId' => $event->pxt_user_id,
                        'start' => $employeeRecords[$event->pxt_user_id]['previous'],
                        'end'   => $event->event_time,
                        'title' => "",
                        'addDay' => ""
                    );
                    array_push($sets,$span);
                    $employeeRecords[$event->pxt_user_id]['error'] = false;
                }
                elseif ($event->direction == 2 && ($employeeRecords[$event->pxt_user_id]['index'] == $occurrences[$event->pxt_user_id] - 1) )
                {
                    $now = new \DateTime('',new \DateTimeZone('Europe/Athens'));
                    $span = array(
                        'id' => $event->id,
                        'resourceId' => $event->pxt_user_id,
                        'start' => $event->event_time,
                        'end'   => $now->format('Y-m-d H:i:s'),
                        'title' => "",
                        'addDay' => ""
                    );
                    array_push($sets,$span);
                    $employeeRecords[$event->pxt_user_id]['error'] = false;
                }
            } else {
                $employeeRecords[$event->pxt_user_id]['error'] = true;
                if ($event->direction == 2) {
                    $fakeEnd = new \DateTime($employeeRecords[$event->pxt_user_id]['previous']);
                    $fakeEnd->add(new \DateInterval('PT5S'));
                    $span = array(
                        'id' => $event->id,
                        'resourceId' => $event->pxt_user_id,
                        'start' => $employeeRecords[$event->pxt_user_id]['previous'],
                        'end'   => $fakeEnd->format('Y-m-d H:i:s'),
                        'title' => "",
                        'addDay' => "",
                        'imageurl' => "img/sign_warning.png",
                        'fake'  => "end"
                    );
                } else {
                    $fakeStart = new \DateTime($event->event_time);
                    $fakeStart->sub(new \DateInterval('PT5S'));
                    $span = array(
                        'id' => $event->id,
                        'resourceId' => $event->pxt_user_id,
                        'start' => $fakeStart->format('Y-m-d H:i:s'),
                        'end'   => $event->event_time,
                        'title' => "",
                        'addDay' => "",
                        'imageurl' => "img/sign_warning.png",
                        'fake'  => "start"
                    );
                }
                array_push($sets,$span);
            }
            $employeeRecords[$event->pxt_user_id]['previous'] = $event->event_time;
            $employeeRecords[$event->pxt_user_id]['index']++;
        }
        //dd($sets);
        return response()->json($sets);

    }



    public function destroy($id) {

        $event = Event::find($id);

        $deletedEvent = new DeletedEvent();
        $deletedEvent->event_id = $event->id;
        $deletedEvent->save();

    }
}

