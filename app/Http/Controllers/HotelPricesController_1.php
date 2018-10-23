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

class HotelPricesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $room_type_list = HotelPrices::select('room_type')->distinct()->get()->toArray();
        $cancel_type_list = HotelPrices::select('cancellation_type')->distinct()->get()->toArray();
        $other_desc_list = HotelPrices::select('other_desc')->distinct()->get()->toArray();
        $category_list = HotelMaster::select('hotel_category')->distinct()->get()->toArray();
        if($request->get('id') != Null && $request->get('id') != ''){
            return view('hotel_prices.index',['id'=>$request->get('id'), 'cancel_type_list'=>$cancel_type_list, 'other_desc_list'=>$other_desc_list, 'category_list'=>$category_list, 'room_type_list' => $room_type_list]);
        }else{
            return view('hotel_prices.index', ['cancel_type_list'=>$cancel_type_list, 'other_desc_list'=>$other_desc_list, 'category_list'=>$category_list, 'room_type_list' => $room_type_list]);
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

        // $hotel_name_list = HotelMaster::select('hotel_id', 'hotel_name', 'hotel_category', 'hotel_stars', 'location', 'booking_rating','guests_favorite_area', 'self_verified', DB::raw('SUM(total) as total'))->groupBy('hotel_id')->get();
        //$hotel_name_array= [];
        // foreach ($hotel_name_list as $item){
        //     $hotel_name_array[$item->hotel_id] = ['hotel_name'=>$item->hotel_name, 'hotel_category'=>$item->hotel_category, 'hotel_stars'=>$item->hotel_stars , 'location'=>$item->location, 'booking_rating'=>$item->booking_rating, 'guests_favorite_area'=>$item->guests_favorite_area, 'self_verified'=>$item->self_verified];
        // }

        $property_urls = PropertyUrl::select('hotel_id','url')->get();
        $property_url_array = [];
        foreach ($property_urls as $item){
            $property_url_array[$item->hotel_id] = $item->url;
        }

        $hotelprices = new HotelPrices();
        //$hotelmaster = HotelMaster::select('hotel_id');

        if($request->get('id') != Null && $request->get('id') != ''){
            $hotelprices = $hotelprices->where('hotel_id',$request->get('id'));
        }

        if(count($request->get('room_types'))>0){
            $room_types = $request->get('room_types');
            $hotelprices = $hotelprices->where(function ($query) use ($room_types) {
                foreach($room_types as $key => $room_type){
                    if($key == 0){
                        $query = $query->where('room_type', $room_type);
                    }else{
                        $query = $query->orWhere('room_type', $room_type);
                    }
                }
                return $query;
            });
        }

        if(count($request->get('max_persons'))>0){
            $max_persons = $request->get('max_persons');
            $hotelprices = $hotelprices->where(function ($query) use ($max_persons) {
                foreach($max_persons as $key => $max_person){
                    if($key == 0){
                        $query = $query->where('max_persons', intval($max_person));
                    }else{
                        $query = $query->orWhere('max_persons', intval($max_person));
                    }
                }
                return $query;
            });
        }

        if(count($request->get('available_only'))>0){
            $available_only = $request->get('available_only');
            $hotelprices = $hotelprices->where(function ($query) use ($available_only) {
                foreach($available_only as $key => $availableonly){
                    if($key == 0){
                        $query = $query->where('available_only', intval($availableonly));
                    }else{
                        $query = $query->orWhere('available_only', intval($availableonly));
                    }
                }
                return $query;
            });
        }

        if($request->get('guest_available')!=Null && $request->get('guest_available')!=''){
            $is_guest_available = $request->get('guest_available');
            if($is_guest_available == 'empty'){
                $hotelprices = $hotelprices->whereNull('number_of_guests')->orWhere('number_of_guests','=',0);
            } else if($is_guest_available == 'not-empty'){
                $hotelprices = $hotelprices->whereNotNull('number_of_guests')->orWhere('number_of_guests','>',0);
            }
        }
        
        if($request->get('created_at_from') != Null && $request->get('created_at_from') != ''){
            $hotelprices = $hotelprices->where('created_at', '>=', Carbon::parse($request->get('created_at_from'))->startOfDay());
        }

        if($request->get('created_at_to') != Null && $request->get('created_at_to') != ''){
            $hotelprices = $hotelprices->where('created_at', '<=', Carbon::parse($request->get('created_at_to'))->endOfDay());
        }

        if($request->get('min_price') != Null && $request->get('min_price') != ''){
            $hotelprices = $hotelprices->where('raw_price','>=',(int)$request->get('min_price'));
        }

        if($request->get('max_price') != Null && $request->get('max_price') != ''){
            $hotelprices = $hotelprices->where('raw_price','<=',(int)$request->get('max_price'));
        }

        if($request->get('checkin_date_from') != Null && $request->get('checkin_date_from') != ''){
            $hotelprices = $hotelprices->where('checkin_date', '>=', Carbon::parse($request->get('checkin_date_from'))->startOfDay());
        }

        if($request->get('checkin_date_to') != Null && $request->get('checkin_date_to') != ''){
            $hotelprices = $hotelprices->where('checkin_date', '<=', Carbon::parse($request->get('checkin_date_to'))->endOfDay());
        }
       
        if($request->get('meal_plan')!=Null && $request->get('meal_plan')!=''){
            $search_meal_plan = $request->get('meal_plan');
            if($search_meal_plan == 'empty'){
                $hotelprices = $hotelprices->whereNull('mealplan_included_name');
            } else if($search_meal_plan == 'not-empty'){
                $hotelprices = $hotelprices->whereNotNull('mealplan_included_name');
            }
        }

        if(count($request->get('cancellation_type'))>0){
            $cancel_type = $request->get('cancellation_type');
            $hotelprices = $hotelprices->where(function ($query) use ($cancel_type) {
                foreach($cancel_type as $key => $cancel_type){
                    if($key == 0){
                        $query = $query->where('cancellation_type', $cancel_type);
                    }else{
                        $query = $query->orWhere('cancellation_type', $cancel_type);
                    }
                }
                return $query;
            });
        }

        if(count($request->get('others_desc'))>0){
            $otherdesc = $request->get('others_desc');
            $hotelprices = $hotelprices->where(function ($query) use ($otherdesc) {
                foreach($otherdesc as $key => $otherdesc){
                    if($key == 0){
                        $query = $query->where('other_desc', $otherdesc);
                    }else{
                        $query = $query->orWhere('other_desc', $otherdesc);
                    }
                }
                return $query;
            });
        }

        if(count($request->get('days'))>0){
            $days = $request->get('days');
            $hotelprices = $hotelprices->where(function ($query) use ($days) {
                foreach($days as $key => $day){
                    if($key == 0){
                        $query = $query->where('number_of_days', intval($day));
                    }else{
                        $query = $query->orWhere('number_of_days', intval($day));
                    }
                }
                return $query;
            });
        }

        //hotel master filters/////////////////////////////////////////////////
        $h_master_filters = [];
        if(count($request->get('stars'))>0){
             array_push($h_master_filters, ['hotelmaster.hotel_stars' => ['$in' => $request->get('stars')]]);
        }
        if(count($request->get('categories'))>0){
            array_push($h_master_filters, ['hotelmaster.hotel_category' => ['$in' => $request->get('categories')]]);
        }
        if(count($request->get('ratings'))>0){
            $ratings = $request->get('ratings');
            $rating_f_array = [];
            foreach($ratings as $key => $rating){
                $start = intval($rating);
                $end = $rating + 1;
                array_push($rating_f_array, ['hotelmaster.booking_rating' => [ '$gte'=> $start, '$lt'=> $end ]]);               
            }
            array_push($h_master_filters,['$or' => $rating_f_array]);
        }
        if(count($request->get('countries'))>0){
            array_push($h_master_filters, ['hotelmaster.country' => ['$in' => $request->get('countries')]]);
        }
        if(count($request->get('cities'))>0){
            array_push($h_master_filters, ['hotelmaster.city' => ['$in' => $request->get('cities')]]);
        }
        if(count($request->get('hotel_names'))>0){
            array_push($h_master_filters, ['hotelmaster.hotel_name' => ['$in' => $request->get('hotel_names')]]);
        }
        if($request->get('self_verified')!=Null && $request->get('self_verified')!=''){
            array_push($h_master_filters, ['hotelmaster.self_verified' => intval($request->get('self_verified'))]);
        }
        if($request->get('guest_favourite')!=Null && $request->get('guest_favourite')!=''){
            array_push($h_master_filters, ['hotelmaster.guests_favorite_area' => intval($request->get('guest_favourite'))]);            
        }        
        /////////////////////////////////////////////////////////////////////       


        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if($request->get('export') != null && $request->get('export') == 'csv'){
            $limit = intval(config('app.data_export_row_limit'));
        }

        if(count($h_master_filters)){
            //dd($h_master_filters);
            $hotelprices_data = $hotelprices->raw(function($collection) use($h_master_filters, $order, $dir, $start, $limit) {
                if($dir == 'asc'){
                    $order_dir = 1;
                }else{
                    $order_dir = -1;
                }
                return $collection->aggregate(array(
                    array( '$lookup' => array(
                        'from' => 'hotel_master',
                        'localField' => 'hotel_id',
                        'foreignField' => 'hotel_id',
                        'as' => 'hotelmaster'
                    )),
                    array( '$unwind' => array( 
                        'path' => '$hotelmaster', 'preserveNullAndEmptyArrays' => True
                    )),
                    array( '$match' => array(
                        '$and' => $h_master_filters
                    )),
                    array( '$sort' => ['hotelmaster.' . $order => $order_dir]),
                    array( '$skip' => intval($start)),
                    array( '$limit' => intval($limit)),
                    ));
                });
        }else{
            $hotelprices_data = $hotelprices->raw(function($collection) use($h_master_filters, $order, $dir, $start, $limit) {
                if($dir == 'asc'){
                    $order_dir = 1;
                }else{
                    $order_dir = -1;
                }                    
                return $collection->aggregate(array(
                    array( '$lookup' => array(
                        'from' => 'hotel_master',
                        'localField' => 'hotel_id',
                        'foreignField' => 'hotel_id',
                        'as' => 'hotelmaster'
                    )),
                    array( '$unwind' => array( 
                        'path' => '$hotelmaster', 'preserveNullAndEmptyArrays' => True
                    )),
                    array( '$sort' => ['hotelmaster.' . $order => $order_dir]),
                    array( '$skip' => intval($start)),
                    array( '$limit' => intval($limit))
                    ));
                });
        }


        // Code To Test The Mongodb Aggregate Query
        #############################################################################
        if($request->get('export') != null && $request->get('export') == 'csv'){
            // $hotelprices_data = $hotelprices->select('*')->offset(intval($start))
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

            $callback = function() use ($hotelprices_data, $columns, $columns_header)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns_header);

                foreach($hotelprices_data as $row) {
                    $hotel_master = $row->hotelmaster;
                    $row->checkin_date =  $row->checkin_date->toDateTime()->format('Y-m-d');
                    $row->raw_price =  str_replace(".",",",$row->raw_price);
                    $row->hotel_title = isset($hotel_master['hotel_name'])?$hotel_master['hotel_name']:'';
                    $row->hotel_category = isset($hotel_master['hotel_category'])?$hotel_master['hotel_category']:'';
                    $row->hotel_stars = isset($hotel_master['hotel_stars'])?$hotel_master['hotel_stars']:'';
                    $row->location = isset($hotel_master['location'])?$hotel_master['location']:'';
                    $row->booking_rating = isset($hotel_master['booking_rating'])?$hotel_master['booking_rating']:'';
                    $row->guests_favorite_area = isset($hotel_master['guests_favorite_area'])?$hotel_master['guests_favorite_area']:'';
                    $row->self_verified = isset($hotel_master['self_verified'])?$hotel_master['self_verified']:'';
                    $row->country = isset($hotel_master['country'])?$hotel_master['country']:'';                    
                    $row->city = isset($hotel_master['city'])?$hotel_master['city']:'';
                    if(count($row->other_desc) > 0){
                        $row->other_desc =  join("|",(array)$row->other_desc);
                    }
                    else{
                        $row->other_desc =  '';
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
            $room_type_array = $hotelprices_roomtype->select('room_type')->distinct()->get()->toarray();
            for($i=0; $i < count($room_type_array); $i++)
            {
                $temp_array = [];
                $temp_array['id'] = $room_type_array[$i][0];
                $temp_array['text'] = $room_type_array[$i][0];
                array_push($room_array,$temp_array);
            }
            
            $statistics = [];
            $statistics['avg_price'] = $hotelprices->avg('raw_price') ?: 0;
            $statistics['max_price'] = $hotelprices->max('raw_price') ?: 0;
            $statistics['min_price'] = $hotelprices->min('raw_price') ?: 0;

            $totalData = $hotelprices->count();
            $totalFiltered = $totalData;

            // $hotelprices_data = $hotelprices->select('*')->offset(intval($start))
            //              ->limit(intval($limit))
            //              ->orderBy($order,$dir)
            //              ->get();
                                    
        }

        for($i=0; $i < count($hotelprices_data); $i++)
        {
            $hotelprices_data[$i]['hotel_title'] = '<a class="hotel_equip_popup" hotel-id="'.$hotelprices_data[$i]['hotel_id'].'" title="hotel equipment" data-title="' . $hotelprices_data[$i]['hotelmaster']['hotel_name'] . '">' . $hotelprices_data[$i]['hotelmaster']['hotel_name'] . ' <i class="fa fa-info-circle"></i></a>';

            $hotelprices_data[$i]['hotel_category'] = isset($hotelprices_data[$i]['hotelmaster']['hotel_category'])?$hotelprices_data[$i]['hotelmaster']['hotel_category']:'';
            $hotelprices_data[$i]['hotel_stars'] = isset($hotelprices_data[$i]['hotelmaster']['hotel_stars'])?$hotelprices_data[$i]['hotelmaster']['hotel_stars']:'';
            $hotelprices_data[$i]['location'] = isset($hotelprices_data[$i]['hotelmaster']['location'])?$hotelprices_data[$i]['hotelmaster']['location']:'';
            $hotelprices_data[$i]['booking_rating'] = isset($hotelprices_data[$i]['hotelmaster']['booking_rating'])?$hotelprices_data[$i]['hotelmaster']['booking_rating']:'';
            $hotelprices_data[$i]['guests_favorite_area'] = isset($hotelprices_data[$i]['hotelmaster']['guests_favorite_area'])?$hotelprices_data[$i]['hotelmaster']['guests_favorite_area']:'';
            $hotelprices_data[$i]['self_verified'] = isset($hotelprices_data[$i]['hotelmaster']['self_verified'])?$hotelprices_data[$i]['hotelmaster']['self_verified']:'';
            $hotelprices_data[$i]['country'] = isset($hotelprices_data[$i]['hotelmaster']['country'])?$hotelprices_data[$i]['hotelmaster']['country']:'';
            $hotelprices_data[$i]['city'] = isset($hotelprices_data[$i]['hotelmaster']['city'])?$hotelprices_data[$i]['hotelmaster']['city']:'';

            $hotelprices_data[$i]['room_type'] = '<a class="room_equip_popup" hotel-id="'.$hotelprices_data[$i]['hotel_id'].'" title="room equipment" data-title="' . $hotelprices_data[$i]['room_type'] . '">' . $hotelprices_data[$i]['room_type'] . ' <i class="fa fa-info-circle"></i></a>';
            $hotelprices_data[$i]['raw_price'] = str_replace(".",",",$hotelprices_data[$i]['raw_price']);


            $checkin_date = $hotelprices_data[$i]['checkin_date']->toDateTime()->format('Y-m-d');
            $checkout_date = Carbon::parse($hotelprices_data[$i]['checkin_date']->toDateTime()->format('Y-m-d'))->addDays($hotelprices_data[$i]['number_of_days'])->format('Y-m-d');

            $url = $property_url_array[$hotelprices_data[$i]['hotel_id']] . "?checkin=" . $checkin_date . "&checkout=" . $checkout_date . "&selected_currency=EUR&group_adults=" . $hotelprices_data[$i]['number_of_guests'];
            $hotelprices_data[$i]['checkin_date'] =  '<a href="' . $url . '" target="_blank">' . $hotelprices_data[$i]['checkin_date']->toDateTime()->format('y-m-d') . "</a>";

            if($hotelprices_data[$i]['cancellation_desc']!= ''){
                $hotelprices_data[$i]['cancellation_type'] = $hotelprices_data[$i]['cancellation_type'] ." ( ".$hotelprices_data[$i]['cancellation_desc']. " )";    
            }
            else{
                $hotelprices_data[$i]['cancellation_type'] = $hotelprices_data[$i]['cancellation_type'];
            }
        }
        
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $hotelprices_data,
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