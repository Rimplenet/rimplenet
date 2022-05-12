<?php

$MATRIX_CAT_NAME = 'RIMPLENET MLM MATRIX';


if(isset( $_POST['rimplenet_matrix_form_submitted'] ) || wp_verify_nonce( $_POST['rimplenet_matrix_settings_nonce_field'], 'rimplenet_matrix_settings_nonce_field' ) )  {

$rimplenet_matrix_name_form = sanitize_text_field( $_POST['rimplenet_matrix_name_form'] );
$rimplenet_matrix_desc_form = sanitize_textarea_field( $_POST['rimplenet_matrix_desc_form'] );
$rimplenet_matrix_width_form = sanitize_text_field( $_POST['rimplenet_matrix_width_form'] );
$rimplenet_matrix_depth_form = sanitize_text_field( $_POST['rimplenet_matrix_depth_form'] );
$user_placement_method_in_matrix = sanitize_text_field( $_POST['user_placement_method_in_matrix'] );



$rimplenet_rules_before_matrix_entry_form = sanitize_textarea_field( $_POST['rimplenet_rules_before_matrix_entry_form'] );
$rimplenet_rules_inside_matrix_form = sanitize_textarea_field( $_POST['rimplenet_rules_inside_matrix_form'] );
$rimplenet_rules_after_matrix_complete_form = sanitize_textarea_field( $_POST['rimplenet_rules_after_matrix_complete_form'] );




$rimplenet_matrix_price_form = sanitize_text_field( $_POST['rimplenet_matrix_price_form'] );
$rimplenet_matrix_min_price_form = sanitize_text_field( $_POST['rimplenet_matrix_min_price_form'] );
$rimplenet_matrix_max_price_form = sanitize_text_field( $_POST['rimplenet_matrix_max_price_form'] );

$rimplenet_rules_inside_matrix_and_linked_product_ordered_form = sanitize_text_field( $_POST['rimplenet_rules_inside_matrix_and_linked_product_ordered_form'] );
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
    		'post_title' => $rimplenet_matrix_name_form,
    		'post_content' => $rimplenet_matrix_desc_form,
    		'post_status' => 'publish',
    		'post_type' => "product",
    		) ;  
    $matrix_product_id = wp_insert_post( $args );
    wp_set_object_terms($matrix_product_id, $MATRIX_CAT_NAME, 'product_cat' );
    
    $metas = array(
      '_visibility' => 'visible',
      '_stock_status' => 'instock',
      'total_sales' => '0',
      '_downloadable' => 'no',
      '_virtual' => 'yes',
      '_regular_price' => $rimplenet_matrix_price_form,
      '_sale_price' => $rimplenet_matrix_price_form,
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
      '_price' => $rimplenet_matrix_price_form,
      'rimplenet_product_min_price' => $rimplenet_matrix_min_price_form,
      'rimplenet_product_max_price' => $rimplenet_matrix_max_price_form,
      'rimplenet_cur' => 'woocommerce_base_cur',
      'use_rimplenet_woocommerce_template' => $use_rimplenet_woocommerce_template,
      'rimplenet_rules_inside_matrix_and_linked_product_ordered' => $rimplenet_rules_inside_matrix_and_linked_product_ordered_form,
      'apply_rules_per_woocommerce_order_instance' => $apply_rules_per_woocommerce_order_instance,
      'apply_rules_per_woocommerce_order_product_quantity_instance' => $apply_rules_per_woocommerce_order_product_quantity_instance,
      '_sold_individually' => TRUE,
      '_manage_stock' => 'no',
      '_backorders' => 'no',
      '_stock' => ''
     );
     foreach ($metas as $key => $value) {
      update_post_meta($matrix_product_id, $key, $value);
     }

}



// Create Matrix on RIMPLENET CPT
$args_1 = array(
		'post_title' => $rimplenet_matrix_name_form,
		'post_content' => $rimplenet_matrix_desc_form,
		'post_status' => 'publish',
		'post_type' => "rimplenettransaction",
		) ;

$matrix_id = wp_insert_post($args_1);
wp_set_object_terms($matrix_id, $MATRIX_CAT_NAME, 'rimplenettransaction_type');
$metas = array(
  'rules_before_matrix_entry' => $rimplenet_rules_before_matrix_entry_form,
  'rules_inside_matrix' => $rimplenet_rules_inside_matrix_form,
  'rules_after_matrix_complete' => $rimplenet_rules_after_matrix_complete_form,
  'user_placement_method_in_matrix' => $user_placement_method_in_matrix,
  'width' => $rimplenet_matrix_width_form,
  'depth' => $rimplenet_matrix_depth_form,
  'price' => $rimplenet_matrix_price_form,
  'rimplenet_product_min_price' => $rimplenet_matrix_min_price_form,
  'rimplenet_product_max_price' => $rimplenet_matrix_max_price_form,
  'rimplenet_cur' => 'woocommerce_base_cur',
  
  'linked_woocommerce_product' => $matrix_product_id,
  'use_rimplenet_woocommerce_template' => $use_rimplenet_woocommerce_template,
  'rimplenet_rules_inside_matrix_and_linked_product_ordered' => $rimplenet_rules_inside_matrix_and_linked_product_ordered_form,
  'apply_rules_per_woocommerce_order_instance' => $apply_rules_per_woocommerce_order_instance,
  'apply_rules_per_woocommerce_order_product_quantity_instance' => $apply_rules_per_woocommerce_order_product_quantity_instance,
);
foreach ($metas as $key => $value) {
  update_post_meta($matrix_id, $key, $value);
}


//Create or Update Page with Matrix Details
$create_and_link_matrix_page = sanitize_text_field( $_POST['create_and_link_matrix_page'] );
if($create_and_link_matrix_page==true){
  
    $page_content = '[rimplenet-draw-mlm-tree default="'.$matrix_id.'"]';
    $args_2 = array(
    		'post_title' => $rimplenet_matrix_name_form,
    		'post_content' => $page_content,
    		'post_status' => 'publish',
    		'post_type' => "page",
    		) ;  
    $page_id = wp_insert_post( $args_2 );
    wp_set_object_terms($matrix_page_id, $MATRIX_CAT_NAME, 'category' );
    update_post_meta($page_id,'linked_matrix_id', $matrix_id);
    
    update_post_meta($matrix_id,'linked_matrix_page_id', $page_id);
    
}


 echo '<div class="updated">
            <p>Your Matrix have been created successfully</p>
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
                        'terms'    => $MATRIX_CAT_NAME,
                ),
             ),)
         );
 if( $txn_loop->have_posts() ){


 


?>
<h2> ACTIVE MATRIX </h2>


<table class="wp-list-table widefat fixed striped posts" >

 <thead>
  <tr>
    <th> Matrix Name </th>
    <th> Description </th>
    <th> Matrix Spec - (Width * Depth) </th>
    <th> User Placement Method </th>
    <th> Shortcode </th>
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


        $spec = get_post_meta($txn_id, 'width', true).' * '.get_post_meta($txn_id, 'depth', true);
        $linked_woocommerce_product_id = get_post_meta($txn_id, 'linked_woocommerce_product', true);
        $linked_page_id = get_post_meta($txn_id, 'linked_matrix_page_id', true);

 	    $edit_matrix_link = '<a href="'.get_edit_post_link($txn_id).'" target="_blank">Edit Matrix & Rules</a>';
        $edit_linked_product_link = ' | <a href="'.get_edit_post_link($linked_woocommerce_product_id).'"  target="_blank">Edit Linked Product</a>'; 
        if(!empty($linked_page_id)){
            $view_matrix_page_link = ' | <a href="'.get_permalink($linked_page_id).'" target="_blank">View Matrix Page</a>' ;
        }
        
        $view_linked_product_link = ' | <a href="'.get_post_permalink($linked_woocommerce_product_id).'"  target="_blank">View Linked Product</a>';

        $user_placement_method  = get_post_meta($txn_id, 'user_placement_method_in_matrix', true);
        $matrix_tree_shortcode  = '[rimplenet-draw-mlm-tree default="'.$txn_id.'"]';

 ?>
  <tr>
    <td><?php echo $title; ?></td>
    <td><?php echo $content; ?></td>
    <td><?php echo $spec; ?></td>
    <td><?php echo $user_placement_method; ?></td>
    <td> <code class="rimplenet_click_to_copy"><?php echo $matrix_tree_shortcode; ?></code> </td>
    <td> 
    	<?php echo $edit_matrix_link; ?> <?php echo $view_matrix_page_link; ?> <?php echo $edit_linked_product_link; ?> <?php echo $view_linked_product_link; ?>
    </td>

    
  </tr>

  <?php

		}

	

  ?>
  
</tbody>

 <tfoot>
  <tr> 
  	<th> Matrix Name </th>
    <th> Description </th>
    <th> Matrix Spec - (Width * Depth) </th>
    <th> User Placement Method </th>
    <th> Shortcode </th>
    <th> Actions </th>
  </tr>
  </tfoot>

</table>

<?php

   }
wp_reset_postdata();
?>


<h2>CREATE NEW MATRIX</h2>
  <form method="POST">
    <input type="hidden" name="rimplenet_matrix_form_submitted" value="true" />
    <?php wp_nonce_field( 'rimplenet_matrix_settings_nonce_field', 'rimplenet_matrix_settings_nonce_field' ); ?>

    <table class="form-table">
        <tbody>

            <tr>
                <th><label for="rimplenet_matrix_name_form"> Matrix Name </label></th>
                <td><input name="rimplenet_matrix_name_form" id="rimplenet_matrix_name_form" type="text" value="<?php echo get_option('rimplenet_matrix_name_form'); ?>" placeholder="e.g Matrix 3 * 2" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
            </tr>
            <tr>
                <th><label for="rimplenet_matrix_desc_form"> Matrix Description </label></th>
                <td>
                	<textarea id="rimplenet_matrix_desc_form" name="rimplenet_matrix_desc_form" placeholder="Description here" style="<?php echo $input_width; ?>"></textarea>

                	</td>
            </tr>

            <tr>
                <th><label for="rimplenet_matrix_width_form"> Matrix Width </label></th>
                <td><input name="rimplenet_matrix_width_form" id="rimplenet_matrix_width_form" type="number" min="1" value="<?php echo get_option('rimplenet_matrix_width_form'); ?>" placeholder="e.g 3"  class="regular-text" required style="<?php echo $input_width; ?>" /></td>
            </tr>


            <tr>
                <th><label for="rimplenet_matrix_depth_form"> Matrix Depth </label></th>
                <td><input name="rimplenet_matrix_depth_form" id="rimplenet_matrix_depth_form" type="number" min="1" value="<?php echo get_option('rimplenet_matrix_depth_form'); ?>" placeholder="e.g 2"  class="regular-text" required style="<?php echo $input_width; ?>" /></td>
            </tr>
            
            <tr>
            <th scope="row">User Placement Method in Matrix - Methods of Placement when user joins matrix</th>
            <td>
            	<fieldset>
            	    <legend class="screen-reader-text"><span>Time Format</span></legend>
            	    <label><input type="radio" name="user_placement_method_in_matrix" value="first_come_first_served" checked="checked"> 
            	    <span class="">First Come First Serve - First User who joins this matrix will get his matrix structure filled up and then followed by second user till infinity</span></label> <br>
            	    
            	    <label><input type="radio" name="user_placement_method_in_matrix" value="referral_based_during_registration"> 
            	    <span class=""> Referral Based - when a user joins matrix, he will be placed under his upline (upline user should be activated and choosed during registration, else user will have his separate matrix structure) </span></label> <br>
            	
            	</fieldset>
            </td>
            
            </tr>
            
            
            <tr>
            <th scope="row">Create and Link Matrix Page</th>
            <td> <fieldset><legend class="screen-reader-text"><span>Create and Link Matrix Page</span></legend><label for="create_and_link_woo_product">
            <input name="create_and_link_matrix_page"  id="create_and_link_matrix_page" type="checkbox" value="1" checked="checked">
            	TICK to create and link Page, you can view matrix structure with this page, if UNTICK, no page is created or linked, you will need to manually create and include matrix shortcode</label>
            </fieldset></td>
            </tr>
            
            
            <tr>
                <td><h2>MATRIX RULES</h2> </td>  
            </tr>


            <tr>
                <th><label for="rimplenet_rules_before_matrix_entry_form"> Rules to Achieve before User Qualifies for this Matrix </label></th>
                <td>
                	<textarea name="rimplenet_rules_before_matrix_entry_form" id="rimplenet_rules_before_matrix_entry_form" style="<?php echo $input_width; ?>"></textarea>

                	</td>
            </tr>

            <tr>
                <th><label for="rimplenet_rules_inside_matrix_entry_form"> Rules to apply to Users actively in Matrix </label></th>
                <td>
                	<textarea name="rimplenet_rules_inside_matrix_entry_form" id="rimplenet_rules_inside_matrix_entry_form" style="<?php echo $input_width; ?>"></textarea>

                	</td>
            </tr>

            <tr>
                <th><label for="rimplenet_rules_after_matrix_complete_form"> Rules to Apply to User Immediately He/She Completes this Matrix </label></th>
                <td>
                	<textarea name="rimplenet_rules_after_matrix_complete_form" id="rimplenet_rules_after_matrix_complete_form" style="<?php echo $input_width; ?>"></textarea>

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
            <th scope="row">Use Rimplenet Template on single Product Page - Lightweight and cuts out numerous process when product is ordered</th>
            <td> <fieldset><legend class="screen-reader-text"><span>Use RIMPLENET Template on single Product Page</span></legend><label for="use_rimplenet_woocommerce_template">
            <input name="use_rimplenet_woocommerce_template"  id="use_rimplenet_woocommerce_template" type="checkbox" value="1" checked="checked">
            	TICK to use RIMPLENET Template, if UNTICK default theme single product template will be used </label>
            </fieldset></td>
            </tr>
              
            
            <tr>
                <th><label for="rimplenet_matrix_price_form">  Price </label></th>
                <td><input name="rimplenet_matrix_price_form" id="rimplenet_matrix_price_form" type="text" value="<?php echo get_option('rimplenet_matrix_price_form'); ?>" placeholder="e.g 100 , accepts only numeric characters, no space or commas"  class="regular-text" required style="<?php echo $input_width; ?>" /></td>
            </tr>
            
            <tr>
                <th><label for="rimplenet_matrix_min_price_form">  Min Price </label></th>
                <td><input name="rimplenet_matrix_min_price_form" id="rimplenet_matrix_min_price_form" type="text" value="<?php echo get_post_meta('rimplenet_matrix_min_price_form'); ?>" placeholder="e.g 100 , accepts only numeric characters, no space or commas"  class="regular-text" style="<?php echo $input_width; ?>" /></td>
            </tr>
            
            <tr>
                <th><label for="rimplenet_matrix_max_price_form">  Max Price </label></th>
                <td><input name="rimplenet_matrix_max_price_form" id="rimplenet_matrix_max_price_form" type="text" value="<?php echo get_post_meta('rimplenet_matrix_max_price_form'); ?>" placeholder="e.g 200 , accepts only numeric characters, no space or commas"  class="regular-text" style="<?php echo $input_width; ?>" /></td>
            </tr>
            
            
             <tr>
                <th><label for="rimplenet_rules_inside_matrix_and_linked_product_ordered_form"> Rules to apply when User is when actively in Matrix and orders Linked Product </label></th>
                <td>
                	<textarea name="rimplenet_rules_inside_matrix_and_linked_product_ordered_form" id="rimplenet_rules_inside_matrix_and_linked_product_ordered_form" style="<?php echo $input_width; ?>"></textarea>
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
        <input type="submit" name="submit" id="submit" class="button button-primary" value="CREATE MATRIX">
    </p>
  </form>
</div>