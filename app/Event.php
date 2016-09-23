<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Event
 *
 * @property integer $id
 * @property integer $pxt_event_id
 * @property integer $device
 * @property boolean $direction
 * @property integer $pxt_user_id
 * @property string $event_time
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event wherePxtEventId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereDevice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereDirection($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event wherePxtUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereEventTime($value)
 * @mixin \Eloquent
 */
class Event extends Model
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
    protected $table = 'events';

}