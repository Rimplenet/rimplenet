<?php

use Credits\UpdateCredits\BaseCredits;

$updateCredits = new class extends BaseCredits
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
        $this->req = [
            'id' => $request['credit_id'],
            'note' => sanitize_text_field($request['note']),
            'type' => 'credit'
        ];

        if ($this->checkEmpty())
            return new WP_REST_Response($this->response);

        $this->updateCredits();
        return new WP_REST_Response($this->response);
    }
};
