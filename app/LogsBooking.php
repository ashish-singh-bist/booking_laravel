<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class LogsBooking extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'logs_booking';
    protected $primaryKey = '_id';

    protected $fillable = [
        'prop_id','status_code','log','created_at'
    ];
}
