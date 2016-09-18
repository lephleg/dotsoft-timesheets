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
     * Retrieves all the events registered to a user for a specified date
     * @param null $timestamp
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

        return $events;
    }

    public function getUserDailySets($timestamp = null) {

        $events = $this->getUserDailyEvents($timestamp);

        //setup XOR toggle switch
        $in = 1;
        $out = 2;
        $toggleSwitch = $in ^ $out;

        $expect = 2;
        $sets = [];
        $previous = null;
        $error = false;
        $total = new DateInterval('P0Y0DT0H0M0S');
        foreach ($events as $index => $event) {
            if ($event->direction == $expect) {
                $expect ^= $toggleSwitch;
                if ($event->direction == 1) {
                    $span = array(
                        'id' => $event->id,
                        'resourceId' => $event->pxt_user_id,
                        'start' => $previous,
                        'end'   => $event->event_time,
                        'title' => ($error)?'error!':'OK',
                        'addDay' => ""
                    );
                    array_push($sets,$span);

                    $start = new DateTime($previous);
                    $duration = $start->diff(new DateTime($event->event_time));
                    $total = add_dateInterval($total,$duration);

                    $error = false;
                }
            } else {
                $error = true;
            }
            $previous = $event->event_time;
        }

        return compact('sets','total');
    }

}
