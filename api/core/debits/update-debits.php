<?php

use Txn\UpdateTxn\Base;

$updateDebits = new class extends Base
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('rimplenet/v1', 'debits', [
            'methods' => 'PUT',
            'callback' => [$this, 'api_update_debits']
        ]);
    }

    public function api_update_debits(WP_REST_Request $request)
    {
        $this->req = [
            'id' => (int) $request['txn_id'],
            'note' => sanitize_text_field($request['note']),
            'type' => 'debit'
        ];

        if ($this->checkEmpty())
            return new WP_REST_Response($this->response);

        $this->updateTxn();
        return new WP_REST_Response($this->response);
    }
};
