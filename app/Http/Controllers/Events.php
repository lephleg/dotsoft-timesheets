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

        $userRecords = array();
        for ($x = 0; $x <= 46; $x++) {
            $userRecords[$x]['expect'] = 2;
            $userRecords[$x]['previous'] = null;
            $userRecords[$x]['error'] = false;
            $userRecords[$x]['index'] = 0;
        }

        $userIds = array();
        foreach ($allEvents as $index => $event) {
            array_push($userIds,$event->pxt_user_id);
        }

        $occurences = array_count_values($userIds);

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
                        'title' => "",
                        'addDay' => ""

                    );
                    array_push($sets,$span);
                    $userRecords[$event->pxt_user_id]['error'] = false;
                }
                elseif ($event->direction == 2 && ($userRecords[$event->pxt_user_id]['index'] == $occurences[$event->pxt_user_id] - 1) )
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
                    $userRecords[$event->pxt_user_id]['error'] = false;
                }
            } else {
                $userRecords[$event->pxt_user_id]['error'] = true;
                if ($event->direction == 2) {
                    //dd('boom');
                    $fakeStart = new \DateTime($event->event_time);
                    $fakeStart->sub(new \DateInterval('PT1S'));
                    $span = array(
                        'id' => $event->id,
                        'resourceId' => $event->pxt_user_id,
                        'start' => $fakeStart->format('Y-m-d H:i:s'),
                        'end'   => $event->event_time,
                        'title' => "",
                        'addDay' => "",
                        'imageurl' => "img/sign_warning.png"
                    );
                } else {
                    //dd('bam');
                    $fakeEnd = new \DateTime($event->event_time);
                    $fakeEnd->add(new \DateInterval('PT1S'));
                    $span = array(
                        'id' => $event->id,
                        'resourceId' => $event->pxt_user_id,
                        'start' => $event->event_time,
                        'end'   => $fakeEnd->format('Y-m-d H:i:s'),
                        'title' => "",
                        'addDay' => "",
                        'imageurl' => "img/sign_warning.png"
                    );
                }
                array_push($sets,$span);
            }
            $userRecords[$event->pxt_user_id]['previous'] = $event->event_time;
            $userRecords[$event->pxt_user_id]['index']++;
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

