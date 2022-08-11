<?php

$updateCredits = new class extends RimplenetUpdateCredits
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('rimplenet/v1', 'credits', [
            'methods' => 'PUT',
            'callback' => [$this, 'api_update_credits']
        ]);
    }

    public function api_update_credits(WP_REST_Request $request)
    {
        do_action('rimplenet_api_request_started', $request, $allowed_roles = ['administrator'], $action = 'rimplenet_update_credits');

        $this->req = [
            'id' => $request['credit_id'] ?? '',
            'note' => sanitize_text_field($request['note'] ?? ''),
            'type' => 'credit'
        ];
        // return $this->req;
        $this->updateCredits();
        return new WP_REST_Response(self::$response, self::$response['status_code']);
    }
};
