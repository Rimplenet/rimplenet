<?php

/**
 * Create wallet
 */
class GetApiKey extends RimplenetGetApiKeys
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'api_keys', [
            'methods' => 'GET',
            'callback' => [$this, 'api_get_keys']
        ]);
    }

    public function api_get_keys(WP_REST_Request $req)
    {
        // do_action('rimplenet_api_request_started', $request, $allowed_roles=['administrator'], $action='rimplenet_api_keys');
        $key = sanitize_text_field($req['api_key'] ?? '');

        $this->getKeys($key);
        return new WP_Rest_Response($this->response, $this->response['status_code']);

    }
}

$GetApiKey = new GetApiKey();
