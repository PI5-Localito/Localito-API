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
        $header = json_encode(self::HEADER) ?? '{}';
        $header = base64_encode($header);
        $header = trim($header, '=');
        $body = json_encode($data) ?? '{}';
        $body = base64_encode($body);
        $body = trim($body, '=');
        $message = "$header.$body";
        $signature = hash_hmac('sha256', $message, $_ENV['APP_SECRET'], true);
        $signature = base64_encode($signature);
        $signature = trim($signature, '=');
        return "$message.$signature";
    }

    /**
     * Deode the JWT request
     * @return array
     */
    public function decode(string $token): false|array
    {
        $parts = explode('.', $token);
        if (count($parts) < 3) {
            return false;
        }
        $message = "$parts[0].$parts[1]";
        $hash = hash_hmac('sha256', $message, $_ENV['APP_SECRET'], true);
        $parts[0] = base64_decode($parts[0]);
        $parts[1] = base64_decode($parts[1]);
        $parts[2] = base64_decode($parts[2]);
        $parts[0] = json_decode($parts[0], true);
        $parts[1] = json_decode($parts[1], true);
        $equals = strcmp($parts[2], $hash) === 0;
        return $equals ? $parts[1] : false;
    }
}
