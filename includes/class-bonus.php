<?php



class RimplenetMLMBonus extends Rimplenet_Wallets{
 
     
  public function __construct() {

         add_action('init',array($this,'add_meta_completion_bonus'),26, 0);
         
         add_action( 'added_post_meta', array($this,'add_user_matrix_completion_bonus'), 10, 4 );
         
       

   }
   
  function add_meta_completion_bonus(){
      
    $matrix_obj = new RimplenetMlmMatrix();
    
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    $matrix_id_array = $matrix_obj->getMLMMatrix();


    foreach ($matrix_id_array as $mtx_id) {

      $matrix_subs_with_bonus = get_post_meta($mtx_id,'user_with_complete_matrix_downline');
      
      if ($matrix_obj->getMatrixCapacityFilledStatus($mtx_id,$user_id) AND !in_array($user_id,  $matrix_subs_with_bonus) ) {
        
        add_post_meta($mtx_id, 'user_with_complete_matrix_downline', $user_id); //This line will call $this->add_user_matrix_completion_bonus as hook
      }
    }
       
       
 
  }
  


  public function add_user_matrix_completion_bonus($meta_id, $post_id, $meta_key, $meta_value)
   {
       
    if ( $meta_key=='user_with_complete_matrix_downline'  ) {
        
        $user_id = $meta_value;
        $completion_bonus = get_post_meta($post_id, 'matrix_completion_bonus', true);
        $wallet_id = 'woocommerce_base_cur';
        $note = "Completion Bonus for Matrix - #".$post_id;
        
        $wallet_obj1 = new Rimplenet_Wallets();
        $wallet_obj1->add_user_mature_funds_to_wallet($user_id, $completion_bonus, $wallet_id, $note);
        
    }
     
  }

   


}

$RimplenetMLMBonus = new RimplenetMLMBonus();