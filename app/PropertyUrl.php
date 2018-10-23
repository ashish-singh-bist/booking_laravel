<?php

namespace App;
use Carbon\Carbon;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class PropertyUrl extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'property_urls';
    protected $primaryKey = '_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city','url','hotel_id','parse_interval','number_of_guests','str_length_stay',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function setUpdatedAtAttribute($value)
    {
        // $dt = Carbon::create(1991, 1, 31, 0);
        $dt = new \MongoDB\BSON\UTCDateTime(new \DateTime("1990-01-01"));
        return $this->attributes['updated_at'] = $dt;
    }
}
