<?php

// namespace Referrals\DeleteReferrals;


use Referrals\Base;

class RimplenetDeleteReferrals extends Base
{
    protected function deleteReferrals(array $param = [])
    {

        $prop = empty($param) ? $this->req : $param;
        extract($prop);

        // $referral = get_usermeta($user_id, 'rimplenet_user_referral', $single=false);
        $referral=delete_user_meta( $user_id, 'rimplenet_user_referral', $user_referral );
        if ($referral) {
            $this->response = [
                'status_code' => 200,
                'status' => 'success',
                'message' => "Referral was successfully deleted",
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
