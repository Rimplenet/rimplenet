<?php

use Txn\UpdateTxn\BaseTxn;

$updateDebits = new class extends BaseTxn
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('rimplenet/v1', 'transactions/update', [
            'methods' => 'PUT',
            'callback' => [$this, 'api_update_txn']
        ]);
    }

    public function api_update_txn(WP_REST_Request $request)
    {
        $this->req = [
            'id' => (int) $request['txn_id'],
            'note' => sanitize_text_field($request['note']),
            'type' => $request['txn_type']
        ];

        if ($this->checkEmpty())
            return new WP_REST_Response($this->response);

        $this->updateTxn();
        return new WP_REST_Response($this->response);
    }
};
