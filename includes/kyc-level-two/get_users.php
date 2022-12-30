<?php


class RimplenetGetKycUser
{
    public $validation_error = [];

    public function __construct()
    {
        // add_shortcode('rimplenet-get-kyc-users', array($this, 'get_kyc'));
    }

    public function get_kyc_users()
    {
        $user_id = NULL;
        $results = [];
        $users = [
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key'     => 'user_profile_photo_url',
                    'value'   => '',
                    'compare' => '!=',
                ],
                [
                    'key'     => 'country_of_origin',
                    'value'   => '',
                    'meta_compare' => '!=',
                ],
                [
                    'key'     => 'date_of_birth',
                    'value'   => '',
                    'meta_compare' => '!=',
                ],
                [
                    'key'     => 'identity_document_date_of_issue',
                    'value'   => '',
                    'meta_compare' => '!=',
                ],
                [
                    'key'     => 'identity_document_date_of_expiry',
                    'value'   => '',
                    'meta_compare' => '!=',
                ],
                [
                    'key'     => 'identity_document_file_url_front',
                    'value'   => '',
                    'meta_compare' => '!=',
                ],
                [
                    'key'     => 'identity_document_file_url_back',
                    'value'   => '',
                    'meta_compare' => '!=',
                ],
            ]
        ];

        if (isset($user_id) && null != $user_id) :
            $users["meta_query"]["user_id"] = [
                'key'     => 'user_id',
                'value'   => $user_id,
                'compare' => '==',
            ];
            $result = self::run_cmd($users);
            var_dump($result);
        endif;

        // Create the WP_User_Query object
        // $wp_user_query = new WP_User_Query();
        // $resp = $wp_user_query->get_results();
        // var_dump($resp); exit;
        $resp = self::run_cmd($users);

        foreach ($resp as $user) :
            $results[] = get_user_meta($user->data->ID);
        endforeach;
        // Get the results
        // var_dump($results); exit; 
        return $results;
    }

    private function run_cmd($data)
    {
        $wp_user_query = new WP_User_Query($data);
        $resp = $wp_user_query->get_results();
        return $resp;
    }
}

$RimplenetGetKycUser = new RimplenetGetKycUser();
