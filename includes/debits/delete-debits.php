<?php
class RimplenetDeleteDebits extends Debits
{
    protected function deleteDebits(int $id, string $type = '')
    {
        do_action('rimplenet_hooks_and_monitors_on_started', 'rimplenet_delete_debits', null, ['debit_id' => $id]);

        if ($Debits = $this->debitsExists($id, $type)) :
            wp_delete_post($Debits->post_id);
            # action hook
            $param['action_status'] = "success";
            $param['debit_id'] = $Debits->post_id;
            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                'rimplenet_delete_debit',
                null,
                $param
            );
            return Res::success(["Transaction $Debits->post_id Deleted"], "Debits Action Completed");
        else :
            # action hook
            $param['action_status'] = "failed";
            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                'rimplenet_delete_debit',
                null,
                $param
            );
            return Res::success(["Operation cannot be completed"], "Debit not Found", 404);
        endif;
        return false;
    }
}
