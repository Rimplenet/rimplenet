<?php

// namespace Referrals\CreateReferrals;

// use Rimplenet_Wallets;
use Referrals\Base;

class RimplenetCreateReferrals extends Base
{
    public function createReferrals(array $param = [])
    {
        $prop = empty($param) ? $this->req : $param;
        extract($prop);

        // add_user_meta($request['user_id'] ?? 1, 'rimplenet_user_refferral', $user['user_meta']['referral'])
        $referral = add_user_meta($user_id ?? 1, 'rimplenet_user_referred', $user_referral);
        $referral = add_user_meta($user_referral ?? 1, 'rimplenet_referrer_sponsor', $user_id);
        if ($referral) {
            $this->response = [
                'status_code' => 201,
                'status' => 'success',
                'message' => "Referral was successfully created",
                'data' => $referral
            ];
        } else {
            $this->response = [
                'status_code' => 201,
                'status' => 'success',
                'message' => "Oops something went wrong.",
                'data' => $referral
            ];
        }

       
    }


}
