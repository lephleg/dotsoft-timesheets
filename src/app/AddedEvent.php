<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\AddedEvent
 *
 * @property integer $id
 * @property integer $device
 * @property boolean $direction
 * @property integer $pxt_user_id
 * @property string $event_time
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\AddedEvent whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AddedEvent whereDevice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AddedEvent whereDirection($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AddedEvent wherePxtUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AddedEvent whereEventTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AddedEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AddedEvent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AddedEvent extends Model
{

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
    protected $table = 'added_events';

}