<?php


class RimplenetAddDebitHook extends BaseDebitHook
{
    public function __construct()
    {
        // add_action('rimplenet_hooks_and_monitors_on_started', array( $this, 'rimplenet_admin_menu_create_debits' ) $action='rimplenet_create_debits', $auth = null ,$request = $param);
        // add_action('rimplenet_hooks_and_monitors_on_started', array( $this, 'rimplenet_create_debits' ,));
        //    add_action('rimplenet_hooks_and_monitors_on_started', array( $this, 'rimplenet_create_debits' ), 10, 3);
        add_action('rimplenet_hooks_and_monitors_on_finished', array( $this, 'rimplenet_create_debits' ), 10, 3);
    }
  
    // rimplenet_create_debits
    public function rimplenet_create_debits($action, $auth, $params)
    {

        if ($action !='rimplenet_create_debits') {
            // exit;
        }else{
            $wallet_id=$params['wallet_id'];
        // $date = date("Y:m:d:h:m:s");
        // $date['rimplenet_total_debit_at_'.str_replace(":", "_",date("Y:m:d:h:m:s")).'_'.$wallet_id] = str_replace(":", "_",date("Y:m:d:h:m:s"));
        $date = ['rimplenet_total_debit_at_'.str_replace(":", "_",date("Y:m:d:H:i:s")).'_'.$wallet_id, 
                'rimplenet_total_debit_at_'.str_replace(":", "_",date("Y:m:d:H:i")).'_'.$wallet_id, 
                'rimplenet_total_debit_at_'.str_replace(":", "_",date("Y:m:d:H")).'_'.$wallet_id, 
                'rimplenet_total_debit_at_'.str_replace(":", "_",date("Y:m:d")).'_'.$wallet_id, 
                'rimplenet_total_debit_at_'.str_replace(":", "_",date("Y:m")).'_'.$wallet_id, 
                'rimplenet_total_debit_at_'.str_replace(":", "_",date("Y")).'_'.$wallet_id, 
                'rimplenet_total_debit_at_all_time_'.$wallet_id];

        foreach ($date as $key => $value) {
            $this->addUserDebit($params['user_id'], $value, $params['amount']);
            $this->addSiteWide($value, $params['amount']);
            $this->rimplenet_count_user_debits($params['user_id'], $value);
            $this->rimplenet_count_site_wide_debits($value);
        }

        $this->rimplenet_create_highest_lowest_debits($action, $auth, $params);
        }
    }

   

    public function rimplenet_create_highest_lowest_debits($action, $auth, $params)
    {

        if ($action !='rimplenet_create_debits') {
            // exit;
        }else{
            $wallet_id=$params['wallet_id'];
            $transaction_id=$params['transaction_id'];
        
            $date = [
                    'rimplenet_maximum_debit_amount_at_'.str_replace(":", "_",date("Y:m:d:H:i:s")).'_'.$wallet_id, 
                    'rimplenet_maximum_debit_amount_at_'.str_replace(":", "_",date("Y:m:d:H:i")).'_'.$wallet_id, 
                    'rimplenet_maximum_debit_amount_at_'.str_replace(":", "_",date("Y:m:d:H")).'_'.$wallet_id, 
                    'rimplenet_maximum_debit_amount_at_'.str_replace(":", "_",date("Y:m:d")).'_'.$wallet_id, 
                    'rimplenet_maximum_debit_amount_at_'.str_replace(":", "_",date("Y:m")).'_'.$wallet_id, 
                    'rimplenet_maximum_debit_amount_at_'.str_replace(":", "_",date("Y")).'_'.$wallet_id, 
                    'rimplenet_maximum_debit_amount_at_all_time_'.$wallet_id,
                ];

            foreach ($date as $key => $value) {
                $this->addHighestUserDebit($params['user_id'], $value, $params['amount'], $transaction_id);
                $this->addHighestSiteWide($value, $params['amount'], $transaction_id);
                // $this->rimplenet_count_user_debits($params['user_id'], $value);
                // $this->rimplenet_count_site_wide_debits($value);
            }

            $date = [
                'rimplenet_minimum_debit_amount_at_'.str_replace(":", "_",date("Y:m:d:H:i:s")).'_'.$wallet_id, 
                'rimplenet_minimum_debit_amount_at_'.str_replace(":", "_",date("Y:m:d:H:i")).'_'.$wallet_id, 
                'rimplenet_minimum_debit_amount_at_'.str_replace(":", "_",date("Y:m:d:H")).'_'.$wallet_id, 
                'rimplenet_minimum_debit_amount_at_'.str_replace(":", "_",date("Y:m:d")).'_'.$wallet_id, 
                'rimplenet_minimum_debit_amount_at_'.str_replace(":", "_",date("Y:m")).'_'.$wallet_id, 
                'rimplenet_minimum_debit_amount_at_'.str_replace(":", "_",date("Y")).'_'.$wallet_id, 
                'rimplenet_minimum_debit_amount_at_all_time_'.$wallet_id,
            ];

            foreach ($date as $key => $value) {
                $this->addLowestUserDebit($params['user_id'], $value, $params['amount'], $transaction_id);
                $this->addLowestSiteWide($value, $params['amount'], $transaction_id);
                // $this->rimplenet_count_user_debits($params['user_id'], $value);
                // $this->rimplenet_count_site_wide_debits($value);
            }

        }
    }




}

$RimplenetAddDebitHook= new RimplenetAddDebitHook();
