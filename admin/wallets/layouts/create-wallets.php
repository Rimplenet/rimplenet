<?php

if(isset( $_POST['rimplenet_wallet_submitted'] ) || wp_verify_nonce( $_POST['rimplenet_wallet_settings_nonce_field'], 'rimplenet_wallet_settings_nonce_field' ) )  {


     $req = [
            'wallet_name'           => sanitize_text_field($_POST['rimplenet_wallet_name']),
            'wallet_id'             => sanitize_text_field($_POST['rimplenet_wallet_id']),
            'wallet_symbol'         => sanitize_text_field($_POST['rimplenet_wallet_symbol']),
            'wallet_symbol_pos'     => sanitize_text_field($_POST['rimplenet_wallet_symbol_pos'] ?? 'left'),
            'wallet_note'           => sanitize_text_field($_POST['rimplenet_wallet_note'] ?? $_POST['rimplenet_wallet_name']),
            'wallet_type'           => sanitize_text_field($_POST['rimplenet_wallet_type']),
            'wallet_decimal'        => intval($_POST['rimplenet_wallet_decimal']) ?? 2,
            'max_withdrawal_amount' => intval($_POST['rimplenet_max_withdrawal_amount']) ?? CreateWallet::MAX_AMOUNT,
            'min_withdrawal_amount' => intval($_POST['rimplenet_min_withdrawal_amount']) ?? CreateWallet::MIN_AMOUNT,
            'inc_i_w_cl'            => $_POST['rimplenet_inc_in_woocmrce_curr_list'] ?? false,
            'e_a_w_p'               => $_POST['rimplenet_enable_as_woocmrce_pymt_wlt'] ?? false,
            'r_b_b_w'               => sanitize_text_field($_POST['rimplenet_rules_before_withdrawal'] ?? ''),
            'r_a_b_w'               => sanitize_text_field($_POST['rimplenet_rules_after_withdrawal'] ?? '')
        ];

 


        $wallets = new RimplenetCreateWallets();

        // var_dump($wallets->createWallet($req));
        // die;
        if ($wallets->createWallet($req) && empty($wallets->response['error'])) {
            echo '<div class="updated">
            <p>Your Wallet have been created successfully</p>
        </div> ';
        }else{
            var_dump($wallets->response['error']);
        }

}
?>



<h2>CREATE NEW WALLET</h2>
  <form method="POST">
    <input type="hidden" name="rimplenet_wallet_submitted" value="true" />
    <?php wp_nonce_field( 'rimplenet_wallet_settings_nonce_field', 'rimplenet_wallet_settings_nonce_field' ); ?>

    <table class="form-table">
        <tbody>

            <tr>
                <th><label for="rimplenet_wallet_name"> Wallet Name </label></th>
                <td><input name="rimplenet_wallet_name" id="rimplenet_wallet_name" type="text" value="<?php echo get_option('rimplenet_wallet_name'); ?>" placeholder="e.g United Bunny Wallet" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
            </tr>
            <tr>
                <th><label for="rimplenet_wallet_desc"> Wallet Description </label></th>
                <td>
                  <textarea id="rimplenet_wallet_desc" name="rimplenet_wallet_desc" placeholder="Description here" style="<?php echo $input_width; ?>"></textarea>

                  </td>
            </tr>
            <tr>
                <th><label for="rimplenet_wallet_symbol"> Wallet Symbol </label></th>
                <td><input name="rimplenet_wallet_symbol" id="rimplenet_wallet_symbol" type="text" value="<?php echo get_option('rimplenet_wallet_symbol'); ?>" placeholder="e.g $" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
            </tr>

            <tr>
                <th><label for="rimplenet_wallet_decimal"> Wallet Decimal </label></th>
                <td><input name="rimplenet_wallet_decimal" id="rimplenet_wallet_decimal" type="number" min="1" value="<?php echo get_option('rimplenet_wallet_decimal'); ?>" placeholder="e.g 2"  class="regular-text" required style="<?php echo $input_width; ?>" /></td>
            </tr>

            <tr>
                <th><label for="rimplenet_wallet_decimal"> Wallet Type </label></th>
                <td><select name="rimplenet_wallet_type" id="rimplenet_wallet_type" style="width: 100%;max-width: 400px; height: 40px;" required="">
                         <option value=""> Select Wallet Type  </option> 
                         <option value="fiat" selected=""> Fiat Currency Wallet </option> 
                         <option value="crypto"> Cryptocurrency Wallet </option> 
                      </select>
                    </td>
            </tr>

            <tr>
                <th><label for="rimplenet_min_withdrawal_amount"> Wallet Minimum Withdrawal Amount  </label></th>
                <td><input name="rimplenet_min_withdrawal_amount" id="rimplenet_min_withdrawal_amount" type="text"  value="<?php echo get_option('rimplenet_min_withdrawal_amount'); ?>" placeholder="e.g 10"  class="regular-text" required style="<?php echo $input_width; ?>" /></td>
            </tr>
            
            <tr>
                <th><label for="rimplenet_max_withdrawal_amount"> Wallet Maximum Withdrawal Amount </label></th>
                <td><input name="rimplenet_max_withdrawal_amount" id="rimplenet_max_withdrawal_amount" type="text"  value="<?php echo get_option('rimplenet_max_withdrawal_amount'); ?>" placeholder="e.g 99.99"  class="regular-text" required style="<?php echo $input_width; ?>" /></td>
            </tr>
            
            <tr>
                <th><label for="rimplenet_wallet_id"> Wallet ID </label></th>
                <td><input name="rimplenet_wallet_id" id="rimplenet_wallet_id" type="text" value="<?php echo get_option('rimplenet_wallet_id'); ?>" placeholder="e.g usd" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
            </tr>
            
            <tr>
            <th scope="row">Wallet Symbol Display Position</th>
            <td>
              <fieldset>
                  <legend class="screen-reader-text"><span>Wallet Symbol Display Position</span></legend>
                  <label><input type="radio" name="rimplenet_wallet_symbol_position" value="left" checked="checked"> 
                  <span class="">Left - (Suitable for Fiat Wallet)  </span></label> <br>
                  
                  <label><input type="radio" name="rimplenet_wallet_symbol_position" value="right"> 
                  <span class=""> Right - (Suitable for crytocurrency wallet) </span></label> <br>
              
              </fieldset>
            </td>
            </tr>
             
            <tr>
            <th scope="row">Include in Withdrawal Form</th>
            <td>
              <fieldset>
                  <legend class="screen-reader-text"><span>Include in Withdrawal Form</span></legend>
                  <label><input type="radio" name="include_in_withdrawal_form" value="yes" checked="checked"> 
                  <span class="">Yes Include - (This will show in Withdrawal form.)  </span></label> <br>
                  
                  <label><input type="radio" name="include_in_withdrawal_form" value="no"> 
                  <span class=""> No, Don't Include - (This will not show in Withdrawal form) </span></label> <br>
              
              </fieldset>
            </td>
            </tr>
             
            <tr>
            <th scope="row">Include in Woocommerce Currencies</th>
            <td>
              <fieldset>
                  <legend class="screen-reader-text"><span>Include in Woocommerce Currencies</span></legend>
                  <label><input type="radio" name="include_in_woocommerce_currency_list" value="yes" checked="checked"> 
                  <span class="">Yes Include - (This will show in Woocommerce Currency List, be careful as some payment processors may not recognized it.)  </span></label> <br>
                  
                  <label><input type="radio" name="include_in_woocommerce_currency_list" value="no"> 
                  <span class=""> No, Don't Include - (This will not show in Woocommerce Currency List) </span></label> <br>
              
              </fieldset>
            </td>
            
            </tr>
            
              
            <tr>
            <th scope="row">Include as Woocommerce Product Payment Wallet</th>
            <td>
              <fieldset>
                  <legend class="screen-reader-text"><span>Include as Woocommerce Product Payment Wallet</span></legend>
                  <label><input type="radio" name="include_in_woocommerce_product_payment_wallet" value="yes"  checked="checked"> 
                  <span class="">Yes Include - (This will show in Woocommerce Product Payment Wallet)  </span></label> <br>
                  
                  <label><input type="radio" name="include_in_woocommerce_product_payment_wallet" value="no"> 
                  <span class=""> No, Don't Include - (This will not show as Woocommerce Product Payment Wallet) </span></label> <br>
              
              </fieldset>
            </td>
            
            </tr>
            
            
            <tr>
                <td><h2>WALLET RULES</h2> </td>  
            </tr>


            <tr>
                <th><label for="rimplenet_rules_before_wallet_withdrawal"> Rules to Achieve before User Qualifies to Withdraw from this wallet </label></th>
                <td>
                  <textarea name="rimplenet_rules_before_wallet_withdrawal" id="rimplenet_rules_before_wallet_withdrawal" style="<?php echo $input_width; ?>"></textarea>

                  </td>
            </tr>

            <tr>
                <th><label for="rimplenet_rules_after_wallet_withdrawal"> Rules to Apply to User after Withdrawal </label></th>
                <td>
                  <textarea name="rimplenet_rules_after_wallet_withdrawal" id="rimplenet_rules_after_wallet_withdrawal" style="<?php echo $input_width; ?>"></textarea>

                </td>
            </tr>
            
        </tbody>
    </table>

    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="CREATE WALLET">
    </p>
  </form>