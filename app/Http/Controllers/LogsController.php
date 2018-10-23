<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use MongoDB\BSON\UTCDatetime;
use Response;
use DB;
use App\LogsBooking;
use App\PropertyUrl;

class LogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('logs.index');
    }

    public function getData(Request $request)
    {

        $columns = ['prop_id','status_code','log','created_at'];

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $logsbooking =  new LogsBooking();
        $totalData = $logsbooking->count();

        $logsbooking_data = $logsbooking->offset(intval($start))
                     ->limit(intval($limit))
                     ->orderBy($order,$dir)
                     ->get();
        //dd($logsbooking_data->prop_id);
        $totalFiltered = $totalData;
        for($i=0; $i < count($logsbooking_data); $i++)
        {
            if($logsbooking_data[$i]->prop_id != null){
                $prop_id = $logsbooking_data[$i]->prop_id;
                $property_url = PropertyUrl::find($prop_id);
                if(count($property_url)>0){
                    if($property_url->hotel_name != '' && $property_url->hotel_name != Null){
                        $logsbooking_data[$i]['property_url'] =  '<a href="' . $property_url->url . '" target="_blank" title="'. $property_url->hotel_name .'">'. $property_url->hotel_name .'</a>';
                    }else{
                        $logsbooking_data[$i]['property_url'] =  '<a href="' .$property_url->url . '" target="_blank" title="'. $property_url->url .'">'. $property_url->url .'</a>';
                    }
                }
            }
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $logsbooking_data,
                    );
            
        echo json_encode($json_data);
    }
}
