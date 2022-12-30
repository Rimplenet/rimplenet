<?php

class RimplenetGetKycUserApi
{
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_api_routes'));
    }

    public function register_api_routes()
    {
        register_rest_route('rimplenet/v1', '/get-kyc-users', [
            'methods' => 'GET',
            'permission_callback' => '__return_true',
            'callback' => [$this, 'get_kyc_users'],
        ]);
    }

    public function get_kyc_users(WP_REST_Request $request)
    {
        do_action('rimplenet_api_request_started', $request, $allowed_roles = ['administrator'], $action = 'rimplenet_kyc_get_users');
        $kyc_users  = new RimplenetGetKycUser;
        $result     = $kyc_users->get_kyc_users();

        $data = [
            'status_code'   =>    200,
            'status'        =>    true,
            'message'       =>    "KYC users retrieved successfully",
            'data'          =>    $result
        ];
        return $data;
    }
}

$RimplenetGetKycUserApi = new RimplenetGetKycUserApi();
