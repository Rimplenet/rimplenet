<?php

// namespace Referrals\GetReferrals;

// use Rimplenet_Wallets;
use Referrals\Base;

class RimplenetGetReferrals extends Base
{
    public function getReferrals(array $param = [])
    {

        $prop = empty($param) ? $this->req : $param;
        extract($prop);

        $referral = get_usermeta($user_id, 'rimplenet_user_referred', $single=false);
        if ($referral) {
            $this->response = [
                'status_code' => 200,
                'status' => 'success',
                'message' => "Referral was successfully Retreived",
                'data' => $referral
            ];
        }else{
            $this->response = [
                'status_code' => 406,
                'status' => 'success',
                'message' => "Referral was successfully Retreived",
                'data' => "No Referral Performed by this User"
            ];

        };

        return $this->response;

       
    }


}
