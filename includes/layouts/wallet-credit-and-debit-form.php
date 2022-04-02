<?php

    global $current_user,$wp;
    wp_get_current_user();
    
    $viewed_url = $_SERVER['REQUEST_URI'];
    $wallet_obj = new Rimplenet_Wallets();
    $all_wallets = $wallet_obj->getWallets();
?>

<div class="rimplenet-mt"> 
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
               
               if($symbol_position=='right'){
                   $disp_info = $wallet['name']." - ".number_format($user_wdr_bal,$dec)." ".$symbol;
               }
               else{
                   $disp_info = $wallet['name']." - ".$symbol.number_format($user_wdr_bal,$dec);
               }
               
              ?>
                <option value="<?php echo $wallet_id_op; ?>" > <?php echo $disp_info; ?> </option> 
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
               if($symbol_position=='right'){
                   $disp_info = $all_wallets[$wallet_id_op]['name']." - ".number_format($user_wdr_bal,$dec)." ".$symbol;;
               }
               else{
                   $disp_info = $all_wallets[$wallet_id_op]['name']." - ".$symbol.number_format($user_wdr_bal,$dec);
               }
             ?>
            <option value="<?php echo $wallet_id_op; ?>" selected> <?php echo $disp_info; ?> </option> 
        <?php
             }
         }
         ?>
      </select>
    </div>
    <div class="form-group col-md-6">
      <label for="rimplenet_amount_to_withdraw"> Amount to Withdraw</label>
      <input type="text" class="form-control" name="rimplenet_amount_to_withdraw" id="rimplenet_amount_to_withdraw" placeholder="e.g 1000 , no space, comma, currency sign or special character" required>
    </div>
  </div>
 
  <?php 
  do_action('rimplenet_withdrawal_form_before_withdrawal_destination',$wallet_id, $user_id,$title,$button_text);  
  $placeholder_text = apply_filters( 'rimplenet_withdrawal_field_placeholder', $wdr_dest_text_placeholder, $wallet_id,$user_id, $title,$button_text);
  ?> 
  <div class="form-row rimplenet_withdrawal_destination">
    <div class="form-group col-md-12">
    <label for="rimplenet_withdrawal_destination">Withdrawal Destination</label>
    <textarea class="form-control" name="rimplenet_withdrawal_destination" id="rimplenet_withdrawal_destination" rows="3" placeholder="<?php echo $placeholder_text; ?>"></textarea>
    </div>
  </div>
  <?php do_action('rimplenet_withdrawal_form_after_withdrawal_destination', $wallet_id, $user_id, $title,$button_text);  ?>
  
  <?php do_action('rimplenet_withdrawal_form_before_withdrawal_note');  ?>
  <div class="form-row rimplenet_withdrawal_note">
    <div class="form-group col-md-12">
    <label for="rimplenet_withdrawal_note">Withdrawal Note (optional) </label>
    <textarea class="form-control" name="rimplenet_withdrawal_note" id="rimplenet_withdrawal_note" rows="3" placeholder="Leave withdrawal note here"></textarea>
    </div>
  </div>
  <?php do_action('rimplenet_withdrawal_form_after_withdrawal_note', $wallet_id, $user_id, $title,$button_text);  ?>
  
	<?php wp_nonce_field( 'rimplenet_wallet_withdrawal_nonce', 'rimplenet_wallet_withdrawal_nonce' ); ?>
  <button type="submit" class="btn btn-primary"> <?php echo $button_text; ?> </button>
</form>
</div>
</div>
</div>