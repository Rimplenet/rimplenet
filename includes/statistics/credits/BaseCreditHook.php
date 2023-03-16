<?php
class BaseCreditHook
{

    public function addSiteWide($key, $amount)
    {
        $data=get_option($key);
        if ($data !== false ) {
            // The option already exists, so update it.
            return update_option( $key, intval($data)+$amount );
 
        } else {
            // The option hasn't been created yet, so add it with $autoload set to 'no'.
            $deprecated = null;
            $autoload = 'no';
            return add_option($key, intval($amount), $deprecated, $autoload );
        }
    }

    public function addUserCredit($user_id, $key, $amount)
    {
        // update_user_meta($user_id, $key, $value);
        $data = get_user_meta($user_id, $key);
        if ($data) {
            update_user_meta($user_id, $key, intval($data[0])+$amount);
        }else{
            add_user_meta($user_id, $key, $amount);
        }
    }

    public function rimplenet_count_user_credits($user_id,$key)
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

    public function rimplenet_count_site_wide_credits($key)
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





    public function addHighestSiteWide($key, $amount)
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



    public function addLowestSiteWide($key, $amount)
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




    public function addHighestUserCredit($user_id, $key, $amount, $transaction_id)
    {
        // update_user_meta($user_id, $key, $value);
        $data = get_user_meta($user_id, $key);
        if ($data) {
            // update_user_meta($user_id, $key, intval($data[0])+$amount);
            if ($amount > $data[0]) {
                update_user_meta($user_id, $key, $amount);

                $key = str_replace('amount', 'transaction', $key);

                update_user_meta($user_id, $key, $transaction_id);
            }
        }else{
            add_user_meta($user_id, $key, $amount);
            $key = str_replace('amount', 'transaction', $key);

            add_user_meta($user_id, $key, $transaction_id);
        }
    }




    public function addLowestUserCredit($user_id, $key, $amount, $transaction_id)
    {
        // update_user_meta($user_id, $key, $value);
        $data = get_user_meta($user_id, $key);
        if ($data) {
            // update_user_meta($user_id, $key, intval($data[0])+$amount);
            if ($amount < $data[0]) {
                update_user_meta($user_id, $key, $amount);
                $key = str_replace('amount', 'transaction', $key);
                update_user_meta($user_id, $key, $transaction_id);
            }
        }else{
            add_user_meta($user_id, $key, $amount);
            $key = str_replace('amount', 'transaction', $key);
            add_user_meta($user_id, $key, $transaction_id);
        }
    }

}