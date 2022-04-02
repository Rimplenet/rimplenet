<?php

if(isset( $_POST['rimplenet_referral_settings_form_submitted'] ) || wp_verify_nonce( $_POST['rimplenet_referral_settings_form_submitted'], 'rimplenet_referral_settings_form_submitted' ) )  {

$rimplenet_rules_to_user_when_their_downline_makes_woo_order = sanitize_text_field( $_POST['rimplenet_rules_to_user_when_their_downline_makes_woo_order'] );
$rimplenet_rules_to_user_when_their_downline_makes_woo_order_instance = sanitize_textarea_field( $_POST['rimplenet_rules_to_user_when_their_downline_makes_woo_order_instance'] );
$rimplenet_rules_to_user_when_their_downline_makes_woo_order_product_quantity_instance = sanitize_text_field( $_POST['rimplenet_rules_to_user_when_their_downline_makes_woo_order_product_quantity_instance'] );

update_option( 'rimplenet_rules_to_user_when_their_downline_makes_woo_order', $rimplenet_rules_to_user_when_their_downline_makes_woo_order );
update_option( 'rimplenet_rules_to_user_when_their_downline_makes_woo_order_instance', $rimplenet_rules_to_user_when_their_downline_makes_woo_order_instance );
//update_option( 'rimplenet_rules_to_user_when_their_downline_makes_woo_order_product_quantity_instance', $rimplenet_rules_to_user_when_their_downline_makes_woo_order_product_quantity_instance );


 echo '<div class="updated">
            <p>Your Settings have been saved successfully</p>
        </div> ';

}


$rimplenet_rules_to_user_when_their_downline_makes_woo_order = get_option( 'rimplenet_rules_to_user_when_their_downline_makes_woo_order','');

$rimplenet_rules_to_user_when_their_downline_makes_woo_order_instance = get_option( 'rimplenet_rules_to_user_when_their_downline_makes_woo_order_instance','1');

$rimplenet_rules_to_user_when_their_downline_makes_woo_order_product_quantity_instance = get_option( 'rimplenet_rules_to_user_when_their_downline_makes_woo_order_product_quantity_instance','');
if($rimplenet_rules_to_user_when_their_downline_makes_woo_order_product_quantity_instance=='yes'){$product_order_instance = 'yes';}

$input_width = 'width:95%';

?>



<div class="rimplenet_admin_div" style="<?php echo $input_width; ?>">



<h2>REFERRAL SETTINGS ON WOOCOMMERCE ORDERS</h2>
  <form method="POST">

    <table class="form-table">
        <tbody>

            
            <tr>
                <td colspan="3"><h2>WOOCOMERCE PRODUCT ORDER SETTINGS</h2> </td>  
            </tr>
            
             <tr>
                <th><label for="rimplenet_rules_to_user_when_their_downline_makes_woo_order"> Rules to apply to {USER} when their referrals orders Woocommerce Product </label></th>
                <td>
                	<textarea name="rimplenet_rules_to_user_when_their_downline_makes_woo_order" id="rimplenet_rules_to_user_when_their_downline_makes_woo_order" style="<?php echo $input_width; ?>"><?php echo  $rimplenet_rules_to_user_when_their_downline_makes_woo_order; ?></textarea>
            	</td>
            </tr>
            
           
            <tr>
                <th>
                  <label for="rimplenet_rules_to_user_when_their_downline_makes_woo_order_instance"> Rules Application Instance on Woocommerce Order</label>
                </th>
                <td>
                    <input type="number" name="rimplenet_rules_to_user_when_their_downline_makes_woo_order_instance" id="rimplenet_rules_to_user_when_their_downline_makes_woo_order_instance" value="<?php echo  $rimplenet_rules_to_user_when_their_downline_makes_woo_order_instance; ?>" min="0" style="<?php echo $input_width; ?>" class="regular-text" /><br>
                    <span class="description">
                    Input 0 to apply rules for every new order, if you specify a number for e.g  3, this rules will apply to {USER}'s SPONSOR when their downlines makes wocoomerce order but only on the first 3 orders </span>
                </td>
               </tr>
            
            <!--
            <tr>
            <th scope="row">Rules Application Instance on Woocommerce Product Quantity(order by ref downlines)</th>
            <td> 
             <fieldset>
                <legend class="screen-reader-text"><span>Rules Application Instance on Woocommerce Product Quantity(order by ref downline)</span></legend>
                <label for="rimplenet_rules_to_user_when_their_downline_makes_woo_order_product_quantity_instance">
                <input name="rimplenet_rules_to_user_when_their_downline_makes_woo_order_product_quantity_instance"  id="rimplenet_rules_to_user_when_their_downline_makes_woo_order_product_quantity_instance" type="checkbox" value="yes" <?php if($product_order_instance == 'yes'){echo 'checked';} ?>>
            	TICK to apply rules for each quantity separately when linked product is in order, UNTICK to apply rules only once ignoring if product quantity > 1</label>
             </fieldset>
            </td>
            </tr>
            -->
            
            
           
           

        </tbody>
    </table>
     
    <input type="hidden" name="rimplenet_referral_settings_form_submitted" value="true" />
    <?php wp_nonce_field( 'rimplenet_referral_settings_nonce_field', 'rimplenet_referral_settings_nonce_field' ); ?>
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="SAVE SETTINGS">
    </p>
  </form>
</div>