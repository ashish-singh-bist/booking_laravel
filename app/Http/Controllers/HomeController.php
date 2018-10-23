<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\User;
use App\CustomConfig;
use App\HotelMaster;
use App\StatsBooking;
use App\PropertyUrl;
use App\HotelPrices;
use Carbon\Carbon;
use Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::count();

        $p_total_count = PropertyUrl::count();
        $p_active_count = PropertyUrl::where('parse_interval','>',0)->count();
        $custom_config = CustomConfig::first();

        //get parser stats
        $stats = StatsBooking::orderBy('date', 'desc')->limit(10)->get();
        
        return view('home', [ 'user_count'=>$users, 'stats' => $stats, 'p_total_count' => $p_total_count, 'p_active_count' => $p_active_count, 'custom_config'=> $custom_config]);
    }

    public function config()
    {
        $custom_config = CustomConfig::first();
        return view('config', [ 'custom_config'=> $custom_config]);
    }

    public function configUpdate(Request $request)
    {
        request()->validate([
            'parsing_interval' => 'required',
            'thread_count' => 'required',
            'number_of_guests' => 'required',
        ]);
        if( $request->has('scraper_active') )
            $scraper_active = 1;
        else
            $scraper_active = 0;
        $custom_config = CustomConfig::first();
        $custom_config->parsing_interval = intval($request->parsing_interval);
        $custom_config->thread_count = intval($request->thread_count);
        $custom_config->number_of_guests = intval($request->number_of_guests);
        $custom_config->scraper_active = intval($scraper_active);
        $custom_config->str_length_stay = $request->str_length_stay;
        $custom_config->save();
        return view('config', [ 'custom_config'=> $custom_config]);
    }

    public function getFilterList(Request $request)
    {
        if($request->get('type') == 'Country'){
            $filter_list = HotelMaster::select('country')->where('country', 'like', '%' .$request->get('search') . '%')->distinct()->get()->toArray();
        }elseif($request->get('type') == 'City'){
            $filter_list = HotelMaster::select('city')->where('city', 'like', '%' .$request->get('search') . '%')->distinct()->get()->toArray();
        }
        elseif($request->get('type')=='HotelName'){
            $filter_list = HotelMaster::select('hotel_name')->where('hotel_name', 'like', '%' .$request->get('search') . '%')->distinct()->get()->toArray();
        }
        $final_array = [];
        foreach($filter_list as $item) {
            $temp['id'] = $item[0];
            $temp['text'] = $item[0];
            array_push($final_array,$temp);
        }

        return response()->json($final_array);
    }

    public function restartParser(Request $request)
    {
        $scraper_path = config('app.scraper_path');
        $res = exec('python3 ' . base_path() . '/booking_start_stop_script.py restart ' . $scraper_path);
        $res_json = json_decode($res);
        if($res_json->status == 'success'){
            flash($res_json->message)->success()->important();
        }else{
            flash($res_json->message)->error()->important();
        }
        return redirect()->back();
    }

    public function stopParser(Request $request)
    {
        $res = exec('python3 ' . base_path() . '/booking_start_stop_script.py stop');
        $res_json = json_decode($res);
        if($res_json->status == 'success'){
            flash($res_json->message)->success()->important();
        }else{
            flash($res_json->message)->error()->important();
        }        
        return redirect()->back();
    }

    public function exportCSV()
    {
        $headers = array(
            "Content-type" => "plain/html",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $data_array = HotelPrices::limit(100)->get();
        //dd($data_array);
        $columns = [];
        $columns_header = [];

        foreach (config('app.hotel_prices_header_key') as $key => $value){
            array_push($columns,$key);
            array_push($columns_header,$key);
        }        

        $callback = function() use ($data_array, $columns, $columns_header)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns_header);

            foreach($data_array as $row) {

                $data_row = [];
                foreach ($columns as $key) {
                    array_push($data_row, $row->{$key});
                }
                fputcsv($file, $data_row);
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }    
}
