<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use MongoDB\BSON\UTCDatetime;
use Response;
use DB;
use App\DistinctData;
use App\RoomDetails;
use App\HotelPrices;
use App\HotelMaster;


class CollectDistinctDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CollectDistinctDetails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Select Distinct Details From Booking Project';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$current_date = Carbon::now()->subDays(1);

    	$data_id ='';
    	$is_exist = false;
    	$distinct_data = DistinctData::first();

    	if($distinct_data == null){
    		$is_exist = false;
    		$distinct_array = [];    		
    		$distinct_array['hotel_category'] = [];
    		$distinct_array['hotel_name'] = [];
    		$distinct_array['room_type'] = [];
    		$distinct_array['max_persons'] = [];
    		$distinct_array['available_only'] = [];
    		$distinct_array['cancellation_type'] = [];
    		$distinct_array['mealplan_included_name'] = [];
    		$distinct_array['other_desc'] = [];
    	}else{
    		$is_exist = true;
    		$data_id = $distinct_data->_id;
    		$distinct_array['hotel_category'] = $distinct_data['hotel_category'];
    		$distinct_array['hotel_name'] = $distinct_data['hotel_name'];
    		$distinct_array['room_type'] = $distinct_data['room_type'];
    		$distinct_array['max_persons'] = $distinct_data['max_persons'];
    		$distinct_array['available_only'] = $distinct_data['available_only'];
    		$distinct_array['cancellation_type'] = $distinct_data['cancellation_type'];
    		$distinct_array['mealplan_included_name'] = $distinct_data['mealplan_included_name'];
    		$distinct_array['other_desc'] = $distinct_data['other_desc'];
    	}

		$room_type_list   = HotelPrices::select('room_type')->where('created_at', '>=', $current_date)->distinct()->get()->toArray();
		$cancel_type_list = HotelPrices::select('cancellation_type')->where('created_at', '>=', $current_date)->distinct()->get()->toArray();
        $other_desc_list  = HotelPrices::select('other_desc')->where('created_at', '>=', $current_date)->distinct()->get()->toArray();
        $category_list 	  = HotelMaster::select('hotel_category')->where('created_at', '>=', $current_date)->distinct()->get()->toArray();
        $meal_type_list   = HotelPrices::select('mealplan_included_name')->where('created_at', '>=', $current_date)->distinct()->get()->toArray();
        $hotel_name_list  = HotelMaster::select('hotel_name')->where('created_at', '>=', $current_date)->distinct()->get()->toArray();
        $max_person_list  = HotelPrices::select('max_persons')->where('created_at', '>=', $current_date)->distinct()->get()->toArray();
		$available_only   = HotelPrices::select('available_only')->where('created_at', '>=', $current_date)->distinct()->get()->toArray();
		
		for($i=0; $i< count($category_list); $i++){
        	$hotel_category = $category_list[$i][0];
        	if (in_array($hotel_category, $distinct_array['hotel_category'])==0) {
	            array_push($distinct_array['hotel_category'], $hotel_category);
	        }
        }

        for($i=0; $i< count($hotel_name_list); $i++){
        	$hotel_name = $hotel_name_list[$i][0];
        	if (in_array($hotel_name, $distinct_array['hotel_name'])==0) {
	            array_push($distinct_array['hotel_name'], $hotel_name);
	        }
        }

        for($i=0; $i < count($room_type_list); $i++){
        	$room_type = $room_type_list[$i][0];
        	if (in_array($room_type, $distinct_array['room_type'])==0) {
	            array_push($distinct_array['room_type'], $room_type);
	        }
        }

        for($i=0; $i< count($max_person_list); $i++){
        	$max_persons = $max_person_list[$i][0];
        	if (in_array($max_persons, $distinct_array['max_persons'])==0) {
	            array_push($distinct_array['max_persons'], $max_persons);
	        }
        }

        for($i=0; $i< count($cancel_type_list); $i++){
        	$cancellation_type = $cancel_type_list[$i][0];
        	if (in_array($cancellation_type, $distinct_array['cancellation_type'])==0) {
	            array_push($distinct_array['cancellation_type'], $cancellation_type);
	        }
        }

        for($i=0; $i< count($meal_type_list); $i++){
        	$meal_name = $meal_type_list[$i][0];
        	if (in_array($meal_name, $distinct_array['mealplan_included_name'])==0) {
	            array_push($distinct_array['mealplan_included_name'], $meal_name);
	        }
        }

        for($i=0; $i< count($other_desc_list); $i++){
        	$other_desc = $other_desc_list[$i][0];
        	if (in_array($other_desc, $distinct_array['other_desc'])==0) {
			    array_push($distinct_array['other_desc'], $other_desc);
			}
        }

        for($i=0; $i< count($available_only); $i++){
        	$availableroom = $available_only[$i][0];
        	if (in_array($availableroom, $distinct_array['available_only'])==0) {
	            array_push($distinct_array['available_only'], $availableroom);
	        }
        }

		asort($distinct_array['hotel_category']);
		asort($distinct_array['hotel_name']);
		asort($distinct_array['room_type']);
		asort($distinct_array['max_persons']);
		asort($distinct_array['cancellation_type']);
		asort($distinct_array['mealplan_included_name']);
		asort($distinct_array['other_desc']);
		asort($distinct_array['available_only']);

		$hotel_category = array_values($distinct_array['hotel_category']);
		$hotel_name = array_values($distinct_array['hotel_name']);
		$room_type = array_values($distinct_array['room_type']);
		$max_persons = array_values($distinct_array['max_persons']);
		$cancellation_type = array_values($distinct_array['cancellation_type']);
		$mealplan_included_name = array_values($distinct_array['mealplan_included_name']);
		$other_desc = array_values($distinct_array['other_desc']);
		$available_only = array_values($distinct_array['available_only']);
		
        if($is_exist == false){
        	$insert_distinct_data = new DistinctData();
        }
    	else{
    		$insert_distinct_data = DistinctData::find($data_id);
       	}
       	$insert_distinct_data->hotel_category = $hotel_category;
        $insert_distinct_data->hotel_name = $hotel_name;
        $insert_distinct_data->room_type = $room_type;
        $insert_distinct_data->max_persons = $max_persons;
        $insert_distinct_data->available_only = $available_only;
        $insert_distinct_data->cancellation_type = $cancellation_type;
        $insert_distinct_data->mealplan_included_name = $mealplan_included_name;
        $insert_distinct_data->other_desc = $other_desc;
        $insert_distinct_data->save();
    }
}
