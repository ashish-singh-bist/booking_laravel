<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use JsValidator;
use App\PropertyUrl;
use App\CustomConfig;

class PropertyUrlController extends Controller
{   
    /*
    |--------------------------------------------------------------------------
    | PropertyUrlController Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles user related task in admin panel (create, edit, update and delete user).
    |
    */
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('property_url.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Code To Save Details in MySQL Database and mongodb the syntax are same
        $path = $request->file('property_url_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $custom_config = CustomConfig::first();
        $parse_interval = 1;
        if($custom_config->parsing_interval != null){
            $parse_interval = $custom_config->parsing_interval;
        }
        $number_of_guests = 0;
        if($custom_config->number_of_guests != null){
            $number_of_guests = $custom_config->number_of_guests;
        }
        $stay_length = '';
        if($custom_config->str_length_stay != null){
            $str_length_stay = $custom_config->str_length_stay;
        }

        foreach ($data as $key => $value) {
            if ($key == 0) continue;
            PropertyUrl::create([ 'city' => $value[0], 'url' => $value[1], 'parse_interval' => intval($parse_interval), 'number_of_guests' => intval($number_of_guests), 'str_length_stay' => $str_length_stay]);
        }
        flash('CSV uploaded successfully!')->success()->important();
        return redirect()->route('property_url.index');
    }

    public function getData(Request $request)
    {
        $columns = ['select', 'id', 'hotel_name', 'city', 'created_at', 'updated_at', 'number_of_guests', 'str_length_stay', 'parse', 'link'];

        $propertyurl = new PropertyUrl();

        if(count($request->get('countries'))>0){
            $countries = $request->get('countries');
            $propertyurl = $propertyurl->where(function ($query) use ($countries) {
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
            $cities = $request->get('cities');
            $propertyurl = $propertyurl->where(function ($query) use ($cities) {
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

        $totalData = $propertyurl->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $propertyurl_data = $propertyurl->offset(intval($start))
                     ->limit(intval($limit))
                     ->orderBy($order,$dir)
                     ->get();

        for($i=0; $i < count($propertyurl_data); $i++)
        {
            $link_hotel_name = '';
            if($propertyurl_data[$i]->hotel_name != '' && $propertyurl_data[$i]->hotel_name != Null){
                $link_hotel_name =  '<a href="' . $propertyurl_data[$i]->url . '" target="_blank" title="'. $propertyurl_data[$i]->hotel_name .'">'. $propertyurl_data[$i]->hotel_name .'</a>';
            }else{
                $link_hotel_name =  '<a href="' .$propertyurl_data[$i]->url . '" target="_blank" title="'. $propertyurl_data[$i]->url .'">'. $propertyurl_data[$i]->url .'</a>';
            }
            $link_html= '';            
            if(isset($propertyurl_data[$i]->hotel_id)){
                $link_html = '&nbsp;<a title="Save" class="btn btn-success update_stay_length" id="'.$propertyurl_data[$i]->_id.'"><i class="fa fa-save" aria-hidden="true"></i></a>';
                $link_html .=  '&nbsp;<a href="' . route('hotel_master.index') . '?id=' . $propertyurl_data[$i]->hotel_id. '" class="btn btn-success" title="Hotel Details"><i class="fa fa-info fa-size"></i></a>';
                $link_html .=  '&nbsp;<a href="' . route('hotel_prices.index') . '?id=' .$propertyurl_data[$i]->hotel_id . '" class="btn btn-success" title="Hotel Prices"><i class="fa fa-euro fa-size"></i></a>';
                // $link_html .=  '&nbsp;<a href="' . route('room_details.index') . '?id=' . $propertyurl_data[$i]->hotel_id . '" class="btn btn-success" title="Room Details"><i class="fa fa-home fa-size"></i></a>';
                // $link_html .=  '&nbsp;<a href="' . route('rooms_availability.index') . '?id=' . $propertyurl_data[$i]->hotel_id . '" class="btn btn-xs btn-success" title="Room Availability"><i class="fa fa-font fa-size"></i></a>';
            }


            // $action_html = '';
            // if($propertyurl_data[$i]->is_active == 1){
            //     $action_html = '&nbsp;<button  prop_id="'. $propertyurl_data[$i]->_id.'" status="1" class="btn btn-xs btn-success update_status" title="Active"><i class="fa fa-check"></i> Active</button>';
            // }
            // else if($propertyurl_data[$i]->is_active == 0){
            //     $action_html = '&nbsp;<button  prop_id="'. $propertyurl_data[$i]->_id.'" status="0" class="btn btn-xs btn-danger update_status" title="Inactive"><i class="fa fa-close"></i> Inactive</button>';
            // }

            $static_array = [0,1,2,3,4,5,6,7,14,30];
            $parse_html = '&nbsp;<select prop_id="'. $propertyurl_data[$i]->_id.'" class="form-control filter_class update_status_'.$propertyurl_data[$i]->_id.'" title="Active">';
            for($j=0;$j<count($static_array);$j++){
                if($j == $propertyurl_data[$i]->parse_interval){
                    if($static_array[$j] == 0){
                        $parse_html.= '<option name="'.$static_array[$j].'" value="'.$static_array[$j].' selected ">'.$static_array[$j].' (inactive)</option>';
                    }
                    else {
                        $parse_html.= '<option name="'.$static_array[$j].'" value="'.$static_array[$j].'" selected>'.$static_array[$j].' days</option>';
                    }
                }else{
                    if($static_array[$j] == 0){
                        $parse_html.= '<option name="'.$static_array[$j].'" value="'.$static_array[$j].'">'.$static_array[$j].' (inactive)</option>';
                    }
                    else{
                        $parse_html.= '<option name="'.$static_array[$j].'" value="'.$static_array[$j].'">'.$static_array[$j].' days</option>';
                    }
                }
            }
            $parse_html .= '</select>';

            $num_guest = [0,1,2,3,4,5,6,7,8,9,10];
            $guest_html = '&nbsp;<select prop_id="'. $propertyurl_data[$i]->_id.'" class="form-control filter_class update_guest_'. $propertyurl_data[$i]->_id.'" title="Active">';
            for($j=0;$j<count($num_guest);$j++){
                if($j == $propertyurl_data[$i]->number_of_guests){
                    if($num_guest[$j] == 0){
                        $guest_html.= '<option name="'.$num_guest[$j].'" value="'.$num_guest[$j].'" selected>'.$num_guest[$j].'</option>';
                    }
                    else{
                        $guest_html.= '<option name="'.$num_guest[$j].'" value="'.$num_guest[$j].'" selected>'.$num_guest[$j].'</option>';
                    }
                }else{
                    if($num_guest[$j] == 0){
                        $guest_html.= '<option name="'.$num_guest[$j].'" value="'.$num_guest[$j].'" selected>'.$num_guest[$j].'</option>';
                    }
                    else{
                        $guest_html.= '<option name="'.$num_guest[$j].'" value="'.$num_guest[$j].'">'.$num_guest[$j].'</option>';
                    }
                }
            }
            $str = '';
            if($propertyurl_data[$i]->str_length_stay != null){
                $str = $propertyurl_data[$i]->str_length_stay;
            }else{
                $str = '';
            }
            $stay_len_html = '&nbsp;<input type="text" class="in_stay_length input_'. $propertyurl_data[$i]->_id.'" value="'.$str.'">&nbsp;';

            // $delete_property = '&nbsp;<a class="btn btn-danger delete_property" id="'.$propertyurl_data[$i]->_id.'" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            

            $propertyurl_data[$i]['select'] = '';
            $propertyurl_data[$i]['created_at'] = $propertyurl_data[$i]->created_at;
            $propertyurl_data[$i]['updated_at'] = $propertyurl_data[$i]->updated_at;
            $propertyurl_data[$i]['id'] = $propertyurl_data[$i]->_id;
            $propertyurl_data[$i]['hotel_name'] = $link_hotel_name;
            $propertyurl_data[$i]['link'] = $link_html;
            $propertyurl_data[$i]['parse'] = $parse_html;
            $propertyurl_data[$i]['num_guest'] = $guest_html;
            $propertyurl_data[$i]['stay_length'] = $stay_len_html;
            // $propertyurl_data[$i]['delete_property'] = $delete_property;
        }
        
        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $propertyurl_data,
                    );
            
        echo json_encode($json_data);
    }

    // public function updatePropertyUrlStatus(Request $request)
    // {
    //     $property_url = PropertyUrl::find($request->_id);
    //     $property_url->timestamps = false;
    //     $property_url->parse_interval = intval($request->parse_interval);
    //     $property_url->save();
    //     return  response()->json([
    //         'status' =>true,
    //         'message' => 'Property Url Status Updated'
    //     ]);
    // }

    // public function updatePropertyUrlNumGuest(Request $request)
    // {
    //     $property_url = PropertyUrl::find($request->_id);
    //     $property_url->timestamps = false;
    //     $property_url->number_of_guests = intval($request->num_guest);
    //     $property_url->save();
    //     return  response()->json([
    //         'status' =>true,
    //         'message' => 'Property Url Guest Number Updated'
    //     ]);
    // }

    public function updatePropertyUrlStayLength(Request $request)
    {
        $property_url = PropertyUrl::find($request->_id);
        $property_url->timestamps = false;
        $property_url->str_length_stay = $request->stay_length;
        $property_url->number_of_guests = intval($request->num_guest);
        $property_url->parse_interval = intval($request->parse_interval);
        $property_url->save();
        return  response()->json([
            'status' =>true,
            'message' => 'Property Url Guest Number Updated'
        ]);
    }

    public function deletePropertyUrlDetails(Request $request)
    {
    	$status = false;
    	$message = '';
        $delete_count =0;
        $property_ids =  $request->get('id');
        if(count($property_ids)>0){
	        foreach($property_ids as $id){
	        	if($id != null || $id != ''){
	        		$property_url = PropertyUrl::find($id);
		            $property_url->delete();
		            $delete_count++;
	        	}
	        }
    	}

       	if($delete_count > 0){
       		$message = 'Property Url deleted';
	        $status = true;
       	}else{
       		$message = 'please select property url';
       		$status = false;
       	}
       	return  response()->json([
            'status' => $status,
            'delete_count' => $delete_count,
            'message' => $message
        ]);
    }
}