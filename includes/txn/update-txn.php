<?php

namespace Txn\UpdateTxn;

use Txn\Base as TxnBase;

abstract class Base extends TxnBase
{
    /**
     * Update Transaction note
     * @param int $id transaction id > ID of transaction to update
     * @param string $note > Value to update note
     * @param string $type > Type of the transaction (debit / credit)
     * @return boolean
     */
    protected function updateTxn(int $id = 0, $note = '', $type = '')
    {
        # assign param id to $id otherwise get id from class
        $id = $id !== 0 ? $id : $this->req['id'];
        # assign param note to $note otherwise get note from class
        $note = !empty($note) ? $note : $this->req['note'];
        # assign param type to $type otherwise get type from class
        $type = !empty($type) ? $type : $this->req['type'];


        # Check if the transaction has already been executed
        if ($this->txnExists($id, $type)) :

            # if transaction is executed proceed to update transaction note
            $txn =  $this->getTxnToUpdate($id);
            if ($txn) :
                update_post_meta($id, 'note', $note);
                $this->response = [
                    'status_code' => 200,
                    'response_message' => 'Updated',
                    'data' => ['note' => $note]
                ];
            else :
                # create new post meta for transaction note is not exists before
                add_post_meta($id, 'note', $note);
                $this->response['status_code'] = 200;
                $this->response['response_message'] = "Updated";
                $this->response['data']['note'] = $note;
            endif;
            return true;
        else :
            # if the transaction has not been executed before time return error false
            $this->response['response_message'] = 'Transaction Not Found';
            $this->response['error'][] = 'Transaction Not Found';
            return false;
        endif;
    }

    /**
     * Transaction to update
     * @param int $id > id of transaction
     * @return boolean>object
     */
    protected function getTxnToUpdate(int $id)
    {
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='note' AND post_id='$id'");
    }

    /**
     * Check  if transaction has been executed before time
     * @param int $id > id of transaction
     * @param string $type > type of transaction (creadit / debit)
     * @return boolean>object
     */
    protected function txnExists(int $id, string $type= 'credit')
    {
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE post_id ='$id' AND meta_key='request_id' AND meta_value = '$type' ");
    }
}
