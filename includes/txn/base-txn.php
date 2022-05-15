<?php

namespace Txn;

use Rimplenet_Wallets;

abstract class Base
{

    /**
     * @var array
     */
    protected $error;

    /**
     * @var string
     */
    const TAXONOMY   = 'rimplenettransaction_type';
    const POST_TYPE  = 'rimplenettransaction';
    const MIN_AMOUNT = 0;
    const MAX_AMOUNT = 999999999;
    const WALLET_CAT_NAME = 'RIMPLENET WALLETS';
    const DEBIT = 'DEBIT';
    const CREDIT = 'CREDIT';

    /**
     * @var array
     */
    protected $response = [
        'status_code' => 400,
        'status' => 'failed',
        'response_message' => '',
        'data' => [],
        'error' => []
    ];

    protected $query = null;

    /**
     * Check empty and required fields
     */
    protected function checkEmpty(array $req = [])
    {
        # if req is not passed use req from parent
        $prop = empty($req) ? $this->req : $req;

        foreach ($prop as $key => $value) :
            if ($key == 'note') continue;
            if (empty($value))
                $this->error[str_replace('_', ' ', $key)] = 'Field is required';
        endforeach;

        if (!empty($this->error)) {
            $this->response['response_message'] = "One or two fields are required";
            $this->response['error'] = $this->error;
            return true;
        }

        return;
    }
    

    /**
     * Check  if transaction has been executed before time
     * @param int $id > id of transaction
     * @param string $type > type of transaction (creadit / debit)
     * @return object>boolean
     */
    protected function txnExists(int $id, string $type= 'credit')
    {
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE post_id ='$id' AND meta_key='request_id' AND meta_value = '$type' ");
    }
}
