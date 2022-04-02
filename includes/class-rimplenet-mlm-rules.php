<?php



class RimplenetRules {
 
     
  public function __construct() {
      
         add_action( 'init', array($this,'update_user_account_status'), 25, 0 );
         add_action('add_decode_rimplenet_rules', array($this,'rimplenet_rules_woocomerce_product_purchase_status'), 25, 2 );
      
   }
   
   public function evalRimplenetRules1($rules, $user, $obj_id='')
   { 
       
       if(empty($rules)){
           return false;
       }
       
        
        global $wp_filter;
        $rimplenet_rules = $wp_filter['add_decode_rimplenet_rules'];
          
        //SANITIZE the $rimplenet_rules filter and get wanted parts
        $rimplenet_rules_hooks = [];
        foreach($rimplenet_rules->callbacks as $arr)
        {
            $rimplenet_rules_hooks = array_merge($rimplenet_rules_hooks , $arr);
            
        }
        
        //Retrieve hook defined in classes and as global functions
        $rimplenet_rule_hook_function_arr = array();
        $rimplenet_rule_hook_classes_arr = array();
        foreach($rimplenet_rules_hooks as $key => $rimplenet_rule_hook)
        { 
            if(is_string($rimplenet_rule_hook['function'])){//if hook is defined as global function
             $rimplenet_rule_hook_function_arr[$rimplenet_rule_hook['function']] = $rimplenet_rule_hook['accepted_args'];  
            }
            elseif(is_object($rimplenet_rule_hook['function'][0])){ // if hook is defined in class
                
              $class_name = get_class($rimplenet_rule_hook['function'][0]);
              $build_arr = array( "class_name"=>$class_name, "accepted_args"=>$rimplenet_rule_hook['accepted_args'] );
              
              $rimplenet_rule_hook_classes_arr[$rimplenet_rule_hook['function'][1]] = $build_arr; 
             
            }
            
        }
        
        //testing starts
        //echo "<hr>PUBLIC FXN<br>"; 
        foreach($rimplenet_rule_hook_function_arr as $rimplenet_rule_hook_function => $args_count)
        { 
            //echo $rimplenet_rule_hook_function.' : '.$args_count;
            //echo '<br><br>';  
        }
        
        //echo "<hr>IN CLASSES<br>";
         foreach($rimplenet_rule_hook_classes_arr as $rimplenet_rule_hook_function => $args)
        { 
           //echo $rimplenet_rule_hook_function.' : '.$args['class_name'].' - '.$args['accepted_args'];
            //echo '<br><br>';
            
        }
        //testing ends
        
       $rules_array = explode("\n", trim($rules));// Get each rule as array from the Rules Specification
        
       foreach ($rules_array as $key => $rule) { // LOOP through each rule
            
            $rule_array = explode(":", trim($rule));
            
            $fxn_str = trim($rule_array[0]);
            
            
            $fxn_args_str = trim($rule_array[1]);
            $fxn_args_arr = explode(",",$fxn_args_str);
            $fxn_args_arr = array_map('trim', $fxn_args_arr);
            
            
            $repetition_mode_str = trim(end($rule_array));
            $repetition_mode_arr = explode(",",$repetition_mode_str);
            
            
            $user = wp_get_current_user();
            
            //Checking execution status
            $execution_status = $this->RimplenetRuleExecutionStatus($rule, $user, $obj_id, $fxn_args_arr);
            if($execution_status=='not_yet_due_for_next_execution'){
                 return $fxn_str." - ".$execution_status;
             }
            
            if(empty($execution_status) or is_numeric($execution_status)){
                
                
                
               //REAL EXECUTION STARTS HERE   
               if (array_key_exists($fxn_str, $rimplenet_rule_hook_classes_arr)){//if rule was defined in a class
                 
                $rimplenet_hook_class = $rimplenet_rule_hook_classes_arr[$fxn_str]['class_name'];
                $object = new $rimplenet_hook_class();
                $function_name = $fxn_str;
                $status = call_user_func_array(array($object, $function_name), array($rule, $user, $obj_id, $fxn_args_arr));
                
                
               }
               elseif(array_key_exists($fxn_str, $rimplenet_rule_hook_function_arr)){//if rule was defined in a global available function
                //call_user_func_array("rimplenet_rules_add_to_immature_wallet", array($user, "two"));  
               } 
                
            }
            
             
            //Checking execution status Again
            $execution_status = $this->RimplenetRuleExecutionStatus($rule, $user, $obj_id, $fxn_args_arr);
             if($execution_status!='publish'){
                 return $fxn_str." - ".$status;
             }

            
             
        
        }
        
        return true; 

     
   } 
   
   public function evalRimplenetRules($rules, $user, $obj_id='')
   { 
       
       if(empty($rules)){
           return false;
       }
       
        
        global $wp_filter;
        $rimplenet_rules = $wp_filter['add_decode_rimplenet_rules'];
          
        //SANITIZE the $rimplenet_rules filter and get wanted parts
        $rimplenet_rules_hooks = [];
        foreach($rimplenet_rules->callbacks as $arr)
        {
            $rimplenet_rules_hooks = array_merge($rimplenet_rules_hooks , $arr);
            
        }
        
        //Retrieve hook defined in classes and as global functions
        $rimplenet_rule_hook_function_arr = array();
        $rimplenet_rule_hook_classes_arr = array();
        foreach($rimplenet_rules_hooks as $key => $rimplenet_rule_hook)
        { 
            if(is_string($rimplenet_rule_hook['function'])){//if hook is defined as global function
             $rimplenet_rule_hook_function_arr[$rimplenet_rule_hook['function']] = $rimplenet_rule_hook['accepted_args'];  
            }
            elseif(is_object($rimplenet_rule_hook['function'][0])){ // if hook is defined in class
                
              $class_name = get_class($rimplenet_rule_hook['function'][0]);
              $build_arr = array( "class_name"=>$class_name, "accepted_args"=>$rimplenet_rule_hook['accepted_args'] );
              
              $rimplenet_rule_hook_classes_arr[$rimplenet_rule_hook['function'][1]] = $build_arr; 
             
            }
            
        }
        
        //testing starts
        //echo "<hr>PUBLIC FXN<br>"; 
        foreach($rimplenet_rule_hook_function_arr as $rimplenet_rule_hook_function => $args_count)
        { 
            //echo $rimplenet_rule_hook_function.' : '.$args_count;
            //echo '<br><br>';  
        }
        
        //echo "<hr>IN CLASSES<br>";
         foreach($rimplenet_rule_hook_classes_arr as $rimplenet_rule_hook_function => $args)
        { 
           //echo $rimplenet_rule_hook_function.' : '.$args['class_name'].' - '.$args['accepted_args'];
            //echo '<br><br>';
            
        }
        //testing ends
        
       $rules_array = explode("\n", trim($rules));// Get each rule as array from the Rules Specification
        
       foreach ($rules_array as $key => $rule) { // LOOP through each rule
            
            $rule_array = explode(":", trim($rule));
            
            $fxn_str = trim($rule_array[0]);
            
            
            $fxn_args_str = trim($rule_array[1]);
            $fxn_args_arr = explode(",",$fxn_args_str);
            $fxn_args_arr = array_map('trim', $fxn_args_arr);
            
            
            $repetition_mode_str = trim(end($rule_array));
            $repetition_mode_arr = explode(",",$repetition_mode_str);
            
            
            $user = wp_get_current_user();
            
            //Checking execution status
            $execution_status = $this->RimplenetRuleExecutionStatus($rule, $user, $obj_id, $fxn_args_arr);
            if($execution_status=='not_yet_due_for_next_execution'){
                 return $fxn_str." - ".$execution_status;
             }
            
            if(empty($execution_status) or is_numeric($execution_status)){
                
                
                
               //REAL EXECUTION STARTS HERE   
               if (array_key_exists($fxn_str, $rimplenet_rule_hook_classes_arr)){//if rule was defined in a class
                 
                $rimplenet_hook_class = $rimplenet_rule_hook_classes_arr[$fxn_str]['class_name'];
                $object = new $rimplenet_hook_class();
                $function_name = $fxn_str;
                $status = call_user_func_array(array($object, $function_name), array($rule, $user, $obj_id, $fxn_args_arr));
                
                
               }
               elseif(array_key_exists($fxn_str, $rimplenet_rule_hook_function_arr)){//if rule was defined in a global available function
                //call_user_func_array("rimplenet_rules_add_to_immature_wallet", array($user, "two"));  
               } 
                
            }
            
             
            //Checking execution status Again
            $execution_status = $this->RimplenetRuleExecutionStatus($rule, $user, $obj_id, $fxn_args_arr);
             if($execution_status!='publish'){
                 return $fxn_str." - ".$status;
             }

            
             
        
        }
        
        return true; 

     
   } 
   
   public function evalRimplenetRulesOld1($rules, $user, $obj_id='')
   { 
       
       if(empty($rules)){
           return false;
       }
       
        
        global $wp_filter;
        $rimplenet_rules = $wp_filter['add_decode_rimplenet_rules'];
          
        //SANITIZE the $rimplenet_rules filter and get wanted parts
        $rimplenet_rules_hooks = [];
        foreach($rimplenet_rules->callbacks as $arr)
        {
            $rimplenet_rules_hooks = array_merge($rimplenet_rules_hooks , $arr);
            
        }
        
        //Retrieve hook defined in classes and as global functions
        $rimplenet_rule_hook_function_arr = array();
        $rimplenet_rule_hook_classes_arr = array();
        foreach($rimplenet_rules_hooks as $key => $rimplenet_rule_hook)
        { 
            if(is_string($rimplenet_rule_hook['function'])){//if hook is defined as global function
             $rimplenet_rule_hook_function_arr[$rimplenet_rule_hook['function']] = $rimplenet_rule_hook['accepted_args'];  
            }
            elseif(is_object($rimplenet_rule_hook['function'][0])){ // if hook is defined in class
                
              $class_name = get_class($rimplenet_rule_hook['function'][0]);
              $build_arr = array( "class_name"=>$class_name, "accepted_args"=>$rimplenet_rule_hook['accepted_args'] );
              
              $rimplenet_rule_hook_classes_arr[$rimplenet_rule_hook['function'][1]] = $build_arr; 
             
            }
            
        }
        
        //testing starts
        //echo "<hr>PUBLIC FXN<br>"; 
        foreach($rimplenet_rule_hook_function_arr as $rimplenet_rule_hook_function => $args_count)
        { 
            //echo $rimplenet_rule_hook_function.' : '.$args_count;
            //echo '<br><br>';  
        }
        
        //echo "<hr>IN CLASSES<br>";
         foreach($rimplenet_rule_hook_classes_arr as $rimplenet_rule_hook_function => $args)
        { 
           //echo $rimplenet_rule_hook_function.' : '.$args['class_name'].' - '.$args['accepted_args'];
            //echo '<br><br>';
            
        }
        //testing ends
        
       $rules_array = explode("\n", trim($rules));// Get each rule as array from the Rules Specification
        
       foreach ($rules_array as $key => $rule) { // LOOP through each rule
            
            $rule_array = explode(":", trim($rule));
            
            $fxn_str = trim($rule_array[0]);
            
            
            $fxn_args_str = trim($rule_array[1]);
            $fxn_args_arr = explode(",",$fxn_args_str);
            $fxn_args_arr = array_map('trim', $fxn_args_arr);
            
            
            $repetition_mode_str = trim(end($rule_array));
            $repetition_mode_arr = explode(",",$repetition_mode_str);
            
            
            $user = wp_get_current_user();
            
            //Checking execution status
            $execution_status = $this->RimplenetRuleExecutionStatus($rule, $user, $obj_id, $fxn_args_arr);
            
            if(empty($execution_status) or is_numeric($execution_status)){
                
                
                
               //REAL EXECUTION STARTS HERE   
               if (array_key_exists($fxn_str, $rimplenet_rule_hook_classes_arr)){//if rule was defined in a class
                 
                $rimplenet_hook_class = $rimplenet_rule_hook_classes_arr[$fxn_str]['class_name'];
                $object = new $rimplenet_hook_class();
                $function_name = $fxn_str;
                $status = call_user_func_array(array($object, $function_name), array($rule, $user, $obj_id, $fxn_args_arr));
                
                
               }
               elseif(array_key_exists($fxn_str, $rimplenet_rule_hook_function_arr)){//if rule was defined in a global available function
                //call_user_func_array("rimplenet_rules_add_to_immature_wallet", array($user, "two"));  
               } 
                
            }
            
             
            //Checking execution status Again
            $execution_status = $this->RimplenetRuleExecutionStatus($rule, $user, $obj_id, $fxn_args_arr);
             if($execution_status!='publish'){
                 return false;
             }

            
             
        
        }
        
        return true; 

     
   }
   
  
   public function RimplenetRuleExecutionStatus($rule, $user, $obj_id='', $args='')
   {     
       
       if(empty($obj_id)){
             $obj_id = 'RIMPLENET_EMPTY_VALUE';
         }
         
        $wp_interval_schedules = wp_get_schedules();
        $args_json = json_encode ($args);

        $rule_array = explode(":", trim($rule));
        
        $repetition_mode_str = trim(end($rule_array));
        $repetition_mode_arr = explode(",",$repetition_mode_str);
        $repetition_mode_arr = array_map('trim', $repetition_mode_arr);
        
        $repeat_mode = $repetition_mode_arr[0];
        $execution_interval = $repetition_mode_arr[1];
        $no_of_execution_required_for_completion = $repetition_mode_arr[2];
        $interval_timer = $repetition_mode_arr[3];
        
        
         

        if ($repeat_mode=='repeat') {
            
             if(empty($interval_timer)){
                 $interval_timer = 'RIMPLENET_EMPTY_VALUE';
             }
             

              $meta_query = array(
                             'relation' => 'AND',
                              array(
                                  'key'     => 'execution_mode',
                                  'value'   => 'repeat',
                                  'compare' => '=',
                               ),    
                              array(
                                      'key' => 'execution_interval',
                                      'value' => $execution_interval,
                                      'compare' => '=',
                              ),   
                              array(
                                      'key' => 'first_execution_timer',
                                      'value' => $interval_timer,
                                      'compare' => '=',
                              ),
                              array(
                                      'key' => 'no_of_execution_required_for_completion',
                                      'value' => $no_of_execution_required_for_completion,
                                      'compare' => '=',
                              ),
                              array(
                                      'key'     => 'obj_id',
                                      'value'   => $obj_id,
                                      'compare' => '=',
                              ),
                              array(
                                      'key' => 'rule_args_json',
                                      'value' => $args_json,
                                      'compare' => '=',
                              ),
                      
                          );


        }
        else{

           $meta_query = array(
                                'relation' => 'AND',
                                array(
                                    'key'     => 'execution_mode',
                                    'value'   => 'once',
                                    'compare' => '=',
                                 ),
                                 array(
                                    'key'     => 'obj_id',
                                    'value'   => $obj_id,
                                    'compare' => '=',
                                 ),    
                                 array(
                                        'key' => 'rule_args_json',
                                        'value' => $args_json,
                                        'compare' => '=',
                                ),
                                
                             );

        }

          $txn_loop = new WP_Query(
                             array(  
                               'post_type' => 'rimplenettransaction', 
                               'post_status' => 'any',
                               'author' => $user->ID ,
                               'posts_per_page' => 1,
                               'tax_query' => array(
                                   array(
                                    'taxonomy' => 'rimplenettransaction_type',
                                    'field'    => 'name',
                                    'terms'    => array( 'RIMPLENET RULES EXECUTION' ),
                                    )
                                  ),
                                'meta_query' => $meta_query,
                                )
                              );
                         
               if( $txn_loop->have_posts() ){
                   while( $txn_loop->have_posts() ){
                        $txn_loop->the_post();
                        $txn_id = get_the_ID(); 
                        $status = get_post_status();
                        
                        if($status!='publish'){
                            
                            $execution_interval = get_post_meta($txn_id, 'execution_interval', true);
                            $last_execution_ts = get_post_meta($txn_id, 'rule_execution_timer', true);
                            
                            $execution_interval_ts = $wp_interval_schedules[$execution_interval]['interval'];
                            
                            $diff_ts = time() - $last_execution_ts;
                            if(is_numeric($execution_interval_ts) AND $diff_ts>=$execution_interval_ts){
                                 
                                 $status = $txn_id; 
                            }
                            
                            else{
                               $status = 'not_yet_due_for_next_execution';
                            }
                          
                        }
                        return $status;
                        
                 }
               }
               else{
                   
                        return '';
               }
     
   }

  
  
   public function rimplenet_rules_woocomerce_product_purchase_status($rule,$user, $obj_id='', $args='')
   {
     

     if(strpos($rule, "rimplenet_rules_woocomerce_product_purchase_status") !== false){

      $rule_array = explode(':', $rule);

      $product_id = trim($rule_array[1]);
      
      
      if (wc_customer_bought_product($user->user_email, $user->ID, $product_id )){
         $status = rimplenetRulesExecuted($rule,$user,$obj_id,$args);
         
      }
     
      elseif (get_post_type( $product_id )!='product'){
         $status = "Product does not exist";
         
      }
      else{
         $status = "Product not purchased yet";;

      }
    
     }
     
    return $status;
       
   }
  

   public function update_user_account_status()
   {

    $current_user = wp_get_current_user();
    $user = $current_user->ID;
    $user_id = $current_user->ID;
    
    $rules = get_option('rimplenet_rules_for_user_account_activation','');
    $status = $this->evalRimplenetRules($rules, $user, $obj_id);
    
    
    
    if(!empty($rules) AND $status===true){
        
     update_user_meta($user_id, 'rimplenet_account_activation_status', 'activated');
     
    }
    else{
        
     update_user_meta($user_id, 'rimplenet_account_activation_status', 'not activated');
    
    }


     
   }

   


}

$RimplenetRules = new RimplenetRules();

function rimplenetRulesExecuted($rule,$user,$obj_id='',$args=''){
    
        $rimplenet_execute_obj = new RimplenetRules();
        $execution_status =  $rimplenet_execute_obj->RimplenetRuleExecutionStatus($rule, $user, $obj_id, $args);
        
        
       
        $rule_array = explode(":", trim($rule));
        
        $repetition_mode_str = trim(end($rule_array));
        $repetition_mode_arr = explode(",",$repetition_mode_str);
        $repetition_mode_arr = array_map('trim', $repetition_mode_arr);
        
        $repeat_mode = $repetition_mode_arr[0];
        $execution_interval = $repetition_mode_arr[1];
        $no_of_execution_required_for_completion = $repetition_mode_arr[2];
        $interval_timer = $repetition_mode_arr[3];
        
        
        $wp_interval_schedules = wp_get_schedules();
        $execution_interval_ts = $wp_interval_schedules[$execution_interval]['interval'];
            
        if(empty($execution_status)){
            $rimplenet_title = 'RIMPLENET Rules Execution for '.$user->user_login.' - RULE : '.$rule;
            $rimplenet_desc = $rimplenet_title;
            $rule_post_args = array(
                'post_author'=> $user->ID,
    		    'post_title' => $rimplenet_title,
    		    'post_content' => $rimplenet_desc,
    		    'post_status' => 'pending',
    		    'post_type' => "rimplenettransaction",
    		);
    		
    		
    
            $rimplenet_id = wp_insert_post( $rule_post_args );
            wp_set_object_terms($rimplenet_id, 'RIMPLENET RULES EXECUTION', 'rimplenettransaction_type' );
            
            update_post_meta($rimplenet_id , 'rule', $rule);
            
            $args_array = array_map('trim', $args);
            update_post_meta($rimplenet_id , 'rule_args', $args_array);
            update_post_meta($rimplenet_id , 'rule_args_json', json_encode ($args_array));
            
            if(empty($obj_id)){
              update_post_meta($rimplenet_id , 'obj_id', 'RIMPLENET_EMPTY_VALUE');
            }
            else{ 
              update_post_meta($rimplenet_id , 'obj_id', $obj_id);
            }

            add_post_meta($rimplenet_id , 'rule_execution_time', time());

            if (!empty($interval_timer) AND is_numeric($interval_timer)) {

              add_post_meta($rimplenet_id , 'first_execution_timer', $interval_timer);
              add_post_meta($rimplenet_id , 'rule_execution_timer', $interval_timer);
            }
            else{

              add_post_meta($rimplenet_id , 'first_execution_timer', 'RIMPLENET_EMPTY_VALUE');
              add_post_meta($rimplenet_id , 'rule_execution_timer', time());
            }
        }
        
        elseif(is_numeric($execution_status)){
            $rimplenet_id = $execution_status;

            $rule_execution_timer = end(get_post_meta($rimplenet_id, 'rule_execution_timer')) + get_post_meta($rimplenet_id, 'execution_interval_ts',true);

            add_post_meta($rimplenet_id , 'rule_execution_timer', $rule_execution_timer);

            add_post_meta($rimplenet_id , 'rule_execution_time', time());
        }
        
        
        
        if($repeat_mode=='repeat'){
            
            update_post_meta($rimplenet_id , 'execution_mode', 'repeat');

            
            update_post_meta($rimplenet_id , 'no_of_execution_required_for_completion', $no_of_execution_required_for_completion);
            
            $total_execution_time = get_post_meta($rimplenet_id, 'rule_execution_time');
            
            
            update_post_meta($rimplenet_id , 'execution_interval', $execution_interval);
            update_post_meta($rimplenet_id , 'execution_interval_ts', $execution_interval_ts);
            
            if(count($total_execution_time)>=$no_of_execution_required_for_completion){
                wp_publish_post( $rimplenet_id );
            }
            
        }
        else{
            wp_publish_post( $rimplenet_id );
            update_post_meta($rimplenet_id , 'execution_mode', 'once');
         }
        
        
}