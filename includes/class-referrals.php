<?php

class Rimplenet_Referrals extends RimplenetRules{
 
   
  
public function __construct() {
    

 add_action( 'init', array($this,'run_rimplenet_rules_to_user_when_their_downline_makes_woo_order'), 25, 0 ); 
 /**
 * If Woocommerce is activated
 */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
    //When 
  add_action( 'woocommerce_register_form', array($this,'rimplenet_woocommerce_referrals_register_fields'), 20 );  
  add_action( 'woocommerce_register_post', array($this,'rimplenet_validate_woocommerce_referrals_register_fields'), 10, 3 );
  add_action( 'woocommerce_created_customer', array($this,'rimplenet_save_woocommerce_referrals_register_fields') );
 
}

 add_action( 'show_user_profile', array($this,'user_referral_profile_fields' ));
 add_action( 'edit_user_profile', array($this,'user_referral_profile_fields' ));
 add_action( 'personal_options_update', array($this,'save_user_referral_profile_fields' ));
 add_action( 'edit_user_profile_update', array($this,'save_user_referral_profile_fields' ));


}

 public function run_rimplenet_rules_to_user_when_their_downline_makes_woo_order()
   {
         
//Run Rules to_user_when_their_downline_makes_woo_order
$rimplenet_rules_to_user_when_their_downline_makes_woo_order = get_option( 'rimplenet_rules_to_user_when_their_downline_makes_woo_order','');

$woo_order_max_instance = get_option( 'rimplenet_rules_to_user_when_their_downline_makes_woo_order_instance','1');

if(!empty($rimplenet_rules_to_user_when_their_downline_makes_woo_order)){
    
    $statuses = ['completed','processing'];
    $orders = wc_get_orders( ['limit' => -1, 'status' => $statuses] );
    foreach($orders as $order){
        $order_id = $order->get_id();;
        
        $customer_user = get_user_by( 'ID', $order->get_user_id() );
        $user_id = $order->get_user_id();
        $referrer = get_user_meta($customer_user->ID, 'rimplenet_referrer_sponsor',true);
        $user_referrer =  get_user_by( 'login', $referrer) ;
        
        $exec_status = get_post_meta($order_id,'rules_to_referrer_when_their_downline_makes_woo_order_executed',true);
        
        $rules_exec_customers = get_user_meta($user_referrer->ID, 'rimplenet_referrals_rules_executed_on_woo_orders_from_user');//get
        if(is_array($rules_exec_customers)){
        $current_order_user_count = array_count_values($rules_exec_customers)[$customer_user->ID];
        }
        $referrals_rules_completed_executed_users = get_user_meta($user_referrer->ID, 'completed_rimplenet_referred_rules_instance_on_woo_orders_from_user');//get
       
       if(is_array($referrals_rules_completed_executed_users)){
        if(!in_array($user_id, $referrals_rules_completed_executed_users)AND $exec_status!='yes' AND isset($user_referrer->ID) AND $user_referrer->ID!=false){
            $rules = $rimplenet_rules_to_user_when_their_downline_makes_woo_order;
          if($woo_order_max_instance>=1 AND $current_order_user_count<$woo_order_max_instance){//when a limit greater or equal to 1 is specified
            $run_rules="yes";
          }
          elseif($woo_order_max_instance<1 OR !is_numeric($woo_order_max_instance)){//when specified to be unlimited
             $run_rules="yes"; 
          }
          
          if($run_rules=="yes"){
            $linked_gen_obj = 'my_referrals_woo_order_'.$order_id;
            if($this->evalRimplenetRules($rules, $user_referrer, $linked_gen_obj)===true) {
            
            
             return $this->rules_to_user_when_their_downline_makes_woo_orderExecuted($customer_user->ID, $user_referrer->ID, $order_id );
              
            } 
          }
         }
        } 
      }
     }
       
   }
   


   
  function rules_to_user_when_their_downline_makes_woo_orderExecuted($user_id,$referrer_sponsor_id,$order_id)
   {
     $user = get_userdata($user_id );
     if ( $user === false ) {
          return 'RIMPLENET_ERROR_USER_DOES_NOT_EXIST';
     }
     else{
    
         add_post_meta($order_id, 'rules_to_referrer_when_their_downline_makes_woo_order_executed', 'yes');
         
         add_post_meta($order_id, 'rules_executed_on_user',$referrer_sponsor_id);
         $woo_order_max_instance = get_option( 'rimplenet_rules_to_user_when_their_downline_makes_woo_order_instance','1');
         add_post_meta($order_id, 'referral_rules_woo_order_instance',$woo_order_max_instance);
         
         
         add_user_meta($referrer_sponsor_id, 'rimplenet_referrals_rules_executed_on_woo_orders_from_user',$user_id);
         add_user_meta($user_id, 'rimplenet_referrer_rules_executed_on_woo_orders_on_user', $referrer_sponsor_id);
         
         
         $rules_exec_customers = get_user_meta($referrer_sponsor_id, 'rimplenet_referrals_rules_executed_on_woo_orders_from_user');//get
        $current_order_user_count = array_count_values($rules_exec_customers)[$user_id]; 
        add_post_meta($order_id, 'current_instance_count_on_order',$current_order_user_count+1);
            
         if($current_order_user_count==$woo_order_max_instance){
              add_user_meta($referrer_sponsor_id , 'completed_rimplenet_referred_rules_instance_on_woo_orders_from_user',$user_id);
              add_user_meta($user_id, 'completed_rimplenet_referred_rules_instance_on_woo_orders_on_user', $referrer_sponsor_id);
            }
              

         return true;
     }
   }


 

function rimplenet_woocommerce_referrals_register_fields(){
  
  $rimplenet_referrer_sponsor = sanitize_text_field($_COOKIE['rimplenet_referrer_sponsor']);
 
  woocommerce_form_field(
    'rimplenet_referrer_sponsor',
    array(
      'type'        => 'text',
      'default' => $rimplenet_referrer_sponsor,
      'placeholder' => $rimplenet_referrer_sponsor,
      'required'    => false, // just adds an "*"
      'label'       => 'My Referrer Sponsor'
    ),
    ( isset($rimplenet_referrer_sponsor) ? $rimplenet_referrer_sponsor : '' )
  );
 
}
  
  

 
function rimplenet_validate_woocommerce_referrals_register_fields( $username, $email, $errors ) {
  $user = sanitize_text_field($_POST['rimplenet_referrer_sponsor']);

  
  $user_by_name = get_user_by('login',$user);
  $user_by_id = get_user_by('ID',$user);
 
  if ( !empty( $_POST['rimplenet_referrer_sponsor'] ) ) {


     if ( empty($user_by_id->ID) AND empty($user_by_name->ID)) {

    $errors->add( 'rimplenet_referrer_sponsor', 'The Username or User ID provided in the Referral Field does not exist, input another user or leave field empty' );

    }


  }
  
 
}
/**
   * Function below checks if the Ref field is set 
   *
   * @since    1.0.0
   */ 
function rimplenet_save_woocommerce_referrals_register_fields( $customer_id ){
 
if ( isset( $_POST['rimplenet_referrer_sponsor'] ) ) {

  $up_user = get_user_by('login', sanitize_text_field($_POST['rimplenet_referrer_sponsor']));
  if (empty($up_user->ID)) {
    $up_user = get_user_by('ID',sanitize_text_field($_POST['rimplenet_referrer_sponsor']));
  }
  
  if (!empty($up_user->ID)) {
    update_user_meta( $customer_id, 'rimplenet_referrer_sponsor', $up_user->ID) ;
  }
  
  
  }
 
}



function user_referral_profile_fields( $user ) { ?>
    <h3><?php _e("RIMPLENET Referrer Information", "rimplenet"); ?></h3>

    <table class="form-table">
    <?php
     

      $key_rimplenet_referrer_sponsor = 'rimplenet_referrer_sponsor';
      $name_rimplenet_referrer_sponsor = __('RIMPLENET Referrer or Sponsor');

      ?>
      <tr><td colspan="3"><hr></td></tr>
       

       <tr>
        <th><label for="<?php echo $key_rimplenet_referrer_sponsor; ?>"><?php _e($name_rimplenet_referrer_sponsor); ?></label></th>
        <td>
            <input type="text" name="<?php echo $key_rimplenet_referrer_sponsor; ?>" id="<?php echo $key_rimplenet_referrer_sponsor; ?>" value="<?php echo esc_attr( get_the_author_meta( $key_rimplenet_referrer_sponsor, $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e($name_rimplenet_referrer_sponsor); ?></span>
        </td>
       </tr>


    
    <tr><td colspan="3"><hr></td></tr>
   </table>
<?php }


function save_user_referral_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
   

      $key_rimplenet_referrer_sponsor = 'rimplenet_referrer_sponsor';


      if (isset($_POST[$key_rimplenet_referrer_sponsor])) {
        update_user_meta( $user_id, $key_rimplenet_referrer_sponsor , sanitize_text_field($_POST[$key_rimplenet_referrer_sponsor]) ); 
      }


    }



}


$Rimplenet_Wallets = new Rimplenet_Referrals();

?>