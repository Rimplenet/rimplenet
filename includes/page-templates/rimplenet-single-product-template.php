<?php 
global $post, $wpdb;;
$product = wc_get_product( $post->ID );
$product_id = $post->ID ;

$price = $product->get_price();
$min_price = get_post_meta($product_id, 'rimplenet_product_min_price', true);
$max_price = get_post_meta($product_id, 'rimplenet_product_max_price', true);
if(empty($min_price)){$min_price = $price;}

if(empty($min_price) AND empty($max_price)){
    $min_price = $price;
    $max_price = $price; 
}

global $current_user;
wp_get_current_user();
$userinfo = wp_get_current_user();
$user_id = $userinfo->ID; 

?>

<?php get_header(); ?>



<div class="clearfix"></div><br>
<div class='rimplenetmlm' style="max-width:600px;margin:auto;">
<div class="rimplenet-single-product">
    
    <?php
    
    if(isset($_POST['buy_woocommerce_product_field']) && wp_verify_nonce($_POST['buy_woocommerce_product_field'], 'buy_woocommerce_product_action' )) {
          
          global $wpdb;
        
        
          $amount = sanitize_text_field(trim($_POST['amount']));
          $product_quantity = sanitize_text_field(trim($_POST['product_quantity']));
          if($product->is_sold_individually( )){$product_quantity = 1;}
          $payment_processor = sanitize_text_field(trim($_POST['payment_processor']));
          
                  
            if (empty($payment_processor)) {
              $status_error = "Payment processor is empty";
            }
            
            elseif (empty($product_quantity ) ) {
             $status_error = "Product Quantity is empty";
            }
            elseif (empty($amount)) {
             $status_error = "Amount is empty";
            }
            elseif (is_numeric($min_price) AND $amount<$min_price) {
             $status_error = "Amount is too small, amount should not be less than ".$min_price;
             }
            elseif(is_numeric($max_price) AND $amount>$max_price){
             $status_error = "Amount is too much, amount should not be greater than ".$max_price;   
            }
            elseif ($userinfo === false or empty($user_id) or $user_id==0) {
             $status_error = "Invalid request, you are not logged in or session expired, please login first";
            }
            else{   
                
             
                
                    $quanity_to_be_bought = $product_quantity;
                    $productId = $post->ID ;
            
                     
                    
                    $address = array(
                     'first_name' => $userinfo->user_firstname,
                     'last_name'  => $userinfo->user_lastname,
                     'email'      => $userinfo->user_email,  
                   );
            
                     $product_cart_id = WC()->cart->generate_cart_id($productId );
                    $cart_item_key = WC()->cart->find_product_in_cart( $product_cart_id );
                    if ( $cart_item_key ) WC()->cart->remove_cart_item( $cart_item_key );
                    WC()->cart->add_to_cart( $productId, $quanity_to_be_bought );// add to cart first
                    $order = wc_create_order(array('customer_id'=>$user_id));  
                    $order->add_product( wc_get_product($productId ), $quanity_to_be_bought);
                    
                    $order->set_address( $address, 'billing' );
                    $order->set_address( $address, 'shipping' );
                    
                    $rimplenet_order_payment_currency = get_post_meta($product_id, 'rimplenet_order_payment_currency', true);
                    if(!empty($rimplenet_order_payment_currency)){
                      $order->set_currency($rimplenet_order_payment_currency);
                    }
                    foreach( $order->get_items() as $item_id => $item ){
                        $new_product_price = $amount; // A static replacement product price
                        $product_quantity = (int) $item->get_quantity(); // product Quantity
                    
                        // The new line item price
                        $new_line_item_price = $new_product_price * $product_quantity;
                    
                        // Set the new price
                        $item->set_subtotal( $new_line_item_price ); 
                        $item->set_total( $new_line_item_price );
                    
                        // Make new taxes calculations
                        $item->calculate_taxes();
                    
                        $item->save(); // Save line item data
                    }
            
                    $order->calculate_totals();
            
            
                    $order_id = $order->get_id();
            
                    $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
            
                    update_post_meta( $order_id, '_payment_method', $payment_processor );
                    update_post_meta( $order_id, '_payment_method_title', $available_gateways[$payment_processor ]->title);
            
                  
                    // Store Order ID in session so it can be re-used after payment failure
                    WC()->session->order_awaiting_payment = $order_id;
                  
                  /*
                    // Process Payment
                    $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
                    $result = $available_gateways[ 'ideal' ]->process_payment( $order->id );
                      
                      
                      // Redirect to success/confirmation/payment page
                      if ( $result['result'] == 'success' ) {
            
                          $result = apply_filters( 'woocommerce_payment_successful_result', $result, $order->id );
            
                          wp_redirect( $result['redirect'] );
                          exit;
            
            
                      }
            
                      */
            
                    
                    $redirection_page = get_post_meta($product_id, 'rimplenet_order_redirection_page', true);
                    if($redirection_page=='PAYMENT_PAGE'){
                      $pay_now_url = $order->get_checkout_payment_url();
                    }
                    elseif($redirection_page=='CHECKOUT_PAGE'){
                      $pay_now_url = wc_get_checkout_url(); 
                    }
                    else{
                      $pay_now_url = wc_get_cart_url();
                    }
                    
                    wp_redirect( $pay_now_url);
                    $pay_now_link = '<a href="'.esc_url( $pay_now_url ).'"> Click here to make payment</a>';
                    
                    $status_success =   'Order placed successfully.'.$pay_now_link;
                   }
       
                         
        } 
               
 
    ?>
    
<div class="rimplenet-status-msg">
      <center>
          
          <?php

                 if (!empty($status_success)) {
               
              ?>

              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong> SUCCESS: </strong> <?php echo $status_success; ?>
              </div>
              <?php
                }


           ?>
           
             
          <?php

                 if (!empty($status_error)) {
               
              ?>

              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong> ERROR: </strong> <?php echo $status_error; ?>
              </div>
              <?php
                }


           ?>
              
          </center>
    </div>

  <form action="" method="POST" class="rimplenet-woocommerce-product-form-1" id="rimplenet-woocommerce-product-form-1" > 
  
     <table class="table table-responsive-md rimplenet-table" id="table-rimplenet-product-desc-<?php echo $product_id; ?>">
	      <thead class="thead-dark">
	        <tr>
	          <th scope="col">Name</th>
	          <th scope="col">Description</th>
	          <th scope="col">Price</th>
	        </tr>
	      </thead>
	      <?php
	      
            $wallet_obj = new Rimplenet_Wallets();
            
            $all_rimplenet_wallets = $wallet_obj->getWallets();
	        $display_price_in = get_post_meta($product_id, 'convert_and_display_price_in', true);
	        if(!empty($display_price_in)){
	            $wallet_id = $display_price_in;
	        }
	        else{
	            
	            $wallet_id = 'woocommerce_base_cur';
	        }

            $wallet_name = $all_rimplenet_wallets[$wallet_id]['name'];
            $wallet_symbol = $all_rimplenet_wallets[$wallet_id]['symbol'];
            $wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];
            $step = number_format(0, $wallet_decimal-1, '.', '')."1"; // for e.g it will make the step like 0.01 for usd on amount input

	        if(is_numeric($min_price) AND is_numeric($max_price)){
                $price_formatted_disp = $wallet_symbol.number_format($min_price,$wallet_decimal).' - '.$wallet_symbol.number_format($max_price,$wallet_decimal);
            }
            
            else{
                $price_formatted_disp = $wallet_symbol.number_format($price,$wallet_decimal);
                $min_price = $price;
            }
	      
	      ?>
	      <tbody>
	        <tr>
	          <th scope="row"> <?php echo $product->get_title(); ?> - #<?php echo $post->ID ?></th>
	          <td> <?php echo apply_filters( 'woocommerce_short_description', get_the_excerpt() ); ?></th>
	          <td> <?php echo $price_formatted_disp; ?> </td>
	        </tr>
	      </tbody>
		      
	</table>
    <?php
    if ( $product->is_sold_individually( ) ) {
    ?>
    <div class="row rimplenet-product-single-amount" id="rimplenet-product-single-amount-<?php echo $product_id; ?>">
     <div class="col-lg-12">
     <label for="amount"> <strong> Amount in <?php echo $wallet_name.' - ['.$wallet_symbol.']'; ?> </strong> </label>
      <input name="amount" id="rimplenet-input-amount" class="rimplenet-input rimplenet-input-amount" placeholder="Amount" type="number" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" step="<?php echo $step; ?>" value="<?php echo $product->get_price(); ?>" required="">       
     </div>
   </div> 
        
    <?php    
        
    }
    else{
    ?>
    
    
   <div class="row rimplenet-product-amount-qnt" id="rimplenet-product-amount-qnt-<?php echo $product_id; ?>">
    <div class="col-lg-6">
     <label for="amount"> <strong> Amount in <?php echo $wallet_name.' - ['.$wallet_symbol.']'; ?> </strong> </label>
      <input name="amount" id="rimplenet-input-amount" class="rimplenet-input rimplenet-input-amount" placeholder="Amount" type="number" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" step="<?php echo $step; ?>"  value="<?php echo $product->get_price(); ?>" required="">       
    </div>
    
    <div class="col-lg-6">
     <label for="product_quantity"> <strong> Quantity</strong> </label>
      <input name="product_quantity" id="rimplenet-input-product-quantity" class="rimplenet-input rimplenet-input-product-quantity" placeholder="Quantity" type="number" min="1" value="1" required="">       
    </div>
   </div>
    <?php
    }
    ?>
    <div class="clearfix"></div><br>

    <div class="clearfix"></div><br>
    <div class="row rimplenet-product-payment-processor" id="rimplenet-product-payment-processor-<?php echo $product_id; ?>">
    <div class="col-lg-12">
      <label for="payment_processor"> <strong> Select Payment Processor </strong> </label>
     
        <select name="payment_processor" id="rimplenet-select-payment-processor" class="rimplenet-select rimplenet-select-payment-processor" required="">
            <?php
            if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){//if woocommerce is activated
            
            $payment_gateways_obj = new WC_Payment_Gateways(); 
            
            $payment_gateways = $payment_gateways_obj->payment_gateways();
            
            
            
            
                if( $payment_gateways) {
                  echo '<option value=""> Select Payment Processor</option> ';
                    $enabled_gateways = [];
                    
                    foreach( $payment_gateways as $gateway => $gateway_details ) {
                        /*
                        if($gateway_details->enabled == 'yes' ) { $enabled_gateways[] = $gateway;
                          
                          $disp = '';
                          $dis_info = '';
                        }
                        else{
                          $disp = 'disabled';
                          $dis_info = ' - DISABLED';
                        }
                        */
                      
                      if($gateway_details->enabled == 'yes' ) {
                        $enabled_gateways[] = $gateway;
                        ?>
                        
                      <option value="<?php echo $gateway; ?>" data-desc="<?php echo $gateway_details->description; ?>" title="<?php echo $gateway_details->description; ?>">
                        <?php echo $gateway_details->title?>
                      </option>
                      
                    <?php
                      }
                      
                        
                    }
                    
                    if (empty($enabled_gateways)) {
                        echo '<option value="" disabled>Please Inform Adminstrator to Setup / Enable Payment Gateways @ Woocommerce</option> ';
                      }
             
                  }
                 else{
                     echo '<option value="" disabled>Please Inform Adminstrator to Set Payment Gateways @ Woocommerce</option> ';
                 }
            
            }
            else{
            echo '<option value="" disabled>Please Inform Adminstrator to Activate Woocommerce Plugin</option> ';
                
            }
            
            ?>
                
        </select>                     
      
    </div>
  </div>
    
    

        

        <?php wp_nonce_field( 'buy_woocommerce_product_action', 'buy_woocommerce_product_field' ); ?>
        <div class="clearfix"></div>
        <br>
    
      <div class="col-lg-12 rimplenet-product-submit-btn" id="rimplenet-product-submit-btn-<?php echo $product_id; ?>">
        <center>
          <input class="rimplenet-button rimplenet-product-submit-btn" id="rimplenet-product-submit-btn-<?php echo $product_id; ?>" type="submit" value="PROCEED TO PAYMENT">
        </center>
      </div>
      
 </form>
 
 </div>
</div>
<div class="clearfix"></div><br>

<?php get_footer(); ?>