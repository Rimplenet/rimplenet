<?php

new class extends RimplenetGetCredits
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('rimplenet/v1', 'credits', [
            'methods' => 'GET',
            'callback' => [$this, 'api_get_credits']
        ]);
    }

    public function api_get_credits(WP_REST_Request $req)
    {
        
        do_action('nll_api_request_started_api_key', $req, $allowed_roles = ['administrator'], $action = 'rimplenet_get_credits');
        # ================= set fields ============
        $wlt_id  = sanitize_text_field($req['credit_id'] ?? '');
        // $page      = $req['page'] ?? 1;

            $this->getCredits($wlt_id, 'credit');
            return new WP_REST_Response(self::$response, self::$response['status_code']);
    }
};
