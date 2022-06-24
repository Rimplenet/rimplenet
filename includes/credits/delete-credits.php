<?php

use Credits\Credits;

Class RimplenetDeleteCredits extends Credits
{
    public function deleteCredits(int $id, string $type = ''){

        if($credits = $this->creditsExists($id, $type)):
            wp_delete_post($credits->post_id);
            $this->response = [
                'status_code' => 200,
                'status' => 'success',
                'response_message' => 'Delete action completed',
                'data' => ['Transaction '.$credits->post_id.' Deleted']
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