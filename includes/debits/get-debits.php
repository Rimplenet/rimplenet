<?php
class RimplenetGetDebits extends Debits
{
    public function getDebits($id, $type)
    {
        if($id !== ''):
            return $this->debitById($id, $type);
        else:
            return $this->getAllDebits();
        endif;

        return $this->response;
    }

    public function debitById($id, $type)
    {
        if($credits = $this->debitsExists($id, $type)):
            $credits = get_post($credits->post_id);
            return Res::success($this->formatDebits($credits), 'Transacrion Retrieved', 200);
        else:
            return Res::error(['Invalid Transaction Id '.$id], 'Transaction not Found', 404);
        endif;
    }

    public function getAllDebits()
    {
        $this->queryTxn('', self::DEBIT);
        if($this->query && $this->query->have_posts()):
            $posts = $this->query->get_posts();
            foreach ($posts as $key => $post):
                $posts[$key] = $this->formatDebits($post);
            endforeach;
            return Res::success($posts, 'Debits Retrieved');
        else:
            return Res::error("Sorry we couldnt retrieve any Debit at the moment", "No Debit Found", 404);
        endif;
        // return $this
    }

    protected function formatDebits($data)
    {
        $this->id = $data->ID;

        $res = [
            'amount'            => $this->postMeta('amount'),
            'balance_after'     => $this->postMeta('balance_after'),
            'balance_before'    => $this->postMeta('balance_before'),
            'currency'          => $this->postMeta('currency'),
            'funds_type'        => $this->postMeta('funds_type'),
            'request_id'        => $this->postMeta('request_id'),
            'total_balance_after' => $this->postMeta('total_balance_after'),
            'total_balance_before' => $this->postMeta('total_balance_before'),
            'Debits_request_id'       => $this->postMeta('Debits_request_id'),
            'Debits_type'             => $this->postMeta('Debits_type'),
            'note'                 => $this->postMeta('note'),
            'description'          => $data->post_title
        ];
        
        return $res;
    }
}