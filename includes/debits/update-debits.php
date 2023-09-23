<?php
class RimplenetUpdateDebits extends Debits
{
    /**
     * Update Transaction note
     * @param int $id transaction id > ID of transaction to update
     * @param string $note > Value to update note
     * @param string $type > Type of the transaction (debit / debits)
     * @return boolean
     */
    protected function updateDebits(int $id = 0, $note = '', $type = '')
    {
        # assign param id to $id otherwise get id from class
        $id = $id !== 0 ? $id : $this->req['id'];
        # assign param note to $note otherwise get note from class
        $note = !empty($note) ? $note : $this->req['note'];
        # assign param type to $type otherwise get type from class
        $type = !empty($type) ? $type : $this->req['type'];

        $request = ['debit_id' => $id, 'note' => $note];
        if ($this->checkEmpty(['debit_id' => $id, 'note' => $note])) return;


        # Check if the transaction has already been executed
        if ($this->debitsExists($id, $type)) :

            # if transaction is executed proceed to update transaction note
            $txn =  $this->getDebitsToUpdate($id);

            do_action('rimplenet_hooks_and_monitors_on_started', 'rimplenet_update_debits', null, $request);

            if ($txn) :
                update_post_meta($id, 'note', $note);
                Res::success(['note' => $note], 'Updated');
            else :
                # create new post meta for transaction note is not exists before
                add_post_meta($id, 'note', $note);
                Res::success(['note' => $note], 'Updated');
            endif;
            return true;
        else :
            # if the transaction has not been executed before time return error false
            return Res::error(['error' => 'Transaction Not Found'], 'Not Found', 404);
        endif;
    }

    /**
     * Transaction to update
     * @param int $id > id of transaction
     * @return boolean>object
     */
    protected function getDebitsToUpdate(int $id)
    {
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='note' AND post_id='$id'");
    }
}
