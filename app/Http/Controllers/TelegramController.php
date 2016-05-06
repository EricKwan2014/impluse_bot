<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use willvincent\Feeds\Facades\FeedsFacade as Feeds;
use Telegram\Bot\Api;
use Input;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Actions;

class TelegramController extends Controller
{
    protected $telegram;

    public function __construct( Api $telegram )
    {
        $this->telegram = $telegram;
    }

    public function getUpdates()
    {
        $disasters_feed = Feeds::make('http://www.who.int/feeds/entity/csr/don/zh/rss.xml', true);
        $disasters_news = $disasters_feed->get_items()[0]->get_description();


        $feed = Feeds::make('http://rss.weather.gov.hk/rss/CurrentWeather_uc.xml', true);
        $data = array(
          // 'title'     => $feed->get_title(),
          // 'permalink' => $feed->get_permalink(),
          'items'     => $feed->get_items(),
        );
        // $response = $this->telegram->sendMessage([
        //   'chat_id' => '-147748587', 
        //   'text' => $feed->data['items'][0]->data['child']['']['description'][0]['data']
        // ]);
        $this->telegram->sendChatAction([
          'chat_id' => '-147748587',
          'action' => Actions::RECORD_VIDEO
        ]);
        return $this->telegram->getUpdates();

        $updates = $this->telegram->getUpdates();
        $message = collect($updates)->last()->getMessage();
        $chatId = $message->getChat()->getId();
        $sender = $message->getFrom()->getUsername();
        $text = $message->getText();
        $str = substr($text, 1);
        $str = strstr($str, '@', true);

        $response = $this->telegram->sendMessage([
          'chat_id' => $chatId, 
          'text' => $sender.' said '.$str.' is hehe.'
        ]);
        return $response;
        // dd($updates);
    }

    public function setWebhook()
    {
        $response = $this->telegram->setWebhook(['url' => URL::to('/'.$this->telegram->getAccessToken().'/webhook')]);
        dd($response);
    }

    public function removeWebhook()
    {
        $response = $this->telegram->removeWebhook();
        dd($response);
    }

    public function getLastResponse()
    {
    	$response = $this->telegram->getLastResponse();
    	dd($response);
    }

    public function getWebhookUpdates(Request $request)
    {
    	$updates = $this->telegram->getWebhookUpdates();
        $message = $updates->getMessage();
        $chatId = $message->getChat()->getId();
        $sender = $message->getFrom()->getUsername();
        $text = $message->getText();
        $str = substr($text, 1);
        $str = strstr($str, '@', true);

        $disasters_feed = Feeds::make('http://www.who.int/feeds/entity/hac/en/rss.xml', true);
        $disasters_news = $disasters_feed->get_items()[0]->get_description();

        $reminder_help = "/remind (用家) (時間之後) (動作)\n e.g /remind Eric tomorrow_3pm take_drug";
        $air_pollution_help = '空氣品質指數是定量描述空氣品質狀況的非線性無量綱指數。其數值越大、級別和類別越高、表征顏色越深，說明空氣污染狀況越嚴重，對人體的健康危害也就越大。
由於顆粒物沒有小時濃度標準，基於24小時平均濃度計算的AQI相對於空氣品質的小時變化會存在一定的滯後性，因此，當首要污染物為PM2.5和PM10時，在看AQI的同時還要兼顧其實時濃度數據。相關單位為彌補滯後性，同時發布了「實時空氣品質指數」，所有污染物均採用當前1小時平均濃度計算。要注意「實時空氣品質指數」不是AQI。
需要說明的是，AQI的計算結果很大程度上取決於相應地區空氣品質分指數及對應的污染物項目濃度指數表，最終的計算結果需要參考相應的濃度指數表才具有實際意義。 對於中國，AQI與原來發布的空氣污染指數（API）有著很大的區別。AQI分級計算參考的標準是GB 3095-2012《環境空氣品質標準》（現行），參與評價的污染物為SO2、NO2、PM10、PM2.5、O3、CO等六項，每小時發布一次；而API分級計算參考的標準是GB 3095-1996《環境空氣品質標準》（已作廢），評價的污染物僅為SO2、NO2和PM10等三項，每天發布一次。因此，AQI採用的標準更嚴、污染物指標更多、發布頻次更高，其評價結果也將更加接近公眾的真實感受。'
        $air_pollution = $json = json_decode(file_get_contents('http://api.openweathermap.org/pollution/v1/o3/22.15,114.10/current.json?appid='.config('app.weather_token')), true);

        if(preg_match('/remind/',$str)){
            if(preg_match('/6/', $str)){
                sleep(6);
                $response = $this->telegram->sendMessage([
                  'chat_id' => $chatId,
                  'text' => $sender . ' remind that Silver need to take drug'
                ]);
            }
            if(preg_match('/12/', $str)){
                sleep(12);
                $response = $this->telegram->sendMessage([
                  'chat_id' => $chatId,
                  'text' => $sender . ' remind that Silver need to take drug'
                ]);
            }
            if(preg_match('/18/', $str)){
                sleep(18);
                $response = $this->telegram->sendMessage([
                  'chat_id' => $chatId,
                  'text' => $sender . ' remind that Silver need to take drug'
                ]);
            }
        }
        if(preg_match('/debug/',$str)){
            $response = $this->telegram->sendMessage([
              'chat_id' => $chatId,
              'text' => $str . 'AND' . $debug
            ]);
        }
        if($str=='disaster_news'){
            $response = $this->telegram->sendMessage([
              'chat_id' => $chatId,
              'text' => $disasters_news
            ]);
        }
        if($str=='reminder_help'){
            $response = $this->telegram->sendMessage([
              'chat_id' => $chatId,
              'text' => $reminder_help
            ]);
        }
        if($str=='air_pollution_help'){
            $response = $this->telegram->sendMessage([
              'chat_id' => $chatId,
              'text' => $air_pollution_help
            ]);
        }
        if($str=='air_pollution'){
            $response = $this->telegram->sendMessage([
              'chat_id' => $chatId,
              'text' => $air_pollution['data']
            ]);
        }
        if($str=='oscar' || $str=='silver'){
            $response = $this->telegram->sendMessage([
              'chat_id' => $chatId,
              'text' => $sender.' said '.$str.' is hehe.'
            ]);
        }elseif($str=='eric'){
            $response = $this->telegram->sendMessage([
              'chat_id' => $chatId,
              'text' => $sender.' said '.$str.' is free rider.'
            ]);
        }else{
            $response = null;
        }
        
        return $response;
    	// return response()->json($updates);
    }
}
