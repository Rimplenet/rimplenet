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
        // do_action('rimplenet_api_request_started', $request, $allowed_roles = ['administrator'], $action = 'rimplenet_kyc_get_users');

        $kyc_users  = new RimplenetGetKycUser;
        $result     = $this->formatData($kyc_users->get_kyc_users());

        $data = [
            'status_code'   =>    200,
            'status'        =>    true,
            'message'       =>    "KYC users retrieved successfully",
            'data'          =>    $result
        ];
        return $data;
    }

    private function formatData($users)
    {
        $user_data = [];
        foreach ($users as $k => $user) :
            $user_data[] = [
                "user_profile_photo_url"            =>  $user['user_profile_photo_url'][0],
                "gender"                            =>  $user['gender'][0],
                "country_of_origin"                 =>  $user['country_of_origin'][0],
                "date_of_birth"                     =>  $user['date_of_birth'][0],
                "identity_document_date_of_issue"   =>  $user['identity_document_date_of_issue'][0],
                "identity_document_date_of_expiry"  =>  $user['identity_document_date_of_expiry'][0],
                "identity_document_file_url_front"  =>  $user['identity_document_file_url_front'][0],
                "identity_document_file_url_back"   =>  $user['identity_document_file_url_back'][0]
            ];
        endforeach;

        return $user_data;
    }
}

$RimplenetGetKycUserApi = new RimplenetGetKycUserApi();
