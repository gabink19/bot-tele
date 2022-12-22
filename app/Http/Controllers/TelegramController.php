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
                fwrite($fh, json_encode($updates).",\r\n");
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
                $response = "";
                if ($message == "/start" || strtolower($message) == "/start@".env('TELEGRAM_BOT_NAME')) {
                    $response = "Hello! I'm a bot. I'm here to help you to gabut maksimal.\n\n";
                    foreach(Command::ListCommands() as $key => $value) {
                        $response .= $key." - ".$value['deskripsi']."\n";
                    }
                    $is_send = true;
                }
                else {
                    $i = 0;
                    foreach(Command::ListCommands() as $key => $value) {
                        if (strtolower($message) == strtolower($key) || strtolower($message) == strtolower($key)."@".env('TELEGRAM_BOT_NAME')) {
                            $response = Command::ListActions($sender, $i);
                            $command = $key;
                            $is_send = true;
                            break;
                        }
                        $i++;
                    }
                }
            }
            if($is_send) {
                $data['reply_to_message_id'] = $reply_to_message_id;
                if($response == "Cek rekening BNI cuy") {
                    $data['caption'] = $response;
                    $data['animation'] = "https://motionisme.files.wordpress.com/2019/01/tenor-2-1.gif";

                    Util::sendAnimation($data);
                }
                else if(isset(Command::ListCommands()[$command]) && (Command::ListCommands()[$command]['type'] == 'image')) {
                    // if (strpos(strtolower($message), 'maucekgempa') !== false) {
                    //     $data['caption'] = $response[0];
                    //     $data['photo'] = $response[1];
                    // }
                    // else
                        $data['photo'] = $response;

                    Util::sendPhoto($data);
                }
                else {
                    $data['text'] = $response;
                    Util::sendMessage($data);
                }
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
        Command::mauSholat();
    }
}

