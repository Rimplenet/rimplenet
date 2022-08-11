<?php

/**
 * Delete
 */


$DeleteDebits = new class extends RimplenetDeleteDebits
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'debits/(?P<Debits>[\d]+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'api_delete_debits']
        ]);
    }

    public function api_delete_debits($Debits)
    {
        do_action('rimplenet_api_request_started', $Debits, $allowed_roles = ['administrator'], $action = 'rimplenet_delete_debits');
        $this->deleteDebits($Debits['Debits'], 'debit');
        return new WP_REST_Response(self::$response, self::$response['status_code']);
    }
};
