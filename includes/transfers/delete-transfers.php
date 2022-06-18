<?php

use Transfers\Transfers;

class RimplenetDeleteTransfers extends Transfers
{
    public function delete($transferId)
    {
       $transfer = $this->getTransferById($transferId);

       if(!$transfer) return;
       wp_delete_post($transfer->post_id, false );
       $this->success(["Transder $transferId Successfully Deleted"], "Action Completed");
       return true;
    }
}