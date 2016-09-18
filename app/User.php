<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DateTime;
use DateInterval;

class User extends Authenticatable
{

    use Notifiable;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';


    /**
     * Retrieves all the main entrance (1172079) events registered to a user
     * for a specific date (default = today)
     *
*@param null $timestamp
     *
     * @return mixed
     */
    public function getUserDailyEvents($timestamp = null)
    {
        $dayBoundaries = getDayBoundaries($timestamp);
        $events = Event::where('pxt_user_id', $this->pxt_user_id)
                    ->where('device',1172079)
                    ->whereBetween('event_time',[$dayBoundaries['start'], $dayBoundaries['end']])
                    ->get();

        $added_events = AddedEvent::where('pxt_user_id', $this->pxt_user_id)
            ->where('device', 1172079)
            ->whereBetween('event_time',[$dayBoundaries['start'], $dayBoundaries['end']])
            ->get();

        $allEvents = $events->merge($added_events);

        return $allEvents;
    }


    ///**
    // * Creates and returns all the valid event sets (in-house time)
    // * (direction = 1 represents an exit, while direction 2 represents an entrance)
    // * for the day specified (default = today)
    // * If the user is
    // * It also returns the total/sum time of all the valid event sets found
    // *
    // * @param null $timestamp
    // *
    // * @return array ($sets, $total)
    // *
    // */
    //public function getUserDailyEventSets($timestamp = null) {
    //
    //    $events = $this->getUserDailyEvents($timestamp);
    //    $totalEvents = count($events);
    //
    //    //setup XOR toggle switch
    //    $in = 1;
    //    $out = 2;
    //    $toggleSwitch = $in ^ $out;
    //
    //    $expect = 2;
    //    $sets = [];
    //    $previous = null;
    //    $errors = 0;
    //    foreach ($events as $index => $event) {
    //        if ($event->direction == $expect) {
    //            $expect ^= $toggleSwitch;
    //            if ($event->direction == 1) {
    //                $span = array(
    //                    'id' => $event->id,
    //                    'resourceId' => $event->pxt_user_id,
    //                    'start' => $previous,
    //                    'end'   => $event->event_time,
    //                    'title' => ($error)?'error!':'OK',
    //                    'addDay' => ""
    //                );
    //                array_push($sets,$span);
    //
    //                $error = false;
    //            }
    //        } elseif ($index == $totalEvents - 1 && $expect == 1) {
    //            $expect ^= $toggleSwitch;
    //            $span = array(
    //                'id' => $event->id,
    //                'resourceId' => $event->pxt_user_id,
    //                'start' => $event->event_time,
    //                'end'   => new DateTime(),
    //                'title' => ($error)?'error!':'OK',
    //                'addDay' => ""
    //            );
    //            array_push($sets,$span);
    //
    //
    //            $error = false;
    //
    //        } else {
    //            $error = true;
    //        }
    //        $previous = $event->event_time;
    //    }
    //
    //    return ($errors != 0) ? compact('total','errors') : $total;
    //}

    /**
     * Calculates and returns the total time for a specific date (default = today)
     * If errors in card usage exist, total time will be returned in an array
     * along with an errors counter
     *
     * @param null $timestamp
     *
     * @return array|DateInterval
     */
    public function getUserDailyTotal($timestamp = null) {

        $events = $this->getUserDailyEvents($timestamp);
        $totalEvents = count($events);

        //setup XOR toggle switch
        $in = 1;
        $out = 2;
        $toggleSwitch = $in ^ $out;

        $expect = 2;
        $previous = null;
        $errors = 0;
        $total = new DateInterval('P0Y0DT0H0M0S');
        foreach ($events as $index => $event) {
            if ($event->direction == $expect) {
                $expect ^= $toggleSwitch;
                if ($event->direction == 1) {
                    $start = new DateTime($previous);
                    $duration = $start->diff(new DateTime($event->event_time));
                    $total = addDateInterval($total,$duration);
                }
            } elseif ($index == $totalEvents - 1 && $expect == 1) {
                $expect ^= $toggleSwitch;
                $start = new DateTime($previous);
                $duration = $start->diff(new DateTime($event->event_time));
                $total = addDateInterval($total,$duration);
            } else {
                $errors += 1;
            }
            $previous = $event->event_time;
        }

        return ($errors != 0) ? compact('total','errors') : $total;

    }

}
