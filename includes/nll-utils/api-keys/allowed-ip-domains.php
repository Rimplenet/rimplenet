<?php

class AllowedIPAndDomains
{
    public static function ip()
    {
        $ips = trim(str_replace(['\r\n', ' ', PHP_EOL], '', get_option('allowed_ip_address')));

        # if ip is not set ignore and return true
        if ($ips === null || empty($ips)) return true;

        // $ip_array = explode(',', $ips);
        // // foreach($ip_array)
    }
    public static function domains()
    {
        $referral = self::host();

        if (!filter_var($referral, FILTER_VALIDATE_URL)) :
            Res::error([
                'referral' => "invalid referral domain",
                'domain' => $referral,
                'process' => 'start'
            ], 'Invalid referral domain', 403);
            echo json_encode(Utils::$response);
            exit;
        endif;

        $domains = trim(str_replace(['\r\n', ' ', PHP_EOL], '', get_option('allowed_domains')));

        # if domain is not set ignore and return true
        if ($domains === null || empty($domains)) return true;

        $domain_array = explode(',', $domains);
        foreach ($domain_array as $key => $domain) :
            if (empty($domain)) unset($domain_array[$key]);
        endforeach;

        if (in_array($referral, $domain_array)) return true;

        Res::error([
            'referral' => "invalid referral domain",
            'domain' => $referral,
            'process' => 'end'
        ], 'Invalid referral domain', 403);
        echo json_encode(Utils::$response);
        exit;
    }

    public static function host()
    {
        $schema = $_SERVER['REQUEST_SCHEME'] ?? '';
        $host = $_SERVER['SERVER_NAME'] ?? '';

        return $schema.'://'.$host;
    }
}
