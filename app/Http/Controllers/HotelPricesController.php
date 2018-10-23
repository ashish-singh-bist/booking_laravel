<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\HotelPrices;
use Carbon\Carbon;
use MongoDB\BSON\UTCDatetime;
use App\HotelMaster;
use App\RoomDetails;
use Response;
use DB;
use App\PropertyUrl;
use App\DistinctData;

class HotelPricesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $room_type_list       = DistinctData::select('room_type')->get()->toArray();
        $cancel_type_list     = DistinctData::select('cancellation_type')->get()->toArray();
        $other_desc_list      = DistinctData::select('other_desc')->get()->toarray();
        $category_list        = DistinctData::select('hotel_category')->get()->toArray();
        $max_person_list      = DistinctData::select('max_persons')->get()->toArray();
        $available_room_list  = DistinctData::select('available_only')->get()->toArray();

        if($request->get('id') != Null && $request->get('id') != ''){
            return view('hotel_prices.index',['id'=>$request->get('id'), 'cancel_type_list'=> $cancel_type_list[0]['cancellation_type'], 'other_desc_list'=> $other_desc_list[0]['other_desc'], 'category_list'=> $category_list[0]['hotel_category'], 'room_type_list'=> $room_type_list[0]['room_type'], 'max_person_list'=> $max_person_list[0]['max_persons'], 'available_room_list'=> $available_room_list[0]['available_only']]);
        }else{
            return view('hotel_prices.index', ['cancel_type_list'=> $cancel_type_list[0]['cancellation_type'], 'other_desc_list'=> $other_desc_list[0]['other_desc'], 'category_list'=> $category_list[0]['hotel_category'], 'room_type_list'=> $room_type_list[0]['room_type'], 'max_person_list'=> $max_person_list[0]['max_persons'], 'available_room_list'=> $available_room_list[0]['available_only']]);
        }        
    }

    public function getData(Request $request)
    {
        $columns = [];
        $columns_header = [];
        foreach (config('app.hotel_prices_header_key') as $key => $value){
            array_push($columns,$key);
            array_push($columns_header,$value);
        }

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if($order == 'c'){
            $order = 'cal_info.c';
        }
        if($order =='s'){
            $order = 'cal_info.s';
        }
        if($order =='p'){
            $order = 'cal_info.p';
        }

        $hotel_master_search =false;
        $hotel_name_list = HotelMaster::select('hotel_id', 'hotel_name', 'hotel_category', 'hotel_stars', 'location', 'booking_rating','guests_favorite_area', 'self_verified', DB::raw('SUM(total) as total'))->groupBy('hotel_id')->get();
        $hotel_name_array= [];
        foreach ($hotel_name_list as $item){
            $hotel_name_array[$item->hotel_id] = ['hotel_name'=>$item->hotel_name, 'hotel_category'=>$item->hotel_category, 'hotel_stars'=>$item->hotel_stars , 'location'=>$item->location, 'booking_rating'=>$item->booking_rating, 'guests_favorite_area'=>$item->guests_favorite_area, 'self_verified'=>$item->self_verified];
        }

        $property_urls = PropertyUrl::select('hotel_id','url')->get();
        $property_url_array = [];
        foreach ($property_urls as $item){
            $property_url_array[$item->hotel_id] = $item->url;
        }

        $hotelprices = new HotelPrices();
        $hotelmaster = HotelMaster::select('hotel_id');

        if(count($request->get('stars'))>0){
            $hotel_master_search = true;
            $stars = $request->get('stars');
            $hotelmaster = $hotelmaster->where(function ($query) use ($stars) {
                foreach($stars as $key => $star){
                    if($key == 0){
                        $query = $query->where('hotel_stars', intval($star));
                    }else{
                        $query = $query->orWhere('hotel_stars', intval($star));
                    }
                }
                return $query;
            });
        }

        if($request->get('min_rating')!= Null && $request->get('min_rating')!= ''){
            $hotel_master_search = true;
            $hotelmaster = $hotelmaster->where('booking_rating','>=', (double)$request->get('min_rating'));
        }

        if($request->get('max_rating')!= Null && $request->get('max_rating')!= ''){
            $hotel_master_search =true;
            $hotelmaster = $hotelmaster->where('booking_rating','<=', (double)$request->get('max_rating'));
        }

        if(count($request->get('countries'))>0){
            $hotel_master_search =true;
            $countries = $request->get('countries');
            $hotelmaster = $hotelmaster->where(function ($query) use ($countries) {
                foreach($countries as $key => $country){
                    if($key == 0){
                        $query = $query->where('country', $country);
                    }else{
                        $query = $query->orWhere('country', $country);
                    }
                }
                return $query;
            });
        }

        if(count($request->get('cities'))>0){
            $hotel_master_search =true;
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
            $hotel_master_search =true;
            $hotel_name = $request->get('hotel_names');
            $hotelmaster = $hotelmaster->where(function ($query) use ($hotel_name) {
                foreach($hotel_name as $key => $name){
                    if($key == 0){
                        $query = $query->where('hotel_name', $name);
                    }else{
                        $query = $query->orWhere('hotel_name', $name);
                    }
                }
                return $query;
            });
        }

        if(count($request->get('categories'))>0){
            $hotel_master_search=true;
            $categories = $request->get('categories');
            $hotelmaster = $hotelmaster->where(function ($query) use ($categories) {
                foreach($categories as $key => $category){
                    if($key == 0){
                        $query = $query->where('hotel_category', $category);
                    }else{
                        $query = $query->orWhere('hotel_category', $category);
                    }
                }
                return $query;
            });
        }

        if($request->get('self_verified')!=Null && $request->get('self_verified')!=''){
            $hotel_master_search =true;
            $is_verified = $request->get('self_verified');
            if($is_verified == '1'){
                $hotelmaster = $hotelmaster->Where('self_verified','>',0);
            } else if($is_verified == '0'){
                $hotelmaster = $hotelmaster->where(function ($query) {
                    $query = $query->whereNull('self_verified');
                    $query = $query->orWhere('self_verified','=',0);
                });
            }
        }

        if($request->get('guest_favourite')!=Null && $request->get('guest_favourite')!=''){
            $hotel_master_search=true;
            $is_favourite = $request->get('guest_favourite');
            if($is_favourite == '1'){
                $hotelmaster = $hotelmaster->Where('guests_favorite_area','>',0);
            } else if($is_favourite == '0'){
                $hotelmaster = $hotelmaster->where(function ($query) {
                    $query = $query->whereNull('guests_favorite_area');
                    $query = $query->orWhere('guests_favorite_area','=',0);
                });
            }
        }

        if($request->get('location')!=Null && $request->get('location')!=''){
            $hotel_master_search =true;
            $location = $request->get('location');
            $hotelmaster = $hotelmaster->where('location',$location);
        }

        if($request->get('id') != Null && $request->get('id') != ''){
            $hotel_master_search = true;
            $hotelmaster = $hotelmaster->where('hotel_id',$request->get('id'));
        }

        $cal_info_filters = [];
        $hotel_id_data = [];
        $hotel_id_array = [];
        $hotel_master_sort = false;
        if($order=='hotel_name' || $order=='hotel_category' || $order=='hotel_stars' || $order=='location' || $order=='booking_rating' || $order=='self_verified' || $order=='guests_favorite_area'){
            $hotel_master_sort = true;
        }
        if($hotel_master_sort){
                $hotelmaster = $hotelmaster->orderBy($order, $dir);
        }
        if($hotel_master_search || $hotel_master_sort){
            $hotel_id_data = $hotelmaster->get();
            foreach($hotel_id_data as $value){
                array_push($hotel_id_array,$value->hotel_id);
            }
            $cal_info_filters['hotel_id'] = ['$in' => $hotel_id_array];
        }


        // filter to unwind hotel_price data /////////////////////////////////////////////////
        
        if($request->get('min_price') != Null && $request->get('min_price') != ''){
            $cal_info_filters['cal_info.p']['$gte'] = intval($request->get('min_price'));
        }

        if($request->get('max_price') != Null && $request->get('max_price') != ''){
            if(intval($request->get('max_price'))<500){
                $cal_info_filters['cal_info.p']['$lte'] = intval($request->get('max_price'));
            }
        }

        if($request->get('checkin_date_from') != Null && $request->get('checkin_date_from') != ''){
            $cal_info_filters['cal_info.c']['$gte'] = new \MongoDB\BSON\UTCDateTime(new \DateTime($request->get('checkin_date_from')));
        }

        if($request->get('checkin_date_to') != Null && $request->get('checkin_date_to') != ''){
            $cal_info_filters['cal_info.c']['$lte'] = new \MongoDB\BSON\UTCDateTime(new \DateTime($request->get('checkin_date_to')));
		}

        if($request->get('created_at_from') != Null && $request->get('created_at_from') != ''){
            $cal_info_filters['cal_info.s']['$gte'] = new \MongoDB\BSON\UTCDateTime(new \DateTime($request->get('created_at_from')));
        }

        if($request->get('created_at_to') != Null && $request->get('created_at_to') != ''){
            $cal_info_filters['cal_info.s']['$lte'] = new \MongoDB\BSON\UTCDateTime(new \DateTime($request->get('created_at_to')));
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

        if(count($request->get('available_only'))>0){
            $available_only = $request->get('available_only');
            $availableonly_array = [];
            foreach($available_only as $key => $availableonly){
                array_push($availableonly_array, intval($availableonly));
            }
            $cal_info_filters['available_only'] = ['$in' => $availableonly_array];
        }

        if($request->get('guest_available')!=Null && $request->get('guest_available')!=''){
            $is_guest_available = $request->get('guest_available');
             if($is_guest_available == 'empty'){
                $cal_info_filters['number_of_guests'] = ['$lte' => 0];
            } else if($is_guest_available == 'not-empty'){
                $cal_info_filters['number_of_guests'] = ['$gt' => 0];
            }
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

        if(count($request->get('others_desc'))>0){
            $otherdesc = $request->get('others_desc');
            $otherdesc_array = [];
            foreach($otherdesc as $key => $otherdesc){
                array_push($otherdesc_array, $otherdesc);
            }
            $cal_info_filters['other_desc'] =  ['$in' => $otherdesc_array];
        }

        if(count($request->get('days'))>0){
            $days = $request->get('days');
            $days_array = [];
            foreach($days as $key => $day){
                array_push($days_array, intval($day));
            }
            $cal_info_filters['number_of_days'] = ['$in' => $days_array];
        }
        /////////////////////////////////////////////////////////////////////

        // $hotelprices->select('*')->offset(intval($start))
        //              ->limit(intval(config('app.data_export_row_limit')))
        //              ->orderBy($order,$dir)
        //              ->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        if($dir == 'asc'){
            $order_dir = 1;
        }else{
            $order_dir = -1;
        }
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
                    ['$sort'  => ['_id.' . $order => $order_dir]],
                    ['$skip'  => intval($start) ],
                    ['$limit' => intval($limit) ]
                ];

        if($hotel_master_sort &&  count($hotel_id_array)>0){
            $aggregate_query[4]['$sort'] = ['hotel_sort_id'=>1];
            $aggregate_query[3]['$project']['hotel_sort_id'] = [
                                                    '$let' =>[
                                                        'vars' =>[
                                                            'sort_order' => $hotel_id_array,
                                                            'hote_id'=>'$hotel_id'
                                                        ],
                                                        'in'=>[ '$indexOfArray' =>['$$sort_order','$$hote_id']]
                                                    ]
                                                ];    
        }


        #############################################################################
        if($request->get('export') != null && $request->get('export') == 'csv'){
            $hotelprices_data = $hotelprices->raw(function($collection) use($aggregate_query) {
                $aggregate_query[6]['$limit'] = intval(config('app.data_export_row_limit'));
                return $collection->aggregate($aggregate_query, ['allowDiskUse' => true]);
            });

            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=file.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $callback = function() use ($hotelprices_data, $columns, $columns_header, $hotel_name_array)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns_header);

                for($i=0; $i < count($hotelprices_data); $i++){
                    $row =new \stdClass();
                    $data_obj = $hotelprices_data[$i]['_id'];
                    $row->hotel_name = $hotel_name_array[$data_obj['hotel_id']]['hotel_name'];
                    $row->hotel_category = $hotel_name_array[$data_obj['hotel_id']]['hotel_category'];
                    $row->hotel_stars = $hotel_name_array[$data_obj['hotel_id']]['hotel_stars'];
                    $row->location =  $hotel_name_array[$data_obj['hotel_id']]['location'];
                    $row->booking_rating = $hotel_name_array[$data_obj['hotel_id']]['booking_rating'];
                    $row->guests_favorite_area = $hotel_name_array[$data_obj['hotel_id']]['guests_favorite_area'];
                    $row->self_verified = $hotel_name_array[$data_obj['hotel_id']]['self_verified'];
                    
                    $row->c = $data_obj['cal_info']['c']->toDateTime()->format('Y-m-d');
                    $row->number_of_days = $data_obj['number_of_days'];
                    $row->number_of_guests = $data_obj['number_of_guests'];
                    $row->room_type = trim(preg_replace('/\n/', '',$data_obj['room_type']));
                    $row->p = str_replace(".",",",$data_obj['cal_info']['p']);
                    $row->available_only = $data_obj['available_only'];
                    $row->max_persons = $data_obj['max_persons'];
                    $row->cancellation_type = $data_obj['cancellation_type'];
                    if(array_key_exists('mealplan_desc', $data_obj)){
                        $row->mealplan_desc = $data_obj['mealplan_desc'];    
                    }else{
                        $row->mealplan_desc = "";
                    }
                    if(array_key_exists('mealplan_included_name', $data_obj)){
                        $row->mealplan_included_name = $data_obj['mealplan_included_name'];    
                    }else{
                        $row->mealplan_included_name = "";
                    }
                    if(array_key_exists('other_desc', $data_obj)){
                        if(count($data_obj['other_desc'])>0){
                            $row->other_desc = join('|', (array)$data_obj['other_desc']);
                        }
                    }else{
                        $row->other_desc = '';
                    }
                    if($data_obj['cal_info']['s'] == null){
                        $row->s = '';
                    }else{
                        $row->s = $data_obj['cal_info']['s']->toDateTime()->format('Y-m-d');
                    }
                    
                    $data_row = [];
                    foreach ($columns as $key) {
                        array_push($data_row, $row->{$key});
                    }
                    fputcsv($file, $data_row);
                }
                fclose($file);
            };
            return Response::stream($callback, 200, $headers);
        }#############################################################################
        else{
            $room_array = [];
            $hotelprices_roomtype = clone $hotelprices;
           
            $distinct_rooms = $hotelprices_roomtype->raw(function($collection) use($cal_info_filters) {
                  return $collection->aggregate([
                    ['$unwind' => '$cal_info'],                    
                    ['$unwind' => '$cal_info.s'],
                    ['$match'  => $cal_info_filters],
                    ['$group'  => ['_id' =>null, 'room_type' => ['$addToSet' => '$room_type'] ] ],
                    ['$unwind' => '$room_type'],
                    ['$project'=> ['_id'=>0 ]]
                ], ['allowDiskUse' => true]);
            });

            if(count($distinct_rooms)>0){
                for($i=0; $i < count($distinct_rooms); $i++){
                    $temp_array = [];
                    $temp_array['id'] = $distinct_rooms[$i]->room_type;
                    $temp_array['text'] = $distinct_rooms[$i]->room_type;
                    array_push($room_array,$temp_array);
                }
            }

            $res = $hotelprices->raw(function($collection) use($cal_info_filters) {
                  return $collection->aggregate([
                    ['$unwind' => '$cal_info'],
                    ['$unwind' => '$cal_info.s'],
                    ['$match' => $cal_info_filters],
                    ['$group' => ["_id"=>null, 'max' => ['$max'=>'$cal_info.p'], 'min'=> ['$min'=>'$cal_info.p'], 'avg' => ['$avg' => '$cal_info.p'], 'count'=>['$sum' => 1 ] ] ],
                ], ['allowDiskUse' => true]);
            });

            $statistics = [];
            if(count($res)>0){
                $totalData = $res[0]->count;
                $statistics['avg_price'] = $res[0]->avg;
                $statistics['max_price'] = $res[0]->max;
                $statistics['min_price'] = $res[0]->min;
            }
            else{
                $totalData = 0;
                $statistics['avg_price']=0;
                $statistics['max_price']=0;
                $statistics['min_price']=0;
            }
            $totalFiltered = $totalData;

            $hotelprices_data =  $hotelprices->raw(function($collection) use($aggregate_query) {
                return $collection->aggregate($aggregate_query,['allowDiskUse' => true]);
            });
        }

        $hotel_data = [];
        for($i=0; $i < count($hotelprices_data); $i++)
        {
            $data_obj = $hotelprices_data[$i]['_id'];
            $data_obj['hotel_name'] = '<a class="hotel_equip_popup" hotel-id="'.$data_obj['hotel_id'].'" title="hotel equipment" data-title="' . $hotel_name_array[$data_obj['hotel_id']]['hotel_name'] . '">' . $hotel_name_array[$data_obj['hotel_id']]['hotel_name'] . ' <i class="fa fa-info-circle"></i></a>';

            $data_obj['hotel_category'] = $hotel_name_array[$data_obj['hotel_id']]['hotel_category'];
            $data_obj['hotel_stars'] = $hotel_name_array[$data_obj['hotel_id']]['hotel_stars'];
            $data_obj['location'] = $hotel_name_array[$data_obj['hotel_id']]['location'];
            $data_obj['booking_rating'] = $hotel_name_array[$data_obj['hotel_id']]['booking_rating'];
            $data_obj['guests_favorite_area'] = $hotel_name_array[$data_obj['hotel_id']]['guests_favorite_area'];
            $data_obj['self_verified'] = $hotel_name_array[$data_obj['hotel_id']]['self_verified'];

            $data_obj['room_type'] = '<a class="room_equip_popup" hotel-id="'.$data_obj['hotel_id'].'" title="room equipment" data-title="' . $data_obj['room_type'] . '">' . $data_obj['room_type'] . ' <i class="fa fa-info-circle"></i></a>';
            $data_obj['p'] = str_replace(".",",",$data_obj['cal_info']['p']);

            $checkin_date = $data_obj['cal_info']['c']->toDateTime()->format('Y-m-d');
            $checkout_date = Carbon::parse($data_obj['cal_info']['c']->toDateTime()->format('Y-m-d'))->addDays($data_obj['number_of_days'])->format('Y-m-d');
            
            $url = '';
            if(array_key_exists($data_obj['hotel_id'], $property_url_array)){
                $url = $property_url_array[$data_obj['hotel_id']] . "?checkin=" . $checkin_date . "&checkout=" . $checkout_date . "&selected_currency=EUR&group_adults=" . $data_obj['number_of_guests'];
            }
            else{
                $url = '';
            }
            
            $data_obj['c'] =  '<a href="' . $url . '" target="_blank">' . $checkin_date . "</a>";

            if(array_key_exists('cancellation_desc',$data_obj) && $data_obj['cancellation_desc']!= ''){
                $data_obj['cancellation_type'] = $data_obj['cancellation_type'] ." ( ".$data_obj['cancellation_desc']. " )";    
            }
            if(array_key_exists('other_desc', $data_obj)){
                if(count($data_obj['other_desc'])>0){
                    $data_obj['other_desc'] = join('|', (array)$data_obj['other_desc']);
                }
            }else{
                $data_obj['other_desc'] = '';
            }
            if($data_obj['cal_info']['s'] != null){
                $data_obj['s'] = $data_obj['cal_info']['s']->toDateTime()->format('Y-m-d');
            }else{
                $data_obj['s'] = '';
            }
            
            
            array_push($hotel_data, $data_obj);
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $hotel_data,
                    "statistics"      => $statistics,
                    "room_array"      => $room_array
                    );
            
        echo json_encode($json_data);
    }

    public function getHotelEquipment(Request $request)
    {
        $hotel_master = HotelMaster::select('hotel_equipments')->where('hotel_id',$request->get('hotel_id'))->first();
        return response()->json(["status"=>"success","data"=>$hotel_master->hotel_equipments]);
    }

    public function getRoomEquipment(Request $request)
    {
        $room_types = RoomDetails::select('room_equipment')->where('room_type',$request->get('room_type'))->latest()->first();
        return response()->json(["status"=>"success", "data"=>$room_types->room_equipment]);
    }
}