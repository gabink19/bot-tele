<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Log;

class Util
{
    public static function sendMessage($data,$chatId)
    {
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage"
            . "?disable_web_page_preview=true&chat_id=" .$chatId
            . "&text=" . urlencode($data['text']);
        if(isset($data['reply_to_message_id']))
            $url .= "&reply_to_message_id=" . $data['reply_to_message_id'];
        file_get_contents($url."&parse_mode=html");
    }

    public static function sendPhoto($data,$chatId)
    {
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto"
            . "?chat_id=" . $chatId
            . "&photo=" . urlencode($data['photo']);
            if(isset($data['caption']))
                $url .= "&caption=" . $data['caption'];
            if(isset($data['parse_mode']))
                $url .= "&parse_mode=" . $data['parse_mode'];
            if(isset($data['reply_to_message_id']))
                $url .= "&reply_to_message_id=" . $data['reply_to_message_id'];
        file_get_contents($url);
    }

    public static function send($method, $data)
    {
        $url = "https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN'). "/" . $method;

        if (!$curld = curl_init()) {
            exit;
        }
        curl_setopt($curld, CURLOPT_POST, true);
        curl_setopt($curld, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curld, CURLOPT_URL, $url);
        curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curld);
        curl_close($curld);
        return $output;
    }
}
