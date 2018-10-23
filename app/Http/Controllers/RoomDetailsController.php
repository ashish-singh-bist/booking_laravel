<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\RoomDetails;
use Carbon\Carbon;
use Response;
use App\DistinctData;

class RoomDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $room_type_list  =  DistinctData::select('room_type')->get()->toArray();
        if($request->get('id') != Null && $request->get('id') != ''){
            return view('room_details.index',['id'=>$request->get('id'), 'room_type_list' => $room_type_list[0]['room_type']]);
        }else{
            return view('room_details.index', ['room_type_list' => $room_type_list[0]['room_type']]);
        }          
    }

    public function getData(Request $request)
    {
        $columns = [];
        $columns_header = [];
        foreach (config('app.room_details_header_key') as $key => $value){
            array_push($columns,$key);
            array_push($columns_header,$value);
        }
        $roomdetails = new RoomDetails();
        if($request->get('id') != Null && $request->get('id') != ''){
            $roomdetails = $roomdetails->where('hotel_id',$request->get('id'));
        }         

        if(count($request->get('room_types'))>0){
            $room_types = $request->get('room_types');
            $roomdetails = $roomdetails->where(function ($query) use ($room_types) {
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

        if($request->get('created_at_from') != Null && $request->get('created_at_from') != ''){
            $roomdetails = $roomdetails->where('created_at', '>=', Carbon::parse($request->get('created_at_from'))->startOfDay());
        }

        if($request->get('created_at_to') != Null && $request->get('created_at_to') != ''){
            $roomdetails = $roomdetails->where('created_at', '<=', Carbon::parse($request->get('created_at_to'))->endOfDay());
        }

        // Check-in date have been removed from Room_Detail Collections
        // if($request->get('checkin_date_from') != Null && $request->get('checkin_date_from') != ''){
        //     $roomdetails = $roomdetails->where('checkin_date', '>=', Carbon::parse($request->get('checkin_date_from'))->startOfDay());
        // }
        // if($request->get('checkin_date_to') != Null && $request->get('checkin_date_to') != ''){
        //     $roomdetails = $roomdetails->where('checkin_date', '<=', Carbon::parse($request->get('checkin_date_to'))->endOfDay());
        // }

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');        

        #############################################################################
        if($request->get('export') != null && $request->get('export') == 'csv'){
            $roomdetails_data = $roomdetails->offset(intval($start))
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

            $callback = function() use ($roomdetails_data, $columns, $columns_header)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns_header);

                foreach($roomdetails_data as $row) {
                    // $row->checkin_date =  $row->checkin_date->toDateTime()->format('y-m-d');
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
            $totalData = $roomdetails->count();
            $totalFiltered = $totalData;

            $roomdetails_data = $roomdetails->offset(intval($start))
                         ->limit(intval($limit))
                         ->orderBy($order,$dir)
                         ->get();
        }                   

        for($i=0; $i < count($roomdetails_data); $i++)
        {
            $roomdetails_data[$i]['created_at'] = $roomdetails_data[$i]['created_at'];
            // $roomdetails_data[$i]['checkin_date'] =  $roomdetails_data[$i]['checkin_date']->toDateTime()->format('y-m-d');
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $roomdetails_data   
                    );
            
        echo json_encode($json_data);

        // $roomdetails_data = Datatables::of($roomdetails_data)
        //     ->editColumn('created_at', function(RoomDetails $roomdetails_data) {
        //         return $roomdetails_data->created_at->format('d M Y');
        //     })
        //     ->with([
        //         'recordsTotal' => intval($totalData),
        //         'recordsFiltered' => intval($totalFiltered),
        //     ])
        //     ->make(true);
        // return $roomdetails_data;
    }
}