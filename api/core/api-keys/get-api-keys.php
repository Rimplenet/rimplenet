<?php

/**
 * Create wallet
 */
class GetApiKey extends RimplenetApiKeys
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
        $key = sanitize_text_field($req['api_key'] ?? '');

        GetApiKey::decKey($key);
        return new WP_Rest_Response($this->response, $this->response['status_code']);

    }
}

$GetApiKey = new GetApiKey();
