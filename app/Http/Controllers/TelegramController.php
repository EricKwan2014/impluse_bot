<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Api;
use Input;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Telegram\Bot\Objects\Message;

class TelegramController extends Controller
{
    protected $telegram;

    public function __construct( Api $telegram )
    {
        $this->telegram = $telegram;
    }

    public function getUpdates()
    {
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
        // $message = new Message($update->get('message'));
        if($updates->getMessage()->getChat()->getId()){
            $chatId = $updates->getMessage()->getChat()->getId();
        }else{
            $chatId = 456;
        }
        // $message = collect($updates)->last()->getMessage();
        // $chatId = $message->getChat()->getId();
        // $sender = $message->getFrom()->getUsername();
        // $text = $message->getText();
        // $str = substr($text, 1);
        // $str = strstr($str, '@', true);

        $response = $this->telegram->sendMessage([
          'chat_id' => '-147748587', 
          'text' => 'Testing'.$chatId
        ]);
        return $response;
    	// return response()->json($updates);
    }
}
