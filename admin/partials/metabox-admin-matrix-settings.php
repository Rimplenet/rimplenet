<?php
        $matrix_post_id = $meta_id->ID;
        $rimplenet_matrix_width = get_post_meta($matrix_post_id, 'width', true);
        $rimplenet_matrix_depth = get_post_meta($matrix_post_id, 'depth', true);
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
                <th><label for="rimplenet_matrix_width"> 
                     Matrix Width 
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Matrix Width is how matrix span from left to right"></span>
                </label></th>
                <td><input name="rimplenet_matrix_width" id="rimplenet_matrix_width" type="number" value="<?php echo $rimplenet_matrix_width; ?>" min="1" placeholder="3" class="regular-text" required style="width:100%;max-width: 400px; height: 40px;"> </td>
            </tr>
            <tr>
                <th><label for="rimplenet_matrix_depth"> 
                     Matrix Depth 
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Matrix depth is how matrix span from top to bottom"></span>
                </label></th>
                <td><input name="rimplenet_matrix_depth" id="rimplenet_matrix_depth" type="number" value="<?php echo $rimplenet_matrix_depth; ?>" min="1" placeholder="2" class="regular-text" required style="width:100%;max-width: 400px; height: 40px;"> </td>
            </tr>
            
            <tr>
                <th>
                 <label for="user_placement_method_in_matrix"> 
                     User Placement Method in Matrix - Methods of Placement in tree when user joins matrix
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Methods of Placement in tree when user joins matrix"></span>
                 </label>
                </th>
                <td>
                 <fieldset>
            	    <legend class="screen-reader-text"><span>Time Format</span></legend>
            	    <label><input type="radio" name="user_placement_method_in_matrix" value="first_come_first_served"  <?php echo ($user_placement_method_in_matrix=='first_come_first_served' OR empty($user_placement_method_in_matrix)) ? "checked" : ""; ?>> 
            	    <span class="">First Come First Serve - First User who joins this matrix will get his matrix structure filled up and then followed by second user till infinity</span></label> <br>
            	    
            	    <label><input type="radio" name="user_placement_method_in_matrix" value="referral_based_during_registration" <?php echo ($user_placement_method_in_matrix=='referral_based_during_registration') ? "checked" : ""; ?> > 
            	    <span class=""> Referral Based - when a user joins matrix, he will be placed under his upline (upline user should be activated and choosed during registration, else user will have his separate matrix structure) </span></label> <br>
            	
            	</fieldset>
                </td>
            </tr>
            
            <tr>
                <th>
                 <label for="create_matrix_tree_page"> 
                     Create Page to display Matrix Tree
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="If created, you and your users can view matrix structure with this page when they visit this page, alternatively you can use matrix tree shortcode to display tree anywhere"></span>
                 </label>
                </th>
                <td>
                    <input name="create_matrix_tree_page" id="create_matrix_tree_page" type="checkbox" value="yes" class="regular-text" style="max-width: 25px;" checked>
                    TICK to create page for matrix tree, you and your users can view matrix structure with this page, if UNTICK, no page is created or linked, you will need to manually include matrix shortcode
                </td>
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
                 <label for="create_matrix_entry_woocommerce_product"> 
                     Create Matrix entry Woocommerce Product 
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="if product is CREATED, this will allow users to join matrix once they buy this product, other rules before joining may still apply if any"></span>
                 </label>
                </th>
                <td>
                    <input name="create_matrix_entry_woocommerce_product" id="create_matrix_entry_woocommerce_product" type="checkbox" value="yes" class="regular-text" style="max-width: 25px;" checked >
                    Tick to Create ~ (TICK to automatically create and link the Woocommerce Product, if UNTICK and no product is linked, some settings below might not work)
                </td>
            </tr>
            <tr>
                <th>
                 <label for="rimplenet_matrix_price"> 
                     Price
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="The Product Price"></span>
                 </label>
                </th>
                <td><input name="rimplenet_matrix_price" id="rimplenet_matrix_price" type="number" value="<?php echo $matrix_product_price; ?>" placeholder="100.00" class="regular-text" style="width:100%;max-width: 400px; height: 40px;"> </td>
            </tr>
            <tr>
                <th>
                 <label for="use_rimplenet_woocommerce_template"> 
                     Use Rimplenet Template on Single Woocommerce Product Page - Lightweight  
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="if  ENABLED, it uses a simple rimplenet custom template"></span>
                 </label>
                </th>
                <td>
                    <input name="use_rimplenet_woocommerce_template" id="use_rimplenet_woocommerce_template" type="checkbox" value="yes" class="regular-text" style="max-width: 25px;" checked >
                    TICK to use the Rimplenet Template, if UNTICK the default theme woocommerce single product template will be used 
                </td>
            </tr>
            <tr>
                <th>
                 <label for="rimplenet_order_redirection_page"> 
                     Product Button Purchase Redirection Page (Works only when Rimplenet Product Template is enabled) 
                     <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="The page to redirect to on product purchase, this cuts out the woocommerce long process"></span>
                 </label>
                </th>
                <td>
                   <select name="rimplenet_order_redirection_page" id="rimplenet_order_redirection_page" style="width: 100%;max-width: 400px; height: 40px;" required>
                       <option value="PAYMENT_PAGE" <?php echo ($order_redirection_page=='PAYMENT_PAGE' OR empty($order_redirection_page)) ? "selected" : ""; ?>> Payment Page </option> 
                       <option value="CHECKOUT_PAGE"<?php echo ($order_redirection_page=='CHECKOUT_PAGE') ? "selected" : ""; ?>> Checkout Confirm Page </option> 
                       <option value="CART_PAGE" <?php echo ($order_redirection_page=='CART_PAGE') ? "selected" : ""; ?>> Default Woocommerce Cart Page </option> 
                    </select>
                </td>
            </tr>
            
            <?php
                }
            ?>
            <tr>
                <th colspan="2"><h2>RULES</h2> </th>  
            </tr>
            <tr>
               <th>
                 <label for="rimplenet_rules_before_matrix_entry">  
                  Rules to Achieve before User Qualifies to join this Matrix  
                 <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Rules to Achieve before User Qualifies for this Matrix"></span>
                 </label>
               </th>
               <td>
                  <textarea id="rimplenet_rules_before_matrix_entry" name="rimplenet_rules_before_matrix_entry" rows="4" placeholder="Insert your rules here or contact Rimplenet Support for your rules if you have premium subscription" style="width:100%;"><?php echo $rimplenet_rules_before_matrix_entry; ?></textarea>
              </td>
            </tr>
            <tr>
               <th>
                 <label for="rimplenet_rules_inside_matrix">  
                  Rules to apply to Users actively in Matrix   
                 <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Rules to apply to Users actively in Matrix"></span>
                 </label>
               </th>
               <td>
                  <textarea id="rimplenet_rules_inside_matrix" name="rimplenet_rules_inside_matrix" rows="4" placeholder="Insert your rules here or contact Rimplenet Support for your rules if you have premium subscription" style="width:100%;"><?php echo $rimplenet_rules_before_matrix_entry; ?></textarea>
              </td>
            </tr>
            <tr>
               <th>
                 <label for="rimplenet_rules_after_matrix_complete">  
                  Rules to Apply to User after Matrix completion   
                 <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Rules to Apply to User after Matrix completion"></span>
                 </label>
               </th>
               <td>
                  <textarea id="rimplenet_rules_after_matrix_complete" name="rimplenet_rules_after_matrix_complete" rows="4" placeholder="Insert your rules here or contact Rimplenet Support for your rules if you have premium subscription" style="width:100%;"><?php echo $rimplenet_rules_before_matrix_entry; ?></textarea>
              </td>
            </tr>
            
            </tbody>
        </table>
        <?php
        
          $admin_post_page_type = sanitize_text_field($_GET["rimplenettransaction_type"]);
          if($admin_post_page_type=='rimplenet-mlm-matrix' OR has_term('rimplenet-mlm-matrix', 'rimplenettransaction_type',$matrix_post_id ) ){
        ?>
        
          <input name="rimplenettransaction_type" id="rimplenettransaction_type" type="hidden" value="rimplenet-mlm-matrix">
           
        <?php
          }
         ?>