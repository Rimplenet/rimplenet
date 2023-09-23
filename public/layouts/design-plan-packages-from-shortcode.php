<?php
//Included from shortcode in includes/class-package-plans-and-rules.php
//use case [rimplenet-packages action="view_packages" user_id="1"]
 global $current_user;
 wp_get_current_user();

$atts = shortcode_atts( array(

    'action' => 'empty',
    'action_btn_text' => 'Invest',
    'user_id' => $current_user->ID,
    'packages' => 'all',

), $atts );


$action = $atts['action'];
$user_id = $atts['user_id'];
$wallet_id = $atts['wallet_id'];
$action_btn_text = $atts['action_btn_text'];

$wallet_obj = new Rimplenet_Wallets();
$all_wallets = $wallet_obj->getWallets();

if($action=='view_packages'){
    ?>
   
  <div class="rimplenet-mt"> 
    <div class="row">
        <div class="col-md-12"> 

     
            <?php
            
        
                                    
            $args =  array(  'post_type' => 'rimplenettransaction', 
                   'post_status' => 'publish',
                   'posts_per_page' => -1,
                   'tax_query' => array(
                     array(
                        'taxonomy' => 'rimplenettransaction_type',
                        'field'    => 'name',
                        'terms'    => 'RIMPLENET MLM PACKAGES',
                ),
             ),);
              
            $txn_loop = new WP_Query($args);
                              
            if( $txn_loop->have_posts() ){
                    ?>
                     
                     <table class="table table-responsive-md">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">Name</th>
                          <th scope="col">Description</th>
                          <th scope="col">Price</th>
                          <th scope="col">Status</th>
                          <th scope="col">Action</th>

                        </tr>
                      </thead>
                          <tbody>
                <?php
                            
                    while( $txn_loop->have_posts() ){
                        $txn_loop->the_post();
                        $txn_id = get_the_ID();
                        $name = get_the_title();
                        $desc = get_the_content();
                        $status = get_post_status();
                        
                        $date_time = get_the_date('D, M j, Y', $txn_id).'<br>'.get_the_date('g:i A', $txn_id);
                        $wallet_id = get_post_meta($txn_id, 'rimplenet_cur', true);

                        $all_rimplenet_wallets = $wallet_obj->getWallets();
                        
                        $wallet_symbol = $all_rimplenet_wallets[$wallet_id]['symbol'];
                        $wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];
                        
                        
                        $price = get_post_meta($txn_id, 'price', true);
                        $min_price = get_post_meta($txn_id, 'rimplenet_product_min_price', true);
                        $max_price = get_post_meta($txn_id, 'rimplenet_product_max_price', true);
                        
                        $txn_type = get_post_meta($txn_id, 'txn_type', true);
                        
                        if(is_numeric($min_price) AND is_numeric($max_price)){
                        $price_formatted_disp = $wallet_symbol.number_format($min_price,$wallet_decimal).' - '.$wallet_symbol.number_format($max_price,$wallet_decimal);
                        }
                        else{
                        $price_formatted_disp = $wallet_symbol.number_format($price,$wallet_decimal);
                            
                        }
                        
                        
                        
                        $package_subs = get_post_meta($txn_id,'package_subscriber');
                        if(in_array($user_id, $package_subs)){
                          $user_status_in_package = '<span class="badge badge-success">Active</span>';  
                        }
                        else{
                          $user_status_in_package = '<span class="badge badge-danger">Not Active</span>';  
                        }

                        $view_txn_nonce = wp_create_nonce('view_txn_nonce');
                        $txn_view_url = add_query_arg( array( 'txn_id'=>$txn_id,'view_txn_nonce'=>$view_txn_nonce), home_url(add_query_arg(array(),$wp->request)) );
                        $view_linked_product_link = '<a href="'.get_post_permalink($linked_woocommerce_product_id).'"  target="_blank">View Package Product</a>';
                        
                        
                        $linked_woocommerce_product_id = get_post_meta($txn_id, 'linked_woocommerce_product', true);
                        $view_linked_product_link = '<a type="submit" name="view_product" href="'.get_post_permalink($linked_woocommerce_product_id).'" class="btn btn-primary btn-sm" style="margin: 2px;" target="_blank"> '.$action_btn_text.' </a>';


                    ?>

                        <tr>
                          <th scope="row"> <?php echo $name ?> - #<?php echo $txn_id ?></th>
                          <td> <?php echo $desc ?></td>
                          <td> <?php echo $price_formatted_disp; ?> </td>
                          <td> <?php echo $user_status_in_package; ?> </td>
                          <td>
                            <?php echo $view_linked_product_link; ?>
                          </td>
                        </tr>

                        <?php

                          }
                         ?>
                         
                         
                          </tbody>
                      </table>
                         
                        <?php

                         }
                         else{
                            echo "<center>Nothing found for this request</center>";
                         }

                        wp_reset_postdata();

                        ?>


         </div>
        </div>
       </div>
    
<?php
 }

elseif($action=='view_packages_list'){
    
    
}
else{
    
  echo __('You did not specify a valid package action in shortcode e.g [rimplenet-packages action="view_packages"] has a valid action which is view_packages', 'rimplenet-text-domain'); 
  
}




?>