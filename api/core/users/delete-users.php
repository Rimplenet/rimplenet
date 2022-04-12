<?php
require_once(ABSPATH.'wp-admin/includes/user.php');

class DeleteUser
{
    public $validation_error = [];

    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_api_routes'));
    }

    public function register_api_routes()
    {
        register_rest_route(
            'rimplenet/v1', '/users/(?P<user_id>\d+)',
            [
                'methods' => 'DELETE',
                'callback' => [$this, 'delete_user']
            ]
        );
    }

    public function delete_user(WP_REST_Request $request)
    {
        global $wpdb;

        $user_id = sanitize_text_field( $request->get_param( 'user_id', null ) );

        if ($user_id) {

            $table='wp_users';
            $deleted = wp_delete_user( $user_id );

            if ($deleted) {
                $response['status_code'] = 204;
                $response['status'] = 'true';
                $response['response_message'] = 'Successfuly deleted';
                return new WP_REST_Response( $response );
            }

            $response['status_code'] = 404;
            $response['status'] = 'failed';
            $response['response_message'] = 'User not found';
            return new WP_REST_Response( $response );

        }

        $response['status_code'] = 400;
        $response['status'] = 'failed';
        $response['error'] = 'user_id id required';

        return new WP_REST_Response( $response );
    }
    
}

$DeleteUser = new DeleteUser();