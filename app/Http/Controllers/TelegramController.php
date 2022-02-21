<?php

namespace App\Http\Controllers;

use App\Helpers\Command;
use App\Helpers\Util;

class TelegramController extends Controller
{
    private function telegramWebhook()
    {
        $is_send = false;
        $updates = json_decode(file_get_contents('php://input'), true);
        if (!empty($updates["message"])) {
            $fh = fopen("request.txt", "a");
            fwrite($fh, json_encode($updates).",\r\n");
            fclose($fh);
            if(!isset($updates["message"]["text"]))
                $is_send = false;
            else {
                $message = $updates["message"]["text"];
                $response = "";
                if ($message == "/start") {
                    $response = "Hello! I'm a bot. I'm here to help you to gabut maksimal.\n\n";
                    foreach(Command::ListCommands() as $key => $value) {
                        $response .= $key." - ".$value['deskripsi']."\n";
                    }
                    $is_send = true;
                }
                else {
                    foreach(Command::ListCommands() as $key => $value) {
                        if (strpos(strtolower($message), strtolower($key)) !== false) {
                            $response = $value['action'];
                            $is_send = true;
                        }
                    }
                }
            }

            if($is_send) {
                if(isset(Command::ListCommands()[$message]) && (Command::ListCommands()[$message]['type'] == 'image')) {
                    $data['photo'] = $response;
                    //Util::sendPhoto($data);
                }
                else {
                    $data['text'] = $response;
                    //Util::sendMessage($data);
                }
            }
            
            return response()->json([
                'status' => 'ok',
                'data' => $data,
                'message' => 'Send success'
            ], 200);
        }
        
        return response()->json([
            'status' => 'ok',
            'data' => null,
            'message' => 'Nothing to send'
        ]);
    }

    public function index()
    {
        $this->telegramWebhook();
    }
}
