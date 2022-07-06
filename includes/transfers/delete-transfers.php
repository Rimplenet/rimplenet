<?php
class RimplenetDeleteTransfers extends Transfers
{
    public function delete($transferId)
    {
        $transfer = $this->getTransferById($transferId);

        if (!$transfer) return Res::error("Transfer not Found", '', 404);
        wp_delete_post($transfer->post_id, false);
        Res::success(["Transder $transferId Successfully Deleted"], "Action Completed");
        return true;
    }
}
