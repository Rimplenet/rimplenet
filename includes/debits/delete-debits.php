<?php
// namespace Debits\DeleteDebits;

use Debits\Base;

Class RimplenetDeleteDebits extends Base
{
    protected function deleteDebits(int $id, string $type = ''){

        if($Debits = $this->debitsExists($id, $type)):
            wp_delete_post($Debits->post_id);
            $this->response = [
                'status_code' => 200,
                'status' => 'success',
                'response_message' => 'Delete action completed',
                'data' => ['Transaction '.$Debits->post_id.' Deleted']
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