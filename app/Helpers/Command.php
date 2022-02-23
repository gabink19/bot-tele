<?php

namespace App\Helpers;

use Carbon\Carbon as Carbon;

class Command
{
    public function __construct() 
    {

    }

    public static function ListActions($data = null)
    {
        return [self::mauPulang(), self::mauGajian(), self::mauLibur(), self::mauOT(), self::mauCrypto(), self::mauCovid(), self::mauLiburan(), self::mauCat(), self::mauDog(), self::mauHari(), self::mauGabut(), self::mauClockIn($data), self::mauClockOut($data), self::mauCekAbsen()];
    }

    public static function ListCommands()
    {
        return [
            '/mauPulang' => [
                'deskripsi' => 'Cek waktu pulang kerja',
                'type' => 'text'
            ],
            '/mauGajian' => [
                'deskripsi' => 'Cek waktu gajian',
                'type' => 'text'
            ],
            '/mauLibur' => [
                'deskripsi' => 'Cek waktu libur bulan ini',
                'type' => 'text'
            ],
            '/mauOT' => [
                'deskripsi' => 'Untuk keperluan overtime',
                'type' => 'image'
            ],
            '/mauCrypto' => [
                'deskripsi' => 'Untuk melihat harga crypto',
                'type' => 'text'
            ],
            '/mauCovid' => [
                'deskripsi' => 'Untuk melihat statistik covid-19 di Indonesia',
                'type' => 'text'
            ],
            '/mauLiburan' => [
                'deskripsi' => 'Untuk melihat info liburan',
                'type' => 'image'
            ],
            '/mauCat' => [
                'deskripsi' => 'Untuk melihat gambar kucing',
                'type' => 'image'
            ],
            '/mauDog' => [
                'deskripsi' => 'Untuk melihat gambar anjing',
                'type' => 'image'
            ],
            '/mauHari' => [
                'deskripsi' => 'Cek hari ini',
                'type' => 'text'
            ],
            '/mauGabut' => [
                'deskripsi' => 'Cek kuyyy',
                'type' => 'text'
            ],
            '/mauClockIn' => [
                'deskripsi' => 'Absen masuk kuy',
                'type' => 'text'
            ],
            '/mauClockOut' => [
                'deskripsi' => 'Absen pulang kuy',
                'type' => 'text'
            ],
            '/mauCekAbsen' => [
                'deskripsi' => 'Cek Absen kuy',
                'type' => 'text'
            ]
        ];
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
        $data = [
            "https://i.pinimg.com/564x/11/d9/b1/11d9b1f13b48c998015442030661b34d.jpg",
            "https://s1.bukalapak.com/img/670387178/large/Anggur_Orang_Tua___Anggur_merah__Kolesom__Arak_Obat__Intisar.png",
            "https://filebroker-cdn.lazada.co.id/kf/S148318cdb34b4cd696ff9c7ffc854656M.jpg",
            "https://pbs.twimg.com/media/EXKosS7UMAAvDQQ.jpg",
            "https://i0.wp.com/warindo.de/wp-content/uploads/2021/01/Bir-Bintang-330ml.jpg"
        ];
        return $data[rand(0, count($data)-1)];
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
        $response = "Informasi Crypto hari ini :\n\n";
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
        if(self::getWaktu()['now'] < self::getWaktu()['masuk']) 
            return 'Belum masuk cuy, rajin amat dah';
        else if(self::getWaktu()['now'] > self::getWaktu()['pulang'])
            return 'Udah pulang cuy, emang mau OT?';
        else if(self::getWaktu()['now']->isWeekend())
            return 'Weekend cuy, gak ada pulang kerja';
        else {
            $waktu = (int) (strtotime(self::getWaktu()['pulang']) - time());
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
            $response = "Ga ada libur bulan ini cuy, fix sad";
        else {
            $response = "Informasi libur bulan ini :\n\n";
            $response .= implode("\n", $output);
        }

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

    public static function mauHari()
    {
        $hari = Util::cek_hari();
        $response = "Lupa hari? Sekarang hari ".$hari." cuy";
        return $response;
    }

    public static function mauClockIn($sender)
    {
        if(self::getWaktu()['now']->isWeekend())
            return "Weekend dulu cuy, absen nya libur dulu";
        else if(self::getWaktu()['now'] < self::getWaktu()['masuk']) 
            return "Belum masuk cuy, belom bisa absen masuk. rajin amat dah";
        else if(self::getWaktu()['now'] > self::getWaktu()['batas_masuk']) 
            return "Udah kelewat absen masuknya cuy, kalau belum absen siap siap potong gaji dah";
        else {
            $file = "Logs/Masuk-".date("d-m-Y").".txt";

            if (!file_exists("Logs"))
                mkdir("Logs", 0775, true);

            if (!file_exists($file))
                $fh = fopen($file, 'w') or die("Can't create file");
                
            $fh = fopen($file, 'r');
            $cek_absen = false;
            while ($line = fgets($fh)) {
                if($line == "@".$sender['username']."\r\n") {
                    $cek_absen = true;
                    break;
                }
            }
            fclose($fh);
            
            $orang = "";
            if(!$cek_absen) {
                $orang .= "Thankyou @".$sender['username']."\r\n";
                $fh = fopen($file, "a");
                fwrite($fh, "@".$sender['username']."\r\n");
                fclose($fh);
            }
            else
                $orang .= "Lu @".$sender['username']." kan udah absen masuk hari ini\r\n";

            $orang .= "List yang udah absen masuk hari ini :\n\n";
            $fh = fopen($file, 'r');
            while ($line = fgets($fh)) {
                $orang .= $line;
            }
            fclose($fh);
            return $orang;
        }
    }

    public static function mauClockOut($sender)
    {
        if(self::getWaktu()['now']->isWeekend())
            return "Weekend dulu cuy, absen nya libur dulu";
        else if(self::getWaktu()['now'] < self::getWaktu()['pulang']) 
            return "Belum waktunya pulang cuy, belom bisa absen pulang. sabar dulu";
        else if(self::getWaktu()['now'] > self::getWaktu()['batas_pulang']) 
            return "Udah kelewat absen pulangnya cuy, siap siap potong gaji dah";
        else {
            $file = "Logs/Pulang-".date("d-m-Y").".txt";

            if (!file_exists("Logs"))
                mkdir("Logs", 0775, true);

            if (!file_exists($file))
                $fh = fopen($file, 'w') or die("Can't create file");
                
            $fh = fopen($file, 'r');
            $cek_absen = false;
            while ($line = fgets($fh)) {
                if($line == "@".$sender['username']."\r\n") {
                    $cek_absen = true;
                    break;
                }
            }
            fclose($fh);
            
            $orang = "";
            if(!$cek_absen) {
                $orang .= "Thankyou @".$sender['username']."\r\n";
                $fh = fopen($file, "a");
                fwrite($fh, "@".$sender['username']."\r\n");
                fclose($fh);
            }
            else
                $orang .= "Lu @".$sender['username']." kan udah absen pulang hari ini\r\n";

            $orang .= "List yang udah absen pulang hari ini :\n\n";
            $fh = fopen($file, 'r');
            while ($line = fgets($fh)) {
                $orang .= $line;
            }
            fclose($fh);
            return $orang;
        }
    }

    public static function mauCekAbsen()
    {
        if(self::getWaktu()['now']->isWeekend())
            return "Weekend dulu cuy, absen nya libur dulu";
        else {
            $file_masuk = "Logs/Masuk-".date("d-m-Y").".txt";
            $file_pulang = "Logs/Pulang-".date("d-m-Y").".txt";

            if (!file_exists("Logs"))
                mkdir("Logs", 0775, true);

            $orang = "List yang udah absen masuk hari ini (".date("d-m-Y")."):\n\n";
            if (file_exists($file_masuk)) {
                $fh = fopen($file_masuk, 'r');
                while ($line = fgets($fh)) {
                    $orang .= $line;
                }
                fclose($fh);
            }

            $orang .= "\nList yang udah absen pulang hari ini (".date("d-m-Y")."):\n\n";
            if (file_exists($file_pulang)) {
                $fh = fopen($file_pulang, 'r');
                while ($line = fgets($fh)) {
                    $orang .= $line;
                }
                fclose($fh);
            }
            return $orang;
        }
    }
}