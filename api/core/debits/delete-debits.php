<?php

/**
 * Delete
 */

use Txn\DeleteTxn\BaseTxn;

$DeleteDebits = new class extends BaseTxn
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'debits/(?P<txn>[\d]+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'api_delete_debits']
        ]);
    }

    public function api_delete_debits($txn)
    {
        $this->deleteTxn($txn['txn'], 'debit');
        return new WP_REST_Response($this->response);
    }
};
