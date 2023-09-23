<?php
//Included from shortcode in includes/class-wallets.php
//use case [rimplenet-wallet action="view_balance" user_id="1"]
 global $current_user;
 wp_get_current_user();

$atts = shortcode_atts( array(

    'action' => 'empty',
    'user_id' => $current_user->ID,
    'wallet_id' => 'woocommerce_base_cur',

), $atts );


$action = $atts['action'];
$user_id = $atts['user_id'];
$wallet_id = $atts['wallet_id'];

$wallet_obj = new rimplenet_Wallets();
$all_wallets = $wallet_obj->getWallets();

    
    if(wp_verify_nonce($_POST['rimplenet_wallet_transfer_nonce'], 'rimplenet_wallet_transfer_nonce')){

    $wallet_id = sanitize_text_field($_POST["rimplenet_wallet"]);
    $rimplenet_wallet_tranfer_amount = sanitize_text_field($_POST["rimplenet_wallet_tranfer_amount"]);
    $rimplenet_wallet_transfer_destination = sanitize_text_field($_POST["rimplenet_wallet_transfer_destination"]);
    $rimplenet_transfer_note = sanitize_text_field($_POST["rimplenet_transfer_note"]);
    
    $note = $rimplenet_transfer_note;
    $user_id = $current_user->ID;
    
    
    do_action('rimplenet_wallet_transfer_form_post', $current_user, $wallet_id, $rimplenet_wallet_tranfer_amount, $rimplenet_wallet_transfer_destination,$note );
    
    $transfer_info = $this->transfer_wallet_bal($user_id, $rimplenet_wallet_tranfer_amount, $wallet_id, $rimplenet_wallet_transfer_destination, $note);
    
    if($transfer_info>1){
        $success_message = 'Transfer Request Successful';
        do_action('rimplenet_wallet_transfer_request_success', $transfer_info, $current_user, $wallet_id, $rimplenet_wallet_tranfer_amount, $rimplenet_wallet_transfer_destination,$note );
    
    }
    else{
        
        $error_message = $transfer_info;
        do_action('rimplenet_wallet_transfer_request_failed', $transfer_info, $current_user, $wallet_id, $rimplenet_wallet_tranfer_amount, $rimplenet_wallet_transfer_destination,$note );
    
    }
    
    
  }
  
?>
   
  <div class="rimplenet-mt"> 
  
 

<center>
<div class="card">
<div class="card-header card-header-primary">
 TRANSFER
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
      <label for="rimplenet_wallet">Select Wallet</label>
      <select name="rimplenet_wallet" id="rimplenet_wallet" class="form-control" required>
         <?php
         
         if($wallet_id=='all'){
            
            foreach($all_wallets as $wallet){
              $wallet_id = $wallet['wallet_id'];
              if($wallet['include_in_transfer_form']=='yes'){
             
              ?>
                <option value="<?php echo $wallet_id; ?>" > <?php echo $wallet['name']; ?></option> 
            <?php
               }
             }
             
             
         }
         else{
             ?>
            <option value="<?php echo $wallet_id; ?>" selected> <?php echo $all_wallets[$wallet_id]['name']; ?></option> 
        <?php
         }
         ?>
      </select>
    </div>
    <div class="form-group col-md-6">
      <label for="rimplenet_wallet_tranfer_amount"> Amount to Transfer</label>
      <input type="text" class="form-control" name="rimplenet_wallet_tranfer_amount" id="rimplenet_wallet_tranfer_amount" placeholder="e.g 1000 , no space, comma, currency sign or special character" required>
    </div>
  </div>
 
  <?php 
  do_action('rimplenet_form_before_wallet_transfer_destination');  
  $placeholder_text = apply_filters( 'rimplenet_wallet_transfer_destination', $wallet_id, $user_id ,'E.g Username' );
  ?> 
  <div class="form-row rimplenet_wallet_transfer_destination">
    <div class="form-group col-md-12">

      <label for="rimplenet_wallet_transfer_destination"> Username</label>
      <input type="text" class="form-control" name="rimplenet_wallet_transfer_destination" id="rimplenet_wallet_transfer_destination" placeholder="e.g doe" required>
    
    </div>
  </div>
  <?php do_action('rimplenet_form_after_wallet_transfer_destination');  ?>
  
  <?php do_action('rimplenet_form_before_transfer_note');  ?>
  <div class="form-row rimplenet_transfer_note">
    <div class="form-group col-md-12">
    <label for="rimplenet_transfer_note">Transfer Note (optional) </label>
    <textarea class="form-control" name="rimplenet_transfer_note" id="rimplenet_transfer_note" rows="3" placeholder="Leave transfer note here"></textarea>
    </div>
  </div>
  <?php do_action('rimplenet_form_after_transfer_note');  ?>
  
  
  <?php do_action('rimplenet_form_before_transfer_submit');  ?>
  <?php wp_nonce_field( 'rimplenet_wallet_transfer_nonce', 'rimplenet_wallet_transfer_nonce' ); ?>
  <button type="submit" class="btn btn-primary">TRANSFER</button>
</form>
</div>
</div>
</center>  