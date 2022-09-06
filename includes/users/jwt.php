<?php

class JWT
{
    public static $AUTH_KEY;
    public function __construct() {
        self::$AUTH_KEY = get_option('nll_auth_secret_key');
    }

    /**
     * -------------- NOTE BRUIZ ------------------------
     * Since methods below are static method which are probably not instantiated before
     * initializing and $AUTH_KEY is set within constructor i.e Methods statically called
     * will ignore the constructor to stay on a safer side, instatiate JWT to get token
     * ================ Updated on Sept 2 2022 ================
     */
    public static function encode($payload) {

        $jwt = new JWT;

        $secret = $jwt::$AUTH_KEY;
        // Create the token header
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);

        // Encode Header
        $base64UrlHeader = Jwt::base64UrlEncode($header);

        // Encode Payload
        $base64UrlPayload = Jwt::base64UrlEncode($payload);

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = Jwt::base64UrlEncode($signature);

        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $jwt;

    }

    public static function decode($access_token) {

        $jwt = new JWT;
        $secret = $jwt::$AUTH_KEY;
        // split the token
        $tokenParts = explode('.', $access_token);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        // check the expiration time - note this will cause an error if there is no 'exp' claim in the token
        // $expiration = Carbon::createFromTimestamp(json_decode($payload)->exp);
        // $tokenExpired = (Carbon::now()->diffInSeconds($expiration, false) < 0);

        // build a signature based on the header and payload using the secret
        $base64UrlHeader = Jwt::base64UrlEncode($header);
        $base64UrlPayload = Jwt::base64UrlEncode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = Jwt::base64UrlEncode($signature);

        // verify it matches the signature provided in the token
        $signatureValid = ($base64UrlSignature === $signatureProvided);

        if ($signatureValid) {
            if (time() > json_decode($payload)->exp) {
                return "Expired token";
            } else {
                return $payload;
                // echo "Token has not expired yet.\n";
            }
        } else {
            return "Invalid signature";
            // echo "The signature is NOT valid\n";
        }

    }

    public static function base64UrlEncode($text)
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }
}