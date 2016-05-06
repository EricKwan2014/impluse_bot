<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use willvincent\Feeds\Facades\FeedsFacade as Feeds;
use App\Http\Requests;
use Illuminate\Support\Facades\View;

class WeatherController extends Controller
{
    public function index() {
    	$json = json_decode(file_get_contents('http://api.openweathermap.org/v3/uvi/22.3,114.2/'.date('Y-m-d').'Z.json?appid='.config('app.weather_token')), true);
    	$feed = Feeds::make('http://www.who.int/feeds/entity/csr/don/zh/rss.xml', true);
    	$data = array(
	      // 'title'     => $feed->get_title(),
	      // 'permalink' => $feed->get_permalink(),
	      'items'     => $feed->get_items(),
	    );
	    $total = $feed->get_items()[0]->get_description();
	    $part_a = $total;
	    return $total;
    	// return dd($feed->data['items'][0]->data['child']['']['description'][0]['data']);
	    // return View::make('feeds', $data);
    }
}
