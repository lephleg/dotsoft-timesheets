<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\DeletedEvent
 *
 * @property integer $id
 * @property integer $event_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\DeletedEvent whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\DeletedEvent whereEventId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\DeletedEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\DeletedEvent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DeletedEvent extends Model
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
    protected $table = 'deleted_events';

}