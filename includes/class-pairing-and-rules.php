<?php

class Rimplenet_Pairing_and_Rules extends RimplenetRules{
  
     
  public function __construct() {
     
     add_action('add_decode_rimplenet_rules', array($this,'rimplenet_rules_check_if_user_pairing_status_is'), 25, 4 );
     add_action('add_decode_rimplenet_rules', array($this,'rimplenet_rules_check_if_user_downlines_in_pairing_is'), 25, 4 );
     add_action('add_decode_rimplenet_rules', array($this,'rimplenet_rules_check_if_user_is_comfirmed_in_pairing_by_upline'), 25, 4 );
     
     add_action('user_pairing_downline_info', array($this,'rimplenet_rules_check_if_user_downlines_in_pairing_is'), 25, 4 );
     
     
    add_shortcode('rimplenet_request_pairing', array($this, 'RimplenetRequestPairing'));
    add_shortcode('user_pairing_dl_upl_info', array($this, 'RimplenetPairingDownlineUplineInfo'));
     add_shortcode('user_pairing_upline_info', array($this, 'RimplenetPairingUplineInfo'));
    add_shortcode('user_pairing_downline_info', array($this, 'RimplenetPairingDownlineInfo'));
     
     add_action( 'init', array($this,'update_user_pairing_and_run_rules'), 25, 0 );  
     
    add_shortcode('rimplenet-pairing-info', array($this, 'RimplenetPairingInfo'));
     if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){

     add_action('woocommerce_order_status_processing', array($this,'woo_order_update_user_pairing') );
      
     }
     else{
      //Add Error Message
     }

       
   }
   
    public function RimplenetRequestPairing($atts) {
	        

	    ob_start();
	     global $current_user,$wpdb, $post,  $wp;
        wp_get_current_user();

        $atts = shortcode_atts( array(
        
            'pairing_id' => 'empty',
            'user_pos' => 'empty',
            'before_pair_display' => 'PAIR ME',
            'after_pair_display' => 'PAIRING REQUEST SUBMITTED',
            'user_id' => $current_user->ID,
        
        ), $atts);
        
        
        $pairing_id = $atts['pairing_id'];
        $user_pos = $atts['user_pos'];
        $before_pair_display = $atts['before_pair_display'];
        $after_pair_display = $atts['after_pair_display'];
        $user_id = $atts['user_id'];
        if (isset($_GET['rimplenet-view-pairing'])) {
        $mlm_pairing_post = get_post(sanitize_text_field($_GET['rimplenet-view-pairing']));
        }
        else{
        $mlm_pairing_post = get_post($atts['pairing_id']);
        }
        
        if(isset($_GET['rimplenet-user-id']) AND current_user_can('manage_options' ) ){
            
         $user_id = sanitize_text_field($_GET['rimplenet-user-id']);
         
        }
        
        if (!empty($mlm_pairing_post->ID)) {
        
        
        $width = $mlm_pairing_post->width;
        $depth = $mlm_pairing_post->depth;
        
        
        $pairing_id = $mlm_pairing_post->ID;
        
        $all_pairing_requester_arr = get_post_meta($pairing_id, 'pairing_requester');
        $all_pairing_subs_arr = get_post_meta($pairing_id, 'pairing_subscriber');
        $all_pairing_completed_subs_arr = get_post_meta($pairing_id, 'pairing_completers');
        
        }
         
        $rimplenetPairing = new RimplenetMlmPairing();
 
        
       $array_user_UPL = $rimplenetPairing->getSubscribersUpline($pairing_id, $user_id);
       $c_pairing_id = sanitize_text_field($_GET['pairing_id']);
       $c_user_id = sanitize_text_field($_GET['req_user_id']);
       
       
       if(!in_array($user_id,$all_pairing_requester_arr) AND wp_verify_nonce($_GET['action_nonce'], 'action_nonce') AND isset($c_pairing_id) AND isset($c_user_id) AND $_GET['action']=='request_rimplenet_pairing_to_upline'){
           
           update_post_meta($c_pairing_id, 'pairing_requester', $user_id );
           
           $c_key_time = 'time_user_'.$user_id.'_requested_pairing';
           update_post_meta($c_pairing_id, $c_key_time, time() );
       }
       
        $all_pairing_requester_arr = get_post_meta($pairing_id, 'pairing_requester');
       if(in_array($user_id,$all_pairing_requester_arr)){
           
           echo $after_pair_display;
       }
       else{
        
       $action_nonce = wp_create_nonce('action_nonce');
       $req_pairing_url = add_query_arg( array( 'action'=>'request_rimplenet_pairing_to_upline','pairing_id'=>$pairing_id,'req_user_id'=>$user_id,'action_nonce'=>$action_nonce), home_url(add_query_arg(array(),$wp->request)) );
       
       echo '<a href="'.$req_pairing_url.'"> <input class="rimplenet-button rimplenet-request-pairing" id="rimplenet-" type="submit" value="'.$before_pair_display.'"> </a>';
       
       }
	     
	    $output = ob_get_clean();

	    return $output;
	  
     }
    public function RimplenetPairingUplineInfo($atts) {
	        

	    ob_start();
	     global $current_user,$wpdb, $post,  $wp;
        wp_get_current_user();

        $atts = shortcode_atts( array(
        
            'pairing_id' => 'empty',
            'user_pos' => 'empty',
            'info_when_not_paired_to_upline' => 'jjm ',
            'confirm_pairer_info_shown_to_downline' => 'empty',
            'after_confirm_pairer_info_shown_to_downline' => 'empty',
            'confirm_pairer_info_shown_to_upline' => 'empty',
            'after_confirm_pairer_info_shown_to_upline' => 'empty',
            'user_id' => $current_user->ID,
        
        ), $atts);
        
        
        $pairing_id = $atts['pairing_id'];
        $user_pos = $atts['user_pos'];
        $info_when_not_paired_to_upline = $atts['info_when_not_paired_to_upline'];
        $confirm_pairer_info_shown_to_downline = $atts['confirm_pairer_info_shown_to_downline'];
        $after_confirm_pairer_info_shown_to_downline = $atts['after_confirm_pairer_info_shown_to_downline'];
        $confirm_pairer_info_shown_to_upline = $atts['confirm_pairer_info_shown_to_upline'];
        $after_confirm_pairer_info_shown_to_upline = $atts['after_confirm_pairer_info_shown_to_upline'];
        $user_id = $atts['user_id'];
        if (isset($_GET['rimplenet-view-pairing'])) {
        $mlm_pairing_post = get_post(sanitize_text_field($_GET['rimplenet-view-pairing']));
        }
        else{
        $mlm_pairing_post = get_post($atts['pairing_id']);
        }
        
        if(isset($_GET['rimplenet-user-id']) AND current_user_can('manage_options' ) ){
            
         $user_id = sanitize_text_field($_GET['rimplenet-user-id']);
         
        }
        
        if (!empty($mlm_pairing_post->ID)) {
        
        
        $width = $mlm_pairing_post->width;
        $depth = $mlm_pairing_post->depth;
        
        
        $pairing_id = $mlm_pairing_post->ID;
        
        $all_pairing_subs_arr = get_post_meta($pairing_id, 'pairing_subscriber');
        $all_pairing_completed_subs_arr = get_post_meta($pairing_id, 'pairing_completers');
        
        }
         
        $rimplenetPairing = new RimplenetMlmPairing();
 
        
        $array_user_UPL = $rimplenetPairing->getSubscribersUpline($pairing_id, $user_id);
        
            
        if(!in_array($user_id,$all_pairing_subs_arr)){
            return ;
        }
        
        if($user_pos=='empty'){
            if(empty($array_user_UPL)){
               echo $info_when_not_paired_to_upline;
            }
            else{
            if(!is_array($array_user_UPL)){
               $array_user_UPL = array($array_user_UPL);
            }
            
            
           for ($i=0; $i <$depth ; $i++) {
    
           	$user_pos1 = $i+1;
    
           echo '<br><strong> USER '.$user_pos1.'</strong>'; 
           	if (isset($array_user_UPL[$i])) {
           		$paired_user =get_user_by('id', $array_user_UPL[$i]);
    
               echo '<br><span> Username: </span> <strong>'.$paired_user->user_login.'</strong>'; 
               if($confirm_my_downline_text!='empty'){
                   echo '<br>';
                   $c_pairing_id = sanitize_text_field($_GET['pairing_id']);
                   $c_user_id = sanitize_text_field($_GET['user_id']);
                   $c_key = 'user_'.$c_user_id.'_confirmed_in_pairing_by_downline';
                   
                   if(wp_verify_nonce($_GET['action_nonce'], 'action_nonce') AND isset($c_pairing_id) AND isset($c_user_id) AND $_GET['action']=='confirm_user_in_pairing_by_downline'){
                       
                       update_post_meta($c_pairing_id, $c_key, $user_id );
                       
                       $c_key_time = 'time_'.$c_key.'_'.$user_id;
                       update_post_meta($c_pairing_id, $c_key_time, time() );
                   }
                   
                   if(get_post_meta($c_pairing_id, $c_key,true )==$user_id){
                       
                       echo $after_confirm_pairer_info_shown_to_downline;
                   }
                   else{
                       
                       echo '<span> Email: </span> <strong>'.$paired_user->user_email.'</strong>'; 
                       echo '<br><span> Phone No: </span> <strong>'.$paired_user->rimplenet_user_phone_number.'</strong>';
                       echo '<br><span> BTC Address: </span> <strong>'.$paired_user->btc_address.'</strong>';
               
                       $action_nonce = wp_create_nonce('action_nonce');
                       $confirm_me_url = add_query_arg( array( 'action'=>'confirm_user_in_pairing_by_downline','pairing_id'=>$pairing_id,'user_id'=>$paired_user->ID,'action_nonce'=>$action_nonce), home_url(add_query_arg(array(),$wp->request)) );
                       
                       echo '<br><a href="'.$confirm_me_url.'"> <input class="rimplenet-button rimplenet-pairing-confirm" id="rimplenet-" type="submit" value="'.$confirm_pairer_info_shown_to_downline.'"> </a>';
                       
                   }
                   
               }
    
           ?> 
    
    
           <?php
           		}
           		else{
    
           		echo __('<br>'.$info_when_not_paired_to_upline);
           	   }
           	  echo '<br>';
           	}
          }
        }
        else{
        }
	    
	     
	    $output = ob_get_clean();

	    return $output;
	  
     }
    
    public function RimplenetPairingDownlineInfo($atts) {
	        

	    ob_start();
	     global $current_user,$wpdb, $post,  $wp;
        wp_get_current_user();

        $atts = shortcode_atts( array(
        
            'pairing_id' => 'empty',
            'user_pos' => 'empty',
            'confirm_my_downline_text' => 'empty',
            'user_id' => $current_user->ID,
        
        ), $atts);
        
        
        $pairing_id = $atts['pairing_id'];
        $user_pos = $atts['user_pos'];
        $confirm_my_downline_text = $atts['confirm_my_downline_text'];
        $user_id = $atts['user_id'];
        if (isset($_GET['rimplenet-view-pairing'])) {
        $mlm_pairing_post = get_post(sanitize_text_field($_GET['rimplenet-view-pairing']));
        }
        else{
        $mlm_pairing_post = get_post($atts['pairing_id']);
        }
        
        if(isset($_GET['rimplenet-user-id']) AND current_user_can('manage_options' ) ){
            
         $user_id = sanitize_text_field($_GET['rimplenet-user-id']);
         
        }
        
        if (!empty($mlm_pairing_post->ID)) {
        
        
        $width = $mlm_pairing_post->width;
        $depth = $mlm_pairing_post->depth;
        
        
        $pairing_id = $mlm_pairing_post->ID;
        
        $all_pairing_subs_arr = get_post_meta($pairing_id, 'pairing_subscriber');
        $all_pairing_completed_subs_arr = get_post_meta($pairing_id, 'pairing_completers');
        
        }
         
        $rimplenetPairing = new RimplenetMlmPairing();
 
       $array_user_DL = $rimplenetPairing->getPairersIncoming($pairing_id, $user_id);
       $array_user_UPL = $rimplenetPairing->getSubscribersUpline($pairing_id, $user_id);
       
       $c_pairing_id = $pairing_id;
       $c_user_id = $paired_user->ID;
       $c_key = 'user_'.$user_id.'_confirmed_in_pairing_by_upline';
           
        if(get_post_meta($c_pairing_id, $c_key, true )!=$array_user_UPL){
            return ;
            
                
        if(get_post_meta($c_pairing_id, $c_key, true )!=$array_user_UPL){
            return ;
        }
        
        }
        
        if($user_pos=='empty'){
            
           for ($i=0; $i <$width ; $i++) {
    
           	$user_pos1 = $i+1;
    
           echo '<br><strong> USER '.$user_pos1.'</strong>'; 
           	if (isset($array_user_DL[$i])) {
           		$paired_user =get_user_by('id', $array_user_DL[$i]);
    
               echo '<br><span> Username: </span> <strong>'.$paired_user->user_login.'</strong>'; 
               if($confirm_my_downline_text!='empty'){
                   echo '<br>';
                   $c_pairing_id = $pairing_id;
                   $c_user_id = $paired_user->ID;
                   $c_key = 'user_'.$c_user_id.'_confirmed_in_pairing_by_upline';
                   
                   if(get_post_meta($c_pairing_id, $c_key, true )==$user_id){
                       
                       echo "This User has been confirmed by you";
                   }
                   elseif(wp_verify_nonce($_GET['action_nonce'], 'action_nonce') AND isset($c_pairing_id) AND isset($c_user_id) AND $_GET['action']=='confirm_user_in_pairing_by_upline'){
                       
                    $c_pairing_id = sanitize_text_field($_GET['pairing_id']);
                    $c_user_id = sanitize_text_field($_GET['user_id']);
                    $c_key = 'user_'.$c_user_id.'_confirmed_in_pairing_by_upline';
                       
                       update_post_meta($c_pairing_id, $c_key, $user_id );
                       
                       $c_key_time = 'time_'.$c_key.'_'.$user_id;
                       update_post_meta($c_pairing_id, $c_key_time, time() );
                   }
                   else{
                       
                       echo '<span> Email: </span> <strong>'.$paired_user->user_email.'</strong>'; 
                       echo '<br><span> Phone No: </span> <strong>'.$paired_user->rimplenet_user_phone_number.'</strong>';
                       echo '<br><span> BTC Address: </span> <strong>'.$paired_user->btc_address.'</strong>';
               
                       $action_nonce = wp_create_nonce('action_nonce');
                       $confirm_me_url = add_query_arg( array( 'action'=>'confirm_user_in_pairing_by_upline','pairing_id'=>$pairing_id,'user_id'=>$paired_user->ID,'action_nonce'=>$action_nonce), home_url(add_query_arg(array(),$wp->request)) );
                       
                       echo '<br><a href="'.$confirm_me_url.'"> <input class="rimplenet-button rimplenet-pairing-confirm" id="rimplenet-" type="submit" value="'.$confirm_my_downline_text.'"> </a>';
                       
                   }
                   
               }
    
           ?> 
    
    
           <?php
           		}
           		else{
    
           		echo __('<br>You are yet to be matched to the  User');
           	   }
           	  echo '<br>';
           	}
           	
        }
        else{
        }
	    
	     
	    $output = ob_get_clean();

	    return $output;
	  
     }
     
    public function RimplenetPairingDownlineUplineInfo($atts) {
	        

	    ob_start();
	     global $current_user,$wpdb, $post,  $wp;
        wp_get_current_user();

        $atts = shortcode_atts( array(
        
            'pairing_id' => 'empty',
            'user_pos' => 'empty',
            'confirm_my_downline_text' => 'empty',
            'user_id' => $current_user->ID,
        
        ), $atts);
        
        
        $pairing_id = $atts['pairing_id'];
        $user_pos = $atts['user_pos'];
        $confirm_my_downline_text = $atts['confirm_my_downline_text'];
        $user_id = $atts['user_id'];
        if (isset($_GET['rimplenet-view-pairing'])) {
        $mlm_pairing_post = get_post(sanitize_text_field($_GET['rimplenet-view-pairing']));
        }
        else{
        $mlm_pairing_post = get_post($atts['pairing_id']);
        }
        
        if(isset($_GET['rimplenet-user-id']) AND current_user_can('manage_options' ) ){
            
         $user_id = sanitize_text_field($_GET['rimplenet-user-id']);
         
        }
        
        if (!empty($mlm_pairing_post->ID)) {
        
        
        $width = $mlm_pairing_post->width;
        $depth = $mlm_pairing_post->depth;
        
        
        $pairing_id = $mlm_pairing_post->ID;
        
        $all_pairing_subs_arr = get_post_meta($pairing_id, 'pairing_subscriber');
        $all_pairing_completed_subs_arr = get_post_meta($pairing_id, 'pairing_completers');
        
        }
         
        $rimplenetPairing = new RimplenetMlmPairing();
        $array_user_DL = $rimplenetPairing->getPairersIncoming($pairing_id, $user_id);
        $array_user_UPL = $rimplenetPairing->getSubscribersUpline($pairing_id, $user_id);
        
        
       
	    $output = ob_get_clean();

	    return $output;
	  
     }
    
    public function RimplenetPairingInfo($atts) {
	        

	    ob_start();

	    include plugin_dir_path( __FILE__ ) . 'page-templates/rimplenet-pairing-info-template.php';
	     
	    $output = ob_get_clean();

	    return $output;
	  
     }
   
   
   function rimplenet_rules_check_if_user_is_comfirmed_in_pairing_by_upline($rule,$user, $obj_id, $args)
    {
     
     $status  = trim($args[1]); // can take completed,active, or not_active
     $pairing_id = trim($args[0]);
     if(empty($pairing_id)){ $pairing_id = $obj_id; }
     $user_id = $user->ID;
     
     if(strpos($rule, "rimplenet_rules_check_if_user_is_comfirmed_in_pairing_by_upline") !== false AND !empty($status)  AND !empty($pairing_id) AND !empty($user_id)){
         
        $active_subscribers = get_post_meta($pairing_id, 'pairing_subscriber');
        $completed_subscribers = get_post_meta($pairing_id, 'pairing_completers');
        
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
         // return rimplenetRulesExecuted($rule,$user,$obj_id,$args);
        }
        else{
          return 'RIMPLENET_ERROR_EXECUTING_RULES_OR_OBJ_DOESNT_EXIST';
        }
        
     }
     else{
          return 'RIMPLENET_ERROR_ONE_OR_MORE_REQUIRED_FIELDS_IS_EMPTY';
     }
           
    }
    
   function rimplenet_rules_check_if_user_pairing_status_is($rule,$user, $obj_id, $args)
    {
     
     $status  = trim($args[0]); // can take completed,active, or not_active
     $pairing_id = trim($args[1]);
     if(empty($pairing_id)){ $pairing_id = $obj_id; }
     $user_id = $user->ID;
     
     if(strpos($rule, "rimplenet_rules_check_if_user_pairing_status_is") !== false AND !empty($status)  AND !empty($pairing_id) AND !empty($user_id)){
         
        $active_subscribers = get_post_meta($pairing_id, 'pairing_subscriber');
        $completed_subscribers = get_post_meta($pairing_id, 'pairing_completers');
        
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
 
 
   function rimplenet_rules_check_if_user_downlines_in_pairing_is($rule,$user, $obj_id, $args)
    {
     $user_id = $user->ID;  
     $Matx = new RimplenetMlmPairing(); 
     $user_downline_count  = trim($args[0]); // can take full, 1, 2 or any positive int
     $pairing_id  = trim($args[1]);
     
     if($user_downline_count=='full'){
        $user_downline_count = $Matx->getPairingCapacity($pairing_id);
        $user_downline_count = $user_downline_count - 1;
     }
     
     if(strpos($rule, "rimplenet_rules_check_if_user_downlines_in_pairing_is") !== false AND !empty($user_downline_count)  AND !empty($pairing_id) AND !empty($user_id)){
         
        
        $retrieved_user_downline_count = $Matx->getPairingCapacityUsed($pairing_id,$user_id) - 1;
     
        if($retrieved_user_downline_count>=$user_downline_count){
            $executed = 'yes';
        }
        else{
            return 'RIMPLENET_INFO_YOUR_PAIRING_#'.$pairing_id.'_DOWNLINE_COUNT_IS_'.$retrieved_user_downline_count;
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


 

   public function update_user_pairing_and_run_rules()
   {

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    $pairing_id_array = $this->getMLMPairing();


     foreach ($pairing_id_array as $obj) {
          
          $pairing_subs = get_post_meta($obj,'pairing_subscriber');// active in pairing users 
          $pairing_completers = get_post_meta($obj,'pairing_completers');
          $completed_rules_executed_users = get_post_meta($obj,'completed_rules_executed_users');
      
          //Run Rules before pairing
          $rules = get_post_meta($obj, 'rules_before_pairing_entry', true);
          if (!empty($rules) AND !in_array($user_id, $pairing_subs) ) {  
            $obj_id = 'pairing_'.$obj.'_active';
            if($this->evalRimplenetRules($rules, $current_user, $obj_id)===true){
              $pairing_id = $obj;
              return $this->setUserMLMPairing($user_id, $pairing_id);
             }
          }
          
          //Run Rules When in pairing, before pairing status is changed to completed 
             $rules = get_post_meta($obj, 'rules_inside_pairing', true);
          if (!empty($rules) AND in_array($user_id, $pairing_subs) AND !in_array($user_id, $pairing_completers)) {
             $obj_id = 'pairing_'.$obj.'_before_completed';
             if($this->evalRimplenetRules($rules, $current_user, $obj_id)===true){ 
              $pairing_id = $obj;
              return $this->setUserMLMPairingComplete($user_id, $pairing_id);
             }
          }
          
          //Run Rules after pairing complete
             $rules = get_post_meta($obj, 'rules_after_pairing_complete', true);
          if (!empty($rules) AND in_array($user_id, $pairing_completers) AND !in_array($user_id, $completed_rules_executed_users)) {
             $obj_id = 'pairing_'.$obj.'_after_completed';
             if($this->evalRimplenetRules($rules, $current_user, $obj_id)===true ){
                $pairing_id = $obj;
                return $this->setUsersPairingCompletedRulesExecuted($user_id, $pairing_id);
            }
          }
          
          //Run Rules on linked product ordered for active subs
          $linked_woocommerce_product = get_post_meta($obj,'linked_woocommerce_product',true);
          if(is_numeric($linked_woocommerce_product) AND get_post_type($linked_woocommerce_product )=='product'  AND in_array($user_id, $pairing_subs) AND in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))  ){
             
              $orders = wc_get_orders(array(
                    'customer_id' => get_current_user_id(),
                ));
                
             foreach($orders as $order){
              $rules = get_post_meta($obj, 'rimplenet_rules_inside_pairing_and_linked_product_ordered', true);
              $order_id = $order->get_id();
              $order_status = $order->get_status();
                  
                $items = $order->get_items(); 
                $pairing_id = $obj;
                foreach ( $items as $item_id => $item ) {
                    
                $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
                $product_id = $item->get_product_id();
                $order_quantity = $item->get_quantity(); // Get the item quantity
                
                if (($product_id == $linked_woocommerce_product) AND ($order_status=='processing' OR $order_status=='completed') ) {
                    
                    
                        $key_linked_product_exec = 'linked_product_rules_executed_for_user_'.$user_id.'_on_order';
                        $linked_executed_rules_on_user_for_orders_arr = get_post_meta($pairing_id,  $key_linked_product_exec);
                        
                        $apply_rules_per_woocommerce_order_instance = get_post_meta($pairing_id,  'apply_rules_per_woocommerce_order_instance',true);
                        $apply_rules_per_woocommerce_order_product_quantity_instance = get_post_meta($pairing_id,  'apply_rules_per_woocommerce_order_instance',true);
                      
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
                            
                            
                           $rules = get_post_meta($obj, 'rimplenet_rules_inside_pairing_and_linked_product_ordered', true);
                           for ($x = 1; $x <= $rules_qnt; $x++) {
                             $linked_gen_obj = 'linked_product_ordered_'.$pairing_id.'_'.$order_id.'_'.$x;
                             if($this->evalRimplenetRules($rules, $current_user, $linked_gen_obj)===true ) {
                                 $pairing_id = $obj;
                                 
                                 return $this->setLinkedProductRulesExecuted($user_id, $pairing_id,$order_id);
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
   

   public function setUserMLMPairing($user_id, $pairing_id)
   {

     $user = get_userdata( $user_id );
     if ( $user === false ) {
          return 'RIMPLENET_ERROR_USER_DOES_NOT_EXIST';
      }
     update_user_meta($user_id, 'user_current_pairing', $pairing_id);

     $key_time_user_subscribe = 'time_user_subscribed_to_pairing_'.$pairing_id;
     add_user_meta( $user_id, $key_time_user_subscribe, time() );

     
     //With Placement Parent:Child
     $referral_of_new_user = trim(get_user_meta($user_id,'rimplenet_referrer_sponsor', true));
     $ref_user = get_user_by('login',$referral_of_new_user);
    
     $parent_user_id = $this->getNextAvailableEmptyPairingParent($pairing_id, $ref_user->ID);
     
     
     if(empty( $parent_user_id )){
        $parent_user_id = 0; 
     }
     
     $child_parent_placement = $user_id.':'.$parent_user_id;
     add_post_meta($pairing_id, 'pairing_subscriber_with_placement', $child_parent_placement);
     
     //Record user as a subscriber
     add_post_meta($pairing_id, 'pairing_subscriber', $user_id);
     $key_time_user_subscribe = 'time_user_'.$user_id.'_subscribed_to_pairing';
     add_post_meta($pairing_id, $key_time_user_subscribe, time());

     return true;
   }

  
  
   public function setUserMLMPairingComplete($user_id, $pairing_id)
   {
     $user = get_userdata( $user_id );
     if ( $user === false ) {
          return 'RIMPLENET_ERROR_USER_DOES_NOT_EXIST';
     }
     else{
     $key_time_user_subscribe = 'time_user_completed_pairing_'.$pairing_id;
     add_user_meta( $user_id, $key_time_user_subscribe, time() );

     add_post_meta($pairing_id, 'pairing_completers', $user_id);
     $key_time_user_subscribe = 'time_user_'.$user_id.'_completed_pairing';
     add_post_meta($pairing_id, $key_time_user_subscribe, time());

     return true;
     }
   }
   
      
   
   public function setUsersPairingCompletedRulesExecuted($user_id, $pairing_id)
   {
     $user = get_userdata( $user_id );
     if ( $user === false ) {
          return 'RIMPLENET_ERROR_USER_DOES_NOT_EXIST';
     }
     else{
     $key_time_user_subscribe = 'time_user_completed_rules_executed_pairing_'.$pairing_id;
     add_user_meta( $user_id, $key_time_user_subscribe, time() );

     add_post_meta($pairing_id, 'completed_rules_executed_users', $user_id);
     $key_time_user_subscribe = 'time_user_'.$user_id.'_completed_rules_executed';
     add_post_meta($pairing_id, $key_time_user_subscribe, time());

     return true;
     }
   }
   
   
   
   public function setLinkedProductRulesExecuted($user_id, $pairing_id,$order_id)
   {
     $user = get_userdata( $user_id );
     if ( $user === false ) {
          return 'RIMPLENET_ERROR_USER_DOES_NOT_EXIST';
     }
     else{
    
         $user_key_linked_product_exec = 'linked_product_rules_executed_for_pairing_'.$pairing_id.'_on_order';
         add_user_meta( $user_id,  $user_key_linked_product_exec, time() );
         
         
         $key_linked_product_exec = 'linked_product_rules_executed_for_user_'.$user_id.'_on_order';
         add_post_meta($pairing_id,  $key_linked_product_exec, $order_id);
         
         $key_time_linked_product_exec = 'time_linked_product_rules_executed_for_user_'.$user_id.'_on_order_'.$order_id;
         add_post_meta($pairing_id,  $key_time_linked_product_exec, time());
         update_post_meta($pairing_id, 'user_linked_product_rules_executed', $user_id);
         add_post_meta($pairing_id, 'order_linked_product_rules_executed', $order_id);
         
         add_post_meta($order_id, 'pairing_linked_product_rules_executed', $pairing_id);
         add_post_meta($order_id, 'user_linked_product_rules_executed', $user_id);

         return true;
     }
   }

   public function getNextAvailableEmptyPairingParent($pairing_id, $referral_sponsor_id_of_new_user) {
     
     $Matx = new RimplenetMlmPairing();
     
     $user_id_with_vacant_position = $Matx->getNextPairingVacantPostion($pairing_id, $referral_sponsor_id_of_new_user);
     
     return $user_id_with_vacant_position;
     
   }



  public function getMLMPairing($type='')
  {

    $pairing_id_array = get_posts(
      array(
      'post_type' => 'rimplenettransaction', // get all posts.
      'numberposts'   => -1, // get all posts.
      'tax_query'     => array(
        array(
          'taxonomy' => 'rimplenettransaction_type',
          'field'    => 'name',
          'terms'    => 'RIMPLENET MLM PAIRING',
        ),
        ),
      'fields'        => 'ids', // Only get post IDs
      )
     );
    wp_reset_postdata();

    return $pairing_id_array;
  }
  
  
  public function woo_order_update_user_pairing($order_id){

    $order = wc_get_order( $order_id );
    $user = $order->get_user();
    $user_id = $order->get_user_id();
    $total_price = $order->get_total();
    

   }

   
}


$Rimplenet_Pairing_and_Rules = new Rimplenet_Pairing_and_Rules();



class RimplenetMlmPairing 
{
	
	function __construct()
	{
		
    add_shortcode('rimplenet-draw-mlm-tree', array($this, 'DrawMlmTree'));
    add_action('init', array($this,'update_user_pairing_completion_bonus'), 25, 0 );


	}

   public function update_user_pairing_completion_bonus()
   {

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    $pairing_id_array = $this->getMLMPairing();


    foreach ($pairing_id_array as $obj) {

      $pairing_subs = get_post_meta($obj,'pairing_subscriber');
     

    }

     
   }

   public function DrawMlmTree($atts) {
	        

	    ob_start();

	    include plugin_dir_path( __FILE__ ) . 'layouts/design-mlm-tree.php';
	     
	    $output = ob_get_clean();

	    return $output;
	  
  }


  public function getPairingCapacity($pairing_id='')
  {
    $mlm_pairing_post = get_post($pairing_id);
    $width = $mlm_pairing_post->width;
    $depth = $mlm_pairing_post->depth;
    
    $totalusers = $width;
    $sumtotal = $width;
    for ($i=2; $i <=$depth ; $i++) { 
     $sumtotal = $sumtotal * $width;
     $totalusers = $totalusers + $sumtotal;
    }

    $totalusers = $totalusers+1;


    return $totalusers;
  	
  }

  public function getPairingCapacityUsed($pairing_id='',$user_id='')
  {
    $subscribers = $this->getSubscribersDownlineArr($pairing_id, $user_id);
    $subscribers_count = count($subscribers);

    return $subscribers_count;
  	
  }

  public function getPairingCapacityFilledStatus($pairing_id='',$user_id='')
  {

  	if ($this->getPairingCapacityUsed($pairing_id, $user_id)>=$this->getPairingCapacity($pairing_id)) {
  		return true;
  	}
  	else{
  		return false;

  	}
  	
  }


  public function getUsersDueforPairingCompletionBonus($pairing_id='')
  {
    $subscribers = get_post_meta($pairing_id, 'pairing_subscriber');
    $subscribers_count = count($subscribers);

    
  	
  }
  
function getFullDummySubsArr($pairing_id){
    $arr1 = array();
    
    $pairing_max_cap = $this->getPairingCapacity($pairing_id);
    $subscribers = range(1,$pairing_max_cap);
    
    $width = get_post_meta($pairing_id, 'width',true);
     
     
     
    $total_downline_subs =  $pairing_max_cap - 1;
    $limit = $total_downline_subs/$width;
    
    $arr1[$subscribers[0]] = NULL;
    $offset = 1;
    
    for ($parent=1; $parent <= $limit ; $parent++) {
    
        $intSubs = array_slice($subscribers, $offset, $width);
    
        foreach ($intSubs as $key => $sub) {
            $arr1[$sub] = $subscribers[$parent-1];
        }
       $offset = ($width * $parent)+1;
    
        
    }
    
    return $arr1;

} 

function getFullDummySubscribersArr($pairing_id){
    $arr1 = array();
    
    $pairing_max_cap = $this->getPairingCapacity($pairing_id);
    $subscribers = range(1,$pairing_max_cap);
    
    $width = get_post_meta($pairing_id, 'width',true);
     
     
    $total_downline_subs =  $pairing_max_cap - 1;
    $limit = $total_downline_subs/$width;
    
    $arr1[$subscribers[0]*-1] = NULL;
    $offset = 1;
    
    for ($parent=1; $parent <= $limit ; $parent++) {
        
        $intSubs = array_slice($subscribers, $offset, $width);
    
        foreach ($intSubs as $key => $sub) {
            $sub_neg = $sub * -1; //transform sub in negative
            $arr1[$sub_neg]  = $subscribers[$parent-1] * -1; //transform value in negative
        }
       $offset = ($width * $parent)+1;
    
        
    }
    
    
    return $arr1;

}

function getFullDummyandFullRealSubsArr($pairing_id,$user_id){
   
     $pairing_max_capacity = $this->getPairingCapacity($pairing_id);
     
     $array_user_DL = $this->getSubscribersDownlineArr($pairing_id, $user_id);
     $FullDummySubs_Arr = $this->getFullDummySubscribersArr($pairing_id);
     $width = get_post_meta($pairing_id, 'width',true);
     
    if(count($array_user_DL)<$pairing_max_capacity){
        //Remove last values from Arr
     $all_parent_arr_values = array_values(array_keys($array_user_DL));
     $arr = $this->RECselectDownlineDummyandRealArr($pairing_id, $all_parent_arr_values, $array_user_DL);
     
    }
    else{
     $arr = $this->getSubscribersDownlineArr($pairing_id, $user_id); 
    }
    
    
    return $arr;

} 

public function RECselectDownlineDummyandRealArr($pairing_id, $AllParentArrValues, $generatedArrDL, $counter=1){
      
      $count_parent_arr = array_count_values($generatedArrDL);
      $mlm_pairing_post = get_post($pairing_id);
      
      $width = $mlm_pairing_post->width;
      $depth = $mlm_pairing_post->depth;
    
      $max_capacity = $this->getPairingCapacity($pairing_id);
      
      $limit = ($max_capacity-1)/$width;
     if(count($generatedArrDL)>=$max_capacity){
        return $generatedArrDL;
     }
    foreach($AllParentArrValues as $parent_id){
        
        $parent_occurence = $count_parent_arr[$parent_id]; //check how many children has this parent 
        if(empty($parent_occurence) ){
            $parent_occurence = 0;
        }
        
        if($parent_occurence<$width){
            $loop_frequency = $width - $parent_occurence;
            for ($x = 1; $x <= $loop_frequency; $x++) {
                
                 $counter_neg = $counter * -1;
                 $generatedArrDL[$counter_neg] =    $parent_id;
                 
                 $counter++;
            }
            
        }
        
        if(count($generatedArrDL)>=$max_capacity){
        return $generatedArrDL;
        }
        
    }
    
    $AllParentArrValues= array_values(array_keys($generatedArrDL));;
    return $this->RECselectDownlineDummyandRealArr($pairing_id, $AllParentArrValues, $generatedArrDL,$counter);
}


function getDepthtoParentinPairing($pairing_id, $child_user_id, $ArrDL ){
      $mlm_pairing_post = get_post($pairing_id);
      
      $width = $mlm_pairing_post->width;
      $depth = $mlm_pairing_post->depth;
      
      $DL_user_parsed  = $this->parsePairingTree($ArrDL)[0];
      return $DL_user_parsed;
}
 
function getPairersIncoming($pairing_id, $user_id,$pointer=1){
    
    
  $subscribers_with_placement = get_post_meta( $pairing_id, 'pairing_subscriber_with_placement');
  $arr_subscribers_child_parent = array();
  foreach ($subscribers_with_placement as $subscriber) {

    $child_parent = explode( ':', $subscriber );

    $parent = $child_parent[1];
    $child = $child_parent[0];
    if ($child==$user_id) {
      $parent = NULL;
    }
    $arr_subscribers_child_parent[$child] = $parent;


  }

    $Allarr = $arr_subscribers_child_parent;
    $selectedArr = array("$user_id"=>NULL);
    
    $ret_Arr = $this->RECselectDownlineArr($pairing_id, $Allarr, $selectedArr);
    
    
    $ret_Arr = array_keys($ret_Arr);
    $ret_Arr = array_slice($ret_Arr,1);
    
    return $ret_Arr; 
    
    
}

function getSubscribersUpline($pairing_id, $user_id,$pointer=1){
    
    
  $subscribers_with_placement = get_post_meta( $pairing_id, 'pairing_subscriber_with_placement');
  $arr_subscribers_child_parent = array();
  foreach ($subscribers_with_placement as $subscriber) {

    $child_parent = explode( ':', $subscriber );

    $parent = $child_parent[1];
    $child = $child_parent[0];
    if ($child==$user_id) {
      return $parent;
    }
   
  }
    
}


function getSubscribersUplineArr($pairing_id, $user_id,$pointer=1){
    
    
  $subscribers_with_placement = get_post_meta( $pairing_id, 'pairing_subscriber_with_placement');
  $arr_subscribers_child_parent = array();
  foreach ($subscribers_with_placement as $subscriber) {

    $child_parent = explode( ':', $subscriber );

    $parent = $child_parent[1];
    $child = $child_parent[0];
    if ($child==$user_id) {
      $parent = NULL;
    }
    $arr_subscribers_child_parent[$child] = $parent;


  }

    $Allarr = $arr_subscribers_child_parent;
    $selectedArr = array("$user_id"=>NULL);
    
    $ret_Arr = $this->RECselectDownlineArr($pairing_id, $Allarr, $selectedArr);
    
    return $ret_Arr; 
    
    
}

function getSubscribersDownlineArr($pairing_id, $user_id,$pointer=1){
    
    
  $subscribers_with_placement = get_post_meta( $pairing_id, 'pairing_subscriber_with_placement');
  $arr_subscribers_child_parent = array();
  foreach ($subscribers_with_placement as $subscriber) {

    $child_parent = explode( ':', $subscriber );

    $parent = $child_parent[1];
    $child = $child_parent[0];
    if ($child==$user_id) {
      $parent = NULL;
    }
    $arr_subscribers_child_parent[$child] = $parent;


  }

    $Allarr = $arr_subscribers_child_parent;
    $selectedArr = array("$user_id"=>NULL);
    
    $ret_Arr = $this->RECselectDownlineArr($pairing_id, $Allarr, $selectedArr);
    
    return $ret_Arr; 
    
    
}

public function RECselectDownlineArr($pairing_id, $Allarr, $selectedArr, $pointer=0){
    
      $mlm_pairing_post = get_post($pairing_id);
      
      $width = $mlm_pairing_post->width;
      $depth = $mlm_pairing_post->depth;
    
      $max_capacity = $this->getPairingCapacity($pairing_id);
      
      $limit = ($max_capacity-1)/$width;
     if(count($selectedArr)>=$max_capacity or $pointer>=$depth){
        return $selectedArr;
     }
    
    
    $array_worked = $selectedArr;
    
   
     $array_val = array_keys($array_worked);
     $user_id = $array_val[$pointer]; 
     
   
    
    $DL = array_keys($Allarr,$user_id);
    foreach($DL as $value){
      $selectedArr[$value] =    $user_id;
    }
    
    $pointer++;
    
    return $this->RECselectDownlineArr($pairing_id, $Allarr, $selectedArr,$pointer);
}

  public function parsePairingTree($tree, $root = null) {
    $return = array();
    # Traverse the tree and search for direct children of the root
    foreach($tree as $child => $parent) {
        # A direct child is found
        if($parent == $root) {
            # Remove item from tree (we don't need to traverse this again)
            unset($tree[$child]);
            # Append the child into result array and parse its children
            $return[] = array(
                'parent_user_id' => $parent,
                'user_id' => $child,
                'name' => $child,
                'children' => $this->parsePairingTree($tree, $child)
            );
        }
    }
    return empty($return) ? null : $return;    
  }


  public function drawMLMtreeFromArray($tree) {
      global $wp;
    if(!is_null($tree) && count($tree) > 0) {
        echo '<ul>';
        foreach($tree as $b) {
            //echo var_dump($tree);
            $user = get_user_by('ID',$b['user_id']);
            if(isset($user->ID)){
                
            $link = add_query_arg( array('rimplenet-user-id'=>$b['user_id'],), home_url(add_query_arg(array($_GET),$wp->request)) );
            
            echo '<li id="pairing_node_user_'.$b['user_id'].'"> 
               <a href="'.$link.'">'.$user->user_login.'</a>';
            }
            else{
               echo '<li id="pairing_node_user_'.$b['user_id'].'"> 
               <a href="javascript:void(0)"> Not Available </a>';  
            }
            
               
               $this->drawMLMtreeFromArray($b['children']);
               
            echo '</li>';
        }
        echo '</ul>';
    }
    
  }

  
  public function getNextPairingVacantParentIdfromArray($arr,$pairing_width) {
      
    if(!is_null($arr) && count($arr) > 0) {
        
        foreach($arr as $b) {
            
            echo $b['user_id'].' => '.$b['parent_user_id'].' - '.count($b['children']);
            echo '<br>';
            
            if(count($b['children'])<$pairing_width){
                //return $b['parent_user_id'];
            }
            
               
            $this->getNextPairingVacantParentIdfromArray($b['children'],$pairing_width);
               
        }
        
     }

  }
  
  
  
  
    
  public function parsePairingVacantParentIdfromArray($arr,$pairing_width) {
      
      
    if(!is_null($arr) && count($arr) > 0) {
        
        foreach($arr as $b) {
            
            echo $b['user_id'].' => '.$b['parent_user_id'].' -- '.count($b['children']);
            echo '<br>';
            
            if(count($b['children'])<$pairing_width){
                //return $b['parent_user_id'];
            }
            
               
            $this->parsePairingVacantParentIdfromArray($b['children'],$pairing_width);
               
        }
        
     }


  }
  
  
  

 
  public function getNextPairingVacantPostion($pairing_id, $user_id) {
    
    $user_placement_method_in_pairing = get_post_meta($pairing_id,'user_placement_method_in_pairing',true);
    $pairing_width = get_post_meta($pairing_id,'width',true);
    
    if($user_placement_method_in_pairing=='first_come_first_served'){  
        $array_user_DL = get_post_meta( $pairing_id, 'pairing_subscriber');
        if(empty($array_user_DL)){return 0;}
        foreach($array_user_DL as $single_user_id){
            
           $DL_user_parsed  = $this->getSubscribersDownlineArr($pairing_id, $single_user_id);
           $DL_user_parsed  = $this->parsePairingTree($DL_user_parsed)[0];
           
           $count_DL = count($DL_user_parsed['children']);
           if($count_DL<$pairing_width){
              return $single_user_id; 
           }
           
         }
     }
     else{//placement here is referral_based_during_registration
        $subscribers_with_placement = get_post_meta( $pairing_id, 'pairing_subscriber_with_placement');
        if(empty($subscribers_with_placement)){return 0;}
        
        $array_user_DL = $this->getSubscribersDownlineArr($pairing_id, $user_id);
        foreach($array_user_DL as $DL_single_user_id=>$parent_id){
            
           $DL_user_parsed  = $this->getSubscribersDownlineArr($pairing_id, $DL_single_user_id);
           $DL_user_parsed  = $this->parsePairingTree($DL_user_parsed)[0];
           
           //echo 'UserID:'.$DL_user_parsed['user_id'] . '=>Parent:'.$parent_id.'-- count:'.count($DL_user_parsed['children']);
           //echo '<br><br>';
           
           $count_DL = count($DL_user_parsed['children']);
           
           if($count_DL<$pairing_width){
              return $DL_single_user_id; 
           }
           
         }
     }
     
        
      

    

    

  }



  public function getMLMPairing($type='')
    {

    $packages_id_array = get_posts(
      array(
      'post_type' => 'rimplenettransaction', // get all posts.
      'numberposts'   => -1, // get all posts.
      'tax_query'     => array(
        array(
          'taxonomy' => 'rimplenettransaction_type',
          'field'    => 'name',
          'terms'    => 'RIMPLENET MLM PAIRING',
        ),
        ),
      'fields'        => 'ids', // Only get post IDs
      )
     );

    wp_reset_postdata();
    return $packages_id_array;
    }



}

$RimplenetMlmPairing  = new RimplenetMlmPairing();