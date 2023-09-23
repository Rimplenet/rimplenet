<?php
//Included from shortcode in includes/class-investments.php
//use case [rimplenet-investment-form linked_package="868" wallet_id="eth,blcc" min="eth:0.1,blcc:20" max="eth:10,blcc:5000" time_to_return_invested_amount="+3 hours"]
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
     return ;
   }
?>

<?php
 global $current_user;
 wp_get_current_user();

$atts = shortcode_atts( array(

    'action' => 'empty',
    'user_id' => $current_user->ID,
    'linked_package' => '',
    'wallet_id' => 'all',
    'time_to_return_invested_amount' => '',
    'min' => '0',
    'max' => INF,

), $atts );

$wallet_obj = new Rimplenet_Wallets();
$all_wallets = $wallet_obj->getWallets();

$action = $atts['action'];
$user_id = $atts['user_id'];
$linked_package = $atts['linked_package'];
$wallet_id = $atts['wallet_id'];
$time_to_return_invested_amount = $atts['time_to_return_invested_amount'];
$min = $atts['min'];
$max = $atts['max'];

//Set Min Investment Amount
if(!empty($min)){
    $min_invest_amt = array();
    
    $min = explode(",",$min);
    foreach($min as $wallet_min_settings){
        $wallet_min_settings_exploded = explode(":",$wallet_min_settings);
        $wal_id = $wallet_min_settings_exploded[0];
        $min_amount = $wallet_min_settings_exploded[1];
        $min_invest_amt[$wal_id] = floatval($min_amount);
    }
 }
 
//Set Miax Investment Amount
if(!empty($max)){
    $max_invest_amt = array();
    
    $max = explode(",",$max);
    foreach($max as $wallet_max_settings){
        $wallet_max_settings_exploded = explode(":",$wallet_max_settings);
        $wal_id = $wallet_max_settings_exploded[0];
        $max_amount = $wallet_max_settings_exploded[1];
        $max_invest_amt[$wal_id] = floatval($max_amount);
    }
 }
 

?>

<div class="clearfix"></div><br>
    <div class='rimplenet-invest-div' style="max-width:600px;margin:auto;">
        <div class="rimplenet-investment-form">
            
            

    
    <?php
    
    if(isset($_POST['rimplenet-investment-form']) && wp_verify_nonce($_POST['rimplenet-investment-form'], 'rimplenet-investment-form' )) {
          
          global $wpdb;
        
        
          $amount = sanitize_text_field(trim($_POST['amount']));
          $payment_wallet = sanitize_text_field(trim($_POST['payment_wallet']));
          
          $min_investment_amt = $min_invest_amt[$payment_wallet];
          if(empty($min_investment_amt)){$min_investment_amt = 0;}
          $max_investment_amt = $max_invest_amt[$payment_wallet];
          if(empty($max_investment_amt)){$max_investment_amt = INF;}
                  
            if (empty($payment_wallet)) {
              $status_error = "Payment Wallet is empty";
            }
            
            elseif (empty($amount)) {
             $status_error = "Amount is empty or 0";
            }
            elseif ($amount===0) {
             $status_error = "Amount cannot be 0";
            }
            
            elseif ($amount<$min_investment_amt) {
             $status_error = "Minimum Investment Amount is ".getRimplenetWalletFormattedAmount($min_investment_amt,$payment_wallet);
            }
            elseif ($amount>$max_investment_amt) {
             $status_error = "Maximum Investment Amount is ".getRimplenetWalletFormattedAmount($max_investment_amt,$payment_wallet);
            }
            
            else{   
                
               $investment_info = $this->rimplenet_wallet_investment($user_id, $amount, $payment_wallet);
               
                if($investment_info>1){
                    $status_success = 'Investment Successful';
                    if(!empty($linked_package)){
                      update_post_meta($investment_info,'linked_package',$linked_package);
                    }
                    if(!empty($time_to_return_invested_amount)){
                      $time_to_return_investment = strtotime($time_to_return_invested_amount);
                      update_post_meta($investment_info,'time_for_invested_amount_to_be_returned',$time_to_return_investment); 
                    }
                    
                    do_action('rimplenet_investment_successful', $investment_info, $current_user, $wallet_id, $amount,$note );
                
                }
                else{
                    
                    $status_error = $investment_info;
                    do_action('rimplenet_investment_failed', $investment_info, $current_user, $wallet_id, $amount,$note );
                
                }
        
               
            }
    }
    ?>
          
          
    <div class="rimplenet-status-msg">
    <center>
          <?php
            if(!empty($status_success)) {
           ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $status_success; ?>
              </div>
          <?php
                }
            ?>
            
          <?php
             if (!empty($status_error)) {
           ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $status_error; ?>
              </div>
            <?php
                }
            ?>
    </center>
    </div>
        
   <form action="" method="POST" class="rimplenet-investment-form" id="rimplenet-investment-form" > 
  
            <div class="clearfix"></div><br>
            <div class="row">
             <div class="col-lg-12">
              <label for="payment_wallet"> <strong> Select Wallet </strong> </label>
             
                <select name="payment_wallet" id="rimplenet-select-payment-wallet" class="rimplenet-select rimplenet-select-payment-wallet" required="">
                    
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
                        <option value="<?php echo $wallet_id_op; ?>">  <?php echo $disp_info; ?> </option> 
                    <?php
                         }
                     }
                     ?>
                    
                </select>
             </div>
            
            
            <div class="clearfix"></div><br> <div class="col-lg-12">
             <label for="amount"> <strong> Amount </strong> </label>
             <input name="amount" id="rimplenet-input-amount" class="rimplenet-input rimplenet-input-amount" placeholder="0" type="text" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>"  value="" required="">       
           </div> 
            
             
           <div class="col-lg-12">
                <?php wp_nonce_field( 'rimplenet-investment-form', 'rimplenet-investment-form' ); ?>
                <div class="clearfix"></div>
                <br>
                <center>
                  <input class="rimplenet-button rimplenet-submit-investment-form" id="rimplenet-submit-investment-form" type="submit" value="INVEST">
                </center>
           </div>
            
           </div>  
         </form>
 
            
            
        </div>
    </div>