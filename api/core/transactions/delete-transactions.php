<?php

/**
 * Delete
 */

use Txn\DeleteTxn\BaseTxn;

$DeleteCredits = new class extends BaseTxn
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'transactions/(?P<txn>[\d]+)', [
            'methods' => 'GET',
            'callback' => [$this, 'api_delete_txn']
        ]);
    }

    public function api_delete_txn($txn, WP_REST_Request $req)
    {
        $this->deleteTxn($txn['txn'], $req['txn_type']);
        return new WP_REST_Response($this->response);
    }
};
