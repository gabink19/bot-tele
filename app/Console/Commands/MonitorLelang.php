<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Helpers\Util;

class MonitorLelang extends Command
{
    protected $signature = 'mau:monitor-lelang';

    protected $description = 'Monitor perubahan bid dan leader pada lelang aktif.';

    private $auctionIds = [8, 12, 18, 20, 43];

    private $stateFile = 'lelang_state.json';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        date_default_timezone_set("Asia/Jakarta");

        $stateFilePath = $this->stateFile;

        $previousState = [];
        if (file_exists($stateFilePath)) {
            $raw = file_get_contents($stateFilePath);
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $previousState = $decoded;
            }
        }

        try {
            $client = new \GuzzleHttp\Client();
            $requestOptions = [
                'verify' => false,
                'headers' => [
                    'apikey'        => env('SUPABASE_KEY'),
                    'Authorization' => 'Bearer ' . env('SUPABASE_KEY'),
                    'Content-Type'  => 'application/json',
                ],
            ];

            $currentState = [];
            $changedItems = [];

            foreach ($this->auctionIds as $id) {
                $url = "https://jkohrhjvqzdhopqrnzpe.supabase.co/rest/v1/auctions?select=*&id=eq." . $id;
                $response = $client->request('GET', $url, $requestOptions);
                $data = json_decode($response->getBody()->getContents(), true);

                if (empty($data)) {
                    continue;
                }

                $item = $data[0];
                $itemId = (string) $item['id'];
                $newBid    = $item['current_bid'];
                $newLeader = $item['current_leader'];

                $currentState[$itemId] = [
                    'name'           => $item['name'],
                    'current_bid'    => $newBid,
                    'current_leader' => $newLeader,
                    'start_price'    => $item['start_price'],
                    'end_time'       => $item['end_time'],
                    'status'         => $item['status'],
                ];

                $prev = isset($previousState[$itemId]) ? $previousState[$itemId] : null;

                $bidChanged    = $prev === null || (string)$prev['current_bid']    !== (string)$newBid;
                $leaderChanged = $prev === null || (string)$prev['current_leader'] !== (string)$newLeader;

                if ($bidChanged || $leaderChanged) {
                    $changedItems[] = [
                        'item'     => $item,
                        'prev'     => $prev,
                        'bid_changed'    => $bidChanged,
                        'leader_changed' => $leaderChanged,
                    ];
                }
            }

            if (!empty($changedItems)) {
                $msg = "🔔 <b>Update Lelang</b>\n";

                foreach ($changedItems as $change) {
                    $item    = $change['item'];
                    $prev    = $change['prev'];
                    $endTime = Carbon::parse($item['end_time'])
                        ->setTimezone('Asia/Jakarta')
                        ->format('d M Y H:i') . ' WIB';

                    $msg .= "━━━━━━━━━━━━━━━━━━━━━\n";
                    $msg .= "📦 <b>" . $item['name'] . "</b>\n";

                    if ($change['bid_changed']) {
                        $prevBid = $prev ? "Rp " . Util::format_number($prev['current_bid']) : "-";
                        $newBid  = "Rp " . Util::format_number($item['current_bid']);
                        $msg .= "📈 <b>Bid</b>: " . $prevBid . " → <b>" . $newBid . "</b>\n";
                    } else {
                        $msg .= "📈 <b>Bid</b>: Rp " . Util::format_number($item['current_bid']) . "\n";
                    }

                    if ($change['leader_changed']) {
                        $prevLeader = $prev ? $prev['current_leader'] : "-";
                        $newLeader  = $item['current_leader'];
                        $msg .= "👑 <b>Leader</b>: " . $prevLeader . " → <b>" . $newLeader . "</b>\n";
                    } else {
                        $msg .= "👑 <b>Leader</b>: " . $item['current_leader'] . "\n";
                    }

                    $msg .= "⏰ <b>Berakhir</b>: " . $endTime . "\n";
                }

                $msg .= "━━━━━━━━━━━━━━━━━━━━━\n";
                $msg .= "🕐 <i>" . date('d M Y H:i:s') . " WIB</i>";

                $data = ['text' => $msg];
                Util::sendMessageHTML($data, env('TELEGRAM_CHAT_ID'));

                $this->info('Perubahan terdeteksi, pesan terkirim.');
            } else {
                $this->info('Tidak ada perubahan bid/leader.');
            }

            file_put_contents($stateFilePath, json_encode($currentState, JSON_PRETTY_PRINT));

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
