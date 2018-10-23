<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use MongoDB\BSON\UTCDatetime;
use Response;
use DB;
use App\UrlStat;
use App\PropertyUrl;

class UrlStatController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('url_stats.index');
    }

    public function getData(Request $request)
    {
        $columns = ['prop_id','log_count','total_urls','fail_count','success_count','run_count','date'];

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $urlstat =  new UrlStat();
        $totalData = $urlstat->count();

        $urlstat_data = $urlstat->offset(intval($start))
                     ->limit(intval($limit))
                     ->orderBy($order,$dir)
                     ->get();
        
        $totalFiltered = $totalData;

        for($i=0; $i < count($urlstat_data); $i++)
        {
            if($urlstat_data[$i]->prop_id != null){
                $prop_id = $urlstat_data[$i]->prop_id;
                $property_url = PropertyUrl::find($prop_id);
                if(count($property_url)>0){
                    if($property_url->hotel_name != '' && $property_url->hotel_name != Null){
                        $urlstat_data[$i]['property_url'] =  '<a href="' . $property_url->url . '" target="_blank" title="'. $property_url->hotel_name .'">'. $property_url->hotel_name .'</a>';
                    }else{
                        $urlstat_data[$i]['property_url'] =  '<a href="' .$property_url->url . '" target="_blank" title="'. $property_url->url .'">'. $property_url->url .'</a>';
                    }
                }
            }
            $date = $urlstat_data[$i]->date->toDateTime()->format('Y-m-d');
            $urlstat_data[$i]['date'] =  $date;
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $urlstat_data,
                    );
            
        echo json_encode($json_data);
    }
}
