<?php
//Included from shortcode in includes/class-withdrawals.php
//use case [rimplenet-withdrawal-form wallet_id="ID_HERE" wdr_dest="bank"] or [rimplenet-withdrawal-form wallet_id="ID_HERE" wdr_dest="crypto_address"] or [rimplenet-withdrawal-form wallet_id="ID_HERE"]
 global $current_user;
 wp_get_current_user();
 ?>
 <?php
   if(!is_user_logged_in()) {
?>
  <center>
   <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong> ERROR: </strong> Please Login or Register by clicking account tab
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
    'wdr_dest' => 'empty',
    'wdr_dest_text_label' => 'Withdrawal Destination',
    'wdr_dest_text_placeholder' => 'Insert your Withdrawal Destination e.g Account Name or Crypto Address',
    'crypto_address_placeholder' => 'Ox........Fb',
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
$wdr_dest = $atts['wdr_dest'];
$wdr_dest_text_label = $atts['wdr_dest_text_label'];
$wdr_dest_text_placeholder = $atts['wdr_dest_text_placeholder'];
$crypto_address_placeholder = $atts['crypto_address_placeholder'];
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
 
    
    if(wp_verify_nonce($_POST['rimplenet_wallet_withdrawal_nonce'], 'rimplenet_wallet_withdrawal_nonce')){

        $request_id = sanitize_text_field(trim($_POST['request_id']));
        $wallet_id_submitted = sanitize_text_field(trim($_POST["rimplenet_withdrawal_wallet"]));
        $rimplenet_amount_to_withdraw_submitted  = sanitize_text_field(trim($_POST["rimplenet_amount_to_withdraw"]));
        $amount_to_withdraw = $rimplenet_amount_to_withdraw_submitted;
        
        $bank_name  = sanitize_text_field(trim($_POST["rimplenet_withdrawal_bank"]));
        $account_number  = sanitize_text_field(trim($_POST["rimplenet_withdrawal_account_number"]));
        $account_name  = sanitize_text_field(trim($_POST["rimplenet_withdrawal_account_name"]));
        $rimplenet_withdrawal_crypto_address  = sanitize_text_field(trim($_POST["rimplenet_withdrawal_crypto_address"]));
        
        $rimplenet_withdrawal_destination_submitted  = sanitize_text_field(trim($_POST["rimplenet_withdrawal_destination"]));
        
        $rimplenet_withdrawal_note_submitted  = sanitize_text_field(trim($_POST["rimplenet_withdrawal_note"]));
        if(!empty($rimplenet_withdrawal_note_submitted)){
          $note = ' WITHDRAWAL - '.$rimplenet_withdrawal_note_submitted;
        }
        else{
            
          $note = 'Withdrawal Transaction';
        }
        $user_id = $current_user->ID;
        
        
        do_action('rimplenet_withdrawal_form_post', $current_user, $wallet_id_submitted, $rimplenet_amount_to_withdraw_submitted, $rimplenet_withdrawal_destination_submitted,$note );
                
        do_action('rimplenet_withdrawal_form_submitted', $request_id, $current_user, $wallet_id_submitted, $rimplenet_amount_to_withdraw_submitted, $rimplenet_withdrawal_destination_submitted, $note);
        
        
        
        $min_withdrawal_amt = $min_withdrawal_amt[$wallet_id_submitted];
        if(empty($min_withdrawal_amt)){$min_withdrawal_amt = 0;}
        
        $max_withdrawal_amt = $max_withdrawal_amt[$wallet_id_submitted];
        if(empty($max_withdrawal_amt)){$max_withdrawal_amt = INF;}
        
        $wdr_dest_data = array();
        if($wdr_dest=="bank"){
            $wdr_dest_data["withdrawal_bank_name"] = $bank_name;
            $wdr_dest_data["withdrawal_account_number"] = $account_number;
            $wdr_dest_data["withdrawal_account_name"] = $account_name;
            
            $wdr_dest_data["withdrawal_destination"] = "$bank_name - $account_number ~ $account_name";
        }
        elseif($wdr_dest=="crypto_address"){
            $wdr_dest_data["withdrawal_crypto_address"] = $rimplenet_withdrawal_crypto_address;
            
            $wdr_dest_data["withdrawal_destination"] = "ADDRESS ~ $rimplenet_withdrawal_crypto_address";
        }
        else{
            $wdr_dest_data["withdrawal_destination"] = $rimplenet_withdrawal_destination_submitted;
        }
        
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
            $wdr_info = $this->withdraw_user_wallet_bal($request_id, $user_id, $rimplenet_amount_to_withdraw_submitted, $wallet_id_submitted, $wdr_dest, $wdr_dest_data, $note, $extra_data);
            
         }
        $wdr_info_encode = json_decode($wdr_info);
        if($wdr_info_encode->status=="success"){
            
            $wdr_id = $wdr_info_encode->data->txn_id;
            $success_message = "Request Successful - #$wdr_id";
            if(!empty($redirect_url_data[$wallet_id_submitted]) ){
                $redirect_link = $redirect_url_data[$wallet_id_submitted];
                $success_message .= '<br>Redirecting to <a href="'.$redirect_link.'">'.$redirect_link.'</a> in few seconds...';
                $success_message .=  '<script>
                      window.addEventListener("load", function(){
                        window.location.href = "'.$redirect_link.'";
                      });
                    </script>';
            }
            do_action('rimplenet_withdrawal_request_submitted_success', $wdr_id, $current_user, $wallet_id_submitted, $rimplenet_amount_to_withdraw_submitted, $rimplenet_withdrawal_destination_submitted,$note );
        
        }
        else{
            
            $error_message = $wdr_info;
            do_action('rimplenet_withdrawal_request_submitted_failed', $wdr_info, $current_user, $wallet_id_submitted, $rimplenet_amount_to_withdraw_submitted, $rimplenet_withdrawal_destination_submitted,$note );
        
        }
        
        
        
       }
    ?>
    
    
        
 <div class="rimplenet-bs-default">       
    <div class="rimplenet-status-msg">
    <center>
          <?php
            if(!empty($success_message)) {
           ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $success_message; ?>
              </div>
          <?php
                }
            ?>
            
          <?php
             if (!empty($error_message)) {
           ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $error_message; ?>
              </div>
            <?php
                }
            ?>
    </center>
    </div>
    
     <form action="" method="POST" class="rimplenet-withdrawal-form" id="rimplenet-withdrawal-form" > 
           <div class="clearfix"></div><br>
            <div class="row">
             <div class="col-md-6">
               <label for="rimplenet_withdrawal_wallet"> <strong> Choose Wallet </strong> </label>
               <select name="rimplenet_withdrawal_wallet" id="rimplenet_withdrawal_wallet" class="rimplenet_withdrawal_wallet rimplenet-select" required="">
                   <?php
                     
                     if($wallet_id=='all'){
                        
                        foreach($all_wallets as $wallet){
                          $wallet_id_op = $wallet['id'];
                          if($wallet['include_in_withdrawal_form']=='yes'){
                           $user_wdr_bal = $wallet_obj->get_withdrawable_wallet_bal($user_id, $wallet_id_op);
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
                           $user_wdr_bal = $wallet_obj->get_withdrawable_wallet_bal($user_id, $wallet_id_op);
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
                <!--<p style="float:right;"><small>SWAP FEE ~ 0.009 ETH</small></p>-->
             </div>
             
           
             
             <div class="col-md-6">
                  <label for="rimplenet_amount_to_withdraw"> <strong> <?php echo $wdr_amt_text_label; ?> </strong> </label>
                  <input name="rimplenet_amount_to_withdraw" id="rimplenet_amount_to_withdraw" class="rimplenet_amount_to_withdraw rimplenet-input" placeholder="<?php echo $wdr_amt_text_placeholder; ?>" type="text" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>"  value="" required="">       
                  <!--<p class="mb-0 text-right">1 USD ~ 0.0001 ETH <a href="#">Expected rate - No extra
                    fees</a></p>-->
                  <?php 
                   do_action('rimplenet_withdrawal_form_before_withdrawal_destination',$wallet_id, $user_id,$title,$button_text);  
                   $placeholder_text = apply_filters( 'rimplenet_withdrawal_field_placeholder', $wdr_dest_text_placeholder, $wallet_id,$user_id, $title,$button_text);
                  ?> 
             </div>
            
            <div class="clearfix"></div><br> 
            
            <?php if($wdr_dest=="bank"){ ?>
             <div class="col-md-6">
               <label for="rimplenet_withdrawal_bank"> <strong> Bank </strong> </label>
               <!--<select name="rimplenet_withdrawal_bank" id="rimplenet_withdrawal_bank" class="rimplenet_withdrawal_bank rimplenet-select" required="">
                   <option value="Other"> Other </option> 
                </select>
                -->
                <input type="text" name="rimplenet_withdrawal_bank" id="rimplenet_withdrawal_bank" class="rimplenet_withdrawal_bank rimplenet-input" placeholder="Bank Name" value="" required="">       
                  
                <!--<p style="float:right;"><small>Bottom Text ~ 0.009 ETH</small></p>-->
             </div>

             <div class="col-md-6">
                  <label for="rimplenet_withdrawal_account_number"> <strong> Account Number </strong> </label>
                  <input name="rimplenet_withdrawal_account_number" id="rimplenet_withdrawal_account_number" class="rimplenet_withdrawal_account_number rimplenet-input" placeholder="<?php echo $wdr_amt_text_placeholder; ?>" type="text" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>"  value="" required="">       
                  <!--<p class="mb-0 text-right">1 USD ~ 0.0001 ETH <a href="#">Expected rate - No extra
                    fees</a></p>-->
             </div>
             
             <div class="col-md-12">
                  <label for="rimplenet_withdrawal_account_name"> <strong> Account Name </strong> </label>
                  <input name="rimplenet_withdrawal_account_name" id="rimplenet_withdrawal_account_name" class="rimplenet_withdrawal_account_name rimplenet-input" placeholder="John Doe" type="text" value="" required="">       
                  <!--<p class="mb-0 text-right">1 USD ~ 0.0001 ETH <a href="#">Expected rate - No extra
                    fees</a></p>-->
             </div>
             
             <?php }
               elseif($wdr_dest=="crypto_address"){
             ?>
             
              
             <div class="col-md-12">
                  <label for="rimplenet_withdrawal_crypto_address"> <strong> Crypto Address </strong> </label>
                  <input name="rimplenet_withdrawal_crypto_address" id="rimplenet_withdrawal_crypto_address" class="rimplenet_withdrawal_crypto_address rimplenet-input" placeholder="<?php echo $crypto_address_placeholder; ?>" type="text" value="" required="">       
                  <!--<p class="mb-0 text-right">1 USD ~ 0.0001 ETH <a href="#">Expected rate - No extra
                    fees</a></p>-->
             </div>
             <?php }
               else{
             ?>
             
            
            <div class="clearfix"></div><br> 
            <div class="col-md-12">
             <label for="rimplenet_withdrawal_destination"><strong> <?php echo $wdr_dest_text_label; ?></strong> </label>
             <textarea name="rimplenet_withdrawal_destination" id="rimplenet_withdrawal_destination" class="rimplenet_withdrawal_destination rimplenet-textarea" rows="3" placeholder="<?php echo $wdr_dest_text_placeholder; ?>" required></textarea>
            </div>
            <?php } ?>
            <?php do_action('rimplenet_withdrawal_form_after_withdrawal_destination', $wallet_id, $user_id, $title,$button_text);  ?>
            
            <div class="clearfix"></div><br> 
            
            <div class="col-md-12">
             <label for="rimplenet_withdrawal_note"><strong> <?php echo $wdr_note_text_label; ?></strong> </label>
             <input type="text" name="rimplenet_withdrawal_note" id="rimplenet_withdrawal_note" class="rimplenet_withdrawal_note rimplenet-input" placeholder="<?php echo $wdr_note_text_placeholder; ?>" maxlength="30" value=""> 
            </div>
            <?php do_action('rimplenet_withdrawal_form_after_withdrawal_note', $wallet_id, $user_id, $title,$button_text);  ?>
            
             
            <div class="clearfix"></div><br> 
             <div class="col-md-12">
                <?php wp_nonce_field( 'rimplenet_wallet_withdrawal_nonce', 'rimplenet_wallet_withdrawal_nonce' ); ?>
                <div class="clearfix"></div>
                <br>
                <center>
                    
                  <input name="request_id" type="hidden" value="<?php echo time(); ?>" required=""> 
                  <input class="rimplenet-button rimplenet_submit_withdrawal_form" id="rimplenet_submit_withdrawal_form" value="<?php echo $button_text; ?>" type="submit" >
                </center>
             </div>
            
           </div>  
         </form>
 
            
            
        </div>
    </div>
 </div> 

<script type="text/javascript">
  jQuery(document).ready(function ($) {
    $('form#rimplenet-withdrawal-form').submit(function(){
        $(this).find(':input[type=submit]').prop('disabled', true);
      });
    });
</script>