<?php

/**
 * Delete
 */


$DeleteCredits = new class extends RimplenetDeleteCredits
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'credits/(?P<credits>[\d]+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'api_delete_credits']
        ]);
    }

    public function api_delete_credits($Credits)
    {
        do_action('rimplenet_api_request_started', $Credits, $allowed_roles = ['administrator'], $action = 'rimplenet_delete_credits');

        $this->deleteCredits($Credits['credits'], 'credit');
        return new WP_REST_Response(self::$response);
    }
};
