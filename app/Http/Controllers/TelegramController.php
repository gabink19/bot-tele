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
            if (!file_exists("Logs"))
                mkdir("Logs", 0775, true);

            try {
                $fh = fopen("Logs/"."Chat-".date("d-m-Y").".txt", "w") or die("Unable to open file!");;
                fwrite($fh, date('H:i:s :').json_encode($updates).",\r\n");
                fclose($fh);
            } catch (\Exception $e) {
                
            }
            $command = "";
            if(!isset($updates["message"]["text"]))
                $is_send = false;
            else {
                $message = $updates["message"]["text"];
                $reply_to_message_id = $updates["message"]["message_id"];
                $sender = $updates["message"]["from"];
                $chatId = $updates["message"]["chat"]["id"];
                $response = "";
                if ($message == "/start" || strtolower($message) == "/start@".env('TELEGRAM_BOT_NAME')) {
                    $response = Command::mauStart();
                    $response .= "\n"."Visit my latest repo : https://github.com/gabink19/bot-tele.git";
                    $is_send = false;
                }else {
                    $i = 0;
                    $found = false;
                    foreach(Command::ListCommands() as $key => $value) {
                        if (strtolower($message) == strtolower($key) || strtolower($message) == strtolower($key)."@".env('TELEGRAM_BOT_NAME')) {
                            $response = Command::ListActions($sender, $i);
                            $command = $key;
                            $is_send = true;
                            $found = true;
                            break;
                        }
                        $i++;
                    }
                    if (!$found) {
                        $response = Command::chatGPT($message);
                        $command = $key;
                        $is_send = true;
                        $found = true;
                    }
                }
            }
            if($is_send) {
                $data['reply_to_message_id'] = $reply_to_message_id;
                $data['text'] = $response;
                Util::sendMessage($data,$chatId);
                echo response()->json([
                    'status' => 'ok',
                    'data' => $data,
                    'message' => 'Send success'
                ], 200);
            }
            else {
                echo response()->json([
                    'status' => 'ok',
                    'data' => null,
                    'message' => 'Nothing to send'
                ], 200);
            }
            
            
        }
    }

    public function index()
    {
        $this->telegramWebhook();
    }

    public function test()
    {
       echo Command::waktuSholat();
    }
}

