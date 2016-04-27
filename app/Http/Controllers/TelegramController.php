<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Api;
use Input;

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
}
