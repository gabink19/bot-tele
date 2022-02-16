<?php

namespace App\Http\Controllers;

use App\Helpers\Command;
use App\Helpers\Util;

class TelegramController extends Controller
{
    private function telegramWebhook()
    {
        $updates = json_decode(file_get_contents('php://input'), true);
        if (!empty($updates["message"])) {
            $chat_id = $updates["message"]["chat"]["id"];
            $message = $updates["message"]["text"];
            $response = "";
            if ($message == "/start") {
                $response = "Hello! I'm a bot. I'm here to help you to gabut maksimal.\n\n";
                foreach(Command::ListCommands() as $key => $value) {
                    $response .= $key." - ".$value['deskripsi']."\n";
                }
            }
            else if(isset(Command::ListCommands()[$message]))
                $response = Command::ListCommands()[$message]['action'];
            else
                $response = "Sorry, I don't understand you.\n";

            $data = ['chat_id' => $chat_id];

            if(isset(Command::ListCommands()[$message]) && (Command::ListCommands()[$message]['type'] == 'image')) {
                $data['photo'] = $response;
                Util::sendPhoto($data);
            }
            else {
                $data['text'] = $response;
                Util::sendMessage($data);
            }
        }
    }

    public function index()
    {
        $this->telegramWebhook();
    }

    public function test()
    {
        Command::mauCat();
    }
}
