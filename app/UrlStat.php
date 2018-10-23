<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class UrlStat extends Eloquent
{
	protected $connection = 'mongodb';
    protected $collection = 'property_urls_stats';
    protected $primaryKey = '_id';

    protected $fillable = [
        'prop_id','log_count','total_urls','fail_count','success_count','run_count','date'
    ];
}
