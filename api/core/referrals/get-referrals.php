<?php
//INCLUDED from api/class-base-api.php ~ main plugin file
use Referrals\GetReferrals\BaseReferrals;

$RimplenetGetReferralsApi = new class extends RimplenetGetReferrals
{

    public function __construct()
    {

        add_action('rest_api_init', array($this, 'register_api_routes'));
    }

    public function register_api_routes()
    {
        register_rest_route('rimplenet/v1', '/referrals', array(
            'methods' => 'GET',
            'permission_callback' => '__return_true',
            'callback' => array($this, 'api_retrieve_refferrals'),
        ));
    }


    public function api_retrieve_refferrals(WP_REST_Request $request)
    {


        global $wpdb;

        //Get inputs   
        $user_id = $request['user_id'] ?? 1;
        $security_secret = $request['security_secret'] ?? '1234';
        $security_code = $request['security_secret'] ?? '1234';
        $pageno = $request['pageno'] ?? '1';
        $extra_data = $request['extra_data'];
        if (!empty($extra_data)) {
            $extra_data_json  = json_decode($extra_data);
        }

        //Save inputed data in array
        $inputed_data = array(
            "user_id" => $user_id,
            "security_code" => $security_code
        );
        //Filter out empty inputs
        $empty_input_array = array();
        foreach ($inputed_data as $input_key => $single_data) {
            if (empty($single_data)) {
                $empty_input_array[$input_key]  = "field_required";
            }
        }

        $security_code_ret = get_option('security_code', "1234"); // get security code set
        //Checks
        if (!empty($empty_input_array)) {
            //if atleast one required input is empty
            $status_code = 400;
            $status = "one_or_more_input_required";
            $response_message = "One or more input field is required";
            $data = $empty_input_array;
            $data["error"] = "one_or_more_input_required";
        } elseif (!empty($security_code) and $security_code != $security_code_ret) {
            // throw error if security fails 
            $status_code = 401;
            $status = "incorrect_security_credentials";
            $response_message = "Security verification failed";
            $data = array(
                "error" => "incorrect_security_credentials"
            );
        } elseif (!empty($extra_data) and json_last_error() === JSON_ERROR_NONE) {
            // throw error if extra_data is not json 
            $status_code = 406;
            $status = "extra_data_not_json";
            $response_message = "extra_data input field should be json";
            $data = array(
                "extra_data" => $extra_data,
                "error" => json_last_error()
            );
        } else {
            //get some info here to retun or some nice date like belo

           
            if (isset($user_id) && $user_id != "any") {
                $this->req = [
                    'user_id'       => (int) $request['user_id'],
                ];
                $referrals=$this->getReferrals();
                return new WP_REST_Response($referrals);

                // $txn_loop = get_usermeta($user_id, 'rimplenet_user_referral');
            } 
        }

        return new WP_REST_Response($data, $status_code);
    }

    public function validateData()
    {
        # code...
    }
};


