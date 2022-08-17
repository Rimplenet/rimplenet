<?php
class RimplenetDeleteDebits extends Debits
{
    protected function deleteDebits(int $id, string $type = '')
    {
        do_action('rimplenet_hooks_and_monitors_on_started', $action = 'rimplenet_delete_debits', $auth = null, $request = ['debit_id' => $id]);

        if ($Debits = $this->debitsExists($id, $type)) :
            wp_delete_post($Debits->post_id);
            # action hook
            $param['action'] = "success";
            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                $action = 'rimplenet_delete_debit',
                $auth = null,
                $request = $param
            );
            return Res::success(["Transaction $Debits->post_id Deleted"], "Debits Action Completed");
        else :
            # action hook
            $param['action'] = "failed";
            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                $action = 'rimplenet_delete_debit',
                $auth = null,
                $request = $param
            );
            return Res::success(["Operation cannot be completed"], "Debit not Found", 404);
        endif;
        return false;
    }
}
