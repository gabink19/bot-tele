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
        $upd = $updates;
        $today = date('Y-m-d');
        $thr = '2023-04-06';
        // if (isset($upd["callback_query"])) {
        //     if (!file_exists("Logs"))
        //         mkdir("Logs", 0775, true);

        //     try {
        //         $fh = fopen("Logs/"."Chat-".date("d-m-Y").".txt", "w") or die("Unable to open file!");;
        //         fwrite($fh, date('H:i:s :').json_encode($upd).",\r\n");
        //         fclose($fh);
        //     } catch (\Exception $e) {
                
        //     }
        //     $updates["message"]["text"] = $upd["callback_query"]["data"];
        //     $updates["message"]["message_id"] = $upd["callback_query"]["message"]["message_id"];
        //     $updates["message"]["from"] = $upd["callback_query"]["message"]["from"];
        // }
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
                $response = "";
                if ($message == "/start" || strtolower($message) == "/start@".env('TELEGRAM_BOT_NAME')) {
                    $response = "Hello! I'm a bot. I'm here to help you to gabut maksimal.\n\n";
                    foreach(Command::ListCommands() as $key => $value) {
                        $response .= $key." - ".$value['deskripsi']."\n";
                    }
                    $response .= "\n"."Visit my latest repo : https://github.com/gabink19/bot-tele.git";
                    $is_send = true;
                }else if (strpos(strtolower($message), "/maucurhat") !== false) {
                    $pesan = str_replace("/maucurhat", "", strtolower($message));
                    $pesan = str_replace("@dewagabutbot", "", strtolower($pesan));
                    $response = Command::mauCurhat(ltrim($pesan));
                    $is_send = true;
                }else if (strpos(strtolower($message), "/maukuis") !== false) {
                    $pesan = str_replace("/maukuis", "", strtolower($message));
                    $pesan = str_replace("@dewagabutbot", "", strtolower($pesan));
                    // $response = Command::mautest(ltrim($pesan));
                    $is_send = false;
                }else if (strpos(strtolower($message), "/maureminder") !== false) {
                    $pesan = str_replace("/maureminder", "", strtolower($message));
                    $pesan = str_replace("@dewagabutbot", "", strtolower($pesan));
                    $response = Command::mauReminder(ltrim($pesan),$sender);
                    $is_send = true;
                }else if (strpos(strtolower($message), "/mauloker") !== false) {
                    $pesan = str_replace("/mauloker", "", strtolower($message));
                    $pesan = str_replace("@dewagabutbot", "", strtolower($pesan));
                    $response = Command::mauLoker(ltrim($pesan));

                    $data['reply_to_message_id'] = $reply_to_message_id;
                    $data['text'] = $response;
                    Util::sendMessageHTML($data);
                    echo response()->json([
                        'status' => 'ok',
                        'data' => $data,
                        'message' => 'Send success'
                    ], 200);
                    die();
                }else if (strpos(strtolower($message), "/maujadikutipan") !== false) {
                    if (isset($updates["message"]["reply_to_message"]["text"])) {
                        $response = Command::mauJadiKutipan($updates["message"]["reply_to_message"]["text"],$updates["message"]["reply_to_message"]["from"]["id"],$updates["message"]["reply_to_message"]["from"]["username"]." - (".$updates["message"]["reply_to_message"]["from"]["first_name"]." ".@$updates["message"]["reply_to_message"]["from"]["last_name"].")");

                        $reply_to_message_id = $updates["message"]["reply_to_message"]["message_id"];
                        $data['reply_to_message_id'] = $reply_to_message_id;
                        if ($response!='') {
                            $data['photo'] = $response;
                            Util::sendPhotoKutipan($data);
                            echo response()->json([
                                'status' => 'ok',
                                'data' => $data,
                                'message' => 'Send success'
                            ], 200);
                            die();
                        }
                    }
                    $response = "Reply pesan yang ingin dijadikan kutipan, dengan minimal 3 kata dan maksimal 15 kata.";
                    $data['text'] = $response;
                    Util::sendMessage($data);
                    echo response()->json([
                        'status' => 'ok',
                        'data' => $data,
                        'message' => 'Send success'
                    ], 200);
                    die();
                }else if (strpos(strtolower($message), "/maujadigambar") !== false) {
                    $pesan = str_replace("/maujadigambar", "", strtolower($message));
                    $pesan = str_replace("@dewagabutbot", "", strtolower($pesan));
                    if (isset($updates["message"]["reply_to_message"]["text"])) {
                        $pesan = $updates["message"]["reply_to_message"]["text"];
                        $reply_to_message_id = $updates["message"]["reply_to_message"]["message_id"];
                    }
                    $response = Command::mauJadiGambar($pesan);
                    if ($response!='') {
                        $data['reply_to_message_id'] = $reply_to_message_id;
                        $data['photo'] = $response;
                        Util::sendPhotoCurl($data);
                        echo response()->json([
                            'status' => 'ok',
                            'data' => $data,
                            'message' => 'Send success'
                        ], 200);
                        die();
                    }
                }
                else {
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
                    if ($found==false) {
                        if (strpos(strtolower($message), "/mau") !== false) {
                            $pesan = str_replace("/mau", "", strtolower($message));
                            $response = Command::mauRandom(ltrim($pesan));
                            if ($response!='') {
                                $data['reply_to_message_id'] = $reply_to_message_id;
                                $data['photo'] = $response;
                                Util::sendPhoto($data);
                                echo response()->json([
                                    'status' => 'ok',
                                    'data' => $data,
                                    'message' => 'Send success'
                                ], 200);
                                die();
                            }
                        }
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
                }else if($message == "/mauthr" && $today==$thr){
                    $data['caption'] = $response;
                    $data['animation'] = "https://motionisme.files.wordpress.com/2019/01/tenor-2-1.gif";

                    Util::sendAnimation($data);
                }else {
                    if ($response=='htmlPulang') {
                        $data['text'] = '';
                        // Util::sendMessageHTML($data);
                    }else{
                        $data['text'] = $response;
                        Util::sendMessage($data);
                    }
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
        echo '<img src="'.Command::mauJadiGambar().'" alt="Girl in a jacket" width="500" height="600">';
    }
    public function testgaji()
    {
       echo Command::mauThr();
    }
}

