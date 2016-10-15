<?php

namespace App\Http\Controllers;

use App\Event;
use App\AddedEvent;
use App\Employee;
use Illuminate\Http\Request;

class Employees extends Controller
{

    public function index() {
        $users = Employee::where('department', 'DOTSOFT')
            ->orderBy('last_name', 'ASC')
            ->get();

        return view('employees.index', array('pageTitle' => 'Users', 'employees' => $users));
    }

    public function show($id, Request $request) {

        $employee = Employee::where('pxt_user_id',$id)->first();

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
            $events = $employee->getEmployeeDailyEvents($dt->format('Y-m-d H:i:s'), true);
            $days[$dt->format('Y-m-d')] = $events;
        }

        $pageTitle = 'User Profile';

        //setup XOR toggle switch
        $in = 1;
        $out = 2;
        $toggleSwitch = $in ^ $out;

        return view('employees.timeline', compact('pageTitle', 'employee', 'days', 'toggleSwitch', 'timelineRange'));
    }


    public function daily($id, Request $request)
    {
        $date = $request->query('start');
        $employee = Employee::where('pxt_user_id', $id)->first();

        $events = $employee->getEmployeeDailyEvents($date, true);

        return response()->json($events);

    }

    public function asResources(Request $request)
    {
        $date = $request->query('start');

        $employees = Employee::where('department', 'DOTSOFT')
            ->orderBy('last_name', 'ASC')
            ->get();

        $dayBoundaries = getDayBoundaries($date);
        $anyAddedEvent = AddedEvent::whereBetween('event_time',[$dayBoundaries['start'], $dayBoundaries['end']])->first(['id']);
        $existAddedEvents = !is_null($anyAddedEvent);

        $colorCodes = array_values(getRainbowColorScheme());

        $resources =[];
        foreach ($employees as $index => $employee) {
            $totalObj = $employee->getEmployeeDailyTotal($date,$existAddedEvents);

            $error = (gettype($totalObj) == 'array');
            $total = ($error) ? $totalObj['total'] : $totalObj;

            $resource = array(
                'id' => $employee->pxt_user_id,
                'user' => $employee->last_name . ' ' . $employee->first_name,
                'total' => $total->h . "h " . $total->i . "m",
                'eventColor' => $colorCodes[$index % 7],
                'error' => $error,
            );
            array_push($resources, $resource);
        }

        return response()->json($resources);
    }

}