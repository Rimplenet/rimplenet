<?php

$RimplenetPostPasswordResetMailApi = new class extends RimplenetPostPasswordResetMail
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('rimplenet/v1', 'password-reset-mail', [
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
            'email'          => sanitize_text_field($req['email'] ?? ''),
            'password'       => sanitize_text_field($req['password']),
            'confirm_password'     => sanitize_text_field($req['confirm_password']),
            'token'      => sanitize_text_field($req['token']),
        ];

        // if ($wlt_id !== '') :
            // $this->send($email, $fire_email);
            $this->validate();
            return new WP_REST_Response($this->response, $this->response['status_code']);
    }
};
