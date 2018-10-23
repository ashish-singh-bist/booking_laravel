<?php

namespace App;
use Carbon\Carbon;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class DistinctData extends Eloquent
{
	protected $connection = 'mongodb';
    protected $collection = 'distinct_data';
    protected $primaryKey = '_id';

    protected $fillable = [
        'hotel_category','hotel_name','room_type','max_persons','available_only','cancellation_type','mealplan_included_name','other_desc'
    ];
}
