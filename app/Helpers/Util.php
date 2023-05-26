<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Log;

class Util
{
    public static function CheckTanggalMerah($value) {
        $array = json_decode(file_get_contents(env("TANGGAL_MERAH")), true);
    
        if(isset($array[$value]))
            return true;
        else
            return false;
    }

    public static function sendMessageHTML($data)
    {
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage"
            . "?disable_web_page_preview=true&chat_id=" . env('TELEGRAM_CHAT_ID')
            . "&text=" . urlencode($data['text']);
        if(isset($data['reply_to_message_id']))
            $url .= "&reply_to_message_id=" . $data['reply_to_message_id'];
        file_get_contents($url."&parse_mode=html");
    }

    public static function sendMessageHTMLPulang($data)
    {
        $sendMessageUrl = 'https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN') . '/sendMessage';
        $html = file_get_contents(base_path()."/public/templateMauPulang.html");
        $parameters = array(
          'reply_to_message_id' => $data['reply_to_message_id'],
          'chat_id' => env('TELEGRAM_CHAT_ID'),
          'parse_mode' => 'HTML',
          'text' => $html
        );

        Log::info('----mauPulang----');
        Log::info('post: '.json_encode($parameters));
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $sendMessageUrl);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        curl_close($curl);
        Log::info('----mauPulang----');
    }

    public static function sendMessage($data)
    {
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage"
            . "?disable_web_page_preview=true&chat_id=" . env('TELEGRAM_CHAT_ID')
            . "&text=" . urlencode($data['text']);
        if(isset($data['reply_to_message_id']))
            $url .= "&reply_to_message_id=" . $data['reply_to_message_id'];
        file_get_contents($url);
    }

    public static function sendPhoto($data)
    {
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto"
            . "?chat_id=" . env('TELEGRAM_CHAT_ID')
            . "&photo=" . urlencode($data['photo']);
            if(isset($data['caption']))
                $url .= "&caption=" . $data['caption'];
            if(isset($data['parse_mode']))
                $url .= "&parse_mode=" . $data['parse_mode'];
            if(isset($data['reply_to_message_id']))
                $url .= "&reply_to_message_id=" . $data['reply_to_message_id'];
        file_get_contents($url);
    }

    public static function sendPhotoCurl($data)
    {
        $bot_url    = "https://api.telegram.org/bot". env('TELEGRAM_BOT_TOKEN') . "/";
        $url        = $bot_url . "sendPhoto?chat_id=" . env('TELEGRAM_CHAT_ID') ;

        $post_fields = array('chat_id'   => env('TELEGRAM_CHAT_ID'),
            'photo'                   => $data['photo'],
            'reply_to_message_id'     => $data['reply_to_message_id'],
        );

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type:multipart/form-data"
        ));
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 
        $output = curl_exec($ch);
        $err = curl_error($ch);
    
        curl_close($ch);
    
        if ($err) {
            Log::info('----mauJadiGambar----');
            Log::info("cURL Error #:" . $err);
        }
    }

    public static function sendPhotoKutipan($data)
    {
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto"
            . "?chat_id=" . env('TELEGRAM_CHAT_ID')
            . "&photo=" . $data['photo'];
            if(isset($data['reply_to_message_id']))
                $url .= "&reply_to_message_id=" . $data['reply_to_message_id'];
        file_get_contents($url);
    }

    public static function sendAnimation($data)
    {
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendAnimation"
            . "?chat_id=" . env('TELEGRAM_CHAT_ID')
            . "&animation=" . urlencode($data['animation']);
            if(isset($data['caption']))
                $url .= "&caption=" . $data['caption'];
            if(isset($data['parse_mode']))
                $url .= "&parse_mode=" . $data['parse_mode'];
            if(isset($data['reply_to_message_id']))
                $url .= "&reply_to_message_id=" . $data['reply_to_message_id'];
        file_get_contents($url);
    }

    public static function getHargaCrypto()
    {
        $curl = curl_init();
        $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.pintu.co.id/v2/trade/price-changes",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_USERAGENT => $agent
        ));

    
        $response = curl_exec($curl);
        $err = curl_error($curl);
    
        curl_close($curl);
    
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public static function checkPositifNumber($number) {
        if($number>0)
            return "+".$number;
        else
            return $number;
    }

    public static function format_number($number) {
        return number_format($number, 0, ',', '.');
    }

    public static function cek_hari($hari) {
        switch($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
            break;
    
            case 'Mon':			
                $hari_ini = "Senin";
            break;
    
            case 'Tue':
                $hari_ini = "Selasa";
            break;
    
            case 'Wed':
                $hari_ini = "Rabu";
            break;
    
            case 'Thu':
                $hari_ini = "Kamis";
            break;
    
            case 'Fri':
                $hari_ini = "Jumat";
            break;
    
            case 'Sat':
                $hari_ini = "Sabtu";
            break;
            
            default:
                $hari_ini = "Tidak di ketahui";		
            break;
        }
        return $hari_ini;
    }

    public static function get_cuaca($id) {
        $data = [
            "0" => "Cerah / Clear Sky",
            "1" => "Cerah Berawan / Partly Cloudy",
            "2" => "Cerah Berawan / Partly Cloudy",
            "3" => "Berawan / Mostly Cloudy",
            "4" => "Berawan Tebal / Overcast",
            "5" => "Udara Kabur / Haze",
            "10" => "Asap / Smoke",
            "45" => "Kabut / Fog",
            "60" => "Hujan Ringan / Light Rain",
            "61" => "Hujan Sedang / Rain",
            "63" => "Hujan Lebat / Heavy Rain",
            "80" => "Hujan Lokal / Isolated Shower",
            "95" => "Hujan Petir / Severe Thunderstorm",
            "97" => "Hujan Petir / Severe Thunderstorm"
        ];

        if(isset($data[$id]))
            return $data[$id];
        else
            return "Tidak di ketahui";
    }
    
    public static function isWeekend($date) {
        $weekDay = date('w', strtotime($date));
        return ($weekDay == 0 || $weekDay == 6);
    }

    public static function validateDate($date, $format = 'd/m/Y_H:i'){
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public static function send($method, $data)
    {
        $url = "https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN'). "/" . $method;

        if (!$curld = curl_init()) {
            exit;
        }
        Log::info('----mauTest----');
        Log::info('post: '.json_encode($data));
        Log::info('url: '.$url);
        curl_setopt($curld, CURLOPT_POST, true);
        curl_setopt($curld, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curld, CURLOPT_URL, $url);
        curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curld);
        curl_close($curld);
        Log::info('----mauTest----');
        return $output;
    }
}
