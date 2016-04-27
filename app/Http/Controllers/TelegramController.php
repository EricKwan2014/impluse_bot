<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Api;
use Input;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Response;

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
        dd($updates);
    }

    public function setWebhook()
    {
    	$response = $this->telegram->setWebhook(['url' => URL::to('/'.$this->telegram->getAccessToken().'/webhook')]);
    	dd($response);
    }

    public function getLastResponse()
    {
    	$response = $this->telegram->getLastResponse();
    	dd($response);
    }

    public function getWebhookUpdates()
    {
    	$updates = $this->telegram->getWebhookUpdates();
    	$response = Response::make($updates, 200);
		$response->header("Content-type: multipart/form-data");
    	dd($response);
    }
}
