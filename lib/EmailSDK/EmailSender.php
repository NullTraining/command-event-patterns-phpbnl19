<?php

declare(strict_types=1);

namespace EmailSDK;

class EmailSender
{
    public function send(string $from, string $to, string $subject, string $body): void
    {
        $time = time();

        $data = [
            'from'    => $from,
            'to'      => $to,
            'subject' => $subject,
            'body'    => $body,
            'time'    => $time,
        ];

        $env  = getenv('APP_ENV');
        $path = '../../data/'.$env.'/emails/'.$time.'.json';
        file_put_contents($path, json_encode($data));
    }
}
