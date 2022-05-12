<?php

class Rimplenet_Matrix_and_Rules extends RimplenetRules{
 
     
  public function __construct() {
      
     add_action('add_decode_rimplenet_rules', array($this,'rimplenet_rules_add_to_mature_wallet_in_matrix'), 25, 4 );
     add_action('add_decode_rimplenet_rules', array($this,'rimplenet_rules_add_to_immature_wallet_in_matrix'), 25, 4 );
     
     add_action('add_decode_rimplenet_rules', array($this,'rimplenet_rules_check_if_user_matrix_status_is'), 25, 4 );
     add_action('add_decode_rimplenet_rules', array($this,'rimplenet_rules_check_if_user_downlines_in_matrix_is'), 25, 4 );
     
     add_action( 'init', array($this,'update_user_matrix_and_run_rules'), 25, 0 );    
     if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){

     add_action('woocommerce_order_status_processing', array($this,'woo_order_update_user_matrix') );
      
     }
     else{
      //Add Error Message
     }

       
   }
   
   
    function rimplenet_rules_add_to_mature_wallet_in_matrix($rule,$user, $obj_id='', $args='')
    {
        $IF_CLAUSE  = trim(end($args)); // can be IF_DOWNLINES_IS;1;23 {1 is downline count, 23 is matrix_id}

        if(!empty($IF_CLAUSE)){
            $if_clause = explode(";",$IF_CLAUSE);
            
            if(trim($if_clause[0])=="IF_DOWNLINES_IS"){
                
                $downline_count_to_check = $if_clause[1];
                $matrix_id_check = $if_clause[2];
                $rule_to_check = "rimplenet_rules_check_if_user_downlines_in_matrix_is: $downline_count_to_check,$matrix_id_check";
                
                $if_clause_check = $this->evalRimplenetRules($rule_to_check, $user, $obj_id);
                
            }
            elseif(trim($if_clause[0])=="IF_USER_MATRIX_STATUS_IS"){
                #Perform check
            }
            
        }
        
        if(empty($IF_CLAUSE)){
            
           $status = 'RIMPLENET_RULES_ERROR_EMPTY_IF_CLAUSE'; 
           
        }
        elseif($if_clause_check!==true){
            
           $status = $if_clause_check;
           
        }
        elseif($if_clause_check===true){
            
             $args_for_funding = $args;
             array_pop($args_for_funding);
            
             $args_alt = implode(",",$args_for_funding);
             
             $rule_to_execute = "rimplenet_rules_add_to_mature_wallet: $args_alt";
             
             $rules_execution_check = $this->evalRimplenetRules($rule_to_execute, $user, $obj_id);
             
             if($rules_execution_check===true){
                $status = rimplenetRulesExecuted($rule,$user,$obj_id,$args);  
             }
             else{
                $status = $rules_execution_check;  
             }
            
        }
        else{
           $status = 'RIMPLENET_UNKNOWN_ERROR'; 
        }
        
        return $status;
           
    }
    
    
    
    function rimplenet_rules_add_to_immature_wallet_in_matrix($rule,$user, $obj_id='', $args='')
    {
        
     $IF_CLAUSE  = trim(end($args)); // can be IF_DOWNLINES_IS;1;23 {1 is downline count, 23 is matrix_id}

        if(!empty($IF_CLAUSE)){
            $if_clause = explode(";",$IF_CLAUSE);
            
            if(trim($if_clause[0])=="IF_DOWNLINES_IS"){
                
                $downline_count_to_check = $if_clause[1];
                $matrix_id_check = $if_clause[2];
                $rule_to_check = "rimplenet_rules_check_if_user_downlines_in_matrix_is: $downline_count_to_check,$matrix_id_check";
                
                $if_clause_check = $this->evalRimplenetRules($rule_to_check, $user, $obj_id);
                
            }
            elseif(trim($if_clause[0])=="IF_USER_MATRIX_STATUS_IS"){
                #Perform check
            }
            
        }
        
        if(empty($IF_CLAUSE)){
            
           $status = 'RIMPLENET_RULES_ERROR_EMPTY_IF_CLAUSE'; 
           
        }
        elseif($if_clause_check!==true){
            
           $status = $if_clause_check;
           
        }
        elseif($if_clause_check===true){
            
             $args_for_funding = $args;
             array_pop($args_for_funding);
            
             $args_alt = implode(",",$args_for_funding);
             
             $rule_to_execute = "rimplenet_rules_add_to_immature_wallet: $args_alt";
             
             $rules_execution_check = $this->evalRimplenetRules($rule_to_execute, $user, $obj_id);
             
             if($rules_execution_check===true){
                $status = rimplenetRulesExecuted($rule,$user,$obj_id,$args);  
             }
             else{
                $status = $rules_execution_check;  
             }
            
        }
        else{
           $status = 'RIMPLENET_UNKNOWN_ERROR'; 
        }
        
        return $status;
     
    }
    
       
   
   function rimplenet_rules_check_if_user_matrix_status_is($rule,$user, $obj_id, $args)
    {
     
     $status  = trim($args[0]); // can take completed,active, or not_active
     $matrix_id = trim($args[1]);
     if(empty($matrix_id)){ $matrix_id = $obj_id; }
     $user_id = $user->ID;
     
     if(strpos($rule, "rimplenet_rules_check_if_user_matrix_status_is") !== false AND !empty($status)  AND !empty($matrix_id) AND !empty($user_id)){
         
        $active_subscribers = get_post_meta($matrix_id, 'matrix_subscriber');
        $completed_subscribers = get_post_meta($matrix_id, 'matrix_completers');
        
        if($status=='active' AND in_array($user_id, $active_subscribers) AND !in_array($user_id, $completed_subscribers)){
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
 
 
   function rimplenet_rules_check_if_user_downlines_in_matrix_is($rule,$user, $obj_id, $args)
    {
     $user_id = $user->ID;  
     $Matx = new RimplenetMlmMatrix(); 
     $user_downline_count  = trim($args[0]); // can take full, 1, 2 or any positive int
     $matrix_id  = trim($args[1]);
     
     if($user_downline_count=='full'){
        $user_downline_count = $Matx->getMatrixCapacity($matrix_id);
        $user_downline_count = $user_downline_count - 1;
     }
     
     if(strpos($rule, "rimplenet_rules_check_if_user_downlines_in_matrix_is") !== false AND (!empty($user_downline_count) OR $user_downline_count==0) AND !empty($matrix_id) AND !empty($user_id)){
         
        
        $retrieved_user_downline_count = $Matx->getMatrixCapacityUsed($matrix_id,$user_id) - 1;
     
        if($retrieved_user_downline_count>=$user_downline_count){
            $executed = 'yes';
        }
        else{
            return 'RIMPLENET_INFO_YOUR_MATRIX_#'.$matrix_id.'_DOWNLINE_COUNT_IS_'.$retrieved_user_downline_count;
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


   public function update_user_matrix_and_run_rules()
   {

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    $matrix_id_array = $this->getMLMMatrix();


     foreach ($matrix_id_array as $obj) {
          
          $matrix_subs = get_post_meta($obj,'matrix_subscriber');// active in matrix users 
          $matrix_completers = get_post_meta($obj,'matrix_completers');
          $completed_rules_executed_users = get_post_meta($obj,'completed_rules_executed_users');
      
          //Run Rules before matrix
          $rules = get_post_meta($obj, 'rules_before_matrix_entry', true);
          if (!empty($rules) AND !in_array($user_id, $matrix_subs) ) {  
            $obj_id = 'matrix_'.$obj.'_active';
            if($this->evalRimplenetRules($rules, $current_user, $obj_id)===true){
              $matrix_id = $obj;
              return $this->setUserMLMMatrix($user_id, $matrix_id);
             }
          }
          
          //Run Rules When in matrix, before matrix status is changed to completed 
             $rules = get_post_meta($obj, 'rules_inside_matrix', true);
          if (!empty($rules) AND in_array($user_id, $matrix_subs) AND !in_array($user_id, $matrix_completers)) {
             $obj_id = 'matrix_'.$obj.'_before_completed';
             if($this->evalRimplenetRules($rules, $current_user, $obj_id)===true){ 
              $matrix_id = $obj;
              return $this->setUserMLMMatrixComplete($user_id, $matrix_id);
             }
          }
          
          //Run Rules after matrix complete
             $rules = get_post_meta($obj, 'rules_after_matrix_complete', true);
          if (!empty($rules) AND in_array($user_id, $matrix_completers) AND !in_array($user_id, $completed_rules_executed_users)) {
             $obj_id = 'matrix_'.$obj.'_after_completed';
             if($this->evalRimplenetRules($rules, $current_user, $obj_id)===true ){
                $matrix_id = $obj;
                return $this->setUsersMatrixCompletedRulesExecuted($user_id, $matrix_id);
            }
          }
          
          //Run Rules on linked product ordered for active subs
          $linked_woocommerce_product = get_post_meta($obj,'linked_woocommerce_product',true);
          if(is_numeric($linked_woocommerce_product) AND get_post_type($linked_woocommerce_product )=='product'  AND in_array($user_id, $matrix_subs) AND in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))  ){
             
              $orders = wc_get_orders(array(
                    'customer_id' => get_current_user_id(),
                ));
                
             foreach($orders as $order){
              $rules = get_post_meta($obj, 'rimplenet_rules_inside_matrix_and_linked_product_ordered', true);
              $order_id = $order->get_id();
              $order_status = $order->get_status();
                  
                $items = $order->get_items(); 
                $matrix_id = $obj;
                foreach ( $items as $item_id => $item ) {
                    
                $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
                $product_id = $item->get_product_id();
                $order_quantity = $item->get_quantity(); // Get the item quantity
                
                if (($product_id == $linked_woocommerce_product) AND ($order_status=='processing' OR $order_status=='completed') ) {
                    
                    
                        $key_linked_product_exec = 'linked_product_rules_executed_for_user_'.$user_id.'_on_order';
                        $linked_executed_rules_on_user_for_orders_arr = get_post_meta($matrix_id,  $key_linked_product_exec);
                        
                        $apply_rules_per_woocommerce_order_instance = get_post_meta($matrix_id,  'apply_rules_per_woocommerce_order_instance',true);
                        $apply_rules_per_woocommerce_order_product_quantity_instance = get_post_meta($matrix_id,  'apply_rules_per_woocommerce_order_instance',true);
                      
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
                            
                            
                           $rules = get_post_meta($obj, 'rimplenet_rules_inside_matrix_and_linked_product_ordered', true);
                           for ($x = 1; $x <= $rules_qnt; $x++) {
                             $linked_gen_obj = 'linked_product_ordered_'.$matrix_id.'_'.$order_id.'_'.$x;
                             if($this->evalRimplenetRules($rules, $current_user, $linked_gen_obj)===true ) {
                                 $matrix_id = $obj;
                                 
                                 return $this->setLinkedProductRulesExecuted($user_id, $matrix_id,$order_id);
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
   

   public function setUserMLMMatrix($user_id, $matrix_id)
   {

     $user = get_userdata( $user_id );
     if ( $user === false ) {
          return 'RIMPLENET_ERROR_USER_DOES_NOT_EXIST';
      }
     update_user_meta($user_id, 'user_current_matrix', $matrix_id);

     $key_time_user_subscribe = 'time_user_subscribed_to_matrix_'.$matrix_id;
     add_user_meta( $user_id, $key_time_user_subscribe, time() );

     
     //With Placement Parent:Child
     $referral_of_new_user = trim(get_user_meta($user_id,'rimplenet_referrer_sponsor', true));
     $ref_user = get_user_by('login',$referral_of_new_user);
    
     $parent_user_id = $this->getNextAvailableEmptyMatrixParent($matrix_id, $ref_user->ID);
     
     
     if(empty( $parent_user_id )){
        $parent_user_id = 0; 
     }
     
     $child_parent_placement = $user_id.':'.$parent_user_id;
     add_post_meta($matrix_id, 'matrix_subscriber_with_placement', $child_parent_placement);
     
     //Record user as a subscriber
     add_post_meta($matrix_id, 'matrix_subscriber', $user_id);
     $key_time_user_subscribe = 'time_user_'.$user_id.'_subscribed_to_matrix';
     add_post_meta($matrix_id, $key_time_user_subscribe, time());

     return true;
   }

  
  
   public function setUserMLMMatrixComplete($user_id, $matrix_id)
   {
     $user = get_userdata( $user_id );
     if ( $user === false ) {
          return 'RIMPLENET_ERROR_USER_DOES_NOT_EXIST';
     }
     else{
     $key_time_user_subscribe = 'time_user_completed_matrix_'.$matrix_id;
     add_user_meta( $user_id, $key_time_user_subscribe, time() );

     add_post_meta($matrix_id, 'matrix_completers', $user_id);
     $key_time_user_subscribe = 'time_user_'.$user_id.'_completed_matrix';
     add_post_meta($matrix_id, $key_time_user_subscribe, time());

     return true;
     }
   }
   
      
   
   public function setUsersMatrixCompletedRulesExecuted($user_id, $matrix_id)
   {
     $user = get_userdata( $user_id );
     if ( $user === false ) {
          return 'RIMPLENET_ERROR_USER_DOES_NOT_EXIST';
     }
     else{
     $key_time_user_subscribe = 'time_user_completed_rules_executed_matrix_'.$matrix_id;
     add_user_meta( $user_id, $key_time_user_subscribe, time() );

     add_post_meta($matrix_id, 'completed_rules_executed_users', $user_id);
     $key_time_user_subscribe = 'time_user_'.$user_id.'_completed_rules_executed';
     add_post_meta($matrix_id, $key_time_user_subscribe, time());

     return true;
     }
   }
   
   
   
   public function setLinkedProductRulesExecuted($user_id, $matrix_id,$order_id)
   {
     $user = get_userdata( $user_id );
     if ( $user === false ) {
          return 'RIMPLENET_ERROR_USER_DOES_NOT_EXIST';
     }
     else{
    
         $user_key_linked_product_exec = 'linked_product_rules_executed_for_matrix_'.$matrix_id.'_on_order';
         add_user_meta( $user_id,  $user_key_linked_product_exec, time() );
         
         
         $key_linked_product_exec = 'linked_product_rules_executed_for_user_'.$user_id.'_on_order';
         add_post_meta($matrix_id,  $key_linked_product_exec, $order_id);
         
         $key_time_linked_product_exec = 'time_linked_product_rules_executed_for_user_'.$user_id.'_on_order_'.$order_id;
         add_post_meta($matrix_id,  $key_time_linked_product_exec, time());
         update_post_meta($matrix_id, 'user_linked_product_rules_executed', $user_id);
         add_post_meta($matrix_id, 'order_linked_product_rules_executed', $order_id);
         
         add_post_meta($order_id, 'matrix_linked_product_rules_executed', $matrix_id);
         add_post_meta($order_id, 'user_linked_product_rules_executed', $user_id);

         return true;
     }
   }

   public function getNextAvailableEmptyMatrixParent($matrix_id, $referral_sponsor_id_of_new_user) {
     
     $Matx = new RimplenetMlmMatrix();
     
     $user_id_with_vacant_position = $Matx->getNextMatrixVacantPostion($matrix_id, $referral_sponsor_id_of_new_user);
     
     return $user_id_with_vacant_position;
     
   }



  public function getMLMMatrix($type='')
  {

    $txn_loop = new WP_Query(
      array(
      'post_type' => 'rimplenettransaction', // get all posts.
      'numberposts'   => -1, // get all posts.
      'tax_query'     => array(
        array(
          'taxonomy' => 'rimplenettransaction_type',
          'field'    => 'name',
          'terms'    => 'RIMPLENET MLM MATRIX',
        ),
        ),
      'fields'        => 'ids', // Only get post IDs
      )
     );
    $matrix_id_array = $txn_loop->posts;
    wp_reset_postdata();

    return $matrix_id_array;
  }
  
  
  public function woo_order_update_user_matrix($order_id){

    $order = wc_get_order( $order_id );
    $user = $order->get_user();
    $user_id = $order->get_user_id();
    $total_price = $order->get_total();
    

   }

   
}


$Rimplenet_Matrix_and_Rules = new Rimplenet_Matrix_and_Rules();