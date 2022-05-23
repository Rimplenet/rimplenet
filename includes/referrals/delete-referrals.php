<?php

namespace Referrals\DeleteReferrals;


use Referrals\Base;

abstract class BaseReferrals extends Base
{
    protected function deleteReferral(array $param = [])
    {

        $prop = empty($param) ? $this->req : $param;
        extract($prop);

        // $referral = get_usermeta($user_id, 'rimplenet_user_referral', $single=false);
        $referral=delete_user_meta( $user_id, 'rimplenet_user_referral', $user_referral );
        if ($referral) {
            $this->response = [
                'status_code' => 200,
                'status' => 'success',
                'response_message' => "Referral was successfully Retreived",
                'data' => $referral
            ];
        }else{
            $this->response = [
                'status_code' => 406,
                'status' => 'success',
                'response_message' => "Referral was successfully Retreived",
                'data' => "No Referral Performed by this User"
            ];

        };

        return $this->response;

       
    }


}
