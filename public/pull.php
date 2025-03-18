<?php

// Secret token dari GitHub (set saat membuat webhook)
$githubSecret = "190595";

// Cek apakah request datang dari GitHub
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$payload = file_get_contents('php://input');

$expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $githubSecret);
if (!hash_equals($expectedSignature, $signature)) {
    http_response_code(403);
    die("Unauthorized access");
}

// Tentukan direktori proyek Git
$repoDir = '..'; // Ganti dengan path repo Anda

// Pindah ke direktori repo
chdir($repoDir);

// Eksekusi git pull
$output = shell_exec('git pull origin main 2>&1');

// Simpan log untuk debugging
file_put_contents('../deploy.log', date('[Y-m-d H:i:s] ') . $output . "\n", FILE_APPEND);


$botToken = "6613350065:AAHsASXeYmkyw2kUHQlJBGq22l2hVg0xog0";
$chatId = "-4507862488";

// Escape karakter khusus MarkdownV2
$escape_chars = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
$output_safe = str_replace($escape_chars, array_map(function($c) {
    return "\\" . $c;
}, $escape_chars), $output);

// Format pesan dengan blok kode
$message = "✅ Git Pull Executed: \n```\n" . $output_safe . "\n```";


// Siapkan data yang akan dikirim
$data = [
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode' => 'MarkdownV2'
];

// Inisialisasi cURL
$ch = curl_init("https://api.telegram.org/bot$botToken/sendMessage");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// Eksekusi request
$response = curl_exec($ch);

// Cek error
if (curl_errno($ch)) {
    file_put_contents('../telegram.log', date('[Y-m-d H:i:s] ') . "cURL Error: " . curl_error($ch) . "\n", FILE_APPEND);
} else {
    file_put_contents('../telegram.log', date('[Y-m-d H:i:s] ') . $response . "\n", FILE_APPEND);
}

// Tutup koneksi
curl_close($ch);

// Tampilkan output
echo "<pre>$output</pre>";

?>