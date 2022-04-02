<?php

class Rimplenet_Package_and_Rules extends RimplenetRules{
 
     
  public function __construct() {
      
      
      add_action('add_decode_rimplenet_rules', array($this,'rimplenet_rules_check_if_user_package_status_is'), 25, 4 );
      add_action( 'init', array($this,'update_user_packages_and_run_rules'), 25, 0 );
      add_shortcode('rimplenet-packages', array($this, 'ShortcodeDesignPackages'));
      
     
     }
   
    
   function rimplenet_rules_check_if_user_package_status_is($rule,$user, $obj_id, $args)
    {
     
     $status  = trim($args[0]); // can take completed,active, or not_active
     $package_id  = $obj_id;
     if(empty($package_id)){$package_id = trim($args[1]);}
     $user_id = $user->ID;
     
     if(strpos($rule, "rimplenet_rules_check_if_user_package_status_is") !== false AND !empty($status)  AND !empty($package_id) AND !empty($user_id)){
         
        $active_subscribers = get_post_meta($package_id, 'package_subscriber');
        $completed_subscribers = get_post_meta($package_id, 'package_completers');
        
        if($status=='active' AND in_array($user_id, $active_subscribers)){
            $executed = 'yes';
        }
        elseif($status=='completed' AND in_array($user_id, $completed_subscribers)){
            $executed = 'yes';
        }
        elseif($status=='not_active' AND !in_array($user_id, $active_subscribers) AND !in_array($user_id, $completed_subscribers)){
            $executed = 'yes';
        }
        
        if($executed=='yes'){
          return rimplenetRulesExecuted($rule,$user,$obj_id,$args);
        }
        else{
          return 'RIMPLENET_ERROR_EXECUTING_RULES_OR_OBJ_DOESNT_EXIST';
        }
        
     }
     else{
          return 'RIMPLENET_ERROR_ONE_OR_MORE_REQUIRED_FIELDS_IS_EMPTY';
     }
           
    }

   public function update_user_packages_and_run_rules()
   {

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    $packages_id_array = $this->getMLMPackages();

    foreach ($packages_id_array as $obj) {
          $package_subs = get_post_meta($obj,'package_subscriber');// active in package users 
          $package_completers = get_post_meta($obj,'package_completers');
          $completed_rules_executed_users = get_post_meta($obj,'completed_rules_executed_users');
          
          //Run Rules before package
          $rules = get_post_meta($obj, 'rules_before_package_entry', true);
          if (!empty($rules) AND !in_array($user_id, $package_subs) ) {  
            $obj_id = 'package_'.$obj.'_active';
            if($this->evalRimplenetRules($rules, $current_user, $obj_id)===true){
              $package_id = $obj;
              return $this->setUserMLMPackage($user_id, $package_id);
             }
          }
          
           //Run Rules When in package
          $rules = get_post_meta($obj, 'rules_inside_package', true);
          if (!empty($rules) AND in_array($user_id, $package_subs) AND !in_array($user_id, $package_completers)) {
             $obj_id = 'package_'.$obj.'_before_completed';
             if($this->evalRimplenetRules($rules, $current_user, $obj_id)===true){ 
             $package_id = $obj;
             return $this->setUserMLMPackageComplete($user_id, $package_id);
             }
          }
         
          
          //Run Rules after package complete
          $rules = get_post_meta($obj, 'rules_after_package_complete', true);
           if (!empty($rules) AND in_array($user_id, $package_completers) AND !in_array($user_id, $completed_rules_executed_users)) {
             $obj_id = 'package_'.$obj.'_after_completed';
             if($this->evalRimplenetRules($rules, $current_user, $obj_id)===true ){
                $package_id = $obj;
                return $this->setUsersPackageCompletedRulesExecuted($user_id, $package_id);
            }
          }
          
          
          //Run Rules when Investment Form for Package is filled
         
          $rules = get_post_meta($obj, 'rules_for_package_investment_form', true);
          $investment_id_array = $this->getLinkedInvestmentIdforPackage();//Get for everyone
        
          if (!empty($rules) AND !empty($investment_id_array)) {
              foreach ($investment_id_array as $inv_id) {
                 
                 $obj_id = 'package_'.$obj.'_investment_'.$inv_id;
                 $package_investment_rule_executed = get_post_meta($inv_id,'package_investment_rule_executed',true);
                 $author_id = get_post_field( 'post_author', $inv_id );
                 $userinfo_investor = get_user_by( 'id', $author_id );
                 
                 if($package_investment_rule_executed!='yes'){
                   if($this->evalRimplenetRules($rules, $userinfo_investor, $obj_id)===true){ 
                   update_post_meta($inv_id,'package_investment_rule_executed','yes');
                   update_post_meta($inv_id,'timed_package_investment_rule_executed',time());
                  }
               }
             } 
          }
          
          
          //Run Rules on linked product ordered for active subs
          
          $linked_woocommerce_product = get_post_meta($obj,'linked_woocommerce_product',true);
          if(is_numeric($linked_woocommerce_product) AND get_post_type($linked_woocommerce_product )=='product'  AND in_array($user_id, $package_subs) AND in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))  ){
             
              $orders = wc_get_orders(array(
                    'customer_id' => get_current_user_id(),
                ));
                
             foreach($orders as $order){
              $rules = get_post_meta($obj, 'rimplenet_rules_inside_package_and_linked_product_ordered', true);
              $order_id = $order->get_id();
              $order_status = $order->get_status();
                  
                $items = $order->get_items(); 
                $package_id = $obj;
                foreach ( $items as $item_id => $item ) {
                    
                $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
                $product_id = $item->get_product_id();
                $order_quantity = $item->get_quantity(); // Get the item quantity
                
                if (($product_id == $linked_woocommerce_product) AND ($order_status=='processing' OR $order_status=='completed') ) {
                    
                    
                        $key_linked_product_exec = 'linked_product_rules_executed_for_user_'.$user_id.'_on_order';
                        $linked_executed_rules_on_user_for_orders_arr = get_post_meta($package_id,  $key_linked_product_exec);
                        
                        $apply_rules_per_woocommerce_order_instance = get_post_meta($package_id,  'apply_rules_per_woocommerce_order_instance',true);
                        $apply_rules_per_woocommerce_order_product_quantity_instance = get_post_meta($package_id,  'apply_rules_per_woocommerce_order_instance',true);
                      
                      if(!in_array($order_id, $linked_executed_rules_on_user_for_orders_arr) ){
                        if($apply_rules_per_woocommerce_order_instance=='once' AND !in_array($order_id, $linked_executed_rules_on_user_for_orders_arr) ){
                            $apply_linked_rules = 'yes'; 
                        }
                        elseif($apply_rules_per_woocommerce_order_instance=='yes'){
                            $apply_linked_rules = 'yes'; 
                        }
                        
                        if($apply_rules_per_woocommerce_order_product_quantity_instance=='yes'){
                            $rules_qnt = $order_quantity;
                        }
                        elseif($apply_rules_per_woocommerce_order_product_quantity_instance=='once'){
                            $rules_qnt = 1;
                        }
                        
                        if($apply_linked_rules=='yes'){
                            
                            
                           $rules = get_post_meta($obj, 'rimplenet_rules_inside_package_and_linked_product_ordered', true);
                           for ($x = 1; $x <= $rules_qnt; $x++) {
                             $linked_gen_obj = 'linked_product_ordered_'.$package_id.'_'.$order_id.'_'.$x;
                             if($this->evalRimplenetRules($rules, $current_user, $linked_gen_obj)===true ) {
                                 $package_id = $obj;
                                 
                                 return $this->setLinkedProductRulesExecuted($user_id, $package_id,$order_id);
                                } 
                              }
                            
                            }
                        
                      }
                        
                    
                    
                  }
                }
                
                
              
            }
              
          }
          
          
          
      
      
      
     }
     
    }
   
     
   public function setUserMLMPackage($user_id, $package_id)
   {
      $user = get_userdata( $user_id );
      if ( $user === false ) {
          return 'RIMPLENET_ERROR_USER_DOES_NOT_EXIST';
      }
      else{
         update_user_meta($user_id, 'user_current_package', $package_id);
    
         $key_time_user_subscribe = 'time_user_subscribed_to_package_'.$package_id;
         add_user_meta( $user_id, $key_time_user_subscribe, time() );
    
         add_post_meta($package_id, 'package_subscriber', $user_id);
         $key_time_user_subscribe = 'time_user_'.$user_id.'_subscribed_to_package';
         add_post_meta($package_id, $key_time_user_subscribe, time());
    
         return true;
      }
   
   }
   
  
   public function setUserMLMPackageComplete($user_id, $package_id)
   {
     $user = get_userdata( $user_id );
     if ( $user === false ) {
          return 'RIMPLENET_ERROR_USER_DOES_NOT_EXIST';
     }
     else{
     $key_time_user_subscribe = 'time_user_completed_package_'.$package_id;
     add_user_meta( $user_id, $key_time_user_subscribe, time() );

     add_post_meta($package_id, 'package_completers', $user_id);
     $key_time_user_subscribe = 'time_user_'.$user_id.'_completed_package';
     add_post_meta($package_id, $key_time_user_subscribe, time());

     return true;
     }
   }
   
      
   
   public function setUsersPackageCompletedRulesExecuted($user_id, $package_id)
   {
     $user = get_userdata( $user_id );
     if ( $user === false ) {
          return 'RIMPLENET_ERROR_USER_DOES_NOT_EXIST';
     }
     else{
     $key_time_user_subscribe = 'time_user_completed_rules_executed_package_'.$package_id;
     add_user_meta( $user_id, $key_time_user_subscribe, time() );

     add_post_meta($package_id, 'completed_rules_executed_users', $user_id);
     $key_time_user_subscribe = 'time_user_'.$user_id.'_completed_rules_executed';
     add_post_meta($package_id, $key_time_user_subscribe, time());

     return true;
     }
   }
   
   
   
   public function setLinkedProductRulesExecuted($user_id, $package_id,$order_id)
   {
     $user = get_userdata( $user_id );
     if ( $user === false ) {
          return 'RIMPLENET_ERROR_USER_DOES_NOT_EXIST';
     }
     else{
    
         $user_key_linked_product_exec = 'linked_product_rules_executed_for_package_'.$package_id.'_on_order';
         add_user_meta( $user_id,  $user_key_linked_product_exec, time() );
         
         
         $key_linked_product_exec = 'linked_product_rules_executed_for_user_'.$user_id.'_on_order';
         add_post_meta($package_id,  $key_linked_product_exec, $order_id);
         
         $key_time_linked_product_exec = 'time_linked_product_rules_executed_for_user_'.$user_id.'_on_order_'.$order_id;
         add_post_meta($package_id,  $key_time_linked_product_exec, time());
         update_post_meta($package_id, 'user_linked_product_rules_executed', $user_id);
         add_post_meta($package_id, 'order_linked_product_rules_executed', $order_id);
         
         add_post_meta($order_id, 'package_linked_product_rules_executed', $package_id);
         add_post_meta($order_id, 'user_linked_product_rules_executed', $user_id);

         return true;
     }
   }
   
   public function getLinkedInvestmentIdforPackage($user_id='')
   {

    $post_per_page = rand(50,500);
    $txn_loop = new WP_Query(
      array(
      'post_type' => 'rimplenettransaction', 
      'posts_per_page'   => $post_per_page, // get posts.
      'order' => 'ASC',
      'post_status'    => 'publish',
      'meta_query' => array(
         'relation' => 'AND',
            array(
                'key'     => 'linked_package',
                'value'   => 0,
                'compare' => '>',
            ),
            array(
                'key' => 'package_investment_rule_executed',
                'compare' => 'NOT EXISTS',
            ),
        ),
      'tax_query'     => array(
         'relation' => 'AND',
             array(
               'taxonomy' => 'rimplenettransaction_type',
               'field'    => 'name',
               'terms'    => 'INVESTMENTS',
               'operator' => 'IN'
             ),
             array(
               'taxonomy' => 'rimplenettransaction_type',
               'field'    => 'name',
               'terms'    => 'DEBIT',
               'operator' => 'IN'
              ),
        ),
      'fields'        => 'ids', // Only get post IDs
      )
     );
    $investment_id_array = $txn_loop->posts;
    wp_reset_postdata();
    
    return $investment_id_array;
    
   }
 
   public function getMLMPackages($type='')
   {

    
    $txn_loop = new WP_Query(
      array(
      'post_type' => 'rimplenettransaction', // get all posts.
      'numberposts'   => -1, // get all posts.
      'tax_query'     => array(
        array(
          'taxonomy' => 'rimplenettransaction_type',
          'field'    => 'name',
          'terms'    => 'RIMPLENET MLM PACKAGES',
        ),
        ),
      'fields'        => 'ids', // Only get post IDs
      )
     );

    $packages_id_array = $txn_loop->posts;
    wp_reset_postdata();
    return $packages_id_array;
   }
  
   public function ShortcodeDesignPackages($atts) {
          

      ob_start();

      include plugin_dir_path( dirname( __FILE__ ) ) . 'public/layouts/design-plan-packages-from-shortcode.php';
       
      $output = ob_get_clean();

      return $output;
    


    }




}

$Rimplenet_Package_and_Rules = new Rimplenet_Package_and_Rules();