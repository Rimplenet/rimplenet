<?php

class RimplenetDeleteCredits extends Credits
{
    public function deleteCredits(int $id, string $type = '')
    {
        if ($credits = $this->creditsExists($id, $type)) :
            do_action(
                'rimplenet_hooks_and_monitors_on_started',
                $action = 'rimplenet_delete_credits',
                $auth = null,
                $request = [
                    "credit_id" => $id
                ]
            );
            wp_delete_post($credits->post_id);
            # Update Credits action hook
            $param['action'] = "success";
            $param['credit_id'] = $credits->post_id;

            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                $action = 'rimplenet_delete_credit',
                $auth = null,
                $param
            );
            return Res::success(["Transaction $credits->post_id Deleted"], "Credits Action Completed");
        else :
            # Update Credits action hook
            $param['action'] = "failed";
            $param['credit_id'] = $credits->post_id;
            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                $action = 'rimplenet_delete_credit',
                $auth = null,
                $request = $param
            );
            return Res::success(["Operation cannot be completed"], "Credit not Found", 404);
        endif;
        return false;
    }
}
