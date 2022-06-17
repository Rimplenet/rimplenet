<?php

$deleteTransfer = new class extends RimplenetDeleteTransfers
{
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'transfers/(?P<id>[\d]+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'api_delete_transfers']
        ]);
    }

    public function api_delete_transfers(WP_REST_Request $req)
    {
        $id = sanitize_text_field($req['id']);
        $this->delete($id);
        return new WP_REST_Response($this->response, $this->response['status_code']);
    }
};