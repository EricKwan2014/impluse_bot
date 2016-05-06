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
        $debug = strstr($str, '@', true);

        $disasters_feed = Feeds::make('http://www.who.int/feeds/entity/hac/en/rss.xml', true);
        $disasters_news = $disasters_feed->get_items()[0]->get_description();

        $reminder_help = "/remind (用家) (時間) (動作)\n e.g /remind Eric tomorrow_3pm take_drug";

        if(preg_match('/debug/',$str)){
            $response = $this->telegram->sendMessage([
              'chat_id' => $chatId,
              'text' => $str . 'AND' . $debug
            ]);
        }
        if($str=='disasters_news'){
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
