<?php

$RimplenetPostPasswordResetMailApi = new class extends RimplenetPostPasswordResetMail
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('rimplenet/v1', 'reset-password', [
            'methods' => 'POST',
            'callback' => [$this, 'send_password_reset_mail']
        ]);
    }

    public function send_password_reset_mail(WP_REST_Request $req)
    {
        # ================= set fields ============
        $email  = sanitize_text_field($req['email']);
        $fire_email =sanitize_text_field($req['fire_email'] ?? false);

        $this->req = [
            'email'          => sanitize_text_field($req['email_address'] ?? ''),
            'password'       => sanitize_text_field($req['new_password']),
            'confirm_password'     => sanitize_text_field($req['confirm_new_password']),
            'token'      => sanitize_text_field($req['token_to_reset_password']),
        ];

        // if ($wlt_id !== '') :
            // $this->send($email, $fire_email);
            $this->validate();
            return new WP_REST_Response($this->response, $this->response['status_code']);
    }
};
