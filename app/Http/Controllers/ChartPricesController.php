<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\HotelPrices;
use Carbon\Carbon;
use MongoDB\BSON\UTCDatetime;
use App\HotelMaster;
use DB;
use App\PropertyUrl;
use App\DistinctData;

class ChartPricesController extends Controller
{
    public function index(Request $request)
    {
        $date_array = [];
        $price_array = [];
        $room_type_list   =  DistinctData::select('room_type')->get();
        $cancel_type_list =  DistinctData::select('cancellation_type')->get();
        $meal_type_list   =  DistinctData::select('mealplan_included_name')->get();
        $hotel_name_list  =  DistinctData::select('hotel_name')->get();
        $max_person_list  =  DistinctData::select('max_persons')->get()->toArray();

        if($request->get('id') != Null && $request->get('id') != ''){
            return view('hotel_prices/chart_prices', ['id' => $request->get('id'), 'room_type_list' => $room_type_list[0]['room_type'], 'cancel_type_list' => $cancel_type_list[0]['cancellation_type'], 'hotel_name_list' => $hotel_name_list[0]['hotel_name'], 'meal_type_list' => $meal_type_list[0]['mealplan_included_name'], 'max_person_list'=>$max_person_list[0]['max_persons'], 'date_array' => json_encode($date_array), 'price_array' =>json_encode($price_array)]);
        }else{
            return view('hotel_prices/chart_prices', ['room_type_list' => $room_type_list[0]['room_type'], 'cancel_type_list' => $cancel_type_list[0]['cancellation_type'], 'hotel_name_list' => $hotel_name_list[0]['hotel_name'], 'meal_type_list' => $meal_type_list[0]['mealplan_included_name'], 'max_person_list'=>$max_person_list[0]['max_persons'], 'date_array' => json_encode($date_array), 'price_array' =>json_encode($price_array)]);
        }
    }

    public function getChartData(Request $request)
    {
        $columns = [];
        foreach (config('app.hotel_prices_header_key') as $key => $value){
            array_push($columns,$key);
        }

        $date_array = [];
        $price_array = [];

        $hotelprices = HotelPrices::select('_id','checkin_date','raw_price');
        $hotelmaster = HotelMaster::select('hotel_id');
        $property_urls = PropertyUrl::select('hotel_id','url')->get();

        $property_url_array = [];
        foreach ($property_urls as $item){
            $property_url_array[$item->hotel_id] = $item->url;
        }

        $cal_info_filters = [];
        $hotel_id_data = [];
        $hotel_id_array = [];
        if(count($request->get('cities'))>0){
            $cities = $request->get('cities');
            $hotelmaster = $hotelmaster->where(function ($query) use ($cities) {
                foreach($cities as $key => $city){
                    if($key == 0){
                        $query = $query->where('city', $city);
                    }else{
                        $query = $query->orWhere('city', $city);
                    }
                }
                return $query;
            });
        }

        if(count($request->get('hotel_names'))>0){
            $hotel_names = $request->get('hotel_names');
            $hotelmaster = $hotelmaster->where(function ($query) use ($hotel_names) {
                foreach($hotel_names as $key => $hotel_name){
                    if($key == 0){
                        $query = $query->where('hotel_name', $hotel_name);
                    }else{
                        $query = $query->orWhere('hotel_name', $hotel_name);
                    }
                }
                return $query;
            });
        }

        if(count($request->get('cities'))>0 || count($request->get('hotel_names'))>0){
            $hotel_id_data = $hotelmaster->get();
            $hotel_id_array = [];
            foreach($hotel_id_data as $hotel){
                array_push($hotel_id_array,$hotel->hotel_id);
            }
            $cal_info_filters['hotel_id'] = ['$in' => $hotel_id_array];
        }
        
        if($request->get('id') != Null && $request->get('id') != ''){
            $hotelprices = $hotelprices->where('hotel_id',$request->get('id'));
        }

        if(count($request->get('room_types'))>0){
            $room_types = $request->get('room_types');
            $room_array = [];
            foreach($room_types as $key => $room_type){
                array_push($room_array, $room_type);
            }
            $cal_info_filters['room_type'] = ['$in' => $room_array];
        }

        if(count($request->get('max_persons'))>0){
            $max_persons = $request->get('max_persons');
            $person_array = [];
            foreach($max_persons as $key => $max_person){
                array_push($person_array, intval($max_person));
            }
            $cal_info_filters['max_persons'] = ['$in' => $person_array];
        }

        if($request->get('calendar_date') != Null && $request->get('calendar_date') != ''){
            $cal_info_filters['cal_info.s']['$eq'] = new \MongoDB\BSON\UTCDateTime(new \DateTime($request->get('calendar_date')));
        }

        if($request->get('checkin_date_from') != Null && $request->get('checkin_date_from') != ''){
            $cal_info_filters['cal_info.c']['$gte'] = new \MongoDB\BSON\UTCDateTime(new \DateTime($request->get('checkin_date_from')));
        }

        if($request->get('checkin_date_to') != Null && $request->get('checkin_date_to') != ''){
            $cal_info_filters['cal_info.c']['$lte'] = new \MongoDB\BSON\UTCDateTime(new \DateTime($request->get('checkin_date_to')));
        }

        if(count($request->get('days'))>0){
            $days = $request->get('days');
            $days_array = [];
            foreach($days as $key => $day){
                array_push($days_array, intval($day));
            }
            $cal_info_filters['number_of_days'] = ['$in' => $days_array];
        }

        if($request->get('meal_plan')!=Null && $request->get('meal_plan')!=''){
            $search_meal_plan = $request->get('meal_plan');
            if($search_meal_plan == 'empty'){
                $cal_info_filters['mealplan_included_name'] = ['$eq' => null];
            } else if($search_meal_plan == 'not-empty'){
                $cal_info_filters['mealplan_included_name'] = ['$ne' => null];
            }
        }

        if(count($request->get('cancellation_type'))>0){
            $cancel_type = $request->get('cancellation_type');
            $cancellation_array = [];
            foreach($cancel_type as $key => $value){
                array_push($cancellation_array, $value);
            }
            $cal_info_filters['cancellation_type'] = ['$in' =>  $cancellation_array];
        }

        // To Filter Room Type on search condition
        $room_array = [];
        $hotelprices_roomtype = clone $hotelprices;
        //$room_type_array = $hotelprices_roomtype->select('room_type')->distinct()->get()->toarray();
        $room_type_array = $hotelprices_roomtype->raw(function($collection) use($cal_info_filters) {
                  return $collection->aggregate([
                    ['$unwind' => '$cal_info'],
                    ['$unwind' => '$cal_info.s'],
                    ['$match'  => $cal_info_filters],
                    ['$group'  => ['_id' =>null, 'room_type' => ['$addToSet' => '$room_type'] ] ],
                    ['$unwind' => '$room_type'],
                    ['$project'=> ['_id'=>0 ]]
                ], ['allowDiskUse' => true]);
            });

        for($i=0; $i < count($room_type_array); $i++)
        {
            $temp_array = [];
            $temp_array['id'] = $room_type_array[$i]['room_type'];
            $temp_array['text'] = $room_type_array[$i]['room_type'];
            array_push($room_array,$temp_array);
        }
        //$hotelprices_data = $hotelprices->select('*')->orderBy('checkin_date','ASC')->get();
        
        $aggregate_query = [
                    ['$unwind' => '$cal_info'],
                    ['$unwind' => '$cal_info.s'],
                    ['$match' => $cal_info_filters],
                    ['$project' =>
                    [
                    '_id'=>['cal_info' => '$cal_info', 'hotel_id' => '$hotel_id', 'mealplan_desc'=> '$mealplan_desc', 'cancellation_type'=> '$cancellation_type', 'available_only'=>'$available_only', 'nr_stays'=>'$nr_stays', 'other_desc'=>'$other_desc', 'max_persons'=> '$max_persons', 'cancellation_desc'=>'$cancellation_desc', 'number_of_days'=>'$number_of_days','room_type'=>'$room_type','number_of_guests'=>'$number_of_guests', 'mealplan_included_name'=>'$mealplan_included_name'],
                    'count' => [ '$sum'=> 1 ]
                    ]
                    ],
                    ['$sort' => ['_id.cal_info.c' => 1]],
                    ['$limit' => 10000]
                    ];

        $hotelprices_data =  $hotelprices->raw(function($collection) use($aggregate_query) {
            return $collection->aggregate($aggregate_query,['allowDiskUse' => true]);
        });


        $chart_data_array = ['checkin_date' => []];
        $dataset_property_urls = [];
        if(count($hotelprices_data)){

            $c_date = Carbon::parse($hotelprices_data[0]['_id']['cal_info']['c']->toDateTime()->format('y-m-d'));
            $end_date = $hotelprices_data[(count($hotelprices_data)-1)]['_id']['cal_info']['c']->toDateTime();

            for($start_date = $c_date; $start_date<=$end_date; $start_date = Carbon::parse($start_date)->addDay()){
                array_push($chart_data_array['checkin_date'],$start_date->format('y-m-d'));
            }
            
            for($i=0; $i < count($hotelprices_data); $i++){
                $data_obj = $hotelprices_data[$i]['_id'];
                $check_in_date = $data_obj['cal_info']['c']->toDateTime()->format('y-m-d');
                $checkout_date = Carbon::parse($data_obj['cal_info']['c']->toDateTime()->format('Y-m-d'))->addDays($data_obj['number_of_days'])->format('Y-m-d');
                if(array_key_exists($data_obj['hotel_id'], $property_url_array)){
                $url = $property_url_array[$data_obj['hotel_id']] . "?checkin=" . $check_in_date . "&checkout=" . $checkout_date . "&selected_currency=EUR&group_adults=" . $data_obj['number_of_guests'];
                }else{
                    $url = '';
                }

                $mealplan_included_name = isset($data_obj['mealplan_included_name'])? $data_obj['mealplan_included_name']:'';
                $cancellation_day_diff = isset($data_obj['cancellation_day_diff'])? $data_obj['cancellation_day_diff']:'';

                $unique_key = $data_obj['room_type'] . '|' . $data_obj['number_of_days'] . '|' . $data_obj['max_persons'] . '|' . $cancellation_day_diff . '|' . $data_obj['cancellation_type'] . '|' . $mealplan_included_name;
                
                //$unique_key = $data_obj['room_type'] . '|' . $data_obj['cancellation_type'] . '|' . $data_obj['mealplan_included_name'];

                if($c_date->format('y-m-d') == $check_in_date){

                    $index = array_search($check_in_date, $chart_data_array['checkin_date']);
                    
                    if (array_key_exists($unique_key,$chart_data_array)){
                        //array_push($chart_data_array[$unique_key],$data_obj['raw_price']);
                        if($chart_data_array[$unique_key][$index] && $data_obj['cal_info']['p']>$chart_data_array[$unique_key][$index]){
                            $chart_data_array[$unique_key][$index] = $data_obj['cal_info']['p'];
                        }else{
                            $chart_data_array[$unique_key][$index] = $data_obj['cal_info']['p'];
                        }
                    }else
                    {
                        $chart_data_array[$unique_key] = [];
                        $dataset_property_urls[$unique_key] = [];
                        for($j=0; $j<$index; $j++){
                            array_push($chart_data_array[$unique_key],null);
                            array_push($dataset_property_urls[$unique_key],null);
                        }
                        $chart_data_array[$unique_key][$index] = $data_obj['cal_info']['p'];
                        array_push($dataset_property_urls[$unique_key],$url);
                    }

                }else{
                    if($i+1 <= count($hotelprices_data) - 1){
                        $next_check_in_date = $hotelprices_data[$i+1]['_id']['cal_info']['c']->toDateTime();

                        for($start_date = $c_date; $start_date < $next_check_in_date; $start_date = Carbon::parse($start_date)->addDay()){
                            foreach($chart_data_array as $key => $value){
                                if($key != 'checkin_date'){
                                    array_push($chart_data_array[$key],null);
                                    array_push($dataset_property_urls[$key],$url);
                                }
                            }
                        }
                        $c_date = Carbon::parse($c_date)->addDay();
                    }
                }
            }
        }
        return response()->json(['status'=>'success','chart_data'=>$chart_data_array, 'dataset_property_urls' => $dataset_property_urls, 'room_array'=> $room_array]);
        
        // $json_data = array(
        //             "chart_data_array"  => $chart_data_array,
        //             "room_array"        => $room_array
        //             );
            
        // echo json_encode($json_data);
    }
}
