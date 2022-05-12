<?php



class Rimplenet_Wallets extends RimplenetRules {
 
   
 public function __construct() {
 
     add_shortcode('rimplenet-wallet-history', array($this, 'RimplenetWalletHistory')); 
     add_action('add_decode_rimplenet_rules', array($this,'rimplenet_rules_add_to_mature_wallet'), 25, 4 );
     add_action('add_decode_rimplenet_rules', array($this,'rimplenet_rules_add_to_immature_wallet'), 25, 4 );
     add_shortcode('rimplenet-wallet', array($this, 'ShortcodeDesignWallet'));
     add_shortcode('rimplenet-user-to-user-wallet-transfer-form', array($this, 'UserToUserWalletTransferForm'));
     
         
     /**
     * Custom currency and currency symbol
     */
    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
    
     add_action('admin_init',  array($this,'update_rimplenet_woocommerce_wallet_and_currency'));
     add_filter('woocommerce_currencies', array($this,'add_rimplenet_currency' ));
    //add_filter('woocommerce_currency_symbol', array($this,'add_rimplenet_currency_symbol', 10, 2));
    }
    
     add_action( 'show_user_profile', 'rimplenet_user_wallet_profile_fields' );
     add_action( 'edit_user_profile', 'rimplenet_user_wallet_profile_fields' );
     add_action( 'personal_options_update', 'save_rimplenet_user_wallet_profile_fields' );
     add_action( 'edit_user_profile_update', 'save_rimplenet_user_wallet_profile_fields' );

 }


 
 public function RimplenetWalletHistory($atts) {
            

        ob_start();

        include plugin_dir_path( __FILE__ ) . 'layouts/wallet-transaction-history.php';
         
        $output = ob_get_clean();

        return $output;
      
 }
 

 public function wallet_credit_debit_form($user_id="all",$wallet_id="all", $allowed_role=""){
        
    include plugin_dir_path( dirname( __FILE__ ) ) . 'includes/layouts/wallet-credit-and-debit-form.php';
    
 } 

 public function rimplenet_rules_add_to_mature_wallet($rule,$user, $obj_id='', $args=''){
 
     $amount  = trim($args[0]);
     $wallet_id  = trim($args[1]);
     $funds_info  = trim($args[2]);
     //$tag_ref = trim($args[3]);
     $tag_ref = $obj_id;
     if(empty($funds_info)){
         $funds_info = "Txn Returns - ".$obj_id;
     }
     //$notification_mode  = 'silent';
     if(!is_numeric($amount)){
        $status = 'RIMPLENET_RULES_ERROR_AMOUNT_NOT_NUMERIC_'.$amount;
     }
     elseif(strpos($rule, "rimplenet_rules_add_to_mature_wallet") !== false AND is_numeric($amount)){
        $tags['txn_ref'] = $tag_ref; 
        $this->add_user_mature_funds_to_wallet($user->ID,$amount, $wallet_id, $funds_info, $tags);
       $status = rimplenetRulesExecuted($rule,$user,$obj_id,$args);
     }
     else{
         
        $status = 'RIMPLENET_UNKNOWN_ERROR';
     }
     
     return $status;
       
 }



 public function rimplenet_rules_add_to_immature_wallet($rule,$user, $obj_id='', $args=''){
 
     $amount  = trim($args[0]);
     $wallet_id  = trim($args[1]);
     $funds_info  = trim($args[2]);
     if(empty($funds_info)){
         $funds_info = "Txn Returns - ".$obj_id;
     }
     //$notification_mode  = 'silent';
     if(!is_numeric($amount)){
        $status = 'RIMPLENET_RULES_ERROR_AMOUNT_NOT_NUMERIC_'.$amount;
     }
     elseif(strpos($rule, "rimplenet_rules_add_to_immature_wallet") !== false AND is_numeric($amount)){
         
        $this->add_user_immature_funds_to_wallet($user->ID,$amount, $wallet_id, $funds_info);
        $status = rimplenetRulesExecuted($rule,$user,$obj_id,$args);
     } 
     else{
         
        $status = 'RIMPLENET_UNKNOWN_ERROR';
     }
     
     return $status;
 }

 public function ShortcodeDesignWallet($atts){
    
      ob_start();

      include plugin_dir_path( dirname( __FILE__ ) ) . 'public/layouts/design-wallet-from-shortcode.php';
       
      $output = ob_get_clean();

      return $output;
  }
   
 public function UserToUserWalletTransferForm($atts) {
          

      ob_start();

      include plugin_dir_path( dirname( __FILE__ ) ) . 'public/layouts/design-transfer-wallet-to-wallet.php';
       
      $output = ob_get_clean();

      return $output;
    


 }


 public function withdraw_wallet_bal($user_id, $amount_to_withdraw, $wallet_id, $address_to, $note='Withdrawal'){
       
        
        
      $user_wdr_bal = $this->get_withdrawable_wallet_bal($user_id, $wallet_id);
      $user_non_wdr_bal = $this->get_nonwithdrawable_wallet_bal($user_id, $wallet_id);
      
      $walllets = $this->getWallets();
        $dec = $walllets[$wallet_id]['decimal'];
        $min_wdr_amount = $walllets[$wallet_id]['min_wdr_amount'];
        $max_wdr_amount = $walllets[$wallet_id]['max_wdr_amount'];
        $symbol = $walllets[$wallet_id]['symbol'];
        $name = $walllets[$wallet_id]['name'];
          
        $balance = $symbol.number_format($balance,$dec);
      
        if (empty($amount_to_withdraw) OR empty($wallet_id) ) {
        $wdr_info = 'One or more compulsory field is empty';
        }
       elseif ($amount_to_withdraw>$user_wdr_bal) {
        $wdr_info = 'Amount to withdraw - <strong>['.$symbol.number_format($amount_to_withdraw,$dec).']</strong> is larger than the amount in your mature wallet, input amount not more than the balance in your <strong>( '.$name.' mature wallet - ['.getRimplenetWalletFormattedAmount($user_wdr_bal,$wallet_id).'] ),</strong> the balance in your <strong>( '.$name.' immature wallet  - ['.getRimplenetWalletFormattedAmount($user_wdr_bal,$wallet_id).'] )</strong>  cannot be withdrawn until maturity';
        }
    
      elseif ($amount_to_withdraw<$min_wdr_amount) {
        $wdr_info = 'Requested amount ['.$amount_to_withdraw.'] is below minimum withdrawal amount, input amount not less than '.$min_wdr_amount;
      } 
      elseif ($amount_to_withdraw>$max_wdr_amount) {
        $wdr_info = 'Requested amount ['.$amount_to_withdraw.'] is above maximum withdrawal amount, input amount not more than '.$max_wdr_amount;
      }
      else{
        
            
        
         $amount_to_withdraw = $amount_to_withdraw * -1;
         $txn_wdr_id = $this->add_user_mature_funds_to_wallet($user_id,$amount_to_withdraw, $wallet_id, $note);
    
         if (is_int($txn_wdr_id)) {
           $modified_title = 'WITHDRAWAL ~ '.get_the_title( $txn_wdr_id);
           $args = 
              array(
              'ID'    =>  $txn_wdr_id,
              'post_title'   => $modified_title,
              'post_status'   =>  'pending',
               'meta_input' => array(
                'withdrawal_address_to'=>$address_to,
                'note'=>$note,
                )
              );
              
    
             wp_set_object_terms($txn_wdr_id, 'WITHDRAWAL', 'rimplenettransaction_type', true);
             wp_update_post($args);
     
             $wdr_info = $txn_wdr_id;
             
          }
      }
     wp_reset_postdata();
    return $wdr_info;

 }


 public function transfer_wallet_bal_external($transfer_from_user, $amount_to_transfer, $wallet_id, $transfer_to_destination, $note='EXTERNAL TRANSFER'){
    
       $user_transfer_bal = $this->get_withdrawable_wallet_bal($transfer_from_user, $wallet_id);
       $user_non_transfer_bal = $this->get_nonwithdrawable_wallet_bal($transfer_from_user, $wallet_id);
      
        $walllets = $this->getWallets();
        $dec = $walllets[$wallet_id]['decimal'];
        $symbol = $walllets[$wallet_id]['symbol'];
        $name = $walllets[$wallet_id]['name'];
        $balance = $symbol.number_format($balance,$dec);
      
      
     if(!is_user_logged_in()) {
        $transfer_info = 'Please Login to use this Feature';
      }
      elseif(empty($transfer_from_user) OR empty($amount_to_transfer) OR empty($wallet_id)) {
        $transfer_info = 'One or more compulsory field is empty';
      }
      elseif($amount_to_transfer>$user_transfer_bal) {
        $transfer_info = 'Amount to transfer - <strong>['.$symbol.number_format($amount_to_transfer,$dec).']</strong> is larger than the amount in your mature wallet, input amount not more than the balance in your <strong>( '.$name.' mature wallet - ['.$symbol.number_format($user_transfer_bal,$dec).'] ),</strong> the balance in your <strong>( '.$name.' immature wallet  - ['. $symbol.number_format($user_non_transfer_bal,$dec).'] )</strong>  cannot be transferred until maturity';
      }
      elseif($amount_to_transfer<$min_transfer_amt) {
        $transfer_info = 'Requested amount ['.$amount_to_transfer.'] is below minimum transfer amount, input amount not less than '.$min_transfer_amt;
      }
      else{ // all is good, make transfer
        
        //transfer funds to user
        
          $amount_to_transfer_to_user = apply_filters('rimplenet_amount_to_transfer', $amount_to_transfer, $wallet_id, $transfer_to_user_id);
          
          $amount_to_debit_in_transfer = $amount_to_transfer_to_user * -1;
          $txn_transfer_id = $this->add_user_mature_funds_to_wallet($transfer_from_user, $amount_to_debit_in_transfer, $wallet_id,$note);
         
         $transfer_info = $txn_transfer_id; 
         if (is_int($txn_transfer_id)) {
            
          $modified_title = 'TRANSFER ~ '.get_the_title( $txn_transfer_id);
           $args = 
              array(
              'ID'    =>  $txn_transfer_id, 
              'post_title'   => $modified_title,
              'post_status'   =>  'publish',
               'meta_input' => array(
                'transfer_address_from'=>$user_id,
                'note'=>$note,
                )
              );
             wp_set_object_terms($txn_transfer_id, 'TRANSFER', 'rimplenettransaction_type', true);
             wp_set_object_terms($txn_transfer_id, 'EXTERNAL TRANSFER', 'rimplenettransaction_type', true);
             wp_update_post($args);

         
         }
         
    }
        
     wp_reset_postdata();
     return $transfer_info;

 }

 public function transfer_wallet_bal($user_id, $amount_to_transfer, $wallet_id, $transfer_to_user, $note=''){
    
        $current_user = get_user_by('ID', $user_id);
      $current_user_id  = $current_user ->ID;
      
        $user_transfer_to = get_user_by('login', $transfer_to_user);
      $transfer_to_user_id  = $user_transfer_to->ID;
      
    $min_transfer_amt = 0;
      $user_transfer_bal = $this->get_withdrawable_wallet_bal($user_id, $wallet_id);
      $user_non_transfer_bal = $this->get_nonwithdrawable_wallet_bal($user_id, $wallet_id);
      
      $walllets = $this->getWallets();
        $dec = $walllets[$wallet_id]['decimal'];
        $symbol = $walllets[$wallet_id]['symbol'];
        $name = $walllets[$wallet_id]['name'];
        $balance = $symbol.number_format($balance,$dec);
      
      
        if (empty($user_id) OR empty($amount_to_transfer) OR empty($wallet_id)  OR empty($transfer_to_user) ) {
        $transfer_info = 'One or more compulsory field is empty';
        }
       elseif ($amount_to_transfer>$user_transfer_bal) {
        $transfer_info = 'Amount to transfer - <strong>['.$symbol.number_format($amount_to_transfer,$dec).']</strong> is larger than the amount in your mature wallet, input amount not more than the balance in your <strong>( '.$name.' mature wallet - ['.$symbol.number_format($user_transfer_bal,$dec).'] ),</strong> the balance in your <strong>( '.$name.' immature wallet  - ['. $symbol.number_format($user_non_transfer_bal,$dec).'] )</strong>  cannot be transferred until maturity';
       }
      elseif ($amount_to_transfer<$min_transfer_amt) {
        $transfer_info = 'Requested amount ['.$amount_to_transfer.'] is below minimum transfer amount, input amount not less than '.$min_transfer_amt;
      }
      elseif (!username_exists($user_transfer_to->user_login)) {
      $transfer_info = 'User with the username <b>['.$transfer_to_user.']</b> does not exist, please crosscheck the username';
    }
      else{ // all is good, make transfer
        
        //transfer funds to user
        
          $amount_to_transfer_to_user = apply_filters('rimplenet_amount_to_transfer', $amount_to_transfer, $wallet_id, $transfer_to_user_id);
          $txn_transfer_id1 = $this->add_user_mature_funds_to_wallet($transfer_to_user_id,$amount_to_transfer_to_user, $wallet_id,$note);
         
         $transfer_info = $txn_transfer_id1; 
         if (is_int($txn_transfer_id1)) {
            
          $modified_title = 'TRANSFER ~ '.get_the_title( $txn_transfer_id1);
           $args = 
              array(
              'ID'    =>  $txn_transfer_id1, 
              'post_title'   => $modified_title,
              'post_status'   =>  'publish',
               'meta_input' => array(
                'transfer_address_from'=>$user_id,
                'note'=>__("TRANSFER from $current_user->user_login $note"),
                )
              );
             wp_set_object_terms($txn_transfer_id1, 'TRANSFER', 'rimplenettransaction_type', true);
             wp_set_object_terms($txn_transfer_id1, 'INTERNAL TRANSFER', 'rimplenettransaction_type', true);
             wp_update_post($args);

          //debit from user making the transfer
         
          $amount_to_debit_in_transfer = $amount_to_transfer * -1;
          $txn_transfer_id2 = $this->add_user_mature_funds_to_wallet($user_id, $amount_to_debit_in_transfer, $wallet_id, $note);
         }
         
         
         if (is_int($txn_transfer_id2)) {
            

          $modified_title = 'TRANSFER ~ '.get_the_title($txn_transfer_id2);
           $args = 
              array(
              'ID'    =>  $txn_transfer_id2, 
              'post_title'   => $modified_title,
              'post_status'   =>  'publish',
               'meta_input' => array(
                'transfer_address_to'=>$transfer_to_user_id,
                'note'=>__("TRANSFER to $user_transfer_to->user_login $note"),
                )
              );
             wp_set_object_terms($txn_transfer_id2, 'TRANSFER', 'rimplenettransaction_type', true);
             wp_set_object_terms($txn_transfer_id2, 'INTERNAL TRANSFER', 'rimplenettransaction_type', true);
             wp_update_post($args);
             
             $transfer_info = $txn_transfer_id2;
          }
        }
        
    update_post_meta($txn_transfer_id1, "alt_transfer_id",$txn_transfer_id2);
    update_post_meta($txn_transfer_id2, "alt_transfer_id",$txn_transfer_id1);


     wp_reset_postdata();
     return $transfer_info;

 }


 public function add_user_immature_funds_to_wallet($user_id,$amount_to_add,$wallet_id,$note='',$tags=[]){

   $key = 'user_nonwithdrawable_bal_'.strtolower($wallet_id);
   $user_balance = get_user_meta($user_id, $key, true);
   

   if (!is_numeric($user_balance) and !is_int($user_balance)){
    $user_balance = 0;
   }
   
   if($amount_to_add===0){
       return ;// don't transact 0
   }
    $bal_before = $user_balance;
    $user_balance_total = $this->get_total_wallet_bal($user_id,$wallet_id);

    $new_balance  = $user_balance + $amount_to_add; 
    $new_balance  = $new_balance;
    
    do_action("before_add_user_immature_funds_to_wallet",$user_id,$amount_to_add,$wallet_id,$note,$tags); 
    
    update_user_meta($user_id, $key, $new_balance);
    

   if ($amount_to_add>0) {
     $tnx_type = 'CREDIT';
   }
   else{
     $tnx_type = 'DEBIT';
     $amount_to_add = $amount_to_add * -1;
    }

    $txn_add_bal_id = $this->record_Txn($user_id,$amount_to_add, $wallet_id, $tnx_type ,'publish');

    if (!empty($note)) {
        add_post_meta($txn_add_bal_id, 'note', $note);
      }
    update_post_meta($txn_add_bal_id, 'balance_before', $bal_before);
    update_post_meta($txn_add_bal_id, 'balance_after', $new_balance);
    
    update_post_meta($txn_add_bal_id, 'total_balance_before', $user_balance_total);
    update_post_meta($txn_add_bal_id, 'total_balance_after', $this->get_total_wallet_bal($user_id,$wallet_id));
    
    update_post_meta($txn_add_bal_id, 'funds_type', $key);

    do_action("after_add_user_immature_funds_to_wallet",$txn_add_bal_id,$user_id,$amount_to_add,$wallet_id,$note,$tags,$tnx_type); 
    
  return $txn_add_bal_id;
}


function rimplenet_fund_user_mature_wallet($request_id,$user_id,$amount_to_add,$wallet_id, $note='',$tags=[],$extra_data=''){
    global $wpdb; 
    
    $txn_request_id = $user_id."_".$request_id;
    $recent_txn_transient_key = "recent_txn_".$txn_request_id;
    
    if($GLOBALS[$recent_txn_transient_key]=="executing"){return ;}
    if(get_transient($recent_txn_transient_key)){return ;}
    
    $GLOBALS[$recent_txn_transient_key] = 'executing';
    set_transient( $recent_txn_transient_key, 'executing',60);
    
    $inputed_data = array(
       "request_id"=>$request_id,"user_id"=>$user_id, "amount_to_add"=>$amount_to_add, "wallet_id"=>$wallet_id);
    
    $empty_input_array = array(); 
    //Loop & Find out empty inputs
    foreach($inputed_data as $input_key=>$single_data){ 
        if(empty($single_data)){
          $empty_input_array[$input_key]  = "field_required" ;
        }
    } 
    
    //RUN CHECKS
    $result = array();
    $additonal_result = array();
    
    $row_result = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='txn_request_id' AND meta_value='$txn_request_id'" );
    if(!empty($row_result)){//it means txn has already exist
         
          $funds_id = $row_result->post_id;
          $status = "transaction_already_executed";
          $response_message = "Transaction Already Executed";
          $data = array("txn_id"=>$funds_id);
    }
    elseif(!empty($empty_input_array)){
          //if atleast one required input is empty
          $status = "one_or_more_input_required";
          $response_message = "One or more input field is required";
          $data = array("error"=>$empty_input_array);
          
     }
    elseif($amount_to_add==0){
          $status = "amount_is_zero";
          $response_message = "Amount should not be Zero";
          $data = array("error"=>"Amount is zero");
    }
    else{// ALL GOOD, PROCEED WITH OPERATION
          $key = 'user_withdrawable_bal_'.strtolower($wallet_id);
          $user_balance = get_user_meta($user_id, $key, true);
          if (!is_numeric($user_balance) and !is_int($user_balance)){
             $user_balance = 0;
          }
          
          $bal_before = $user_balance;
          $user_balance_total = $this->get_total_wallet_bal($user_id,$wallet_id);
        
          $new_balance  = $user_balance + $amount_to_add; 
          $new_balance  = $new_balance;
            
          $update_bal = update_user_meta($user_id, $key, $new_balance);
        if($update_bal){//balance successfully updated
           if($amount_to_add>0) {
             $tnx_type = "CREDIT";
           }
           else{
             $tnx_type = "DEBIT";
             $amount_to_add = $amount_to_add * -1;
           }
        
           $txn_add_bal_id = $this->record_Txn($user_id, $amount_to_add, $wallet_id, $tnx_type, 'publish');
        
            if(!empty($note)) {
                add_post_meta($txn_add_bal_id, 'note', $note);
            }
            add_post_meta($txn_add_bal_id, 'request_id', $request_id);
            add_post_meta($txn_add_bal_id, 'txn_request_id', $txn_request_id);
            update_post_meta($txn_add_bal_id, 'balance_before', $bal_before);
            update_post_meta($txn_add_bal_id, 'balance_after', $new_balance);
            
            update_post_meta($txn_add_bal_id, 'total_balance_before', $user_balance_total);
            update_post_meta($txn_add_bal_id, 'total_balance_after', $this->get_total_wallet_bal($user_id,$wallet_id));
            update_post_meta($txn_add_bal_id, 'funds_type', $key);
        }
        else{
          $status = "unknown_error";
          $response_message = "Unknown Error";
          $data = array(); 
        }
    }
     
    if($txn_add_bal_id>0){
      $result = $txn_add_bal_id;
    }else{
      $result = array("status"=>$status,
                      "message"=>$response_message,
                      "data"=>$data); 
      $result = json_encode($result);
    }
    
    return $result;
 }

 function add_user_mature_funds_to_wallet($user_id,$amount_to_add,$wallet_id, $note='',$tags=[]){
    
    if(!empty($tags['txn_ref'])){
        $external_txn_id = $tags['txn_ref'];
        $ext_txn_id = rimplenet_txn_exist($user_id,$external_txn_id);
        if($ext_txn_id>1){
            return $ext_txn_id;
        }
        $note .= " ~ #$external_txn_id";
    }

   if($amount_to_add===0){
       return ;// don't transact 0
   }
   
   $key = 'user_withdrawable_bal_'.strtolower($wallet_id);
   $user_balance = get_user_meta($user_id, $key, true);

    if (!is_numeric($user_balance) and !is_int($user_balance)){
     $user_balance = 0;
    }
   
    $bal_before = $user_balance;
    $user_balance_total = $this->get_total_wallet_bal($user_id,$wallet_id);

    $new_balance  = $user_balance + $amount_to_add; 
    $new_balance  = $new_balance;
    
    
    do_action("before_add_user_mature_funds_to_wallet",$user_id,$amount_to_add,$wallet_id,$note,$tags); 
   
    update_user_meta($user_id, $key, $new_balance);

    if ($amount_to_add>0) {
     $tnx_type = 'CREDIT';
    }
   else{
     $tnx_type = 'DEBIT';
     $amount_to_add = $amount_to_add * -1;
    }

    $txn_add_bal_id = $this->record_Txn($user_id,$amount_to_add, $wallet_id, $tnx_type ,'publish');

    if (!empty($note)) {
        add_post_meta($txn_add_bal_id, 'note', $note);
      }
    update_post_meta($txn_add_bal_id, 'balance_before', $bal_before);
    update_post_meta($txn_add_bal_id, 'balance_after', $new_balance);
    
    update_post_meta($txn_add_bal_id, 'total_balance_before', $user_balance_total);
    update_post_meta($txn_add_bal_id, 'total_balance_after', $this->get_total_wallet_bal($user_id,$wallet_id));
    
    update_post_meta($txn_add_bal_id, 'funds_type', $key);
    if(!empty($tags['txn_ref'])){
      update_post_meta($txn_add_bal_id, 'external_txn_id', $external_txn_id);
    }

    do_action("after_add_user_mature_funds_to_wallet",$txn_add_bal_id,$user_id,$amount_to_add,$wallet_id,$note,$tags,$tnx_type);
    
  return $txn_add_bal_id;

}


function get_nonwithdrawable_wallet_bal($user_id,$wallet_id){

  $key = 'user_nonwithdrawable_bal_'.strtolower($wallet_id);
 
  $user_balance = get_user_meta($user_id, $key, true);
  if (empty($user_balance)) {
      $user_balance = 0;
  }
  
  //$balance = number_format($user_balance,2);
  $balance = $user_balance;

  return $balance;
}

function get_withdrawable_wallet_bal($user_id,$wallet_id){

  $key = 'user_withdrawable_bal_'.strtolower($wallet_id);
 
  $user_balance = get_user_meta($user_id, $key, true);
  if (empty($user_balance)) {
      $user_balance = 0;
  }
  
  //$balance = number_format($user_balance,2);
  $balance = $user_balance;

  return $balance;

}

function get_total_wallet_bal($user_id,$wallet_id){

  
  $balance = $this->get_withdrawable_wallet_bal($user_id, $wallet_id) + $this-> get_nonwithdrawable_wallet_bal($user_id, $wallet_id);

  $walllets = $this->getWallets();
  $dec = $walllets[$wallet_id]['decimal'];
  
  //$balance = number_format($balance,$dec);

  return $balance;

}

function get_total_wallet_bal_disp_formatted($user_id,$wallet_id){

  
  $balance = $this->get_withdrawable_wallet_bal($user_id, $wallet_id) + $this-> get_nonwithdrawable_wallet_bal($user_id, $wallet_id);

  $walllets = $this->getWallets();
  $dec = $walllets[$wallet_id]['decimal'];
  $symbol = $walllets[$wallet_id]['symbol'];
  $symbol_position = $all_wallets[$wallet_id]['symbol_position'];
    if($symbol_position=='right'){
        $balance = number_format($balance,$dec)." ".$symbol;
    }
    else{
        $balance = $symbol.number_format($balance,$dec);
    }
  

  return $balance;

}


function record_Txn($user_id, $amount, $wallet_id, $tnx_type ,$status='pending'){

        $user_info = get_user_by('ID', $user_id);
        $walllets = $this->getWallets();
        $decimal = $walllets[$wallet_id]['decimal'];
        $amount_formatted = number_format($amount,$decimal);
        $wallet_symbol = $walllets[$wallet_id]['symbol'];
        $wallet_name = $walllets[$wallet_id]['name'];

        $post_title = 'TRANSACTION by '.$user_info->user_login .', Type: '.$tnx_type.', Wallet Info: '.$wallet_symbol.''.$amount_formatted.' '.$wallet_name.'  on '.date("l jS \of F Y @ h:i:s A");

         $post_content = 'Amount:'.$amount;

         $new_txn_args = array(
              'post_author'=> $user_info->ID,
              'post_type' => 'rimplenettransaction',
              'post_title'    => wp_strip_all_tags($post_title),
              'post_content'  => $post_content,
              'post_status'   => $status,
              'meta_input' => array(
                'amount'=>$amount,
                'currency'=>strtolower($wallet_id),
                'txn_type'=>$tnx_type
                ),
              );
         
               
       $new_txn = wp_insert_post($new_txn_args);


       if ($tnx_type=='BUY' or $tnx_type=='SELL') {
           $amount_usd = $amount;
           update_post_meta($new_txn, 'amount_usd', $amount_usd);
           
           $amount_coin = $amount;
           update_post_meta($new_txn, 'amount_coin', $amount_coin);

          $rate_1usd_to_coin = get_option('rate_1btc_to_usd_rate',9000);
          $amount_btc = $amount_usd/$rate_1usd_to_coin;
          update_post_meta($new_txn, 'amount_btc', $amount_btc);
         
       }

       if (is_int($new_txn)) {
         wp_set_object_terms($new_txn, $tnx_type, 'rimplenettransaction_type', true);

         return $new_txn;
       }

       wp_reset_postdata();


}



function add_rimplenet_currency( $currencies ) {
     $include_only = array('default','db');
     $all_wallets = $this->getWallets($include_only);
     
     
     foreach($all_wallets as $wallet_id => $wallet){
         
         $include_in_woocommerce_currency_list = $wallet['include_in_woocommerce_currency_list'];
         
        if($include_in_woocommerce_currency_list =='yes'){
         $rimplenet_wallet_id = 'rimplenet_'.$wallet_id;
         $rimplenet_wallet_name = $wallet['name'];
         $rimplenet_wallet_name = $wallet['name'];
         $currencies[$rimplenet_wallet_id] = __( $rimplenet_wallet_name, 'woocommerce' ); 
        }
     }
     
    //$currencies['ABC'] = __( 'ABC Currency name', 'woocommerce' );
    
     return $currencies;
}


function add_rimplenet_currency_symbol( $currency_symbol, $currency ) {
    
     switch( $currency ) {
          case 'ABC': $currency_symbol = '$'; break;
     }
     
     return $currency_symbol;
}

function update_rimplenet_woocommerce_wallet_and_currency() {
    
      $woo_cur_symbol = get_woocommerce_currency_symbol();
      $woo_cur = get_woocommerce_currency();
    
      $all_woo_cur_array = get_woocommerce_currencies();
      $woo_cur_name = $all_woo_cur_array[$woo_cur];
      $woo_cur_name_disp = $woo_cur_name.' ('.$woo_cur.')';
    
      $rimplenet_woocommerce_wallet_and_currency = array( 
        "id" => "woocommerce_base_cur",  
        "name" => $woo_cur_name_disp,  
        "symbol" => $woo_cur_symbol, 
        "symbol_position" => "left",  
        "value_1_to_base_cur" => 0.01, 
        "value_1_to_usd" => 1, 
        "value_1_to_btc" => 0.01, 
        "decimal" => 2, 
        "min_wdr_amount" => 0, 
        "max_wdr_amount" => INF, 
        "include_in_withdrawal_form" => "yes",
        "include_in_woocommerce_currency_list" => "no",
        "action" => array( 
          "deposit" => "yes",  
          "withdraw" => "yes", 
      ) 
     ); 
    
    update_option( 'rimplenet_woocommerce_wallet_and_currency', $rimplenet_woocommerce_wallet_and_currency );

    
}


function getWallets($include_only='' ){ //$exclude can be default, woocommerce, or db
  if(empty($include_only)){$include_only = array('default','woocommerce','db');}

      $activated_wallets = array();
      $wallet_type = array('mature','immature'); 
  
      
    if(in_array('default', $include_only)){
      
    $activated_wallets['rimplenetcoin'] = array( 
      "id" => "rimplenetcoin",  
      "name" => "RIMPLENET Coin",
      "symbol" => "RMPNCOIN",  
      "symbol_position" => "right",  
      "value_1_to_base_cur" => 0.01, 
      "value_1_to_usd" => 1, 
      "value_1_to_btc" => 0.01, 
      "decimal" => 0, 
      "min_wdr_amount" => 0, 
      "max_wdr_amount" => INF, 
      "include_in_withdrawal_form" => "yes",
      "include_in_woocommerce_currency_list" => "no",
      "action" => array( 
          "deposit" => "yes",  
          "withdraw" => "yes", 
      ) 
    ); 
  
 }
 
 if(in_array('woocommerce', $include_only) AND in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
          //For Woocommerce
          $activated_wallets['woocommerce_base_cur']  = apply_filters( 'rimplenet_filter_woocommerce_base_cur', get_option('rimplenet_woocommerce_wallet_and_currency') );
      }
  
     /*
     $activated_wallets['btc'] = array( 
          "id" => "btc",  
          "name" => "Bitcoin", 
          "symbol" => "BTC", 
          "value_1_to_base_cur" => 0.01, 
          "value_1_to_usd" => 0.01, 
          "value_1_to_btc" => 0.01, 
          "decimal" => 8, 
          "include_in_woocommerce_currency_list" => 'no',
          "action" => array( 
              "deposit" => "yes",  
              "withdraw" => "yes", 
          ) 
      ); 
      
      */

 
     
  if(in_array('db', $include_only)){
   //Add Wallets saved in database
   $WALLET_CAT_NAME = 'RIMPLENET WALLETS';
   $txn_loop = new WP_Query(
           array(  'post_type' => 'rimplenettransaction', 
                   'post_status' => 'publish',
                   'posts_per_page' => -1,
                   'tax_query' => array(
                     array(
                        'taxonomy' => 'rimplenettransaction_type',
                        'field'    => 'name',
                        'terms'    => $WALLET_CAT_NAME,
                ),
             ),)
         );
   if( $txn_loop->have_posts() ){
    while( $txn_loop->have_posts() ){
        $txn_loop->the_post();
        $txn_id = get_the_ID(); 
        $status = get_post_status();
        $wallet_name = get_the_title();
        $wallet_desc  = get_the_content();
        
        $wallet_decimal = get_post_meta($txn_id, 'rimplenet_wallet_decimal', true);
        $min_wdr_amount = get_post_meta($txn_id, 'rimplenet_min_withdrawal_amount', true);
        if(empty($min_wdr_amount)){$min_wdr_amount = 0;}
        
        $max_wdr_amount = get_post_meta($txn_id, 'rimplenet_max_withdrawal_amount', true);
        if(empty($max_wdr_amount)){$max_wdr_amount = INF;}
        
        $wallet_symbol = get_post_meta($txn_id, 'rimplenet_wallet_symbol', true);
        $wallet_symbol_position = get_post_meta($txn_id, 'rimplenet_wallet_symbol_position', true);
        $wallet_id = get_post_meta($txn_id, 'rimplenet_wallet_id', true);
        $include_in_withdrawal_form = get_post_meta($txn_id, 'include_in_withdrawal_form', true);
        $include_in_woocommerce_currency_list = get_post_meta($txn_id, 'include_in_woocommerce_currency_list', true);
        
        $activated_wallets[$wallet_id] = array( 
              "id" => $wallet_id,  
              "name" => $wallet_name,
              "symbol" => $wallet_symbol, 
              "symbol_position" => $wallet_symbol_position,  
              "value_1_to_base_cur" => 0.01, 
              "value_1_to_usd" => 1, 
              "value_1_to_btc" => 0.01, 
              "decimal" => $wallet_decimal, 
              "min_wdr_amount" => $min_wdr_amount, 
              "max_wdr_amount" => $max_wdr_amount, 
              "include_in_withdrawal_form" => "yes",
              "include_in_woocommerce_currency_list" => $include_in_woocommerce_currency_list,
              "action" => array( 
                  "deposit" => "yes",  
                  "withdraw" => "yes",
                  
              ) 
          ); 
        }
        
    }
    
    wp_reset_postdata();
  }


 return $activated_wallets;
  
  
}



 }



$Rimplenet_Wallets = new Rimplenet_Wallets();

function RimplenetGetWallets($include_only=''){
    $wallet_obj = new Rimplenet_Wallets();
    return $wallet_obj->getWallets($include_only);
}
 
function ConvertRimplenetAmount($amount,$wallet_from,$wallet_to){
    
    $base_wallet = get_option("rimplenet_website_base_wallet","rimplenetcoin");
    
    $key_from_wallet_to_base_wallet = "rate_1_".$wallet_from."_to_website_base_wallet";
    $value_from_wallet_to_base_wallet = get_option($key_from_wallet_to_base_wallet,1);
    
    $key_to_wallet_to_base_wallet = "rate_1_".$wallet_to."_to_website_base_wallet";
    $value_to_wallet_to_base_wallet = get_option($key_to_wallet_to_base_wallet,1);
    
    $amount_to_base_wallet = $amount * $value_from_wallet_to_base_wallet; // convert the amt (in wallet_from) to website base cur value
    $amount_to_wallet_to = $amount_to_base_wallet / $value_to_wallet_to_base_wallet; // convert from website base cur value TO provided WALLET_TO
    
    $amt_converted = $amount_to_wallet_to;
    
    return $amt_converted;
    
}


function getRimplenetWalletFormattedAmount($amount,$wallet_id,$include_data=''){
    
        if(empty($include_data)){$include_data = array();}
        else{ $include_data = explode(",",$include_data);}
        
        $wallet_obj = new Rimplenet_Wallets();
        $all_wallets = $wallet_obj->getWallets();

        $dec = $all_wallets[$wallet_id]['decimal'];
        $symbol = $all_wallets[$wallet_id]['symbol'];
        $symbol_position = $all_wallets[$wallet_id]['symbol_position'];
        
        if($symbol_position=='right'){
           $disp_info = number_format($amount,$dec)." ".$symbol;;
        }
        else{
           $disp_info = $symbol.number_format($amount,$dec);
        }
          
        if(in_array('wallet_name', $include_data)){
            $disp_info = $all_wallets[$wallet_id]['name']." - ".$disp_info;
        }
          
    return $disp_info;
}


 //NEW METHOD for checking Txn Exist
 function rimplenet_txn_exists($user_id,$txn_id){
        global $wpdb; 
      
                  $txn_request_id = $user_id."_".$txn_id;
                  $row_result = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='txn_request_id' AND meta_value='$txn_request_id'" );
                  if(!empty($row_result)){//it means txn has already exist
         
                      $funds_id = $row_result->post_id;
                      $status = "transaction_already_executed";
                      $response_message = "Transaction Already Executed";
                      $data = array("txn_id"=>$funds_id);
                   }
                   else{
                       
                      $funds_id = $row_result->post_id;
                      $status = "transaction_does_not_exist";
                      $response_message = "Transaction does not exist";
                      $data = array("info"=>"transaction_does_not_exist");
                   }
    
     
                if(!empty($status)){
                  $result = array("status"=>$status,
                                  "message"=>$response_message,
                                  "data"=>$data); 
                }else{
                   $result = array("status"=>"unknown_error",
                          "message"=>"Unknown Error",
                          "data"=>array(
                                      "error"=>"unknown_error"
                                        )
                        ); 
                }
                
                
        return $result;
 }

 
 function rimplenet_txn_exist($user_id,$external_txn_id){
        
                   $txn_loop = new WP_Query(
                             array(  
                               'post_type' => 'rimplenettransaction', 
                               'post_status' => 'any',
                               'author' => $user_id ,
                               'posts_per_page' => 1,
                               'tax_query' => array(
                                   'relation' => 'OR',
                                   array(
                                    'taxonomy' => 'rimplenettransaction_type',
                                    'field'    => 'name',
                                    'terms'    => array( 'CREDIT' ),
                                   ),
                                  array(
                                    'taxonomy' => 'rimplenettransaction_type',
                                    'field'    => 'name',
                                    'terms'    => array( 'DEBIT' ),
                                      ),
                                  ),
                                )
                              );
                              
                       if( $txn_loop->have_posts() ){
                          while( $txn_loop->have_posts() ){
                        
                                $txn_loop->the_post();
                                $txn_id = get_the_ID(); 
                                $status = get_post_status(); 
                                
                                if(get_post_meta($txn_id, "external_txn_id",true)==$external_txn_id){
                                    return $txn_id;
                                }
                           
                          }
             
                       }
    
        
    }

function rimplenet_user_wallet_profile_fields( $user ) { ?>
    <h3><?php _e("RIMPLENET Wallet Balance", "rimplenet"); ?></h3>

    <table class="form-table">
    <?php
     $all_wallets = RimplenetGetWallets();
     
     foreach ($all_wallets as $wallet_id=> $single_wallet) {
      $key_nonwithdrawable = 'user_nonwithdrawable_bal_'.$single_wallet['id'];
      $name_nonwithdrawable = $single_wallet['name'].' ~ ('.$single_wallet['symbol'].') - NON WITHDRAWABLE';

      $key_withdrawable = 'user_withdrawable_bal_'.$single_wallet['id'];
      $name_withdrawable = $single_wallet['name'].' ~ ('.$single_wallet['symbol'].') - WITHDRAWABLE';

      ?>
      <tr><td colspan="3"><hr></td></tr>
       <tr>
        <th><label for="<?php echo $key_nonwithdrawable; ?>"><?php _e($name_nonwithdrawable); ?></label></th>
        <td>
            <input type="text" name="<?php echo $key_nonwithdrawable; ?>" id="<?php echo $key_nonwithdrawable; ?>" value="<?php echo esc_attr( get_the_author_meta( $key_nonwithdrawable, $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e($name_nonwithdrawable); ?></span>
        </td>
       </tr>

       <tr>
        <th><label for="<?php echo $key_withdrawable; ?>"><?php _e($name_withdrawable); ?></label></th>
        <td>
            <input type="text" name="<?php echo $key_withdrawable; ?>" id="<?php echo $key_withdrawable; ?>" value="<?php echo esc_attr( get_the_author_meta( $key_withdrawable, $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e($name_withdrawable); ?></span>
        </td>
       </tr>


    <?php
     }
    ?>
    <tr><td colspan="3"><hr></td></tr>
   </table>
<?php }


function save_rimplenet_user_wallet_profile_fields( $user_id ) {
    
    if ( !current_user_can( 'edit_user', $user_id ) or get_current_user_id()!=74 ) { 
        return false; 
    }
   
   $all_wallets = RimplenetGetWallets();
     
     foreach ($all_wallets as $wallet_id=> $single_wallet) {
      $key_non_withdrawable = 'user_nonwithdrawable_bal_'.$single_wallet['id'];

      $key_withdrawable = 'user_withdrawable_bal_'.$single_wallet['id'];

      if (isset($_POST[$key_non_withdrawable])) {
        update_user_meta( $user_id, $key_non_withdrawable , $_POST[$key_non_withdrawable] ); 
      }

      if (isset($_POST[$key_withdrawable])) {
        update_user_meta( $user_id, $key_withdrawable , $_POST[$key_withdrawable] ); 
      }


    }

  


  }


function rimplenet_form_field($key, $args, $value = null ) {
    $defaults = array(
        'type'              => 'text',
        'label'             => '',
        'description'       => '',
        'placeholder'       => '',
        'maxlength'         => false,
        'required'          => false,
        'autocomplete'      => false,
        'id'                => $key,
        'class'             => array(),
        'label_class'       => array(),
        'input_class'       => array(),
        'return'            => false,
        'options'           => array(),
        'custom_attributes' => array(),
        'validate'          => array(),
        'default'           => '',
        'autofocus'         => '',
        'priority'          => '',
    );

    $args = wp_parse_args( $args, $defaults );
    $args = apply_filters( 'rimplenet_form_field_args', $args, $key, $value );

    if ( $args['required'] ) {
        $args['class'][] = 'validate-required';
        $required        = '&nbsp;<abbr class="required" title="' . esc_attr__( 'required', 'rimplenet' ) . '">*</abbr>';
    } else {
        $required = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'rimplenet' ) . ')</span>';
    }

    if ( is_string( $args['label_class'] ) ) {
        $args['label_class'] = array( $args['label_class'] );
    }

    if ( is_null( $value ) ) {
        $value = $args['default'];
    }

    // Custom attribute handling.
    $custom_attributes         = array();
    $args['custom_attributes'] = array_filter( (array) $args['custom_attributes'], 'strlen' );

    if ( $args['maxlength'] ) {
        $args['custom_attributes']['maxlength'] = absint( $args['maxlength'] );
    }

    if ( ! empty( $args['autocomplete'] ) ) {
        $args['custom_attributes']['autocomplete'] = $args['autocomplete'];
    }

    if ( true === $args['autofocus'] ) {
        $args['custom_attributes']['autofocus'] = 'autofocus';
    }

    if ( $args['description'] ) {
        $args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
    }

    if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
        foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
            $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
        }
    }

    if ( ! empty( $args['validate'] ) ) {
        foreach ( $args['validate'] as $validate ) {
            $args['class'][] = 'validate-' . $validate;
        }
    }

    $field           = '';
    $label_id        = $args['id'];
    $sort            = $args['priority'] ? $args['priority'] : '';
    $field_container = '<p class="form-row %1$s" id="%2$s" data-priority="' . esc_attr( $sort ) . '">%3$s</p>';

    switch ( $args['type'] ) {
        
        case 'textarea':
            $field .= '<textarea name="' . esc_attr( $key ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '' ) . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $value ) . '</textarea>';

            break;
        case 'checkbox':
            $field = '<label class="checkbox ' . implode( ' ', $args['label_class'] ) . '" ' . implode( ' ', $custom_attributes ) . '>
                    <input type="' . esc_attr( $args['type'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="1" ' . checked( $value, 1, false ) . ' /> ' . $args['label'] . $required . '</label>';

            break;
        case 'text':
        case 'password':
        case 'datetime':
        case 'datetime-local':
        case 'date':
        case 'month':
        case 'time':
        case 'week':
        case 'number':
        case 'email':
        case 'url':
        case 'tel':
            $field .= '<input type="' . esc_attr( $args['type'] ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '"  value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

            break;
        case 'select':
            $field   = '';
            $options = '';

            if ( ! empty( $args['options'] ) ) {
                foreach ( $args['options'] as $option_key => $option_text ) {
                    if ( '' === $option_key ) {
                        // If we have a blank option, select2 needs a placeholder.
                        if ( empty( $args['placeholder'] ) ) {
                            $args['placeholder'] = $option_text ? $option_text : __( 'Choose an option', 'rimplenet' );
                        }
                        $custom_attributes[] = 'data-allow_clear="true"';
                    }
                    $options .= '<option value="' . esc_attr( $option_key ) . '" ' . selected( $value, $option_key, false ) . '>' . esc_attr( $option_text ) . '</option>';
                }

                $field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '">
                        ' . $options . '
                    </select>';
            }

            break;
        case 'radio':
            $label_id .= '_' . current( array_keys( $args['options'] ) );

            if ( ! empty( $args['options'] ) ) {
                foreach ( $args['options'] as $option_key => $option_text ) {
                    $field .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ) . '" ' . implode( ' ', $custom_attributes ) . ' id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false ) . ' />';
                    $field .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio ' . implode( ' ', $args['label_class'] ) . '">' . $option_text . '</label>';
                }
            }

            break;
    }

    if ( ! empty( $field ) ) {
        $field_html = '';

        if ( $args['label'] && 'checkbox' !== $args['type'] ) {
            $field_html .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) . '">' . $args['label'] . $required . '</label>';
        }

        $field_html .= '<span class="rimplenet-input-wrapper">' . $field;

        if ( $args['description'] ) {
            $field_html .= '<span class="description" id="' . esc_attr( $args['id'] ) . '-description" aria-hidden="true">' . wp_kses_post( $args['description'] ) . '</span>';
        }

        $field_html .= '</span>';

        $container_class = esc_attr( implode( ' ', $args['class'] ) );
        $container_id    = esc_attr( $args['id'] ) . '_field';
        $field           = sprintf( $field_container, $container_class, $container_id, $field_html );
    }

    /**
     * Filter by type.
     */
    $field = apply_filters( 'rimplenet_form_field_'. $args['type'], $field, $key, $args, $value );

    /**
     * General filter on form fields.
     *
     * @since 3.4.0
     */
    $field = apply_filters( 'rimplenet_form_field', $field, $key, $args, $value );

    if ( $args['return'] ) {
        return $field;
    } else {
        echo $field; // WPCS: XSS ok.
    }
 }

?>