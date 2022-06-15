<?php
/**
 * Create Transfers
 */

class CreateTransfers extends RimplenetCreateTransfer
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'transfers', [
            'methods' => 'POST',
            'callback' => [$this, 'api_create_transfers']
        ]);
    }

    public function api_create_transfers(WP_REST_Request $req)
    {
        return "Hello Transfer";
    }
}

$CreateTransfers = new CreateTransfers();