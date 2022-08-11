<?php


$updateDebits = new class extends RimplenetUpdateDebits
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
        do_action('rimplenet_api_request_started', $req, $allowed_roles = ['administrator'], $action = 'rimplenet_update_debits');
        $this->req = [
            'id' => (int) $request['debits_id'],
            'note' => sanitize_text_field($request['note']),
            'type' => 'debit'
        ];

        $this->updateDebits();
        return new WP_REST_Response(self::$response, self::$response['status_code']);
    }
};
