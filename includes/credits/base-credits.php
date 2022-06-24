<?php

namespace Credits;

use Rimplenet_Wallets;
use Wallets\Base;

abstract class Credits extends Base
{

    /**
     * Check empty and required fields
     */
    // protected function checkEmpty(array $req = [])
    // {
    //     # if req is not passed use req from parent
    //     $prop = empty($req) ? $this->req : $req;

    //     foreach ($prop as $key => $value) :
    //         if ($key == 'note') continue;
    //         if (empty($value))
    //             $this->error[str_replace('_', ' ', $key)] = 'Field is required';
    //     endforeach;

    //     if (!empty($this->error)) {
    //         $this->response['response_message'] = "One or two fields are required";
    //         $this->response['error'] = $this->error;
    //         return true;
    //     }

    //     return;
    // }
    

    /**
     * Check  if transaction has been executed before time
     * @param int $id > id of transaction
     * @param string $type > type of transaction (credit / debit)
     * @return object>boolean
     */
    protected function creditsExists(int $id, string $type= 'credit')
    {
        global $wpdb;
        $type = strtoupper($type);
        return $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE post_id ='$id' AND meta_key='request_id' AND meta_value = '$type' ");
    }
}
