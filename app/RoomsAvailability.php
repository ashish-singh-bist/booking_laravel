<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class RoomsAvailability extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'rooms_availability';
    protected $primaryKey = '_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hotel_id','room_type','available_only',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}

