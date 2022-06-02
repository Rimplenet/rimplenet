<?php

// namespace Referrals\CreateReferrals;

use Rimplenet_Wallets;
use Referrals\Base;

class RimplenetCreateReferrals extends Base
{
    protected function createReferrals(array $param = [])
    {

        $prop = empty($param) ? $this->req : $param;
        extract($prop);

        // add_user_meta($request['user_id'] ?? 1, 'rimplenet_user_refferral', $user['user_meta']['referral'])
        $referral = add_user_meta($user_id ?? 1, 'rimplenet_user_referral', $user_referral);
        if ($referral) {
            $this->response = [
                'status_code' => 201,
                'status' => 'success',
                'response_message' => "Referral was successfully created",
                'data' => $referral
            ];
        };

       
    }


}
