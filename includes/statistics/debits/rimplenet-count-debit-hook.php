<?php


class RimplenetCountdebitHook
{
    public function __construct()
    {
        // add_action('rimplenet_hooks_and_monitors_on_started', array( $this, 'rimplenet_admin_menu_create_debits' ) $action='rimplenet_create_debits', $auth = null ,$request = $param);
        // add_action('rimplenet_hooks_and_monitors_on_started', array( $this, 'rimplenet_debits_statistics' ), 10, 3);
    }
    
    private function addSiteWide($key)
    {
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

    public function rimplenet_debits_statistics($action, $auth, $params)
    {
        $wallet_id=$params['wallet_id'];
        $date = [
                    'rimplenet_total_count_debit_at_'.str_replace(":", "_",date("Y:m:d:h:m:s")).'_'.$wallet_id, 
                    'rimplenet_total_count_debit_at_'.str_replace(":", "_",date("Y:m:d:h:m")).'_'.$wallet_id, 
                    'rimplenet_total_count_debit_at_'.str_replace(":", "_",date("Y:m:d:h")).'_'.$wallet_id, 
                    'rimplenet_total_count_debit_at_'.str_replace(":", "_",date("Y:m:d")).'_'.$wallet_id, 
                    'rimplenet_total_count_debit_at_'.str_replace(":", "_",date("Y:m")).'_'.$wallet_id, 
                    'rimplenet_total_count_debit_at_'.str_replace(":", "_",date("Y")).'_'.$wallet_id, 
                    'rimplenet_total_count_debit_at_all_time_'.$wallet_id
                ];

        foreach ($date as $key => $value) {
            $this->addUserCountdebit($params['user_id'], $value, $params['amount']);
            $this->addSiteWide($value);
        }
    }

    private function addUserCountdebit($user_id, $key, $amount)
    {
        // update_user_meta($user_id, $key, $value);
        $data = get_user_meta($user_id, $key);
        if ($data) {
            update_user_meta($user_id, $key, intval($data)+1);
        }else{
            add_user_meta($user_id, $key, 1);
        }
    }

}

$RimplenetAdddebitHook= new RimplenetAdddebitHook();
