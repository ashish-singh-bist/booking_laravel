<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use JsValidator;
use App\PropertyUrl;
use App\CustomConfig;
use GuzzleHttp\Client;

class PropertyUrlByCity extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
        $this->guzzle_client = new Client();
    }

    public function index()
    {
        return view('property_url.property_url_by_city');
    }

    public function getPropertyUrlsByCity(Request $request)
    {
    	$city = $request->get('city');
        $response_data = [];
		if($city!=null || $city!=''){
            $url = "https://www.booking.com/searchresults.de.html?dest_type=city&dtdisc=0&from_sf=1&group_adults=2&group_children=0&inac=0&index_postcard=0&label_click=undef&no_rooms=1&postcard=0&raw_dest_type=city&ss=" . urlencode($city) . "&ssb=empty&sshis=0&order=popularity";

            //Guzzle Code
            $guj_request = $this->guzzle_client->get($url);
            if($guj_request->getStatusCode() == 200){
             $html = $guj_request->getBody();
             $response_data = $this->parseProperties($html);
            }
		}

		$property_url_city = [];
		for($i=0; $i < count($response_data); $i++){
			$property_url_city[$i]['select'] = '';
            $property_url_city[$i]['img_url'] = $response_data[$i]['img_url'];
            $property_url_city[$i]['name'] = '<a target="_blank" href="' . "https://www.booking.com/" . $response_data[$i]['hotel_url'] . '">' . $response_data[$i]['name'] . '</a>';
            $property_url_city[$i]['hotel_id'] = $response_data[$i]['hotel_id'];
			$property_url_city[$i]['url'] = "https://www.booking.com/" . $response_data[$i]['hotel_url'];
            $property_url_city[$i]['review_count'] = $response_data[$i]['review_count'];
            $property_url_city[$i]['rating'] = $response_data[$i]['rating'];
            $property_url_city[$i]['address'] = $response_data[$i]['address'];
		}

		$json_data = array(
	                    "draw" => intval($request->input('draw')),
	                    "data" => $property_url_city,
	                    "city" => $city
                    );
            
        echo json_encode($json_data);
    }

    public function savePropertyUrlDetails(Request $request)
    {
        $update_count = 0;
        $skip_count = 0;

        $custom_config = CustomConfig::first();
        $parse_interval = 1;
        if($custom_config->parsing_interval != null){
            $parse_interval = $custom_config->parsing_interval;
        }
        $number_of_guests = 0;
        if($custom_config->number_of_guests != null){
            $number_of_guests = $custom_config->number_of_guests;
        }
        $str_length_stay = '';
        if($custom_config->str_length_stay != null){
            $str_length_stay = $custom_config->str_length_stay;
        }

        $properties_url = PropertyUrl::select('url')->get()->toArray();
        $property_arr = [];
        foreach ($properties_url as $value) {
            array_push($property_arr, $value["url"]);
        }
        
    	$property_urls =  $request->get('url');
    	$city = $request->get('city');

    	foreach($property_urls as $url){
            if (in_array($url, $property_arr)){
                $skip_count++;
            }else{
                $property_url_obj = new PropertyUrl();
                $property_url_obj->url = $url;
                $property_url_obj->city = $city;
                $property_url_obj->parse_interval = intval($parse_interval);
                $property_url_obj->number_of_guests = intval($number_of_guests);
                $property_url_obj->str_length_stay = $str_length_stay;
                $property_url_obj->save();
                $update_count++;
            }
    	}
    	$property_url_city = [];
    	//flash('Property url saved successfully!')->success()->important();
    	return  response()->json([
            'status' =>true,
            'message' => 'Property Url Inserted',
            'skip_count' => $skip_count,
            'insert_count' => $update_count
        ]);
    }

    function parseProperties($html)
    {
        $property_array = array();
        $page_count = 1;

        do {
            preg_match_all("/(<div[^>]+data-hotelid[^>]*>.+?class\W+(sr_rooms_table_block|room_details))/ims", $html, $tempmatches, PREG_SET_ORDER);
            foreach ($tempmatches as $tempval) 
            {
                $name='';$address='';$hotel_url='';$img_url='';$rating='';$review_count='';$hotel_id='';
                $property = array();
                $str=$tempval[1]."<br>";
                #<span class="sr-hotel__name" data-et-click="">SchlafGut AppartementHotel</span>
                if(preg_match("/<span[^>]+class\W+sr-hotel__name\W[^>]*>(.+?)<\/span>/is",$str,$temparr))
                {
                    $name=trim($temparr[1]);
                }
                #<div class="bui-review-score__text"> 189 reviews </div>
                //<span class="review-score-widget__subtext" role="link" aria-label=" from 194 reviews" data-et-view="ZCPQLOLOLOCcBUKZaZXeIJNZGCTKe:2">194 reviews</span>
                if(preg_match("/<div[^>]+class\W+bui-review-score__text\W[^>]*>(.+?)<\/div>/is",$str,$temparr)){
                    $review_count=trim($temparr[1]);
                }elseif(preg_match("/<span[^>]+class\W+review-score-widget__subtext\W[^>]*>(.+?)<\/span>/is",$str,$temparr)){
                    $review_count=trim($temparr[1]);
                }
                $review_count=preg_replace("/[^0-9,.]/", "", $review_count);

                ##<div class="bui-review-score__badge" role="link" aria-label="7.6"> 7.6 </div>
                //<span class="review-score-badge" role="link" aria-label="Bewertet mit 7,8">7,8</span>
                if(preg_match("/<div[^>]+class\W+bui-review-score__badge\W[^>]*>(.+?)<\/div>/is",$str,$temparr)){
                    $rating=trim($temparr[1]);
                }elseif(preg_match("/<span[^>]+class\W+review-score-badge\W[^>]*>(.+?)<\/span>/is",$str,$temparr)){
                    $rating=trim($temparr[1]);
                }
                #<img class="hotel_image" src="https://t-ec.bstatic.com/xdata/images/hotel/square200/123324327.jpg?k=527c64214efbda20a3051d59a919eca4ec104dec6936088c3b0aaa4be4d7558b&amp;o=" alt="
                if(preg_match("/(<img[^>]+class\W+hotel_image\W[^>]*)/is",$str,$temparr))
                {
                    $img_str=$temparr[1];
                    if(preg_match("/src\W+([^\'\"]+)/i",$img_str,$temparr))
                    {
                        $img_url=trim($temparr[1]);
                    }
                }
                if(preg_match("/(<a[^>]+class\W+[^\"\']*hotel_name_link\W[^>]+>)/is",$str,$temparr))
                {
                    $hotel_url_str=$temparr[1];
                    if(preg_match("/href\W+([^\'\"]+)/i",$hotel_url_str,$temparr))
                    {
                        $hotel_url=trim($temparr[1]);
                        $hotel_url=substr($hotel_url,0,strpos($hotel_url,"?",0));
                    }
                }

                #<div data-et-view="" class="sr_item sr_item_new sr_item_default sr_property_block sr_item_bs sr_flex_layout    sr_item_no_dates             " data-hotelid="56509" data-class="4" data-score="8.1">
                if(preg_match("/(<div[^>]+class\W+[^\"\']*sr_item_new\W[^>]+>)/is",$str,$temparr))
                {
                    $hotel_id_str=$temparr[1];

                    if(preg_match("/data-hotelid\W+([^\'\"]+)/i",$hotel_id_str,$temparr))
                    {                
                        $hotel_id=trim($temparr[1]);
                    }
                }

                #<div class="address">
                if(preg_match("/<div[^>]+class\W+address\W[^>]*>(.+?)<\/div>/is",$str,$temparr))
                {
                    $tmp_address=$temparr[1];
                    if(preg_match("/<a[^>]+class\W+[^\"\']*jq_tooltip\W[^>]+>(.+?)<\/a>/is",$str,$tmp_address))
                    {
                        $address=$tmp_address[1];
                    }                    
                }                

                $property['name'] = $name;
                $property['review_count'] = $review_count;
                $property['rating'] = $rating;
                $property['img_url'] = $img_url;
                $property['hotel_url'] = $hotel_url;
                $property['hotel_id'] = $hotel_id;
                $property['address'] = $address;

                array_push($property_array,$property);
                
            }// end for each
            $page_count++;
            if(count($property_array) >= 100){
                break;
            }

        }while ($html=$this->getNext($html,$page_count)); // end do while
        return $property_array;
    }

    function getNext($html,$page_count)
    {
        //<li class="bui-pagination__item bui-pagination__next-arrow">
        if(preg_match("/<li[^>]+class\W+[^\"\']*bui-pagination__next-arrow\W[^>]*>(.+?)<\/li>/is",$html,$temparr)){
            $temp_html=$temparr[1];

            //<a href="/searchresults.en-gb.html?label=gen173nr-1FCAEoggJCAlhYSDNYBGhsiAEBmAEHuAEGyAEM2AEB6AEB-AELkgIBeagCAw&sid=d36802aa73ad0b7e0de890b777b7fd28&city=-1817680&class_interval=1&dest_id=98&dest_type=country&dtdisc=0&from_sf=1&group_adults=2&group_children=0&inac=0&index_postcard=0&label_click=undef&no_rooms=1&postcard=0&raw_dest_type=country&room1=A%2CA&sb_price_type=total&search_selected=1&slp_r_match=0&src=searchresults&src_elem=sb&srpvid=e18b47ef15e7024c&ss=India&ss_all=0&ss_raw=india&ssb=empty&sshis=0&ssne_untouched=Leipzig&rows=15&offset=15" data-page-next class="bui-pagination__link paging-next ga_sr_gotopage_2_2703" title="Next page">

            if(preg_match("/href\W+([^\'\"]+)/i",$temp_html,$temparr))
            {
                $temp_next_page_url=trim($temparr[1]);
                $next_page_url = $temp_next_page_url;

                $guj_request = $this->guzzle_client->get($next_page_url);
                if($guj_request->getStatusCode() == 200){
                    return $guj_request->getBody();
                }else{
                    return 0;
                }
            }
        }
        return 0;
    }
}
