<?php
//Included from shortcode in includes/class-wallets.php
//use case [rimplenet-wallet action="view_balance" user_id="1"]
 global $current_user;
 wp_get_current_user();
 ?>
 <?php
   if(!is_user_logged_in()) {
?>
  <center>
   <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong> ERROR: </strong> Please Login or Register to Procced
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
   </div>
  </center>
<?php
     return ;// END PROCESS IF NOT LOGGED IN
   }
?>
<?php
$wallet_obj = new Rimplenet_Wallets();
$all_wallets = $wallet_obj->getWallets();
$atts1 = $atts;
$atts = shortcode_atts( array(

    'action' => 'empty',
    'user_id' => $current_user->ID,
    'wallet_id' => 'woocommerce_base_cur',
    'title' => 'WITHDRAWAL',
    'button_text' => 'WITHDRAW',
    'wdr_amt_text_label' => 'Amount to Withdraw',
    'wdr_amt_text_placeholder' => 'e.g 1000 , no space, comma, currency sign or special character',
    'wdr_dest_text_label' => 'Withdrawal Destination',
    'wdr_dest_text_placeholder' => 'Insert your Withdrawal Destination or bank details',
    'wdr_note_text_label' => 'Withdrawal Note (optional)',
    'wdr_note_text_placeholder' => 'Leave withdrawal note here',
    'min' => '0',
    'max' => INF,
    'redirect_url' => '',
    'posts_per_page' => get_option( 'posts_per_page' ),
), $atts );


$action = $atts['action'];
$user_id = $atts['user_id'];
$wallet_id = $atts['wallet_id'];
$title = $atts['title'];
$button_text = $atts['button_text'];
$wdr_amt_text_label = $atts['wdr_amt_text_label'];
$wdr_amt_text_placeholder = $atts['wdr_amt_text_placeholder'];
$wdr_dest_text_label = $atts['wdr_dest_text_label'];
$wdr_dest_text_placeholder = $atts['wdr_dest_text_placeholder'];
$wdr_note_text_label = $atts['wdr_note_text_label'];
$wdr_note_text_placeholder = $atts['wdr_note_text_placeholder'];
$min = $atts['min'];
$max = $atts['max'];
$posts_per_page = $atts['posts_per_page'];
$redirect_url = $atts['redirect_url'];

//Set Min Withdrawal Amount
if(!empty($min)){
    $min_withdrawal_amt = array();
    
    $min = explode(",",$min);
    foreach($min as $wallet_min_settings){
        $wallet_min_settings_exploded = explode(":",$wallet_min_settings);
        $wal_id = $wallet_min_settings_exploded[0];
        $min_amount = $wallet_min_settings_exploded[1];
        $min_withdrawal_amt[$wal_id] = floatval($min_amount);
    }
 }
 

//Set Max Withdrawal Amount
if(!empty($max)){
    $max_withdrawal_amt = array();
    
    $max = explode(",",$max);
    foreach($max as $wallet_max_settings){
        $wallet_max_settings_exploded = explode(":",$wallet_max_settings);
        $wal_id = $wallet_max_settings_exploded[0];
        $max_amount = $wallet_max_settings_exploded[1];
        $max_withdrawal_amt[$wal_id] = floatval($max_amount);
    }
 }
 
//Set Redirect Url for different wallet
if(!empty($redirect_url)){
    
    /*$red_url = filter_var($redirect_url, FILTER_SANITIZE_URL);
    if (filter_var($red_url, FILTER_VALIDATE_URL)){
    
    }
    */
    
    $redirect_url_data = array();
    
    $redirect_url = explode(",", $redirect_url);
    foreach($redirect_url as $redirect_url_settings){
        $redirect_url_settings_exploded = explode("~",$redirect_url_settings);
        $wal_id = $redirect_url_settings_exploded[0];
        $redirect_url = $redirect_url_settings_exploded[1];
        $redirect_url_data[$wal_id] = $redirect_url;
        unset($redirect_url);
    }
 }
 


if($action=='withdraw'){
    
    if(wp_verify_nonce($_POST['rimplenet_wallet_withdrawal_nonce'], 'rimplenet_wallet_withdrawal_nonce')){

        $wallet_id_submitted = $_POST["rimplenet_withdrawal_wallet"];
        $rimplenet_amount_to_withdraw_submitted  = $_POST["rimplenet_amount_to_withdraw"];
        $amount_to_withdraw = $rimplenet_amount_to_withdraw_submitted;
        $rimplenet_withdrawal_destination_submitted  = $_POST["rimplenet_withdrawal_destination"];
        $rimplenet_withdrawal_note_submitted  = $_POST["rimplenet_withdrawal_note"];
        if(!empty($rimplenet_withdrawal_note_submitted)){
          $note = ' WITHDRAWAL - '.$rimplenet_withdrawal_note_submitted;
        }
        else{
            
          $note = 'Withdrawal Transaction';
        }
        $user_id = $current_user->ID;
        
        
        do_action('rimplenet_withdrawal_form_post', $current_user, $wallet_id_submitted, $rimplenet_amount_to_withdraw_submitted, $rimplenet_withdrawal_destination_submitted,$note );
        
        
        $min_withdrawal_amt = $min_withdrawal_amt[$wallet_id_submitted];
        if(empty($min_withdrawal_amt)){$min_withdrawal_amt = 0;}
        
        $max_withdrawal_amt = $max_withdrawal_amt[$wallet_id_submitted];
        if(empty($max_withdrawal_amt)){$max_withdrawal_amt = INF;}

        
        //Checks
        if (empty($wallet_id_submitted)) {
            $wdr_info = "Wallet ID is empty";
        }
        elseif (empty($amount_to_withdraw)) {
            $wdr_info = "Amount is empty or 0";
            }
        elseif ($amount_to_withdraw<$min_withdrawal_amt) {
            $wdr_info = "Minimum Amount should be ".getRimplenetWalletFormattedAmount($min_withdrawal_amt,$wallet_id_submitted);
            }
        elseif ($amount_to_withdraw>$max_withdrawal_amt) {
            $wdr_info = "Maximum Amount should be ".getRimplenetWalletFormattedAmount($max_withdrawal_amt,$wallet_id_submitted);
            }
        elseif(!empty($GLOBALS['wdr_err_notice'] )){
            $wdr_info = $GLOBALS['wdr_err_notice'] ;
        }
        else{
            $wdr_info = $this->withdraw_wallet_bal($user_id, $rimplenet_amount_to_withdraw_submitted, $wallet_id_submitted, $rimplenet_withdrawal_destination_submitted, $note);
        }
        
        if($wdr_info>1){
            $success_message = 'Request Successful';
            if(!empty($redirect_url_data[$wallet_id_submitted]) ){
                $redirect_link = $redirect_url_data[$wallet_id_submitted];
                $success_message .= '<br>Redirecting to <a href="'.$redirect_link.'">'.$redirect_link.'</a> in few seconds...';
                $success_message .=  '<script>
                      window.addEventListener("load", function(){
                        window.location.href = "'.$redirect_link.'";
                      });
                    </script>';
            }
            do_action('rimplenet_withdrawal_request_submitted_success', $wdr_info, $current_user, $wallet_id_submitted, $rimplenet_amount_to_withdraw_submitted, $rimplenet_withdrawal_destination_submitted,$note );
        
        }
        else{
            
            $error_message = $wdr_info;
            do_action('rimplenet_withdrawal_request_submitted_failed', $wdr_info, $current_user, $wallet_id_submitted, $rimplenet_amount_to_withdraw_submitted, $rimplenet_withdrawal_destination_submitted,$note );
        
        }
        
        
        
       }
    ?>
   
  <div class="rimplenet-mt"> 
        <center>
        <div class="card">
        <div class="card-header card-header-primary">
            <?php echo $title; ?>
        </div>
        <div class="card-body">
         <br>
                        <?php

                           if (!empty($success_message)) {
                         
                        ?>

                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                          <strong> SUCCESS: </strong> <?php echo $success_message; ?>
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <?php
                          }
    

                     ?>

                    <?php

                           if (!empty($error_message)) {
                         
                        ?>

                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                          <strong> ERROR: </strong> <?php echo $error_message; ?>
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <?php
                          }
    
                     ?>

      <br>
 <form action="" method="post">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="rimplenet_withdrawal_wallet">Select Wallet</label>
      <select name="rimplenet_withdrawal_wallet" id="rimplenet_withdrawal_wallet" class="form-control" required>
         <?php
         
         if($wallet_id=='all'){
            
            foreach($all_wallets as $wallet){
              $wallet_id_op = $wallet['id'];
              if($wallet['include_in_withdrawal_form']=='yes'){
               $user_wdr_bal = $this->get_withdrawable_wallet_bal($user_id, $wallet_id_op);
               $dec = $wallet['decimal'];
               $symbol = $wallet['symbol'];
               $symbol_position = $all_wallets[$wallet_id_op]['symbol_position'];
               
               $disp_info = getRimplenetWalletFormattedAmount($user_wdr_bal,$wallet_id_op,'wallet_name');
               
              ?>
                <option value="<?php echo $wallet_id_op; ?>"> <?php echo $disp_info; ?> </option> 
            <?php
               }
             }
             
             
         }
         else{
             $withdrawal_wallets_op = explode(",",$wallet_id);
             foreach($withdrawal_wallets_op as $wallet_id_op){
               $wallet_id_op = trim($wallet_id_op);
               $user_wdr_bal = $this->get_withdrawable_wallet_bal($user_id, $wallet_id_op);
               $dec = $all_wallets[$wallet_id_op]['decimal'];
               $symbol = $all_wallets[$wallet_id_op]['symbol'];
               $symbol_position = $all_wallets[$wallet_id_op]['symbol_position'];
               
               $disp_info = getRimplenetWalletFormattedAmount($user_wdr_bal,$wallet_id_op,'wallet_name');
               
             ?>
            <option value="<?php echo $wallet_id_op; ?>"> <?php echo $disp_info; ?> </option> 
        <?php
             }
         }
         ?>
      </select>
    </div>
    <div class="form-group col-md-6">
      <label for="rimplenet_amount_to_withdraw"> <?php echo $wdr_amt_text_label; ?> </label>
      <input type="text" class="form-control" name="rimplenet_amount_to_withdraw" id="rimplenet_amount_to_withdraw" placeholder="<?php echo $wdr_amt_text_placeholder; ?>" required>
    </div>
  </div>
 
  <?php 
  do_action('rimplenet_withdrawal_form_before_withdrawal_destination',$wallet_id, $user_id,$title,$button_text);  
  $placeholder_text = apply_filters( 'rimplenet_withdrawal_field_placeholder', $wdr_dest_text_placeholder, $wallet_id,$user_id, $title,$button_text);
  ?> 
  <div class="form-row rimplenet_withdrawal_destination">
    <div class="form-group col-md-12">
    <label for="rimplenet_withdrawal_destination"><?php echo $wdr_dest_text_label; ?></label>
    <textarea class="form-control" name="rimplenet_withdrawal_destination" id="rimplenet_withdrawal_destination" rows="3" placeholder="<?php echo $wdr_dest_text_placeholder; ?>" required></textarea>
    </div>
  </div>
  <?php do_action('rimplenet_withdrawal_form_after_withdrawal_destination', $wallet_id, $user_id, $title,$button_text);  ?>
  
  <?php do_action('rimplenet_withdrawal_form_before_withdrawal_note');  ?>
  <div class="form-row rimplenet_withdrawal_note">
    <div class="form-group col-md-12">
    <label for="rimplenet_withdrawal_note"> <?php echo $wdr_note_text_label; ?> </label>
    <textarea class="form-control" name="rimplenet_withdrawal_note" id="rimplenet_withdrawal_note" rows="3" placeholder="<?php echo $wdr_note_text_placeholder; ?>"></textarea>
    </div>
  </div>
  <?php do_action('rimplenet_withdrawal_form_after_withdrawal_note', $wallet_id, $user_id, $title,$button_text);  ?>
  
    <?php wp_nonce_field( 'rimplenet_wallet_withdrawal_nonce', 'rimplenet_wallet_withdrawal_nonce' ); ?>
  <button type="submit" class="btn btn-primary"> <?php echo $button_text; ?> </button>
</form>
</div>
</div>
</center>  

    
<?php
 echo "<p><small> Consider using shortcode [rimplenet-withdrawal-form] instead on this page as the current used shortcode on this page will be deprecated soon</small></p>";

 }
elseif($action=='view_balance_history'){
   echo $wallet_obj->RimplenetWalletHistory($atts1);
   echo "<p><small> Consider using shortcode [rimplenet-wallet-history] instead on this page as the current used shortcode on this page will be deprecated soon</small></p>";
 }

elseif($action=='view_balance'){
    
    
    //$wallet_obj->add_user_immature_funds_to_wallet($user_id, -10,$wallet_id, 'Testing here');
     
    $bal = $wallet_obj->get_total_wallet_bal_disp_formatted($user_id, $wallet_id );

    echo $bal;
}
else{
  echo __('You did not specify a valid wallet action in shortcode e.g [rimplenet-wallet action="view_balance"] has a valid action which is view balance', 'rimplenet-text-domain'); 
}




?>