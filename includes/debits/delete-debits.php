<?php
// namespace Debits\DeleteDebits;

use Debits\Debits;
use Res\Res;

Class RimplenetDeleteDebits extends Debits
{
    protected function deleteDebits(int $id, string $type = ''){

        if($Debits = $this->debitsExists($id, $type)):
            wp_delete_post($Debits->post_id);
            return Res::success(["Transaction $Debits->post_id Deleted"], "Debits Action Completed");
        else:
            return Res::success(["Operation cannot be completed"], "Debit not Found", 404);
        endif;
        return false;
        
    }
}