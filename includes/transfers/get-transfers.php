<?php

class RimplenetGetTransfers extends Transfers
{

    public function transfers($params = [])
    {
        extract($params);
        if (isset($userId) && $userId !== '') :
        # get transfer by a user Id
        elseif (isset($transfer_id) && $transfer_id !== '') :
           return $this->transferById($transfer_id);
        else :
            # Get all Transfers
            $this->query = new WP_Query([
                'post_type' => self::POST_TYPE,
                'post_status' => 'publish',
                'posts_per_page' => Utils::LIMIT,
                'paged' => $page ?? 1,
                'tax_query' => array([
                    'taxonomy' => self::TAXONOMY,
                    'field'    => 'name',
                    'terms'    => self::TRANSFERS,
                ]),
            ]);
            if ($this->query && $this->query->have_posts()) :
                $posts = $this->query->get_posts();
                foreach ($posts as $key => $transfer) :
                    $posts[$key] = $this->formatTransfer($transfer);
                endforeach;
                Res::success($posts, 'Transfers Retrieved');
                return $posts;
            else :
                Res::error(
                    'You have not made any transfer at the moment',
                    'No wallet Found',
                    404
                );
            endif;
        endif;
    }

    public function transferById($transfer_id)
    { 
        $transfer = $this->getTransferById($transfer_id);
        if(!$transfer) return false;
        $transfer = get_post($transfer->post_id);
        $transferData = $this->formatTransfer($transfer);
       Res::success($transferData, 'Transfer Retrieved');
        return $transferData;
    }

    public function formatTransfer($transfer)
    {
        $this->id = $transfer->ID;
        $transferFrom = get_user_by('ID', $this->postMeta('transfer_address_from'));
        $transferTo = get_user_by('ID', $this->postMeta('transfer_address_to'));
        return (object) [
            'transferId' => $this->postMeta('alt_transfer_id'),
            'transferTo' => $transferTo->user_login ?? '',
            'transferFrom' =>  $transferFrom->user_login ?? '',
            'transferAmount' => $this->postMeta('amount'),
            'transferDesc' => $this->postMeta('note'),
            'transferType' => $this->postMeta('txn_type'),
            'balanceBefore' => $this->postMeta('balance_before'),
            'balanceAfter' => $this->postMeta('balance_after'),
            'totalBalanceBefore' => $this->postMeta('total_balance_before'),
            'totalBalanceAfter' => $this->postMeta('total_balance_after'),
            'symbol' => $this->postMeta('transfer_wallet_symbol'),
            'currency' => $this->postMeta('currency'),
            'dateRaw' => $transfer->post_modified,
            'formattedDate' => date('Y M D h:i a', strtotime($transfer->post_modified))
        ];
    }
}
