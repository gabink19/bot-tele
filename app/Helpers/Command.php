<?php

namespace App\Helpers;

use Carbon\Carbon as Carbon;

class Command
{
    public function __construct() 
    {

    }

    public static function ListCommands()
    {
        return [
            '/mauPulang' => [
                'deskripsi' => 'Cek waktu pulang kerja',
                'type' => 'text',
                'action' => self::mauPulang()
            ],
            '/mauGajian' => [
                'deskripsi' => 'Cek waktu gajian',
                'type' => 'text',
                'action' => self::mauGajian()
            ],
            '/mauLibur' => [
                'deskripsi' => 'Cek waktu libur bulan ini',
                'type' => 'text',
                'action' => self::mauLibur()
            ],
            '/mauOT' => [
                'deskripsi' => 'Untuk keperluan overtime',
                'type' => 'image',
                'action' => self::mauOT()
            ],
            '/mauCrypto' => [
                'deskripsi' => 'Untuk melihat harga crypto',
                'type' => 'text',
                'action' => self::mauCrypto()
            ],
            '/mauCovid' => [
                'deskripsi' => 'Untuk melihat statistik covid-19 di Indonesia',
                'type' => 'text',
                'action' => self::mauCovid()
            ],
            '/mauLiburan' => [
                'deskripsi' => 'Untuk melihat info liburan',
                'type' => 'image',
                'action' => self::mauLiburan()
            ],
            '/mauCat' => [
                'deskripsi' => 'Untuk melihat gambar kucing',
                'type' => 'image',
                'action' => self::mauCat()
            ],
            '/mauDog' => [
                'deskripsi' => 'Untuk melihat gambar anjing',
                'type' => 'image',
                'action' => self::mauDog()
            ],
            '/mauGabut' => [
                'deskripsi' => 'Cek kuyyy',
                'type' => 'text',
                'action' => self::mauGabut()
            ],
        ];
    }

    public static function getWaktu()
    {
        return [
            'now'       => Carbon::parse(date("Y-m-d H:i:s")),
            'masuk'     => Carbon::parse('09:00:00'),
            'pulang'    => Carbon::parse('18:00:00')
        ];
    }

    public static function mauGajian()
    {
        $month  = date("Y-m");
        $start  = Carbon::parse($month)->startOfMonth();
        $end    = Carbon::parse($month)->endOfMonth();

        while ($start->lte($end)) {
            $carbon = Carbon::parse($start);
            if (($carbon->isWeekend() != true) && !Util::CheckTanggalMerah($carbon->format("Ymd"))) 
                $last_work = $start->copy()->format('Y-m-d');
            
            $start->addDay();
        }

        $last_work = Carbon::parse($last_work." 23:59:59");
        $sisa_gajian = $last_work->diffInDays(self::getWaktu()['now']);

        if($sisa_gajian == "0")
            $response = "Cek rekening BNI cuy";
        else if($sisa_gajian == "1")
            $response = "Besok gajian cuy";
        else if(self::getWaktu()['now'] > $last_work)
            $response = "Kan udah gajian bulan ini cuy";
        else
            $response = $sisa_gajian . " hari lagi gajiannya cuy";

        return $response;
    }

    public static function mauOT() 
    {
        // return 'https://picsum.photos/200/300?random='.rand(1,1000).'.jpg';
        // return 'https://random.imagecdn.app/500/150';
        return 'https://i0.wp.com/warindo.de/wp-content/uploads/2021/01/Bir-Bintang-330ml.jpg';
    }

    public static function mauGabut() 
    {
        return 'https://5.182.209.164/category/box-office';
    }

    public static function mauCrypto() 
    {
        $list_crypto = [
            'btc/idr' => 'Bitcoin',
            'eth/idr' => 'Ethereum',
            'bnb/idr' => 'BNB',
            'sand/idr' => 'The Sandbox',
            'doge/idr' => 'Dogecoin'
        ];
        $data = Util::getHargaCrypto();
        $data = json_decode($data, true);
        $response = "Informasi Crypto :\n\n";
        foreach($data['payload'] as $key => $value) {
            if (array_key_exists($value['pair'], $list_crypto))
                $response .= $list_crypto[$value['pair']]." : Rp. ". Util::format_number($value['latestPrice'])." (".Util::checkPositifNumber($value['day'])."%)\n";
        }
        return $response;
    }

    public static function mauCovid()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://dekontaminasi.com/api/id/covid19/stats');
        $data = json_decode($response->getBody()->getContents(), true);
        $response = "Data Covid-19 di Indonesia (".date("d-m-Y").") :\n\n";
        $response .= "Positif : ".Util::format_number($data['numbers']['infected'])."\n";
        $response .= "Sembuh : ".Util::format_number($data['numbers']['recovered'])."\n";
        $response .= "Dalam Perawatan : ".Util::format_number($data['numbers']['infected']-$data['numbers']['recovered']-$data['numbers']['fatal'])."\n";
        $response .= "Meninggal : ".Util::format_number($data['numbers']['fatal'])."\n";
        $response .= "\n";
        return $response;
    }

    public static function mauPulang()
    {
        if(self::getWaktu()['now']->format("Y-m-d H:i:s") < self::getWaktu()['masuk']) 
            return 'Belum masuk cuy, rajin amat dah';
        else if(self::getWaktu()['now']->format("Y-m-d H:i:s") > self::getWaktu()['pulang'])
            return 'Udah pulang cuy, emang mau OT?';
        else if(self::getWaktu()['now']->isWeekend())
            return 'Weekend cuy, gak ada pulang kerja';
        else {
            $waktu = (int) (strtotime(self::getWaktu()['pulang']->format('Y-m-d H:i:s')) - time());
            $jam = floor($waktu / 3600);
            $menit = floor(($waktu % 3600) / 60);
            $detik = $waktu % 60;
            $waktuTerbilang = '';
            if ($jam > 0)
                $waktuTerbilang .= $jam . ' jam ';
            if ($menit > 0)
                $waktuTerbilang .= $menit . ' menit ';
            if ($detik > 0)
                $waktuTerbilang .= $detik . ' detik';
            return $waktuTerbilang. " lagi cuy";
        }
    }

    public static function mauCuaca()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.openweathermap.org/data/2.5/weather?q=Tangerang&appid=".env('APIKEY_OPENWEATHER'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: ".env('APIKEY_OPENWEATHER')
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err)
            echo "cURL Error #:" . $err;
        else
            return $response;
    }

    public static function mauLibur() 
    {
        $bulan_ini = date("Ym");
        $array = json_decode(file_get_contents(env("TANGGAL_MERAH")), true);

        $output = [];
        foreach($array as $key => $value) {
            if (strpos($key, $bulan_ini) !== false) {
                $output[] = "(".substr($key, 0, 4)."-".substr($key, 4, 2)."-".substr($key, 6, 2).") ".$value['deskripsi'];
            }
        }

        if(count($output) == 0)
            $response = "Tidak ada libur bulan ini, sad";
        else
            $response = implode("\n", $output);

        return $response;
    }

    public static function mauLiburan()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://random.imagecdn.app/v1/image?width=300&height=300&category=buildings&format=json');
        return json_decode($response->getBody()->getContents(), true)['url'];
    }

    public static function mauCat()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://aws.random.cat/meow');
        return json_decode($response->getBody()->getContents(), true)['file'];
    }

    public static function mauDog()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://dog.ceo/api/breeds/image/random');
        return json_decode($response->getBody()->getContents(), true)['message'];
    }
}
