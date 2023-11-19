<?php

namespace App\Service;

class JWT
{
    public const HEADER = [
        'alg' => "HS256",
        'typ' => "JWT",
    ];

    /**
     * Encode data as JWT
     * @param array $data
     *
     * @return string
     */
    public function encode(mixed $data)
    {
        $header = base64_encode(self::HEADER);
        $body = json_encode($data) ?? '{}';
        $body = base64_encode($body);
        $message = "$header.$body";
        $signature = hash('sha256', $message);
        return "$message.$signature";
    }

    /**
     * Deode the JWT request
     * @return array
     */
    public function decode(string $token): array
    {

    }
}
