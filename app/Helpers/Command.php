<?php

namespace App\Helpers;

use Carbon\Carbon as Carbon;
use Exception;

use Illuminate\Support\Facades\Log;

class Command
{
    public function __construct() 
    {

    }

    public static function ListActions($data = null, $i)
    {
        switch($i) {
            case "0" : 
                return self::waktuSholat();
                break;
            default :
                "nothing";
            }
    }

    public static function ListCommands()
    {
        return [
            '/waktuSholat' => [
                'deskripsi' => 'Waktu Sholat',
                'type' => 'text'
            ]
        ];
    }

    public static function waktuSholat()
    {
        $date = date("Y/m/d");
        $url = "https://api.myquran.com/v1/sholat/jadwal/1227/".$date;
        $client = new \GuzzleHttp\Client();
        $resp = $client->request('GET', $url);
        $data = json_decode($resp->getBody()->getContents(), true);

        $response = "Informasi Jadwal Sholat Hari Ini \n";
        $response .= "Waktu : ".$data['data']['jadwal']['tanggal']." \n\n";
        $response .= "Wilayah : ".$data['data']['lokasi']." \n";
        $response .= "Imsak : ".$data['data']['jadwal']['imsak']."\n";
        $response .= "Subuh : ".$data['data']['jadwal']['subuh']."\n";
        $response .= "Dzuhur : ".$data['data']['jadwal']['dzuhur']."\n";
        $response .= "Ashar : ".$data['data']['jadwal']['ashar']."\n";
        $response .= "Maghrib : ".$data['data']['jadwal']['maghrib']."\n";
        $response .= "Isya : ".$data['data']['jadwal']['isya']."\n\n";


        $url = "https://api.myquran.com/v1/sholat/jadwal/1108/".$date;
        $client = new \GuzzleHttp\Client();
        $resp1 = $client->request('GET', $url);
        $data = json_decode($resp1->getBody()->getContents(), true);

        $response .= "Wilayah : ".$data['data']['lokasi']." \n";
        $response .= "Imsak : ".$data['data']['jadwal']['imsak']."\n";
        $response .= "Subuh : ".$data['data']['jadwal']['subuh']."\n";
        $response .= "Dzuhur : ".$data['data']['jadwal']['dzuhur']."\n";
        $response .= "Ashar : ".$data['data']['jadwal']['ashar']."\n";
        $response .= "Maghrib : ".$data['data']['jadwal']['maghrib']."\n";
        $response .= "Isya : ".$data['data']['jadwal']['isya']."\n";

        return $response;
    }

    public static function getWaktu()
    {
        return [
            'now'           => Carbon::parse(date("Y-m-d H:i:s")),
            'masuk'         => Carbon::parse('09:00:00'),
            'batas_masuk'   => Carbon::parse('10:00:00'),
            'pulang'        => Carbon::parse('18:00:00'),
            'batas_pulang'  => Carbon::parse('23:59:59')
        ];
    }

    public static function mauStart($value='')
    {
        $idx = 0;
        $idxs = 0;
        $keyboard = [];
        foreach (self::ListCommands() as $key => $value) {
            $keyboard['inline_keyboard'][$idx][$idxs]['text'] = $value['deskripsi'];
            $keyboard['inline_keyboard'][$idx][$idxs]['callback_data'] = $key;
            $idxs++;
            if ($idxs==3) {
                $idxs = 0;
                $idx++;
            }

        }
        $encodedKeyboard = json_encode($keyboard);
        $parameters = 
            array(
                'chat_id' => env('TELEGRAM_CHAT_ID'), 
                'text' => 'Silahkan Pilih Menu :', 
                'reply_markup' => $encodedKeyboard
            );

        Util::send('sendMessage', $parameters); // function description Below
    }

    private static function getData()
    {
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets API');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $path = app()->basePath('credentials.json');
        $client->setAuthConfig($path);

        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = '1G7HXIgqe3zyH2QVOHEB959YEANI1cmtlUgcKSzpP-L8';
        $range = 'Data Baru!A3:N3';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $header = $response->getValues();

        $range = 'Data Baru!A4:N1000';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        $data = [];
        $idx = 0;
        foreach ($values as $key => $value) {
            foreach ($value as $k => $val) {
                $data[$idx][$header[0][$k]] = self::removeRp($val);
            }
            if (date('d') > 25) {
                if($value[0]==date('F Y')) break;
            }else{  
                if($value[0]==date('F Y',strtotime('-1 month'))) break;
            }
            $idx++;
        }
        $jsonString = json_encode($data, JSON_UNESCAPED_UNICODE);

        return str_replace('"','\"',$jsonString);
    }

    private function removeRp($val){
        $val = str_replace(',','',$val);
        $val = str_replace('Rp ','',$val);
        
        return is_numeric($val)?(int)$val:$val;
    }

    public static function chatGPT($message='')
    {         
        if ($message!='') {   
            if (strpos($message, '--data') == true) {
                $message = str_replace('--data','',$message);
                $message .= " ".self::getData();
            } 
            $url        = "https://api.openai.com/v1/chat/completions";
            $authorization = "Authorization: Bearer ".env("TOKEN_GPT");
            $payload    = '{
                             "model": "gpt-3.5-turbo",
                             "messages": [{"role": "user", "content": "'.$message.'"}] 
                            }';
            $ch         = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',$authorization));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result     = curl_exec($ch);
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
            }
            $result     = json_decode($result, true);
            curl_close ($ch);
        }

        if (isset($error_msg)) {
            try {
                $fh = fopen("Logs/"."Chat-".date("d-m-Y").".txt", "w") or die("Unable to open file!");;
                fwrite($fh, json_encode($error_msg).",\r\n");
                fclose($fh);
            } catch (\Exception $e) {
                
            }
        }   
            try {
                $fh = fopen("Logs/"."Chat-".date("d-m-Y").".txt", "w") or die("Unable to open file!");;
                fwrite($fh, json_encode($result).",\r\n");
                fclose($fh);
            } catch (\Exception $e) {
                
            }

        return (isset($result['choices'][0]['message']['content']))?$result['choices'][0]['message']['content']:"Sini Curhat....";
    }
}