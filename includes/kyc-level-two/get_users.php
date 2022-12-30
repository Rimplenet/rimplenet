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
        
        $resp = self::run_cmd($users);

        foreach ($resp as $user) :
            // return $user;
            $result = get_user_meta($user->data->ID);
            $results[] = array_merge(self::to_array($result), self::to_array($user));
        endforeach;

        return $this->formatData($results);
    }

    private function run_cmd($data)
    {
        $wp_user_query = new WP_User_Query($data);
        $resp = $wp_user_query->get_results();
        return $resp;
    }

    private function to_array($obj)
    {
        if(is_array($obj)):
            $result = $obj;
        elseif(is_object($obj)) :
            $result = json_decode(json_encode($obj), true);
        else :
            $result = json_decode($obj, true);
        endif;

        return $result;
    }

    private function formatData($users)
    {
        $user_data = [];
        foreach ($users as $k => $user) :
            $user_data[] = [
                "id"                                =>  $user["ID"],
                "gender"                            =>  $user['gender'][0],
                "username"                          =>  $user['username'][0], 
                "first_name"                        =>  $user['first_name'][0], 
                "last_name"                         =>  $user['last_name'][0],
                "email"                             =>  $user["data"]['user_email'],
                "user_profile_photo_url"            =>  $user['user_profile_photo_url'][0],
                "country_of_origin"                 =>  $user['country_of_origin'][0],
                "date_of_birth"                     =>  $user['date_of_birth'][0],
                "identity_document_date_of_issue"   =>  $user['identity_document_date_of_issue'][0],
                "identity_document_date_of_expiry"  =>  $user['identity_document_date_of_expiry'][0],
                "identity_document_file_url_front"  =>  $user['identity_document_file_url_front'][0],
                "identity_document_file_url_back"   =>  $user['identity_document_file_url_back'][0],
            ];
        endforeach;

        return $user_data;
    }
}

$RimplenetGetKycUser = new RimplenetGetKycUser();
