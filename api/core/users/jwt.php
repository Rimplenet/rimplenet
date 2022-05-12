<?php
require 'bootstrap.php';

use Carbon\Carbon;

class JWT
{

    public static function encode($payload) {

        $secret = getenv('SECRET');
        // Create the token header
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);

        // Encode Header
        $base64UrlHeader = base64UrlEncode($header);

        // Encode Payload
        $base64UrlPayload = base64UrlEncode($payload);

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = base64UrlEncode($signature);

        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $jwt;

    }

    public static function decode($access_token) {

        $secret = getenv('SECRET');
        // split the token
        $tokenParts = explode('.', $access_token);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        // check the expiration time - note this will cause an error if there is no 'exp' claim in the token
        $expiration = Carbon::createFromTimestamp(json_decode($payload)->exp);
        $tokenExpired = (Carbon::now()->diffInSeconds($expiration, false) < 0);

        // build a signature based on the header and payload using the secret
        $base64UrlHeader = base64UrlEncode($header);
        $base64UrlPayload = base64UrlEncode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = base64UrlEncode($signature);

        // verify it matches the signature provided in the token
        $signatureValid = ($base64UrlSignature === $signatureProvided);

        if ($signatureValid) {
            if ($tokenExpired) {
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
}