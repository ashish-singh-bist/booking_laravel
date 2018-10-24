<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\HotelMaster;
use Carbon\Carbon;
use Response;
use App\DistinctData;

class HotelMasterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $category_list = DistinctData::select('hotel_category')->get()->toArray();
        
        if($request->get('id') != Null && $request->get('id') != ''){
            return view('hotel_master.index',['id'=>$request->get('id'), 'category_list' => $category_list[0]['hotel_category']]);
        }else{
            return view('hotel_master.index', ['category_list' => $category_list[0]['hotel_category']]);
        }
    }

    public function getData(Request $request)
    {
        $columns = [];
        $columns_header =[];
        foreach (config('app.hotel_master_header_key') as $key => $value){
            array_push($columns,$key);
            array_push($columns_header,$value);
        }

        $hotelmaster = new HotelMaster();
        if($request->get('id') != Null && $request->get('id') != ''){
            $hotelmaster = $hotelmaster->where('hotel_id',$request->get('id'))->whereNotNull('prop_url');
        }
        if(count($request->get('stars'))>0){
            $stars = $request->get('stars');
            $hotelmaster = $hotelmaster->where(function ($query) use ($stars) {
                foreach($stars as $key => $star){
                    if($key == 0){
                        $query = $query->where('hotel_stars', $star);
                        $query = $query->whereNotNull('prop_url');
                    }else{

                        $query = $query->orWhere('hotel_stars', $star);
                        $query = $query->whereNotNull('prop_url');
                    }
                }
                return $query;
            });
        }

        if($request->get('min_rating')!= Null && $request->get('min_rating')!= ''){ 
            $hotelmaster = $hotelmaster->where('booking_rating','>=', (double)$request->get('min_rating'))->whereNotNull('prop_url');
        }

        if($request->get('max_rating')!= Null && $request->get('max_rating')!= ''){
            $hotelmaster = $hotelmaster->where('booking_rating','<=', (double)$request->get('max_rating'))->whereNotNull('prop_url');
        }

        if($request->get('created_at_from') != Null && $request->get('created_at_from') != ''){
            $hotelmaster = $hotelmaster->where('created_at', '>=', Carbon::parse($request->get('created_at_from'))->startOfDay())->whereNotNull('prop_url');
        }

        if($request->get('created_at_to') != Null && $request->get('created_at_to') != ''){
            $hotelmaster = $hotelmaster->where('created_at', '<=', Carbon::parse($request->get('created_at_to'))->endOfDay())->whereNotNull('prop_url');
        }

        if(count($request->get('countries'))>0){
            $countries = $request->get('countries');
            $hotelmaster = $hotelmaster->where(function ($query) use ($countries) {
                foreach($countries as $key => $country){
                    if($key == 0){
                        $query = $query->where('country', $country);
                        $query = $query->whereNotNull('prop_url');
                    }else{
                        $query = $query->orWhere('country', $country);
                        $query = $query->whereNotNull('prop_url');
                    }
                }
                return $query;
            });
        }

        if(count($request->get('cities'))>0){
            $cities = $request->get('cities');
            $hotelmaster = $hotelmaster->where(function ($query) use ($cities) {
                foreach($cities as $key => $city){
                    if($key == 0){
                        $query = $query->where('city', $city);
                        $query = $qurey->whereNotNull('prop_url');
                    }else{
                        $query = $query->orWhere('city', $city);
                        $query= $query->whereNotNull('prop_url');
                    }
                }
                return $query;
            });
        }        

        if(count($request->get('categories'))>0){
            $categories = $request->get('categories');
            $hotelmaster = $hotelmaster->where(function ($query) use ($categories) {
                foreach($categories as $key => $category){
                    if($key == 0){
                        $query = $query->where('hotel_category', $category);
                        $query = $query->whereNotNull('prop_url');
                    }else{
                        $query = $query->orWhere('hotel_category', $category);
                        $query = $query->whereNotNull('prop_url');
                    }
                }
                return $query;
            });
        }

        if($request->get('self_verified')!=Null && $request->get('self_verified')!=''){
            $is_verified = $request->get('self_verified');
            if($is_verified == '1'){
                $hotelmaster = $hotelmaster->Where('self_verified','>',0)->whereNotNull('prop_url');
            } else if($is_verified == '0'){
                $hotelmaster = $hotelmaster->where(function ($query) {
                    $query = $query->whereNull('self_verified');
                    $query = $query->orWhere('self_verified','=',0);
                    $query = $query->whereNotNull('prop_url');
                });
            }
        }

        if($request->get('guest_favourite')!=Null && $request->get('guest_favourite')!=''){
            $is_favourite = $request->get('guest_favourite');
            if($is_favourite == '1'){
                $hotelmaster = $hotelmaster->Where('guests_favorite_area','=',1)->whereNotNull('prop_url');
            } else if($is_favourite == '0'){
                $hotelmaster = $hotelmaster->where(function ($query) {
                    $query = $query->whereNull('guests_favorite_area');
                    $query = $query->orWhere('guests_favorite_area','=',0);
                    $query = $query->whereNotNull('prop_url');
                });
            }
        }

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        #############################################################################
        if($request->get('export') != null && $request->get('export') == 'csv'){
            $hotelmaster_data = $hotelmaster->offset(intval($start))
                         ->limit(intval(config('app.data_export_row_limit')))
                         ->orderBy($order,$dir)
                         ->get();                       
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=file.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $callback = function() use ($hotelmaster_data, $columns, $columns_header)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns_header);

                foreach($hotelmaster_data as $row) {
	                $row->booking_rating = str_replace('.', ',', $row['booking_rating']);
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
            $statistics = [];
            $statistics['avg_rating'] = $hotelmaster->avg('booking_rating') ?: 0;
            $statistics['max_rating'] = $hotelmaster->max('booking_rating') ?: 0;
            $statistics['min_rating'] = $hotelmaster->min('booking_rating') ?: 0;

            $totalData = $hotelmaster->count();
            $totalFiltered = $totalData; 

            $hotelmaster_data = $hotelmaster->offset(intval($start))
                         ->limit(intval($limit))
                         ->orderBy($order,$dir)
                         ->get();            
        }                     
        
        for($i=0; $i < count($hotelmaster_data); $i++)
        {
        	if($hotelmaster_data[$i]['hotel_name'] != ''){
        		$hotelmaster_data[$i]['hotel_name'] = '<a class="hotel_equip_popup" hotel-id="' . $hotelmaster_data[$i]['hotel_id'] . '" title="hotel equipment" data-title="' . $hotelmaster_data[$i]['hotel_name'] . '">' . $hotelmaster_data[$i]['hotel_name'] . ' <i class="fa fa-info-circle"></i></a>';
        	}
        	else{
        		$hotelmaster_data[$i]['hotel_name'] = '<a class="hotel_equip_popup" hotel-id="' . $hotelmaster_data[$i]['hotel_id'] . '" title="hotel equipment" data-title="' . $hotelmaster_data[$i]['prop_url'] . '">' . $hotelmaster_data[$i]['prop_url'] . ' <i class="fa fa-info-circle"></i></a>';
        	}

            $hotelmaster_data[$i]['hotel_id'] = '<a target="_blank" href="' . $hotelmaster_data[$i]['prop_url'] . '" title="View Property">' . $hotelmaster_data[$i]['hotel_id'] . '</a>';
            $hotelmaster_data[$i]['booking_rating'] = str_replace('.',',', $hotelmaster_data[$i]['booking_rating']);
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $hotelmaster_data,
                    "statistics" => $statistics
                    );
            
        echo json_encode($json_data);
    }
}