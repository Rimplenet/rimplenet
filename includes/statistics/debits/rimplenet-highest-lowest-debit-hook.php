<?php


class RimplenetHighestLowestDebitHook
{
    public function __construct()
    {
        // add_action('rimplenet_hooks_and_monitors_on_started', array( $this, 'rimplenet_admin_menu_create_Debits' ) $action='rimplenet_create_Debits', $auth = null ,$request = $param);
    //    add_action('rimplenet_hooks_and_monitors_on_started', array( $this, 'rimplenet_create_Debits' ), 10, 3);

        // add_action('rimplenet_hooks_and_monitors_on_finished', array( $this, 'rimplenet_create_highest_lowest_Debits' ), 10, 3);
    }
    
    private function addSiteWide($key, $amount)
    {
        $data=get_option($key);
        if ($data !== false ) {
            // The option already exists, so update it.
            // return update_option( $key, intval($data)+$amount );
            if ($amount > $data) {
                return update_option( $key, intval($data)+$amount );
            }
 
        } else {
            // The option hasn't been created yet, so add it with $autoload set to 'no'.
            $deprecated = null;
            $autoload = 'no';
            return add_option($key, intval($amount), $deprecated, $autoload );
        }
    }


    private function addLowestSiteWide($key, $amount)
    {
        $data=get_option($key);
        if ($data !== false ) {
            // The option already exists, so update it.
            // return update_option( $key, intval($data)+$amount );
            if ($amount < $data) {
                return update_option( $key, intval($data)+$amount );
            }
 
        } else {
            // The option hasn't been created yet, so add it with $autoload set to 'no'.
            $deprecated = null;
            $autoload = 'no';
            return add_option($key, intval($amount), $deprecated, $autoload );
        }
    }

    public function rimplenet_create_highest_lowest_Debits($action, $auth, $params)
    {

        if ($action !='rimplenet_create_Debits') {
            // exit;
        }else{
            $wallet_id=$params['wallet_id'];
        
            $date = [
                    'rimplenet_highest_amount_at_'.str_replace(":", "_",date("Y:m:d:H:i:s")).'_'.$wallet_id, 
                    'rimplenet_highest_amount_at_'.str_replace(":", "_",date("Y:m:d:H:i")).'_'.$wallet_id, 
                    'rimplenet_highest_amount_at_'.str_replace(":", "_",date("Y:m:d:H")).'_'.$wallet_id, 
                    'rimplenet_highest_amount_at_'.str_replace(":", "_",date("Y:m:d")).'_'.$wallet_id, 
                    'rimplenet_highest_amount_at_'.str_replace(":", "_",date("Y:m")).'_'.$wallet_id, 
                    'rimplenet_highest_amount_at_'.str_replace(":", "_",date("Y")).'_'.$wallet_id, 
                    'rimplenet_highest_amount_at_all_time_'.$wallet_id
                ];

        foreach ($date as $key => $value) {
            $this->addUserDebit($params['user_id'], $value, $params['amount']);
            $this->addSiteWide($value, $params['amount']);
            // $this->rimplenet_count_user_Debits($params['user_id'], $value);
            // $this->rimplenet_count_site_wide_Debits($value);
        }

        $date = [
            'rimplenet_lowest_amount_at_'.str_replace(":", "_",date("Y:m:d:H:i:s")).'_'.$wallet_id, 
            'rimplenet_lowest_amount_at_'.str_replace(":", "_",date("Y:m:d:H:i")).'_'.$wallet_id, 
            'rimplenet_lowest_amount_at_'.str_replace(":", "_",date("Y:m:d:H")).'_'.$wallet_id, 
            'rimplenet_lowest_amount_at_'.str_replace(":", "_",date("Y:m:d")).'_'.$wallet_id, 
            'rimplenet_lowest_amount_at_'.str_replace(":", "_",date("Y:m")).'_'.$wallet_id, 
            'rimplenet_lowest_amount_at_'.str_replace(":", "_",date("Y")).'_'.$wallet_id, 
            'rimplenet_lowest_amount_at_all_time_'.$wallet_id
        ];

        foreach ($date as $key => $value) {
            $this->addLowestUserDebit($params['user_id'], $value, $params['amount']);
            $this->addLowestSiteWide($value, $params['amount']);
            // $this->rimplenet_count_user_Debits($params['user_id'], $value);
            // $this->rimplenet_count_site_wide_Debits($value);
        }

        }
    }

    private function addUserDebit($user_id, $key, $amount)
    {
        // update_user_meta($user_id, $key, $value);
        $data = get_user_meta($user_id, $key);
        if ($data) {
            // update_user_meta($user_id, $key, intval($data[0])+$amount);
            if ($amount > $data[0]) {
                update_user_meta($user_id, $key, $amount);
            }
        }else{
            add_user_meta($user_id, $key, $amount);
        }
    }


    private function addLowestUserDebit($user_id, $key, $amount)
    {
        // update_user_meta($user_id, $key, $value);
        $data = get_user_meta($user_id, $key);
        if ($data) {
            // update_user_meta($user_id, $key, intval($data[0])+$amount);
            if ($amount < $data[0]) {
                update_user_meta($user_id, $key, $amount);
            }
        }else{
            add_user_meta($user_id, $key, $amount);
        }
    }

    private function rimplenet_count_user_Debits($user_id,$key)
    {

        $key= str_replace("total", "total_count", $key);
         // update_user_meta($user_id, $key, $value);
         $data = get_user_meta($user_id, $key);
         if ($data) {
             update_user_meta($user_id, $key, intval($data)+1);
         }else{
            add_user_meta($user_id, $key, 1);
         }
 
         
    }

    private function rimplenet_count_site_wide_Debits($key)
    {
        $key= str_replace("total", "total_count", $key);
        $data=get_option($key);
        if ($data !== false ) {
            // The option already exists, so update it.
            return update_option( $key, $data+1 );
 
        } else {
            // The option hasn't been created yet, so add it with $autoload set to 'no'.
            $deprecated = null;
            $autoload = 'no';
            return add_option($key, 1, $deprecated, $autoload );
        }
    }

}

$RimplenetHighestLowestDebitHook= new RimplenetHighestLowestDebitHook();
