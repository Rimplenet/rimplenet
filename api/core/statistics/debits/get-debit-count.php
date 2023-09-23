<?php


$RimplenetWalletAddonGetDebitCount = new class
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet-wallet-addon/v1', 'get-debit-count', [
            'methods' => 'GET',
            'callback' => [$this, 'api_get_Debit_count']
        ]);
    }

    public function api_get_Debit_count(WP_REST_Request $req)
    {
        $this->req = [
            'entity_type'          => sanitize_text_field($req['entity_type'] ?? ''),
            'entity_id'       => sanitize_text_field($req['entity_id']),
            'meta_key'     => sanitize_text_field(strtolower($req['meta_key'])),
            'meta_value'      => $req['meta_value'],
        ];

        $this->update();
        return new WP_REST_Response(self::$response, self::$response['status_code']);
    }
};
