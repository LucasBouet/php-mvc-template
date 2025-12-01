<?php

class JWT
{
    private $signing_key;

    public function __construct()
    {
        // Clean up the key by removing "base64:" prefix and "=" padding
        $this->signing_key = str_replace(['base64:', '='], '', getenv('APP_KEY'));
    }

    public function encode(array $payload) : string
    {
        $header = [
            "alg" => "HS512",
            "typ" => "JWT"
        ];

        $encodedHeader = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
        $encodedPayload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');

        $signature = hash_hmac('sha512', "$encodedHeader.$encodedPayload", $this->signing_key, true);
        $encodedSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return "{$encodedHeader}.{$encodedPayload}.{$encodedSignature}";
    }

    public function decode(string $token) : ?array
    {
        $tokenParts = explode('.', $token);

        if (count($tokenParts) !== 3) {
            return null; // Invalid JWT format
        }

        list($encodedHeader, $encodedPayload, $providedSignature) = $tokenParts;

        $header = json_decode(base64_decode(strtr($encodedHeader, '-_', '+/')), true);
        $payload = json_decode(base64_decode(strtr($encodedPayload, '-_', '+/')), true);
        $signature = base64_decode(strtr($providedSignature, '-_', '+/'));

        // Validate JSON decoding
        if ($header === null || $payload === null) {
            return null;
        }

        // Re-create signature
        $validSignature = hash_hmac('sha512', "$encodedHeader.$encodedPayload", $this->signing_key, true);

        if (!hash_equals($signature, $validSignature)) {
            return null; // Invalid signature
        }

        return [
            'header' => $header,
            'body' => $payload
        ];
    }
}

/*

$jwtHelper = new \APP\Helpers\JWT();
$token = $jwtHelper->encode(['user_id' => 123, 'role' => 'admin']);
echo "JWT Token: " . $token;

$decoded = $jwtHelper->decode($token);
if ($decoded) {
    print_r($decoded); // Prints header and payload
} else {
    echo "Invalid token.";
}

 */
