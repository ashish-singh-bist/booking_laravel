<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class CustomConfig extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'config';
    protected $primaryKey = '_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parsing_interval', 'thread_count'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
