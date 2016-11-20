<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Employee
 *
 * @property integer $id
 * @property integer $pxt_user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $department
 * @property string $access_level
 * @method static \Illuminate\Database\Query\Builder|\App\Employee whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Employee wherePxtUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Employee whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Employee whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Employee whereDepartment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Employee whereAccessLevel($value)
 * @mixin \Eloquent
 */
class Employee extends Model
{

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
    protected $table = 'employees';

    /**
     * Retrieves all the main entrance (1172079) events registered to a user
     * for a specific date (default = today)
     *
     * @param null $timestamp
     *
     * @return mixed
     */
    public function getEmployeeDailyEvents($timestamp = null, $existAddedEvents = false)
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
    public function getEmployeeDailyTotal($timestamp = null, $existAddedEvents = false) {

        $events = $this->getEmployeeDailyEvents($timestamp, $existAddedEvents);
        $totalEvents = count($events);

        //setup XOR toggle switch
        $in = 1;
        $out = 2;
        $toggleSwitch = $in ^ $out;

        $expect = 2;
        $previous = null;
        $errors = 0;
        $total = new \DateIntervalEnhanced('P0Y0DT0H0M0S');
        $endOfDay = new \DateTime($timestamp, new \DateTimeZone('Europe/Athens'));
        $endOfDay->setTime(23, 59,59);
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
                    // if this is not viewed the same day there is an error here (the guy never exited)
                    if ($now->getTimestamp() > $endOfDay->getTimestamp()) {
                        $now = $endOfDay;
                        $errors += 1;
                    }
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

    public function getEmployeeDailyTotalDb($date) {

        $dailyTime = DailyLookup::where('pxt_user_id', $this->pxt_user_id)
                            ->where('date', $date)
                            ->value('minutes');
        return $dailyTime;
    }


    public function getMonthAverage($month, $year)
    {
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $days = 0;

        $average = [];
        $reset = \DateTime::createFromFormat('Y-m-d H:i:s', $year.'-'.$month.'-01 00:00:00');
        $total = clone $reset;
        // for each day of this month
        for ($i = 0; $i < $num; $i++)
        {
            $day = $year.'-'.$month.'-'.($i+1);
            $minutes = $this->getEmployeeDailyTotalDb($day);

            if (is_null($minutes)) {
                continue;
            }

            if ($minutes > 0) {
                $days++;
            }

            $enhancedDayTotalInterval = new \DateIntervalEnhanced('PT0H'.$minutes.'M0S');
            $total->add($enhancedDayTotalInterval);
        }

        if (!$days > 0) {
            $average['days'] = 0;
            $average['hours'] = 0;
            $average['minutes'] = 0;
            $average['seconds'] = 0;
        } else {
            $totalInterval = $total->diff($reset);
            $enhancedInterval = new \DateIntervalEnhanced('P'.$totalInterval->d.'DT'.$totalInterval->h.'H'.$totalInterval->m.'M'.$totalInterval->s.'S');

            $averageInterval = new \DateIntervalEnhanced("PT" . round($enhancedInterval->to_seconds()/$days) . "S");
            $averageInterval->recalculate();

            $average['days'] = $averageInterval->d;
            $average['hours'] = $averageInterval->h;
            $average['minutes'] = $averageInterval->i;
            $average['seconds'] = $averageInterval->s;
        }

        return $average;
    }

}
