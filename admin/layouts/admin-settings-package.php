<?php

$PACKAGE_CAT_NAME = 'RIMPLENET MLM PACKAGES';


if(isset( $_POST['rimplenet_package_form_submitted'] ) || wp_verify_nonce( $_POST['rimplenet_package_settings_nonce_field'], 'rimplenet_package_settings_nonce_field' ) )  {

$rimplenet_package_name_form = sanitize_text_field( $_POST['rimplenet_package_name_form'] );
$rimplenet_package_desc_form = sanitize_textarea_field( $_POST['rimplenet_package_desc_form'] );
$rimplenet_package_price_form = sanitize_text_field( $_POST['rimplenet_package_price_form'] );
$rimplenet_package_min_price_form = sanitize_text_field( $_POST['rimplenet_package_min_price_form'] );
$rimplenet_package_max_price_form = sanitize_text_field( $_POST['rimplenet_package_max_price_form'] );


$rimplenet_rules_before_package_entry_form = sanitize_textarea_field( $_POST['rimplenet_rules_before_package_entry_form'] );
$rimplenet_rules_inside_package_form = sanitize_textarea_field( $_POST['rimplenet_rules_inside_package_form'] );
$rimplenet_rules_after_package_complete_form = sanitize_textarea_field( $_POST['rimplenet_rules_after_package_complete_form'] );


$rimplenet_rules_inside_package_and_linked_product_ordered_form = sanitize_textarea_field( $_POST['rimplenet_rules_inside_package_and_linked_product_ordered_form'] );
$use_rimplenet_woocommerce_template = sanitize_text_field( $_POST['use_rimplenet_woocommerce_template'] );
    if($use_rimplenet_woocommerce_template==true){
       $use_rimplenet_woocommerce_template='yes'; 
    }


$create_and_link_woo_product = sanitize_text_field( $_POST['create_and_link_woo_product'] );

if($create_and_link_woo_product==true){
  
    
    
    
    $apply_rules_per_woocommerce_order_instance = sanitize_text_field( $_POST['apply_rules_per_woocommerce_order_instance'] );
    if($apply_rules_per_woocommerce_order_instance==true){
       $apply_rules_per_woocommerce_order_instance='yes'; 
    }
    else{
        $apply_rules_per_woocommerce_order_instance = 'once';
    }
    
    $apply_rules_per_woocommerce_order_product_quantity_instance = sanitize_text_field( $_POST['apply_rules_per_woocommerce_order_product_quantity_instance'] );
    if($apply_rules_per_woocommerce_order_product_quantity_instance==true){
       $apply_rules_per_woocommerce_order_product_quantity_instance='yes'; 
    }
    else{
        $apply_rules_per_woocommerce_order_product_quantity_instance = 'once';
    }
    


    $args = array(
    		'post_title' => $rimplenet_package_name_form,
    		'post_content' => $rimplenet_package_desc_form,
    		'post_status' => 'publish',
    		'post_type' => "product",
    		) ;  
    $package_product_id = wp_insert_post( $args );
    wp_set_object_terms($package_product_id, $PACKAGE_CAT_NAME, 'product_cat' );
    
    $metas = array(
      '_visibility' => 'visible',
      '_stock_status' => 'instock',
      'total_sales' => '0',
      '_downloadable' => 'no',
      '_virtual' => 'yes',
      '_regular_price' => $rimplenet_package_price_form,
      '_sale_price' => $rimplenet_package_price_form,
      '_purchase_note' => '',
      '_featured' => 'no',
      '_weight' => '',
      '_length' => '',
      '_width' => '',
      '_height' => '',
      '_sku' => '',
      '_product_attributes' => array(),
      '_sale_price_dates_from' => '',
      '_sale_price_dates_to' => '',
      '_price' => $rimplenet_package_price_form,
      'rimplenet_product_min_price' => $rimplenet_package_min_price_form,
      'rimplenet_product_max_price' => $rimplenet_package_max_price_form,
      'rimplenet_cur' => 'woocommerce_base_cur',
      'use_rimplenet_woocommerce_template' => $use_rimplenet_woocommerce_template,
      'rimplenet_rules_inside_package_and_linked_product_ordered' => $rimplenet_rules_inside_package_and_linked_product_ordered_form,
      'apply_rules_per_woocommerce_order_instance' => $apply_rules_per_woocommerce_order_instance,
      'apply_rules_per_woocommerce_order_product_quantity_instance' => $apply_rules_per_woocommerce_order_product_quantity_instance,
      '_sold_individually' => TRUE,
      '_manage_stock' => 'no',
      '_backorders' => 'no',
      '_stock' => ''
     );
     foreach ($metas as $key => $value) {
      update_post_meta($package_product_id, $key, $value);
     }

}

// Create Package on RIMPLENET CPT
$args_1 = array(
		'post_title' => $rimplenet_package_name_form,
		'post_content' => $rimplenet_package_desc_form,
		'post_status' => 'publish',
		'post_type' => "rimplenettransaction",
		) ;

$package_id = wp_insert_post($args_1);
wp_set_object_terms($package_id, $PACKAGE_CAT_NAME, 'rimplenettransaction_type');
$metas = array(
  'rules_before_package_entry' => $rimplenet_rules_before_package_entry_form,
  'rules_inside_package' => $rimplenet_rules_inside_package_form,
  'rules_after_package_complete' => $rimplenet_rules_after_package_complete_form,
  'price' => $rimplenet_package_price_form,
  'rimplenet_product_min_price' => $rimplenet_package_min_price_form,
  'rimplenet_product_max_price' => $rimplenet_package_max_price_form,
  'rimplenet_cur' => 'woocommerce_base_cur',
  
  'linked_woocommerce_product' => $package_product_id,
  'use_rimplenet_woocommerce_template' => $use_rimplenet_woocommerce_template,
  'rimplenet_rules_inside_package_and_linked_product_ordered' => $rimplenet_rules_inside_package_and_linked_product_ordered_form,
  'apply_rules_per_woocommerce_order_instance' => $apply_rules_per_woocommerce_order_instance,
  'apply_rules_per_woocommerce_order_product_quantity_instance' => $apply_rules_per_woocommerce_order_product_quantity_instance,
);
foreach ($metas as $key => $value) {
  update_post_meta($package_id, $key, $value);
}




 echo '<div class="updated">
            <p>Your Package have been created successfully</p>
        </div> ';


}


$input_width = 'width:95%';
?>



<div class="rimplenet_admin_div" style="<?php echo $input_width; ?>">
 


<?php
   $txn_loop = new WP_Query(
           array(  'post_type' => 'rimplenettransaction', 
                   'post_status' => 'publish',
                   'posts_per_page' => -1,
                   'tax_query' => array(
                     array(
                        'taxonomy' => 'rimplenettransaction_type',
                        'field'    => 'name',
                        'terms'    => $PACKAGE_CAT_NAME,
                ),
             ),)
         );
 if( $txn_loop->have_posts() ){

?>
<h2> ACTIVE PACKAGES / PLANS</h2>
<table class="wp-list-table widefat fixed striped posts" >

 <thead>
  <tr>
    <th> Package Name </th>
    <th> Description </th>
    <th> Price </th>
    <th> Actions </th>
  </tr>
 </thead>
  
 <tbody>

<?php
  
    while( $txn_loop->have_posts() ){
        $txn_loop->the_post();
        $txn_id = get_the_ID(); 
        $status = get_post_status();
        $title = get_the_title();
        $content = get_the_content();


        $linked_woocommerce_product_id = get_post_meta($txn_id, 'linked_woocommerce_product', true);
        $price = get_post_meta($txn_id, 'price', true);
        
        $min_price = get_post_meta($txn_id, 'rimplenet_product_min_price', true);
        $max_price = get_post_meta($txn_id, 'rimplenet_product_max_price', true);
        
        $wallet_obj = new Rimplenet_Wallets();
        $all_wallets = $wallet_obj->getWallets();
        $wallet_id = 'woocommerce_base_cur';

        $all_rimplenet_wallets = $wallet_obj->getWallets();
        
        $wallet_symbol = $all_rimplenet_wallets[$wallet_id]['symbol'];
        $wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];

        if(is_numeric($min_price) AND is_numeric($max_price)){
         $price_formatted_disp = $wallet_symbol.number_format($min_price,$wallet_decimal).' - '.$wallet_symbol.number_format($max_price,$wallet_decimal);
        }
        else{
         $price_formatted_disp = $wallet_symbol.number_format($price,$wallet_decimal);
            
        }
            

 		$edit_package_link = '<a href="'.get_edit_post_link($txn_id).'" target="_blank">Edit Package & Rules</a>';
        $edit_linked_product_link = '<a href="'.get_edit_post_link($linked_woocommerce_product_id).'"  target="_blank">Edit Package Product</a>'; 

        //$view_package_link = '<a href="'.get_permalink($txn_id).'" >View Package</a>' ;
        $view_linked_product_link = '<a href="'.get_post_permalink($linked_woocommerce_product_id).'"  target="_blank">View Package Product</a>';
        
        

       

 ?>
  <tr>
    <td><?php echo $title; ?></td>
    <td><?php echo $content; ?></td>
    <td><?php echo $price_formatted_disp; ?></td>
    <td>
      <?php echo $edit_package_link; ?> | <?php echo $edit_linked_product_link; ?> | <?php echo $view_linked_product_link; ?>
    	

    </td>

    
  </tr>

  <?php

		}

	

  ?>
  
</tbody>

 <tfoot>
  <tr> 
  	<th> Package Name </th>
    <th> Description </th>
    <th> Price </th>
    <th> Actions </th>
  </tr>
  </tfoot>

</table>

<?php

   }
wp_reset_postdata();
?>


<h2>CREATE NEW PACKAGE / PLAN</h2>
  <form method="POST">
    <input type="hidden" name="rimplenet_package_form_submitted" value="true" />
    <?php wp_nonce_field( 'rimplenet_package_settings_nonce_field', 'rimplenet_package_settings_nonce_field' ); ?>

    <table class="form-table">
        <tbody>

            <tr>
                <th><label for="rimplenet_package_name_form"> Package Name </label></th>
                <td><input name="rimplenet_package_name_form" id="rimplenet_package_name_form" type="text" value="<?php echo get_option('rimplenet_package_name_form'); ?>" placeholder="e.g Silver Plan" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
            </tr>
            <tr>
                <th><label for="rimplenet_package_desc_form"> Package Description </label></th>
                <td>
                	<textarea  id="rimplenet_package_desc_form" name="rimplenet_package_desc_form"  placeholder="Description here" style="<?php echo $input_width; ?>"></textarea>

                	</td>
            </tr>
            
            <tr>
                <th><label for="rimplenet_package_price_form">  Price </label></th>
                <td><input name="rimplenet_package_price_form" id="rimplenet_package_price_form" type="text" value="<?php echo get_option('rimplenet_package_price_form'); ?>" placeholder="e.g 100 , accepts only numeric characters, no space or commas"  class="regular-text" required style="<?php echo $input_width; ?>" /></td>
            </tr>
            
            <tr>
                <th><label for="rimplenet_package_min_price_form">  Min Price </label></th>
                <td><input name="rimplenet_package_min_price_form" id="rimplenet_package_min_price_form" type="text" value="<?php echo get_post_meta('rimplenet_package_min_price_form'); ?>" placeholder="e.g 100 , accepts only numeric characters, no space or commas"  class="regular-text" style="<?php echo $input_width; ?>" /></td>
            </tr>
            
            <tr>
                <th><label for="rimplenet_package_max_price_form">  Max Price </label></th>
                <td><input name="rimplenet_package_max_price_form" id="rimplenet_package_max_price_form" type="text" value="<?php echo get_post_meta('rimplenet_package_max_price_form'); ?>" placeholder="e.g 200 , accepts only numeric characters, no space or commas"  class="regular-text" style="<?php echo $input_width; ?>" /></td>
            </tr>


            <tr>
                <td><h2>PACKAGE RULES</h2> </td>  
            </tr>


            <tr>
                <th><label for="rimplenet_rules_before_package_entry_form"> Rules to Achieve before User Qualifies for package </label></th>
                <td>
                	<textarea name="rimplenet_rules_before_package_entry_form" id="rimplenet_rules_before_package_entry_form" style="<?php echo $input_width; ?>"></textarea>

                	</td>
            </tr>

            <tr>
                <th><label for="rimplenet_rules_inside_package_form"> Rules to apply to User when actively in package </label></th>
                <td>
                	<textarea name="rimplenet_rules_inside_package_form" id="rimplenet_rules_inside_package_form" style="<?php echo $input_width; ?>"></textarea>

                	</td>
            </tr>

            <tr>
                <th><label for="rimplenet_rules_after_package_complete_form"> Rules to Apply to User Immediately He/She Completes this Package </label></th>
                <td>
                	<textarea name="rimplenet_rules_after_package_complete_form" id="rimplenet_rules_after_package_complete_form" style="<?php echo $input_width; ?>"></textarea>

                </td>
            </tr>
            
            
            <tr>
                <td colspan="3"><h2>WOOCOMERCE PRODUCT SETTINGS</h2> </td>  
            </tr>
            
            
            <tr>
            <th scope="row">Link Woocommerce Product</th>
            <td> <fieldset><legend class="screen-reader-text"><span>LINK WOOCOMMERCE PRODUCT</span></legend><label for="create_and_link_woo_product">
            <input name="create_and_link_woo_product"  id="create_and_link_woo_product" type="checkbox" value="1" checked="checked">
            	TICK to automatically create and link Woocommerce Product, if UNTICK and no product is linked, all settings below will not work</label>
            </fieldset></td>
            </tr>
           
            <tr>
            <th scope="row">Use RIMPLENET Template on single Product Page - Lightweight and cuts out numerous process when product is ordered</th>
            <td> <fieldset><legend class="screen-reader-text"><span>Use RIMPLENET Template on single Product Page</span></legend><label for="use_rimplenet_woocommerce_template">
            <input name="use_rimplenet_woocommerce_template"  id="use_rimplenet_woocommerce_template" type="checkbox" value="1" checked="checked">
            	TICK to use RIMPLENET Template, if UNTICK default theme single product template will be used </label>
            </fieldset></td>
            </tr>
            
             <tr>
                <th><label for="rimplenet_rules_inside_package_and_linked_product_ordered_form"> Rules to apply when User is actively in Package and orders Linked Product </label></th>
                <td>
                	<textarea name="rimplenet_rules_inside_package_and_linked_product_ordered_form" id="rimplenet_rules_inside_package_and_linked_product_ordered_form" style="<?php echo $input_width; ?>"></textarea>
            	</td>
            </tr>
            
            <tr>
            <th scope="row" >Rules Application Instance on Woocommerce Order with linked product</th>
            <td> 
             <fieldset>
                <legend class="screen-reader-text"><span>Rules Application Instance on Woocommerce Order</span></legend>
                <label for="apply_rules_per_woocommerce_order_instance">
                <input name="apply_rules_per_woocommerce_order_instance"  id="apply_rules_per_woocommerce_order_instance" type="checkbox" value="1" checked="checked">
            	TICK to apply rules for each new order with linked product, UNTICK to apply rules only once ignoring future woocommerce order </label>
             </fieldset>
            </td>
            </tr>
            
            
            <tr>
            <th scope="row">Rules Application Instance on Woocommerce Product Quantity</th>
            <td> 
             <fieldset>
                <legend class="screen-reader-text"><span>Rules Application Instance on Woocommerce Product Quantity</span></legend>
                <label for="apply_rules_per_woocommerce_order_product_quantity_instance">
                <input name="apply_rules_per_woocommerce_order_product_quantity_instance"  id="apply_rules_per_woocommerce_order_product_quantity_instance" type="checkbox" value="1" checked="checked">
            	TICK to apply rules for each quantity separately when linked product is in order, UNTICK to apply rules only once ignoring if product quantity > 1</label>
             </fieldset>
            </td>
            </tr>
            

        </tbody>
    </table>

    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="CREATE PACKAGE">
    </p>
  </form>
</div>