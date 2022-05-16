<?php

use Txn\CreateTxn\BaseTxn;

$createDebits = new class extends BaseTxn
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'transactions/create', [
            'methods' => 'POST',
            'callback' => [$this, 'api_create_txn']
        ]);
    }

    public function api_create_txn(WP_REST_Request $req)
    {
        if ($req['txn_type']=="DEBIT") {
            $this->debitData($req);
        } else {
            $this->creditData($req);
        }
        

        # check for required fields
        if ($this->checkEmpty())
            return new WP_REST_Response($this->response);

        if ($db = $this->createDebit()) :
            return new WP_REST_Response([
                'status' => 200,
                'response_message' => 'Executed',
                'data' => [$db]
            ]);
        else :
            return new WP_REST_Response($this->response);
        endif;
    }

    public function debitData($req)
    {
        $this->req = [
            'note'          => sanitize_text_field($req['note'] ?? ''),
            'user_id'       => (int) $req['user_id'],
            'wallet_id'     => sanitize_text_field(strtolower($req['wallet_id'])),
            'request_id'      => sanitize_text_field($req['txn_type']),
            'amount_to_add' => floatval(-str_replace('-', '', $req['amount'])),
        ];
    }

    public function creditData($req)
    {
        $this->req = [
            'note'          => sanitize_text_field($req['note'] ?? ''),
            'user_id'       => (int) $req['user_id'],
            'wallet_id'     => sanitize_text_field(strtolower($req['wallet_id'])),
            'request_id'      => sanitize_text_field($req['txn_type']),
            'amount_to_add' => floatval(str_replace('-', '',$req['amount'])),
        ];

    }
};
