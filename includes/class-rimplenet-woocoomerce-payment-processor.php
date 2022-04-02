<?php

defined( 'ABSPATH' ) or exit;


// Make sure WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}


/**
 * Add the gateway to WC Available Gateways
 * 
 * @since 1.0.0
 * @param array $gateways all available WC gateways
 * @return array $gateways all WC gateways + rimplenet gateway
 */
function wc_rimplenet_wallets_add_to_gateways( $gateways ) {
	$gateways[] = 'WC_Gateway_rimplenet_wallets';
	return $gateways;
}
add_filter( 'woocommerce_payment_gateways', 'wc_rimplenet_wallets_add_to_gateways' );


/**
 * Adds plugin page links
 * 
 * @since 1.0.0
 * @param array $links all plugin links
 * @return array $links all plugin links + our custom links (i.e., "Settings")
 */
function wc_rimplenet_wallets_gateway_plugin_links( $links ) {

	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=rimplenet_wallets_gateway' ) . '">' . __( 'Configure', 'rimplenet' ) . '</a>'
	);

	return array_merge( $plugin_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wc_rimplenet_wallets_gateway_plugin_links' );


add_action( 'plugins_loaded', 'wc_rimplenet_wallets_gateway_init', 11 );

function wc_rimplenet_wallets_gateway_init() {

	class WC_Gateway_rimplenet_wallets extends WC_Payment_Gateway {

		/**
		 * Constructor for the gateway.
		 */
		public function __construct() {
		    
		    $this->current_user = wp_get_current_user();
	  
			$this->id                 = 'rimplenet_wallets_gateway';
			$this->icon               = apply_filters('woocommerce_rimplenet_wallets_icon', '');
			$this->has_fields         = true;
			$this->method_title       = __( 'Rimplenet Wallets', 'rimplenet' );
			$this->method_description = __( 'Allows Payments from Rimplenet Wallets', 'rimplenet' );
			$this->supports = array(
		                'products');
 
		  
			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();
		  
			// Define user set variables
			$this->title        = $this->get_option( 'title' );
			$this->description  = $this->get_option( 'description' );
			$this->instructions = $this->get_option( 'instructions', $this->description );
			$this->testmode =  $this->get_option( 'testmode' );
			$this->enabled = $this->get_option( 'enabled' );
		  
			// Actions
			add_action( 'woocommerce_update_options_payment_gateways_'. $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
		  
			// Customer Emails
			add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
		}
	
	
		/**
		 * Initialize Gateway Settings Form Fields
		 */
		public function init_form_fields() {
	  
			$this->form_fields = apply_filters( 'wc_rimplenet_wallets_form_fields', array(
		  
				'enabled' => array(
					'title'   => __( 'Enable/Disable', 'rimplenet' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable Rimplenet Wallets Payment', 'rimplenet' ),
					'default' => 'yes'
				),
				
				'title' => array(
					'title'       => __( 'Title', 'rimplenet' ),
					'type'        => 'text',
					'description' => __( 'This controls the title for the payment method the customer sees during checkout.', 'rimplenet' ),
					'default'     => __( 'Rimplenet Wallets Payment', 'rimplenet' ),
					'desc_tip'    => true,
				),
				
				'description' => array(
					'title'       => __( 'Description', 'rimplenet' ),
					'type'        => 'textarea',
					'description' => __( 'Payment method description that the customer will see on your checkout.', 'rimplenet' ),
					'default'     => __( 'Pay with your Rimplenet Wallets.', 'rimplenet' ),
					'desc_tip'    => true,
				),
				
				'testmode' => array(
					'title'       => 'Test mode',
					'label'       => 'Enable Test Mode',
					'type'        => 'checkbox',
					'description' => 'Place the payment gateway in test mode, user funds will not be deducted even on successful payments.',
					'default'     => 'no',
					'desc_tip'    => true,
				),
				
				'instructions' => array(
					'title'       => __( 'Success Payment Message', 'rimplenet' ),
					'type'        => 'textarea',
					'description' => __( 'Instructions that will be shown when Payments is successful.', 'rimplenet' ),
					'default'     => __( '<h2><font color="green">Your payments with Account Balance is successful.</font></h2>', 'rimplenet' ),
					'desc_tip'    => true,
				),
			) );
		}
	  public function payment_fields() {
 
	// ok, let's display some description before the payment form
	if ( $this->description ) {
		// you can instructions for test mode, I mean test card numbers etc.
		if ( $this->testmode=='yes') {
			$this->description .= ' TEST MODE ENABLED. In test mode, your payment will always be successful even with zero balance.';
			$this->description  = trim( $this->description );
		}
		
		// display the description with <p> tags etc.
		echo wpautop( wp_kses_post( $this->description ) );
	 }
 
	// I will echo() the form, but you can close PHP tags and print it directly in HTML

 
	// Add this action hook if you want your custom payment gateway to support it
	do_action( 'rimplenet_wallets_payment_form_start', $this->id );
 		

           $wallet_obj = new Rimplenet_Wallets();
           $wallets = $wallet_obj->getWallets();
           $wallet_id = 'woocommerce_base_cur';
           $dec = $wallets[$wallet_id]['decimal'];
           $symbol = $wallets[$wallet_id]['symbol'];
          
           $bal = $wallet_obj->get_withdrawable_wallet_bal($this->current_user->ID,$wallet_id);
           $balance = $symbol.number_format($bal,$dec);
           
           
           $wallet_disp = $wallets[$wallet_id]['name'].' - ('.$balance.')';
           
       ?>
       <div class="row">
        <div class="col-lg-12">
            
            <label for="rimplenet_checkout_wallet"> <strong> <?php echo __('Select Wallet', 'rimplenet' );  ?></strong> </label>
            <select name="rimplenet_checkout_wallet" id="rimplenet-select-checkout-wallet" class="rimplenet-select rimplenet-select-checkout-wallet" required="">
                <option value="woocommerce_base_cur" title="<?php echo __('Rimplenet Wallet '.$wallet_disp, 'rimplenet' ); ?>">
                    <?php echo __($this->title.' - '.$wallet_disp, 'rimplenet' ); ?> 
                </option>                       
            </select>                     
          
        </div>
      </div>
  
  <?php

	do_action( 'rimplenet_wallets_payment_form_end', $this->id );
 
}
public function validate_fields(){
   if( !is_user_logged_in() ) {
		wc_add_notice(  'Cannot retrieved wallet balance, please login First', 'error' );
		return false;
	}

	return true;
 
}
	
		/**
		 * Output for the order received page.
		 */
		public function thankyou_page() {
			if ( $this->instructions ) {
				echo wpautop( wptexturize( $this->instructions ) );
			}
		}
	
	
		/**
		 * Add content to the WC emails.
		 *
		 * @access public
		 * @param WC_Order $order
		 * @param bool $sent_to_admin
		 * @param bool $plain_text
		 */
		public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
		
			if ( $this->instructions && ! $sent_to_admin && $this->id === $order->payment_method && $order->has_status( 'on-hold' ) ) {
				echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
			}
		}
	
	
		/**
		 * Process the payment and return the result
		 *
		 * @param int $order_id
		 * @return array
		 */
		public function process_payment( $order_id ) {
	
			   $order = wc_get_order( $order_id );
			
               $wallet_obj = new Rimplenet_Wallets();
               $wallets = $wallet_obj->getWallets();
               $wallet_id = 'woocommerce_base_cur';
               $dec = $wallets[$wallet_id]['decimal'];
               $symbol = $wallets[$wallet_id]['symbol'];
               
               $user_id = $this->current_user->ID;
              
               $user_bal = $wallet_obj->get_withdrawable_wallet_bal($user_id, $wallet_id);
               $balance_disp = $symbol.number_format($user_bal,$dec);
            
        	if($user_bal<$order->get_total() AND $this->testmode!='yes') {
        	    $err_msg = 'Amount in wallet ('.$balance_disp.') is not enough for payment of order total amount - '.get_woocommerce_currency_symbol().$order->get_total();
        		wc_add_notice(  $err_msg, 'error' );
        		
			    $order->add_order_note( 'Payment with '.$this->title.' failed because of insufficient funds ('.$balance_disp.') in user balance' , true );
        		return false;
        	}
        	
        	
        	if($this->testmode=='yes') {//if testmode is set
    			$order->add_order_note( 'Test Payment initiated with '.$this->title.'- '.$wallets[$wallet_id]['name'], true );
    			update_post_meta($order_id,'rimplenet_test_payment','yes');
			}
        	else{   
            	$amount = $order->get_total()*-1;
            	$note = 'Payment for order - #'.$order_id;
            	$wallet_obj->add_user_mature_funds_to_wallet($user_id,$amount,$wallet_id, $note);
        	}
        	
        	// some notes to customer (replace true with false to make it private)
			$order->add_order_note( 'Payment successful with '.$this->title.'- '.$wallets[$wallet_id]['name'], true );
			$order->payment_complete();
			// Reduce stock levels
			$order->reduce_order_stock();
			
			// Remove cart
			WC()->cart->empty_cart();
			
			// Return thankyou redirect
			return array(
				'result' 	=> 'success',
				'redirect'	=> $this->get_return_url( $order )
			);
		}
	
  } // end WC_Gateway_rimplenet_wallets class
}

// add the filter 
add_filter('woocommerce_get_price_html', 'woocommerce_convert_and_display_price', 10, 2 );
function woocommerce_convert_and_display_price( $price, $product ) { 
        //This functions serves 2 filters listed, parameters are not exactly the same, refer to woocommerce doc for those for more understanding.
        
        global $product;
        if(!empty($product)){
            
              $product_id = $product->get_id();
              $product_price = $price; 
            
            $wallet_obj = new Rimplenet_Wallets();
            
            $all_rimplenet_wallets = $wallet_obj->getWallets();
	        $wallet_id = 'woocommerce_base_cur';
	        $display_price_in = get_post_meta($product_id , 'convert_and_display_price_in', true);
	        if(!empty($display_price_in)){
	            $wallet_id = $display_price_in;
	        }

            $price = $product->get_price();;
            $min_price = get_post_meta($product_id, 'rimplenet_product_min_price', true);
            $max_price = get_post_meta($product_id, 'rimplenet_product_max_price', true);
            if(empty($min_price)){$min_price = $price;}
     

            $wallet_symbol = $all_rimplenet_wallets[$wallet_id]['symbol'];
            $wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];

	        if(is_numeric($min_price) AND is_numeric($max_price)){
                $price_formatted_disp = getRimplenetWalletFormattedAmount($min_price,$wallet_id).' - '.getRimplenetWalletFormattedAmount($max_price,$wallet_id);
                $disp_info = $product_price . ' ~ ('.$price_formatted_disp.')'; 
            }
            elseif(is_numeric($price) && !empty($display_price_in)){
                $price_formatted_disp = getRimplenetWalletFormattedAmount($price,$wallet_id);
                $disp_info = $product_price . ' ~ ('.$price_formatted_disp.')'; 
            }
            
            else{
                $price_formatted_disp = getRimplenetWalletFormattedAmount($price,$wallet_id);
                $disp_info = $product_price; 
            }
	      
        return $disp_info;
    }
}; 
          
// add the filter 
add_filter( 'woocommerce_cart_item_price', 'woocommerce_convert_and_display_price_cart', 10, 3 );
function woocommerce_convert_and_display_price_cart( $price_htmL, $cart_item, $cart_item_key ) { 
            
            $product_id =  $cart_item["product_id"];
            $product_price = $cart_item["data"]->price; 
            
            $wallet_obj = new Rimplenet_Wallets();
            
            $all_rimplenet_wallets = $wallet_obj->getWallets();
	        $wallet_id = 'woocommerce_base_cur';
	        $display_price_in = get_post_meta($product_id , 'convert_and_display_price_in', true);
	        if(!empty($display_price_in)){
	            $wallet_id = $display_price_in;
	        }

            $price = $product_price;
            $min_price = get_post_meta($product_id, 'rimplenet_product_min_price', true);
            $max_price = get_post_meta($product_id, 'rimplenet_product_max_price', true);
            if(empty($min_price)){$min_price = $price;}
     

            $wallet_symbol = $all_rimplenet_wallets[$wallet_id]['symbol'];
            $wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];

	        if(is_numeric($min_price) AND is_numeric($max_price)){
                $price_formatted_disp = getRimplenetWalletFormattedAmount($min_price,$wallet_id).' - '.getRimplenetWalletFormattedAmount($max_price,$wallet_id);
                $disp_info = $price_htmL. ' ~ ('.$price_formatted_disp.')'; 
            }
            elseif(is_numeric($price) && !empty($display_price_in)){
                $price_formatted_disp = getRimplenetWalletFormattedAmount($price,$wallet_id);
                $disp_info =  $price_htmL. ' ~ ('.$price_formatted_disp.')'; 
            }
            
            else{
                $price_formatted_disp = getRimplenetWalletFormattedAmount($price,$wallet_id);
                $disp_info = $price_htmL; 
            }
	      
        return $disp_info;
            
    
    return $product_id; 
};


?>