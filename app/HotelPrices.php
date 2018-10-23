<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class HotelPrices extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'prices';
    protected $primaryKey = '_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hotel_id','checkin_date','number_of_days','number_of_guest','room_type','room_prices',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
