<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use App\RoomsAvailability;
use Response;

class RoomsAvailabilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $room_type_list = RoomsAvailability::select('room_type')->distinct()->get()->toArray();

        if($request->get('id') != Null && $request->get('id') != ''){
            return view('rooms_availability.index',['id'=>$request->get('id'), 'room_type_list' => $room_type_list]);
        }else{
            return view('rooms_availability.index', ['room_type_list' => $room_type_list]);
        }         
    }

    public function getData(Request $request)
    {
        $columns = [];
        $columns_header =[];
        foreach (config('app.rooms_availability_header_key') as $key => $value){
            array_push($columns,$key);
            array_push($columns_header,$value);
        }

        $roomsavailability = new RoomsAvailability();
        if($request->get('id') != Null && $request->get('id') != ''){
            $roomsavailability = $roomsavailability->where('hotel_id',$request->get('id'));
        }        

        if(count($request->get('room_types'))>0){
            $room_types = $request->get('room_types');
            $roomsavailability = $roomsavailability->where(function ($query) use ($room_types) {
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
        if($request->get('days') != Null && $request->get('days') != ''){
            $roomsavailability = $roomsavailability->where('number_of_days',(int)$request->get('days'));
        }        
        if($request->get('available_only') != Null && $request->get('available_only') != ''){
            $roomsavailability = $roomsavailability->where('available_only',(int)$request->get('available_only'));
        }
        if($request->get('checkin_date_from')!=Null && $request->get('checkin_date_from')!=''){
            $roomsavailability = $roomsavailability->where('checkin_date', '>=', Carbon::parse($request->get('checkin_date_from'))->startOfDay());
        }
        if($request->get('checkin_date_to')!=Null && $request->get('checkin_date_to')!=''){
            $roomsavailability = $roomsavailability->where('checkin_date', '<=', Carbon::parse($request->get('checkin_date_to'))->endOfDay());
        }
        if($request->get('created_at_from') != Null && $request->get('created_at_from') != ''){
            $roomsavailability = $roomsavailability->where('created_at', '>=', Carbon::parse($request->get('created_at_from'))->startOfDay());
        }
        if($request->get('created_at_to') != Null && $request->get('created_at_to') != ''){
            $roomsavailability = $roomsavailability->where('created_at', '<=', Carbon::parse($request->get('created_at_to'))->endOfDay());
        }

        // if($request->get('created_at') != Null && $request->get('created_at') != ''){
        //     $start_date = Carbon::parse($request->get('created_at'))->startOfDay();
        //     $end_date = Carbon::parse($request->get('created_at'))->endOfDay();
        //     $roomsavailability = $roomsavailability->whereBetween(
        //      'created_at', array(
        //          $start_date,
        //          $end_date
        //      )
        //  );
        // }
        // if($request->get('checkin_date') != Null && $request->get('checkin_date') != ''){
        //     $start_date = Carbon::parse($request->get('checkin_date'))->startOfDay();
        //     $end_date = Carbon::parse($request->get('checkin_date'))->endOfDay();
        //     $roomsavailability = $roomsavailability->whereBetween(
        //      'checkin_date', array(
        //          $start_date,
        //          $end_date
        //      )
        //  );
        // } 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        #############################################################################
        if($request->get('export') != null && $request->get('export') == 'csv'){
            $roomsavailability_data = $roomsavailability->offset(intval($start))
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

            $callback = function() use ($roomsavailability_data, $columns, $columns_header)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns_header);

                foreach($roomsavailability_data as $row) {
                    $row->checkin_date =  $row->checkin_date->toDateTime()->format('y-m-d');
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
            $totalData = $roomsavailability->count();
            $totalFiltered = $totalData; 

            $roomsavailability_data = $roomsavailability->offset((int)$start)
                         ->limit((int)$limit)
                         ->orderBy($order,$dir)
                         ->get();
        }                  

        for($i=0; $i < count($roomsavailability_data); $i++)
        {
            //$roomsavailability_data[$i]['created_at'] = $roomsavailability_data[$i]['created_at'];
            // $roomsavailability_data[$i]['room_type'] = '<span class="popoverMsg">'. $roomsavailability_data[$i]['room_type'] .'</span>';
            $roomsavailability_data[$i]['checkin_date'] =  $roomsavailability_data[$i]['checkin_date']->toDateTime()->format('y-m-d');
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $roomsavailability_data   
                    );
            
        echo json_encode($json_data);
    }
}