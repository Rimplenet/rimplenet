<?php

$GetTransfers = new class extends RimplenetGetTransfers
{
    public function __construct() {
        add_action('rest_api_init', [$this, 'rest_api_routes']);
    }

    public function rest_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'transfers', [
            'methods' => 'GET',
            'callback' => [$this, 'api_get_transfers']
        ]);
    }

    public function api_get_transfers(WP_REST_Request $req)
    {
        do_action('rimplenet_api_request_started', $req, $allowed_roles = ['administrator'], $action = 'rimplenet_get_transfers');
        $param = [
            'transfer_id' => sanitize_text_field($req['transfer_id']),
            'user_id' => sanitize_text_field($req['userId']),
        ];

        $this->transfers($param);
        return new WP_REST_Response(self::$response, self::$response['status_code']);
    }
};