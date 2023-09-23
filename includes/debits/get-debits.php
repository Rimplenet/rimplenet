<?php
class RimplenetGetDebits extends Debits
{
    public function getDebits($id, $type)
    {
        if ($id !== '') :
            do_action('rimplenet_hooks_and_monitors_on_started', 'rimplenet_get_debit', null, ['debit_id' => $id]);
            return $this->debitById($id, $type);
        else :
            do_action('rimplenet_hooks_and_monitors_on_started', 'rimplenet_get_debits', null, []);
            return $this->getAllDebits();
        endif;

        return $this->response;
    }

    public function debitById($id, $type)
    {
        if ($debits = $this->debitsExists($id, $type)) :
            $debits = get_post($debits->post_id);
            $debit = $this->formatDebits($debits);
            # action hook
            $param['action_status'] = "success";
            $param['debit_id'] = $debit;
            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                'rimplenet_get_debit',
                null,
                $param
            );
            return Res::success($debit, 'Transacrion Retrieved', 200);
        else :
            # action hook
            $param['action_status'] = "failed";
            $param['debit_id'] = $id;
            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                'rimplenet_get_debit',
                null,
                $param
            );
            return Res::error(['Invalid Transaction Id ' . $id], 'Transaction not Found', 404);
        endif;
    }

    public function getAllDebits()
    {
        $this->queryTxn('', self::DEBIT);
        if ($this->query && $this->query->have_posts()) :
            $posts = $this->query->get_posts();
            foreach ($posts as $key => $post) :
                $posts[$key] = $this->formatDebits($post);
            endforeach;
            # action hook
            $param['action_status'] = "success";
            $param['debits'] = $posts;
            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                'rimplenet_get_debits',
                null,
                $param
            );
            return Res::success($posts, 'Debits Retrieved');
        else :
            # action hook
            $param['action_status'] = "failed";
            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                'rimplenet_get_debits',
                null,
                $param
            );
            return Res::error("Sorry we couldnt retrieve any Debit at the moment", "No Debit Found", 404);
        endif;
        // return $this
    }

    protected function formatDebits($data)
    {
        $this->id = $data->ID;

        $res = [
            'id'                => $data->ID,
            'amount'            => $this->postMeta('amount'),
            'balance_after'     => $this->postMeta('balance_after'),
            'balance_before'    => $this->postMeta('balance_before'),
            'currency'          => $this->postMeta('currency'),
            'funds_type'        => $this->postMeta('funds_type'),
            'request_id'        => $this->postMeta('request_id'),
            'total_balance_after' => $this->postMeta('total_balance_after'),
            'total_balance_before' => $this->postMeta('total_balance_before'),
            'debits_request_id'       => $this->postMeta('txn_request_id'),
            'txn_type'             => $this->postMeta('txn_type'),
            'note'                 => $this->postMeta('note'),
            'description'          => $data->post_title
        ];

        return $res;
    }
}
