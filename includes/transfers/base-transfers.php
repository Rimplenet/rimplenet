<?php
use Traits\Wallet\RimplenetWalletTrait;

class Transfers extends Utils {

    use RimplenetWalletTrait;
    
    const TERMS = 'INTERNAL TRANSFER';

    public function getTransferById(int $transferID)
    {
        global $wpdb;
        $transferID = sanitize_text_field($transferID);
        $transfer = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='alt_transfer_id' AND meta_value='$transferID'");

        if ($transfer) :
            return $transfer;
        else :
           Res::error('Invalid Transfer Id', 'Transfer not found', 404);
            return false;
            exit;
        endif;
    }
}