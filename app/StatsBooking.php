<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class StatsBooking extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'stats_booking';
    protected $primaryKey = '_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
