<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class HotelMaster extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'hotel_master';
    protected $primaryKey = '_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hotel_id','hotel_name','hotel_stars','location','latitude','longitude','booking_rating','hotel_equipments',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}

