<?php

class AllowedIPAndDomains
{
    public static function ip_domains($ipAndDomains)
    {
        $referral = self::host();

        $domains = trim(str_replace(['\r\n', ' ', PHP_EOL], '', $ipAndDomains));

        # if domain is not set ignore and return true
        if ($domains === null || empty($domains)) return true;

        $domain_array = explode(',', $domains);
        foreach ($domain_array as $key => $domain) :
            if (empty($domain)) unset($domain_array[$key]);
        endforeach;

        if (in_array($referral['ip'], $domain_array) || in_array($referral['host'], $domain_array) || in_array($referral['referral'], $domain_array)) return true;

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

        return [
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            'host' => $_SERVER['SERVER_NAME'] ?? '',
            'referral' => $_SERVER['HTTP_REFERER'] ?? '*',
        ];
    }
}
