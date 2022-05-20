<?php

/**
 * Delete
 */

use Credits\DeleteCredits\BaseCredits;

$DeleteCredits = new class extends BaseCredits
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
        $this->deleteCredits($Credits['credits'], 'credit');
        return new WP_REST_Response($this->response);
    }
};
