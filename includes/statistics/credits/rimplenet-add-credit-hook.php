<?php


class RimplenetAddCreditHook extends BaseCreditHook
{
    public $totalCredit = [];
    public function __construct()
    {
        add_action('rimplenet_hooks_and_monitors_on_finished', array( $this, 'rimplenet_create_credits' ), 10, 3);
    }
    

    public function rimplenet_create_credits($action, $auth, $params)
    {

        if ($action !='rimplenet_create_credits') {
            // exit;
        }else{
            $wallet_id=$params['wallet_id'];
        
            $date = [
                    'rimplenet_total_credit_at_'.str_replace(":", "_",date("Y:m:d:H:i:s")).'_'.$wallet_id, 
                    'rimplenet_total_credit_at_'.str_replace(":", "_",date("Y:m:d:H:i")).'_'.$wallet_id, 
                    'rimplenet_total_credit_at_'.str_replace(":", "_",date("Y:m:d:H")).'_'.$wallet_id, 
                    'rimplenet_total_credit_at_'.str_replace(":", "_",date("Y:m:d")).'_'.$wallet_id, 
                    'rimplenet_total_credit_at_'.str_replace(":", "_",date("Y:m")).'_'.$wallet_id, 
                    'rimplenet_total_credit_at_'.str_replace(":", "_",date("Y")).'_'.$wallet_id, 
                    'rimplenet_total_credit_at_all_time_'.$wallet_id
                ];

        foreach ($date as $key => $value) {
            $this->addUserCredit($params['user_id'], $value, $params['amount']);
            $this->addSiteWide($value, $params['amount']);
            $this->rimplenet_count_user_credits($params['user_id'], $value);
            $this->rimplenet_count_site_wide_credits($value);
        }

        $this->rimplenet_create_highest_lowest_credits($action, $auth, $params);

        }
    }


    public function rimplenet_create_highest_lowest_credits($action, $auth, $params)
    {

        if ($action !='rimplenet_create_credits') {
            // exit;
        }else{
            $wallet_id=$params['wallet_id'];
            $transaction_id=$params['transaction_id'];
        
            $date = [
                    'rimplenet_maximum_credit_amount_at_'.str_replace(":", "_",date("Y:m:d:H:i:s")).'_'.$wallet_id, 
                    'rimplenet_maximum_credit_amount_at_'.str_replace(":", "_",date("Y:m:d:H:i")).'_'.$wallet_id, 
                    'rimplenet_maximum_credit_amount_at_'.str_replace(":", "_",date("Y:m:d:H")).'_'.$wallet_id, 
                    'rimplenet_maximum_credit_amount_at_'.str_replace(":", "_",date("Y:m:d")).'_'.$wallet_id, 
                    'rimplenet_maximum_credit_amount_at_'.str_replace(":", "_",date("Y:m")).'_'.$wallet_id, 
                    'rimplenet_maximum_credit_amount_at_'.str_replace(":", "_",date("Y")).'_'.$wallet_id, 
                    'rimplenet_maximum_credit_amount_at_all_time_'.$wallet_id,
                ];
            

        foreach ($date as $key => $value) {
            $this->addHighestUserCredit($params['user_id'], $value, $params['amount'], $transaction_id);
            $this->addHighestSiteWide($value, $params['amount'], $transaction_id);
            // $this->rimplenet_count_user_credits($params['user_id'], $value);
            // $this->rimplenet_count_site_wide_credits($value);
        }

        $date = [
            'rimplenet_minimum_credit_amount_at_'.str_replace(":", "_",date("Y:m:d:H:i:s")).'_'.$wallet_id, 
            'rimplenet_minimum_credit_amount_at_'.str_replace(":", "_",date("Y:m:d:H:i")).'_'.$wallet_id, 
            'rimplenet_minimum_credit_amount_at_'.str_replace(":", "_",date("Y:m:d:H")).'_'.$wallet_id, 
            'rimplenet_minimum_credit_amount_at_'.str_replace(":", "_",date("Y:m:d")).'_'.$wallet_id, 
            'rimplenet_minimum_credit_amount_at_'.str_replace(":", "_",date("Y:m")).'_'.$wallet_id, 
            'rimplenet_minimum_credit_amount_at_'.str_replace(":", "_",date("Y")).'_'.$wallet_id, 
            'rimplenet_minimum_credit_amount_at_all_time_'.$wallet_id,
        ];

        foreach ($date as $key => $value) {
            $this->addLowestUserCredit($params['user_id'], $value, $params['amount'], $transaction_id);
            $this->addLowestSiteWide($value, $params['amount'], $transaction_id);
            // $this->rimplenet_count_user_credits($params['user_id'], $value);
            // $this->rimplenet_count_site_wide_credits($value);
        }

        }
    }

}

$RimplenetAddCreditHook= new RimplenetAddCreditHook();
