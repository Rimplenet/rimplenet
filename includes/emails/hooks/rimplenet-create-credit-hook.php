<?php

use Traits\Email\RimplenetEmailTrait;

class RimplenetCreateCreditHook
{
    use RimplenetEmailTrait;
    public function __construct()
    {
        add_action('rimplenet_create_credit_alert_hook', array( $this, 'sendCreditAlert' ) $action='rimplenet_create_credits', $auth = null ,$request = $param);
    }

    public function sendCreditAlert($action, $auth, $params)
    {
        $this->sendCreditAlertEmail($params['email'], $params['amount']);
    }
}