<?php

class RimplenetUpdateCredits extends Credits
{
    /**
     * Update Transaction note
     * @param int $id transaction id > ID of transaction to update
     * @param string $note > Value to update note
     * @param string $type > Type of the transaction (debit / credit)
     * @return boolean
     */
    public function updateCredits(int $id = 0, $note = '', $type = '')
    {
        # assign param id to $id otherwise get id from class
        $id = $id !== 0 ? $id : $this->req['id'];
        # assign param note to $note otherwise get note from class
        $note = !empty($note) ? $note : $this->req['note'];
        # assign param type to $type otherwise get type from class
        $type = !empty($type) ? $type : $this->req['type'];

        if (self::requires([
            'credit_id' => $id,
            'note' => $note
        ])) return;

        $request = ['credit_id' => $id, 'note' => $note];
        # update credit do action
        do_action('rimplenet_hooks_and_monitors_on_started', 'rimplenet_update_credits', null, $request);


        # Check if the transaction has already been executed
        if ($this->creditsExists($id, $type)) :

            # if transaction is executed proceed to update transaction note
            $txn =  $this->getCreditsToUpdate($id);


            if ($txn) :
                update_post_meta($id, 'note', $note);

                #  Updated Credit on ended do action
                $$request['action_status'] = "success";
                do_action(
                    'rimplenet_hooks_and_monitors_on_finished',
                    'rimplenet_update_credit',
                    null,
                    $request
                );
                return Res::success(['note' => $note . " Updated"], 'Note updated');
            else :
                # create new post meta for transaction note is not exists before
                add_post_meta($id, 'note', $note);

                # update credits action hook
                $request['action_status'] = "success";
                do_action(
                    'rimplenet_hooks_and_monitors_on_finished',
                    'rimplenet_update_credit',
                    null,
                    $request
                );
                return Res::success(['note' => $note . " Updated"], 'Note updated');
            endif;
            return true;
        else :
            # Update Credits action hook
            $request['action_status'] = "failed";
            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                'rimplenet_update_credit',
                null,
                $request
            );
            # if the transaction has not been executed before time return error false
            return Res::error(['Transaction Not Found'], 'Transaction not Found', 404);
        endif;
    }

    /**
     * Transaction to update
     * @param int $id > id of transaction
     * @return boolean>object
     */
    protected function getCreditsToUpdate(int $id)
    {
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='note' AND post_id='$id'");
    }
}
