<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DailyLookup
 * @package App
 */
class DailyLookup extends Model {

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'daily_lookup';

    public static function store($date = null) {

        if (is_null($date)) {
            $date = new \DateTime('', new \DateTimeZone('Europe/Athens'));
        } else {
            $date = \DateTime::createFromFormat('Y-m-d', $date);
        }

        $employees = Employee::where('department', 'DOTSOFT')
            ->orderBy('last_name', 'ASC')
            ->get();

        // for each one of the employees
        foreach ($employees as $index => $employee) {

            $dayTotalObj = $employee->getEmployeeDailyTotal($date->format('Y-m-d 00:00:00'), true);

            // if there is an error store a zero internal
            $error = (gettype($dayTotalObj) == 'array');
            $dayTotalObj = ($error) ? new \DateIntervalEnhanced('PT0H0M0S') : $dayTotalObj;

            $total = $dayTotalObj->to_minutes();

            //store daily
            $daily = new DailyLookup();
            $daily->pxt_user_id = $employee->pxt_user_id;
            $daily->minutes = $total;
            $daily->date = $date->format('Y-m-d');
            $daily->save();

        }
        return;
    }

    public static function storeMonth($month, $year)
    {
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        // for each day of this month
        for ($i = 0; $i < $num; $i++)
        {
            $day = $year . '-' . $month . '-' . ($i+1);
            self::store($day);
        }
    }

    public static function storeYear($year)
    {
        // for each month of this year
        for ($i = 1; $i < 13; $i++) {
            self::storeMonth($i,$year);
        }
    }

}
