<?php

$getCredits = new class extends VerifyEmailMail
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('rimplenet/v1', 'verify-email-address', [
            'methods' => 'POST',
            'callback' => [$this, 'send_verify_email_mail']
        ]);
    }

    public function send_verify_email_mail(WP_REST_Request $req)
    {
        # ================= set fields ============
        $data['email']  = sanitize_text_field($req['email_address']);
        $data['token']  = sanitize_text_field($req['token_to_verify_email_address']);

        // if ($wlt_id !== '') :
            $this->validate($data);
            return new WP_REST_Response($this->response, $this->response['status_code']);
    }
};
