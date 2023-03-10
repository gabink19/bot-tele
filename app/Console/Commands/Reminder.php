<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\Util;

class Reminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mau:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menjalankan Reminder.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        date_default_timezone_set("Asia/Bangkok");
        $this->info('++++++++++++++++++++++++++++');
        $this->info('           START           ');
        $this->info('++++++++++++++++++++++++++++');
        $this->info('From: ' . date('Y-m-d H:i:s'));

        $this->callback();

        $this->info('To: ' . date('Y-m-d H:i:s'));
        $this->info('++++++++++++++++++++++++++++');
        $this->info('           FINISH           ');
        $this->info('++++++++++++++++++++++++++++');
    }

    public function callback()
    {  
        $array = json_decode(file_get_contents(env("PATH_PUBLIC")."/reminder.json"), true);
        $now = date("d/m/Y_H:i");
        if (isset($array[$now])) {
            foreach ($array[$now] as $key => $value) {
                $data['text'] = "Reminder : ".$value;
                Util::sendMessage($data);
                sleep(1);
                Util::sendMessage($data);
                sleep(1);
                Util::sendMessage($data);
            }
        }
    }

}
