<?php

/**
 * Delete
 */

use Debits\DeleteDebits\BaseDebits;

$DeleteDebits = new class extends BaseDebits
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
        $this->deleteDebits($Debits['Debits'], 'debit');
        return new WP_REST_Response($this->response);
    }
};
