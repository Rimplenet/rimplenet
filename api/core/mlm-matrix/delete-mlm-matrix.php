<?php

class DeleteMlmMatrix extends RimplenetDeleteMlmMatrix
{
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'mlm-matrix/(?P<id>[\d]+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'api_delete_mlm_matrix']
        ]);
    }

    public function api_delete_mlm_matrix(WP_REST_Request $req)
    {
        $this->deleteMlmMatrix(sanitize_text_field($req['id']));    
        return new WP_REST_Response(self::$response, self::$response['status_code']);
    }
}

$DeleteMlmMatrix = new DeleteMlmMatrix;