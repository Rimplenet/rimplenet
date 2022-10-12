<?php

use Traits\Email\RimplenetEmailTrait;

class RimplenetCreateDebitHook
{
    use RimplenetEmailTrait;
    public function __construct()
    {
        // add_action('rimplenet_create_debit_alert_hook', array( $this, 'sendDebitAlert' ), $action='rimplenet_create_debits', $auth = null ,$request = $param);
        add_action('rimplenet_create_debit_alert_hook', array( $this, 'sendDebitAlert' ), 10, 3);
    }

    public function sendDebitAlert($action, $auth, $params)
    {
        $this->sendDebitAlertEmail($params['email'], $params['amount']);
    }
}

new RimplenetCreateDebitHook();