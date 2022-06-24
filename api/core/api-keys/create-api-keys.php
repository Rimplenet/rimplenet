<?php

/**
 * Create wallet
 */
class CreateApiKeys extends RimplenetApiKeys
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'api_keys', [
            'methods' => 'POST',
            'callback' => [$this, 'api_create_keys']
        ]);
    }

    public function api_create_keys(WP_REST_Request $req)
    {
        $params = [
            'name' => sanitize_text_field($req['app_name'] ?? ''),
            'app_id' => sanitize_text_field($req['app_id'] ?? '')
        ];

        CreateApiKeys::genkey($params);
        return new WP_Rest_Response($this->response, $this->response['status_code']);

    }
}

$CreateApiKeys = new CreateApiKeys();
