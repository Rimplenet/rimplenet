<?php
$getDebits = new Class extends RimplenetGetDebits
{
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('rimplenet/v1', 'debits', [
            'methods' => 'GET',
            'callback' => [$this, 'api_get_debits']
        ]);
    }

    public function api_get_debits(WP_REST_Request $req)
    {
        # ================= set fields ============
        $wlt_id  = sanitize_text_field($req['debits_id'] ?? '');
        $page      = $req['page'] ?? 1;
        
        $this->getDebits($wlt_id, 'debit');
        return new WP_REST_Response($this->response, $this->response['status_code']);


    }
};