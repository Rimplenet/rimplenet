<?php
namespace Txn\DeleteTxn;

use Txn\Base;

abstract Class BaseTxn extends Base
{
    protected function deleteTxn(int $id, string $type = ''){

        if($txn = $this->txnExists($id, $type)):
            wp_delete_post($txn->post_id);
            $this->response = [
                'status_code' => 200,
                'status' => 'success',
                'response_message' => 'Delete action completed',
                'data' => ['Transaction '.$txn->post_id.' Deleted']
            ];
            return true;
        else:
            $this->response['status_code'] = 404;
            $this->response['response_message'] = "Transaction not found";
            $this->response['error'][] = "Operation cannot be completed";
        endif;
        return false;
        
    }
}