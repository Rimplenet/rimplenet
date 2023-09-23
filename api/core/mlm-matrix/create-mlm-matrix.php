<?php

class CreateMlmMatrix extends RimplenetCreateMlmMatrix
{
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'mlm-matrix', [
            'methods' => 'POST',
            'callback' => [$this, 'api_create_mlm_matrix']
        ]);
    }

    public function api_create_mlm_matrix(WP_REST_Request $req)
    {
        $req  = [
            'matrix_name' => sanitize_text_field($req['matrix_name'] ?? ''),
            'matrix_description' => sanitize_text_field($req['matrix_description'] ?? ''),
            'matrix_width' => sanitize_text_field($req['matrix_width'] ?? ''),
            'matrix_depth' => sanitize_text_field($req['matrix_depth'] ?? ''),
        ];

        $this->createMlmMatrix($req);    
        return new WP_REST_Response(self::$response, self::$response['status_code']);
    }
}

$createMlmMatrix = new CreateMlmMatrix;