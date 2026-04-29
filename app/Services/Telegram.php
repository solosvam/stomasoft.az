<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Telegram
{
    public static string $token = '8734774530:AAF8Y-2wLFpUpFxa-rgs7H6-0NHdujJdoIc';
    public static string $api_url = 'https://api.telegram.org';

    public static function send($to, $message)
    {
        $data = [
            "chat_id" => $to,
            "text" => $message,
            "parse_mode" => "HTML"
        ];

        $url = self::$api_url . "/bot" . self::$token . "/sendMessage?" . http_build_query($data);

        try {
            $response = file_get_contents($url);
            return $response ? json_decode($response, true) : false;
        } catch (\Throwable $e) {
            return false;
        }
    }


}
