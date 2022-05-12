<?php
        $wallet_post_id = $meta_id->ID;
        $wallet_decimal = get_post_meta($wallet_post_id, 'rimplenet_wallet_decimal', true);
        $wallet_symbol = get_post_meta($wallet_post_id, 'rimplenet_wallet_symbol', true);
        $wallet_id = get_post_meta($wallet_post_id, 'rimplenet_wallet_id', true);
        $user_balance_shortcode  = '[rimplenet-wallet action="view_balance" wallet_id="'.$wallet_id.'"]';
        
        $min_withdrawal_amount = get_post_meta($wallet_post_id, 'rimplenet_min_withdrawal_amount', true);
        $max_withdrawal_amount= get_post_meta($wallet_post_id, 'rimplenet_max_withdrawal_amount', true);
        
        $wallet_symbol_position = get_post_meta($wallet_post_id, 'rimplenet_wallet_symbol_position', true);
        $enable_as_woocommerce_product_payment_wallet = get_post_meta($wallet_post_id, 'enable_as_woocommerce_product_payment_wallet', true);
        $include_in_woocommerce_currency_list = get_post_meta($wallet_post_id, 'include_in_woocommerce_currency_list', true);
        $wallet_type = get_post_meta($wallet_post_id, 'rimplenet_wallet_type', true);
        $wallet_note = get_post_meta($wallet_post_id, 'rimplenet_wallet_note', true);
        
        

?>
    <table class="form-table">
          <tbody>
            <tr>
                <th colspan="2"><h2>BASIC SETTINGS</h2> </th>  
            </tr>
            <tr>
                <th><label for="rimplenet_wallet_id"> 
                     Wallet Unique ID 
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Wallet ID should be unique for each of your created wallet, wallet id should be lowercase alphabets and underscore (blank space is not allowed) e.g btc for United State Dollars, ngn for Nigerian Naira , btc for Bitcoin, you can as well use savings_wallet"></span>
                </label></th>
                <td><input name="rimplenet_wallet_id" id="rimplenet_wallet_id" type="text" value="<?php echo $wallet_id; ?>" placeholder="usd or ngn or bitcoin or savings_wallet" class="regular-text" required style="width:100%;max-width: 400px; height: 40px;"> </td>
            </tr>
            <tr>
                <th><label for="rimplenet_wallet_symbol"> 
                     Wallet Symbol 
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Wallet Symbol of the Wallet e.g $ for Dollars, ₦ for Naira,  ₿  for Bitcoin or maybe ETH for Ethereum"></span>
                </label></th>
                <td><input name="rimplenet_wallet_symbol" id="rimplenet_wallet_symbol" type="text" value="<?php echo $wallet_symbol; ?>" placeholder="$ or € or ₦ or ₿ or ETH" class="regular-text" required style="width:100%;max-width: 400px; height: 40px;" checked> </td>
            </tr>
            <tr>
                <th>
                 <label for="rimplenet_wallet_symbol_position"> 
                     Wallet Symbol Display Position
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Symbol Display Position of the Wallet"></span>
                 </label>
                </th>
                <td>
                 <fieldset>
                  <legend class="screen-reader-text"><span>Wallet Symbol Display Position</span></legend>
                  <label><input type="radio" name="rimplenet_wallet_symbol_position" value="left" <?php echo ($wallet_symbol_position=='left' OR empty($wallet_symbol_position)) ? "checked" : ""; ?> > 
                  <span class="">Left - (Suitable for Fiat Wallet)  </span></label> <br>
                  
                  <label><input type="radio" name="rimplenet_wallet_symbol_position" value="right" <?php echo ($wallet_symbol_position=='right') ? "checked" : ""; ?> > 
                  <span class="">Right - (Suitable for crytocurrency wallet) </span></label> <br>
                 </fieldset>
                </td>
            </tr>
            <tr>
                <th><label for="rimplenet_wallet_decimal"> 
                     Wallet Decimal 
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Wallet Decimal of the Wallet, it sometimes 2 is for fiat currecny wallet & 6 or more for cryptocurrency wallet"></span>
                </label></th>
                <td><input name="rimplenet_wallet_decimal" id="rimplenet_wallet_decimal" type="number" value="<?php echo $wallet_decimal; ?>" placeholder="2 or 6" class="regular-text" required style="width:100%;max-width: 400px; height: 40px;"> </td>
            </tr>
            <tr>
                <th>
                 <label for="rimplenet_min_withdrawal_amount"> 
                     Wallet Minimum Single Withdrawal Amount 
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Wallet Minimum Single Withdrawal Amount"></span>
                 </label>
                </th>
                <td><input name="rimplenet_min_withdrawal_amount" id="rimplenet_min_withdrawal_amount" type="number" value="<?php echo $min_withdrawal_amount; ?>" placeholder="100.00" class="regular-text" style="width:100%;max-width: 400px; height: 40px;"> </td>
            </tr>
            <tr>
                <th>
                 <label for="rimplenet_max_withdrawal_amount"> 
                     Wallet Maximum Single Withdrawal Amount 
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Wallet Minimum Single Withdrawal Amount"></span>
                 </label>
                </th>
                <td><input name="rimplenet_max_withdrawal_amount" id="rimplenet_max_withdrawal_amount" type="number" value="<?php echo $max_withdrawal_amount; ?>" placeholder="9999.99" class="regular-text" style="width:100%;max-width: 400px; height: 40px;"> </td>
            </tr>
            <?php 
                if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
                //Only show below fields if Woocommerce is Installed and Activated
            ?>
            
            <tr>
                <th colspan="2"><h2>WOOCOMMERCE INTEGRATIONS</h2> </th>  
            </tr>
            <tr>
                <th>
                 <label for="enable_as_woocommerce_product_payment_wallet"> 
                     Enable Wallet for Woocommerce Product Payment
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Wallet can be used to make payment just like other Woocommerce Payment Gateways / Processors"></span>
                 </label>
                </th>
                <td>
                 <fieldset>
                  <legend class="screen-reader-text"><span>Include as Woocommerce Product Payment Wallet</span></legend>
                  <label><input type="radio" name="enable_as_woocommerce_product_payment_wallet" value="yes" <?php echo ($enable_as_woocommerce_product_payment_wallet=='yes' OR empty($enable_as_woocommerce_product_payment_wallet)) ? "checked" : ""; ?> > 
                  <span class="">Yes Enable - (This will allow this wallet to be used for Woocommerce Product Payment Wallet)  </span></label> <br>
                  
                  <label><input type="radio" name="enable_as_woocommerce_product_payment_wallet" value="no" <?php echo ($enable_as_woocommerce_product_payment_wallet=='no') ? "checked" : ""; ?> > 
                  <span class="">No, Disable - (This will not allow this wallet to be used for Woocommerce Product Payment Wallet) </span></label> <br>
                 </fieldset>
                </td>
            </tr>
            <tr>
                <th>
                 <label for="include_in_woocommerce_currency_list"> 
                     Include as Woocommerce Currencies
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="This will show up as one of the supported woocommerce currencies, however some woocommerce payment processors might not still recognize it"></span>
                 </label>
                </th>
                <td>
                 <fieldset>
                  <legend class="screen-reader-text"><span>Include in Woocommerce Currencies</span></legend>
                  <label><input type="radio" name="include_in_woocommerce_currency_list" value="yes" <?php echo ($include_in_woocommerce_currency_list=='yes') ? "checked" : ""; ?> > 
                  <span class="">Yes Include - (This will show in Woocommerce Currency List, be careful as some payment processors may not recognized it.)  </span></label> <br>
                  
                  <label><input type="radio" name="include_in_woocommerce_currency_list" value="no" <?php echo ($include_in_woocommerce_currency_list=='no' OR empty($include_in_woocommerce_currency_list)) ? "checked" : ""; ?> > 
                  <span class="">No, Don't Include - (This will not show in Woocommerce Currency List) </span></label> <br>
                 </fieldset>
                </td>
            </tr>
            <?php
                }
            ?>
            <tr>
                <th>
                 <label for="create_woocommerce_fund_product"> 
                     Create Fund Wallet Payment Product 
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="This will allow users to fund your wallet via the created product"></span>
                 </label>
                </th>
                <td>
                    <input name="create_woocommerce_fund_product" id="create_woocommerce_fund_product" type="checkbox" value="yes" class="regular-text" style="max-width: 25px;" checked >
                    Tick to Create ~ (however you need to install & activate woocommerce, then setup woocommerce payment processors for wallet funding to work)
                    </td>
            </tr>
            <tr>
                <th>
                 <label for="create_wallet_balance_page"> 
                     Create Wallet Balance Page 
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="If created, users will see their wallet balance when they visit this page, alternatively you can use wallet balance shortcode of this wallet to display user wallet balance anywhere"></span>
                 </label>
                </th>
                <td>
                    <input name="create_wallet_balance_page" id="create_wallet_balance_page" type="checkbox" value="yes" class="regular-text" style="max-width: 25px; "checked >
                    Tick to Create
                </td>
            </tr>
            <tr>
                <th>
                 <label for="rimplenet_wallet_type"> 
                     Wallet Type 
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="This will allow users to fund your wallet via the created product"></span>
                 </label>
                </th>
                <td>
                   <select name="rimplenet_wallet_type" id="rimplenet_wallet_type" style="width: 100%;max-width: 400px; height: 40px;" required>
                         <option value=""> Select Wallet Type  </option> 
                         <option value="fiat" <?php echo ($wallet_type=='fiat' OR empty($wallet_type)) ? "selected" : ""; ?>> Fiat Currency Wallet </option> 
                         <option value="crypto" <?php echo ($wallet_type=='crypto') ? "selected" : ""; ?>> Cryptocurrency Wallet </option> 
                      </select>
                </td>
            </tr>
            <tr>
               <th><label for="rimplenet_wallet_note"> Wallet Note </label></th>
               <td>
                  <textarea id="rimplenet_wallet_note" name="rimplenet_wallet_note" rows="4" placeholder="Leave note here maybe about what you will use wallet for" style="width:100%;max-width:400px;"><?php echo $wallet_note; ?></textarea>
              </td>
            </tr>
            
            </tbody>
        </table>
        <?php
        
          $admin_post_page_type = sanitize_text_field($_GET["rimplenettransaction_type"]);
          if($admin_post_page_type=='rimplenet-wallets' OR has_term('rimplenet-wallets', 'rimplenettransaction_type',$wallet_post_id) ){
        ?>
        
          <input name="rimplenettransaction_type" id="rimplenettransaction_type" type="hidden" value="rimplenet-wallets">
           
        <?php
          }
         ?>