<?php

use Traits\Email\RimplenetEmailTrait;

class RimplenetGeneralEmailHook
{
    use RimplenetEmailTrait;
    public function __construct()
    {
        // add_action('rimplenet_create_credit_alert_hook', array( $this, 'sendCreditAlert' ), $action='rimplenet_create_credits', $auth = null ,$request = $param);
        add_action('rimplenet_general_email_hook', array( $this, 'sendEmail' ), 10, 3);
    }

    public function sendEmail($action, $auth, $params)
    {
        $this->sendGeneralEmail($params['email'], $params);
    }
}

new RimplenetGeneralEmailHook();