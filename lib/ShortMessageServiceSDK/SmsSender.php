<?php

declare(strict_types=1);

namespace ShortMessageServiceSDK;

class SmsSender
{
    public function send(string $phoneNumber, string $message): void
    {
        $time = time();

        $data = [
            'phoneNumber' => $phoneNumber,
            'message'     => $message,
        ];

        $env  = getenv('APP_ENV');
        $path = '../../data/'.$env.'/sms/'.$time.'.json';
        file_put_contents($path, json_encode($data));
    }
}
