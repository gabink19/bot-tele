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
                return self::mauPulang();
                break;
            case "1" : 
                return self::mauGajianv2();
                break;
            case "2" : 
                return self::mauLibur();
                break;
            case "3" : 
                return self::mauOT();
                break;
            case "4" : 
                return self::mauCrypto();
                break;
            case "5" : 
                return self::mauCovid();
                break;
            case "6" : 
                return self::mauLiburan();
                break;
            case "7" : 
                return self::mauCat();
                break;
            case "8" : 
                return self::mauDog();
                break;
            case "9" : 
                return self::mauHari();
                break;
            case "10" : 
                return self::mauGabut();
                break;
            case "11" : 
                return self::mauCuaca();
                break;
            case "12" : 
                return self::mauCekGempa();
                break;
            case "13" : 
                return self::mauClockIn($data);
                break;
            case "14" : 
                return self::mauClockOut($data);
                break;
            case "15" : 
                return self::mauCekAbsen();
                break;
            case "16" : 
                return self::mauBerita();
                break;
            case "17" : 
                return self::mauTHR();
                break;
            case "18" : 
                return self::mauBonus();
                break;
            case "19" : 
                return self::mauSholat();
                break;
            case "20" : 
                return self::mauTanggalMerah();
                break;
            case "21" : 
                return self::mauPuasa();
                break;
            case "22" : 
                return self::mauCurhat();
                break;
            case "23" : 
                return self::mauReminder();
                break;
            case "24" : 
                return self::mauLoker();
                break;
            case "25" : 
                return self::mauJadiKutipan();
                break;
            case "26" : 
                return self::mauCekNamaRekening();
                break;
            case "27" : 
                return self::mauFaktaRandom();
                break;
            default :
                "nothing";
            }
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
            '/mauCuaca' => [
                'deskripsi' => 'Cek cuaca hari ini',
                'type' => 'text'
            ],
            '/mauCekGempa' => [
                'deskripsi' => 'Cek gempa bumi',
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
                'deskripsi' => 'Cek absen kuy',
                'type' => 'text'
            ],
            '/mauBerita' => [
                'deskripsi' => 'Cek berita terkini',
                'type' => 'text'
            ],
            '/mauTHR' => [
                'deskripsi' => 'Cek THR',
                'type' => 'text'
            ],
            '/mauBonus' => [
                'deskripsi' => 'Cek Bonus',
                'type' => 'text'
            ],
            '/mauSholat' => [
                'deskripsi' => 'Cek waktu sholat dan imsak',
                'type' => 'text'
            ],
            '/mauTanggalMerah' => [
                'deskripsi' => 'Cek Tanggal Merah di hari kerja',
                'type' => 'text'
            ],
            '/mauPuasa' => [
                'deskripsi' => 'Cek Mulai Puasa dan Akhir Puasa di Tahun ini',
                'type' => 'text'
            ],
            '/mauCurhat' => [
                'deskripsi' => 'Curhat sama bot chatGPT',
                'type' => 'text'
            ],
            '/mauReminder' => [
                'deskripsi' => 'Bikin Pengingat di Tele',
                'type' => 'text'
            ],
            '/mauLoker' => [
                'deskripsi' => 'Menampilkan 5 Loker Terbaru (Salary>15jt)',
                'type' => 'text'
            ],
            '/mauJadiKutipan' => [
                'deskripsi' => 'Mengubah text menjadi gambar kutipan.',
                'type' => 'text'
            ],
            '/mauCekNamaRekening' => [
                'deskripsi' => 'Cek Nama dari pemiliki rekening bank atau e-wallet.',
                'type' => 'text'
            ],
            '/mauFaktaRandom' => [
                'deskripsi' => 'Untuk mendapatkan fakta-fakta random.',
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

        if($end->copy()->format('d') == 26)
            $end    = $end->subDays(1);

        while ($start->lte($end)) {
            $carbon = Carbon::parse($start);
            if (($carbon->isWeekend() != true) && !Util::CheckTanggalMerah($carbon->format("Ymd"))) 
                $last_work = $start->copy()->format('Y-m-d');
            
            $start->addDay();
        }

        $last_work = Carbon::parse($last_work." 23:59:59");
        $sisa_gajian = $last_work->diffInDays(self::getWaktu()['now']);

        if(self::getWaktu()['now'] > $last_work)
            $response = "Kan udah gajian bulan ini cuy";
        else if($sisa_gajian == "0")
            $response = "Cek rekening BNI cuy";
        else if($sisa_gajian == "1")
            $response = "Besok gajian cuy";
        else
            $response = $sisa_gajian . " hari lagi gajiannya cuy";

        return $response;
    }

    public static function mauGajianv2()
    {
        $hariini = date('Y-m-d');
        $tgl_gajian_skrg = date('Y-m-25');
        $hari_dualima_skrg = date("l", strtotime($tgl_gajian_skrg));
        if ($hari_dualima_skrg=="Sunday") {
            $tgl_gajian_skrg = date('Y-m-d',strtotime("-2 days",strtotime($tgl_gajian_skrg)));
        }else if ($hari_dualima_skrg=="Saturday") {
            $tgl_gajian_skrg = date('Y-m-d',strtotime("-1 days",strtotime($tgl_gajian_skrg)));
        }else if (Util::CheckTanggalMerah(date('Ym25')) && $hari_dualima_skrg=="Monday") {
            $tgl_gajian_skrg = date('Y-m-d',strtotime("-3 days",strtotime($tgl_gajian_skrg)));
        }else if (Util::CheckTanggalMerah(date('Ym25'))) {
            $tgl_gajian_skrg = date('Y-m-d',strtotime("-1 days",strtotime($tgl_gajian_skrg)));
        }

        $date1=date_create($hariini);
        $date2=date_create($tgl_gajian_skrg);
        $diff=date_diff($date1,$date2);

        if ($diff->format("%R%a")==0) {
            $response = "Cek rekening BNI cuy";
        }else if ($diff->format("%R") == "+") {
            if ($diff->format("%a") == "1") {
                $response = "Besok gajian cuy";
            }else{
                $response = $diff->format("%a") . " hari lagi gajiannya cuy !!";
            }
        }else if ($diff->format("%R") == "-") {
            $tgl_gajian_depan = date('Y-m-25',strtotime("+1 month",strtotime($tgl_gajian_skrg)));
            $hari_dualima_depan = date("l", strtotime($tgl_gajian_depan));
            if ($hari_dualima_depan=="Sunday") {
                $tgl_gajian_depan = date('Y-m-d',strtotime("-2 days",strtotime($tgl_gajian_depan)));
            }else if ($hari_dualima_depan=="Saturday") {
                $tgl_gajian_depan = date('Y-m-d',strtotime("-1 days",strtotime($tgl_gajian_depan)));
            }else if (Util::CheckTanggalMerah(date('Ym25',strtotime($tgl_gajian_depan))) && $hari_dualima_depan=="Monday") {
                $tgl_gajian_depan = date('Y-m-d',strtotime("-3 days",strtotime($tgl_gajian_depan)));
            }else if (Util::CheckTanggalMerah(date('Ym25',strtotime($tgl_gajian_depan)))) {
                $tgl_gajian_depan = date('Y-m-d',strtotime("-1 days",strtotime($tgl_gajian_depan)));
            }

            $date1=date_create($hariini);
            $date2=date_create($tgl_gajian_depan);
            $diff=date_diff($date1,$date2);
            $response = "";
            if ($diff->format("%a")>28) {
                $rand = ["Baru gajian udah nnyain aja. \n","Baru juga gajian. \n","Dikemanain tuh gajinya udah abis aja. \n","Sabar masih lama gajiannya. \n"];
                $response .= $rand[rand(0,3)];
            }
            $response .= $diff->format("%a") . " hari lagi gajiannya cuy !!";
        }

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
        $response = "Pilih sendiri cuy mau gabut dimana :"."\n";
        $response .= "http://179.43.163.52/ (Film sub indo)"."\n";
        $response .= "https://pahe.li/ (Film)"."\n";
        $response .= "https://tv1.lk21official.wiki/ (Film sub indo)"."\n";
        $response .= "https://unduhfilmhd.com/ (Film)"."\n";
        $response .= "https://drive.seikel.workers.dev/0:/ (Film)"."\n";
        $response .= "https://subsc.my.id/ (subtitle)"."\n";
        $response .= "https://dema737ch.com/ (Doraemon)"."\n";
        $response .= "https://oploverz.best/ (Anime)"."\n";
        $response .= "https://mangaku.vip/ (Manga)"."\n";
        $response .= "https://www.ovagames.com/ (Game Bajakan)"."\n"."\n";
        $response .= "Klo ada link mati, report aja ya.";
        return $response;
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
        try {
            $now    = date('Hi');
            $limit  = $now."+10";
            $fh_res = fopen("count.txt", 'r') or die("Unable to open file!");
            $read   = fread($fh_res,filesize("count.txt"));
            if ($read==$limit) {
                return "BACOT";
            }else{
                if ($read=='') {
                    $limit = $now."+1";
                }else{
                    $parse = explode("+", $read);
                    if ($now != $parse[0]) {
                        $limit = $now."+1";
                    }else{
                        $count  = (int)$parse[1]+1;
                        $limit = $now."+".$count;
                    }
                }
            }

            $fh = fopen("count.txt", "w") or die("Unable to open file!");;
            fwrite($fh, $limit);
            fclose($fh);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
            
        }
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
        $url = "https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/DigitalForecast-Banten.xml";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $data = $response->getBody()->getContents();
        $xml = \simplexml_load_string($data);
        $array = json_decode(json_encode((array)$xml), TRUE);
        $provinsi = "Banten";
        $kota = "Kota Tangerang";
        $isi = [];
        foreach($array['forecast']['area'] as $value) {
            if($value['@attributes']['description'] == $kota && $value['@attributes']['domain'] == $provinsi) {
                foreach($value['parameter'] as $parameters) {
                    if($parameters['@attributes']['id'] == "weather") {
                        foreach($parameters['timerange'] as $data) {
                            if(substr($data['@attributes']['datetime'],0,8) == date("Ymd")) {
                                $isi[] = "(".substr($data['@attributes']['datetime'],8,2).":".substr($data['@attributes']['datetime'],10,2).") ".Util::get_cuaca($data['value']);
                            }
                        }
                    }
                }
            }
        }
        $updated_at = $array['forecast']['issue']['year']."-".$array['forecast']['issue']['month']."-".$array['forecast']['issue']['day']." ".$array['forecast']['issue']['hour'].":".$array['forecast']['issue']['minute'].":".$array['forecast']['issue']['second'];
        $response = "Prakiraan Cuaca Untuk Hari Ini (".date("d-m-Y").")\n";
        $response .= "Provinsi : ".$provinsi."\n";
        $response .= "Kota : ".$kota."\n\n";
        for($i=0; $i<count($isi); $i++) {
            $response .= $isi[$i]."\n";
        }
        $isi = [];
        foreach($array['forecast']['area'] as $value) {
            if($value['@attributes']['description'] == 'Serpong' && $value['@attributes']['domain'] == $provinsi) {
                foreach($value['parameter'] as $parameters) {
                    if($parameters['@attributes']['id'] == "weather") {
                        foreach($parameters['timerange'] as $data) {
                            if(substr($data['@attributes']['datetime'],0,8) == date("Ymd")) {
                                $isi[] = "(".substr($data['@attributes']['datetime'],8,2).":".substr($data['@attributes']['datetime'],10,2).") ".Util::get_cuaca($data['value']);
                            }
                        }
                    }
                }
            }
        }
        $updated_at = $array['forecast']['issue']['year']."-".$array['forecast']['issue']['month']."-".$array['forecast']['issue']['day']." ".$array['forecast']['issue']['hour'].":".$array['forecast']['issue']['minute'].":".$array['forecast']['issue']['second'];

        $response .= "\n"."Kota : Kota Tangerang Selatan (Serpong) \n\n";
        for($i=0; $i<count($isi); $i++) {
            $response .= $isi[$i]."\n";
        }
        $response .= "\nSumber : BMKG Indonesia";
        $response .= "\nUpdated at : ".$updated_at." WIB";
        return $response;
    }

    public static function mauBerita()
    {
        $url = "https://www.antaranews.com/rss/terkini.xml";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $data = $response->getBody()->getContents();
        $xml = \simplexml_load_string($data);
        $array = json_decode(json_encode((array)$xml), TRUE);
        $isi = [];
        foreach($array['channel']['item'] as $value) {
            $isi[] = "Judul : ".$value['title']."\nTanggal : ".substr($value['pubDate'],0,25)." WIB\nLink : ".$value['link']."\n\n";  
        }
        $response = "Informasi Berita Terkini\n\n";
        $response .= $isi[0]."\n";
        $response .= "Sumber : ".$array['channel']['title'];
        
        return $response;
    }

    public static function mauCekGempa()
    {
        $url = "https://data.bmkg.go.id/DataMKG/TEWS/autogempa.json";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $data = json_decode($response->getBody()->getContents(), true);

        $image = "https://ews.bmkg.go.id/tews/data/".$data['Infogempa']['gempa']['Shakemap'];
        $response = "Informasi gempa terkini\n\n";
        $response .= "Tanggal : ".$data['Infogempa']['gempa']['Tanggal']." ".$data['Infogempa']['gempa']['Jam']."\n";
        $response .= "Magnitudo : ".$data['Infogempa']['gempa']['Magnitude']."\n";
        $response .= "Kedalaman : ".$data['Infogempa']['gempa']['Kedalaman']."\n";
        $response .= "Wilayah : ".$data['Infogempa']['gempa']['Wilayah']."\n";
        $response .= "Potensi : ".$data['Infogempa']['gempa']['Potensi']."\n";
        $response .= "Gambar : ".$image."\n";
        $response .= "\n Sumber : BMKG Indonesia";

        return $response;
        return [$response, $image];
    }

    public static function mauLibur() 
    {
        $bulan_ini = date("Y-m");
        $array = json_decode(file_get_contents(env("TANGGAL_MERAH")), true);
        $output = [];
        foreach($array as $key => $value) {
            if (strpos($key, $bulan_ini) !== false) {
                $output[] = "(".$key.") ".$value['summary'][0];
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
        try{
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', 'https://api.unsplash.com/photos/random?client_id=Ff1Ep2lLgcuTmf9rUEAMd9Dtya8HerwBBYkW0wH2Qsw&query=vacation');
            $rng = mt_rand(1, 100);
            $randpik = mt_rand(1,2);
            $rngArray = array(1,2,3,4,5,6,7,8,9,10);
            if (in_array($rng, $rngArray)) {
                return "https://radmed.co.id/dokter%20".$randpik.".jpg";
            }else{
                return json_decode($response->getBody()->getContents(), true)['urls']['small'];
            }

        } catch (Exception $e) {
            die();
        }
    }

    public static function mauCat()
    {   
        try{
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', 'https://api.unsplash.com/photos/random?client_id=Ff1Ep2lLgcuTmf9rUEAMd9Dtya8HerwBBYkW0wH2Qsw&query=cat');
            
            return json_decode($response->getBody()->getContents(), true)['urls']['small'];
        } catch (Exception $e) {
            die();
        }
    }

    public static function mauDog()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://dog.ceo/api/breeds/image/random');
        return json_decode($response->getBody()->getContents(), true)['message'];
    }

    public static function mauHari()
    {
        $hariini = date("D");
        $hari = Util::cek_hari($hariini);
        $tanggal = date("d M Y");
        $response = "Lupa hari? Sekarang hari ".$hari." Tanggal ".$tanggal." cuy";
        return $response;
    }

    public static function mauClockIn($sender)
    {
        if(self::getWaktu()['now']->isWeekend())
            return "Weekend dulu cuy, absen nya libur dulu";
        else if(Util::CheckTanggalMerah(self::getWaktu()['now']->format("Ymd")))
            return "Libur dulu cuy, absen nya libur dulu";
        else if(self::getWaktu()['now'] < self::getWaktu()['masuk']) 
            return "Belum masuk cuy, belom bisa absen masuk. rajin amat dah";
        else if(self::getWaktu()['now'] > self::getWaktu()['batas_masuk']) 
            return "Udah kelewat absen masuknya cuy, kalau belum absen siap siap potong gaji dah";
        else {
            $file = "Logs/Masuk-".date("d-m-Y").".txt";

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
        else if(Util::CheckTanggalMerah(self::getWaktu()['now']->format("Ymd")))
            return "Libur dulu cuy, absen nya libur dulu";
        else if(self::getWaktu()['now'] < self::getWaktu()['pulang']) 
            return "Belum waktunya pulang cuy, belom bisa absen pulang. sabar dulu";
        else if(self::getWaktu()['now'] > self::getWaktu()['batas_pulang']) 
            return "Udah kelewat absen pulangnya cuy, siap siap potong gaji dah";
        else {
            $file = "Logs/Pulang-".date("d-m-Y").".txt";

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
        else if(Util::CheckTanggalMerah(self::getWaktu()['now']->format("Ymd")))
            return "Libur dulu cuy, absen nya libur dulu";
        else {
            $file_masuk = "Logs/Masuk-".date("d-m-Y").".txt";
            $file_pulang = "Logs/Pulang-".date("d-m-Y").".txt";

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

    public static function mauTHR() 
    {
        $hari_ini=date_create(date('Y-m-d'));
        $thr=date_create('2023-04-06');
        $diff=date_diff($hari_ini,$thr);
        $add = "";
        $response="";

        if ($diff->format("%R%a") == 0) {
            $response .= "Cek rekening BNI cuy \n\n";
        }else if ($diff->format("%R") == "+") {
            $add = "(".$diff->format("%a")." Hari Lagi !!)";
        }
        $response .= "- Tahun 2021 : 30 April 2021 \n";
        $response .= "- Tahun 2022 : 14 April 2022 \n";
        $response .= "- Tahun 2023 : 6 April 2023 ".$add." \n";
        return $response;
    }

    public static function mauBonus() 
    {   
        $response = "- Tahun 2020 : 20 Januari 2020 \n";
        $response .= "- Tahun 2021 : 29 April 2021 \n";
        $response .= "- Tahun 2022 : 28 April 2022 (50%) & Juni 2022 (50%) \n";
        $response .= "- Tahun 2023 : 28 Maret 2023 (90%) & ?? ?? 2023 (10%) \n";

        return $response;
    }

    public static function mauSholat()
    {
        $date = date("Y/m/d");
        $url = "https://api.myquran.com/v2/sholat/jadwal/1107/".$date;
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


        $url = "https://api.myquran.com/v2/sholat/jadwal/1108/".$date;
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

    public static function mauTanggalMerah()
    {
        $tahun_ini = date('Y');
        $bulan_ini = (int)date('m');
        $liburtahunini = [];
        $array = json_decode(file_get_contents(env("TANGGAL_MERAH")), true);
        $skip = ['Hari Kartini','Hari Ibu','Hari Ayah','Hari Batik'];
        foreach ($array as $key => $value) {
            $substr = substr($key, 0,4);
            $substr_bln = (int)substr($key, 5,2);
            if ($substr_bln<$bulan_ini) {
                continue;
            }
            if ($substr==$tahun_ini && !Util::isWeekend($key) && !in_array($value['summary'][0], $skip)) {
                $hari = Util::cek_hari(date('D',strtotime($key)));
                $output[] = "[".$hari.", ".date("d M Y",strtotime($key))."] ".$value['summary'][0];
            }
        }
        $response = "Informasi Tanggal Merah Tahun ini di hari kerja :\n\n";
        $response .= implode("\n", $output);
        return $response;
    }

    public static function mauPuasa()
    {
        $tahun_ini = date('Y');
        $puasa = '';
        $lebaran = '';
        $array = json_decode(file_get_contents(env("TANGGAL_MERAH")), true);
        foreach ($array as $key => $value) {
            $substr = substr($key, 0,4);
            if ($substr==$tahun_ini) {
                if ($value['summary'][0]=='Hari Idul Fitri') {
                    $lebaran = date('Y-m-d',strtotime($key));
                    $hariini = date('Y-m-d');
                    if (strtotime($lebaran)<strtotime($hariini)) {
                        $tahun_ini = date('Y',strtotime("+1 year",strtotime($hariini)));
                        continue;
                    }
                    break;
                }
            }
        }
        $puasa = date('Y-m-d',strtotime('-30 days',strtotime($lebaran)));

        $hari_ini=date_create(date('Y-m-d'));
        $date2=date_create($puasa);
        $diff=date_diff($hari_ini,$date2);

        $date3=date_create($lebaran);
        $diff_lebaran=date_diff($hari_ini,$date3);

        $response = "Informasi Tanggal Mulai dan Akhir Puasa Tahun ini :\n\n";
        if ($diff->format("%R%a") == 0) {
                $response .= "- Hari ini hari pertama puasa, Selamat Berpuasa. \n";
                $response .= "- ".$diff_lebaran->format("%a")." Hari Menuju Lebaran \n";
                $response .= "- Lebaran Diperkirakan Tanggal ".date('d M Y',strtotime($lebaran))."\n";
        }elseif ($diff->format("%R") == "+") {
                $response .= "- Puasa Diperkirakan dimulai Tanggal ".date('d M Y',strtotime($puasa))."\n";
                $response .= "- ".$diff->format("%a")." Hari Menuju Puasa \n";
        }else if ($diff->format("%R") == "-") {
                $response .= "- ".$diff_lebaran->format("%a")." Hari Menuju Lebaran \n";
                $response .= "- Lebaran Jatuh pada Tanggal ".date('d M Y',strtotime($lebaran))." (Bisa Berubah)\n";
        }
        return $response;
    }

    public static function mauCurhat($message='')
    {      
        // Real ChatGPT   
        // if ($message!='') {   
        //     $url        = "https://api.openai.com/v1/chat/completions";
        //     $authorization = "Authorization: Bearer ".env("TOKEN_GPT");
        //     $payload    = '{
        //                      "model": "gpt-3.5-turbo",
        //                      "messages": [{"role": "user", "content": "'.$message.'"}] 
        //                     }';
        //     $ch         = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);
        //     curl_setopt($ch, CURLOPT_POST, 1);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        //     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',$authorization));
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     $result     = curl_exec($ch);
        //     if (curl_errno($ch)) {
        //         $error_msg = curl_error($ch);
        //     }
        //     $result     = json_decode($result, true);
        //     curl_close ($ch);
        // }

        // if (isset($error_msg)) {
        //     try {
        //         $fh = fopen("Logs/"."Chat-".date("d-m-Y").".txt", "w") or die("Unable to open file!");;
        //         fwrite($fh, json_encode($error_msg).",\r\n");
        //         fclose($fh);
        //     } catch (\Exception $e) {
                
        //     }
        // }   
        //     try {
        //         $fh = fopen("Logs/"."Chat-".date("d-m-Y").".txt", "w") or die("Unable to open file!");;
        //         fwrite($fh, json_encode($result).",\r\n");
        //         fclose($fh);
        //     } catch (\Exception $e) {
                
        //     }

        // return (isset($result['choices'][0]['message']['content']))?$result['choices'][0]['message']['content']:"Sini Curhat....";
        
        //Chat GPT akuari
        if ($message=='') { 
            return "Sini Curhat....";
        }
        $param = urlencode($message);
        $array = json_decode(file_get_contents("https://api.akuari.my.id/ai/gpt?chat=".$param), true);
        return $array["respon"];
    
    }

    public static function mauReminder($message='',$sender='')
    {   
        $array = json_decode(file_get_contents("reminder.json"), true); 
        $old_array = $array;    
        if ($message!='') {
            $exploderMsg = explode(" ",$message);
            $date = $exploderMsg[0];
            if (Util::validateDate($date,"d/m/Y_H:i")) {
                $message = str_replace($date." ", "", $message);
                $msg = $message." ("."@".$sender['username'].")";
                $array[$date][] = $msg;
                $write = true;
                if (isset($old_array[$date])) {
                    foreach ($old_array[$date] as $value) {
                        if ($value==$msg) {
                            $write = false;
                        }
                    }
                }

                if ($write) {
                    $fh = fopen("reminder.json", "w") or die("Unable to open file!");;
                    fwrite($fh, json_encode($array));
                    fclose($fh);
                }

                // $date = date('d/m/Y H:i',strtotime($date));
                return "Noted : $date $msg";
            }
        }

        return "Format Reminder : /mauReminder {d/m/Y_H:i} {pesan reminder}";
    }

    public static function mauLoker($message='backend engineer',$limit=10)
    {   
        // try {
        //     $now    = date('Hi');
        //     $max  = $now;
        //     $fh_res = fopen("countLoker.txt", 'r') or die("Unable to open file!");
        //     $read   = fread($fh_res,filesize("countLoker.txt"));
        //     if ($read==$max) {
        //         return "/mauLoker hanya bisa di hit 1 menit sekali.";
        //     }

        //     $fh = fopen("countLoker.txt", "w") or die("Unable to open file!");;
        //     fwrite($fh, $max);
        //     fclose($fh);
        // } catch (\Exception $e) {
        //     throw new \Exception($e->getMessage());
            
        // }
        if($message==''){
            $message='Programmer';
        }
        $count = 1 ;
        $response = "Menampilkan ".$limit." :\n\n";
        for ($i=1; $i < 100; $i++) { 
            $url        = "
            https://www.jobstreet.co.id/api/chalice-search/v4/search?siteKey=ID-Main&sourcesystem=houston&userqueryid=28430507083827b0ff41fa0e24ac0005-1463615&userid=48e349f3-4193-44a1-9a8a-a9d1c3c219b0&usersessionid=48e349f3-4193-44a1-9a8a-a9d1c3c219b0&eventCaptureSessionId=48e349f3-4193-44a1-9a8a-a9d1c3c219b0&page=1&seekSelectAllPages=true&classification=6281&subclassification=6290,6287,6302&salarytype=monthly&salaryrange=10000000-&pageSize=100&include=seodata&locale=id-ID&solId=6891072a-6a24-407d-8e4b-a8852934d6bf";
            $ch         = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result     = curl_exec($ch);
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
            }
            $result  = json_decode($result, true);
            curl_close ($ch);

            if (isset($result['data'])) {
                foreach ($result['data'] as $key => $value) {
                    if (isset($value['salary']) && $value['salary'] != "") {
                        if ($count>$limit) {
                            break;
                        }
                        $response .= "<b>".$count.". ".$value['title']." (".$value['companyName']." - ".$value['locations'].")</b>\n";
                        $response .= "- Gaji : ".$value['salary']."\n";
                        $response .= "- Tanggal Posting : ".date('d-M-Y H:i',strtotime($value['listingDate']))."\n";
                        $response .= "- Tipe Kerja : ".$value['workType']."\n";
                        $response .= "- Teaser : ".$value['teaser']."\n";
                        $response .= "- Link : https://www.jobstreet.co.id/id/job/".$value['id']."\n";
                        $count++;
                    }
                }
            }else{
                break;
            }
        }
        

        return $response;
    }

    public static function mauRandom($message='rebahanAJaGaskeunLah')
    {
        try {
            $array = json_decode(file_get_contents('https://api.unsplash.com/photos/random?client_id=Ff1Ep2lLgcuTmf9rUEAMd9Dtya8HerwBBYkW0wH2Qsw&lang=id&query='.$message), true); 
            if (isset($array['urls']['small'])) {
                return $array['urls']['small'];     
            }
        } catch (Exception $e) {
            die();
        }
        
        return '';
    }

    public static function mauJadiKutipan($message='ASSALAMUALAIKUM TI GUYS',$userID='1243421652',$nama='XX')
    {
        $words = explode(" ", $message);
        if (count($words) < 3 || count($words) > 15) {
            return '';
        }
        try {
            $msg = ucfirst($message);
            $message = base64_encode($msg);
            $nama = base64_encode($nama);
            $uniq = uniqid();
            putenv("PATH=/home/system_user/bin:/home/system_user/bin:/usr/local/bin:/bin:/usr/bin:/usr/local/sbin:/usr/sbin:/home/system_user/.composer/vendor/bin:/usr/local/go/bin:/home/system_user/.local/bin:/home/system_user/bin:/usr/local/go/bin:/usr/local/go/bin:/home/system_user/.local/bin:/home/system_user/bin");

            $command = shell_exec("/var/www/html/radmed.co.id/bot-tele/quote-maker/run.sh $nama $message $userID $uniq 2>&1");
            // if ($command === null) {
            //     $error = error_get_last();
            //     echo "Error: " . $error['message'];
            // } else {
            //     echo $command;
            // }
            $command = shell_exec("chmod 777 /var/www/html/radmed.co.id/bot-tele/public/quote/".$uniq.".png");
            // Menjalankan perintah dan menyimpan outputnya
            // if ($command === null) {
            //     $error = error_get_last();
            //     echo "Error: " . $error['message'];
            // } else {
            //     echo $command;
            // }
            return "https://radmed.co.id/bot-tele/public/quote/".$uniq.".png";
        } catch (Exception $e) {
        }
        
        return '';
    }
    
    public static function mauCekNamaRekening($message='')
    {
        $msg = "Format Cek Rekening : /mauCekNamaRekening {kodebank}-{rekening}\n\n";
        $msg .= "----Kode - BANK----\nbca - BCA\nmandiri - Mandiri\nbni - BNI\nbri - BRI\nbsm - BSI (Bank Syariah Indonesia)\nbca_syr - BCA Syariah\nbtn - BTN/BTN Syariah\ncimb - CIMB Niaga / CIMB Niaga Syariah\ndbs - DBS Indonesia\nbtpn - BTPN / Jenius\nartos - Bank Jago\nkesejahteraan_ekonomi - Seabank/Bank BKE\ndanamon - Danamon / Danamon Syariah\nmuamalat - Muamalat\nhana - LINE Bank/KEB Hana\nroyal - Blu/BCA Digital\nnationalnobu - Nobu Bank\n\n----Kode - E-Wallet----\novo - OVO\ndana - Dana\nlinkaja - LinkAja\ngopay - GoPay\nshopeepay - ShopeePay";

        if ($message=='') {
            return $msg;
        }

        $exp = explode('-',$message);
        if (!isset($exp[0]) || !isset($exp[1])) {
            return $msg;
        }

        $url        = "https://cekrek.netovas.com/api/account-inquiry";
        $payload    = 'accountBank='.$exp[0].'&accountNumber='.$exp[1];
        $ch         = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result     = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        $result  = json_decode($result, true);
        curl_close ($ch);
        if (isset($error_msg)) {
            return json_encode($error_msg);
        }
        Log::debug($url." - ".$payload);
        Log::debug(json_encode($result));
        if (isset($result['success'])) {
            if ($result['success']=='true') {
                $resp = $result['message']."\n";
                $resp .= "Account Name : ". $result['data']['account_name']."\n";
                return $resp;
            }
        }
        return $result['message'];
    }
    
    public static function mauFaktaRandom()
    {
        $array = json_decode(file_get_contents("https://api.akuari.my.id/randomtext/faktaunik"), true);
        return "<b>".$array["hasil"]."</b>";
    }
}