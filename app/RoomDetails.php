<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class RoomDetails extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'room_details';
    protected $primaryKey = '_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hotel_id','room_type','room_equipment',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}

