<?php
class RimplenetGetCredits extends Credits
{
    public function getCredits($id, $type)
    {
        if ($id !== '') :
            # get single credit
            do_action('rimplenet_hooks_and_monitors_on_started', 'rimplenet_get_credit', null, ['credit_id' => $id]);

            return $this->creditById($id, $type);
        else :
            # get all credits
            do_action('rimplenet_hooks_and_monitors_on_started', 'rimplenet_get_credits', null, []);

            return $this->getAllCredits();
        endif;

        return $this->response;
    }

    public function creditById($id, $type)
    {
        if ($credits = $this->CreditsExists($id, $type)) :
            $credits = get_post($credits->post_id);
            # Format credits
            $credits = $this->formatCredits($credits);
            # get Credits action hook
            $param['action_status'] = "success";
            $param['credit'] = $credits;
            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                'rimplenet_get_credit',
                null,
                $param
            );
            return Res::success($credits, 'Transacrion Retrieved', 200);
        else :
            # get Credits action hook
            $param['action_status'] = "failed";
                do_action(
                    'rimplenet_hooks_and_monitors_on_finished',
                    'rimplenet_get_credit',
                    null,
                    $param
                );
            return Res::error(['Invalid Transaction Id ' . $id], 'Transaction not Found', 404);
        endif;
    }

    public function getAllCredits()
    {
        $this->queryTxn('');
        if ($this->query && $this->query->have_posts()) :
            $posts = $this->query->get_posts();
            foreach ($posts as $key => $post) :
                $posts[$key] = $this->formatCredits($post);
            endforeach;
            # get Credits action hook
            $param['action_status'] = "success";
            $param['credits'] = $posts;
                do_action(
                    'rimplenet_hooks_and_monitors_on_finished',
                    'rimplenet_get_credits',
                    null,
                    $param
                );
            return Res::success($posts, 'Credits Retrieved');
        else :
            # Update Credits action hook
            $param['action_status'] = "failed";
                do_action(
                    'rimplenet_hooks_and_monitors_on_finished',
                    'rimplenet_get_credits',
                    null,
                    $param
                );
            return Res::error("Sorry we couldnt retrieve any Credit at the moment", "No wallet Found", 404);
        endif;
        // return $this
    }

    protected function formatCredits($data)
    {
        $this->id = $data->ID;

        $res = [
            'id'            => $data->ID,
            'amount'            => $this->postMeta('amount'),
            'balance_after'     => $this->postMeta('balance_after'),
            'balance_before'    => $this->postMeta('balance_before'),
            'currency'          => $this->postMeta('currency'),
            'funds_type'        => $this->postMeta('funds_type'),
            'request_id'        => $this->postMeta('request_id'),
            'request_id'        => $this->postMeta('txn_request_id'),
            'total_balance_after' => $this->postMeta('total_balance_after'),
            'total_balance_before' => $this->postMeta('total_balance_before'),
            'credits_request_id'       => $this->postMeta('txn_request_id'),
            'txn_type'             => $this->postMeta('txn_type'),
            'note'                 => $this->postMeta('note'),
            'description'          => $data->post_title
        ];
        return $res;
    }
}
