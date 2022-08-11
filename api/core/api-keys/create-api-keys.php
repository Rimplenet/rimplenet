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
        register_rest_route('/rimplenet/v1', 'api-keys', [
            'methods' => 'POST',
            'callback' => [$this, 'api_create_keys']
        ]);
    }

    public function api_create_keys(WP_REST_Request $req)
    {
        
        do_action('rimplenet_api_request_started', $req, $allowed_roles = ['administrator'], $action = 'rimplenet_api_key');
        $params = [
            'name' => sanitize_text_field(ucwords($req['app_name'] ?? '')),
            'app_id' => sanitize_text_field($req['app_id'] ?? ''),
            'action' => sanitize_text_field(strtolower($req['action'] ?? '')),
            'key_type' => sanitize_text_field(strtolower($req['key_type'] ?? '')),
            'allowed_actions' => sanitize_text_field(strtolower($req['allowed_actions'] ?? '')),
        ];
        CreateApiKeys::genkey($params);
        return new WP_Rest_Response(Utils::$response, Utils::$response['status_code']);

    }

    
}

$CreateApiKeys = new CreateApiKeys();
