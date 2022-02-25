<?php

namespace App\Helpers;

class Util
{
    public static function CheckTanggalMerah($value) {
        $array = json_decode(file_get_contents(env("TANGGAL_MERAH")), true);
    
        if(isset($array[$value]))
            return true;
        else
            return false;
    }

    public static function sendMessage($data)
    {
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage"
            . "?chat_id=" . env('TELEGRAM_CHAT_ID')
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
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.pintu.co.id/v2/trade/price-changes",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
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

    public static function cek_hari() {
        $hari = date ("D");
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
}
