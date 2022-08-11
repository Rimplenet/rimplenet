<?php
Class RimplenetDeleteDebits extends Debits
{
    protected function deleteDebits(int $id, string $type = ''){

        if($Debits = $this->debitsExists($id, $type)):

            do_action('rimplenet_hooks_and_monitors_on_started', $action = 'rimplenet_delete_debits', $auth = null, $request = ['debit_id' => $id]);

            wp_delete_post($Debits->post_id);
            return Res::success(["Transaction $Debits->post_id Deleted"], "Debits Action Completed");
        else:
            return Res::success(["Operation cannot be completed"], "Debit not Found", 404);
        endif;
        return false;
        
    }
}