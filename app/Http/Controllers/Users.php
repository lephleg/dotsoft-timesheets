<?php

namespace App\Http\Controllers;

use App\Event;
use App\AddedEvent;
use App\User;
use Illuminate\Http\Request;

class Users extends Controller
{

    public function index() {
        $users = User::where('department', 'DOTSOFT')
            ->orderBy('last_name', 'ASC')
            ->get();

        return view('users.index', array('pageTitle' => 'Users', 'users' => $users));
    }

    public function show($id, Request $request) {

        $user = User::where('pxt_user_id',$id)->first();

        if (!is_null($request->query('start')) && !is_null($request->query('end'))) {
            $startDate = new \DateTime($request->query('start'));
            $endDate = new \DateTime($request->query('end'));

            $timelineRange = $startDate->format('l jS F') . ' - ' . $endDate->format('l jS F');
        } else {
            $lastDateString = Event::where('pxt_user_id', $id)
                ->orderBy('id', 'DESC')
                ->first(['event_time'])->event_time;

            $endDate = new \DateTime($lastDateString);

            //create last 5 days interval
            $startDate = clone $endDate;
            $startDate->sub(new \DateInterval('P4D'));
        }

        $endDate = $endDate->modify( '+1 day' );

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($startDate, $interval, $endDate);
        $period = array_reverse(iterator_to_array($period));

        $days = array();
        foreach ( $period as $dt ) {
            $events = $user->getUserDailyEvents($dt->format('Y-m-d H:i:s'), true);
            $days[$dt->format('Y-m-d')] = $events;
        }

        $pageTitle = 'User Profile';

        //setup XOR toggle switch
        $in = 1;
        $out = 2;
        $toggleSwitch = $in ^ $out;

        return view('users.timeline', compact('pageTitle', 'user', 'days', 'toggleSwitch', 'timelineRange'));
    }


    public function daily($id, Request $request)
    {
        $date = $request->query('start');
        $user = User::where('pxt_user_id', $id)->first();

        $events = $user->getUserDailyEvents($date, true);

        return response()->json($events);

    }

    public function asResources(Request $request)
    {
        $date = $request->query('start');

        $users = User::where('department', 'DOTSOFT')
            ->orderBy('last_name', 'ASC')
            ->get();

        $dayBoundaries = getDayBoundaries($date);
        $anyAddedEvent = AddedEvent::whereBetween('event_time',[$dayBoundaries['start'], $dayBoundaries['end']])->first(['id']);
        $existAddedEvents = !is_null($anyAddedEvent);

        $colorCodes = array_values(getRainbowColorScheme());

        $resources =[];
        foreach ($users as $index => $user) {
            $totalObj = $user->getUserDailyTotal($date,$existAddedEvents);

            $error = (gettype($totalObj) == 'array');
            $total = ($error) ? $totalObj['total'] : $totalObj;

            $resource = array(
                'id' => $user->pxt_user_id,
                'user' => $user->last_name . ' ' . $user->first_name,
                'total' => $total->h . "h " . $total->i . "m" . (($error) ? " (!)": ""),
                'eventColor' => $colorCodes[$index % 7]
            );
            array_push($resources, $resource);
        }

        return response()->json($resources);
    }

}