<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

/**
 * App\User
 *
 * @property integer $id
 * @property integer $pxt_user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $department
 * @property string $access_level
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $unreadNotifications
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePxtUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereDepartment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereAccessLevel($value)
 * @mixin \Eloquent
 */
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
     * @param null $timestamp
     *
     * @return mixed
     */
    public function getUserDailyEvents($timestamp = null, $existAddedEvents = false)
    {
        $dayBoundaries = getDayBoundaries($timestamp);

        $events = Event::where('pxt_user_id', $this->pxt_user_id)
            ->where('device',1172079)
            ->whereBetween('event_time',[$dayBoundaries['start'], $dayBoundaries['end']])
            ->whereNotExists(function($query)
            {
                $query->select(DB::raw(1))
                    ->from('deleted_events')
                    ->whereRaw('events.id = deleted_events.event_id');
            })
            ->get();

        if ($existAddedEvents) {
            $added_events = AddedEvent::where('pxt_user_id', $this->pxt_user_id)
                ->where('device', 1172079)
                ->whereBetween('event_time',[$dayBoundaries['start'], $dayBoundaries['end']])
                ->get();
            $allEvents = $events->merge($added_events)->sortBy('event_time');
            return $allEvents;
        } else {
            return $events->sortBy('event_time');
        }

    }

    /**
     * Calculates and returns the total time for a specific date (default = today)
     * If errors in card usage exist, total time will be returned in an array
     * along with an errors counter
     *
     * @param null $timestamp
     *
     * @return array|\DateInterval
     */
    public function getUserDailyTotal($timestamp = null, $existAddedEvents = false) {

        $events = $this->getUserDailyEvents($timestamp, $existAddedEvents);
        $totalEvents = count($events);

        //setup XOR toggle switch
        $in = 1;
        $out = 2;
        $toggleSwitch = $in ^ $out;

        $expect = 2;
        $previous = null;
        $errors = 0;
        $total = new \DateInterval('P0Y0DT0H0M0S');
        foreach ($events as $index => $event) {
            if ($event->direction == $expect) {
                $expect ^= $toggleSwitch;
                if ($event->direction == 1) {
                    $start = new \DateTime($previous);
                    $duration = $start->diff(new \DateTime($event->event_time));
                    $total = addDateInterval($total,$duration);
                } elseif ( ($event->direction == 2) && ($index == $totalEvents - 1) ) {
                    $start = new \DateTime($event->event_time, new \DateTimeZone('Europe/Athens'));
                    $now = new \DateTime('', new \DateTimeZone('Europe/Athens'));
                    $duration = $start->diff($now);
                    $total = addDateInterval($total,$duration);
                }
            } else {
                $errors += 1;
            }
            $previous = $event->event_time;
        }

        return ($errors != 0) ? compact('total','errors') : $total;

    }

}
